<?php 
// Include the database connection
include '../includes/db.php';

// Handle status update
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['req_id'], $_POST['status'])) {
    $req_id = intval($_POST['req_id']); // Sanitize input
    $status = $_POST['status']; // Direct value from dropdown

    // Validate status
    $valid_statuses = ['Pending', 'In Progress', 'Completed'];
    if (in_array($status, $valid_statuses)) {
        // Update the request status in the database
        $stmt = $conn->prepare("UPDATE request SET req_status = ? WHERE req_id = ?");
        $stmt->bind_param("si", $status, $req_id);
        if ($stmt->execute()) {
            $success_message = "Request #$req_id status updated to '$status'!";
        } else {
            $success_message = "Failed to update status for Request #$req_id.";
        }
        $stmt->close();
    }
}

// Fetch all requests
$requests = $conn->query("
    SELECT r.req_id, c.cust_name, r.req_details, r.req_status, SUM(s.serv_price * rs.reqserv_quantity) AS total_amount
    FROM request r
    INNER JOIN customer c ON r.cust_id = c.cust_id
    LEFT JOIN request_service rs ON r.req_id = rs.req_id
    LEFT JOIN service s ON rs.serv_id = s.serv_id
    GROUP BY r.req_id, c.cust_name, r.req_details, r.req_status
");

// Define request status options
$request_status_options = ['Pending', 'In Progress', 'Completed'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Requests</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #92d6ea;
            margin: 0;
            padding: 0;
        }

        /* Navbar Styling */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 10px; /* Increased padding for a taller navbar */
            background-color: #2c3e50;
            color: #fff;
        }

        .navbar .logo {
            font-size: 28px; /* Increased font size for the logo text */
            font-weight: bold;
            color: #fff; /* Ensure logo text is white */
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navbar ul li {
            margin: 0 15px; /* Increased space between navbar items */
        }

        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px; /* Slightly larger text for the navbar links */
            transition: color 0.3s ease;
        }

        .navbar ul li a:hover {
            color: #f39c12;
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
            color: #fff;
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
        <nav class="navbar">
            <div class="logo">
                <img src="../assets/images/logo.png" alt="My Laundry Service" style="max-height: 60px;"> <!-- Increased logo size -->
            </div>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_requests.php">Manage Requests</a></li>
                <li><a href="manage_services.php">Manage Services</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <h1>Manage Customer Requests</h1>
    <h2>Existing Requests</h2>

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
                <th>Request ID</th>
                <th>Customer Name</th>
                <th>Request Details</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $requests->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['req_id']) ?></td>
                <td><?= htmlspecialchars($row['cust_name']) ?></td>
                <td><?= htmlspecialchars($row['req_details']) ?></td>
                <td>RM <?= ($row['total_amount'] === null) ? 'N/A' : number_format($row['total_amount'], 2) ?></td>
                <td>
                    <form method="POST" action="">
                        <select name="status" onchange="this.form.submit()">
                            <?php foreach ($request_status_options as $status) { ?>
                                <option value="<?= $status ?>" <?= ($row['req_status'] === $status) ? 'selected' : '' ?>>
                                    <?= $status ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="hidden" name="req_id" value="<?= htmlspecialchars($row['req_id']) ?>">
                    </form>
                </td>
                <td>
                    <a href="delete_request.php?id=<?= htmlspecialchars($row['req_id']) ?>">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
