<?php
// header('Content-Type: application/json');
// $rootDir = __DIR__ . '/../../../';
// include_once($rootDir . "config/app_config.php");
// include_once($rootDir . "config/globals.php");
// $dbh = include($rootDir . 'config/connection.php');

// try {
//     // Query for Incomplete Tasks (progress < 50)
//     $stmt = $dbh->prepare("
//         SELECT COUNT(*) AS incomplete_tasks
//         FROM task_section
//         WHERE status = 'Incompleted'
//     ");
//     $stmt->execute();
//     $incompleteTasks = $stmt->fetch(PDO::FETCH_ASSOC)['incomplete_tasks'];

//     // Query for Pending Tasks (status = 'Pending')
//     $stmt = $dbh->prepare("
//         SELECT COUNT(*) AS pending_tasks
//         FROM task_section
//         WHERE status = 'Pending'
//     ");
//     $stmt->execute();
//     $pendingTasks = $stmt->fetch(PDO::FETCH_ASSOC)['pending_tasks'];

//     // Query for High Priority Tasks (priority = 'high')
//     $stmt = $dbh->prepare("
//         SELECT COUNT(*) AS high_priority_tasks
//         FROM task_section
//         WHERE priority = 'high' 
       
//     ");
//     $stmt->execute();
//     $highPriorityTasks = $stmt->fetch(PDO::FETCH_ASSOC)['high_priority_tasks'];

//     // Prepare response
//     $response = [
//         'success' => true,
//         'incomplete_tasks' => $incompleteTasks,
//         'pending_tasks' => $pendingTasks,
//         'high_priority_tasks' => $highPriorityTasks
//     ];
// } catch (PDOException $e) {
//     $response = [
//         'success' => false,
//         'message' => 'Error: ' . $e->getMessage()
//     ];
// }

// echo json_encode($response);
// ?>
<?php
session_start();
header('Content-Type: application/json');
$rootDir = __DIR__ . '/../../../';
include_once($rootDir . "config/app_config.php");
include_once($rootDir . "config/globals.php");
$dbh = include($rootDir . 'config/connection.php');

// Get the logged-in user's email from the session
$userEmail = $_SESSION['useremail'];

try {
    // Prepare response array
    $response = ['success' => true];

    // If the user is admin or a specific user, fetch totals for all tasks
    if ($userEmail === 'admin@protech.com.al' || $userEmail === 'enriketacara@protech.com.al') {
        // Query for Incomplete Tasks (progress < 50) for all users
        $stmt1 = $dbh->prepare("
            SELECT COUNT(*) AS incomplete_tasks
            FROM task_section
            WHERE status = 'Incompleted'
        ");
        $stmt1->execute();
        $response['incomplete_tasks'] = $stmt1->fetch(PDO::FETCH_ASSOC)['incomplete_tasks'];

        // Query for Pending Tasks for all users
        $stmt2 = $dbh->prepare("
            SELECT COUNT(*) AS pending_tasks
            FROM task_section
            WHERE status = 'Pending'
        ");
        $stmt2->execute();
        $response['pending_tasks'] = $stmt2->fetch(PDO::FETCH_ASSOC)['pending_tasks'];

        // Query for High Priority Tasks for all users
        $stmt3 = $dbh->prepare("
            SELECT COUNT(*) AS high_priority_tasks
            FROM task_section
            WHERE priority = 'high'
        ");
        $stmt3->execute();
        $response['high_priority_tasks'] = $stmt3->fetch(PDO::FETCH_ASSOC)['high_priority_tasks'];



        // Query for High Priority Tasks for all users
        $stmt4 = $dbh->prepare("
            SELECT COUNT(*) AS high_incomplete_tasks
            FROM task_section
            WHERE priority = 'high' and status = 'Incompleted'
        ");
        $stmt4->execute();
        $response['high_incomplete_tasks'] = $stmt4->fetch(PDO::FETCH_ASSOC)['high_incomplete_tasks'];

    } else {
        // If not admin, fetch totals for the logged-in user's assigned tasks
        
        // Query for Incomplete Tasks assigned to the logged-in user
        $stmt1 = $dbh->prepare("
           SELECT COUNT(task_section.id) AS incomplete_tasks
FROM task_section
LEFT JOIN task_section_users ON task_section.id = task_section_users.task_section_id
LEFT JOIN task_section_groups ON task_section.id = task_section_groups.task_section_id
LEFT JOIN groups_users ON task_section_groups.group_id = groups_users.group_id
JOIN users ON task_section_users.user_id = users.userid OR groups_users.user_id = users.userid
WHERE task_section.status = 'Incompleted'
  AND users.email = :user_email;

        ");
        $stmt1->bindParam(':user_email', $userEmail);
        $stmt1->execute();
        $response['incomplete_tasks'] = $stmt1->fetch(PDO::FETCH_ASSOC)['incomplete_tasks'];

        // Query for Pending Tasks assigned to the logged-in user
        $stmt2 = $dbh->prepare("
          SELECT COUNT(task_section.id) AS pending_tasks
FROM task_section
LEFT JOIN task_section_users ON task_section.id = task_section_users.task_section_id
LEFT JOIN task_section_groups ON task_section.id = task_section_groups.task_section_id
LEFT JOIN groups_users ON task_section_groups.group_id = groups_users.group_id
JOIN users ON task_section_users.user_id = users.userid OR groups_users.user_id = users.userid
WHERE task_section.status = 'Pending'
  AND users.email = :user_email;
        ");
        $stmt2->bindParam(':user_email', $userEmail);
        $stmt2->execute();
        $response['pending_tasks'] = $stmt2->fetch(PDO::FETCH_ASSOC)['pending_tasks'];

        // Query for High Priority Tasks assigned to the logged-in user
        $stmt3 = $dbh->prepare("
            SELECT COUNT(task_section.id) AS high_priority_tasks
FROM task_section
LEFT JOIN task_section_users ON task_section.id = task_section_users.task_section_id
LEFT JOIN task_section_groups ON task_section.id = task_section_groups.task_section_id
LEFT JOIN groups_users ON task_section_groups.group_id = groups_users.group_id
JOIN users ON task_section_users.user_id = users.userid OR groups_users.user_id = users.userid
WHERE task_section.priority = 'high'
  AND users.email = :user_email;

        ");
        $stmt3->bindParam(':user_email', $userEmail);
        $stmt3->execute();
        $response['high_priority_tasks'] = $stmt3->fetch(PDO::FETCH_ASSOC)['high_priority_tasks'];

        // Query for High Priority Tasks assigned to the logged-in user
        $stmt4 = $dbh->prepare("
            SELECT COUNT(task_section.id) AS high_incomplete_tasks
FROM task_section
LEFT JOIN task_section_users ON task_section.id = task_section_users.task_section_id
LEFT JOIN task_section_groups ON task_section.id = task_section_groups.task_section_id
LEFT JOIN groups_users ON task_section_groups.group_id = groups_users.group_id
JOIN users ON task_section_users.user_id = users.userid OR groups_users.user_id = users.userid
WHERE task_section.priority = 'high'
  AND task_section.status = 'Incompleted'
  AND users.email = :user_email;


        ");
        $stmt4->bindParam(':user_email', $userEmail);
        $stmt4->execute();
        $response['high_incomplete_tasks'] = $stmt4->fetch(PDO::FETCH_ASSOC)['high_incomplete_tasks'];


    }

    // Add additional data if needed or finalize the response
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
    ];
}

echo json_encode($response);
?>
