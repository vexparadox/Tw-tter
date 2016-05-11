/**
 * Created by williammeaton on 28/04/2016.
 */
var currentPage = 0;
var loadAll = false;
function loadPosts() {
    $.post("functions/getPosts.php", {page: currentPage, posts: 10, all: loadAll}).done(
        function (data) {
            try {
                var responseObj = JSON.parse(data);
            } catch (e) {
                alert(e);
            }if (responseObj.type == 0) {
                $("#postContainer").html(responseObj.text);
            }else{
                $("#postContainer").html(responseObj.text);
            }
        })
}

$(document).ready(function() {
    $("#postContainer").html("Loading posts...");
    loadPosts();
    //reload posts every 2 seconds
    setInterval(loadPosts, 2000);


    $("#allPostCheck").click(function (e) {
        loadAll = $("#allPostCheck").prop("checked");
        $("#postContainer").html("Loading posts...");
        loadPosts();
    });


    $("#nextBtnLink").click(function(e) {
        e.preventDefault();
        currentPage+=1;
        loadPosts();
        if(currentPage > 0) {
            $("#backBtn").show();
        }
    });

    $("#backBtnLink").click(function (e) {
        e.preventDefault();
        if(currentPage-1 >= 0){
           currentPage-=1;
        }
        loadPosts();
        if(currentPage == 0){
            $("#backBtn").hide();
        }
    });

});
