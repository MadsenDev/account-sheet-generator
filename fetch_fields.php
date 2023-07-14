<?php

header('Content-Type: application/json');

// Check if brand_id is passed in GET request
if (!isset($_GET['brand_id'])) {
    echo json_encode(['error' => 'brand_id not provided']);
    exit;
}

$brandId = $_GET['brand_id'];

// Check if selectedLanguage is passed in GET request
if (!isset($_GET['selectedLanguage'])) {
    echo json_encode(['error' => 'selectedLanguage not provided']);
    exit;
}

$selectedLanguage = $_GET['selectedLanguage'];

// Include the database connection file
require_once('db.php');

// Assuming $conn is the connection object in db.php
global $conn;

// Your query to join and fetch data from both tables
$query = "SELECT bf.type, t.translation as label
          FROM brand_fields bf 
          JOIN label_presets lp ON bf.label_id = lp.id 
          JOIN label_preset_translations t ON lp.id = t.label_preset_id
          WHERE bf.brand_id = ? AND t.language_id = ?
          ORDER BY bf.order";

// Prepare and execute query
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $brandId, $selectedLanguage);
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch data
$fetchedData = $result->fetch_all(MYSQLI_ASSOC);

// Encode the fetched data as JSON
echo json_encode($fetchedData);

?>