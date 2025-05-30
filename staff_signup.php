<?php
session_start(); // Start the session
include "connection.php"; 

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no']; 
    $gender = $_POST['gender'];
    $password = $_POST['pw'];
    $userType = 'Staff';

    // Check if the email already exists in the database
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmailQuery);

    // If the email exists, show an error message
    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists.'); window.location.href='staff_signup.html';</script>";
        exit(); // Stop further execution
    }

    // If the email does not exist, proceed to register the user
    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the INSERT statement to add a new user
    $insertQuery = "INSERT INTO users (fname, lname, email, phone, gender, password, userType) 
                    VALUES ('$fname', '$lname', '$email', '$contact_no', '$gender', '$hashedPassword', '$userType')";

    // Execute the insert statement and check for success
    if ($conn->query($insertQuery) === TRUE) {
        echo "<script>alert('Registration successful.'); window.location.href='login.html';</script>";
        exit(); // Stop further execution
    } else {
        echo "<script>alert('Error occured, please try again later.'); window.location.href='signup.html';</script>";
    }
}

// Close the database connection
$conn->close();
?>