<?php
require "includes/header.php"; 
require "includes/classes/User.php";
require "includes/classes/Post.php";

if(isset($_POST['post_button']))
{
    $post=new Post($conn,$loggedInUser);
    $post->submit_post($_POST['post_text'],"none");
}

?>
<script>

    function loadMorePosts()
            {
                var page=$(".post_area").find(".nextPage").val();
                var noMorePosts=$(".post_area").find(".noMorePosts").val();
                $.ajax({
                    url:"includes/handlers/ajax_load_posts.php", 
                    type:"POST",
                    data:"page="+ page +"&loggedInUser="+loggedInUser,
                    cache:false,
                    success:function(response){
                        $(".post_area").find('.next_page').remove(); 
                        $(".post_area").find('.noMorePosts').remove(); 
                        $(".post_area").append(response);
                    }
            }); 
        }
</script>
    <div class="user_details">
            <a href="<?php echo $loggedInUser; ?>"><img src="<?php echo $user['profile_pic']?>"></a>   
        <div class="left_right">
            <a href="<?php echo $loggedInUser; ?>">
                <?php
                echo $user['first_name']." ".$user['last_name']."<br>"; 
                ?>
            </a>
            <?php
            echo "Posts: ".$user['num_posts']."<br>";
            echo "Likes: ".$user['num_likes'];
            ?>
        </div>
    </div>
    <div class="main_column">
        <form action="index.php" method="post" class="post_form">
            <textarea placeholder="Got something to say??" name="post_text" id="post_text"></textarea>
            <input type="submit" value="Post" name="post_button" id="post_button">
        </form>
       
        <div class="post_area"></div> <!--where all posts are loaded -->
        <button onclick="loadMorePosts()" id="morePost" style="margin-left:40%;margin-bottom:1%;border:none;border-radius: 10px;background-color: #90a9d8;
    font-family: 'Lobster', cursive;font-size: 150%;color: #fff;text-shadow: #000 2px 2px 1px;outline: none;">Load More Posts</button>
    </div>

    <script>
    var loggedInUser="<?php echo $loggedInUser; ?>";
    
    $(document).ready(function(){ 

       
        $.ajax({
            url:"includes/handlers/ajax_load_posts.php", 
            type:"POST",
            data:"page=1&loggedInUser="+loggedInUser,
            cache:false,
            success:function(data){
                $("#loading").hide();
                $(".post_area").html(data);
            }
        });


        /*$(window).scroll(function(){ //handle the window scrolling

        var height=$(".post_area").height(); //height of div containing posts
        var scroll_top=$(this).scrollTop(); //return vertical position of scrollbar... returns 0 when scrollbar on top
        var page=$(".post_area").find(".nextPage").val();
        var noMorePosts=$(".post_area").find(".noMorePosts").val();
        console.log("No More Posts"+noMorePosts);

        //height you scroll to is equal to top of the window plus height of window i.e we've reached bottom of page & need to reload more posts
        if((document.body.scrollHeight==document.body.scrollTop + window.innerHeight) && noMorePosts=='false')
        {
            // alert("reached end");
            $("#loading").show();

            //ajax call to load more posts
            $.ajax({
                url:"includes/handlers/ajax_load_posts.php", 
                type:"POST",
                data:"page="+ page +"&loggedInUser="+loggedInUser,//parameters we are sending
                cache:false,
                success:function(response){
                    $("#loading").hide();
                    $(".post_area").find('.next_page').remove(); //removes current .nextPage
                    $(".post_area").find('.noMorePosts').remove(); //removes current .noMorePosts
                    $(".post_area").append(response);
                    // console.log(response);
                }
        }); 

        }
        return false; //doubt

        });*/
    });

    </script>
    

</div> <!--closing tag for .wrapper in header.php -->
</body>
</html>
