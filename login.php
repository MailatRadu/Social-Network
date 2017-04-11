<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles/login.css">
  </head>
  <body>
    <?php
      /*
       * Filename: login.php
       * Author: Mailat
       * Purpose: Login page
       */
       include ("mysqli_connect.php");

       session_start();
       if(isset($_SESSION['username'])){
         header("location: profile.php");
         exit;
       }

       if($_SERVER['REQUEST_METHOD'] == 'POST') {
         $username = mysqli_real_escape_string($dbc, $_POST['username']);
         $password = trim($_POST['password']);
         $password = strip_tags($password);
         $password = htmlspecialchars($password);
         $query = "SELECT UID, password FROM user WHERE username='$username'";

         if($result = mysqli_query($dbc, $query)){
           $row = mysqli_fetch_array($result);
           if(mysqli_num_rows($result) == 1 && $password == $row['password']){
             $_SESSION['id'] = $row['UID'];
             $_SESSION['username'] = $username;
             header('location: profile.php');
           }
           else {
             print '<p>Wrong username and password!</p>';
           }
         }
         else {
           print "<p>Error: " . mysqli_error($dbc) . "</p>";
         }
       }
    ?>
    <h1>Social Media Website</h1>
    <div class="login">
      <h2> Login </h2>
      <form action="login.php" method="post">
        <p><label>Username: <input type="text" name="username"></label></p>
        <p><label>Password: <input type="password" name="password"></label></p>
        <p><input type="submit" name"submit" value="Login" class="submit"></p>
      </form>
      <a href="register.php">First time? Register now!</a>
    </div>
  </body>
</html>
