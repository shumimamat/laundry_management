<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include('../includes/db.php');

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $service_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Prepare the DELETE query
    $query = "DELETE FROM service WHERE serv_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $service_id);

    // Execute the query and check if it was successful
    if (mysqli_stmt_execute($stmt)) {
        // Redirect back to the manage services page with a success message
        header("Location: manage_services.php?status=success&message=Service deleted successfully.");
    } else {
        // If there was an error deleting, redirect with an error message
        header("Location: manage_services.php?status=error&message=Failed to delete service.");
    }

    mysqli_stmt_close($stmt);
} else {
    // If 'id' is not set, redirect with an error message
    header("Location: manage_services.php?status=error&message=Service ID is missing.");
}

mysqli_close($conn);
?>
