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

// Check if a plan is selected (through GET request)
if (isset($_GET['plan_id'])) {
    $plan_id = $_GET['plan_id'];

    // Check if the user already has a membership registration
    $check_sql = "SELECT * FROM membership_registration WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('i', $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $user_row = $check_result->fetch_assoc();

    if ($user_row) {
        // User is already registered for a membership
        echo "<script>alert('You are already registered for a membership plan.'); window.location.href='membership.php';</script>";
    } else {
        // Proceed to register for the new membership plan
        $membership_sql = "SELECT id FROM membership_plans WHERE id = ?";
        $membership_stmt = $conn->prepare($membership_sql);
        $membership_stmt->bind_param('i', $plan_id);
        $membership_stmt->execute();
        $membership_result = $membership_stmt->get_result();
        $membership_row = $membership_result->fetch_assoc();

        if ($membership_row) {
            // Insert new membership registration
            $new_membership_id = $membership_row['id'];
            $insert_sql = "INSERT INTO membership_registration (user_id, membership_id, registration_date) VALUES (?, ?, CURRENT_TIMESTAMP)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param('ii', $user_id, $new_membership_id);

            if ($insert_stmt->execute()) {
                echo "<script>alert('Membership registration successful!'); window.location.href='membership.php';</script>";
            } else {
                echo "<script>alert('Error registering membership: " . $conn->error . "'); window.location.href='membership.php';</script>";
            }
        } else {
            // Invalid plan selected
            echo "<script>alert('Invalid plan selected.'); window.location.href='membership.php';</script>";
        }
    }

    // Close prepared statements
    $check_stmt->close();
    $membership_stmt->close();
    $insert_stmt->close();
} else {
    echo "<p>No plan selected for registration.</p>";
}

// Close connection
$conn->close();
?>

