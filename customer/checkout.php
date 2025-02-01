<?php
session_start();
include('../includes/db.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}


$query = "SELECT id FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) == 0) {
    echo "User not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['payment_method'])) {
        $payment_method = $_POST['payment_method'];

        foreach ($_SESSION['cart'] as $item) {
            $service_name = trim($item['service_name']); 
            $quantity = $item['quantity'];

          
            $service_query = "SELECT id, price_per_kg FROM services WHERE service_name = ? LIMIT 1";
            $stmt_service = mysqli_prepare($conn, $service_query);
            mysqli_stmt_bind_param($stmt_service, "s", $service_name);
            mysqli_stmt_execute($stmt_service);
            $service_result = mysqli_stmt_get_result($stmt_service);
            $service = mysqli_fetch_assoc($service_result);

            if ($service) {
                $service_id = $service['id'];
                $weight = $quantity * $service['price_per_kg']; 

                
                $order_query = "INSERT INTO orders (customer_id, service_id, weight, status, payment_status, order_date) 
                                VALUES (?, ?, ?, 'Pending', 'Pending', NOW())";
                $stmt_order = mysqli_prepare($conn, $order_query);
                mysqli_stmt_bind_param($stmt_order, "iid", $user_id, $service_id, $weight);

                if (mysqli_stmt_execute($stmt_order)) {
                    $order_id = mysqli_insert_id($conn); 

                    
                    $payment_query = "INSERT INTO payments (order_id, payment_method, payment_status) 
                                      VALUES (?, ?, 'Pending')";
                    $stmt_payment = mysqli_prepare($conn, $payment_query);
                    mysqli_stmt_bind_param($stmt_payment, "is", $order_id, $payment_method);

                    if (!mysqli_stmt_execute($stmt_payment)) {
                        echo "Payment error: " . mysqli_error($conn);
                        exit();
                    }
                } else {
                    echo "Order error: " . mysqli_error($conn);
                    exit();
                }
            } else {
                echo "<pre>Service '$service_name' not found in the database!</pre>"; 
                exit();
            }
        }

       
        unset($_SESSION['cart']);

       
        echo "<script>alert('Payment successful!'); window.location.href = 'myorder.php';</script>";
        exit();
    } else {
        echo "Please select a payment method.";
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .checkout-container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        p {
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }
        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .payment-method {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        .payment-method img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 5px;
        }
        .payment-method:hover {
            background-color: #e8e8e8;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .back-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #dc3545;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .back-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <h1>Checkout</h1>
    <p>Please select a payment method:</p>

    <form method="post">
        <div class="payment-methods">
            <label class="payment-method">
                <input type="radio" name="payment_method" value="COD" required>
                <img src="../assets/images/cod.png" alt="COD">
                Cash on Delivery (COD)
            </label>

            <label class="payment-method">
                <input type="radio" name="payment_method" value="Online Banking/FPX" required>
                <img src="../assets/images/fpx.png" alt="FPX">
                Online Banking / FPX
            </label>

            <label class="payment-method">
                <input type="radio" name="payment_method" value="Credit/Debit Card" required>
                <img src="../assets/images/card.png" alt="Card">
                Credit / Debit Card
            </label>

            <label class="payment-method">
                <input type="radio" name="payment_method" value="QR Pay" required>
                <img src="../assets/images/qr.png" alt="QR Pay">
                DuitNow QR
            </label>
        </div>

        <button type="submit" style="display: block; width: 100%; padding: 10px; background-color: #28a745; color: #fff; border: none; border-radius: 5px; font-size: 16px; margin-top: 20px; cursor: pointer;">Proceed to Payment</button>
    </form>

    <a href="cart.php" class="back-btn">Return to Cart</a>
</div>

</body>
</html>
