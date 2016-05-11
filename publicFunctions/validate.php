<?php
//this page will validate a users account via their email addresss
require("../functions/connect.php");
$token = $_GET['t'];
$sqlToken = $mysqli->query("SELECT * FROM `users` WHERE emailconfirm='$token' AND validated='0'");
if (mysqli_num_rows($sqlToken) > 0) {
    $user = $sqlToken->fetch_assoc();
    $userID = $user['id'];
    if($mysqli->query("UPDATE `users` SET validated='1' WHERE id='$userID'") === TRUE){
        header( 'Location: /' );
    }
}


