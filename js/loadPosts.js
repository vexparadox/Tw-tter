/**
 * Created by williammeaton on 04/05/2016.
 * This loads posts for a specific user
 */
var currentPage = 0;

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

function loadPosts() {
    $.post("functions/getPosts.php", {page: currentPage, posts: 7
        , a: 1, user: getUrlParameter('u')}).done(
        function (data) {
            try {
                var responseObj = JSON.parse(data);
            } catch (e) {
                alert(e);
            }
            if (responseObj.type == 0) {
                $("#postContainer").html(responseObj.text);
            }else{
                $("#postContainer").html(responseObj.text);
            }
        })
}

$(document).ready(function() {
    $("#postContainer").html("Loading user posts...");
    loadPosts();
    //reload posts every 10 seconds
    setInterval(loadPosts, 10000);

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
