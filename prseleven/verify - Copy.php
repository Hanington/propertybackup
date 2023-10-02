<?php 
require_once("DBConnection.php");
require_once 'gauthenticate.php';
include("functions.php");
$ga = new GoogAuth();
session_start();
if(!isset($_SESSION["sess_user"])){
    header("Location: index.php");
  }
  else{
?>

<?php
$email = (isset($_POST['email'])) ? strtolower(trim($_POST['email'])) : false;
$code = (isset($_POST['code'])) ? strtolower(trim($_POST['code'])) : false;
$action =  (isset($_GET['action'])) ? strtolower(trim($_GET['action'])) : '' ;

$app_name = "PRPS";

 	if (isset($_POST['verify'])) {
	 	if (!empty($_POST['username']) && !empty($_POST['code'])) {
	 		$username = mysqli_real_escape_string($conn,$_POST['username']);
	 		$vcode = mysqli_real_escape_string($_POST['code']);

            $verify = verify($username,$vcode);          
	 	}
	 	else{
		 	echo "Required All fields!";
		} 	
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

            <a id="register" href="registration.php">Sign Up</a>
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

                <h3>Please verify to continue. . .</h3>
                <hr>
                <form method="POST" class="loginForm">
                <div class="alert alert-danger" id="invalidMsg">
                    <?php      
                        if(isset($_POST['verify'])){
                            if($verify == false)
                                echo "<script type='text/javascript'>document.getElementById('invalidMsg').style.display = 'block';</script>";
                                echo "Invalid Username or Password";
                        }
                        else
                            echo "";
                    ?>
                    </div>
                    <div class="mb-3">
                        <?php  echo "<input class='form-control' type='text' name='email' value='$email' readonly /><br />"; ?>
                    </div>
                    <div class="mb-3">
                            <?php 
                                $checkResult = $ga->verifyCode($secret_key, $code, 2);
                            echo "<input class='form-control' type='text' name='code' /><br />"; ?>
                    </div>
                    <input type="submit" class="btn btn-success" name="verify" value="Verify">
                </form>
            </div>
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