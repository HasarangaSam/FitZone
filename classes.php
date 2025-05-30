<?php
include('connection.php');

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Classes</title>
  <link rel="icon" type="image/x-icon" href="images/logo.jpg">
  <link rel="stylesheet" href="style.css"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script>
         //Function to confirm to redirecting to login
         function confirmLogin() {
            if (confirm("You need to login before registering. Do you want to proceed to the login page?")) {
                window.location.href = 'login.html';
            }
        }

        //Function to confirm class registration
        function confirmClassRegistration(classId, programName) {
            const confirmation = confirm("Do you want to register for " + programName + "?");
            if (confirmation) {
                // Proceed with registration
                window.location.href = 'register_for_class.php?class_id=' + classId;
            }
        }

        // Function to filter class cards based on search input
        function filterClasses() {
            const searchInput = document.getElementById('search-input').value.toLowerCase();
        
            // Select class cards 
            const classCards = document.querySelectorAll('.class-card');

            // Filter class cards
            classCards.forEach(card => {
                const className = card.querySelector('h3').innerText.toLowerCase();
                const description = card.querySelector('p').innerText.toLowerCase();
            
                if (className.includes(searchInput) || description.includes(searchInput)) {
                card.style.display = 'block'; 
                } else {
                card.style.display = 'none'; 
                }
            });
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
          <li><a href="classes.php" class="active">Classes</a></li>
          <li><a href="training.php">Personal Training</a></li>
          <li><a href="blog.php">Blog</a></li>
          <li><a href="contact.php">Contact Us</a></li>
          <?php if ($isLoggedIn): ?>
              <li><a href="customer.php">My Account</a></li>
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

  <main>

  <section class="membership-message">
    <p>Already signed up as a customer? Fantastic! You’re now ready to enjoy our classes. Don’t miss out—register for this month’s sessions today!</p>
  </section>

  <section class="search-section">
    <div class="container">
        <input type="text" id="search-input" placeholder="Search for classes..." onkeyup="filterClasses()" />
        <button id="search-button" aria-label="Search">
            <i class="fas fa-search"></i>
        </button>
    </div>
</section>

<?php
// Fetch data from the fitness_programs table
$sql = "SELECT * FROM fitness_programs";
$result = $conn->query($sql);
?>

<section class="class-section">
    <div class="container">
        <h2 class="section-title">Our Fitness Classes</h2>
        <div class="class-cards">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="class-card">
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['program_name']) ?>">
                        <div class="card-content">
                            <h3><?= htmlspecialchars($row['program_name']) ?></h3>
                            <p><?= htmlspecialchars($row['description']) ?></p>
                            <p><strong>Specialties:</strong> <?= htmlspecialchars($row['specialties']) ?></p>
                            
                            <?php if ($isLoggedIn): ?>
                                <button class="btn-register" onclick="confirmClassRegistration(<?= $row['id'] ?>, '<?= addslashes($row['program_name']) ?>')">Register</button>
                            <?php else: ?>
                                <button class="btn-register" onclick="confirmLogin()">Register</button>
                            <?php endif; ?>
                        </div> <!-- Close card-content -->
                    </div> <!-- Close class-card -->
                <?php endwhile; ?>
            <?php else: ?>
                <p>No fitness programs found.</p>
            <?php endif; ?>
        </div> <!-- Close class-cards -->
    </div> <!-- Close container -->
</section>

<section class="class-table">
    <h3>Weekly Class Schedule</h3>
    <table class="class-schedule">    
            <tr>
                <th>Time</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
                <th>Sunday</th>
            </tr>      
            <?php
            $query = "SELECT cs.day_of_week, cs.start_time, fp.program_name AS class_name 
                  FROM class_schedule cs 
                  JOIN fitness_programs fp ON cs.class_id = fp.id"; 
            $result = $conn->query($query);
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $schedule = [];
            while ($row = $result->fetch_assoc()) {
                $schedule[$row['day_of_week']][] = $row;
            }

            // Time slots for the schedule
            $time_slots = [
                '6:00 AM - 7:00 AM' => ['Monday' => '', 'Tuesday' => '', 'Wednesday' => '', 'Thursday' => '', 'Friday' => '', 'Saturday' => '', 'Sunday' => ''],
                '7:30 AM - 8:30 AM' => ['Monday' => '', 'Tuesday' => '', 'Wednesday' => '', 'Thursday' => '', 'Friday' => '', 'Saturday' => '', 'Sunday' => ''],
                '9:00 AM - 10:00 AM' => ['Monday' => '', 'Tuesday' => '', 'Wednesday' => '', 'Thursday' => '', 'Friday' => '', 'Saturday' => '', 'Sunday' => ''],
                '5:00 PM - 6:00 PM' => ['Monday' => '', 'Tuesday' => '', 'Wednesday' => '', 'Thursday' => '', 'Friday' => '', 'Saturday' => '', 'Sunday' => ''],
                '6:30 PM - 7:30 PM' => ['Monday' => '', 'Tuesday' => '', 'Wednesday' => '', 'Thursday' => '', 'Friday' => '', 'Saturday' => '', 'Sunday' => '']
            ];

            // Populate the time slots
            foreach ($time_slots as $time => &$days) {
                foreach ($schedule as $day => $classes) {
                    foreach ($classes as $class) {
                        // Use explode to split the time
                        $timeRange = explode(' - ', $time);
                        if ($class['start_time'] == date('H:i:s', strtotime($timeRange[0]))) {
                            $days[$day] = $class['class_name'];
                        }
                    }
                }
            }

            // Display the schedule
            foreach ($time_slots as $time => $days) {
                echo '<tr>';
                echo '<td>' . $time . '</td>';
                foreach ($days as $day => $class) {
                    echo '<td>' . ($class ? $class : 'No Class') . '</td>';
                }
                echo '</tr>';
            }
            ?>
    </table>
</section>
</main> 

<?php include 'footer.php'; ?>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>


