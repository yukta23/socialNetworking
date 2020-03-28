<?php

if(isset($_POST['login_button']))
{
    $email=filter_var($_POST['log_email'],FILTER_VALIDATE_EMAIL);
    $_SESSION['log_email']=$email;
    $password=md5($_POST['log_password']);

    $check_email_query=mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND password='$password'");
    $ans1=mysqli_num_rows($check_email_query);

    if($ans1==1) 
    { 
        $row=mysqli_fetch_array($check_email_query);
        
        if($row['user_closed']==1)
        {
            $reopen_account=mysqli_query($conn,"UPDATE users SET user_closed=0 WHERE email='$email' AND password='$password'");
        }
        $username=$row['username']; 
        $_SESSION['username']=$username; 
        header("Location: index.php"); 
        exit(); 
    }
    else
    array_push($error_arr,"Incorrect login");

}

?>
