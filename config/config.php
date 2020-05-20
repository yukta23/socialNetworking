<?php
ob_start();//turns on output buffering,PHP is loaded into browser in sections,this saves entire PHP data when page is loaded & is available to all
session_start(); //session lasts as long as browser window is open, automatically lost after 24 minutes

$timezone=date_default_timezone_set("Asia/Kolkata");

$conn = mysqli_connect("localhost","root","","test"); 

if(mysqli_connect_errno())
{
    echo "Failed to connect: ".mysqli_connect_error();
}
?>