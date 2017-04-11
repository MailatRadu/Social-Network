<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/profile.css">
  </head>
  <body>
    <?php
      /*
       * Filename: welcome.php
       * Author:  Mailat
       * Purpose:
       */

       include ("mysqli_connect.php");
       session_start();
       if(!isset($_SESSION['username'])) {
         header('location: login.php');
         exit;
       }
       else {
         if(empty($_GET['username'])) {
           $username = $_SESSION['username'];
         }
         else {
           $username = $_GET['username'];
         }
         //Find the user in the database
         $query = "SELECT UID FROM user WHERE username='{$username}'";
         $result =  mysqli_query($dbc, $query);
         $row = mysqli_fetch_array($result);
         //Check if the user exists
         if(mysqli_num_rows($result) == 0) {
           print '<p>Error: No such user in the database</p>';
           print '<p><a href="profile.php">Home</a></p>';
           exit;
         }
         else {
           $id = $row['UID'];
           //Get the details of user
           if($result = mysqli_query($dbc, "SELECT email, fullname, country, gender, photo FROM user_info WHERE UID = '{$id}'")){
             $row = mysqli_fetch_array($result);
           }
           else {
             print "<p>Error: " . mysqli_error($dbc) . "</p>";
           }
         }
         if($_SERVER['REQUEST_METHOD'] == 'POST') {
           $query = "INSERT INTO relationship(user_id, friend_id) VALUES ('" . $_SESSION['id'] . "' , '$id')";
           if($result = mysqli_query($dbc, $query)) {
             $message = '<p>You are now friends!</p>';
           }
         }
    ?>
    <nav class="clearfix">
      <ul>
        <li><a href="logout.php">Logout</a></li>
        <li><a href="friends.php">Friends</a></li>
        <li><a href="profile.php">My Profile</a></li>
      </ul>
      <form action="profile.php" method="get">
        <input type="text" name="username" size="20">
      </form>
    </nav>
    <?php
      if(isset($message)) {
        print $message;
      }
    ?>
    <div class="photo" style="background-image: url('<?php if($row['photo'] == NULL) print 'photos/noimage.jpg';
                    else print $row['photo']; ?>')">
    </div>
    <div class="info clearfix">
      <p>Email: <?php print "{$row['email']}" ?> </p>
      <p>Full Name: <?php print "{$row['fullname']}" ?> </p>
      <p>Country: <?php print "{$row['country']}" ?> </p>
      <p>Gender: <?php print "{$row['gender']}" ?> </p>
      <?php if($_SESSION['username'] == $username) { ?>
      <p><a href="edit_profile.php">Edit Profile</a></p>
      <?php } ?>
      <?php
        if($_SESSION['username']!=$username) {
          $query = "SELECT ID FROM relationship WHERE (user_id='" . $_SESSION['id'] . "' AND
          friend_id='" . $id . "') OR (user_id='" . $id . "' AND
          friend_id='" . $_SESSION['id'] . "')";

          if($result = mysqli_query($dbc, $query)) {
            if(mysqli_num_rows($result) == 0) {
              print '<form action="profile.php?username=' . $username . '" method="post"><input type="submit" name="submit" value="Add Friend">
              </form>';
            }
          }
          else {
            print '<p>Error: ' . mysqli_error($dbc) . '</p>';
          }
        }


      ?>
    </div>
    <?php } ?>
  </body>
</html>
