<?php
session_start();  // Start the session to access session variables

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();


header("Location: login.html");
exit();  // Make sure to exit after the redirect
?>
