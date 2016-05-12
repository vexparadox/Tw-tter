<?php
require("connect.php");
require ("databaseFunctions.php");
//get the data required
$session = $_COOKIE['twatterSession'];
$loginValue = $_COOKIE['twatterUser'];
$post = $_POST['p'];
$action = $_POST['a'];
$responseText = "";
$responseType = 0;

//check if the followed user exists
if(!$post || !doesPostExist($mysqli, $post)){
    $responseText = "This post doesn't exist.";
    $responseType = 0;
    die(json_encode(array('text' => $responseText, 'type' => $responseType)));
}
//get the userid
$userID = getUserID($mysqli, $loginValue);

//check if they're validated
if(!isUserIDValidated($mysqli, $userID)){
    $responseText = "Please validate your account.";
    $responseType = 0;
    die(json_encode(array('text' => $responseText, 'type' => $responseType)));
}

//check the users session
if(!checkSession($mysqli)){
    $responseText = "Your session has ended, please re-login.";
    $responseType = 0;
    die(json_encode(array('text' => $responseText, 'type' => $responseType)));
}
if($action === "like") {
    $date = time();
    if ($mysqli->query("INSERT INTO `likes` (id, userid, postid, time) 
        VALUES ('0', '$userID', '$post', '$date')") === TRUE
    ) {
        $responseText = "Post liked.";
        $responseType = 1;
    } else {
        $responseText = "There was a database error.";
        $responseType = 0;
    }
}else if($action === "unlike"){
    if($mysqli->query("DELETE FROM `likes` WHERE userid='$userID' AND postid='$post'") === TRUE){
        $responseText = "Post un-liked.";
        $responseType = 1;
    }else{
        $responseText = "There was a database error.";
        $responseType = 0;
    }

}
echo json_encode(array('text' => $responseText, 'type' => $responseType));

