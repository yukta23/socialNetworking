<?php

class User{
    private $user;
    private $conn;

    public function __construct($conn,$user){ 
        $this->conn=$conn;
        $user_details_query=mysqli_query($conn,"SELECT * FROM users WHERE username='$user'");
        $this->user=mysqli_fetch_array($user_details_query);
    }

    public function isClosed()
    {
        if($this->user['user_closed']==0)
        return false;
        else
        return true;
    }

    public function getUsername()
    {
        return $this->user['username'];
    }

    public function getNumPosts()
    {
        return $this->user['num_posts'];
    }

    public function getFirstAndLastname(){   
        return $this->user['first_name']." ".$this->user['last_name'];
    }

    public function getProfilePic(){
        return $this->user['profile_pic'];
    }

    public function isFriend($usernameToCheck)
    {
        $usernameComma=",".$usernameToCheck.",";
        
        if(strstr($this->user['friend_array'],$usernameComma) || $usernameToCheck==$this->user['username'])
            return true;
        else    
            return false;
    }

}

?>