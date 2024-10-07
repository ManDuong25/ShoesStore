$(document).ready(function () {
    var sizeId = null;

    $(".psize").on("click", ".squish-in", function () {
        console.log("size clicked");
        $(".squish-in").css("color", "");
        $(this).css("color", "black");
        sizeId = $(this).text(); 
    });

    var addtoCartButton = $(".addtocart");
    if (addtoCartButton.length) {
        addtoCartButton.on("click", function () {
            var quantity = $('[name="pquantity"]').val();
            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get("id");

            if (!sizeId) {
                alert("Please select a size");
                return;
            }

            if (!quantity) {
                alert("Please enter quantity");
                return;
            }

            if (isNaN(quantity)) {
                alert("Please enter a valid quantity");
                return;
            }
            
            var quantityNumber = Number(quantity);
            console.log(quantityNumber);
            if (!Number.isInteger(quantityNumber)) {
                alert("Quantity must be an integer");
                return;
            }

            if (quantity <= 0) {
                alert("Quantity should be greater than 0");
                return;
            }

            // Sending data via AJAX
            $.ajax({
                url:
                    "http://localhost/ShoesStore/frontend/?module=indexphp&action=singleproduct&id=" +
                    id,
                type: "POST",
                dataType: "json",
                data: {
                    addtocart: true,
                    sizeItem: sizeId,
                    pquantity: quantity,
                },

                success: function (data) {
                    if (data.status == "success") {
                        alert(data.message);
                        $(".squish-in").css("color", "");
                        $('[name="pquantity"]').val("");
                        sizeId = null;
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
});
