<?php
use PHPUnit\Framework\Constraint\IsEmpty;
ob_start();

use backend\bus\PaymentMethodsBUS;
use backend\bus\ProductBUS;
use backend\bus\CartsBUS;
use backend\bus\UserBUS;
use backend\bus\TokenLoginBUS;
use backend\enums\OrderStatusEnums;
use backend\models\OrderItemsModel;
use backend\models\OrdersModel;
use backend\services\session;
use backend\bus\SizeItemsBUS;
use backend\bus\OrdersBUS;
use backend\bus\OrderItemsBUS;
use backend\bus\CouponsBUS;
use backend\models\PaymentsModel;
use backend\bus\PaymentsBUS;
use backend\services\validation;

requireLogin();
CartsBUS::getInstance()->refreshData();
$token = session::getInstance()->getSession('tokenLogin');
$tokenModel = TokenLoginBUS::getInstance()->getModelByToken($token);
$userModel = UserBUS::getInstance()->getModelById($tokenModel->getUserId());
$cartListFromUser = CartsBUS::getInstance()->getModelByUserId($userModel->getId());

if (count($cartListFromUser) == 0) {
    echo '<script>';
    echo 'alert("Your cart is currently empty!")';
    echo '</script>';
    echo '<script>';
    echo 'window.location.href = "?module=cartsection&action=cart"';
    echo '</script>';
    die();
}

if ($userModel->getMaNhomQuyen() == 1 || $userModel->getMaNhomQuyen() == 2 || $userModel->getMaNhomQuyen() == 3) {
    echo '<script>';
    echo 'alert("You don\'t have access to this page!")';
    echo '</script>';
    echo '<script>';
    echo 'window.location.href = "?module=indexphp&action=product"';
    echo '</script>';
    die();
}

$totalPriceOfCart = 0;
foreach ($cartListFromUser as $cartModel) {
    $productModel = ProductBUS::getInstance()->getModelById($cartModel->getProductId());
    $totalPriceOfCart += $productModel->getPrice() * $cartModel->getQuantity();
}
?>

<div id="header">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php layouts("header") ?>
</div>

<body>
    <div class="order-confirm">
        <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/cart_handler.js"></script>
        <h2>Order Confirm</h2>
        <form class="row g-1" method="POST">
            <h4>Customer Info</h4>
            <div class="col-5">
                <label for="inputName" class="form-label">Name</label>
                <input type="text" class="form-control form-control-sm" id="inputNameId" name="inputName"
                    value="<?php echo $userModel->getName(); ?>">
            </div>
            <div class="col-3">
                <label for="inputText" class="form-label">Phone Number</label>
                <input type="text" class="form-control form-control-sm" id="inputPhoneNumberId" name="inputPhoneNumber"
                    value="<?php echo $userModel->getPhone(); ?>">
            </div>
            <!-- <div class="col-5">
                <label for="inputText" class="form-label">Discount</label>
                <input type="text" name="discount-code" class="form-control form-control-sm" id="inputDiscountId"
                    name="inputDiscount">
            </div> -->
            <div class="col-6">
                <label for="inputAddress" class="form-label">Address</label>
                <input type="text" class="form-control form-control-sm" id="inputAddressId" name="inputAddress"
                    placeholder="1234 Main St" name="inputAddress" value="<?php echo $userModel->getAddress(); ?>">
            </div>
            <div class="col-2">
                <label for="inputPayment" class="form-label">Payment Method</label>
                <select id="inputPaymentId" class="form-select form-select-sm" name="inputPaymentMethod">
                    <option selected>Cash</option>
                    <!-- <option>Credit Card</option> -->
                </select>
            </div>

            <h4 class="pt-3">Product List</h4>
            <div id="order-confirm-product">
                <table class="table table-striped col-12 align-middle table-borderless text-center table-secondary">
                    <thead>
                        <tr>
                            <th scope="col" colspan="2" class="text-start">Product</th>
                            <th scope="col">Size</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody id="productList">
                        <?php
                        foreach ($cartListFromUser as $cartModel) {
                            $productModel = ProductBUS::getInstance()->getModelById($cartModel->getProductId());
                            $sizeItemsModel = SizeItemsBUS::getInstance()->getModelBySizeIdAndProductId($cartModel->getSizeId(), $cartModel->getProductId());

                            if (!$productModel || !$sizeItemsModel) {
                                error_log('Failed to get product model for ID: ' . $cartModel->getProductId() . ' or size item for ID: ' . $cartModel->getSizeId());
                                echo '<script>';
                                echo 'alert("A product you have in cart is not available or out of stock!")';
                                echo '</script>';
                                CartsBUS::getInstance()->deleteModel($cartModel->getId());
                                CartsBUS::getInstance()->refreshData();
                                echo '<script>';
                                echo 'window.location.href = "?module=cartsection&action=cart"';
                                echo '</script>';
                                break;
                            }

                            echo '<tr>';
                            echo '<td class="col-1"><img src="' . $productModel->getImage() . '" alt="" class="cart-item-img"></td>';
                            echo '<td class="col-4 cart-item-name text-start">' . $productModel->getName() . '</td>';
                            echo '<td class="col-1 cart-item-size">' . $cartModel->getSizeId() . '</td>';
                            echo '<td class="col-2 cart-item-price">' . $productModel->getPrice() . '</td>';
                            echo '<td class="col-1 cart-item-quantity">' . $cartModel->getQuantity() . '</td>';
                            echo '<td class="col-2 cart-item-total">' . $productModel->getPrice() * $cartModel->getQuantity() . '</td>';
                            echo '</tr>';
                        }
                        ?>
            </div>
            </td>
            </tr>
            </tbody>
            </table>
    </div>
    <form action="?module=cartsection&action=cart" method="POST">
        <div class="btn-group ">
            <input type="button" id="btnBack" value="Back"
                onclick="window.location.href='?module=cartsection&action=cart'">
            <p id="totalPrice">Final price: <?php echo $totalPriceOfCart ?></p>
            <input type="submit" name="submitButton" id="order-confirm-submit" value="Submit">
        </div>
    </form>
    <?php
    //button to submit the order :
    if (isPost()) {
        if (isset($_POST['submitButton'])) {
            //Get current time with GMT+7 timezone:
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $currentDate = date('Y-m-d');
            $currentTime = date('Y-m-d H:i:s');

            $orderModel = new OrdersModel(null, null, null, null, null, null, null, null);


            $customerName = $_POST['inputName'];
            $customerPhone = $_POST['inputPhoneNumber'];
            $customerAddress = $_POST['inputAddress'];

            if (empty($customerName) || empty($customerPhone) || empty($customerAddress) || trim($customerName) == "" || trim($customerPhone) == "" || trim($customerAddress) == "") {
                ob_end_clean();
                return jsonResponse('error', 'Please write all the information!');
            }

            if (validation::isValidPhoneNumber($customerPhone) == false) {
                ob_end_clean();
                return jsonResponse('error', 'Invalid phone number!');
            }

            if (validation::isValidName($customerName) == false) {
                ob_end_clean();
                return jsonResponse('error', 'Invalid name!');
            }

            if (validation::isValidAddress($customerAddress) == false) {
                ob_end_clean();
                return jsonResponse('error', 'Invalid address!');
            }

            $orderModel->setCustomerName($customerName);
            $orderModel->setCustomerPhone($customerPhone);
            $orderModel->setCustomerAddress($customerAddress);

            //Check for discount:
            // if (isset($_POST['inputDiscount'])) {
            //     $discountCode = $_POST['inputDiscount'];
            // } else {
            //     $discountCode = null;
            // }
    
            if ($discountCode != null) {
                //Check if the discount code is valid:
                $discountModel = CouponsBUS::getInstance()->getModelByCode($discountCode);
                //Check if the discount code is valid:
                if ($discountModel == null || trim($discountCode) == "") {
                    ob_end_clean();
                    return jsonResponse('error', 'Discount code is invalid!');
                }

                //Check if the discount code has remaining quantity = 0
                if ($discountModel != null && $discountModel->getQuantity() == 0) {
                    ob_end_clean();
                    return jsonResponse('error', 'Discount code has run out!');
                }

                //Check if the discount code is expired:
                if ($discountModel != null && $discountModel->getExpired() < $currentDate) {
                    ob_end_clean();
                    return jsonResponse('error', 'Discount code has expired!');
                }

                //If valid, apply the discount to the total price, getPercent is percentage:
                $totalPriceOfCart = $totalPriceOfCart - ($totalPriceOfCart * $discountModel->getPercent() / 100);

                //Update the coupon remaining quantity:
                if ($discountModel != null) {
                    $discountModel->setQuantity($discountModel->getQuantity() - 1);
                    CouponsBUS::getInstance()->updateModel($discountModel);
                    CouponsBUS::getInstance()->refreshData();
                }

                ob_end_clean();
                return jsonResponse('error', 'Applied discount successfully!');
            }


            // lam tu day
            // 
            //Create the order:
            $orderModel->setUserId($userModel->getId());
            $orderModel->setOrderDate($currentTime);
            $orderModel->setTotalAmount($totalPriceOfCart);
            $orderModel->setStatus(OrderStatusEnums::PENDING);
            OrdersBUS::getInstance()->addModel($orderModel);
            OrdersBUS::getInstance()->refreshData();

            $lastOrderId = OrdersBUS::getInstance()->getLastInsertId();
            //Create order items:
    
            $productMustBeDeleted = [];
            $productMustBeDeletedCartId = [];
            foreach ($cartListFromUser as $cartModel) {
                $orderItemModel = new OrderItemsModel(null, null, null, null, null, null, null);

                $productId = $cartModel->getProductId();
                $sizeId = $cartModel->getSizeId();
                $sizeItems = SizeItemsBUS::getInstance()->getAllModelBySizeIdAndProductId($sizeId, $productId);
                $quantityInventory = 0;
                foreach ($sizeItems as $sizeItem) {
                    $quantityInventory += $sizeItem->getQuantity();
                }

                $productName = ProductBUS::getInstance()->getModelById($productId)->getName();

                if ($cartModel->getQuantity() > $quantityInventory) {
                    $productMustBeDeleted[] = $productName;
                    $productMustBeDeletedCartId[] = $cartModel->getId();
                }


            }

            if (!empty($productMustBeDeleted)) {
                foreach ($productMustBeDeletedCartId as $cartItemId) {
                    CartsBUS::getInstance()->deleteModel($cartItemId);
                }
                ob_end_clean();
                header('Content-Type: application/json');
                echo json_encode(['status' => 'alert', 'productMustBeDeleted' => $productMustBeDeleted]);
                exit;
            } else {
                foreach ($cartListFromUser as $cartModel) {

                    $productId = $cartModel->getProductId();
                    $sizeId = $cartModel->getSizeId();
                    $sizeItems = SizeItemsBUS::getInstance()->getAllModelBySizeIdAndProductId($sizeId, $productId);
                    if (empty($sizeItems)) {
                        ob_end_clean();
                        return jsonResponse('error', 'Size item not found for product ID: ' . $cartModel->getProductId() . ' and size ID: ' . $cartModel->getSizeId());
                    }
                    $cartModelQuantityTemp = $cartModel->getQuantity();
                    foreach ($sizeItems as $sizeItem) {

                        if ($cartModelQuantityTemp > 0) {
                            if ($cartModelQuantityTemp >= $sizeItem->getQuantity()) {
                                $orderItemModel = new OrderItemsModel(null, null, null, null, null, null, null);
                                $orderItemModel->setOrderId($lastOrderId);
                                $orderItemModel->setProductId($productId);
                                $orderItemModel->setSizeId($sizeId);
                                $orderItemModel->setQuantity($sizeItem->getQuantity());
                                $orderItemModel->setPrice(ProductBUS::getInstance()->getModelById($cartModel->getProductId())->getPrice());
                                $orderItemModel->setImportPrice($sizeItem->getImportPrice());
                                OrderItemsBUS::getInstance()->addModel($orderItemModel);
                                OrderItemsBUS::getInstance()->refreshData();


                                SizeItemsBUS::getInstance()->deleteModel($sizeItem);
                                $cartModelQuantityTemp -= $sizeItem->getQuantity();

                            } else {
                                $orderItemModel = new OrderItemsModel(null, null, null, null, null, null, null);
                                $orderItemModel->setOrderId($lastOrderId);
                                $orderItemModel->setProductId($productId);
                                $orderItemModel->setSizeId($sizeId);
                                $orderItemModel->setQuantity($cartModelQuantityTemp);
                                $orderItemModel->setPrice(ProductBUS::getInstance()->getModelById($cartModel->getProductId())->getPrice());
                                $orderItemModel->setImportPrice($sizeItem->getImportPrice());
                                OrderItemsBUS::getInstance()->addModel($orderItemModel);
                                OrderItemsBUS::getInstance()->refreshData();


                                $sizeItem->setQuantity($sizeItem->getQuantity() - $cartModelQuantityTemp);
                                SizeItemsBUS::getInstance()->updateModel($sizeItem);
                                $cartModelQuantityTemp = 0;
                            }
                        }
                    }
                }
            }

            //Create payment:
            $paymentModel = new PaymentsModel(null, null, null, null, null);
            $paymentModel->setOrderId($lastOrderId);

            $paymentMethod = $_POST['inputPaymentMethod'];
            if (!isset($paymentMethod)) {
                ob_end_clean();
                return jsonResponse('error', 'Please choose payment method!');
            }

            if ($paymentMethod == "Cash") {
                //Set default cash payment method:
                $methodId = 1;
                $paymentModel->setMethodId(PaymentMethodsBUS::getInstance()->getModelById($methodId)->getId());
            } else {
                $methodId = 2;
                $paymentModel->setMethodId(PaymentMethodsBUS::getInstance()->getModelById($methodId)->getId());
            }

            $paymentModel->setPaymentDate($currentTime);
            $paymentModel->setTotalPrice($totalPriceOfCart);
            PaymentsBUS::getInstance()->addModel($paymentModel);
            PaymentsBUS::getInstance()->refreshData();

            // When everything is successful, update the quantity in product, check for sizesItem as well:
            foreach ($cartListFromUser as $cartModel) {
                $sizeItemProduct = SizeItemsBUS::getInstance()->getModelBySizeIdAndProductId($cartModel->getSizeId(), $cartModel->getProductId());
                //Log error if sizeItemProduct is null
                if ($sizeItemProduct == null) {

                }
            }
            SizeItemsBUS::getInstance()->refreshData();

            //Delete the cart:
            foreach ($cartListFromUser as $cartModel) {
                CartsBUS::getInstance()->deleteModel($cartModel->getId());
                CartsBUS::getInstance()->refreshData();
            }
            ob_end_clean();
            return jsonResponse('success', 'Order successfully! Thank you for shopping with us!');
        }
    }
    ?>
    </div>
    <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/order_processing.js"></script>
    </div>
</body>

<div id="footer">
    <?php layouts("footer") ?>
</div>