<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
header('login.html');

include 'connection.php'; // Include your database connection file

// Get the user_id from session
$user_id = $_SESSION['user_id'];

// Get current month and year
$currentMonth = date('m'); // Current month 
$currentYear = date('Y');  // Current year 

// Prepare SQL query to get class registration details
$classSql = "
    SELECT class_registration.registration_id, class_registration.registration_date, fitness_programs.program_name
    FROM users
    INNER JOIN class_registration ON users.id = class_registration.user_id
    INNER JOIN fitness_programs ON class_registration.class_id = fitness_programs.id
    WHERE users.id = ?
    AND MONTH(class_registration.registration_date) = ? 
    AND YEAR(class_registration.registration_date) = ?
";

// Prepare SQL query to get personal training registration details
$ptSql = "
    SELECT personal_training_registration.registration_id, personal_training_registration.booking_date, personal_training.trainer_name
    FROM users
    INNER JOIN personal_training_registration ON users.id = personal_training_registration.user_id
    INNER JOIN personal_training ON personal_training_registration.trainer_id = personal_training.id
    WHERE users.id = ?
    AND MONTH(personal_training_registration.registration_date) = ? 
    AND YEAR(personal_training_registration.registration_date) = ?
";

// Prepare SQL query to get membership registration details
$membershipSql = "
    SELECT membership_registration.registration_id, membership_registration.registration_date, membership_plans.plan_name
    FROM membership_registration
    INNER JOIN membership_plans ON membership_registration.membership_id = membership_plans.id
    WHERE membership_registration.user_id = ?
";

// Prepare SQL query to get customer-specific queries
$querySql = "
    SELECT created_at, message, respond
    FROM customer_queries 
    WHERE user_id = ?
";

// Prepare SQL query to get user profile details
$profileSql = "
    SELECT fname, lname, phone, email
    FROM users 
    WHERE id = ?
";

// Execute queries
$statements = [$classSql, $ptSql, $membershipSql, $querySql];
$results = [];

// Loop through queries and execute each one
foreach ($statements as $query) {
    if ($stmt = $conn->prepare($query)) {
        if (strpos($query, 'MONTH') !== false || strpos($query, 'YEAR') !== false) {
            // Bind the additional parameters for month and year
            $stmt->bind_param('iii', $user_id, $currentMonth, $currentYear);
        } else {
            $stmt->bind_param('i', $user_id);
        }
        $stmt->execute();
        $results[] = $stmt->get_result();
        $stmt->close();
    } else {
        echo "Error in query: " . $conn->error;
    }
}

// Fetch profile data
if ($stmt = $conn->prepare($profileSql)) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $profileResult = $stmt->get_result();
    $profileData = $profileResult->fetch_assoc();
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="icon" type="image/x-icon" href="images/logo.jpg">
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Dashboard Container */
        .dashboard-container {
            width: 100%;
            max-width: 1000px;
            margin: 20px auto; 
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            border-radius: 10px; 
        }

        /* Section Titles */
        h2 {
            color: #black; 
            border-bottom: 2px solid #ff5722; 
            padding-bottom: 10px; 
            margin-bottom: 10px;
            margin-top: 20px;
        }

        /* Form Styling */
        .form-group {
            margin-top: 25px;
            margin-bottom: 25px; 
        }

        .form-group label {
            display: block; 
            margin-bottom: 5px; 
            color: #333; 
        }

        .form-group input {
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            font-size: 14px; 
        }

        /* Buttons */
        .btn {
            padding: 10px 15px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold; 
            transition: background-color 0.3s ease; 
        }

        .btn-update {
            background-color: #4CAF50; 
            color: white; 
            margin-bottom: 20px;
            padding: 15px 20px;
        }

        .btn-update:hover {
            background-color: #45a049; 
        }

        .btn-delete {
            background-color: #f44336; 
            color: white; 
        }

        .btn-delete:hover {
            background-color: #e53935; 
        }

        /* Registration Items */
        .registration-item {
            border-bottom: 1px solid #ccc; 
            padding: 10px 0; 
            display: flex; /* Flex for alignment */
            justify-content: space-between; 
            align-items: center; 
        }

        .registration-item:last-child {
            border-bottom: none; 
        }

        .registration-item h3 {
            margin: 0; 
            color: #ff5722; 
            font-size:20px;
        }

        .registration-item p {
            margin: 5px 0; 
            color: #666; 
        }

        /* Icons */
        .icon {
            font-size: 1.2em; 
            color: #ff5722; 
        }
    </style>
    <script>
        function validate() {
            const fname = document.signupForm.fname.value;
            const lname = document.signupForm.lname.value;
            const email = document.signupForm.email.value;
            const contact = document.signupForm.contact.value;

            // First Name Validation
            if (fname === "") {
                alert("Please provide your first name");
                return false;
            }

            // Last Name Validation
            if (lname === "") {
                alert("Please provide your last name");
                return false;
            }

            // Email Validation
            if (email === "") {
                alert("Please provide your email");
                return false;
            }

            if (
                email.indexOf("@") <= 1 ||
                email.lastIndexOf(".") - email.indexOf("@") < 2
            ) {
                alert("Please enter a correct email address");
                return false;
            }

            // Contact Number Validation
            if (contact.length !== 10 || isNaN(contact)) {
                alert("Please enter a valid 10-digit contact number");
                return false;
            }

            // Submit Form
            document.signupForm.submit();
            return true;
        }
    </script>
</head>
<body>
<header class="header">
    <nav class="navigation">
      <img src="images/logo.jpg" alt="Logo" class="logo">
      <ul>
          <li><a href="home.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="membership.php">Memberships</a></li>
          <li><a href="classes.php">Classes</a></li>
          <li><a href="training.php">Personal Training</a></li>
          <li><a href="blog.php">Blog</a></li>
          <li><a href="contact.php">Contact Us</a></li>
          <?php if ($isLoggedIn): ?>
            <?php
                if (isset($_SESSION['user_type'])) {
                    switch ($_SESSION['user_type']) {
                        case 'Admin':
                            $myAccountUrl = 'admin_user.php';
                            break;
                        case 'Staff':
                            $myAccountUrl = 'staff_user.php';
                            break;
                        case 'Customer':
                            $myAccountUrl = 'customer.php';
                            break;
                        default:
                            $myAccountUrl = 'customer.php';
                            break;
                    }
                }
                ?>
                <li><a href="customer.php" class="active">My Account</a></li>
          <?php else: ?>
              <li><a href="login.html">My Account</a></li>
          <?php endif; ?>
          <?php if ($isLoggedIn): ?>
              <li><a href="logout.php" class="register-link">Logout</a></li>
          <?php else: ?>
              <li><a href="signup.html" class="register-link">Sign up</a></li>
          <?php endif; ?>
      </ul>
    </nav>
  </header>


  <div class="dashboard-container">
  <h2><i class="fas fa-user"></i> Your Profile</h2>
    <?php if ($profileData): ?>
        <p>Please review your profile details below and make changes where necessary. Click "Save Changes" to update your profile.</p>
        <form action="update_profile.php" method="POST" name="signupForm" onsubmit="return validate()">
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" value="<?= htmlspecialchars($profileData['fname']) ?>" required>
            </div>
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" value="<?= htmlspecialchars($profileData['lname']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($profileData['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact Number:</label>
                <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($profileData['phone']) ?>" required>
            </div>
            <button type="submit" class="btn btn-update">Save Changes</button>
        </form>
    <?php else: ?>
        <p>No profile information found.</p>
    <?php endif; ?>
    <br>

    <h2><i class="fas fa-gem"></i> Your Membership Plan</h2>
    <?php if ($results[2]->num_rows > 0): ?>
        <?php while ($row = $results[2]->fetch_assoc()): ?>
            <div class="registration-item">
                <div>
                    <h3><?= htmlspecialchars($row['plan_name']) ?></h3>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No membership registrations found.</p>
    <?php endif; ?>
    <br>

    <h2><i class="fas fa-list"></i> Class Registrations for this month</h2>
    <?php if ($results[0]->num_rows > 0): ?>
        <?php while ($row = $results[0]->fetch_assoc()): ?>
            <div class="registration-item">
                <div>
                    <h3><?= htmlspecialchars($row['program_name']) ?></h3>
                    <p>Registration Date: <?= htmlspecialchars($row['registration_date']) ?></p>
                </div>
                <div class="action-buttons">
                    <form action="delete_class_registration.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this registration?');">
                        <input type="hidden" name="registration_id" value="<?= $row['registration_id'] ?>">
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No class registrations found.</p>
    <?php endif; ?>
    <br>
    <h2><i class="fas fa-user-tie"></i> Personal Training Registrations for this month</h2>
    <?php if ($results[1]->num_rows > 0): ?>
        <?php while ($row = $results[1]->fetch_assoc()): ?>
            <div class="registration-item">
                <div>
                    <h3>Trainer: <?= htmlspecialchars($row['trainer_name']) ?></h3>
                    <p>Booking Date: <?= htmlspecialchars($row['booking_date']) ?></p>
                </div>
                <div class="action-buttons">
                    <form action="delete_training_registration.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this registration?');">
                        <input type="hidden" name="registration_id" value="<?= $row['registration_id'] ?>">
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No personal training registrations found.</p>
    <?php endif; ?>
    <br>

    <h2><i class="fas fa-envelope"></i> Your Queries</h2>
    <?php if ($results[3]->num_rows > 0): ?>
        <?php while ($row = $results[3]->fetch_assoc()): ?>
            <div class="registration-item">
                <div>
                    <h3>Message: <?= htmlspecialchars($row['message']) ?></h3>
                    <h3>Respond: <?= htmlspecialchars($row['respond']) ?></h3>
                    <p>Submitted on: <?= htmlspecialchars($row['created_at']) ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No queries found.</p>
    <?php endif; ?>
</div>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?> 

