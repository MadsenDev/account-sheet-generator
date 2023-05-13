<?php
require_once 'db.php';

if (!isset($_GET['user_type'])) {
    http_response_code(400);
    exit();
}

$user_type_id = $_GET['user_type'];
$brands_query = "SELECT name FROM brands INNER JOIN user_brands ON brands.id = user_brands.brand_id WHERE user_type_id = ?";
$stmt = mysqli_prepare($conn, $brands_query);
mysqli_stmt_bind_param($stmt, 'i', $user_type_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$brands = [];
while ($row = mysqli_fetch_assoc($result)) {
    $brands[] = $row['name'];
}

header('Content-Type: application/json');
echo json_encode($brands);
?>