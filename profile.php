<?php
require "includes/header.php"; 
if(isset($_POST['add_friend'])){
	$friend=mysqli_real_escape_string($conn,$_POST['username']);
	$friend_err="";
	$result=mysqli_query($conn,"SELECT * FROM users WHERE username = '" . $friend. "' 	");
	if(mysqli_num_rows($result)>0){
		if($row=mysqli_fetch_assoc($result)){
			if(mysqli_query($conn,"INSERT INTO friends(friend_name,username) VALUES('" . $row['username'] . "','" . $loggedInUser . "')")){
				$friend_err="Friend Request sent";
		}
	}
	else{
		$friend_err="No such username";
		//header("Location:profile.php");
	}

}
	unset($_POST['add_friend']);
}
	
		

if(isset($_POST["upload"])){
	$target_dir="assets/images/profile_pics/defaults/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$check=getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	if($check !== false) {
		$script='<script>alert("File is an image - ' . $check["mime"] . '");</script>';
        echo $script;
        $uploadOk = 1;
    } else {
    	$script='<script>alert("File is not an image");</script>';
        echo $script;
        $uploadOk = 0;
    }
    
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		$script='<script>alert("Sorry, your file is too large.");</script>';
        echo $script;
    	$uploadOk = 0;
	}
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" ) {
		$script='<script>alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");</script>';
        echo $script;
    	$uploadOk = 0;
	}
	if ($uploadOk == 0) {
		$script='<script>alert("Sorry, your file was not uploaded.");</script>';
        echo $script;
	} 
	else {
    	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    		$pic="./assets/images/profile_pics/defaults/". basename( $_FILES["fileToUpload"]["name"]);
    		if(mysqli_query($conn,"UPDATE users SET profile_pic='" . $pic . "' WHERE username='" . $loggedInUser . "'")){
				$profile_err="Success";
				 header("Location: profile.php");
				$uploadOk=0;
			}
				
			else{
				$profile_err="No such username";
					
			}			

    	} 
    	else {
    		$script='<script>alert("Sorry, there was an error uploading your file.");</script>';
        	echo $script;
    	}
	}
	unset($_POST["upload"]);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>PROFILE PAGE</title>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
	
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-4" >
				<div class="profile-img">
					<img style="width: 70%;height: 300%;" src=" <?php echo $user['profile_pic']?>" >
				</div>
			</div>
			<div class="col-md-8">
				<div class="profile-head">
					<h5><?php echo $user['first_name'] ." ".$user['last_name']?></h5>
					<ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Friends</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Home</a>
                        </li>
                    </ul>
				</div>
			</div>
		</div>
		<br>
		<br>
		<br>
		<br>
		<div class="row">
			<div class="col-md-4">
				<div class="upload">
					<form action="#" method="post" enctype="multipart/form-data">
						<br><br><br>
	    				Select image to upload:<br>
	    				<input type="file" name="fileToUpload" id="fileToUpload" ><br><br>
	    				<input type="submit" value="Upload" name="upload"  class="btn btn-success">
					</form>
				</div>
				<div class="friend_search">
					<form action="#"  method="post">
						<input type="text" name="username" id="username" placeholder="Add username"><br><br>
                        <input type="submit" class="btn btn-success" name="add_friend" value="Add "   />
                        <input type="submit" class="btn btn-primary" name="search_friend" id="search_friend" value="Search" data-toggle="modal" data-target="#myModal" >
                        <input type="hidden" id="btnClickedValue" name="btnClickedValue" value="" />
						<br>
                        <?php 
			                        	if(isset($_POST['search_friend'])){
			                        		echo "<script>
									        $(function() {
									         $('#myModal').modal('show');
									        });
									        </script>";
			                            echo '<div class="modal fade" id="myModal">';
			                        	echo '<div class="modal-dialog modal-dialog-centered modal-lg">';
			                        	echo '<div class="modal-content">';
			                        	echo '<div class="modal-header">
                        						<h4 class="modal-title">PROFILE</h4>
                        						<button type="button" class="close" data-dismiss="modal">&times;</button>
                        					</div>';
                        				echo '<div class="modal-body">';
										$friend=mysqli_real_escape_string($conn,$_POST['username']);;
										$friend_err="";
										$result=mysqli_query($conn,"SELECT * FROM users WHERE username = '" . $friend. "' 	");
										if(mysqli_num_rows($result)>0){
											if($row=mysqli_fetch_assoc($result)){
										    
												echo "<b>Username :  </b>";echo $row['username'];echo "<br/>";
												echo "<b>Name :  </b>";echo $row['first_name'];
												echo " ";
												echo $row['last_name'];echo "<br/>";
												echo "<b>Email :  </b>";echo $row['email'];echo "<br/>";
												echo "<b>Signup Date :  </b>";echo $row['signup_date'];echo "<br/>";
												echo "<b>Num Posts :  </b>";echo $user['num_posts'];echo "<br/>";
												$friend_list=mysqli_query($conn,"SELECT * FROM friends WHERE username= '" . $row['username'] . "' AND accepted=1");
                                                echo "<b>Friends :  </b>";	echo mysqli_num_rows($friend_list); 
												unset($_POST['search_friend']);
												unset($_POST['username']);
											}
										}
										else{
											//$friend_err="No such username";
										}
										echo '</div>';
										echo '<div class="modal-footer">
        									<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      									</div></div></div></div>';
									}
			                        ?>                        
                        <span class="text-danger"><?php if(isset($friend_err)) {echo $friend_err; unset($friend_err);} ?> </span>
                    </form>
                                 	
				</div>
			</div>
			<div class="col-md-8">
				<div class="tab-content profile-tab" id="myTabContent">
							<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">                                
			                    	<div style="float: left;">
			                    		<?php 
			                    			$friend_list=mysqli_query($conn,"SELECT * FROM friends WHERE username= '" . $loggedInUser . "' AND accepted = 1 ");
			                    			$removed_friend="";
			                    			while($row = mysqli_fetch_assoc($friend_list)){
			                    					echo "<b>";
				                    				echo $row['friend_name'];
				                    				$removed_friend=$row['friend_name'];				                    		
				                    				echo "</b>";
				                    				$html='<div >
				                    				<form method="post" action="#" style="float:right;">
				                    				<input type="hidden" name="id" value="' . $row['friend_name'] . '" />
				                    				<input type="submit" class="btn btn-danger" name="remove_friend" value="Remove"/>
				                    				</form>
				                    				</div>';
				                    				echo $html;
				                    				if(isset($_POST['remove_friend'])){
				                    					$id=$_POST['id'];
				                    					if(mysqli_query($conn,"DELETE FROM friends WHERE friend_name= '" . $id . "' AND accepted=1 AND username= '" . $loggedInUser . "'")){
				                    						if(mysqli_query($conn,"DELETE FROM friends WHERE username='" . $id . "' AND accepted=1 AND friend_name= '" . $loggedInUser . "'"))
				                    						unset($_POST['remove_friend']);
				                    						header("Location:profile.php");
				                    					}
				                    					else mysqli_error($conn);
				                    				}
				                    		}
				                    	?>
			                    	</div>
			                    
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><b>User Id</b></label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $user['username']?></p>
                                            </div>
                                        </div>
                                     
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><b>Email</b></label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $user['email']?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><b>Signup Date</b></label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $user['signup_date']?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><b>Posts</b></label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $user['num_posts']?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><b>Friends</b></label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php 
                                                	$friend_list=mysqli_query($conn,"SELECT * FROM friends WHERE username= '" . $loggedInUser . "' AND accepted=1");
                                                	echo mysqli_num_rows($friend_list); ?></p>
                                            </div>
                                        </div>
                            </div>
                            
			</div>
			</div>
				
		</div>
	</div>
</body>
</html>
