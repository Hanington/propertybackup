<?php 
require_once("DBConnection.php");
require_once 'gauthenticate.php';
include ("glinkadmin.php");
include("functions.php");
$ga = new GoogAuth();
if(!isset($_SESSION["sess_user"])){
    header("Location: index.php");
  }
  else{
?>

<?php


$app_name = "PRPS";

 	if (isset($_POST['verify'])) {
	 	if (!empty($_POST['code'])) {
            $code = (isset($_POST['code'])) ? strtolower(trim($_POST['code'])) : false;
            $verify = verify($email, $code);      
        }}
	 	else{
		 	echo "Required code field!";
		} 	
 	


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>PPPS</title>
    <style>
        #invalidMsg{
            display:none;
        }
    </style>
</head>
     

<body>

    <!-- header -->
    <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Property Price Prediction System</a>

        </div>
    </nav>
    <!-- header ends -->

   

    <!-- body -->
    <div class="container-fluid">
        <div class="row">
            <!-- container and row divs for responsive -->

            <!-- leftComponent -->
            <div class="leftComponent col-md-5">
                <img src="img/indeximg.jpg" alt="Leave Image" class="img-fluid">
            </div>
            <!-- leftComponent ends -->


            <!-- rightComponent -->
            <div class="rightComponent col-md-5">

                <!-- <h3>Please verify to continue. . .</h3>
                <hr> -->
                <?php

                if (file_exists('secretcodes/'.md5($email))) {
            echo "Enter the code for $app_name from Google Authenticator $type $email<br>";
            echo "<form method='post' class='loginForm>";
            echo "<input class='form-control' type='text' name='email' value='$email'/><br />";
            echo "<input class='form-control' type='text' name='code' placeholder='google code'/><br />";
            echo "<button class='btn btn-success' type='submit' name='verify'>SUBMIT</button>";
            echo "</form>";

        }
        ?>
            <!-- rightComponent ends -->
        </div>
    </div>
    <!-- body ends -->


    <footer class="footer navbar navbar-expand-lg navbar-light bg-light" style="color:white;">
    <div>
      <p class="text-center">&copy; <?php echo date("Y"); ?> - Property Price Prediction System</p>
    </div>
    </footer>
</body>
</html>

<?php
  }
ini_set('display_errors', true);
error_reporting(E_ALL);
?>