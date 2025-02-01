<?php
include '../includes/db.php';

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $conn->query("DELETE FROM orders WHERE id = $order_id");
    header('Location: manage_orders.php');
}
?>
