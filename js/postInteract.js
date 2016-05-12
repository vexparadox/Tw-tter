$(document).ready(function() {
    $("#postLike").click(function() {
        console.log("log");
    })
});

function likePost(postID) {
    $.post("functions/postInteract.php", {p:postID, a: "like"}).done(
        function (data) {
            try {
                var responseObj = JSON.parse(data);
            } catch (e) {
                alert(e);
            }if (responseObj.type == 0) {
                $("#postContainer").html(responseObj.text);
            }else{
                loadPosts();
                // $("#postContainer").html(responseObj.text);
            }
        })

}

function unLikePost(postID) {
    $.post("functions/postInteract.php", {p:postID, a: "unlike"}).done(
        function (data) {
            try {
                var responseObj = JSON.parse(data);
            } catch (e) {
                alert(e);
            }if (responseObj.type == 0) {
                $("#postContainer").html(responseObj.text);
            }else{
                loadPosts();
                // $("#postContainer").html(responseObj.text);
            }
        })

}
