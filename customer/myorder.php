<?php
session_start();
include('../includes/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch orders for the logged-in user
$query = "SELECT o.id AS order_id, o.order_date, o.status, o.payment_status, 
                 s.service_name, o.weight, p.payment_method 
          FROM orders o
          JOIN services s ON o.service_id = s.id
          LEFT JOIN payments p ON o.id = p.order_id
          WHERE o.customer_id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$order_result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        /* Navigation Bar */
        nav {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav img {
            height: 50px;
            width: auto;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 15px;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #f39c12;
        }

        /* Main Container */
        .order-container {
            max-width: 800px;
            margin: 30px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            font-size: 22px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        /* Order List Styling */
        .order-list {
            list-style: none;
            padding: 0;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f4f4f4;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .order-status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            text-align: center;
        }

        /* Status Colors */
        .status-pending {
            background-color: #f39c12;
            color: white;
        }

        .status-completed {
            background-color: #28a745;
            color: white;
        }

        .status-cancelled {
            background-color: #e74c3c;
            color: white;
        }

        /* Footer Styling */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #000;
            color: white;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav>
    <div>
        <a href="dashboard.php"><img src="../assets/images/picture1.jpg" alt="Logo"></a>
    </div>
    <ul>
        <li><a href="dashboard.php">Services</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="myorder.php">My Order</a></li>
        <li><a href="#about">About Us</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<!-- Order Container -->
<div class="order-container">
    <h2>Your Orders</h2>

    <?php if (mysqli_num_rows($order_result) > 0): ?>
        <ul class="order-list">
            <?php while ($order = mysqli_fetch_assoc($order_result)): ?>
                <li class="order-item">
                    <div>
                        <strong>Service:</strong> <?php echo htmlspecialchars($order['service_name']); ?><br>
                        <strong>Weight:</strong> <?php echo htmlspecialchars($order['weight']); ?> kg<br>
                        <strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method'] ?? 'Not Paid'); ?>
                    </div>
                    <div class="order-status <?php echo 'status-' . strtolower($order['status']); ?>">
                        <?php echo htmlspecialchars($order['status']); ?>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 SM Company. All rights reserved.</p>
</footer>

</body>
</html>
