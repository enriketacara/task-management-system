
<?php

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
$rootDir = __DIR__ . '/../../../';
include_once($rootDir . "config/app_config.php");
include_once($rootDir . "config/globals.php");
$dbh = include($rootDir . 'config/connection.php');
// Include necessary files
include('/var/www/html/billing-system/Classes/EmailService.php');

try {
    // Get current datetime
    $current_datetime = new DateTime('now');
    $current_time_only = $current_datetime->format('H:i'); // Extract current time in "HH:mm" format
    $current_datetime_string = $current_datetime->format('Y-m-d H:i:s');
    // Updating One Time tasks where status is Completed

    $updateOneTimeQuery = "UPDATE task_section SET active = '0' WHERE frequency = 'One Time' AND status = 'Completed'";
    $stmt = $dbh->prepare($updateOneTimeQuery);
    $stmt->execute();

    // Handle Pending tasks
    // $pending_query = "SELECT * FROM task_section WHERE id='796'";
    $pending_query = "SELECT * FROM task_section WHERE (status = 'Pending' OR status = 'Incompleted') AND task_time <= :current_time AND next_reminder <= :current_time AND active = '1'"; 
    // $pending_query = "SELECT * FROM task_section WHERE id=796"; 
    $pending_stmt = $dbh->prepare($pending_query); 
    $pending_stmt->bindParam(':current_time', $current_datetime_string, PDO::PARAM_STR);
    $pending_stmt->execute();
   // Loop through tasks and send reminder emails
    while ($task_row = $pending_stmt->fetch(PDO::FETCH_ASSOC)) {

    $cron_start_time = $task_row['cron_start_time']; // Start time in "HH:mm"
    $cron_end_time = $task_row['cron_end_time'];     // End time in "HH:mm"

    // Check if current time is within cron_start_time and cron_end_time
    if ($current_time_only < $cron_start_time || $current_time_only > $cron_end_time) {
    // Skip this task if current time is outside the interval
        continue;
    }
    $id = $task_row['id'];
    $task_title = $task_row['task_title'];
    $task_description = $task_row['task_description'];
    $frequency = $task_row['frequency'];
    $task_type = $task_row['task_type'];
    $task_time = new DateTime($task_row['task_time']);
    $next_reminder = new DateTime($task_row['next_reminder']);
    $status = $task_row['status'];
       // Fetching users associated with the task
       $user_stmt = $dbh->prepare("
       SELECT CONCAT(users.firstname, ' ', users.lastname) as fullname 
       FROM task_section_users 
       JOIN users ON task_section_users.user_id = users.userid 
       WHERE task_section_users.task_section_id = :task_id
   ");
   $user_stmt->bindParam(':task_id', $id, PDO::PARAM_INT);
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
   $group_stmt->bindParam(':task_id', $id, PDO::PARAM_INT);
   $group_stmt->execute();
   $groups = $group_stmt->fetchAll(PDO::FETCH_COLUMN);

   // Convert groups array to a comma-separated string
   $assigned_groups = !empty($groups) ? implode(', ', $groups) : 'None';

        // Check if today is Saturday or Sunday
        // if (($current_day_of_week === 'Saturday' && $task_row['Saturday'] != 1) ||
        //     ($current_day_of_week === 'Sunday' && $task_row['Sunday'] != 1)) {
        //     // Skip sending the email if it's the weekend and the respective column is not 1
        //     continue;
        // }
        
    if ($task_type == 'Monthly' || $task_type == 'Weekly' || $task_type == 'Daily') {
        // Set status to 'Incompleted' and add +1 day to next reminder
        $next_reminder->modify('+1 day');
        
    } elseif ($task_type == 'Hourly') {
        // Set status to 'Incompleted' and add +1 hour to next reminder
        $next_reminder->modify('+1 hour');
    }
    $next_reminder_string = $next_reminder->format('Y-m-d H:i:s');
    // Retrieve the emails of assigned users for the task
 
    $update_task_query = "UPDATE task_section 
    SET status = 'Incompleted', next_reminder = '$next_reminder_string' 
    WHERE id = :id";
    $update_stmt = $dbh->prepare($update_task_query);
    $update_stmt->bindParam(':id', $id);
    $update_stmt->execute();

    $cc_email_array = [];
    $CCs = [];
    $users_query = "SELECT users.email FROM task_section_users 
                    JOIN users ON task_section_users.user_id = users.userid 
                    WHERE task_section_users.task_section_id = :task_id";
    $user_stmt = $dbh->prepare($users_query);
    $user_stmt->bindParam(':task_id', $id);
    $user_stmt->execute();
    while ($row_u = $user_stmt->fetch(PDO::FETCH_ASSOC)) {
        $cc_email_array[] = $row_u['email'];
    }

    // Retrieve the emails of participants in the selected groups for the task
    $groups_query = "SELECT users.email FROM groups_users 
                     JOIN users ON groups_users.user_id = users.userid 
                     JOIN user_groups ON groups_users.group_id = user_groups.id 
                     JOIN task_section_groups ON task_section_groups.group_id = user_groups.id 
                     WHERE task_section_groups.task_section_id = :task_id";
    $group_stmt = $dbh->prepare($groups_query);
    $group_stmt->bindParam(':task_id', $id);
    $group_stmt->execute();
    while ($row_g = $group_stmt->fetch(PDO::FETCH_ASSOC)) {
        $cc_email_array[] = $row_g['email'];
    }
   
$BCC = array('enriketacara@protech.com.al');
$message = generateEmailTemplate($id,$task_title, $task_description, $frequency,$task_type, $task_time ,$assigned_users,$assigned_groups, $status);
$subject = 'TMS Overdue:' . $task_title;

    if (!empty($cc_email_array)) {
        $CCs = array_unique($cc_email_array);
        // EmailService::sendEmail('sendAlert', $userEmail, $CCs, $subject, $message, "0");
        EmailService::sendEmailBCC('sendAlert','alerts@protech.com.al',$CCs,$BCC,$subject, $message);

        $update_reminder_query = "UPDATE task_section SET next_reminder = :next_reminder WHERE id = :id";
        $reminder_stmt = $dbh->prepare($update_reminder_query);
        $reminder_stmt->bindParam(':next_reminder', $next_reminder_string);
        $reminder_stmt->bindParam(':id', $id);
        $reminder_stmt->execute();
    }
}

    $completed_query = "SELECT * FROM task_section 
                        WHERE status = 'Completed' 
                        AND frequency = 'Multi Time'
                        AND task_time <= :current_time and active='1'";
    $completed_stmt = $dbh->prepare($completed_query);
    $completed_stmt->bindParam(':current_time', $current_datetime_string, PDO::PARAM_STR);
    $completed_stmt->execute();

    while ($task_row = $completed_stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = $task_row['id'];
        $frequency = $task_row['frequency'];
        $task_type = $task_row['task_type'];
        $task_time = new DateTime($task_row['task_time']);
        $next_reminder = clone $task_time;

        if ($task_type == 'Monthly') {
            // Add +1 month and adjust task time for next month
            $next_reminder->modify('+1 month');
        } elseif ($task_type == 'Weekly') {
            // Use the specific day name from task_time for the next weekly occurrence
            $day_of_week = $task_row['task_time']; // e.g., "Saturday"
            $next_reminder->modify("next $day_of_week");
        } elseif ($task_type == 'Daily') {
            // Add +1 day for the next occurrence
            $next_reminder->modify('+1 day');
        } 

        $next_reminder_string = $next_reminder->format('Y-m-d H:i:s');
        $update_task_query = "UPDATE task_section 
                              SET task_time = :task_time, next_reminder = :next_reminder,status='Pending'
                              WHERE id = :id";
        $update_stmt = $dbh->prepare($update_task_query);
        $update_stmt->bindParam(':task_time', $next_reminder_string);
        $update_stmt->bindParam(':next_reminder', $next_reminder_string);
        $update_stmt->bindParam(':id', $id);
        $update_stmt->execute();
    }

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
function generateEmailTemplate($task_id,$task_title, $task_description, $frequency, $task_type, $task_time, $users, $groups, $status) {
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
            <h2>Task Incompleted: $task_title</h2>
            <p> Please complete it as soon as possible. Below are the task details:</p>
            <table class='task-details'>
                <tr><td><strong>Id:</strong></td><td>$task_id</td></tr>
                <tr><td><strong>Title:</strong></td><td>$task_title</td></tr>
                <tr><td><strong>Description:</strong></td><td>$task_description</td></tr>
                <tr><td><strong>Frequency:</strong></td><td>$frequency</td></tr>
                <tr><td><strong>Task Type:</strong></td><td>$task_type</td></tr>
                <tr><td><strong>Task Time:</strong></td><td>{$task_time->format('Y-m-d H:i:s')}</td></tr>
                <tr><td><strong>Assigned users:</strong></td><td>$users</td></tr>
                <tr><td><strong>Assigned groups:</strong></td><td>$groups</td></tr>
                <tr><td><strong>Status:</strong></td><td>$status</td></tr>
            </table>
            <p>Make sure to review the task and complete it on time.</p>
            <div class='footer'>
                <p>This is an automated message from the Task Management System.</p>
            </div>
        </div>
    </body>
    </html>
    ";
}
?>