<?php
// header('Content-Type: application/json');
// $rootDir = __DIR__ . '/../../../';
// include_once($rootDir . "config/app_config.php");
// include_once($rootDir . "config/globals.php");
// $dbh = include($rootDir . 'config/connection.php');

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     try {
//         $taskId = isset($_POST['id']) ? intval($_POST['id']) : null;
//         $newStatus = isset($_POST['status']) ? $_POST['status'] : null;

//         if ($taskId === null || $newStatus === null) {
//             throw new Exception('Invalid input.');
//         }

//         $stmt = $dbh->prepare("UPDATE task_section SET status = :status WHERE id = :id");
//         $stmt->bindParam(':status', $newStatus);
//         $stmt->bindParam(':id', $taskId);

//         if ($stmt->execute()) {
//             $response = array('success' => true);
//         } else {
//             throw new Exception('Failed to update status.');
//         }
//     } catch (PDOException $e) {
//         $response = array(
//             'error' => true,
//             'message' => 'Database error: ' . $e->getMessage()
//         );
//     } catch (Exception $e) {
//         $response = array(
//             'error' => true,
//             'message' => $e->getMessage()
//         );
//     }
// } else {
//     $response = array(
//         'success' => false,
//         'message' => 'Invalid request method.'
//     );
// }

// echo json_encode($response);

// header('Content-Type: application/json');
// $rootDir = __DIR__ . '/../../../';
// include_once($rootDir . "config/app_config.php");
// include_once($rootDir . "config/globals.php");
// $dbh = include($rootDir . 'config/connection.php');
// session_start();
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     try {
//         // Fetch the inputs
//         $taskId = isset($_POST['id']) ? intval($_POST['id']) : null;
//         $newStatus = isset($_POST['status']) ? $_POST['status'] : null;
//         $currentUsername = isset($_SESSION['login_session']) ? $_SESSION['login_session'] : 'Unknown User'; // Replace with the actual logged-in user

//         if ($taskId === null || $newStatus === null) {
//             throw new Exception('Invalid input.');
//         }

//         // Fetch the current status of the task before updating
//         $stmt = $dbh->prepare("SELECT status, status_updated_by FROM task_section WHERE id = :id");
//         $stmt->bindParam(':id', $taskId);
//         $stmt->execute();
//         $task = $stmt->fetch(PDO::FETCH_ASSOC);

//         if (!$task) {
//             throw new Exception('Task not found.');
//         }

//         $previousStatus = $task['status']; // Get the current (previous) status

//         // Prepare the status update message for status_last_updated
//         $dateTimeNow = date('m-d-Y h:i:s a'); // Format the current date and time
//         $statusUpdateMessage = "$previousStatus to $newStatus by $currentUsername at $dateTimeNow";

//         // Append the log of status changes to the status_updated_by column (considering it is a string log)
//         $newLogEntry = "<tr><td>$previousStatus</td><td>$newStatus</td><td>$currentUsername</td><td>$dateTimeNow</td></tr>";
//         $updatedLog = $task['status_updated_by'] . $newLogEntry;

//         // Update the task status, status_updated_by, and status_last_updated
//         $stmt = $dbh->prepare("
//             UPDATE task_section 
//             SET status = :status, 
//                 status_last_updated = :status_last_updated, 
//                 status_updated_by = :status_updated_by 
//             WHERE id = :id
//         ");
//         $stmt->bindParam(':status', $newStatus);
//         $stmt->bindParam(':status_last_updated', $statusUpdateMessage);
//         $stmt->bindParam(':status_updated_by', $updatedLog); // Append log
//         $stmt->bindParam(':id', $taskId);

//         if ($stmt->execute()) {
//             $response = array('success' => true);
//         } else {
//             throw new Exception('Failed to update status.');
//         }
//     } catch (PDOException $e) {
//         $response = array(
//             'error' => true,
//             'message' => 'Database error: ' . $e->getMessage()
//         );
//     } catch (Exception $e) {
//         $response = array(
//             'error' => true,
//             'message' => $e->getMessage()
//         );
//     }
// } else {
//     $response = array(
//         'success' => false,
//         'message' => 'Invalid request method.'
//     );
// }

// echo json_encode($response);

?>
<?php
header('Content-Type: application/json');
$rootDir = __DIR__ . '/../../../';
include_once($rootDir . "config/app_config.php");
include_once($rootDir . "config/globals.php");
$dbh = include($rootDir . 'config/connection.php');
include_once($rootDir . "Classes/EmailService.php");// Include your email service class
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Fetch the inputs
        $taskId = isset($_POST['id']) ? intval($_POST['id']) : null;
        $newStatus = isset($_POST['status']) ? $_POST['status'] : null;
        $currentUsername = isset($_SESSION['login_session']) ? $_SESSION['login_session'] : 'Unknown User'; // Replace with the actual logged-in user

        if ($taskId === null || $newStatus === null) {
            throw new Exception('Invalid input.');
        }

        // Fetch the current status of the task before updating
        // Fetch additional task details
$stmt = $dbh->prepare("
SELECT task_title, task_description, task_time, frequency, cron_start_time, cron_end_time, priority, status, status_updated_by 
FROM task_section 
WHERE id = :id
");
$stmt->bindParam(':id', $taskId);
$stmt->execute();
$task = $stmt->fetch(PDO::FETCH_ASSOC);
// Prepare task details for the email
$task_title = $task['task_title'];
$task_description = $task['task_description'];
$task_time = $task['task_time']; // new field
$frequency = $task['frequency']; // new field
$cron_start_time = $task['cron_start_time'];
$cron_end_time = $task['cron_end_time'];
$priority = $task['priority'];



        if (!$task) {
            throw new Exception('Task not found.');
        }

        $previousStatus = $task['status']; // Get the current (previous) status

        // Prepare the status update message for status_last_updated
        $dateTimeNow = date('m-d-Y h:i:s a'); // Format the current date and time
        $statusUpdateMessage = "$previousStatus to $newStatus by $currentUsername at $dateTimeNow";

        // Append the log of status changes to the status_updated_by column (considering it is a string log)
        $newLogEntry = "<tr><td>$previousStatus</td><td>$newStatus</td><td>$currentUsername</td><td>$dateTimeNow</td></tr>";
        $updatedLog = $task['status_updated_by'] . $newLogEntry;

        // Update the task status, status_updated_by, and status_last_updated
        $stmt = $dbh->prepare("
            UPDATE task_section 
            SET status = :status, 
                status_last_updated = :status_last_updated, 
                status_updated_by = :status_updated_by 
            WHERE id = :id
        ");
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':status_last_updated', $statusUpdateMessage);
        $stmt->bindParam(':status_updated_by', $updatedLog); // Append log
        $stmt->bindParam(':id', $taskId);

        if ($stmt->execute()) {
            // Check if the status is now "Completed"
            if ($newStatus === 'Completed') {
                $update_progress_stmt = $dbh->prepare("
                UPDATE task_section 
                SET progress = 100 
                WHERE id = :task_id
            ");
            $update_progress_stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
            $update_progress_stmt->execute();
                // Fetch group emails associated with the task
                $groups_query = "
                    SELECT users.email FROM groups_users 
                    JOIN users ON groups_users.user_id = users.userid 
                    JOIN user_groups ON groups_users.group_id = user_groups.id 
                    JOIN task_section_groups ON task_section_groups.group_id = user_groups.id 
                    WHERE task_section_groups.task_section_id = :task_id";
                $group_emails_stmt = $dbh->prepare($groups_query);
                $group_emails_stmt->bindParam(':task_id', $taskId);
                $group_emails_stmt->execute();
                $groupEmails = $group_emails_stmt->fetchAll(PDO::FETCH_COLUMN);

                // Get users associated with the task
                $user_emails_stmt = $dbh->prepare("
                    SELECT users.email FROM task_section_users 
                    JOIN users ON task_section_users.user_id = users.userid 
                    WHERE task_section_users.task_section_id = :task_id");
                $user_emails_stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
                $user_emails_stmt->execute();
                $userEmails = $user_emails_stmt->fetchAll(PDO::FETCH_COLUMN);

                // Combine group and user emails
                $assignedEmails = array_merge($groupEmails, $userEmails);


                // Get users associated with the task
// Fetching users associated with the task
$user_stmt = $dbh->prepare("
    SELECT CONCAT(users.firstname, ' ', users.lastname) as fullname 
    FROM task_section_users 
    JOIN users ON task_section_users.user_id = users.userid 
    WHERE task_section_users.task_section_id = :task_id
");
$user_stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
$user_stmt->execute();
$users = $user_stmt->fetchAll(PDO::FETCH_COLUMN);

// Convert users array to a comma-separated string
$assigned_users = !empty($users) ? implode(', ', $users) : 'None';

// Fetching groups associated with the task
$group_stmt = $dbh->prepare("
    SELECT user_groups.name 
    FROM user_groups 
    JOIN task_section_groups ON task_section_groups.group_id = user_groups.id 
    WHERE task_section_groups.task_section_id = :task_id
");
$group_stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
$group_stmt->execute();
$groups = $group_stmt->fetchAll(PDO::FETCH_COLUMN);

// Convert groups array to a comma-separated string
$assigned_groups = !empty($groups) ? implode(', ', $groups) : 'None';

                // Prepare email notification
                
                $subject = "Task Completed: " . $task['task_title'];
                $message = "The task '{$task['task_title']}' has been marked as 'Completed' by $currentUsername.";
                // Generate the email template with all details
                 $message .= generateEmailTemplate($task_title, $task_description, $task_time, $frequency, $assigned_users, $assigned_groups, $priority);

                // Send email to all assigned users and groups
                if (!empty($assignedEmails)) {
                    // $emailService->sendBulkEmail($assignedEmails, $subject, $message);
                     // Send email
                     EmailService::sendEmail('sendAlert', 'enriketacara@protech.com.al', $assignedEmails, $subject, $message, "0");
                }
            }

            $response = array('success' => true, 'message' => 'Task status updated and emails sent (if completed).');
        } else {
            throw new Exception('Failed to update status.');
        }
    } catch (PDOException $e) {
        $response = array(
            'error' => true,
            'message' => 'Database error: ' . $e->getMessage()
        );
    } catch (Exception $e) {
        $response = array(
            'error' => true,
            'message' => $e->getMessage()
        );
    }
} else {
    $response = array(
        'success' => false,
        'message' => 'Invalid request method.'
    );
}

echo json_encode($response);
function generateEmailTemplate($task_title, $task_description, $task_time, $frequency, $users, $groups, $priority) {
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
                color: #248727;
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
                color: #ffc107;
                text-decoration: none;
            }
            .footer a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Task Completed: $task_title</h2>
            <p>The task you were assigned has been marked as 'Completed'. Here are the task details:</p>
            <table class='task-details'>
                <tr><td><strong>Title:</strong></td><td>$task_title</td></tr>
                <tr><td><strong>Description:</strong></td><td>$task_description</td></tr>
                <tr><td><strong>Task Time:</strong></td><td>$task_time</td></tr>
                <tr><td><strong>Frequency:</strong></td><td>$frequency</td></tr>
                <tr><td><strong>Assigned users:</strong></td><td>$users</td></tr>
                <tr><td><strong>Assigned groups:</strong></td><td>$groups</td></tr>
                <tr><td><strong>Priority:</strong></td><td>$priority</td></tr>
            </table>
            <p>Thank you for completing the task. If you have any further tasks assigned, be sure to review them.</p>
            <div class='footer'>
                <p>This is an automated message from the Task Management System.</p>
            </div>
        </div>
    </body>
    </html>
    ";
}
