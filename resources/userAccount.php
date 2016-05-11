<?php
if(!doesUserExist($mysqli,$userID)){
    echo "<div class='blog-header'>
        <h1 class='blog-title'>User not found</h1>
        <p class='lead blog-description'>You got a dud.</p>
        </div> <class='col-sm-8 blog-main'><h3><a href='/'>Go back.</a></h3>";
}else{
    $username = getUsername($mysqli,$userID);
    $tagline = getTagLine($mysqli,$userID);
    if($tagline == ""){
        $tagline = "The tag-line goes here.";
    }
    echo "<div class=\"blog-header\">
        <h1 class=\"blog-title\">$username</h1>
        <p class=\"lead blog-description\">$tagline</p>
        <p id='response'></p></div>";
    if(userIsFollowing($mysqli, $userID)){
        echo "<div class='col-sm-8 blog-main'><h3><a href=''id='unFollowBtn'>Un-follow</a></h3><hr />
        <h3>Timeline</h3></div>";
    }else{
        echo "<div class='col-sm-8 blog-main'><h3><a href='' id='followBtn'>Follow</a></h3><hr /><h3>Timeline</h3></div>";
    }
}
?>
