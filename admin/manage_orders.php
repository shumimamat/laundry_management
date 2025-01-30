<?php
// Include the database connection
include '../includes/db.php';

// Handle status update
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']); // Sanitize input
    $status = $_POST['status']; // Direct value from dropdown

    // Validate status
    $valid_statuses = ['Pending', 'In Progress', 'Completed', 'Cancelled'];
    if (in_array($status, $valid_statuses)) {
        // Update the order status in the database
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        if ($stmt->execute()) {
            $success_message = "Order #$order_id status updated to '$status'!";
        } else {
            $success_message = "Failed to update status for Order #$order_id.";
        }
        $stmt->close();
    }
}

// Fetch all orders
$orders = $conn->query("
    SELECT o.id, u.name AS customer_name, s.service_name, 
           o.weight * s.price_per_kg AS amount, o.status 
    FROM orders o
    INNER JOIN users u ON o.customer_id = u.id
    INNER JOIN services s ON o.service_id = s.id
");

// Define order status options
$order_status_options = ['Pending', 'In Progress', 'Completed', 'Cancelled'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        /* Navbar Styling */
        /* Navbar Styling */
nav {
    background-color: #000100; /* Dark background */
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
}

.nav-left img {
    height: 50px; /* Adjust logo size */
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

/* Centering Logout Button */
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


        h1, h2 {
            text-align: center;
            color: #2c3e50;
            margin: 30px 0;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #34495e;
            color: #323232;
        }

        tr:nth-child(even) {
            background-color: #ecf0f1;
        }

        tr:hover {
            background-color: #f39c12;
            color: white;
        }

        select {
            padding: 8px;
            border: 1px solid #ccc;
            background-color: #f4f4f9;
            border-radius: 5px;
        }

        select:focus {
            outline: none;
            border-color: #f39c12;
        }

        .alert {
            width: 90%;
            margin: 20px auto;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            background-color: #2ecc71;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .alert button {
            background: none;
            border: none;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            table {
                width: 100%;
            }

            .navbar ul {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar ul li {
                margin: 5px 0;
            }
        }
    </style>
    <script>
        // Auto-hide alert
        setTimeout(() => {
            const alertBox = document.querySelector('.alert');
            if (alertBox) alertBox.style.display = 'none';
        }, 5000);
    </script>
</head>
<body>
    <header>
    <nav>
        <div class="nav-left">
            <img src="../assets/images/logo.png" alt="Logo">
        </div>
        <div class="nav-center">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_orders.php">Manage Orders</a></li>
            </ul>
        </div>
        <div class="nav-right">
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    </header>

    <h1>Manage Customer Orders</h1>
    <h2>Existing Orders</h2>

    <!-- Display success message -->
    <?php if ($success_message): ?>
        <div class="alert">
            <?= htmlspecialchars($success_message) ?>
            <button onclick="this.parentElement.style.display='none'">×</button>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Service</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $orders->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                <td><?= htmlspecialchars($row['service_name']) ?></td>
                <td>RM <?= number_format($row['amount'], 2) ?></td>
                <td>
                    <form method="POST" action="">
                        <select name="status" onchange="this.form.submit()">
                            <?php foreach ($order_status_options as $status) { ?>
                                <option value="<?= $status ?>" <?= ($row['status'] === $status) ? 'selected' : '' ?>>
                                    <?= $status ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['id']) ?>">
                    </form>
                </td>
                <td>
                    <a href="delete_order.php?id=<?= htmlspecialchars($row['id']) ?>">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
