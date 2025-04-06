<?php
header('Content-Type: application/json');
$rootDir = __DIR__ . '/../../../';
include_once($rootDir . "config/app_config.php");
include_once($rootDir . "config/globals.php");
$dbh = include($rootDir . 'config/connection.php');

try {
    $taskId = $_POST['task_id'];
    $active = $_POST['active'];

    // Update query
    $stmt = $dbh->prepare("UPDATE task_section SET active = :active WHERE id = :task_id");
    $stmt->bindParam(':active', $active, PDO::PARAM_INT);
    $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
    $stmt->execute();

    $response = [
        'success' => true,
        'message' => 'Task status updated successfully.'
    ];
} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ];
}

echo json_encode($response);
