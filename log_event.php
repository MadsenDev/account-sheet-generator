<?php
session_start();
require_once 'db.php';
require_once 'admin/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event = $_POST['event'];
    eventLog($conn, $event, 'print');
}
?>