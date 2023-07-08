
<!-- This is the PHP file that will be called by the JavaScript file. It will fetch the data from the database and return it to the JavaScript file. -->
<?php
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');

  require_once('db.php');

  // Fetch data from database
  $id = intval($_GET['id']);
  $sql = "SELECT info FROM brands WHERE id = ?";
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
