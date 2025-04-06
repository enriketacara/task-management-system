<?php



header('Content-Type: application/json');
$rootDir = __DIR__ . '/../../../';
include_once($rootDir . "config/app_config.php");
include_once($rootDir . "config/globals.php");
$dbh = include($rootDir . 'config/connection.php');
include_once($rootDir . "Classes/EmailService.php");
session_start();

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredParams = ['task_id', 'task_title', 'task_description', 'start_hour', 'start_minute', 'end_hour', 'end_minute', 'task_frequency', 'task_type', 'priority'];

    $dbh->beginTransaction();

    $taskId = $_POST['task_id'];
    $frequency = $_POST['frequency']; // 'Hourly', 'Daily', 'Weekly', 'Monthly'
    $hour = $_POST['hour'];
    $minute = $_POST['minute'];
    $weekday = $_POST['weekday'];
    $day = isset($_POST['day']) ? $_POST['day'] : null;  // Only for weekly tasks

    $taskTime = calculateTaskTime($frequency, $hour, $minute, $weekday, $day, $response, false);
    $nextReminderDate = calculateTaskTime($frequency, $hour, $minute, $weekday, $day, $response, true);

    if (!$taskTime) {
        echo json_encode($response);
        exit();
    }

    try {
        // Prepare the task update
        $stmt = $dbh->prepare("UPDATE task_section SET
            task_title = :task_title, 
            task_description = :task_description, 
            progress = :progress,
            cron_start_time = :cron_start_time, 
            cron_end_time = :cron_end_time, 
            saturday = :saturday, 
            sunday = :sunday, 
            frequency = :task_frequency, 
            task_type = :task_type, 
            priority = :priority, 
            updated_at = NOW(),
            task_time = :task_time,
            status = :status,
            next_reminder = :next_reminder
            WHERE id = :task_id");

        // Bind parameters
         // Prepare the data for update
        $stmt->bindParam(':task_title', $_POST['edit_task_title'], PDO::PARAM_STR);
        $stmt->bindParam(':task_description', $_POST['edit_task_des'], PDO::PARAM_STR);
        $stmt->bindValue(':cron_start_time', $_POST['edit_hourPicker_startTime'] . ':' . $_POST['edit_minutePicker_starTime'], PDO::PARAM_STR);
        $stmt->bindValue(':cron_end_time', $_POST['edit_hourPicker_endTime'] . ':' . $_POST['edit_minutePicker_endTime'], PDO::PARAM_STR);
        $stmt->bindParam(':progress', $_POST['edit_progress'], PDO::PARAM_INT);  
        $stmt->bindValue(':saturday', isset($_POST['edit_saturday_cron']) ? 1 : 0, PDO::PARAM_INT);
        $stmt->bindValue(':sunday', isset($_POST['edit_sunday_cron']) ? 1 : 0, PDO::PARAM_INT);
        $stmt->bindParam(':task_frequency', $_POST['edit_time'], PDO::PARAM_STR);
        $stmt->bindParam(':task_type', $_POST['edit_radio'], PDO::PARAM_STR);
        $stmt->bindParam(':priority', $_POST['edit_priority'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $_POST['edit_status'], PDO::PARAM_STR);
        $stmt->bindParam(':task_time', $taskTime, PDO::PARAM_STR);
        $stmt->bindParam(':next_reminder', $nextReminderDate, PDO::PARAM_STR);
        $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
        // Execute the task update
      
        // Execute the task update
        $stmt->execute();

        // Delete existing task_section_users and task_section_groups
        $taskId = $_POST['task_id'];
        
        $dbh->prepare("DELETE FROM task_section_users WHERE task_section_id = :task_id")->execute([':task_id' => $taskId]);
        $dbh->prepare("DELETE FROM task_section_groups WHERE task_section_id = :task_id")->execute([':task_id' => $taskId]);

        // Insert updated assigned users
        if (!empty($_POST['edit_assign_task'])) {
            $stmtUser = $dbh->prepare("INSERT INTO task_section_users (task_section_id, user_id) VALUES (:task_id, :user_id)");
            foreach ($_POST['edit_assign_task'] as $user_id) {
                $stmtUser->bindParam(':task_id', $taskId, PDO::PARAM_INT);
                $stmtUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            
                $stmtUser->execute();
            }
        }

        // Insert updated assigned groups
        if (!empty($_POST['edit_group'])) {
            $stmtGroup = $dbh->prepare("INSERT INTO task_section_groups (task_section_id, group_id) VALUES (:task_id, :group_id)");
            foreach ($_POST['edit_group'] as $group_id) {
                $stmtGroup->bindParam(':task_id', $taskId, PDO::PARAM_INT);
                $stmtGroup->bindParam(':group_id', $group_id, PDO::PARAM_INT);
                $stmtGroup->execute();
            }
        }

        // Commit the transaction
        $dbh->commit();


        // Success response
        $response['success'] = true;
        $response['message'] = 'Task updated successfully!';
    } catch (Exception $e) {
        // Rollback the transaction on error
        $dbh->rollBack();
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} else {
    // Invalid request method
    $response['message'] = 'Invalid request method.';
}

// Output the response in JSON format
echo json_encode($response);


function calculateTaskTime($frequency, $hour, $minute, $weekday, $day = null, &$response, $forNextReminder = false) {
    // Check if the frequency is valid and required fields are not empty
    if ($frequency === 'Hourly' || $frequency === 'Daily') {
        if (empty($hour) || empty($minute)) {
            $response['message'] = 'Hour and minute cannot be empty';
            return false; // Error occurred
        }
    } else if ($frequency === 'Weekly') {
        if (empty($weekday)) {
            $response['message'] = 'Weekday cannot be empty';
            return false; // Error occurred
        }
    } else if ($frequency === 'Monthly') {
        if (empty($hour) || empty($minute) || empty($day)) {
            $response['message'] = 'Day, Hour, and Minute cannot be empty';
            return false; // Error occurred
        }
    }

    $currentDateTime = new DateTime();
    $taskDateTime = clone $currentDateTime;

    // Set the hour and minute
  
    // Handle the frequencies
    switch ($frequency) {
        case 'Hourly':
        case 'Daily':
            $taskDateTime->setTime($hour, $minute, 0);

            if ($currentDateTime > $taskDateTime) {
                $taskDateTime->modify('+1 day');
            }
            break;

        case 'Weekly':
            if (!empty($weekday)) {
                if ($forNextReminder) {
                    // Get the next occurrence of the weekday as a date for next_reminder
                    $taskDateTime->modify("next $weekday");
                } else {
                    // Store the weekday as a string for task_time
                    return $weekday;
                }
            } else {
                $response['message'] = 'Weekday is required for weekly frequency.';
                return false; // Error occurred
            }
            break;

        case 'Monthly':
            $taskDateTime->setTime($hour, $minute, 0);
            $taskDateTime->setDate($taskDateTime->format('Y'), $taskDateTime->format('m'), $day);
            if ($currentDateTime > $taskDateTime) {
                $taskDateTime->modify('+1 month');
            }
            break;

        default:
            $response['message'] = 'Invalid frequency type.';
            return false; // Error occurred
    }

    // Return the formatted datetime string for storage in the database
    return $taskDateTime->format('Y-m-d H:i:s');
}

