<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

$conn = new mysqli("localhost", "root", "", "your_database_name");
$student_id = $_SESSION['student_id'];

$sql = "SELECT name, allowance FROM userstest WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    echo json_encode(["name" => $row['name'], "allowance" => $row['allowance']]);
} else {
    echo json_encode(["error" => "User not found"]);
}
?>
