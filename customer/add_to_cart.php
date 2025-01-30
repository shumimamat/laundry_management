<?php
session_start();
include '../includes/db.php';

// Handle adding to cart
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['service_id'])) {
    $service_id = (int)$_POST['service_id'];

    // Fetch the service details from the database
    $stmt = $conn->prepare("SELECT service_name, price_per_kg FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $service_name = $row['service_name'];
        $price_per_kg = $row['price_per_kg'];

        // Debugging: Output service data to ensure proper fetching
        echo "Service Name: $service_name<br>";
        echo "Price per KG: $price_per_kg<br>";

        // Check if the service already exists in the cart
        $exists = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['service_id'] === $service_id) {
                $item['quantity'] += 1;  // Increase quantity if item already in cart
                $exists = true;
                break;
            }
        }

        // If service does not exist in cart, add it
        if (!$exists) {
            $_SESSION['cart'][] = [
                'service_id'   => $service_id,
                'service_name' => $service_name,
                'price_per_kg' => $price_per_kg,
                'quantity'     => 1
            ];
        }
    } else {
        echo "Service not found in the database.<br>";
    }

    $stmt->close();

    // Redirect back to the dashboard
    header("Location: dashboard.php");
    exit();
}
?>
