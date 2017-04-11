<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Friends</title>
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/friends.css">
  </head>
  <body>
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
      /*
       * Filename: friends.php
       * Author: Mailat
       * Purpose: Show list of users' friends
       */

       include ("mysqli_connect.php");
       session_start();
       if(!isset($_SESSION['username'])) {
         header('location: login.php');
         exit;
       }
       $query = "SELECT user_id, friend_id FROM relationship WHERE user_id='" . $_SESSION['id'] . "' OR
       friend_id='" . $_SESSION['id'] . "'";
       print '<h2>Friends</h2>';
       if($result = mysqli_query($dbc, $query)) {
         print '<ul class="friend-list">';
         while($row = mysqli_fetch_array($result))
         {
           if($row['user_id'] == $_SESSION['id']) {
             $query = "SELECT username FROM user WHERE UID='" . $row['friend_id'] . "'";
             if($r = mysqli_query($dbc, $query)) {
               $friend = mysqli_fetch_array($r);
               print '<li><a href="profile.php?username=' . $friend['username'] . '">' . $friend['username'] . '
               </a></li>';
             }
           }
           else if($row['friend_id'] == $_SESSION['id']) {
             $query = "SELECT username FROM user WHERE UID='" . $row['user_id'] . "'";
             if($r = mysqli_query($dbc, $query)) {
               $friend = mysqli_fetch_array($r);
               print '<li><a href="profile.php?username=' . $friend['username'] . '">' . $friend['username'] . '
               </a></li>';
             }
           }

         }
         print '</ul>';
       }
    ?>


  </body>
</html>
