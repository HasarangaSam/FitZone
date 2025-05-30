<?php
session_start();

include 'connection.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the user_id from session
$user_id = $_SESSION['user_id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];

    // Prepare SQL query to update user profile
    $updateSql = "
        UPDATE users 
        SET fname = ?, lname = ?, email = ? 
        WHERE id = ?
    ";

    if ($stmt = $conn->prepare($updateSql)) {
        $stmt->bind_param('sssi', $fname, $lname, $email, $user_id);
        if ($stmt->execute()) {
            echo "<script>alert('Profile updated successfully!'); window.location.href='customer.php';</script>";   
        } else {
            echo "Error updating profile: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error" . $conn->error;
    }
}

// Close the connection
$conn->close();

// Redirect back to the dashboard
header('Location: customer.php');
exit();
?>