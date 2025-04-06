<?php
header('Content-Type: application/json');
$rootDir = __DIR__ . '/../../../';
include_once($rootDir . "config/app_config.php");
include_once($rootDir . "config/globals.php");
$dbh = include($rootDir . 'config/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id > 0) {
        try {
            // Prepare the SQL statement to clear the comment field
            $stmt = $dbh->prepare("UPDATE task_section SET comment = NULL WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $response = array('success' => true, 'message' => 'Comment deleted successfully.');
            } else {
                $response = array('success' => false, 'message' => 'No comment found to delete.');
            }
        } catch (PDOException $e) {
            $response = array('success' => false, 'message' => 'Error: ' . $e->getMessage());
        }
    } else {
        $response = array('success' => false, 'message' => 'Invalid task ID.');
    }
} else {
    $response = array('success' => false, 'message' => 'Invalid request method.');
}

echo json_encode($response);
