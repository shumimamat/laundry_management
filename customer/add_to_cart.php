<?php
session_start();
include '../includes/db.php';


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['service_id'])) {
    $service_id = (int)$_POST['service_id'];


    $stmt = $conn->prepare("SELECT service_name, price_per_kg FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $service_name = $row['service_name'];
        $price_per_kg = $row['price_per_kg'];

        echo "Service Name: $service_name<br>";
        echo "Price per KG: $price_per_kg<br>";


        $exists = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['service_id'] === $service_id) {
                $item['quantity'] += 1; 
                $exists = true;
                break;
            }
        }


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

    header("Location: dashboard.php");
    exit();
}
?>
