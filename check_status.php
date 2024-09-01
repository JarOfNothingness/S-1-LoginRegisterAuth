<?php
session_start();
include("connection.php");

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
    
    // Check user status
    $query = "SELECT status FROM user WHERE userid = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    // Return the status as JSON
    echo json_encode(array('status' => $status));
}
?>