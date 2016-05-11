<?php
    require("functions/connect.php");
    $loginValue = $_COOKIE['twatterUser'];
    $pageID = $_GET['p'];
?>
<html lang="en">
<head>
    <?php require("views/header.php"); ?>
</head>

<body>

<div class="blog-masthead">
    <div class="container">
        <nav class="blog-nav">
            <?php
            if(isset($pageID)){
                echo "<a class='blog-nav-item' href='http://twitter.wmapp.uk'>Home</a>
                <a class='blog-nav-item active' href='/?p=account'>Account</a>";
            }else{
                echo "<a class='blog-nav-item active' href='http://twitter.wmapp.uk'>Home</a>
                <a class='blog-nav-item' href='/?p=account'>Account</a>";
            }
            ?>
        </nav>
    </div>
</div>

<div class="container">
    <div class="row">
        <?php
            if(isset($pageID)){
                //try and include the file
                if(!include ("views/$pageID.php")) {
                    include ("views/404.php");
                }
            }else {
                echo "<div class='blog-header'>
        <h1 class='blog-title'>Tw*tter</h1>
        <p class='lead blog-description'>The un-official clone of Twitter.</p>
        </div>";
                if (!$loginValue) {
                    //not logged in so just show nothing
                    include("views/welcome.php");
                } else {
                    //standard posts
                    include("views/feed.php");
                }
            }
        ?>
        <?php require("views/sidebar.html"); ?>
    </div><!-- /.row -->
        <?php require("views/footer.php"); ?>
    </div><!-- /.container -->
</body>

