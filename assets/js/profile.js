$(document).ready(function () {


    // Load profile

    loadProfile();


    function loadProfile() {

        $.ajax({

            url: "php/profile.php",

            type: "GET",

            dataType: "json",

            xhrFields: {
                withCredentials: true
            },


            success: function (response) {

                if (!response.success) {

                    window.location.href =
                        "login.html";

                    return;
                }


                // MySQL data

                $("#username").text(
                    response.user.username
                );

                $("#email").text(
                    response.user.email
                );


                // MongoDB data

                $("#full_name").val(
                    response.profile.full_name
                );

                $("#phone").val(
                    response.profile.phone
                );

                $("#bio").val(
                    response.profile.bio
                );

            },


            error: function () {

                window.location.href =
                    "login.html";

            }

        });

    }


    // Save profile

    $("#profileForm").submit(function (event) {

        event.preventDefault();


        $.ajax({

            url: "php/profile.php",

            type: "POST",

            dataType: "json",

            xhrFields: {
                withCredentials: true
            },


            data: {

                full_name:
                    $("#full_name").val().trim(),

                phone:
                    $("#phone").val().trim(),

                bio:
                    $("#bio").val().trim()

            },


            success: function (response) {

                if (response.success) {

                    $("#profileMessage").html(
                        '<div class="alert alert-success">' +
                        response.message +
                        '</div>'
                    );

                } else {

                    $("#profileMessage").html(
                        '<div class="alert alert-danger">' +
                        response.message +
                        '</div>'
                    );

                }

            },


            error: function () {

                $("#profileMessage").html(
                    '<div class="alert alert-danger">' +
                    'Unable to save profile.' +
                    '</div>'
                );

            }

        });

    });


    // Logout

    $("#logoutBtn").click(function () {

        $.ajax({

            url: "php/logout.php",

            type: "POST",

            dataType: "json",

            xhrFields: {
                withCredentials: true
            },


            success: function () {

                window.location.href =
                    "login.html";

            },


            error: function () {

                window.location.href =
                    "login.html";

            }

        });

    });

});