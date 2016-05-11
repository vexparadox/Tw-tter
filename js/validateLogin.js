function validateLogin() {
    var email = $("#email").val();
    var username = $("#username").val();
    var password = $("#password").val();
    var signupCheck = $("#signupCheck").prop("checked");
    var rememberCheck = $("#rememberCheck").prop("checked");
    if ((username != "") && (password != "")) {
        //you need email if you're signing up
        if(signupCheck && email === ""){
            $("#response").css('color', 'red');
            $("#response").html("Emails are required for new accounts.");
        }else{
            $.post("functions/login.php", {
                email: email,
                username: username,
                password: password,
                signup: signupCheck,
                remember: rememberCheck
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
                    if (responseObj.type == 1) {
                        $("#response").css('color', 'green');
                        setTimeout(changeScreen, 500);
                    } else {
                        $("#response").css('color', 'red');
                    }
                });
        }
    }else {
        $("#response").css('color', 'red');
        $("#response").html("Both fields are required.");
    }
    return false;
}

function changeScreen(){
    document.location.href = "/?p=account";
}

$(document).ready(function() {
    $("#signupCheck").click(function () {
        if($("#signupCheck").prop("checked")){
            $("#emailField").show(400);
        }else{
            $("#emailField").hide(400);
        }
    });
});