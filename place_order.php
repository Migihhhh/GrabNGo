<?php
session_start();
header('Content-Type: application/json'); // Ensure this is at the very top

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['student_id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Not logged in']));
}

try {
    $json = file_get_contents('php://input');
    if ($json === false) {
        throw new Exception('Failed to read input data');
    }

    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }

    if (!$data || !isset($data['items']) || !is_array($data['items']) || empty($data['items'])) {
        throw new Exception('Invalid order data - no items provided');
    }

    $serverName = "localhost";
    $connectionOptions = [
        "Database" => "GrabNGoDB",
        "Uid" => "",   // your DB username if any
        "Pwd" => ""    // your DB password if any
    ];

    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) {
        throw new Exception('Database connection failed: ' . print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_begin_transaction($conn) === false) {
        throw new Exception("Could not begin transaction");
    }

    $studentId = $_SESSION['student_id'];
    $totalAmount = $data['total_amount'];

    // --- MODIFIED HERE: Use OUTPUT INSERTED.ID to get the order ID directly ---
    $sql = "INSERT INTO orders (student_id, total_amount) OUTPUT INSERTED.ID VALUES (?, ?)";
    $params = [$studentId, $totalAmount];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        $errors = sqlsrv_errors();
        throw new Exception("Failed to create order (INSERT with OUTPUT): " . print_r($errors, true));
    }

    // Fetch the result set from the INSERT statement
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    // Check if the ID was successfully retrieved
    if ($row === null || !isset($row['ID']) || $row['ID'] === null) {
        throw new Exception("Could not retrieve order ID after insertion using OUTPUT. Fetched row data: " . print_r($row, true));
    }
    $orderId = $row['ID']; // The column name will be 'ID' from OUTPUT INSERTED.ID

    error_log("Retrieved Order ID using OUTPUT: " . $orderId); // Added for debugging

    // Insert order items
    foreach ($data['items'] as $item) {
        // Ensure $orderId is not NULL here before using it
        if ($orderId === null) {
            throw new Exception("Order ID is NULL when trying to insert order item (critical error).");
        }
        $sql = "INSERT INTO order_items (order_id, food_id, quantity) VALUES (?, ?, 1)";
        $params = [$orderId, $item['id']];
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            $errors = sqlsrv_errors();
            throw new Exception("Failed to add order item for food_id " . ($item['id'] ?? 'N/A') . ": " . print_r($errors, true));
        }
    }

    // Deduct from user's allowance
    $sql = "UPDATE userstest SET allowance = allowance - ? WHERE student_id = ?";
    $params = [$totalAmount, $studentId];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        $errors = sqlsrv_errors();
        throw new Exception("Failed to update allowance: " . print_r($errors, true));
    }

    // Update session allowance
    $_SESSION['allowance'] -= $totalAmount;

    // Commit transaction
    sqlsrv_commit($conn);

    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'order_id' => $orderId,
        'new_allowance' => $_SESSION['allowance']
    ]);

} catch (Exception $e) {
    if (isset($conn) && $conn !== false) {
        sqlsrv_rollback($conn);
    }

    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_details' => (isset($data) ? $data : null)
    ]);
}

if (isset($conn) && $conn !== false) {
    sqlsrv_close($conn);
}
?>