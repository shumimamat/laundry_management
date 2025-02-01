<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $index => $new_quantity) {
        $_SESSION['cart'][$index]['quantity'] = max(1, (int)$new_quantity); 
    }
    header("Location: cart.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $index_to_remove = (int)$_POST['remove_item'];
    array_splice($_SESSION['cart'], $index_to_remove, 1); 
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }

        nav {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
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

        h1 {
            text-align: center;
            margin-top: 80px;
            color: #34495e;
        }

        .cart-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            text-align: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: rgb(0, 0, 0);
            color: white;
        }

        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            background-color: rgb(0, 0, 0);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #e67e22;
        }

        .btn-danger {
            background-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
        }

        .remove-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .remove-btn:hover {
            background-color: #c0392b;
        }

        .total {
            font-weight: bold;
            font-size: 1.2em;
        }

        .empty-message {
            text-align: center;
            font-size: 1.2em;
            color: #555;
        }

        footer {
            background-color: rgb(0, 0, 0);
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
    </style>
</head>
<body>

<header>

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
</header>

<h1>Your Cart</h1>

<div class="cart-container">
    <?php if (!empty($_SESSION['cart'])): ?>
        <form method="post">
            <table>
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Quantity</th>
                        <th>Price (RM)</th>
                        <th>Total (RM)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_price = 0;
                    foreach ($_SESSION['cart'] as $index => $item):
                       
                        $price_per_item = ($item['service_name'] === "Cucian Biasa") ? 3.50 : 8.00;
                        $item_total = $price_per_item * $item['quantity'];
                        $total_price += $item_total;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['service_name']); ?></td>
                            <td>
                                <input 
                                    type="number" 
                                    name="quantity[<?= $index; ?>]" 
                                    value="<?= $item['quantity']; ?>" 
                                    class="quantity-input" 
                                    min="1" 
                                    max="10">
                            </td>
                            <td><?= number_format($price_per_item, 2); ?></td>
                            <td><?= number_format($item_total, 2); ?></td>
                            <td>
                                <button type="submit" name="remove_item" value="<?= $index; ?>" class="remove-btn">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total">Total Price:</td>
                        <td colspan="2" class="total">RM <?= number_format($total_price, 2); ?></td>
                    </tr>
                </tfoot>
            </table>

            <div class="cart-actions">
                <button type="submit" name="update_cart" class="btn">Update Cart</button>
                <button type="submit" name="clear_cart" class="btn btn-danger">Clear Cart</button>
                <a href="checkout.php" class="btn">Proceed to Checkout</a>
            </div>
        </form>
    <?php else: ?>
        <p class="empty-message">Your cart is empty!</p>
        <a href="dashboard.php" class="btn">Return to Dashboard</a>
    <?php endif; ?>
</div>


<footer>
    <p>&copy; 2025 SM Company. All rights reserved.</p>
</footer>

</body>
</html>
