<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['student_id'])) {
    echo json_encode([]);
    exit;
}

$serverName = "localhost";
$connectionOptions = [
    "Database" => "GrabNGoDB",
    "Uid" => "",
    "Pwd" => ""
];

try {
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    $sql = "SELECT n.id, n.order_id, n.status, n.created_at 
            FROM notifications n
            WHERE user_id = ? 
            ORDER BY created_at DESC";
    
    $params = [$_SESSION['student_id']];
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    $notifications = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $row['created_at'] = $row['created_at']->format('Y-m-d H:i:s');
        $notifications[] = $row;
    }
    
    echo json_encode($notifications);
    
} catch (Exception $e) {
    echo json_encode([]);
}
?>