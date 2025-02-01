<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'customer') {
    header("Location: ../login.php");
    exit();
}

include('../includes/db.php');

$query = "SELECT * FROM services";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Services</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Available Services</h2>
    <ul>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <li>
                <strong><?php echo $row['service_name']; ?></strong><br>
                Price per KG: RM <?php echo $row['price_per_kg']; ?><br>
                Description: <?php echo $row['description']; ?><br>
                <a href="order_service.php?service_id=<?php echo $row['id']; ?>">Order Now</a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
