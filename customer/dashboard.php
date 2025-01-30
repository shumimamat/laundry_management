<?php
session_start();
include('../includes/db.php'); // Database connection

// Initialize the cart array if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Initialize the message variable
$message = '';

// Check if the Add to Cart form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // Get form data
    $service_name = $_POST['service_name'];
    $quantity = (int)$_POST['quantity'];

    // Check if the item is already in the cart
    $item_found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['service_name'] === $service_name) {
            $item['quantity'] += $quantity; // Update quantity if item exists
            $item_found = true;
            break;
        }
    }
    unset($item); // Unset reference to prevent unexpected behavior

    // If item is not already in the cart, add it as a new entry
    if (!$item_found) {
        $_SESSION['cart'][] = [
            'service_name' => $service_name,
            'quantity' => $quantity,
        ];
    }

    // Set the success message
    $message = 'Services added to cart!';

    // Store this flag in session to display the popup
    $_SESSION['show_popup'] = true;

    // Redirect back to the same page to prevent resubmission
    header("Location: dashboard.php");
    exit();
}

// Check if the popup should be shown
$show_popup = isset($_SESSION['show_popup']) ? $_SESSION['show_popup'] : false;

// After showing the popup, unset the session variable to prevent it from showing again
if ($show_popup) {
    unset($_SESSION['show_popup']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Increase quantity
            document.querySelectorAll('.increase-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const input = button.previousElementSibling;
                    let value = parseInt(input.value);
                    input.value = value + 1;
                });
            });

            // Decrease quantity
            document.querySelectorAll('.decrease-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const input = button.nextElementSibling;
                    let value = parseInt(input.value);
                    if (value > 1) input.value = value - 1;
                });
            });

            // Show popup when service added to cart
            <?php if ($show_popup): ?>
                document.getElementById('popup').style.display = 'block';
            <?php endif; ?>
        });
    </script>
    <style>
    body {
    background: url('../assets/images/bg5.jpg') no-repeat center center fixed;
    background-size: cover;
    position: relative;
}

body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('../assets/images/bg5.jpg') no-repeat center center fixed;
    background-size: cover;
    filter: blur(10px); /* Adjust the blur intensity here */
    z-index: -1;
}

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
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}

.content-wrapper {
    text-align: center;
    padding: 40px;
}

h1 {
    font:optima;
    color:#f39c12;
    font-size: 80px
}

.services-list {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
}

.service-card {
    background: #ffffff; /* Solid white color */
    padding: 20px;
    border-radius: 10px;
    width: 250px;
    text-align: center;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.service-card:hover {
    transform: scale(1.05); /* Hover effect */
}

.service-card img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
}

.service-card h4 {
    margin-top: 10px;
    font-size: 20px;
    color: #555;
}

.service-card p {
    font-size: 18px;
    color: #131512; /* Golden color for price */
}

.quantity-controls {
    display: flex;
    justify-content: center;
    margin: 10px 0;
}

.quantity-controls button {
    background-color: #131512; /* Set the button color */
    color: white; /* Set text color to white for contrast */
    padding: 10px;
    margin: 0 5px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
}

.quantity-controls button:hover {
    background-color: #3a3a3a; /* Optional: darken the color on hover */
}

.btn {
    background-color: #131512;
    border: none;
    color: white;
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
}

.btn:hover {
    background-color: #ec971f;
}

.category-title {
    font-size: 24px;
    color:rgb(255, 255, 255);
    margin-top: 40px;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.message {
    font-size: 18px;
    color:#131512;
    margin-bottom: 20px;
}

footer {
    text-align: center;
    padding: 20px;
    background-color: #000;
    color: white;
    margin-top: 40px;
}

.popup {
    position: fixed;
    top: 20%;
    left: 50%;
    transform: translateX(-50%);
    background-color: #333;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    display: none;
    color: #fff;
}

.popup .close-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 20px;
    cursor: pointer;
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

<div class="content-wrapper">
    <h1> AMARYLIS LAUNDRY</h1>

    <!-- Display success message if exists -->
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Laundry Categories -->
    <section id="services">
        <!-- Normal Wash Category -->
        <h2 class="category-title">Normal Wash</h2>
        <div class="services-list">
            <?php
            $normal_wash_services = [
                ['name' => 'Laundry', 'price' => 'RM 3.50/kg', 'image' => 'service1.jpg'],
                ['name' => 'Hand Wash', 'price' => 'RM 1.50/pc', 'image' => 'service2.jpg'],
                ['name' => 'Comforter', 'price' => 'RM 10.00/plot', 'image' => 'service5.jpeg'],
            ];
            
            foreach ($normal_wash_services as $service) {
                echo '
                    <div class="service-card">
                        <img src="../assets/images/' . $service['image'] . '" alt="' . $service['name'] . '">
                        <h4>' . $service['name'] . '</h4>
                        <p>' . $service['price'] . '</p>
                        <form method="post">
                            <input type="hidden" name="service_name" value="' . $service['name'] . '">
                            <div class="quantity-controls">
                                <button type="button" class="decrease-btn">-</button>
                                <input type="number" name="quantity" value="1" min="1" max="10">
                                <button type="button" class="increase-btn">+</button>
                            </div>
                            <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                        </form>
                    </div>
                ';
            }
            ?>
        </div>

        <!-- Dry Cleaning Category -->
        <h2 class="category-title">Dry Cleaning</h2>
        <div class="services-list">
            <?php
            $dry_cleaning_services = [
                ['name' => 'Jacket', 'price' => 'RM 12.00/pc', 'image' => 'service12.jpg'],
                ['name' => 'Coat', 'price' => 'RM 8.00/pc', 'image' => 'service6.jpg'],
            ];
            
            foreach ($dry_cleaning_services as $service) {
                echo '
                    <div class="service-card">
                        <img src="../assets/images/' . $service['image'] . '" alt="' . $service['name'] . '">
                        <h4>' . $service['name'] . '</h4>
                        <p>' . $service['price'] . '</p>
                        <form method="post">
                            <input type="hidden" name="service_name" value="' . $service['name'] . '">
                            <div class="quantity-controls">
                                <button type="button" class="decrease-btn">-</button>
                                <input type="number" name="quantity" value="1" min="1" max="10">
                                <button type="button" class="increase-btn">+</button>
                            </div>
                            <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                        </form>
                    </div>
                ';
            }
            ?>
        </div>

        <!-- Ironing Category -->
        <h2 class="category-title">Ironing</h2>
        <div class="services-list">
            <?php
            $ironing_services = [
                ['name' => 'Shirt Ironing', 'price' => 'RM 2.00/pc', 'image' => 'service14.jpeg'],
                ['name' => 'Pants Ironing', 'price' => 'RM 3.00/pc', 'image' => 'service13.jpg'],
            ];
            
            foreach ($ironing_services as $service) {
                echo '
                    <div class="service-card">
                        <img src="../assets/images/' . $service['image'] . '" alt="' . $service['name'] . '">
                        <h4>' . $service['name'] . '</h4>
                        <p>' . $service['price'] . '</p>
                        <form method="post">
                            <input type="hidden" name="service_name" value="' . $service['name'] . '">
                            <div class="quantity-controls">
                                <button type="button" class="decrease-btn">-</button>
                                <input type="number" name="quantity" value="1" min="1" max="10">
                                <button type="button" class="increase-btn">+</button>
                            </div>
                            <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                        </form>
                    </div>
                ';
            }
            ?>
        </div>
    </section>
</div>

<!-- Popup -->
<div id="popup" class="popup">
    <span class="close-btn" onclick="document.getElementById('popup').style.display='none'">&times;</span>
    <p>Services added to cart!</p>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 SM Company. All rights reserved.</p>
</footer>

</body>
</html>
