<?php
require("connect.php");
require("databaseFunctions.php");
$loginValue = $_COOKIE['twatterUser'];

//check validation first thing
if(!$loginValue || !checkSession($mysqli)){
    $responseText = "Your login has expired.";
    $responseType = 0;
    die(json_encode(array('text' => $responseText, 'type' => $responseType, 'postNum' => $numPosts)));
}

//if you're getting for users
$userToGet = htmlspecialchars(mysqli_real_escape_string($mysqli, $_POST['user']));
//if you're on an account page or not
$accountPage = htmlspecialchars(mysqli_real_escape_string($mysqli, $_POST['a']));
//the page number
$pageNumber = htmlspecialchars(mysqli_real_escape_string($mysqli, $_POST['page']));
//the number of posts per page
$postsPerPage = htmlspecialchars(mysqli_real_escape_string($mysqli, $_POST['posts']));

//if it should load all posts or just followers
$allPosts = $_POST['all'];

//this is what is returned to the page
$responseText = "";
$responseType = 0;

$numPosts = 0;
$limitBottom = $pageNumber * $postsPerPage;
$limitTop = $pageNumber+1 * $postsPerPage;

$userID = getUserID($mysqli, $loginValue);
//build the SQL Query
if(!$accountPage || $accountPage === 0)
{
    //If you're on the main page
    //and if you want followers only
    if($allPosts === "false"){
        $follows = getFollows($mysqli, $userID);
        //create the user get statement
        $followSQLString = "`user` = '$userID'";
        foreach ($follows as $followID) {
            //if it's the last, there is no OR
            $followSQLString = $followSQLString." OR `user`='$followID'";
        }
//        create the sql including the followerss
        $sqlPosts = $mysqli->query("SELECT * FROM `posts` WHERE $followSQLString ORDER BY `date` DESC LIMIT $limitBottom, $limitTop");
    }else{
        //just get all posts
        $sqlPosts = $mysqli->query("SELECT * FROM `posts` ORDER BY `date` DESC LIMIT $limitBottom, $limitTop");
    }
} else if($accountPage && !$userToGet){
    //if you're on your own account page
    $sqlPosts = $mysqli->query("SELECT * FROM `posts` WHERE `user`='$userID' ORDER BY `date` DESC LIMIT $limitBottom, $limitTop");
} else if($accountPage){
    //if you're on an account page
    //If you're on the main page
    $sqlPosts = $mysqli->query("SELECT * FROM `posts` WHERE `user`='$userToGet' ORDER BY `date` DESC LIMIT $limitBottom, $limitTop");
}

if(mysqli_num_rows($sqlPosts)>0){
    $numPosts = mysqli_num_rows($sqlPosts);
    $usernames = array(); // This will store all the users that have posted
    while ($row = $sqlPosts->fetch_assoc()) {
        $userID = $row['user'];
        //Don't request the username for posts that you've already got
        //it's a waste of time
        if(!array_key_exists($userID,$usernames)) {
            $sqlGetPoster = $mysqli->query("SELECT * FROM `users` WHERE id='" . $userID . "'");
            $poster = $sqlGetPoster->fetch_assoc();
            $usernames[$userID] = $poster['username'];
        }
        //only show the days if it's not today
        if (date("Y-m-d", $row['date']) == date("Y-m-d")) {
            $date = date("H:i", $row['date']);
        } else {
            $date = date("H:i d/m/y", $row['date']);
        }
        //create the html to return, this will be all the posts
        $content = $row['content'];
        $responseText = $responseText."<div class='post'><small class='blog-post-meta'><a id='link' href='?p=account&u=" . $userID . "'>" . $usernames[$userID] . "</a> at " . $date . "</small>
        <div class='blog-post'><p>$content</p><small class='blog-post-action'></small></div></div>";
    }
    $responseType = 1;
}else{
    //if there are no posts
    if($allPosts === "true"){
        $responseText = "There are no posts what so ever!";
    }else {
        $responseText = "Your feed is empty, try posting or looking at all posts.";
    }
    $responseType = 0;
}
echo json_encode(array('text' => $responseText, 'type' => $responseType, 'postNum' => $numPosts));