<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Only run this code if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serverName = "localhost";
    $connectionOptions = [
        "Database" => "GrabNGoDB",
        "Uid" => "",
        "PWD" => ""  // no password
    ];

    // Connect to database
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if ($conn === false) {
        die("Connection failed: " . print_r(sqlsrv_errors(), true));
    }

    // Get POST data safely
    $student_id = $_POST['student_id'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if inputs are not empty
    if (empty($student_id) || empty($password)) {
        echo "<script>
            alert('Please enter both Student ID and Password.');
            window.location.href = '/login.html';
        </script>";
        exit();
    }

    // Prepare SQL query to fetch user with allowance and name
    $sql = "SELECT student_id, name, allowance FROM userstest WHERE student_id = ? AND password = ?";
    $params = [$student_id, $password];

    // Execute query
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("SQL error: " . print_r(sqlsrv_errors(), true));
    }

    // Check if user exists
    if (sqlsrv_has_rows($stmt)) {
        $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        // Save user info to session
        $_SESSION['student_id'] = $user['student_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['allowance'] = $user['allowance'];

        // Redirect to meals page
        header("Location: /meals.php");
        exit();
    } else {
        echo "<script>
            alert('Invalid Student ID or password.');
            window.location.href = '/login.html';
        </script>";
        exit();
    }
} else {
    // Optional: redirect if this script is accessed without submitting the form
    header("Location: /login.html");
    exit();
}
?>
