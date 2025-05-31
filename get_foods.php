<?php
$serverName = "localhost";
$connectionOptions = [
    "Database" => "GrabNGoDB",
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$sql = "SELECT * FROM foods";
$stmt = sqlsrv_query($conn, $sql);

$foods = [];

if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $foods[] = $row;
    }
    echo json_encode($foods);
} else {
    echo json_encode(["error" => "Query failed."]);
}
?>
