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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/comment_style.css">
</head>
<body>

<script>

    function toggle()
    {
        var element=document.getElementById("comment_section");

        if(element.style.display=='block')
            element.style.display='none';
        else
            element.style.display='block';
    }
</script>

<?php
//get ID of post

    $post_id=0;
    if(isset($_GET['post_id']))
    {
        $post_id=$_GET['post_id'];

        $user_query=mysqli_query($conn,"SELECT * FROM posts WHERE id='$post_id'");
        $row=mysqli_fetch_array($user_query);
        $posted_to=$row['added_by'];
    }
        
    if(isset($_POST['postComment'.$post_id]))
    {
        $post_body=$_POST['post_body'];
        $post_body=strip_tags($post_body);
        $post_body=mysqli_escape_string($conn,$post_body);
        $date_time_now=date("Y-m-d H:i:s");

        $check_empty=preg_replace('/\s+/','',$post_body);
        if($check_empty!="")
        {
            $insert_comment=mysqli_query($conn,"INSERT INTO comments VALUES ('','$post_body','$loggedInUser','$posted_to','$date_time_now',0,'$post_id')");
            echo "<p>Comment Posted !!</p>";
        }
        else
        {
            echo "<p>Can't post blank comment !</p>";
        }
        
    }

?>
    
    <form action="comments_frame.php?post_id=<?php echo $post_id;?>" method='POST' id="comment_form" >
        <textarea name="post_body"> </textarea>
        <input type="submit" name="postComment<?php echo $post_id?>" value="Post">
    </form>

    <?php
        $comment_query=mysqli_query($conn,"SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id DESC");
        $count=mysqli_num_rows($comment_query);

        if($count!=0)
        {
            while($comment=mysqli_fetch_array($comment_query))
            {
                $comment_body=$comment['post_body'];
                $posted_to=$comment['posted_by'];
                $posted_by=$comment['posted_by'];
                $date_added=$comment['date_added'];
                $removed=$comment['removed'];
                $user_posted_obj=new User($conn,$posted_by);

                if($removed==1)
                continue;

                //Time Frame
                $date_time_now=date("Y-m-d H:i:s");
                $start_date=new DateTime($date_added);//Time of post
                $end_date=new DateTime($date_time_now);//Current Time
                $interval=$start_date->diff($end_date);//Difference between dates

                if($interval->y >=1)
                {
                    if($interval==1)
                        $time_message=$interval->y." year ago"; //1 year ago
                    else
                        $time_message=$interval->y." years ago";
                }
                else if($interval->m >=1)
                {
                    if($interval->d ==0)
                        $days=" ago";
                    else if($interval->d ==1)
                        $days=$interval->d." day ago";
                    else
                        $days=$interval->d." days ago";
                    
                    if($interval->m==1)
                        $time_message=$interval->m." month ".$days;
                    else
                        $time_message=$interval->m." months ".$days;
                }
                else if($interval->d >=1)
                {
                    if($interval->d==1)
                        $time_message="Yesterday";
                    else
                        $time_message=$interval->d." days ago";
                }
                else if($interval->h >=1)
                {
                    if($interval->h ==1)
                        $time_message=$interval->h." hour ago";
                    else
                        $time_message=$interval->h." hours ago";
                }
                else if($interval->i >=1)
                {
                    if($interval->i ==1)
                        $time_message=$interval->i." minute ago";
                    else
                        $time_message=$interval->i." minutes ago";
                }
                else 
                {
                    if($interval->s <30)
                        $time_message="Just Now";
                    else
                        $time_message=$interval->s." seconds ago";

                }

                ?>
                <div class="comment_section">
                    <a href="<?php echo $posted_by?>" target="_parent"><img src="<?php echo $user_posted_obj->getProfilePic()?>"></a>
                    <a href="<?php echo $posted_by?>" target="_parent"><b><?php echo $user_posted_obj->getFirstAndLastname();?></b></a>
                    &emsp;&emsp; <?php echo $time_message."<br>".$comment_body ?>
                </div>
                <hr>
                <?php
            }
            
        }
        else{
            echo "<center style='font-size:150%;'><br>No Commments to show !!</center>";
        }

    ?>
    

</body>
</html>