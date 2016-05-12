<?php
/**
 * Created by PhpStorm.
 * User: williammeaton
 * Date: 28/04/2016
 * Time: 14:00
 */
function getUserID($mysqli, $username){
    $sqlUser = $mysqli->query("SELECT * FROM `users` WHERE username='$username'");
    if(mysqli_num_rows($sqlUser) > 0) {
        return $sqlUser->fetch_row()[0];
    }else{
        return -1;
    }
}

function isUserValidated($mysqli,$username)
{
    $sqlUser = $mysqli->query("SELECT * FROM `users` WHERE username='$username' AND validated='1'");
    if (mysqli_num_rows($sqlUser) > 0) {
        return true;
    } else {
        return false;
    }
}

function isUserIDValidated($mysqli, $userID){
    $sqlUser = $mysqli->query("SELECT * FROM `users` WHERE id='$userID' AND validated='1'");
    if (mysqli_num_rows($sqlUser) > 0) {
        return true;
    } else {
        return false;
    }
}

function getNumPostLikes($mysqli, $postID){
    $sqlPost = $mysqli->query("SELECT * FROM `likes` WHERE postid='$postID'");
    return mysqli_num_rows($sqlPost);
}

function postLiked($mysqli, $postID){
    $loginValue = $_COOKIE['twatterUser'];
    $userID = getUserID($mysqli, $loginValue);
    $sqlPost = $mysqli->query("SELECT * FROM `likes` WHERE userid='$userID' AND postid='$postID'");
    if(mysqli_num_rows($sqlPost) > 0){
        return true;
    }
    return false;
}

function getUsername($mysqli, $userID){
    $sqlUser = $mysqli->query("SELECT * FROM `users` WHERE id='$userID'");
    return $sqlUser->fetch_row()[2];
}

function getTagLine($mysqli,$userID){
    $sqlUser = $mysqli->query("SELECT * FROM `users` WHERE id='$userID'");
    return $sqlUser->fetch_row()[3];
}

function doesUserExist($mysqli,$userID){
    $sqlUser = $mysqli->query("SELECT * FROM `users` WHERE id='$userID'");
    if(mysqli_num_rows($sqlUser) > 0){
        return true;
    }
    return false;
}

function doesPostExist($mysqli, $postID){
    $sqlPost = $mysqli->query("SELECT * FROM `posts` WHERE id='$postID'");
    if(mysqli_num_rows($sqlPost) > 0){
        return true;
    }
    return false;
}

function checkSession($mysqli){
    $session = $_COOKIE['twatterSession'];
    $username = $_COOKIE['twatterUser'];
    if(isset($session)) {
        $sqlSession = $mysqli->query("SELECT * FROM `sessions` WHERE id='$session'");
        if (mysqli_num_rows($sqlSession) > 0) {
            if($sqlSession->fetch_row()[1] === getUserID($mysqli, $username)) {
                while ($row = $sqlSession->fetch_row()) {
                    if ($row[2] < time()) {
                        removeSession($mysqli, $session);
                        return false;
                    }
                }
                return true;
            }else{
                return false;
            }
        }
    }
    return false;
}

function removeSession($mysqli, $session){
    if($sqlSession = $mysqli->query("DELETE FROM `sessions` WHERE id='$session'")){
        if (isset($_COOKIE['twatterSession'])) {
            setcookie("twatterSession", null, 0, '/');
        }
        return true;
    }
    return false;
}

function userIsFollowing($mysqli, $followID){
    $loginValue = $_COOKIE['twatterUser'];
    $userID = getUserID($mysqli, $loginValue);
    $sqlUser = $mysqli->query("SELECT * FROM `follows` WHERE userid='$userID' AND followid='$followID'");
    if(mysqli_num_rows($sqlUser) > 0){
        return true;
    }
    return false;

}

function getFollows($mysqli, $userID){
    $sqlFollows = $mysqli->query("SELECT * FROM `follows` WHERE userid='$userID'");
    $follows = array();
    while ($row = $sqlFollows->fetch_row()) {
        array_push($follows, $row[2]);
    }
    return $follows;
}