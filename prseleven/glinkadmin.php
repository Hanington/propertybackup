<?php 
require_once("DBConnection.php");
require_once 'gauthenticate.php';
$ga = new GoogAuth();
session_start();
$username= $_SESSION['sess_user'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE name='".$username."'");
$numrows = mysqli_num_rows($query);
if($numrows !=0)
{
    while($row = mysqli_fetch_assoc($query))
    {
        $email=$row['email'];
        $password=$row['password'];
        $type=$row['type'];
    }}





function verify($email, $code){
    $query = mysqli_query($conn, "SELECT * FROM users WHERE name='".$username."'");
$numrows = mysqli_num_rows($query);
if($numrows !=0)
{
    while($row = mysqli_fetch_assoc($query))
    {
        $type=$row['type'];
    }}
    $ga = new GoogAuth();
    $secret_key = file_get_contents('secretcodes/'.md5($email));
    $code = (isset($_POST['code'])) ? strtolower(trim($_POST['code'])) : false;
    $checkResult = $ga->verifyCode($secret_key, $code, 2);    // 2 = 2*30sec clock tolerance

        if($checkResult)
        {
           // $_SESSION['sess_user']=$username;
           // $_SESSION['sess_eid']=$id;
           // $_SESSION['sess_type']=$type;
            //Redirect Browser
                header("Location:admin.php");

            return true;
        }			else{
            //echo "Invalid Username or Password";
            return false;
            
        }
}


function show_error($errmessage){
    echo $errmessage.'<br/>';
    echo '<a href="gfactor.php">Got Back Home</a>';
}
?>