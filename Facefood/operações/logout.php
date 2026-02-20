<?php
session_start();

// Destroy the session
session_destroy();

// Clear session variables
$_SESSION = [];

// Redirect to login page
header("Location: login.php");
exit();
?>