<?php
session_start();
header('Content-Type: application/json');
$rootDir = __DIR__ . '/../../../';
include_once($rootDir . "config/app_config.php");
include_once($rootDir . "config/globals.php");
$dbh = include($rootDir . 'config/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id']; // Assuming the ID of the task is sent via POST
    $newComment = $_POST['comment'];
    $username = $_SESSION['login_session']; // Retrieve the actual username from the session or other source

    try {
        // Fetch existing comments
        $stmt = $dbh->prepare("SELECT comment FROM task_section WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $existingComments = $row['comment'];

        // Append new comment with timestamp and username
        $currentDate = date('Y-m-d H:i:s');
        $formattedComment = "$username on $currentDate: $newComment";
        $updatedComments = $existingComments ? $existingComments . "\n" . $formattedComment : $formattedComment;

        // Update the database with the new comment
        $updateStmt = $dbh->prepare("UPDATE task_section SET comment = :comment WHERE id = :id");
        $updateStmt->bindParam(':comment', $updatedComments);
        $updateStmt->bindParam(':id', $id);
        $updateStmt->execute();

        // Respond with success
        $response = array('success' => true);
        echo json_encode($response);
    } catch (PDOException $e) {
        // Respond with error
        $response = array('success' => false, 'message' => 'Error: ' . $e->getMessage());
        echo json_encode($response);
    }
} else {
    $response = array('success' => false, 'message' => 'Invalid request method.');
    echo json_encode($response);
}
?>
