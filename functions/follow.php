<?php
require("connect.php");
require ("databaseFunctions.php");
//get the data required
$session = $_COOKIE['twatterSession'];
$loginValue = $_COOKIE['twatterUser'];
$follower = $_POST['f'];
$unFollow = $_POST['un'];
$responseText = "";
$responseType = 0;


//check if the followed user exists
if(!$follower || !doesUserExist($mysqli, $follower)){
    $responseText = "This user doesn't exist.";
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

if(!$unFollow) {
    $date = time();
    if ($mysqli->query("INSERT INTO `follows` (id, userid, followid, time) 
        VALUES ('0', '$userID', '$follower', '$date')") === TRUE) {
        $responseText = "You are now following this user.";
        $responseType = 1;
    }else{
        $responseText = "There was a database error.";
        $responseType = 0;
    }
}else{
    if($mysqli->query("DELETE FROM `follows` WHERE userid='$userID' AND followid='$follower'") === TRUE){
        $responseText = "You're no longer following this person.";
        $responseType = 1;
    }else{
        $responseText = "There was a database error.";
        $responseType = 0;
    }
}
echo json_encode(array('text' => $responseText, 'type' => $responseType));

