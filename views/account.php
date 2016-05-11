<?php
    require("functions/connect.php");
    require("functions/databaseFunctions.php");
    $loginValue = $_COOKIE['twatterUser'];
    $userID = htmlspecialchars($_GET['u']);
    if(!$loginValue){
            require("resources/loginForm.html");
        echo "<script src='js/validateLogin.js'></script>";
    }else {
        echo "<div class='row'>"; //keep the formating nice
        if ($userID && getUserID($mysqli, $loginValue) != $userID) {
            require("resources/userAccount.php");
        } else {
            require("resources/yourAccount.html");
        }
        //start the main bit
        echo "<div class='col-sm-8 blog-main'><br /><span id ='postContainer'></span>
                 <nav>
                   <ul class='pager'>
                        <li id='backBtn' style='display: none'><a id='backBtnLink' href=''>Previous</a></li>
                        <li id='nextBtn'><a id='nextBtnLink' href=''>Next</a></li>
                    </ul>
                    </nav></div>";
        echo "<script src='js/loadPosts.js'></script>
        <script src='js/followUser.js'></script>";
        }
?>
