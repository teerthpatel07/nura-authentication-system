$(document).ready(function () {

    $("#registerForm").submit(function (event) {

        event.preventDefault();


        const username = $("#username").val().trim();
        const email = $("#email").val().trim();
        const password = $("#password").val();
        const confirmPassword = $("#confirm_password").val();


        $("#message").html("");


        // Check passwords

        if (password !== confirmPassword) {

            $("#message").html(
                '<div class="alert alert-danger">' +
                'Passwords do not match.' +
                '</div>'
            );

            return;
        }


        // Send data to PHP

        $.ajax({

            url: "php/register.php",

            type: "POST",

            dataType: "json",

            data: {
                username: username,
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


                    $("#registerForm")[0].reset();

                } else {

                    $("#message").html(
                        '<div class="alert alert-danger">' +
                        response.message +
                        '</div>'
                    );

                }

            },


            error: function () {

                $("#message").html(
                    '<div class="alert alert-danger">' +
                    'Something went wrong. Please try again.' +
                    '</div>'
                );

            }

        });

    });

});