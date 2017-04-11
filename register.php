<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles/login.css">
  </head>
  <body>
    <?php
      /*
       * Filename: register.php
       * Author: Mailat
       * Purpose: Register form
       */
       include ("mysqli_connect.php");
       if($_SERVER['REQUEST_METHOD'] == 'POST') {
         if(!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['fullname'])) {
           $query = "SELECT username FROM user WHERE username='" . $_POST['username'] . "'";
           if($result = mysqli_query($dbc, $query)){

             if(mysqli_num_rows($result) != 0) {
               print '<p>Username already exists!</p>';
             }
             else {
               $username = mysqli_real_escape_string($dbc, $_POST['username']);
               $email = mysqli_real_escape_string($dbc, $_POST['email']);
               $password = trim($_POST['password']);
               $password = strip_tags($password);
               $password = htmlspecialchars($password);
               if(isset($_POST['country'])){
                 $country = mysqli_real_escape_string($dbc, $_POST['country']);
               }
               else $country = mysqli_real_escape_string($dbc, "-");
               $fullname = mysqli_real_escape_string($dbc, $_POST['fullname']);
               $gender = mysqli_real_escape_string($dbc, $_POST["gender"]);

               $query = "INSERT INTO user(username, password) VALUES ('$username',
                '$password'); INSERT INTO user_info(email, fullname, gender, country)
                 VALUES ('$email', '$fullname', '$gender', '$country')";
               if(mysqli_multi_query($dbc, $query)) {
                 header('location: login.php');
               }
               else {
                 print "<p>Error: " . mysqli_error($dbc) . "</p>";
               }
             }
           }
           else {
             print '<p>Error: ' . mysqli_error($dbc) . '</p>';
           }
         }
         else {
           print '<p>Complete all fields!</p>';
         }
       }
    ?>
    <div class="login">
      <h2> Register </h2>
      <form action="register.php" method="post">
        <p><label>Username: <input type="text" name="username"></label></p>
        <p><label>Full Name: <input type="text" name="fullname"></label></p>
        <p><label>Email: <input type="text" name="email"></label></p>
        <p><label>Country: <input type="text" name="country"></label></p>
        <p><label>Gender: <select name="gender">
          <option value="male" selected="selected">male</option>
          <option value="female">female</option>
        </select></label></p>
        <p><label>Password: <input type="password" name="password"></label></p>
        <p><input type="submit" name="submit" value="Register"></p>
      </form>
    </div>

  </body>
</html>
