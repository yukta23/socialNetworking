<?php
require './config/config.php';
if(isset($_SESSION['username'])) //to check the user logged in 
{
    $loggedInUser=$_SESSION['username'];
    $user_details=mysqli_query($conn,"SELECT * FROM users WHERE username='$loggedInUser'");
    $ans=mysqli_num_rows($user_details);
    $user=mysqli_fetch_array($user_details);

}
else //if the user isn't logged in then move to register.php
{
    header("Location: register.php");
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/header_style.css"> <!--keep this below in order to override bootstrap-->
    <link href="https://fonts.googleapis.com/css?family=Lobster|Permanent+Marker&display=swap" rel="stylesheet">
    <title>Chat Box</title>
    <script>
        <script>
            $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
</head>
<body>

    <div class="navbar">
        <div class="logo">
        <a href="index.php">Chat Box</a>
        </div>
        <nav>
            <a href="<?php echo $loggedInUser; ?>" style="text-decoration:none"><?php echo $user['first_name']?></a>
            <a href="index.php" data-toggle="tooltip" data-placement="bottom" title="Home"><i class="fa fa-home fa-lg"></i></a>
            <a href="#" data-toggle="tooltip" data-placement="bottom" title="Notifications"><i class="fa fa-bell-o fa-lg"></i></a>
            <a href="friend_request.php" data-toggle="tooltip" data-placement="bottom" title="Friend Requests"><i class="fa fa-users fa-lg"></i></a>
            <a href="#" data-toggle="tooltip" data-placement="bottom" title="Settings"><i class="fa fa-cog fa-lg"></i></a>
            <a href="includes/handlers/logout.php" data-toggle="tooltip" data-placement="bottom" title="Sign Out"><i class="fa fa-sign-out fa-lg"></i></a>
        </nav>
    </div>
    <div class="wrapper">
    