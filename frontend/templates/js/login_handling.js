// $(document).ready(function () {
//     var loginBtn = $("#loginBtn");

//     if (loginBtn.length) {
//         loginBtn.on("click", function (e) {
//             var email = $('input[name="email"]').val();
//             var password = $('input[name="password"]').val();
//             if ($('.messageErrorDetail').length) {
//                 $('.messageErrorDetail').text();
//             }
//             $.ajax({
//                 url: "http://localhost/ShoesStore/frontend/?module=auth&action=login",
//                 type: "POST",
//                 dataType: "json",
//                 data: {
//                     email: email,
//                     password: password
//                 },

//                 success: function (data) {
//                     // Xử lý phản hồi từ server ở đây
//                     console.log(data); // Hiển thị dữ liệu phản hồi từ server
//                     if (data.status == 'error') {
//                         if ($('.messageErrorDetail').length) {
//                             $('.messageErrorDetail').text(data.message);
//                         } else {
//                             $('.message').append("<div class='messageErrorDetail cw text-center alert alert-danger'>" + data.message + "</div>");
//                         }
//                     } else {
//                         window.location.href = "?module=indexphp&action=userhomepage";
//                     }
//                 },
//                 error: function (xhr, status, error) {
//                     console.error("Error:", error);
//                     alert("Error occurred. Please try again.");
//                 },
//             });
//         });
//     }
// });

document.addEventListener('DOMContentLoaded', function () {
    let loginBtn = document.getElementById('loginBtn');


    function loginHandler() {
        let email = document.querySelector("input[name='email']").value;
        let password = document.querySelector("input[name='password']").value;
        fetch('http://localhost/ShoesStore/frontend/?module=auth&action=login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'email=' + email + '&password=' + password
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.status == "success") {
                    window.location.href = "?module=indexphp&action=userhomepage";
                } else if (data.status == "error") {

                    if (document.querySelector('.messageErrorDetail') != null) {
                        document.querySelector('.messageErrorDetail').innerHTML = data.message;
                    } else {
                        let newDiv = document.createElement("div");
                        newDiv.setAttribute('class', 'messageErrorDetail cw text-center alert alert-danger');
                        newDiv.innerHTML = data.message;
                        document.querySelector('.message').append(newDiv);
                    }
                }
            });
    }

    loginBtn.addEventListener("click", function () {
        loginHandler();
    })

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            loginHandler();
        }
    });
});
