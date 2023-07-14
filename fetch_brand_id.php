<?php

header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');

require_once 'db.php';

// Fetch data from database
$id = intval($_GET['name']);
$sql = "SELECT id FROM brands WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Return data as JSON
if ($row) {
  echo json_encode($row);
} else {
  echo json_encode(['error' => 'No data found']);
}

// Close connection
$stmt->close();
$conn->close();
?>