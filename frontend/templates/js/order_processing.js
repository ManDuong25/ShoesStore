// $(document).ready(function () {
//     var submitOrderButton = document.getElementById('order-confirm-submit');
//     if (submitOrderButton.length > 0) {
//         submitOrderButton.on('click', function (e) {
//             e.preventDefault();

//             var inputName = $('#inputNameId').val();
//             var inputPhoneNumber = $('#inputPhoneNumberId').val();
//             var inputAddress = $('#inputAddressId').val();
//             var inputDiscount = $('#inputDiscountId').val();
//             var inputPaymentMethodId = $('#inputPaymentId').val();

//             if (!inputName) {
//                 alert('Please enter your name');
//                 return;
//             }

//             if (!inputPhoneNumber) {
//                 alert('Please enter your phone number');
//                 return;
//             }

//             if (isNaN(inputPhoneNumber)) {
//                 alert('Please enter a valid phone number');
//                 return;
//             }

//             if (!inputAddress) {
//                 alert('Please enter your address');
//                 return;
//             }

//             if(!inputPaymentMethodId) {
//                 alert('Please select a payment method');
//                 return;
//             }

//             $.ajax({
//                 url: 'http://localhost/ShoesStore/frontend/index.php?module=indexphp&action=order',
//                 type: 'POST',
//                 dataType: 'html',
//                 data: {
//                     module: 'cartsection',
//                     action: 'order',
//                     submitOrderButton: true,
//                     inputName: inputName,
//                     inputPhoneNumber: inputPhoneNumber,
//                     inputAddress: inputAddress,
//                     inputDiscount: inputDiscount,
//                     inputPaymentMethod: inputPaymentMethodId,
//                 },
//                 success: function (response) {
//                     var data = JSON.parse(response);
//                     if (data.success) {
//                         alert('Đặt hàng thành công!');
//                     } else {
//                         alert('Đặt hàng thất bại: ' + data.message);
//                     }
//                     console.log(inputPaymentMethod);
//                 },
//             });
//         });
//     }
// });


document.addEventListener('DOMContentLoaded', function () {
    let orderBtn = document.querySelector('#order-confirm-submit');

    orderBtn.addEventListener('click', function (e) {
        e.preventDefault();
        let inputName = document.querySelector('#inputNameId').value;
        let inputPhoneNumber = document.querySelector('#inputPhoneNumberId').value;
        let inputAddress = document.querySelector('#inputAddressId').value;
        // let inputDiscount = document.querySelector('#inputDiscountId').value;
        let inputPaymentMethodId = document.querySelector('#inputPaymentId').value;

        if (!inputName) {
            alert('Please enter your name');
            return;
        }

        if (!inputPhoneNumber) {
            alert('Please enter your phone number');
            return;
        }

        if (isNaN(inputPhoneNumber)) {
            alert('Please enter a valid phone number');
            return;
        }

        if (!inputAddress) {
            alert('Please enter your address');
            return;
        }

        if (!inputPaymentMethodId) {
            alert('Please select a payment method');
            return;
        }

        fetch('http://localhost/ShoesStore/frontend/?module=cartsection&action=order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'submitButton=' + true + '&inputName=' + inputName + '&inputPhoneNumber=' + inputPhoneNumber + '&inputAddress=' + inputAddress + '&inputPaymentMethod=' + inputPaymentMethodId
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.status == 'error') {
                    alert(data.message);
                } else if (data.status == 'success') {
                    alert(data.message);
                    window.location.href = "?module=indexphp&action=product";
                } else if (data.status == 'alert') {
                    let productMustBeDeletedString = data.productMustBeDeleted.join(', ');
                    alert('Product must be deleted from cart because of running out of stocks: ' + productMustBeDeletedString + '. Please reorder later!');
                    window.location.href = 'http://localhost/ShoesStore/frontend/?module=cartsection&action=cart'
                }
            });
    })
})