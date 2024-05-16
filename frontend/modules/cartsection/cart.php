<?php
ob_start();

use backend\bus\ProductBUS;
use backend\bus\CartsBUS;
use backend\bus\UserBUS;
use backend\bus\TokenLoginBUS;
use backend\services\session;
use backend\bus\SizeItemsBUS;
use backend\bus\SizeBUS;

requireLogin();
CartsBUS::getInstance()->refreshData();
$token = session::getInstance()->getSession('tokenLogin');
$tokenModel = TokenLoginBUS::getInstance()->getModelByToken($token);
$userModel = UserBUS::getInstance()->getModelById($tokenModel->getUserId());

if ($userModel->getMaNhomQuyen() == 1 || $userModel->getMaNhomQuyen() == 2 || $userModel->getMaNhomQuyen() == 3) {
    echo '<script>';
    echo 'alert("You don\'t have access to this page!")';
    echo '</script>';
    echo '<script>';
    echo 'window.location.href = "?module=indexphp&action=product"';
    echo '</script>';
    die();
}
?>

<?php
// if (isPost()) {
//     if (isset($_POST['quantity'])) {
//         $quantity = $_POST['quantity'];
//         $cartId = $_POST['cartId'];
//         $cart = CartsBUS::getInstance()->getModelById($cartId);
//         $cart->setQuantity($quantity);
//         CartsBUS::getInstance()->updateModel($cart);
//         CartsBUS::getInstance()->refreshData();
//     } else if (isset($_POST['delete'])) {
//         $cartId = $_POST['cartId'];
//         CartsBUS::getInstance()->deleteModel($cartId);
//         CartsBUS::getInstance()->refreshData();
//         //Refresh the page:
//         echo '<script>';
//         echo 'window.location.href = "?module=cartsection&action=cart"';
//         echo '</script>';
//     }
// }
?>
<div id="header">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php layouts("header") ?>
</div>

<body>
    <div id="cart">
        <!-- <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/cart_handler.js"></script> -->
        <div class="cart-header">
            <div class="cart-header-wrapper">
                <h1>Cart</h1>
                <div class="cart-info">
                    <div class="text-group">
                        <label class="cart-header-label" for="cart-total">Total:</label>
                        <div id="cartTotalAll">
                            
                        </div>
                    </div>
                </div>
                <button id="order-continue" type="submit" class="checkout">Continue</button>
            </div>
        </div>

        <div class="cart-content">
            <div class="cart-content-header">
                <div class="header-wrapper">
                    <p class="cart-product">Product</p>
                    <p class="cart-size">Size</p>
                    <p class="cart-price">Price</p>
                    <p class="cart-quantity">Quantity</p>
                    <p class="cart-total">Total</p>
                    <p class="cart-action">Action</p>
                </div>
            </div>
            <div class="areaCartItems">
                <?php
                if (isPost()) {
                    $filterAll = filter();
                    if (isset($filterAll['loadData'])) {
                        $cartList = CartsBUS::getInstance()->getModelByUserId($userModel->getId());

                        foreach ($cartList as $cart) {
                            $product = ProductBUS::getInstance()->getModelById($cart->getProductId());
                            $sizeItems = SizeItemsBUS::getInstance()->getModelBySizeIdAndProductId($cart->getSizeId(), $cart->getProductId());

                            if ($product != null && $sizeItems != null) {
                                if ($sizeItems->getQuantity() == 0 || $product->getStatus() == 'inactive') {
                                    echo '<script>';
                                    echo 'alert("A product you have in cart: ' . $product->getName() . ' is not available or out of stock!")';
                                    echo '</script>';
                                    CartsBUS::getInstance()->deleteModel($cart->getId());
                                    CartsBUS::getInstance()->refreshData();

                                    //Refresh a page?
                                    echo '<script>';
                                    echo 'window.location.href = "?module=cartsection&action=cart"';
                                    echo '</script>';
                                    break;
                                }
                            } else {
                                echo '<script>';
                                echo 'alert("A product you have in cart is not available or out of stock!")';
                                echo '</script>';
                                CartsBUS::getInstance()->deleteModel($cart->getId());
                                CartsBUS::getInstance()->refreshData();

                                //Refresh a page?
                                echo '<script>';
                                echo 'window.location.href = "?module=cartsection&action=cart"';
                                echo '</script>';
                                break;
                            }
                        }

                        $cartListArray = array_map(function ($cart) {
                            $cartArray = $cart->toArray();
                            $productModel = ProductBUS::getInstance()->getModelById($cart->getProductId());
                            $productImage = $productModel->getImage();
                            $productName = $productModel->getName();
                            $productPrice = $productModel->getPrice();
                            $totaPrice = (float)$productModel->getPrice() * (float)$cart->getQuantity();
                            $cartArray['productImage'] = $productImage;
                            $cartArray['productName'] = $productName;
                            $cartArray['productPrice'] = $productPrice;
                            $cartArray['totalPrice'] = $totaPrice;
                            return $cartArray;
                        }, $cartList);

                        ob_end_clean();
                        header('Content-Type: application/json');
                        echo json_encode(['cartList' => $cartListArray]);
                        exit;
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <?php
    if (isPost()) {
        $filterAll = filter();
        if (isset($filterAll['plusBtn']) && isset($filterAll['cartId'])) {
            $cartId = $filterAll['cartId'];
            $cart = CartsBUS::getInstance()->getModelById($cartId);
            $cart->setQuantity($cart->getQuantity() + 1);
            CartsBUS::getInstance()->updateModel($cart);
            CartsBUS::getInstance()->refreshData();
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
            exit;
        }
    }

    if (isPost()) {
        $filterAll = filter();
        if (isset($filterAll['minusBtn']) && isset($filterAll['cartId'])) {
            $cartId = $filterAll['cartId'];
            $cart = CartsBUS::getInstance()->getModelById($cartId);
            if ($cart->getQuantity() > 1) {
                $cart->setQuantity($cart->getQuantity() - 1);
                CartsBUS::getInstance()->updateModel($cart);
                CartsBUS::getInstance()->refreshData();
                ob_end_clean();
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success']);
                exit;
            } else {
                $result = CartsBUS::getInstance()->deleteModel($cartId);
                ob_end_clean();
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success']);
                exit;
            }
        }
    }

    if (isPost()) {
        $filterAll = filter();
        if (isset($filterAll['deleteItemInCartBtn']) && isset($filterAll['cartId'])) {
            $cartId = $filterAll['cartId'];
            $cart = CartsBUS::getInstance()->getModelById($cartId);

            $result = CartsBUS::getInstance()->deleteModel($cartId);
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
            exit;
        }
    }
    ?>
</body>
<!--Footer-->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let areaCartItems = document.querySelector('.areaCartItems');
        let cartTotalAll = document.getElementById('cartTotalAll');

        function loadData() {
            fetch('http://localhost/ShoesStore/frontend/?module=cartsection&action=cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'loadData=' + true
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    console.log(data);
                    areaCartItems.innerHTML = toHTMLCartList(data.cartList);
                    cartTotalAll.innerHTML = countTotalAll(data.cartList);
                    addEventToPlusBtn();
                    addEventToMinusBtn();
                    addEventToDeleteBtn();
                });
        }

        loadData();

        function toHTMLCartList(cartList) {
            let html = '';
            cartList.forEach(function(cart) {
                html += `
                <div id="${cart.id}" class="item-container" name="itemContainer">
                    <div class="cart-item cart-product" name="cartProduct">
                        <img src="${cart.productImage}" alt="" name="productImage">
                        <p name="productName">${cart.productName}</p>
                    </div>
                    <div class="cart-item cart-size" name="cartSize">
                        <p name="sizeNumber">${cart.sizeId}</p>
                    </div>
                    <div class="cart-item cart-price" name="cartPrice">${cart.productPrice}</div>
                    <div class="cart-item cart-quantity" name="cartQuantity">
                        <button class="minus-btn minusBtn" name="">-</button>
                        <input type="number" class="productQuantity" value="${cart.quantity}" readonly>
                        <button class="plus-btn plusBtn" name="">+</button>
                    </div>
                    <div class="cart-item cart-total" name="cartTotal">${cart.totalPrice}</div>
                    <div class="cart-item cart-action deleteItemInCartBtn" name="">
                        <i class="fa-solid fa-trash" style="color: #ff0700a6;" name="trashIcon"></i>
                    </div>
                </div>
                `
            })
            return html;
        }

        function countTotalAll(cartList) {
            let total = 0;
            cartList.forEach(function(cart) {
                total += cart.totalPrice;
            })
            return total;
        }

        function addEventToPlusBtn() {
            let plusBtns = document.querySelectorAll('.plusBtn');
            plusBtns.forEach(function(plusBtn) {
                plusBtn.addEventListener('click', function() {
                    row = this.closest('.item-container');
                    let cartId = row.id;
                    fetch('http://localhost/ShoesStore/frontend/?module=cartsection&action=cart', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'plusBtn=' + true + '&cartId=' + cartId
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            if (data.status == 'success') {
                                loadData();
                            }
                        });
                });
            })
        }

        function addEventToMinusBtn() {
            let minusBtns = document.querySelectorAll('.minusBtn');
            minusBtns.forEach(function(minusBtn) {
                minusBtn.addEventListener('click', function() {
                    row = this.closest('.item-container');
                    let cartId = row.id;
                    fetch('http://localhost/ShoesStore/frontend/?module=cartsection&action=cart', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'minusBtn=' + true + '&cartId=' + cartId
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            if (data.status == 'success') {
                                loadData();
                            }
                        });
                });
            })
        }

        function addEventToDeleteBtn() {
            let deleteItemInCartBtns = document.querySelectorAll('.deleteItemInCartBtn');
            deleteItemInCartBtns.forEach(function(deleteItemInCartBtn) {
                deleteItemInCartBtn.addEventListener('click', function() {
                    row = this.closest('.item-container');
                    let cartId = row.id;
                    fetch('http://localhost/ShoesStore/frontend/?module=cartsection&action=cart', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'deleteItemInCartBtn=' + true + '&cartId=' + cartId
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            if (data.status == 'success') {
                                loadData();
                            }
                        });
                })
            })
        }

        let orderContinue = document.getElementById('order-continue');
        orderContinue.addEventListener('click', function() {
            window.location.href = 'http://localhost/ShoesStore/frontend/?module=cartsection&action=order';
        })
    });
</script>
<div id="footer" style="margin-top: 15rem">
    <?php layouts('footer') ?>
</div>