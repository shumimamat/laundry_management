<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include('../includes/db.php');

// Check if there is a status in the URL (for success or error messages)
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $message = $_GET['message'];
    echo "
    <div class='alert-container'>
        <div class='alert " . ($status == 'success' ? 'success' : 'error') . "'>
            $message
        </div>
    </div>
    ";
}

// Handle form submission for adding a new service
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $serv_name = mysqli_real_escape_string($conn, $_POST['serv_name']);
    $serv_price = mysqli_real_escape_string($conn, $_POST['serv_price']);
    $serv_desc = mysqli_real_escape_string($conn, $_POST['serv_desc']);
    $serv_availability = 'Available'; // Default availability value
    
    // Insert the new service into the database
    $query = "INSERT INTO services (service_name, price_per_kg, description, availability) 
              VALUES ('$serv_name', '$serv_price', '$serv_desc', '$serv_availability')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: manage_services.php?status=success&message=Service added successfully.");
    } else {
        header("Location: manage_services.php?status=error&message=Failed to add service.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Add your custom CSS styles here -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

       
       /* Navbar Styling */
nav {
    background-color: #000100; /* Updated background color */
    color: #000100;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
    border-radius: 0 0 0px 0px;
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


        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 600px;
            margin: 0 auto;
        }

        form input, form textarea, form button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            background-color: #2c3e50;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #f39c12;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        table th {
            background-color: #2c3e50;
            color: white;
        }

        table tr:hover {
            background-color: #f39c12;
            color: white;
        }

        .alert {
            text-align: center;
            padding: 15px;
            background-color: #27ae60;
            color: white;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 18px;
        }

        .error {
            background-color: #e74c3c;
        }

        .success {
            background-color: #2ecc71;
        }

        .alert a {
            color: #fff;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>

   <!-- Navbar -->
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

<div class="container">
    <h2>Manage Services</h2>

    <!-- Add new service form -->
    <form method="POST" action="manage_services.php">
        <label for="serv_name">Service Name:</label>
        <input type="text" id="serv_name" name="serv_name" required>
        
        <label for="serv_price">Price per KG:</label>
        <input type="text" id="serv_price" name="serv_price" required>
        
        <label for="serv_desc">Description:</label>
        <textarea id="serv_desc" name="serv_desc" rows="4" required></textarea>
        
        <button type="submit">Add Service</button>
    </form>

    <!-- Display existing services in a table -->
    <table>
        <thead>
            <tr>
                <th>Service Name</th>
                <th>Price per KG</th>
                <th>Description</th>
                <th>Availability</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch services from the database
            $query = "SELECT * FROM services";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                        <tr>
                            <td>' . $row['service_name'] . '</td>
                            <td>' . $row['price_per_kg'] . '</td>
                            <td>' . $row['description'] . '</td>
                            <td>' . $row['availability'] . '</td>
                            <td><a href="delete_service.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this service?\')">Delete</a></td>
                        </tr>
                    ';
                }
            } else {
                echo '<tr><td colspan="5">No services found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
