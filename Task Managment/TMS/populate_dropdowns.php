<?php

header('Content-Type: application/json');
$rootDir = __DIR__ . '/../../../';
include_once($rootDir . "config/app_config.php");
include_once($rootDir . "config/globals.php");
$dbh = include($rootDir . 'config/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Query for user groups
        $stmtGroups = $dbh->query("SELECT id, name FROM user_groups");
        $user_groups = $stmtGroups->fetchAll(PDO::FETCH_ASSOC);

        // Query for users with specific access levels
        $stmtUsers = $dbh->query("SELECT * FROM users WHERE accesslevel IN ('admin', 'noc', 'other')");
        $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

        // Response with the fetched data
        $response = [
            'user_groups' => $user_groups,
            'users' => $users,
        ];

        // Output the response in JSON format
        echo json_encode($response);
    } catch (PDOException $e) {
        // Response on exception
        $response = [
            'error' => true,
            'message' => 'Error: ' . $e->getMessage()
        ];
        echo json_encode($response);
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid request method.'
    ];
    echo json_encode($response);
}
?>
