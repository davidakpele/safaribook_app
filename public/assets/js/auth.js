import xhrClient from "./libs/xhrClient" 


$(document).ready(() => {
 
    $("#error").hide();
    $(".statusMsg").hide();

    $('#signIn_form').submit((e) => {
        e.preventDefault();
        const email = $("#email").val().trim();
        const password = $("#password").val().trim();
        const rememberMe = $('#rememberMe').is(':checked') ? 'Yes:Checked' : '';

        if (email === '') {
            $("#error").fadeIn().text("Email address is required.");
            $("#email").focus();
            return false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            $("#error").fadeIn().text("Please enter a valid email address.");
            $("#email").focus();
            return false;
        }

        if (password === '') {
            $("#error").fadeIn().text("Password is required.");
            $("#password").focus();
            return false;
        }

        $("#error").hide(); 

        const data = {
            email: email,
            password: password,
            rememberMe: rememberMe
        };

        $.ajax({
            url: 'login',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                if (response.redirect) {
                    window.location.href = base_url+'dashboard/'+response.redirect;
                }
            },
            error: function (xhr) {
                let message = 'Login failed. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                $("#error").fadeIn().text(message);
            }
        });
    });

  
});


