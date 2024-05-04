<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<!--  Name: Harman Singh Sandhu
      Student ID: 215629967
-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name = "viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Diet Dashboard</title>
    
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<header>
    <div class="logo" >
    <span onclick="openNav()"><img src="logo.jpg" alt="App Logo" width="100px" height="100px" border-radius: 100px></span>
        </div>
    <div class="date-time">
        <span id="currentDateTime"></span>
    </div>
</header>
<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="#foodlist" onclick="loadContent('index.php')">Food List</a>
  <a href="#today" onclick="loadContent('today.php')">Today's List</a>
  <a href="#chart" onclick="loadContent('chart.php')">Chart</a>
  <a href="reset-password.php" >Reset Your Password</a>
  <a href="logout.php" >Sign Out</a>
</div>



<!-- Add all page content inside this div if you want the side nav to push page content to the right (not used if you only want the sidenav to sit on top of the page -->
<div id="main">
<div class="container">
    <h3 contenteditable>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h3>
    <h4 contenteditable>Welcome to Food Diary!</h4>

    <!-- Content of the selected tab will be loaded here dynamically --> 
</div>
</div>




<footer>
    <p>&copy; All rights reserved, Harman Sandhu 2023</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="dashScript.js"></script>

</body>
</html>