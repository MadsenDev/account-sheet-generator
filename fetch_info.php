<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Replace these values with your own database credentials
$servername = "localhost";
$username = "madsensd_madsen";
$password = "data2023";
$dbname = "madsensd_acct";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$sql = "SELECT info FROM brands WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
  echo json_encode($row);
} else {
  echo json_encode(['error' => 'No data found']);
}

$stmt->close();
$conn->close();
?>
