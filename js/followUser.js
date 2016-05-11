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

$(document).ready(function() {
    $("#followBtn").click(function (e) {
        e.preventDefault();
        followUser();
    });
    $("#unFollowBtn").click(function (e) {
        e.preventDefault();
        unFollowUser();
    });

});

function unFollowUser() {
    $.post("functions/follow.php", {
        f: getUrlParameter('u'),
        un: 1
    }).done(
        function (data) {
            //parse the json
            try {
                var responseObj = JSON.parse(data);
            } catch (e) {
                alert(e);
            }
            $("#response").html(responseObj.text);
            //depending on the response type
            if (responseObj.type == 1){
                $("#response").css('color', 'green');
                setTimeout(changeScreen, 500);
            } else {
                $("#response").css('color', 'red');
            }
        });
}

function followUser(){
    $.post("functions/follow.php", {
        f: getUrlParameter('u')
    }).done(
        function (data) {
            //parse the json
            try {
                var responseObj = JSON.parse(data);
            } catch (e) {
                alert(e);
            }
            $("#response").html(responseObj.text);
            //depending on the response type
            if (responseObj.type == 1){
                $("#response").css('color', 'green');
                setTimeout(changeScreen, 500);
            } else {
                $("#response").css('color', 'red');
            }
        });
}

function changeScreen(){
    document.location.href = "/?p=account&u="+getUrlParameter('u');
}
