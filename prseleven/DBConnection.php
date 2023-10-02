<?php
  $conn = mysqli_connect('localhost','root','','newproperty');
  
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error."<br>");
  }
?>