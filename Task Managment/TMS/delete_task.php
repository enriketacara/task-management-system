<?php
header('Content-Type: application/json');
$rootDir = __DIR__ . '/../../../';
include_once($rootDir . "config/app_config.php");
include_once($rootDir . "config/globals.php");
$dbh = include($rootDir . 'config/connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Fetch the task ID
        $taskId = isset($_POST['id']) ? intval($_POST['id']) : null;

        if ($taskId === null) {
            throw new Exception('Invalid task ID.');
        }

        // Prepare the delete statement
        $stmt = $dbh->prepare("DELETE FROM task_section WHERE id = :id");
        $stmt->bindParam(':id', $taskId);

        // Execute the delete statement
        if ($stmt->execute()) {
            $response = array('success' => true, 'message' => 'Task deleted successfully.');
        } else {
            throw new Exception('Failed to delete task.');
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
?>
