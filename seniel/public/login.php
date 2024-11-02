<?php
session_start();
include_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $username = $_POST['username'];
     $password = $_POST['password'];

     $database = new Database();
     $db = $database->getConnection();

     // Fetch the user record by username
     $query = "SELECT * FROM user WHERE username = :username";
     $stmt = $db->prepare($query);
     $stmt->bindParam(':username', $username);
     $stmt->execute();

     // Check if a user with the provided username exists
     if ($stmt->rowCount() == 1) {
          $user = $stmt->fetch(PDO::FETCH_ASSOC);

          // Verify the provided password against the hashed password
          if (password_verify($password, $user['password'])) {
               $_SESSION['username'] = $user['username'];
               header("Location: index.php"); // Redirect to main page
               exit();
          } else {
               echo "<p>Invalid username or password.</p>";
          }
     } else {
          echo "<p>Invalid username or password.</p>";
     }
}
?>

<!DOCTYPE html>
<html>

<head>
     <title>Login</title>
</head>

<body>
     <h2>Login</h2>
     <form method="post" action="login.php">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" required><br><br>

          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required><br><br>

          <input type="submit" value="Login">
     </form>

     <p>Don't have an account? <a href="register.php">Create one here</a>.</p>

</body>

</html>