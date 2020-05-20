<?php 
require "includes/header.php";

?>
<!DOCTYPE html>
<html>
<head>
	<title>FRIEND_REQUEST</title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	<div class="container">
		
			<?php 
			$result=mysqli_query($conn,"SELECT * FROM friends WHERE friend_name= '" . $loggedInUser . "' AND accepted = 0 ");
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_assoc($result)){
					$user=mysqli_query($conn,"SELECT * FROM users WHERE username='" . $row['username'] . "'");
					$row2=mysqli_fetch_assoc($user);
					$html='
					<div class="card col-md-4" style="float:left; padding:10px;">
					<img class="card-img-top" src="' . $row2['profile_pic'] . '" alt="Card image" style="width:100%">
					<div class="card-body" style="float: left;">
					<h4 class="card-title">' . $row2['username'] . '</h4>
					<p class="card-text">' . $row2['first_name'] . '  ' . $row2['last_name'] . '</p>
					<form method="post" action="#">
				    <input type="hidden" name="id" value="' . $row2['username'] . '" />
				    <input type="submit" class="btn btn-primary" name="accept" value="Accept"/>
				    </form>
					</div>
					</div>
					';
					echo $html;
					if(isset($_POST['accept'])){
						$id=$_POST['id'];
						if(mysqli_query($conn,"UPDATE friends SET accepted=1 WHERE username='" . $id . "' AND friend_name='" . $loggedInUser . "'")){
							if(mysqli_query($conn,"INSERT INTO friends(friend_name,username,accepted) VALUES('" . $id . "','" . $loggedInUser . "',1)")){
								unset($_POST['accept']);
								header("Location:friend_request.php");
							}							
						}
						else mysqli_error($conn);
					}

				}
			}			
			?>   
	</div>
</body>
</html>