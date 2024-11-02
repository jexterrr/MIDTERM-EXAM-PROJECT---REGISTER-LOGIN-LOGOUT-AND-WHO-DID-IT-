<?php
include_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $username = $_POST['username'];
     $password = $_POST['password'];
     $confirm_password = $_POST['confirm_password'];

     // Validate that password and confirm password match
     if ($password !== $confirm_password) {
          echo "<p>Passwords do not match. Please try again.</p>";
     } else {
          $database = new Database();
          $db = $database->getConnection();

          // Check if username already exists
          $query = "SELECT * FROM user WHERE username = :username";
          $stmt = $db->prepare($query);
          $stmt->bindParam(':username', $username);
          $stmt->execute();

          if ($stmt->rowCount() > 0) {
               echo "<p>Username already exists. Please choose a different username.</p>";
          } else {
               // Insert the new user with hashed password
               $hashed_password = password_hash($password, PASSWORD_BCRYPT);
               $query = "INSERT INTO user (username, password) VALUES (:username, :password)";
               $stmt = $db->prepare($query);
               $stmt->bindParam(':username', $username);
               $stmt->bindParam(':password', $hashed_password);

               if ($stmt->execute()) {
                    echo "<p>Account created successfully! <a href='login.php'>Login here</a>.</p>";
               } else {
                    echo "<p>Unable to create account. Please try again later.</p>";
               }
          }
     }
}
?>

<!DOCTYPE html>
<html>

<head>
     <title>Register</title>
</head>

<body>
     <h2>Create an Account</h2>
     <form method="post" action="register.php">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" required><br><br>

          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required><br><br>

          <label for="confirm_password">Confirm Password:</label>
          <input type="password" id="confirm_password" name="confirm_password" required><br><br>

          <input type="submit" value="Register">
     </form>
</body>

</html>