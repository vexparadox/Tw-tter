<input type='submit' id='newPostBtn' style='display: none' class='btn btn-default' value='New Post'>
<?php require("resources/postForm.html"); ?>
<div class='col-sm-8 blog-main'>
    <p>Show all user's posts? <input type="checkbox" id="allPostCheck"></p>
    <span id ='postContainer'></span>
    <nav>
        <ul class='pager'>
            <li id='backBtn' style='display: none'><a id='backBtnLink' href=''>Previous</a></li>
            <li id='nextBtn'><a id='nextBtnLink' href=''>Next</a></li>
        </ul>
    </nav>
</div>