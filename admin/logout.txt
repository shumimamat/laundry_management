<?php
// Start the session
session_start();

// Destroy the session to log the admin out
session_destroy();

// Redirect to the login page (create a login page if you don't have one)
header('Location: ../index.php');
exit();
?>
