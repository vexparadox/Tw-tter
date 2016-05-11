<?php
require ("../functions/databaseFunctions.php");
require ("../functions/connect.php");
if (isset($_COOKIE['twatterUser'])) {
    setcookie("twatterUser", null, 0, '/');
}
removeSession($mysqli, $_COOKIE['twatterSession']);
header( 'Location: /' );

