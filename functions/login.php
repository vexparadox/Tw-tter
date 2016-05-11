<?php
require("connect.php");
require("databaseFunctions.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = htmlspecialchars(mysqli_real_escape_string($mysqli, $_POST['username']));
$password = htmlspecialchars(mysqli_real_escape_string($mysqli, $_POST['password']));
$signup = htmlspecialchars(mysqli_real_escape_string($mysqli, $_POST['signup']));
$email = htmlspecialchars(mysqli_real_escape_string($mysqli, $_POST['email']));
$rememberMe = htmlspecialchars(mysqli_real_escape_string($mysqli, $_POST['remember']));
$responseText = ""; // what the response should show
$responseType = 0; // 0 is bad, 1 is good
if(!$username || !$password){
    header( 'Location: account.php' ) ;
}
//if login, has to be string comparision because it's stupid
if($signup === "false") {
    $sqlSalt = $mysqli->query("SELECT * FROM `users` WHERE username='$username'");
    //if there is no user
    if (mysqli_num_rows($sqlSalt) != 1) {
        $responseText = "Account doesn't exist. Did you mean to create one?";
        $responseType = 0;
    } else {
        $saltReturn = $sqlSalt->fetch_assoc();
        $password = hash('sha256', $password . $saltReturn['salt']);

        $sqlPassword = $mysqli->query("SELECT * FROM `users` WHERE username='$username' AND password='$password'");
        if (mysqli_num_rows($sqlPassword) != 1) {
            $responseText = "Password is incorrect.";
            $responseType = 0;
        } else {
            $session = rand(0, 9223372036854775800);
            $userID = $sqlPassword->fetch_row()[0];
            if($rememberMe === "true"){
                $timeout = time() + (86400 * 30);
            }else{
                $timeout = time() + (86400);
            }
            if($mysqli->query("INSERT INTO `sessions` (id, user, time) VALUES ('$session', '$userID', '$timeout')") === TRUE) {
                createCookie($timeout, $username, $session);
                $responseText = "You're logged in";
                $responseType = 1;
            }else{
                $responseText = "There an error creating a session.";
                $responseType = 0;
            }
        }
    }
    echo json_encode(array('text' => $responseText, 'type' => $responseType));
}else{
    if(strlen($password) < 6){
        $responseText = "Password is too short, 6 characters or more.";
        $responseType = 0;
    }else if(strlen($username) > 20) {
        $responseText = "Username is too long, less than 20 characters.";
        $responseType = 0;
    }else if(strlen($email >= 255)){
        $responseText = "Email is too long.";
        $responseType = 0;
    }else{
        //check if it already exists
        $sqlUsercheck = $mysqli->query("SELECT * FROM `users` WHERE username='$username'");
        if (mysqli_num_rows($sqlUsercheck) > 0) {
            $responseText = "This account already exists.";
            $responseType = 0;
        }else {
            //if every check is ok, lets create the account
            $salt = uniqid(mt_rand(), true);
            $password = hash('sha256', $password . $salt);
            $emailConfirm = uniqid(mt_rand(), true); // this is used to validate the account
            //insert
            if ($mysqli->query("INSERT INTO `users` (id, username, tag, email, password, salt, emailconfirm, validated) 
        VALUES ('0', '$username', 'Your tag goes here.','$email','$password', '$salt', '$emailConfirm', 0)") === TRUE
            ) {
                $session = rand(0, 9223372036854775800);
                $userID = getUserID($mysqli, $username);
                if($rememberMe === "true"){
                    $timeout = time() + (86400 * 30);
                }else{
                    $timeout = time() + (86400);
                }
                if($mysqli->query("INSERT INTO `sessions` (id, user, time) VALUES ('$session', '$userID', '$timeout')") === TRUE) {
                    createCookie($timeout, $username, $session);
                    //crete the response
                    $responseText = "Your account has been created, we've emailed you.";
                    $responseType = 1;
                    //now email them the validation token
                    $message = "Thank you for signing up to Twatter! Please either click this link or copy it into your browser to validate your account. \n http://twitter.wmapp.uk/publicFunctions/validate.php?t=" . $emailConfirm;
                    $subject = 'Validate Account';
                    $headers = 'From: no-reply@twitter.wmapp.uk' . "\r\n" .
                        'Reply-To: no-reply@twitter.wmapp.uk' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
                    mail($email, $subject, $message, $headers);
                }else{
                    $responseText = "There was a database error.";
                    $responseType = 0;
                }
            } else {
                $responseText = "There was a database error.";
                $responseType = 0;
            }
        }
    }
    //return the json
    echo json_encode(array('text' => $responseText, 'type' => $responseType));
}
 
function createCookie($timeout, $username, $session){
    //create a cookie
    setcookie("twatterSession", $session, $timeout, "/");
    setcookie("twatterUser", $username, $timeout, "/");
}
?>
