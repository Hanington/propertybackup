<?php
require_once 'gauthenticate.php';
require_once("DBConnection.php"); 
$ga = new GoogAuth();

$email = (isset($_POST['email'])) ? strtolower(trim($_POST['email'])) : false;
$code = (isset($_POST['code'])) ? strtolower(trim($_POST['code'])) : false;
$action =  (isset($_GET['action'])) ? strtolower(trim($_GET['action'])) : '' ;

$app_name = "PRPS";
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
// part3 -- test the code againsts Google Authenticator
if ($action == 'part3') {
    if (!file_exists('secretcodes/'.md5($email))) { show_error("unknown account"); }
    if (!$code) { show_error("code cannot be empty"); }
    
    $secret_key = file_get_contents('secretcodes/'.md5($email));
    $checkResult = $ga->verifyCode($secret_key, $code, 2);    // 2 = 2*30sec clock tolerance
    
    if ($checkResult) {
        $query = "UPDATE users SET secret_key='$secret_key' WHERE email='".$email."'";
        $execute = mysqli_query($conn,$query);
        echo "<script type='text/javascript'>alert('Registration Successful');
        window.location='index.php';
        </script>";

    } else {
        show_error("Wrong code. Please try again.");    
    }

// if registered, request for code, if not, register user
} elseif ($action == 'part2') {
    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if (file_exists('secretcodes/'.md5($email))) { // registered in the past
            echo "Enter the code for $app_name from Google Authenticator<br>";
            echo "<form action='gfactor.php?action=part3' method='post'>";
            echo "<input class='form-control' type='text' name='email' value='$email' readonly /><br />";
            echo "<input class='form-control' type='text' name='code' /><br />";
            echo "<button class='btn btn-success' type='submit'>SUBMIT</button>";
            echo "</form>";

        } else { // new registration
            $secret_key = $ga->createSecret();
            $account = $email.'-'.$app_name;
            file_put_contents('secretcodes/'.md5($email), $secret_key);
            echo "This is your first time using $app_name.<br/>";
            echo "Scan the QR code below with Google Authenticator app.<br/>";
            $qrCodeUrl = $ga->getQRCodeGoogleUrl($account, $secret_key);
            echo "<img src='$qrCodeUrl' /><br />";
            echo "or enter this code manually into Google Authenticator<br/>";
            echo "Your Account : $account<br/>";
            echo "Your Key : $secret_key<br/>";
            echo "When you are ready, click the button below.<br />";
            echo "<form action='gfactor.php?action=part2' method='post'>";
            echo "<input class='form-control' type='hidden' name='email' value='$email' />";
            echo "<button class='btn btn-success' type='submit'>CONTINUE</button>";
            echo "</form>";
        }
    } else {
        show_error("invalid email format");
    }
} else {
    echo "Enter email address to proceed with login.";
    echo "<form action='gfactor.php?action=part2' method='post'>";
    echo "<input class='form-control' type='text' name='email' value='' />";
    echo "<button class='btn btn-success' type='submit'>LOGIN</button>";
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
function show_error($errmessage){
    echo $errmessage.'<br/>';
    echo '<a href="gfactor.php">Got Back Home</a>';
}
