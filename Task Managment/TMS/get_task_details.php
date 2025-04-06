<?php
// header('Content-Type: application/json');
// $rootDir = __DIR__ . '/../../../';
// include_once($rootDir . "config/app_config.php");
// include_once($rootDir . "config/globals.php");
// $dbh = include($rootDir . 'config/connection.php');

// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['task_id'])) {
//     try {
//         // Fetch task details
//         $stmt = $dbh->prepare("SELECT * FROM task_section WHERE id = :task_id");
//         $stmt->bindParam(':task_id', $_GET['task_id'], PDO::PARAM_INT);
//         $stmt->execute();
//         $task = $stmt->fetch(PDO::FETCH_ASSOC);

//         if ($task) {
//             // Fetch associated users and groups
//             $stmtUsers = $dbh->prepare("SELECT user_id FROM task_section_users WHERE task_section_id = :task_id");
//             $stmtUsers->bindParam(':task_id', $_GET['task_id'], PDO::PARAM_INT);
//             $stmtUsers->execute();
//             $users = $stmtUsers->fetchAll(PDO::FETCH_COLUMN);

//             $stmtGroups = $dbh->prepare("SELECT group_id FROM task_section_groups WHERE task_section_id = :task_id");
//             $stmtGroups->bindParam(':task_id', $_GET['task_id'], PDO::PARAM_INT);
//             $stmtGroups->execute();
//             $groups = $stmtGroups->fetchAll(PDO::FETCH_COLUMN);

//             // Fetch all available users and groups
//             $stmtAllUsers = $dbh->prepare("SELECT userid, firstname, lastname FROM users WHERE accesslevel IN ('admin', 'noc', 'other')");
//             $stmtAllUsers->execute();
//             $allUsers = $stmtAllUsers->fetchAll(PDO::FETCH_ASSOC);

//             $stmtAllGroups = $dbh->prepare("SELECT id, name FROM user_groups");
//             $stmtAllGroups->execute();
//             $allGroups = $stmtAllGroups->fetchAll(PDO::FETCH_ASSOC);

//             // Prepare user and group options with selected states
//             $userOptions = array_map(function($user) use ($users) {
//                 $selected = in_array($user['userid'], $users) ? 'selected' : '';
//                 return '<option value="' . $user['userid'] . '" ' . $selected . '>' . $user['firstname'] . ' ' . $user['lastname'] . '</option>';
//             }, $allUsers);

//             $groupOptions = array_map(function($group) use ($groups) {
//                 $selected = in_array($group['id'], $groups) ? 'selected' : '';
//                 return '<option value="' . $group['id'] . '" ' . $selected . '>' . $group['name'] . '</option>';
//             }, $allGroups);

//             // Return the task and options
//             $response = [
//                 'success' => true,
//                 'data' => array_merge($task, [
//                     'assign_task' => implode('', $userOptions),
//                     'group' => implode('', $groupOptions)
//                 ])
//             ];
//         } else {
//             $response = [
//                 'success' => false,
//                 'message' => 'Task not found.'
//             ];
//         }
//     } catch (PDOException $e) {
//         $response = [
//             'success' => false,
//             'message' => 'Error: ' . $e->getMessage()
//         ];
//     }
//     echo json_encode($response);
// }

header('Content-Type: application/json');
$rootDir = __DIR__ . '/../../../';
include_once($rootDir . "config/app_config.php");
include_once($rootDir . "config/globals.php");
$dbh = include($rootDir . 'config/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['task_id'])) {
    try {
        // Fetch task details
        $stmt = $dbh->prepare("SELECT * FROM task_section WHERE id = :task_id");
        $stmt->bindParam(':task_id', $_GET['task_id'], PDO::PARAM_INT);
        $stmt->execute();
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($task) {
            // Extract time components from task_time
            $task_time = $task['task_time'];
            $dateTime = new DateTime($task_time);
            $hour = $dateTime->format('H');
            $minute = $dateTime->format('i');
            $day = $dateTime->format('d');
            $weekday = $dateTime->format('l'); // Full weekday name
            
            // Fetch associated users and groups
            $stmtUsers = $dbh->prepare("SELECT user_id FROM task_section_users WHERE task_section_id = :task_id");
            $stmtUsers->bindParam(':task_id', $_GET['task_id'], PDO::PARAM_INT);
            $stmtUsers->execute();
            $users = $stmtUsers->fetchAll(PDO::FETCH_COLUMN);

            $stmtGroups = $dbh->prepare("SELECT group_id FROM task_section_groups WHERE task_section_id = :task_id");
            $stmtGroups->bindParam(':task_id', $_GET['task_id'], PDO::PARAM_INT);
            $stmtGroups->execute();
            $groups = $stmtGroups->fetchAll(PDO::FETCH_COLUMN);

            // Fetch all available users and groups
            $stmtAllUsers = $dbh->prepare("SELECT userid, firstname, lastname FROM users WHERE accesslevel IN ('admin', 'noc', 'other')");
            $stmtAllUsers->execute();
            $allUsers = $stmtAllUsers->fetchAll(PDO::FETCH_ASSOC);

            $stmtAllGroups = $dbh->prepare("SELECT id, name FROM user_groups");
            $stmtAllGroups->execute();
            $allGroups = $stmtAllGroups->fetchAll(PDO::FETCH_ASSOC);

            // Prepare user and group options with selected states
            $userOptions = array_map(function($user) use ($users) {
                $selected = in_array($user['userid'], $users) ? 'selected' : '';
                return '<option value="' . $user['userid'] . '" ' . $selected . '>' . $user['firstname'] . ' ' . $user['lastname'] . '</option>';
            }, $allUsers);

            $groupOptions = array_map(function($group) use ($groups) {
                $selected = in_array($group['id'], $groups) ? 'selected' : '';
                return '<option value="' . $group['id'] . '" ' . $selected . '>' . $group['name'] . '</option>';
            }, $allGroups);

            // Return the task and options
            $response = [
                'success' => true,
                'data' => array_merge($task, [
                    'assign_task' => implode('', $userOptions),
                    'group' => implode('', $groupOptions),
                    'hourly_hour' => ($task['task_type'] === 'Hourly') ? $hour : null,
                    'hourly_minute' => ($task['task_type'] === 'Hourly') ? $minute : null,
                    'daily_hour' => ($task['task_type'] === 'Daily') ? $hour : null,
                    'daily_minute' => ($task['task_type'] === 'Daily') ? $minute : null,
                    'weekly_day' => ($task['task_type'] === 'Weekly') ? $weekday : null,
                    'monthly_day' => ($task['task_type'] === 'Monthly') ? $day : null,
                    'monthly_hour' => ($task['task_type'] === 'Monthly') ? $hour : null,
                    'monthly_minute' => ($task['task_type'] === 'Monthly') ? $minute : null
                ])
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Task not found.'
            ];
        }
    } catch (PDOException $e) {
        $response = [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
    echo json_encode($response);
}
?>
