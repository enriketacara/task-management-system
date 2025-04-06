<?php

header('Content-Type: application/json');
$rootDir = __DIR__ . '/../../../';
include_once($rootDir . "config/app_config.php");
include_once($rootDir . "config/globals.php");
$dbh = include($rootDir . 'config/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Retrieve data from the task_section table
        $stmt = $dbh->query("SELECT * FROM task_section");
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepare data for DataTables
        $data = [];
        foreach ($tasks as $row) {
            $task_id = $row['id'];

            // Get users associated with the task
            $user_stmt = $dbh->prepare("SELECT CONCAT(users.firstname, ' ', users.lastname) as fullname FROM task_section_users 
                                         JOIN users ON task_section_users.user_id = users.userid 
                                         WHERE task_section_users.task_section_id = :task_id");
            $user_stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $user_stmt->execute();
            $users = $user_stmt->fetchAll(PDO::FETCH_COLUMN);
            // Get groups associated with the task
            $group_stmt = $dbh->prepare("SELECT user_groups.name 
                             FROM user_groups 
                             JOIN task_section_groups ON task_section_groups.group_id = user_groups.id 
                             WHERE task_section_groups.task_section_id = :task_id");
            $group_stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $group_stmt->execute();
            $groups = $group_stmt->fetchAll(PDO::FETCH_COLUMN);


            $groups_query = "SELECT users.email FROM groups_users 
            JOIN users ON groups_users.user_id = users.userid 
            JOIN user_groups ON groups_users.group_id = user_groups.id 
            JOIN task_section_groups ON task_section_groups.group_id = user_groups.id 
            WHERE task_section_groups.task_section_id = :task_id";
            $group_emails_stmt = $dbh->prepare($groups_query);
            $group_emails_stmt->bindParam(':task_id', $task_id);
            $group_emails_stmt->execute();
            $group_emails = $group_emails_stmt->fetchAll(PDO::FETCH_COLUMN);


        // Get users associated with the task
        $user_emails_stmt = $dbh->prepare("SELECT users.email as fullname FROM task_section_users 
        JOIN users ON task_section_users.user_id = users.userid 
        WHERE task_section_users.task_section_id = :task_id");
        $user_emails_stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $user_emails_stmt->execute();
        $user_emails = $user_emails_stmt->fetchAll(PDO::FETCH_COLUMN);
        // var_dump( $emails );
            $data[] = [
                'id' => $row['id'],
                'task_title' => $row['task_title'],
                'task_description' => $row['task_description'],
                'frequency' => $row['frequency'],
                'task_type' => $row['task_type'],
                'task_time' => $row['task_time'],
                'progress' => $row['progress'],
                'status' => $row['status'],
                'active' => $row['active'],
                'comment' => $row['comment'],
                'status_updated_by' => $row['status_updated_by'],
                'status_last_updated' => $row['status_last_updated'],
                'updated_at' => $row['updated_at'],
                'created_at' => $row['created_at'],
                'created_by' => $row['created_by'],
                'priority' => $row['priority'],
                'users' => $users,
                'groups' => $groups,
                'group_emails' => $group_emails,
                'user_emails' => $user_emails
            ];
        }

        // Response with data for DataTables
        $response = array(
            'data' => $data
        );

        // Output the response in JSON format
        echo json_encode($response);
    } catch (PDOException $e) {
        // Response on exception
        $response = array(
            'error' => true,
            'message' => 'Error: ' . $e->getMessage()
        );
        echo json_encode($response);
    }
} else {
    $response = array(
        'success' => false,
        'message' => 'Invalid request method.'
    );
    echo json_encode($response);
}
?>
