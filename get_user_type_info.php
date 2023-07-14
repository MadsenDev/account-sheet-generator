<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

if (isset($_GET['id'])) {
    $user_type_id = intval($_GET['id']);

    $user_type_query = "SELECT * FROM user_type WHERE id = ?";
    $stmt = mysqli_prepare($conn, $user_type_query);
    mysqli_stmt_bind_param($stmt, 'i', $user_type_id);
    mysqli_stmt_execute($stmt);
    $user_type_result = mysqli_stmt_get_result($stmt);
    $user_type_info = mysqli_fetch_assoc($user_type_result);

    $user_brands_query = "SELECT brands.name FROM user_brands INNER JOIN brands ON user_brands.brand_id = brands.id WHERE user_brands.user_type_id = ?";
    $stmt = mysqli_prepare($conn, $user_brands_query);
    mysqli_stmt_bind_param($stmt, 'i', $user_type_id);
    mysqli_stmt_execute($stmt);
    $brands_result = mysqli_stmt_get_result($stmt);

    $brands_list = [];
    while ($brand = mysqli_fetch_assoc($brands_result)) {
        $brands_list[] = $brand['name'];
    }

    $user_type_info['brands'] = $brands_list;

    echo json_encode($user_type_info);
}
?>