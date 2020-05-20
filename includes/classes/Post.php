<?php

class Post{
    private $user_obj;
    private $conn;

    public function __construct($conn,$user)
    {
        $this->conn=$conn;
        $this->user_obj=new User($conn,$user);
    }

    public function submit_post($body,$user_to)
    {
        $body=strip_tags($body); 
        $body=mysqli_real_escape_string($this->conn,$body); 
        $check_empty=preg_replace('/\s+/','',$body); 
        
        if($check_empty!="")
        {
            $date_added=date("Y-m-d H:i:s");

            $added_by=$this->user_obj->getUsername();

            if($user_to==$added_by)
                $user_to="none";

            $query=mysqli_query($this->conn,"INSERT INTO posts VALUES ('','$body','$added_by','$user_to','$date_added',0,0,0)");
            $returned_id=mysqli_insert_id($this->conn);  

            $num_posts=$this->user_obj->getNumPosts();
            $num_posts++;
            $update_query=mysqli_query($this->conn,"UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
        } 
    }

    public function loadPostsByFriends($data,$limit)
    {
        $page=$data['page'];
        $loggedInUser=$this->user_obj->getUserName();

        if($page==1)
            $start=0;
        else
            $start=($page-1)*$limit;


        $str="";
        $data_query=mysqli_query($this->conn,"SELECT * FROM posts WHERE deleted=0 ORDER BY id  DESC");

        if(mysqli_num_rows($data_query) > 0) 
        {
            $num_iterations=0; 

            while($row=mysqli_fetch_array($data_query))  //keep on fetching matching rows
            {
                $id=$row['id'];
                $body=$row['body'];
                $added_by=$row['added_by'];
                $date_time=$row['date_added'];

                if($row['user_to']=="none")
                {
                    $user_to="";
                }
                else{
                    $user_to_obj=new User($this->conn,$row['user_to']);
                    $user_to_name=$user_to_obj->getFirstAndLastname();
                    $user_to=" to <a href='".$row['user_to']."'>".$user_to_name."</a>";
                }

                $added_by_obj=new User($this->conn,$added_by);
                if($added_by_obj->isClosed())
                continue;

                $user_logged_obj=new User($this->conn,$loggedInUser);
                if($user_logged_obj->isFriend($added_by))
                {

                    if($num_iterations < $start)
                    {
                        $num_iterations++;
                        continue; 
                    }

                    if($num_iterations > $start+$limit-1)
                        break;

                    $user_details_query=mysqli_query($this->conn,"SELECT * FROM users WHERE username='$added_by'");
                    $user_row=mysqli_fetch_array($user_details_query);
                    $first_name=$user_row['first_name'];
                    $last_name=$user_row['last_name'];
                    $profile_pic=$user_row['profile_pic'];

                    ?>
                    <script>
                        function toggle<?php echo $id?>() 
                        {
                            var target=$(event.target);
                            if(!target.is("a")) //when the anchor tag for profile is not the target
                            {
                                var element=document.getElementById("toggleComment<?php echo $id?>");
                                // console.log(element);
                                if(element.style.display=='block')
                                    element.style.display='none';
                                else
                                    element.style.display='block';
                            }
                            
                        }
                    </script>
                    <?php
                    
                    $comment_query=mysqli_query($this->conn,"SELECT * FROM comments WHERE post_id='$id'");
                    $num_comments=mysqli_num_rows($comment_query);

                    //Time Frame
                    $date_time_now=date("Y-m-d H:i:s");
                    $start_date=new DateTime($date_time);
                    $end_date=new DateTime($date_time_now);
                    $interval=$start_date->diff($end_date);

                    if($interval->y >=1)
                    {
                        if($interval==1)
                            $time_message=$interval->y." year ago"; 
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

                    $str .="<div class='status_post' onClick='javascript:toggle$id()' style='width:96%;font-size:15px;min-height:75px;margin-left:2%;cursor:pointer;'>
                                <div class='profile_pic' style='float:left;margin-right:1%;'>
                                    <img src='$profile_pic' width=50 height=50 style='border-radius:5px'>
                                </div>
                                <div class='posted_by' style='color:#ACACAC'>
                                    <a href='$added_by'> $first_name $last_name</a> $user_to &emsp; &emsp; $time_message
                                </div>
                                <div id='post_body'>
                                    $body
                                    <br><br>
                                </div>
                                <div class='comment_details' style='color:#0d7cdc;'>
                                Comments($num_comments)&emsp;&emsp;
                                <iframe src='like.php?post_id=$id' frameborder=0 scrolling='no' style='width:20%;height:20px;position:absolute;'></iframe>
                                </div>
                                <div class='post_comment' id='toggleComment$id' style='display:none;background-color:#e3e8eb'>
                                    <iframe src='comments_frame.php?post_id=$id' frameborder=0 style='width:100%;max-height:250px'></iframe>
                                </div>
                            </div><hr> ";
                    
                    $num_iterations++;
                }
            }

            if($num_iterations > $start+$limit-1)
            {
                $str.="<input type='hidden' class='nextPage' value='" . ($page+1) . "'>
                        <input type='hidden' class='noMorePosts' value='false'>";
            }
            else{
                $str.="<input type='hidden' class='noMorePosts' value='true'><p style='text-align:center;'>No More posts to show !!</p>
                <script>$('#morePost').hide()</script>";
            }

        }
        echo $str;
        
    }
}

?>
