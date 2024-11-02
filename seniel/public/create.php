<?php
// Include the database and Developer class
include_once '../config/db.php';
include_once '../classes/Developer.php';

session_start(); // Start the session at the beginning of the file

// Check if the user is logged in by verifying if the 'username' session variable is set
if (!isset($_SESSION['username'])) {
     // If not logged in, redirect to login page
     header("Location: login.php");
     exit();
}

// Initialize the database and developer object
$database = new Database();
$db = $database->getConnection();
$crud = new crud($db);
$stmt1 = $crud->read("providers", "provider_id");

// Check if the form was submitted
if ($_POST) {
     // Set developer property values
     $crud->petName = htmlspecialchars(strip_tags($_POST['petName']));
     $crud->service = htmlspecialchars(strip_tags($_POST['service']));
     $crud->providerName = htmlspecialchars(strip_tags($_POST['provider']));
     $crud->appointDate = htmlspecialchars(strip_tags($_POST['appointDate']));

     // Create a new developer
     if ($crud->create()) {
          echo "<div>New appointment created.</div>";
     } else {
          echo "<div>Unable to create an appointment.</div>";
     }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Add Appointment</title>
     <link rel="stylesheet" href="assets/style.css">
</head>

<body>

     <h1>Add Appointment</h1>

     <!-- Form to add a new developer -->
     <form action="create.php" method="post">
          <label for="petName">Pet Name:</label><br>
          <input type="text" name="petName" required><br><br>

          <label for="service" required>Service:</label>
          <select name="service">
               <option value="">Select Service</option>
               <option value="Hair Cut">Hair Cut</option>
               <option value="Nail Cutting">Nail Cutting</option>
               <option value="Wash">Wash</option>
          </select><br><br>

          <label for="provider" required>Provider:</label>
          <select name="provider">
               <option value="">Select Provider</option>
               <?php foreach ($stmt1 as $provider): ?>
                    <option value="<?= htmlspecialchars($provider['provider_name']); ?>">
                         <?= htmlspecialchars($provider['provider_name']); ?>
                    </option>
               <?php endforeach; ?>
          </select><br><br>

          <label for="appointDate">Date:</label>
          <input type="date" name="appointDate"><br><br>

          <input type="submit" value="Save">
     </form>

     <br>
     <a href="index.php">Back to list</a>

</body>

</html>