<?php
header('Content-Type: application/json');
$rootDir = __DIR__ . '/../../../';
include_once($rootDir . "config/app_config.php");
include_once($rootDir . "config/globals.php");
$dbh = include($rootDir . 'config/connection.php');

try {
    // Query to get the number of high-priority and incomplete tasks lower than 50% for each user
    $stmt = $dbh->prepare("
        SELECT u.userid, u.firstname, u.lastname, 
            SUM(CASE WHEN ts.priority = 'high' AND ts.progress < 50 AND ts.status = 'Incompleted' THEN 1 ELSE 0 END) AS task_count
        FROM task_section ts
        INNER JOIN task_section_users tsu ON ts.id = tsu.task_section_id
        INNER JOIN users u ON tsu.user_id = u.userid
        GROUP BY u.userid, u.firstname, u.lastname
    ");
    $stmt->execute();
    $taskSummary = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate the total number of high-priority and incomplete tasks
    $totalTasks = 0;
    foreach ($taskSummary as $userTask) {
        $totalTasks += $userTask['task_count'];
    }

    // Prepare response
    $response = [
        'success' => true,
        'total_tasks' => $totalTasks,
        'task_summary' => $taskSummary
    ];
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ];
}

echo json_encode($response);
?>
