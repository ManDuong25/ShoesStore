document.addEventListener('DOMContentLoaded', function () {
    // Select all card bodies
    let cardBodies = document.querySelectorAll('.card-body');

    // Iterate over card bodies
    cardBodies.forEach((cardBody) => {
        // Attach click event listener to each card body
        cardBody.addEventListener('click', (event) => {
            // Handle the event here
            //console.log('Card body clicked:', event.target);
        });
    });

    // Select the reset button inside the first card
    let firstCard = document.querySelector('.card-body');
    let resetButton = firstCard.querySelector('#resetButton');

    // Select the image upload button
    let imageUploadButton = document.getElementById('imageUploadIdButton');
    let showImage = document.getElementById('showImageId');

    if (imageUploadButton) {
        imageUploadButton.addEventListener('change', (event) => {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    // The result attribute contains the file's data as a base64 encoded string
                    let base64Image = e.target.result;
                    showImage.src = base64Image;

                    // Create an AJAX request
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', 'http://localhost/ShoesStore/frontend/?module=account&action=profilesetting', true);

                    // Set up a handler for when the request finishes
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            console.log('Upload complete!');
                        } else {
                            console.error('An error occurred during the upload.');
                        }
                    };

                    // Send the AJAX request
                    let formData = new FormData();
                    formData.append("image", base64Image);
                    xhr.send(formData);
                }
                reader.readAsDataURL(file);
            }
        });
    }


    //let saveButton = firstCard.querySelector('#applyChangesFirstCard');
    let saveButton = document.getElementById('applyChangesFirstCard');
    if (saveButton) {
        saveButton.addEventListener('click', (event) => {
            let username = document.getElementById('usernameId');
            let name = document.getElementById('accountNameId');
            let email = document.getElementById('mailAccountId');
            let maleGender = document.getElementById('male');
            let femaleGender = document.getElementById('female');

            if (name.value === "") {
                alert("Please enter your name!");
                return;
            }

            if (email.value === "") {
                alert("Please enter your email!");
                return;
            } else {
                // Biểu thức chính quy để kiểm tra định dạng email
                var emailPattern = /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/;


                // Kiểm tra email có khớp với định dạng không
                if (!emailPattern.test(email.value)) {
                    alert("Please enter a valid email address!");
                    return;
                }
            }
            // Use ajax to send the data only if there is a change:
            $.ajax({
                url: "http://localhost/ShoesStore/frontend/?module=account&action=profilesetting",
                type: "POST",
                dataType: "json",
                data: {
                    username: username.value,
                    'account-name': name.value,
                    mailAccount: email.value,
                    maleGender: maleGender.checked,
                    femaleGender: femaleGender.checked,
                    gender: maleGender.checked ? 'male' : 'female',
                    showImage: showImage.src,
                    saveButton: true,
                },
                success: function (data) {
                    console.log(data);
                    if (data.status == "success") {
                        alert(data.message);
                    } else if (data.status == "error") {
                        alert(data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                    alert("Error occurred. Please try again.");
                },
            });
        });
    }

    // Select the reset button inside the second card
    let changePasswordButton = document.getElementById('applyChangesSecondCard');
    if (changePasswordButton) {
        // Attach click event listener to the save button
        changePasswordButton.addEventListener('click', (event) => {
            // Get the input fields and checkboxes
            let currentPassword = document.getElementById('currentPassword');
            let newPassword = document.getElementById('newPassword');
            let confirmNewPassword = document.getElementById('repeatNewPassword');

            //Check for empty current password field:
            if (currentPassword.value === "") {
                alert("Please enter your current password!");
                return;
            }

            //Check for empty new password field:
            if (newPassword.value === "") {
                alert("Please enter your new password!");
                return;
            }

            //Check for empty confirm new password field:
            if (confirmNewPassword.value === "") {
                alert("Please confirm your new password!");
                return;
            }

            //Check if new password and confirm new password match:
            if (newPassword.value !== confirmNewPassword.value) {
                alert("New password and confirm new password do not match!");
                return;
            }

            if (currentPassword.value === newPassword.value) {
                alert("New password cannot be the same as the current password!");
                return;
            }

            $.ajax({
                url: "http://localhost/ShoesStore/frontend/index.php?module=account&action=profilesetting",
                type: "POST",
                dataType: "json",
                data: {
                    'saveButtonPassword': true,
                    'currentPassword': currentPassword.value,
                    'newPassword': newPassword.value,
                    'repeatNewPassword': confirmNewPassword.value
                },
                success: function (data) {
                    currentPassword.value = "";
                    newPassword.value = "";
                    confirmNewPassword.value = "";
                    if (data.status == "success") {
                        alert(data.message);
                    } else if (data.status == "error") {
                        alert(data.message);
                    }
                },
            });
        });
    }

    let changeContactButton = document.getElementById('applyChangesThirdCard');
    if (changeContactButton) {
        // Attach click event listener to the save button
        changeContactButton.addEventListener('click', (event) => {
            // Get the input fields and checkboxes
            let phoneNumber = document.getElementById('phoneField');
            let address = document.getElementById('addressField');

            //Check for empty phone number field:
            if (phoneNumber.value === "") {
                alert("Please enter your phone number!");
                return;
            } else {
                var regex = /(84|0[3|5|7|8|9])+([0-9]{8})/;
                if (!regex.test(phoneNumber.value)) {
                    alert("Invalid phone number!");
                    return;
                }
            }

            //Check for empty address field:
            if (address.value === "") {
                alert("Please enter your address!");
                return;
            }

            $.ajax({
                url: "http://localhost/ShoesStore/frontend/index.php?module=account&action=profilesetting",
                type: "POST",
                dataType: "json",
                data: {
                    'phone-customer': phoneNumber.value,
                    'address-customer': address.value,
                    saveContactButton: true,
                },
                success: function (data) {
                    if (data.status == "success") {
                        alert(data.message);
                    } else if (data.status == "error") {
                        alert(data.message);
                    }
                },
            });

        });
    }
});