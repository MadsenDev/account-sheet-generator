<?php

header('Content-Type: application/json');

// Check if language_id is passed in GET request
if (!isset($_GET['language_id'])) {
    echo json_encode(['error' => 'language_id not provided']);
    exit;
}

$languageId = $_GET['language_id'];

// Include the database connection file
require_once('db.php');

// Assuming $conn is the connection object in db.php
global $conn;

// Your query to fetch translations from the site_label_translations table
$site_label_query = "SELECT sl.label, slt.translation 
          FROM site_label_translations slt 
          JOIN site_labels sl ON slt.site_label_id = sl.id
          WHERE slt.language_id = ?";

// Your query to fetch translations from the category_translations table
$category_query = "SELECT c.name, ct.translation 
          FROM category_translations ct 
          JOIN categories c ON ct.category_id = c.id
          WHERE ct.language_id = ?";

// Prepare and execute site label query
$stmt = $conn->prepare($site_label_query);
$stmt->bind_param("i", $languageId);
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch data
$site_labels = $result->fetch_all(MYSQLI_ASSOC);

// Prepare and execute category query
$stmt = $conn->prepare($category_query);
$stmt->bind_param("i", $languageId);
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch data
$categories = $result->fetch_all(MYSQLI_ASSOC);

// Combine site labels and categories
$fetchedData = [
    'site_labels' => $site_labels,
    'categories' => $categories
];

// Encode the fetched data as JSON
echo json_encode($fetchedData);

?>