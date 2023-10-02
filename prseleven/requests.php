<?php
require_once("DBConnection.php");
//error_reporting(0);
session_start();
global $row;
if(!isset($_SESSION["sess_user"])){
  header("Location: index.php");
}
else{
?>

<?php 
  $reasonErr = $absenceErr = "";
  global $tabforthisApplicationValidate;
  if(isset($_POST['submit'])){
    if(empty($_POST['absence'])){
      $absenceErr = "Please select";
      $tabforthisApplicationValidate = false;
    }
    else{
      $arr = $_POST['absence'];
      $servicetype = implode(",",$arr);
      $tabforthisApplicationValidate = true;
    }

    if(empty($_POST['requestdate'])){
      $servicedateErr = "Please Enter service date";
      $tabforthisApplicationValidate = false;
    }
    else{
      $servicedate = mysqli_real_escape_string($conn,$_POST['requestdate']);
      $tabforthisApplicationValidate = true;
    }

    
    $reason = mysqli_real_escape_string($conn,$_POST['reason']);
    if(empty($reason)){
      $reasonErr = "Please give details for the request required";
      $tabforthisApplicationValidate = false;
    }
    else{
      $absencePlusReason = $absence." : ".$reason;
      $tabforthisApplicationValidate = true;
    }


    
    $status = "Pending";
    

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="js/formselection.js"></script>
	<link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
  <title>PPPS | Client</title>
  <style>
    h1 {
      text-align: center;
      font-size: 2.5em;
      font-weight: bold;
      padding-top: 1em;
      margin-bottom: -0.5em;
    }

    form {
      padding: 40px;
    }

    input,
    textarea {
      margin: 5px;
      font-size: 1.1em !important;
      outline: none;
    }

    label {
      margin-top: 2em;
      font-size: 1.1em !important;
    }

    label.form-check-label {
      margin-top: 0px;
    }


    select {
      width: max-content;
      padding: 5px;
      margin-top: 20px;
      margin-bottom: 20px;
      margin-left: 30px;
      margin-right: 5px;
    }

    #err {
      display: none;
      padding: 1.5em;
      padding-left: 4em;
      font-size: 1.2em;
      font-weight: bold;
      margin-top: 1em;
    }

    table{
      width: 90% !important;
      margin: 1.5rem auto !important;
      font-size: 1.1em !important;
    }

    .error{
      color: #FF0000;
    }
    form{display:none}
  </style>

  <script>
    const validate = () => {

      let desc = document.getElementById('tabforthisDesc').value;
      let checkbox = document.getElementsByClassName("form-check-input");
      let errDiv = document.getElementById('err');

      let checkedValue = [];
      for (let i = 0; i < checkbox.length; i++) {
        if(checkbox[i].checked === true)
          checkedValue.push(checkbox[i].id);
      }

      let errMsg = [];

      if (desc === "") {
        errMsg.push("Please enter the details and date of service");
      }

      if(checkedValue.length < 1){
        errMsg.push("Please select the type of Property");
      }

      if (errMsg.length > 0) {
        errDiv.style.display = "block";
        let msgs = "";

        for (let i = 0; i < errMsg.length; i++) {
          msgs += errMsg[i] + "<br/>";
        }

        errDiv.innerHTML = msgs;
        scrollTo(0, 0);
        return;
      }
    }
</script>

<body>
  <!--Navbar-->
  <nav class="navbar header-nav navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Property Price Prediction System |   <?php     $username= $_SESSION['sess_user'];
  echo "$username"; ?> </a>
      <ul class="nav justify-content-end">
           
            <li class="nav-item">
                <a class="nav-link" href="#" style="color:white;">My Request History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" style="color:white;">Current Requests</a>
            </li>
            <li class="nav-item">
            <button id="logout" onclick="window.location.href='logout.php';">Logout</button>
            </li>
            </ul>

      
    </div>
  </nav>




  <div class="container">
    <div class="alert alert-danger" id="err" role="alert">
  </div>
      <!--Select the form-->
      <label>Select Type</label>
      <select id="selectForm">
        <option value="" disabled selected>Land/Property</option>
        <option value="form1land">Land Prediction</option>
        <option value="form2building">Property Prediction</option>
        </select>
      <hr/>

      <!--First Form for Land-->
    <form method="POST" name="firstform" id="form1land">
    <h1>LAND PRICE PREDICTION</h1>
  
      <!-- error message if type of absence isn't selected -->
      <span class="error"><?php echo "&nbsp;".$absenceErr ?></span><br/>
      <div class="col-5">
        <select class="projects">
            <option disabled selected>Select Area</option>
            <option id="Westlands"   name="absence[]"  value="Westlands">Westlands</option>
            <option id="Embakasi"     name="absence[]"  value="Embakasi">Embakasi</option>
            <option id="Lang'ata"  name="absence[]"  value="Lang'ata">Lang'ata</option>
            <option id="Roysambu"  name="absence[]"  value="Roysambu">Roysambu</option>
            <option id="Other"        name="absence[]"  value="Other">Other</option>
        </select>
      </div><br>
      
      <div>
        <label for="landsize"><b>Land Size:</label>
        <input type="number" id="landsize" name="landsize" min="1" max="1000" placeholder="(m sq.)">
      </div>
      
      <input type="submit" name="submit" value="Submit" class="btn btn-success">
     </form>

      <!--Second Form for Buildings-->
     <form name="secondform" id="form2building" action="">
          <h1>BUILDING PRICE PREDICTION</h1>
          <label>form 2 read me here</label>
      <div class="col-5">
        <select class="projects">
            <option disabled selected>Select Area</option>
            <option id="Westlands"   name="absence[]"  value="Westlands">Westlands</option>
            <option id="Embakasi"     name="absence[]"  value="Embakasi">Embakasi</option>
            <option id="Lang'ata"  name="absence[]"  value="Lang'ata">Lang'ata</option>
            <option id="Roysambu"  name="absence[]"  value="Roysambu">Roysambu</option>
            <option id="Other"        name="absence[]"  value="Other">Other</option>
        </select>
      </div><br><br>
      
      <div class="col-5">
        <select class="projects">
            <option disabled selected>Type of Property</option>
            <option id="Apartment"   name="absence[]"  value="Apartment">Apartment</option>
            <option id="Villa"     name="absence[]"  value="Villa">Villa</option>
            <option id="Townhouse"  name="absence[]"  value="Townhouse">Townhouse</option>
            <option id="Other"        name="absence[]"  value="Other">Other</option>
        </select>
      </div><br><br>
      <div>
      <label for="prooms"><b>No. of rooms:</label>
        <input type="number" id="prooms" name="prooms" min="1" max="1000" placeholder="(m sq.)">
      </div>
  
     <input type="submit" name="submit" value="Submit" class="btn btn-success">
   </form>

  </div>

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