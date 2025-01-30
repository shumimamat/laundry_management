<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Order status updated successfully.";
    } else {
        echo "Failed to update order status.";
    }

    $stmt->close();
    $conn->close();
}
