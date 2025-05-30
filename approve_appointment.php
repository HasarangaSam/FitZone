<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Redirect to login if not authenticated
    exit();
}
  
include 'connection.php';

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // Fetch the pending training data to insert into personal_training_registration
    $stmt = $conn->prepare("SELECT user_id, trainer_id, booking_date FROM pending_training WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $user_id = $row['user_id'];
        $trainer_id = $row['trainer_id'];
        $booking_date = $row['booking_date'];

        // Insert the data into personal_training_registration
        $stmt = $conn->prepare("INSERT INTO personal_training_registration (user_id, trainer_id, booking_date) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $trainer_id, $booking_date);
        $stmt->execute();

        // Delete the data from pending_training
        $stmt = $conn->prepare("DELETE FROM pending_training WHERE booking_id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();

        header("Location: admin_appointments.php");
    } else {
        echo "No data found for the given booking ID.";
    }
} else {
    echo "Invalid request.";
}
?>

<?php
// Close the database connection
$conn->close();
?>

