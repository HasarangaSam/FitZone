<?php
session_start(); // Start the session
include "connection.php"; 

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a SQL statement to fetch user data based on the email
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    // Check if the user exists
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc(); // Fetch user data

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a session and store user data
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['user_type'] = $user['userType']; 

            // Redirect based on user type
            switch ($user['userType']) {
                case 'Admin':
                    echo "<script>alert('Welcome Admin'); window.location.href='admin_user.php';</script>";
                    break;
                case 'Customer':
                    echo "<script>alert('Login Successfull'); window.location.href='home.php';</script>";
                    break;
                case 'Staff':
                    echo "<script>alert('Login Successfull'); window.location.href='staff_user.php';</script>";
                    break;
                default:
                    header("Location: home.php"); // Fallback if user type doesn't match
            }
            exit(); // Ensure no further code is executed after redirection
        } else {
            echo "<script>alert('Incorrect username or password. Try Again!'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('No user found with that email.'); window.location.href='login.html';</script>";
    }

    // Close the result set
    $result->close();
}

// Close the database connection
$conn->close();
?>
