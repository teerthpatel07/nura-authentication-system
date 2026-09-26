$(document).ready(function () {


    $("#loginForm").submit(function (event) {

        event.preventDefault();


        const email = $("#email").val().trim();

        const password = $("#password").val();


        $("#message").html("");


        $.ajax({

            url: "php/login.php",

            type: "POST",

            dataType: "json",

            xhrFields: {
                withCredentials: true
            },

            data: {
                email: email,
                password: password
            },


            success: function (response) {

                if (response.success) {

                    $("#message").html(
                        '<div class="alert alert-success">' +
                        response.message +
                        '</div>'
                    );


                    setTimeout(function () {

                        window.location.href =
                            "profile.html";

                    }, 700);


                } else {

                    $("#message").html(
                        '<div class="alert alert-danger">' +
                        response.message +
                        '</div>'
                    );

                }

            },


            error: function (xhr) {

                console.log(
                    "Login error:",
                    xhr.responseText
                );

                $("#message").html(
                    '<div class="alert alert-danger">' +
                    'Unable to connect to server.' +
                    '</div>'
                );

            }

        });

    });

});