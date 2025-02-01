<?php 
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); 
    exit(); 
}


include('../db.php');


$pendingOrdersQuery = "SELECT COUNT(*) AS pending_orders FROM orders WHERE status = 'Pending'";
$pendingOrdersResult = mysqli_query($conn, $pendingOrdersQuery);
$pendingOrders = mysqli_fetch_assoc($pendingOrdersResult)['pending_orders'];

$completedOrdersQuery = "SELECT COUNT(*) AS completed_orders FROM orders WHERE status = 'Completed'";
$completedOrdersResult = mysqli_query($conn, $completedOrdersQuery);
$completedOrders = mysqli_fetch_assoc($completedOrdersResult)['completed_orders'];


$netProfitQuery = "SELECT SUM(o.weight * s.price_per_kg) AS net_profit 
                   FROM orders o
                   JOIN services s ON o.service_id = s.id
                   WHERE o.status = 'Completed'";

$netProfitResult = mysqli_query($conn, $netProfitQuery);
$netProfit = mysqli_fetch_assoc($netProfitResult)['net_profit'];


if ($netProfit === null) {
    $netProfit = 0;
}

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

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #dcdcdc; 
        }

     
nav {
    background-color: #000100; 
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
    background-color: #6f7769; 
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
    background-color: #d9534f; 
}

.nav-right a:hover {
    background-color: #c9302c; 
}


        .dashboard-container {
            max-width: 1200px;
            margin: 100px auto 50px; 
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
            ; 
        }

        .link-box a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }

        
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

     
        .stat-box .value {
            font-size: 30px;
            font-weight: bold;
            color: #40463b;
        }
    </style>
</head>
<body>

  
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


    <div class="dashboard-container">
        <h2>Welcome to the Admin Dashboard</h2>

     
        <div class="dashboard-links">
            <a href="manage_services.php" class="link-box">Manage Services</a>
            <a href="manage_orders.php" class="link-box">Manage Orders</a>
        </div>

 
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

