<?php
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Post.php");

$limit=7; //Number of posts to be loaded per call
$posts=new Post($conn,$_REQUEST['loggedInUser']); //$_REQUEST is the body of AJAX request
$posts->loadPostsByFriends($_REQUEST,$limit);

?>