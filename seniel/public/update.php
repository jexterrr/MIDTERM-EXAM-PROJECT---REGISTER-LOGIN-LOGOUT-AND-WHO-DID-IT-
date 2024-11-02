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

// Get ID of the developer to be edited
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Appointment ID not found.');

// Set ID property of developer to be edited
$crud->id = $id;

// Get the current data of the developer
$crud->readOne();
$stmt1 = $crud->read("providers", "provider_id");

// Check if the form was submitted
if ($_POST) {
     // Set developer property values
     $crud->petName = htmlspecialchars(strip_tags($_POST['petName']));
     $crud->service = htmlspecialchars(strip_tags($_POST['service']));
     $crud->providerName = htmlspecialchars(strip_tags($_POST['provider']));
     $crud->appointDate = htmlspecialchars(strip_tags($_POST['appointDate']));

     // Update the developer
     if ($crud->update()) {
          echo "<div>Appointment was updated.</div>";
     } else {
          echo "<div>Unable to update Appointment.</div>";
     }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Edit Appointment</title>
</head>

<body>
     <h1>Edit Appointment</h1>

     <!-- Form to edit an appointment -->
     <form action="update.php?id=<?php echo $id; ?>" method="post">
          <label for="petName">Pet Name:</label><br>
          <input type="text" name="petName" required value="<?php echo htmlspecialchars($crud->petName ?? ''); ?>"> <br><br>

          <label for="service">Service:</label>
          <select name="service" required>
               <option value="">Select Service</option>
               <option value="Hair Cut" <?= isset($crud->service) && $crud->service == 'Hair Cut' ? 'selected' : ''; ?>>Hair Cut</option>
               <option value="Nail Cutting" <?= isset($crud->service) && $crud->service == 'Nail Cutting' ? 'selected' : ''; ?>>Nail Cutting</option>
               <option value="Wash" <?= isset($crud->service) && $crud->service == 'Wash' ? 'selected' : ''; ?>>Wash</option>
          </select><br><br>

          <label for="provider">Provider:</label>
          <select name="provider" required>
               <option value="">Select Provider</option>
               <?php foreach ($stmt1 as $provider): ?>
                    <option value="<?= htmlspecialchars($provider['provider_name']); ?>"
                         <?= isset($crud->providerName) && $crud->providerName == $provider['provider_name'] ? 'selected' : ''; ?>>
                         <?= htmlspecialchars($provider['provider_name']); ?>
                    </option>
               <?php endforeach; ?>
          </select><br><br>

          <label for="appointDate">Date:</label>
          <input type="date" name="appointDate" value="<?php echo htmlspecialchars($crud->appointDate ?? ''); ?>"><br><br>

          <input type="submit" value="Update">
     </form>

     <br>
     <a href="index.php">Back to List</a>
</body>

</html>