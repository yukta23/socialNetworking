<?php
require './config/config.php';
require "includes/classes/User.php";
require "includes/classes/Post.php";

if(isset($_SESSION['username']))
{
    $loggedInUser=$_SESSION['username'];
    $user_details=mysqli_query($conn,"SELECT * FROM users WHERE username='$loggedInUser'");
    $ans=mysqli_num_rows($user_details);
    $user=mysqli_fetch_array($user_details);

}
else 
{
    header("Location: register.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Like Page</title>
        <style>
        *{
            font-size:16px;
        }
        body,html{
            padding:0;
            margin:0;
            width:100%;
            height:100%;
        }
        .likes_section{
            color:#0d7cdc;
            display:inline-block;
        }
        form{
            width:100%;
        }
        input[type="submit"]{
            font-size:14px;
            border:none;
            color:#0d7cdc;
            font-weight:bold;
            background-color:#fff;
        }

        </style>
    </head>
    <body>
        <?php
            $post_id=0;
            if(isset($_GET['post_id']))
            {
                $post_id=$_GET['post_id'];
            }
            $str="";
            $get_likes=mysqli_query($conn,"SELECT likes,added_by FROM posts WHERE id='$post_id'");
            $row=mysqli_fetch_array($get_likes);
            $total_likes=$row['likes'];
            $user_liked=$row['added_by'];

            $user_details_query=mysqli_query($conn,"SELECT * FROM users WHERE username='$user_liked'"); //person who posted
            $row=mysqli_fetch_array($user_details_query);
            $total_user_likes=$row['num_likes']; //total likes received by user
            
            if(isset($_POST['like_button']))
            {
                $total_likes++;
                $query=mysqli_query($conn,"UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
                $total_user_likes++;
                $user_likes=mysqli_query($conn,"UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
                $insert_user=mysqli_query($conn,"INSERT INTO likes VALUES ('','$loggedInUser','$post_id')");

                //Insert Notification
            }

            if(isset($_POST['unlike_button']))
            {
                $total_likes--;
                $query=mysqli_query($conn,"UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
                $total_user_likes--;
                $user_likes=mysqli_query($conn,"UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
                $del_user=mysqli_query($conn,"DELETE FROM likes WHERE username='$loggedInUser' AND post_id='$post_id'");

                //Insert Notification
            }

            //check for previous likes by the logged in user
            $check_query=mysqli_query($conn,"SELECT * FROM likes WHERE username='$loggedInUser' AND post_id='$post_id'");
            $num_row=mysqli_num_rows($check_query);

            if($num_row>0)
            {
                echo '<form action="like.php?post_id='.$post_id.'" method="post">
                <input type="submit" name="unlike_button" value="Unlike">
                <div class="likes_section">'. $total_likes.' likes</div> </form>';
            }
            else
            {
                echo '<form action="like.php?post_id='.$post_id.'" method="post">
                <input type="submit" name="like_button" value="Like">
                <div class="likes_section">'.$total_likes .' likes </div></form>';
            }


        ?>
    </body>
</html>