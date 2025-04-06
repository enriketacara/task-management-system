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
    // Validate required parameters
    $requiredParams = ['task_title', 'task_description', 'start_hour', 'start_minute', 'end_hour', 'end_minute', 'task_frequency', 'task_type', 'priority'];
    foreach ($requiredParams as $param) {
        if (!isset($_POST[$param])) {
            $response['message'] = 'Missing : ' . $param;
            echo json_encode($response);
            exit();
        }
    }

    // Start a transaction
    $dbh->beginTransaction();

    // Calculate task time based on frequency
    $frequency = $_POST['task_type']; // 'Hourly', 'Daily', 'Weekly', 'Monthly'
    // var_dump( $frequency );
    $hour = $_POST['hour'];
    $minute = $_POST['minute'];
    $weekday = $_POST['weekday'];
    // var_dump($weekday);
    $day = isset($_POST['day']) ? $_POST['day'] : null;  // Only for weekly tasks

    $taskTime = calculateTaskTime($frequency, $hour, $minute, $weekday, $day, $response, false);
    $nextReminderDate = calculateTaskTime($frequency, $hour, $minute, $weekday, $day, $response, true);

    if (!$taskTime) {
        // If calculateTaskTime returned false or error, respond with failure
        echo json_encode($response);
        exit();
    }

    try {
        // Prepare the main task insertion
        $stmt = $dbh->prepare("INSERT INTO task_section (
            task_title, 
            task_description, 
            cron_start_time, 
            cron_end_time, 
            saturday, 
            sunday, 
            frequency, 
            task_type, 
            priority, 
            status, 
            active, 
            created_by, 
            created_at, 
            task_time,
            next_reminder
        ) VALUES (
            :task_title, 
            :task_description, 
            :cron_start_time, 
            :cron_end_time, 
            :saturday, 
            :sunday, 
            :task_frequency, 
            :task_type, 
            :priority, 
            :status, 
            :active, 
            :created_by, 
            NOW(), 
            :task_time,
            :next_reminder
        )");

$startHour = isset($_POST['start_hour']) ? intval($_POST['start_hour']) : 0;
$startMinute = isset($_POST['start_minute']) ? intval($_POST['start_minute']) : 0;
$endHour = isset($_POST['end_hour']) ? intval($_POST['end_hour']) : 0;
$endMinute = isset($_POST['end_minute']) ? intval($_POST['end_minute']) : 0;

// Convert times to minutes since midnight
$startTotalMinutes = $startHour * 60 + $startMinute;
$endTotalMinutes = $endHour * 60 + $endMinute;

// Validate that the start time is earlier than the end time
if ($startTotalMinutes >= $endTotalMinutes) {
    $response['success'] = false;
    $response['message'] = 'Cron start time must be earlier than cron end time.';
    echo json_encode($response);
    exit();
}
        // Bind parameters
        $stmt->bindParam(':task_title', $_POST['task_title'], PDO::PARAM_STR);
        $stmt->bindParam(':task_description', $_POST['task_description'], PDO::PARAM_STR);
        $stmt->bindValue(':cron_start_time', $_POST['start_hour'] . ':' . $_POST['start_minute'], PDO::PARAM_STR);
        $stmt->bindValue(':cron_end_time', $_POST['end_hour'] . ':' . $_POST['end_minute'], PDO::PARAM_STR);
        $stmt->bindValue(':saturday', $_POST['saturday_cron'], PDO::PARAM_INT);
        $stmt->bindValue(':sunday', $_POST['sunday_cron'], PDO::PARAM_INT);
        $stmt->bindParam(':task_frequency', $_POST['task_frequency'], PDO::PARAM_STR);
        $stmt->bindParam(':task_type', $_POST['task_type'], PDO::PARAM_STR);
        $stmt->bindParam(':priority', $_POST['priority'], PDO::PARAM_STR);
        $stmt->bindValue(':status', 'Pending', PDO::PARAM_STR);
        $stmt->bindValue(':active', 1, PDO::PARAM_INT);
        $stmt->bindParam(':created_by', $_SESSION['login_session'], PDO::PARAM_INT);
        $stmt->bindParam(':task_time', $taskTime, PDO::PARAM_STR); // Binding the calculated task time
        $stmt->bindParam(':next_reminder', $nextReminderDate, PDO::PARAM_STR);
        // Execute the main task insertion
        $stmt->execute();
        $taskId = $dbh->lastInsertId();

        // Insert assigned users
        if (!empty($_POST['assign_task'])) {
            $stmtUser = $dbh->prepare("INSERT INTO task_section_users (task_section_id, user_id) VALUES (:task_id, :user_id)");
            foreach ($_POST['assign_task'] as $user_id) {
                $stmtUser->bindParam(':task_id', $taskId, PDO::PARAM_INT);
                $stmtUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmtUser->execute();
            }
        }

        // Insert assigned groups
        if (!empty($_POST['group'])) {
            $stmtGroup = $dbh->prepare("INSERT INTO task_section_groups (task_section_id, group_id) VALUES (:task_id, :group_id)");
            foreach ($_POST['group'] as $group_id) {
                $stmtGroup->bindParam(':task_id', $taskId, PDO::PARAM_INT);
                $stmtGroup->bindParam(':group_id', $group_id, PDO::PARAM_INT);
                $stmtGroup->execute();
            }
        }

        // Send email notifications to assigned users and group members
        $CCs = [];

        // Fetch assigned users' emails
        if (!empty($_POST['assign_task'])) {
            $stmtUser = $dbh->prepare("SELECT email FROM users WHERE userid = :user_id");
            foreach ($_POST['assign_task'] as $user_id) {
                $stmtUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmtUser->execute();
                $userEmail = $stmtUser->fetchColumn();
                if ($userEmail) {
                    $CCs[] = $userEmail;
                }
            }
        }

        // Fetch users in assigned groups
        if (!empty($_POST['group'])) {
            $stmtGroupUsers = $dbh->prepare("
                SELECT users.email FROM users 
                JOIN groups_users ON users.userid = groups_users.user_id 
                WHERE groups_users.group_id IN (" . implode(",", $_POST['group']) . ")
            ");
            $stmtGroupUsers->execute();
            while ($groupUserEmail = $stmtGroupUsers->fetchColumn()) {
                if ($groupUserEmail) {
                    $CCs[] = $groupUserEmail;
                }
            }
        }

        // Remove duplicate email addresses
        $CCs = array_unique($CCs);

        // Generate the email message using the template
        $userEmail = 'alerts@protech.com.al'; // Replace with real email
        $message = generateEmailTemplate($_POST['task_title'], $_POST['task_description'], $_POST['start_hour'] . ':' . $_POST['start_minute'], $_POST['end_hour'] . ':' . $_POST['end_minute'], $_POST['priority']);
        $subject = 'New Task Assigned: ' . $_POST['task_title'];

        // Send email
        EmailService::sendEmail('sendAlert', $userEmail, $CCs, $subject, $message, "0");

        // Commit the transaction
        $dbh->commit();

        // Success response
        $response['success'] = true;
        $response['message'] = 'Task added successfully!';
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

// Helper function to calculate task time

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





function generateEmailTemplate($task_title, $task_description, $cron_start_time, $cron_end_time, $priority) {
    return "
    <html>
    <head>
        <style>
        body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f0f2f5;
            }
            .container {
                max-width: 500px;
                margin: 20px auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border: 1px solid #ddd;
            }
            h2 {
                color: #4CAF50;
                font-size: 20px;
                margin-bottom: 15px;
            }
            p {
                font-size: 14px;
                color: #555555;
            }
            .task-details {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
                font-size: 14px;
            }
            .task-details th, .task-details td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            .task-details th {
                background-color: #f4f4f4;
                color: #333;
                font-weight: bold;
            }
            .footer {
                margin-top: 15px;
                font-size: 12px;
                color: #888888;
            }
            .footer a {
                color: #4CAF50;
                text-decoration: none;
            }
            .footer a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>New Task Assigned: $task_title</h2>
            <p>You have been assigned a new task. Below are the task details:</p>
            <table class='task-details'>
                <tr><td><strong>Title:</strong></td><td>$task_title</td></tr>
                <tr><td><strong>Description:</strong></td><td>$task_description</td></tr>
                <tr><td><strong>Start Time:</strong></td><td>$cron_start_time</td></tr>
                <tr><td><strong>End Time:</strong></td><td>$cron_end_time</td></tr>
                <tr><td><strong>Priority:</strong></td><td>$priority</td></tr>
            </table>
            <p>Make sure to review the task and complete it by the end time.</p>
            <div class='footer'>
                <p>This is an automated message from the Task Management System.</p>
            </div>
        </div>
    </body>
    </html>
    ";
}
?>
