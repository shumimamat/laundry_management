<?php 
session_start();

// Check if user is logged in as 'admin', if not redirect to login.php
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); // Redirect to login page if not logged in as admin
    exit(); // Stop further execution
}

// Database connection
include('../db.php');

// Fetch Pending Orders
$pendingOrdersQuery = "SELECT COUNT(*) AS pending_orders FROM orders WHERE status = 'Pending'";
$pendingOrdersResult = mysqli_query($conn, $pendingOrdersQuery);
$pendingOrders = mysqli_fetch_assoc($pendingOrdersResult)['pending_orders'];

// Fetch Completed Orders
$completedOrdersQuery = "SELECT COUNT(*) AS completed_orders FROM orders WHERE status = 'Completed'";
$completedOrdersResult = mysqli_query($conn, $completedOrdersQuery);
$completedOrders = mysqli_fetch_assoc($completedOrdersResult)['completed_orders'];

// Fetch Net Profit (Calculating based on completed orders and their associated services)
$netProfitQuery = "SELECT SUM(o.weight * s.price_per_kg) AS net_profit 
                   FROM orders o
                   JOIN services s ON o.service_id = s.id
                   WHERE o.status = 'Completed'";

$netProfitResult = mysqli_query($conn, $netProfitQuery);
$netProfit = mysqli_fetch_assoc($netProfitResult)['net_profit'];

// If net profit is null, set it to 0
if ($netProfit === null) {
    $netProfit = 0;
}

// Fetch Total Services
$totalServicesQuery = "SELECT COUNT(*) AS total_services FROM services";
$totalServicesResult = mysqli_query($conn, $totalServicesQuery);
$totalServices = mysqli_fetch_assoc($totalServicesResult)['total_services'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #dcdcdc; /* Updated background */
        }

        /* Navbar Styling */
        /* Navbar Styling */
nav {
    background-color: #000100; /* New background color */
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
    border-radius: 0 0 0px 0px;
}

.nav-left {
    font-size: 20px;
    font-weight: bold;
}

.nav-center {
    flex-grow: 1;
    display: flex;
    justify-content: center;
}

.nav-center ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 20px;
}

.nav-center ul li {
    display: inline;
}

.nav-center ul li a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    padding: 8px 15px;
    border-radius: 4px;
    transition: background 0.3s;
}

.nav-center ul li a:hover {
    background-color: #6f7769; /* Slightly darker greenish-gray */
}

.nav-right {
    display: flex;
    justify-content: center;
}

.nav-right a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    padding: 8px 15px;
    border-radius: 4px;
    transition: background 0.3s;
    background-color: #d9534f; /* Red logout button */
}

.nav-right a:hover {
    background-color: #c9302c; /* Darker red */
}


        .dashboard-container {
            max-width: 1200px;
            margin: 100px auto 50px; /* Added margin to prevent overlap with navbar */
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Dashboard Links */
        .dashboard-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .link-box {
            background-color: #40463b;
            padding: 20px;
            text-align: center;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, background 0.3s;
        }

        .link-box:hover {
            transform: scale(1.05);
            background-color: #6a6e67 
            ; /* Slightly darker blue on hover */
        }

        .link-box a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }

        /* Dashboard Stats */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .stat-box {
            background-color: #ffffff;
            padding: 20px;
            color: #333;
            font-weight: bold;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 1px solid #ddd;
        }

        .stat-box h4 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #40463b;
        }

        .stat-box p {
            font-size: 24px;
            margin: 0;
            color: #333;
        }

        /* Stat Title and Value Customization */
        .stat-box .value {
            font-size: 30px;
            font-weight: bold;
            color: #40463b;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav>
    <div class="nav-left">Admin Dashboard</div>
    <div class="nav-center">
        <ul>
            <li><a href="manage_services.php">Manage Services</a></li>
            <li><a href="manage_orders.php">Manage Orders</a></li>
        </ul>
    </div>
    <div class="nav-right">
        <a href="logout.php">Logout</a>
    </div>
</nav>

    <!-- Dashboard Section -->
    <div class="dashboard-container">
        <h2>Welcome to the Admin Dashboard</h2>

        <!-- Dashboard Links -->
        <div class="dashboard-links">
            <a href="manage_services.php" class="link-box">Manage Services</a>
            <a href="manage_orders.php" class="link-box">Manage Orders</a>
        </div>

        <!-- Dashboard Stats -->
        <div class="stats">
            <div class="stat-box">
                <h4>Pending Orders</h4>
                <p class="value"><?php echo $pendingOrders; ?></p>
            </div>
            <div class="stat-box">
                <h4>Completed Orders</h4>
                <p class="value"><?php echo $completedOrders; ?></p>
            </div>
            <div class="stat-box">
                <h4>Net Profit</h4>
                <p class="value">RM <?php echo number_format($netProfit, 2); ?></p>
            </div>
            <div class="stat-box">
                <h4>Total Services</h4>
                <p class="value"><?php echo $totalServices; ?></p>
            </div>
        </div>
    </div>

</body>
</html>

