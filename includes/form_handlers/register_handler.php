<?php


$fname=""; 
$lname=""; 
$em=""; 
$em2=""; 
$password=""; 
$password2="";
$date=""; 
$error_arr=array();
$em_set=false; 
$fname_set=false;
$lname_set=false; 
$password_set=false; 

if(isset($_POST['register_button'])) { 

    $fname=strip_tags($_POST['reg_fname']); 
    $fname=str_replace(' ','',$fname); 
    $fname=ucfirst(strtolower($fname));

    //Last Name
    $lname=strip_tags($_POST['reg_lname']); 
    $lname=str_replace(' ','',$lname); 
    $lname=ucfirst(strtolower($lname)); 

    //Email
    $em=strip_tags($_POST['reg_email']);
    $em=str_replace(' ','',$em);
    $em=ucfirst(strtolower($em));
    $_SESSION['reg_email']=$em;

    //Email 2
    $em2=strip_tags($_POST['reg_email2']);
    $em2=str_replace(' ','',$em2);
    $em2=ucfirst(strtolower($em2));
    $_SESSION['reg_email2']=$em2;
    
    //Password
    $password=strip_tags($_POST['reg_password']);
    $password2=strip_tags($_POST['reg_password2']);

    $date=date("Y-m-d"); //current date

    if(strlen($fname)>25 ||  strlen($fname)<2 )
    {
        array_push($error_arr,"First Name should be between 2 and 25 characters<br>");
    }
    else 
    {
        $_SESSION['reg_fname']=$fname;
        $fname_set=true;
    }
    if(strlen($lname>25) || strlen($lname)<2)
    {
        array_push($error_arr,"Last Name should be between 2 and 25 characters<br>");
    } 
    else 
    {
        $_SESSION['reg_lname']=$lname;
        $lname_set=true;
    }

    if($em==$em2)
    {
        if(filter_var($em,FILTER_VALIDATE_EMAIL))
        {
            $em=filter_var($em,FILTER_VALIDATE_EMAIL); 
            
            $em_check=mysqli_query($conn,"SELECT email FROM users WHERE email='$em' ");
            $ans=mysqli_num_rows($em_check); //returns the number of rows that match
       
            if($ans>0)
            {
                array_push($error_arr,"Email already in use !<br>");
            }
            else
            $em_set=true;
        }
        else
        {
            array_push($error_arr,"Email in invalid format !<br>");
        }
    }
    else
    {
        array_push($error_arr,"Emails don't match !<br>");
    }
    
    if($password!=$password2)
    {
        array_push($error_arr,"Passwords don't match!<br>");
    }
    else
    {
        if(strlen($password)<8)
        array_push($error_arr,"Password should be minimum 8 characters!<br>");
        else
        $password_set=true;
    }
}
    // if(count($error_arr)>0) //to display above all error messages
    // {
    //     for($i=0;$i<count($error_arr);$i++)
    //     echo $error_arr[$i];
    // }
    // print_r($error_arr);

    if(empty($error_arr)  && $fname_set && $lname_set && $em_set && $password_set) 
    {
        $password=md5($password); 

        //Generate a username
        $username=strtolower($fname.'_'.$lname);
        $check_username_query=mysqli_query($conn,"SELECT username FROM users WHERE username='$username'");

        $i=0;
        while(mysqli_num_rows($check_username_query)!=0)
        {
            $i++;
            $username=$username.'_'.$i;
            $check_username_query=mysqli_query($conn,"SELECT username FROM users WHERE username='$username'");
        }

        $rand=rand(1,2);
        if($rand==1)
        {
            $profile_pic="./assets/images/profile_pics/defaults/head_alizarin.png";
        }
        else if($rand==2)
        {
            $profile_pic="./assets/images/profile_pics/defaults/head_amethyst.png";
        }

        $insert_query=mysqli_query($conn,"INSERT INTO users VALUES('','$fname','$lname','$username','$em','$password','$date','$profile_pic',0,0,0,',')");
        
        array_push($error_arr,"registration successful");

        session_unset(); 
    }



?>
