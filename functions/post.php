<?php
require("connect.php");
require("databaseFunctions.php");
$action = htmlspecialchars($_GET['a']);
$loginValue = $_COOKIE['twatterUser'];
$loginSession = $_COOKIE['twatterSession'];
$responseText = "";
$responseType = 0;

if(!$loginValue || !checkSession($mysqli)){
    $responseText = "Your login has expired.";
    $responseType = 0;
    die(json_encode(array('text' => $responseText, 'type' => $responseType)));
}

if(!isUserValidated($mysqli, $loginValue)){
    $responseText = "Your account is not validated, see your emails.";
    $responseType = 0;
    die(json_encode(array('text' => $responseText, 'type' => $responseType)));
}

if($action === "submit"){
    $postContent = htmlspecialchars(mysqli_real_escape_string($mysqli, $_POST['content']));
    $userID = getUserID($mysqli, $loginValue);
    $date = time();

    $userTag = strstr($postContent, '@');
    if($userTag != "") {
        $userTag = $userTag." ";
        $userTag = strstr($userTag, ' ', TRUE);
        $userTagID = getUserID($mysqli, substr($userTag, 1));
        if ($userTagID >=0) {
            $string = "<a href=\"/?p=account&u=".$userTagID."\">$userTag</a>";
            $postContent = str_replace($userTag, $string, $postContent);
        }
    }
//    $postContent));

    if($mysqli->query("INSERT INTO `posts` (id, user, content, date) 
        VALUES ('0', '$userID', '$postContent', '$date')") === TRUE){
        $responseText = "Post added successfully.";
        $responseType = 1;
    }else{
        $responseText = "Server error.";
        $responseType = 0;
    }
}

echo json_encode(array('text' => $responseText, 'type' => $responseType));