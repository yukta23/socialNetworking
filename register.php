<?php
require './config/config.php';
require './includes/form_handlers/register_handler.php'; 
require './includes/form_handlers/login_handler.php';   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration page</title>
    <link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Permanent+Marker&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
</head>
<body>
    <?php
    if(isset($_POST['register_button']))
    echo '
    <script>
    $(document).ready(function(){
    $(".second").show();
    $(".first").hide();
    });
    </script>
    '
    ?>
    <div class="form_container">
        <div class="login_box">
            <div class="login_header">
                <h1>Chat Box !</h1><br>
                <p>Sign Up or Login here !</p>
            </div>
            <div class="first">
                <form action="register.php" method="post">
                <input type="email" name="log_email" placeholder="Email Address" value="<?php
                if(isset($_SESSION['log_email']))
                echo $_SESSION['log_email'];
                ?>"/>
                <br>
                <input type="password" name="log_password" placeholder="Password" />
                <br>
                <?php
                if(in_array("Incorrect login",$error_arr))
                echo "Incorrect Email or password<br>";
                ?>
                <input type="submit" name="login_button" value="Login" />
                <br>
                <a href="#" id="signup">Need an account? Register here !</a>
                </form>
            </div>  
            <div class="second">
                <form action="register.php" method="post">
                <input type="text" placeholder="First Name" name="reg_fname" value="<?php 
                if(isset($_SESSION['reg_fname']))
                {
                    echo $_SESSION['reg_fname'];
                }
                ?>" required /><br>
                <?php if(in_array("First Name should be between 2 and 25 characters<br>",$error_arr))
                echo "First Name should be between 2 and 25 characters<br>";
                ?>
                <input type="text" placeholder="Last Name" name="reg_lname" value="<?php 
                if(isset($_SESSION['reg_lname']))
                {
                    echo $_SESSION['reg_lname'];
                }
                ?>" required /><br>
                <?php if(in_array("Last Name should be between 2 and 25 characters<br>",$error_arr))
                echo "Last Name should be between 2 and 25 characters<br>"; 
                ?>
                <input type="email" placeholder="Email" name="reg_email" value="<?php 
                if(isset($_SESSION['reg_email']))
                {
                    echo $_SESSION['reg_email'];
                }
                ?>" required/>
                <input type="email" placeholder="Confirm Email" name="reg_email2" value="<?php 
                if(isset($_SESSION['reg_email2']))
                {
                    echo $_SESSION['reg_email2'];
                }
                ?>" required /><br>
                <?php if(in_array("Email already in use !<br>",$error_arr))
                echo "Email already in use !<br>" ;
                elseif(in_array("Emails in invalid format !<br>",$error_arr))
                echo "Emails in invalid format !<br>";
                elseif(in_array("Emails don't match !<br>",$error_arr))
                echo "Emails don't match !<br>";
                ?>
                <input type="password" placeholder="Password" name="reg_password" required />
                <br>
                <input type="password" placeholder="Confirm Password" name="reg_password2" required />
                <br><?php if(in_array("Passwords don't match!<br>",$error_arr))
                echo "Passwords don't match!<br>" ;
                elseif(in_array("Password should be minimum 8 characters!<br>",$error_arr))
                echo "Password should be minimum 8 characters!<br>";
                ?>
                <input type="submit" value="Register" name="register_button" />
                <br>
                <?php
                if(in_array("registration successful",$error_arr))
                echo "<span style='color:green'>Congratulations!! Registration Successful</span><br>"; 
                ?>
                <a href="#" id="signin">Already have an account? Sign in here !</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>