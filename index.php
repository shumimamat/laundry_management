<?php 
require 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Amarylis Laundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
 
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: url('assets/images/bg3.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding-top: 80px; 
            scroll-behavior: smooth; 
        }


        nav {
            width: 100%;
            padding: 15px 20px;
            background-color: rgba(0, 0, 0, 0.8);
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        nav img {
            height: 50px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        nav ul li a:hover {
            background: #f39c12;
            color: #000;
        }

        /* Main Content */
        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 60px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            margin-top: 120px;
            max-width: 800px;
            width: 100%;
        }

        h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 30px;
            line-height: 1.3;
            color: #f39c12;
        }

        p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            line-height: 1.6;
            font-weight: 500;
        }

        .role-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .role-buttons a {
            text-decoration: none;
            padding: 15px 30px;
            background-color: #f39c12;
            color: #000;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 5px;
            transition: background 0.3s ease;
            display: inline-block;
        }

        .role-buttons a:hover {
            background-color: #e67e22;
        }


        #location {
            width: 100%;
            background-color: #fdf6e3; 
            padding: 50px 20px;
            color: #333;
            margin-top: 50px;
        }

        #location h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 30px;
            text-align: center;
        }

        .location-content {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            align-items: center;
        }

        .location-content img {
            width: 40%;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .location-list {
            width: 50%;
            list-style: none;
            padding-left: 20px;
        }

        .location-list li {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

     
        #about {
            width: 100%;
            background-color: #fff;
            padding: 50px 20px;
            color: #333;
            margin-top: 50px;
        }

        #about h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 30px;
            text-align: center;
        }

        .about-images {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            margin-bottom: 40px;
        }

        .about-image {
            width: 30%;
            text-align: center;
        }

        .about-image img {
            width: 300px;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .about-image p {
            margin-top: 10px;
            font-size: 1.1rem;
            color: #f39c12;
        }

   
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            width: 100%;
        }

        footer p {
            margin: 0;
            font-size: 1.2rem;
            font-family: 'Montserrat', sans-serif; 
        }
    </style>
</head>
<body>
 
    <nav>
        <img src="assets/images/logo.png" alt="Laundry Management System Logo">
        <ul>
            <li><a href="admin/dashboard.php">Admin</a></li>
            <li><a href="login.php?role=customer">Customer</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#location">Location</a></li> 
        </ul>
    </nav>

    <div class="container">
        <h1>Welcome to Amarilys Laundry</h1>
        <p>Which one are you?</p>

        <div class="role-buttons">
            <a href="admin/dashboard.php">Admin</a>
            <a href="login.php?role=customer">Customer</a>
        </div>
    </div>


    <div id="location">
        <h2>Our Locations</h2>
        <div class="location-content">
            <img src="assets/images/location.jpg" alt="Location Image">
            <ul class="location-list">
                <li>Bazar Mara Machang is located in Kampung Sungai Hala, Machang, Kelantan. The full address is:

Bazar Mara Machang Kampung Sungai Hala 18500 Machang Kelantan

This location is situated near the Machang District in Kelantan, Malaysia.</li>
            </ul>
        </div>
    </div>


    <div id="about">
        <h2>About Us</h2>
        <div class="about-images">
            <div class="about-image">
                <img src="assets/images/about1.png" alt="About Image 1">
                <p>SHUHAIMI BIN MAMAT</p>
                <p>2022822262</p>
            </div>
            <div class="about-image">
                <img src="assets/images/about2.png" alt="About Image 2">
                <p>NUR AINA BALKHIS BINTI ANUAR</p>
                <p>2022604792</p>
            </div>
            <div class="about-image">
                <img src="assets/images/about3.png" alt="About Image 3">
                <p>UMIYATUL AUNI BINI HANAPI</p>
                <p>2022464628</p>
            </div>
        </div>
    </div>


    <footer>
        <p>&copy; 2025 Amarylis Laundry. All rights reserved.</p>
    </footer>

    <?php $conn->close(); ?>
</body>
</html>
