<?php 
session_start();
require 'connection.php'; 

// If the user is not logged in, go to the login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

// Get the user's ID from the session
$user_id = $_SESSION['user_id'];

// Check if a class is selected from the URL (through GET request)
if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];

    // Get the current month and year for the comparison
    $currentMonth = date('m'); // Current month (01-12)
    $currentYear = date('Y');  // Current year (e.g., 2024)

    // Check if the user is already registered for the class in the current month and year
    $check_sql = "
        SELECT * 
        FROM class_registration 
        WHERE user_id = ? 
        AND class_id = ? 
        AND MONTH(registration_date) = ? 
        AND YEAR(registration_date) = ?
    ";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('iiii', $user_id, $class_id, $currentMonth, $currentYear);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // If the user is already registered for this class in the current month
        echo "<script>alert('You are already registered for this class for this month.'); window.location.href='classes.php';</script>";
        exit();
    }

    // No registration found for the current month, proceed with registration
    $insert_sql = "INSERT INTO class_registration (user_id, class_id, registration_date) VALUES (?, ?, NOW())"; // Use NOW() to store the current date and time

    if ($insert_stmt = $conn->prepare($insert_sql)) {
        $insert_stmt->bind_param('ii', $user_id, $class_id); // Bind user_id and class_id
        
        if ($insert_stmt->execute()) {
            // Registration successful
            echo "<script>alert('Registration successful!'); window.location.href='classes.php';</script>";
        } else {
            // Error during registration
            echo "<script>alert('There was an error registering for the class: " . $conn->error . "'); window.location.href='classes.php';</script>";
        }

        $insert_stmt->close();
    } else {
        // Error 
        echo "<script>alert('There was an error " . $conn->error . "'); window.location.href='classes.php';</script>";
    }

    $check_stmt->close();
} else {
    echo "<p>No class selected for registration.</p>";
}
?>


<!-- <?php 
session_start();
require 'connection.php'; 

// If the user is not logged in, go to the login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

// Get the user's ID from the session
$user_id = $_SESSION['user_id'];

// Check if the user is registered for a membership
$membership_check_sql = "SELECT * FROM membership_registration WHERE user_id = ?";
$membership_check_stmt = $conn->prepare($membership_check_sql);
$membership_check_stmt->bind_param('i', $user_id);
$membership_check_stmt->execute();
$membership_check_result = $membership_check_stmt->get_result();

if ($membership_check_result->num_rows == 0) {
    // User has no membership; restrict class registration
    echo "<script>alert('You must have an active membership to register for a class.'); window.location.href='membership.php';</script>";
    exit();
}

// Check if a class is selected from the URL (through GET request)
if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];

    // Check the last registration date for the selected class
    $check_sql = "SELECT * FROM class_registration WHERE user_id = ? AND class_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('ii', $user_id, $class_id); 
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $row = $check_result->fetch_assoc();
        $last_booking_date = new DateTime($row['registration_date']); 
        $current_date = new DateTime();
        $interval = $current_date->diff($last_booking_date);

        // Check if one month has passed since the last booking
        if ($interval->m < 1 || ($interval->m == 1 && $interval->d == 0)) {
            echo "<script>alert('You can only book this class again after one month.'); window.location.href='classes.php';</script>";
            exit();
        }
    }

    // No booking exists or more than a month has passed, proceed to register
    $insert_sql = "INSERT INTO class_registration (user_id, class_id, registration_date) VALUES (?, ?, NOW())"; // Use NOW() to store the current date and time

    if ($insert_stmt = $conn->prepare($insert_sql)) {
        $insert_stmt->bind_param('ii', $user_id, $class_id); // Bind user_id and class_id
        
        if ($insert_stmt->execute()) {
            // Registration successful
            echo "<script>alert('Registration successful!'); window.location.href='classes.php';</script>";
        } else {
            // Error during registration
            echo "<script>alert('There was an error registering for the class: " . $conn->error . "'); window.location.href='classes.php';</script>";
        }

        $insert_stmt->close();
    } else {
        // Error 
        echo "<script>alert('There was an error " . $conn->error . "'); window.location.href='classes.php';</script>";
    }

    $check_stmt->close();
} else {
    echo "<p>No class selected for registration.</p>";
}

// Close connection
$conn->close();
?> -->
