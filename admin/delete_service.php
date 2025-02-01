<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include('../includes/db.php');


if (isset($_GET['id'])) {
    $service_id = mysqli_real_escape_string($conn, $_GET['id']);


    $query = "DELETE FROM service WHERE serv_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $service_id);


    if (mysqli_stmt_execute($stmt)) {

        header("Location: manage_services.php?status=success&message=Service deleted successfully.");
    } else {

        header("Location: manage_services.php?status=error&message=Failed to delete service.");
    }

    mysqli_stmt_close($stmt);
} else {

    header("Location: manage_services.php?status=error&message=Service ID is missing.");
}

mysqli_close($conn);
?>
