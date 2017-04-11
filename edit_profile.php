<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Edit</title>
    <link rel="stylesheet" href="styles/edit_profile.css">
  </head>
  <body>
    <?php
      /*
       * Filename: edit_profile.php
       * Author: Mailat
       * Purpose:
       */
       session_start();
       include ('mysqli_connect.php');
       if(!isset($_SESSION['username'])) {
         header('location: login.php');
         exit;
       }
       $query = "SELECT UID FROM user WHERE username='" . $_SESSION['username'] . "'";
       if($result = mysqli_query($dbc, $query)) {
         $row = mysqli_fetch_array($result);
         $id = $row['UID'];
       }
       if($_SERVER['REQUEST_METHOD'] == 'POST') {
         if(!empty($_POST['fullname']) && !empty($_POST['email'])) {
           $fullname = mysqli_real_escape_string($dbc, $_POST['fullname']);
           $email = mysqli_real_escape_string($dbc, $_POST['email']);
           $country = mysqli_real_escape_string($dbc, $_POST['country']);
           if(move_uploaded_file($_FILES['photo']['tmp_name'], "photos/$id." . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION))) {
             $photo = "photos/$id." . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
           }
           else {
             $photo = NULL;
           }
           $query = "UPDATE user_info SET fullname='$fullname', email='$email',
           country='$country', photo='$photo' WHERE UID='$id'";
           if(mysqli_query($dbc, $query)) {
             header('location: profile.php');
             exit;
           }
           else {
             print "<p>Error: " . mysqli_error($dbc) . "</p>";
           }
         }
         else {
           print "<p>Error: Complete all fields! </p>";
         }
       }
       $query = "SELECT fullname, country, photo, email FROM user_info WHERE UID='$id'";
       if($result = mysqli_query($dbc, $query)) {
           $row = mysqli_fetch_array($result);
    ?>
    <div class="login">
      <h1>Edit profile</h1>
      <form action="edit_profile.php" enctype="multipart/form-data" method="post">
        <p><label>Full Name: <input type="text" name="fullname" value="<?php print $row['fullname'] ?>"></label></p>
        <p><label>Email: <input type="text" name="email" value="<?php print $row['email'] ?>"></label></p>
        <p><label>Country: <input type="text" name="country" value="<?php print $row['country'] ?>"></label></p>
        <input type="hidden" name="MAX_FILE_SIZE" value="30000000">
        <p><input type="file" name="photo" <?php if($row['photo'] != NULL) print 'value="' . $row['photo'] . '"' ?>></p>
        <p><input class="submit" type="submit" name="submit" value="Edit"></p>
      </form>
    </div>

    <?php
       }
       else {
         print "<p>Error: " . mysqli_error($dbc) . "</p>";
       }
    ?>
  </body>
</html>
