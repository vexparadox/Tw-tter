$(document).ready(function() {
    $("#newPostBtn").click(function() {
        $("#postForm").show(400);
        $("#newPostBtn").hide(200);
    })
    $("#postCancel").click(function () {
        $("#postContent").val('');
        $("#postForm").hide(400);
        $("#newPostBtn").show(200);
    })
    $("#postSubmit").click(function () {
        //get the user input
        var postContent = $("#postContent").val();
        //check its length
        if (postContent.length > 0 && postContent.length < 255) {
            //reset the response
            $("#postResponse").html("");
            //post request
            $.post("functions/post.php?a=submit", {content: postContent}).done(
                function (data) {
                    try {
                        var responseObj = JSON.parse(data);
                    } catch (e) {
                        alert(e);
                    }
                    if (responseObj.type == 0) {
                        $("#postResponse").css('color', 'red');
                        $("#postResponse").html(responseObj.text);
                    }else{
                        $("#postContent").val('');
                        loadPosts();
                    }
                })
        }else{
            $("#postResponse").css('color', 'red');
            $("#postResponse").html("Post is either too short or long.");
        }
    })
});