<?php

use backend\bus\CartsBUS;
use backend\enums\StatusEnums;

ob_start();

use backend\bus\CategoriesBUS;
use backend\bus\ChiTietPhieuNhapBUS;
use backend\bus\NhaCungCapBUS;
use backend\bus\OrderItemsBUS;
use backend\bus\PhieuNhapBUS;
use backend\bus\SizeItemsBUS;
use backend\models\ProductModel;
use backend\services\session;
use backend\bus\TokenLoginBUS;
use backend\bus\UserBUS;

$title = 'Product';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission("Q10", "CN1") && !checkPermission("Q10", "CN4")) {
    die('Access denied');
}

include(__DIR__ . '/../inc/head.php');

use backend\bus\ProductBUS;
use backend\bus\SizeBUS;
use backend\models\CartsModel;
use backend\models\ChiTietPhieuNhapModel;
use backend\models\PhieuNhapModel;
use backend\models\SizeItemsModel;
use Symfony\Component\VarDumper\VarDumper;

$tokenLoginNow = session::getInstance()->getSession('tokenLogin');
$userIdNow = TokenLoginBUS::getInstance()->getModelByToken($tokenLoginNow)->getUserId();
?>

<body>
    <!-- HEADER -->
    <?php include(__DIR__ . '/../inc/header.php'); ?>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR MENU -->
            <?php include(__DIR__ . '/../inc/sidebar.php'); ?>

            <!-- MAIN -->
            <main class="col-9 ms-sm-auto col-lg-10 px-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?= $title ?>
                    </h1>
                    <div class="btn-toolbar mb-2 mb-0">
                        <button type="button" class="btn btn-sm btn-warning align-middle" id="cartProduct"
                            class="cartBtn">
                            <span data-feather="shopping-cart"></span>
                            Cart
                        </button>
                    </div>
                    <div class="btn-toolbar mb-2 mb-0">
                        <button type="button" class="btn btn-sm btn-success align-middle" data-bs-toggle="modal"
                            data-bs-target="#addModal" id="addProduct" class="addBtn">
                            <span data-feather="plus"></span>
                            Add
                        </button>
                    </div>
                </div>

                <div class="search-group input-group py-2">
                    <input type="text" name="productSearch" id="productSearchBar" class="searchInput form-control"
                        placeholder="Search anything here...">
                    <button type="submit" id="productSearchButton" name="productSearchButtonName"
                        class="btn btn-sm btn-primary align-middle px-3">
                        <span data-feather="search"></span>
                    </button>
                </div>

                <table class="table align-middle table-borderless table-hover">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th></th>
                            <th class='text-center'>ID</th>
                            <th class='col-3'>Product Name</th>
                            <th class='col-2'>Category</th>
                            <th class='col-5'>Description</th>
                            <th class='col-2 text-center'>Action</th>
                            <th class='col-1 text-center'>Status</th>
                        </tr>
                    </thead>

                    <tbody class="areaProduct">
                        <nav aria-label='Page navigation example'>
                            <ul class='pagination justify-content-start areaPagination'>

                            </ul>
                        </nav>

                        <?php
                        if (isPost()) {
                            $filterAll = filter();
                            if (isset($filterAll['thisPage']) && isset($filterAll['limit'])) {
                                $thisPage = $filterAll['thisPage'];
                                $limit = $filterAll['limit'];
                                $beginGet = $limit * ($thisPage - 1);

                                $filterName = $filterAll['filterName'];
                                $filterCategory = $filterAll['filterCategory'];
                                $filterGender = $filterAll['filterGender'];
                                $filterPriceFrom = $filterAll['filterPriceFrom'];
                                $filterPriceTo = $filterAll['filterPriceTo'];

                                if (
                                    ($filterName == "") &&
                                    ($filterCategory == "") &&
                                    ($filterGender == "") &&
                                    ($filterPriceFrom == "") &&
                                    ($filterPriceTo == "")
                                ) {
                                    $totalQuantity = ProductBUS::getInstance()->countAllModels();
                                    $listSP = ProductBUS::getInstance()->paginationTech($beginGet, $limit);
                                    $listSPArray = array_map(function ($product) {
                                        $categoryName = CategoriesBUS::getInstance()->getModelById($product->getCategoryId())->getName();
                                        return $product->toArrayDashBoard($categoryName);
                                    }, $listSP);
                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['listProducts' => $listSPArray, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                    exit;
                                } else {
                                    $listSP = ProductBUS::getInstance()->multiFilter($beginGet, $limit, $filterName, $filterCategory, $filterGender, $filterPriceFrom, $filterPriceTo);
                                    $totalQuantity = ProductBUS::getInstance()->countFilteredProducts($filterName, $filterCategory, $filterGender, $filterPriceFrom, $filterPriceTo);
                                    $totalQuantity = isset($totalQuantity) ? $totalQuantity : 0;
                                    $listSPArray = array_map(function ($product) {
                                        return $product->toArray();
                                    }, $listSP);
                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['listProducts' => $listSPArray, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                    exit;
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Add modal -->
                <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Product</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="row g-3">
                                    <div class="col-7">
                                        <label for="inputProductName" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="inputProductName"
                                            name="productName">
                                    </div>
                                    <div class="col-5">
                                        <label for="inputProductCate" class="form-label">Categories</label>
                                        <select id="inputProductCate" class="form-select" name="category">
                                            <?php $categoriesList = CategoriesBUS::getInstance()->getAllModels();
                                            foreach ($categoriesList as $categories) {
                                                echo "<option value='" . $categories->getId() . "'>" . $categories->getName() . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label for="inputPrice" class="form-label">Price</label>
                                        <input type="text" class="form-control" id="inputPrice" name="price">
                                    </div>
                                    <div class="col-4">
                                        <label for="giaNhap" class="form-label">Import Price</label>
                                        <input type="text" class="form-control" id="giaNhap" name="price">
                                    </div>
                                    <div class="col-4">
                                        <label for="inputGender" class="form-label">Gender</label>
                                        <select id="inputGender" class="form-select" name="gender">
                                            <option value="0" selected>Male</option>
                                            <option value="1">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-7">
                                        <label for="inputDescription" class="form-label">Description</label>
                                        <textarea class="form-control" id="w3review" name="description" row="1"
                                            cols="40"></textarea>
                                    </div>
                                    <div class="col-7">
                                        <label for="inputImg">Image (.JPG, .JPEG, .PNG)</label>
                                        <input type="file" class="form-control" name="image" id="inputImg"
                                            accept=".jpg, .jpeg, .png">
                                    </div>
                                    <div class="col-5 productImg">
                                        <img id="imgPreview" src="" alt="Preview Image">
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="saveButton"
                                    name="saveBtnName">Save</button>
                            </div>
                            <?php
                            if (isPost()) {
                                if (isset($_POST['saveBtn'])) {
                                    error_log('Save button clicked');
                                    $productName = $_POST['productName'];
                                    $productCategory = $_POST['category'];
                                    $productPrice = $_POST['price'];
                                    $productGender = $_POST['gender'];
                                    $productDescription = $_POST['description'];
                                    $data = $_POST['image'];
                                    $giaNhap = $_POST['giaNhap'];
                                    $productModel = new ProductModel(null, $productName, $productCategory, $productPrice, $productDescription, $data, $productGender, strtolower(StatusEnums::INACTIVE), $giaNhap);
                                    ProductBUS::getInstance()->addModel($productModel);
                                    ProductBUS::getInstance()->refreshData();
                                    ob_end_clean();
                                    return jsonResponse('success', 'Product added successfully!');
                                }
                            }
                            ?>
                            </form>
                        </div>
                    </div>
                </div>







                <!-- Choose size modal -->
                <div class="modal fade" id="chooseSizeModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Choose Size</h1>
                                <button type="button" class="btn-close closeChooseSizeIcon" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="row g-3">
                                    <div class="col-4">
                                        <label for="productIdCS" class="form-label">Id</label>
                                        <input type="text" class="form-control" id="productIdCS" readonly>
                                    </div>
                                    <div class="col-7">
                                        <label for="productNameCS" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="productNameCS" readonly>
                                    </div>
                                    <div class="col-5">
                                        <label for="productCateCS" class="form-label">Categories</label>
                                        <input type="text" class="form-control" id="productCateCS" readonly>
                                    </div>
                                    <div class="col-4">
                                        <label for="productPriceCS" class="form-label">Import Price</label>
                                        <input type="text" class="form-control" id="productPriceCS">
                                    </div>
                                    <div class="col-4">
                                        <label for="productGenderCS" class="form-label">Gender</label>
                                        <input type="text" class="form-control" id="productGenderCS" readonly>
                                    </div>
                                    <div class="col-4">
                                        <label for="productSizeCS" class="form-label">Size</label>
                                        <select id="productSizeCS" class="form-select" name="gender">
                                            <?php
                                            $sizeList = SizeBUS::getInstance()->getAllModels();
                                            foreach ($sizeList as $size) {
                                                echo "<option value='" . $size->getId() . "'>" . $size->getName() . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label for="productQuantityCS" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="productQuantityCS">
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="importBtn"
                                    name="importBtnName">Import</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>



                <!-- Cart modal -->
                <div class="modal fade" id="cartModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div style="display: flex; justify-content: space-between;" class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Nhập hàng</h1>
                                <button type="button" class="btn-close closeCartIcon" aria-label="Close">
                                </button>
                            </div>
                            <div class="modal-body">
                                <select name="" id="nhaCungCap">
                                    <?php
                                    $nhaCungCapList = NhaCungCapBUS::getInstance()->getAllActive();
                                    foreach ($nhaCungCapList as $nhaCungCap) {
                                        echo "<option value='" . $nhaCungCap->getMaNCC() . "'>" . $nhaCungCap->getTen() . "</option>";
                                    }
                                    ?>
                                </select>
                                <div><br></div>
                                <table class="table align-middle table-borderless table-hover">
                                    <thead class="table-light">
                                        <tr class="align-middle">
                                            <th></th>
                                            <th class='text-center'>ID</th>
                                            <th class='col-3'>Product Name</th>
                                            <th class='col-2 text-center'>Import price</th>
                                            <th class='col-2 text-center'>Quantity</th>
                                            <th class='col-2 text-center'>Size</th>
                                            <th class='col-1 text-center'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="areaCartItems">
                                        <?php
                                        if (isPost()) {
                                            $filterAll = filter();
                                            if (isset($filterAll['loadDataCart'])) {
                                                $userIdLoggedIn = $userIdNow;
                                                $userCartItems = CartsBUS::getInstance()->getDetailedCartInfoByUserId($userIdLoggedIn);
                                                ob_end_clean();
                                                header('Content-Type: application/json');
                                                echo json_encode(['userCartItems' => $userCartItems]);
                                                exit;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <hr>
                                <h3 class="totalAmountCartAdmin">Tổng tiền: 0</h3>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="closeCartBtn btn btn-secondary">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="nhapHangBtn">Import</button>
                            </div>
                        </div>
                    </div>
                </div>



                <?php
                if (isPost()) {
                    $filterAll = filter();

                    if (isset($filterAll['productId']) && isset($filterAll['productSize']) && isset($filterAll['productQuantity']) && isset($filterAll['importBtn']) && isset($filterAll['productPrice'])) {
                        $productId = $filterAll['productId'];
                        $productSize = $filterAll['productSize'];
                        $productQuantity = $filterAll['productQuantity'];
                        $productPrice = $filterAll['productPrice'];

                        $tokenLogin = session::getInstance()->getSession('tokenLogin');
                        $userId = TokenLoginBUS::getInstance()->getModelByToken($tokenLogin)->getUserId();

                        if (CartsBUS::getInstance()->checkDuplicateProductMD($userId, $productId, $productSize)) {
                            ob_end_clean();
                            return jsonResponse('alert', 'Đã tồn tại sản phẩm trong giỏ hàng, thay đổi giá nhập không?');
                            // $cartModel = CartsBUS::getInstance()->checkDuplicateProductMD($userId, $productId, $productSize);
                            // if ($cartModel) {
                            //     $cartModel->setQuantity($cartModel->getQuantity() + $productQuantity);
                            //     $result = CartsBUS::getInstance()->updateModel($cartModel);
                            // }
                        } else {
                            $cartModel = new CartsModel(null, $userId, $productId, $productQuantity, $productSize, $productPrice);
                            $result = CartsBUS::getInstance()->addModel($cartModel);
                        }
                        if ($result) {
                            ob_end_clean();
                            return jsonResponse('success', 'Thêm vào giỏ hàng thành công!');
                        } else {
                            ob_end_clean();
                            return jsonResponse('error', 'Thêm vào giỏ hàng thất bại!');
                        }
                    }
                }

                if (isPost()) {
                    $filterAll = filter();
                    if (isset($filterAll['productId']) && isset($filterAll['productSize']) && isset($filterAll['productQuantity']) && isset($filterAll['changeImportPrice']) && isset($filterAll['productPrice'])) {
                        $productId = $filterAll['productId'];
                        $productSize = $filterAll['productSize'];
                        $productQuantity = $filterAll['productQuantity'];
                        $productPrice = $filterAll['productPrice'];

                        $tokenLogin = session::getInstance()->getSession('tokenLogin');
                        $userId = TokenLoginBUS::getInstance()->getModelByToken($tokenLogin)->getUserId();
                        $cartModel = CartsBUS::getInstance()->checkDuplicateProductMD($userId, $productId, $productSize);
                        $cartModel->setImportPrice($productPrice);
                        if ($cartModel) {
                            $cartModel->setQuantity($cartModel->getQuantity() + $productQuantity);
                            $result = CartsBUS::getInstance()->updateModel($cartModel);
                            if ($result) {
                                ob_end_clean();
                                return jsonResponse('success', 'Thêm vào giỏ hàng thành công!');
                            } else {
                                ob_end_clean();
                                return jsonResponse('error', 'Thêm vào giỏ hàng thất bại!');
                            }
                        }
                    }
                }

                if (isPost()) {
                    $filterAll = filter();
                    if (isset($filterAll['minusProductQuantity']) && isset($filterAll['cartId'])) {
                        $cartId = $filterAll['cartId'];
                        $cartModel = CartsBUS::getInstance()->getModelById($cartId);
                        if ($cartModel->getQuantity() > 1) {
                            $cartModel->setQuantity($cartModel->getQuantity() - 1);
                            $result = CartsBUS::getInstance()->updateModel($cartModel);
                            if ($result) {
                                ob_end_clean();
                                return jsonResponse('successNoComment', 'Giảm số lượng thành công!');
                            } else {
                                ob_end_clean();
                                return jsonResponse('errorNoComment', 'Giảm số lượng thất bại!');
                            }
                        } else {
                            $result = CartsBUS::getInstance()->deleteModel($cartId);
                            if ($result) {
                                ob_end_clean();
                                return jsonResponse('success', 'Xoá sản phẩm khỏi giỏ hàng thành công!');
                            } else {
                                ob_end_clean();
                                return jsonResponse('error', 'Xoá sản phẩm khỏi giỏ hàng thất bại!');
                            }
                        }
                    }
                }


                if (isPost()) {
                    $filterAll = filter();
                    if (isset($filterAll['plusProductQuantity']) && isset($filterAll['cartId'])) {
                        $cartId = $filterAll['cartId'];
                        $cartModel = CartsBUS::getInstance()->getModelById($cartId);

                        $cartModel->setQuantity($cartModel->getQuantity() + 1);
                        $result = CartsBUS::getInstance()->updateModel($cartModel);
                        if ($result) {
                            ob_end_clean();
                            return jsonResponse('successNoComment', 'Tăng số lượng thành công!');
                        } else {
                            ob_end_clean();
                            return jsonResponse('errorNoComment', 'Tăng số lượng thất bại!');
                        }
                    }
                }

                if (isPost()) {
                    $filterAll = filter();
                    if (isset($filterAll['nhapHang']) && isset($filterAll['nhaCungCap'])) {
                        $maNCC = $filterAll['nhaCungCap'];
                        date_default_timezone_set('Asia/Ho_Chi_Minh');
                        $currentDate = date('Y-m-d');
                        $currentTime = date('Y-m-d H:i:s');
                        $tokenLogin = session::getInstance()->getSession('tokenLogin');
                        $userId = TokenLoginBUS::getInstance()->getModelByToken($tokenLogin)->getUserId();

                        $userCartItems = CartsBUS::getInstance()->getModelByUserId($userId);
                        $totalAmount = 0;

                        foreach ($userCartItems as $userCartItem) {
                            $totalAmount += $userCartItem->getImportPrice() * $userCartItem->getQuantity();
                        }

                        $phieuNhapNew = new PhieuNhapModel(null, $userId, $currentTime, $totalAmount, $maNCC, 0);
                        $result = PhieuNhapBUS::getInstance()->addModel($phieuNhapNew);
                        $maPhieuNhapNew = (int) PhieuNhapBUS::getInstance()->getMaxPhieuNhapId();
                        if (!$result) {
                            ob_end_clean();
                            return jsonResponse('error', 'Có lỗi xảy ra khi tạo mới phiếu nhập!');
                        }
                        foreach ($userCartItems as $userCartItem) {
                            $chiTietPhieuNhapNew = new ChiTietPhieuNhapModel(null, $maPhieuNhapNew, $userCartItem->getProductId(), $userCartItem->getSizeId(), $userCartItem->getQuantity(), $userCartItem->getImportPrice());
                            $result = ChiTietPhieuNhapBUS::getInstance()->addModel($chiTietPhieuNhapNew);

                            if (!$result) {
                                ob_end_clean();
                                return jsonResponse('error', 'Có lỗi xảy ra khi tạo mới CTPN!');
                            }
                        }


                        foreach ($userCartItems as $userCartItem) {
                            $result = CartsBUS::getInstance()->deleteModel($userCartItem->getId());
                            if (!$result) {
                                ob_end_clean();
                                return jsonResponse('error', 'Có lỗi xảy ra khi xoá giỏ hàng!');
                            }
                        }

                        // foreach ($userCartItems as $userCartItem) {
                        //     $itemInInventory = SizeItemsBUS::getInstance()->getModelBySizeIdAndProductId($userCartItem->getSizeId(), $userCartItem->getProductId());
                        //     if (isset($itemInInventory)) {
                        //         $itemInInventory->setQuantity($itemInInventory->getQuantity() + $userCartItem->getQuantity());
                        //         $result = SizeItemsBUS::getInstance()->updateModel($itemInInventory);
                        //         if (!$result) {
                        //             ob_end_clean();
                        //             return jsonResponse('error', 'Có lỗi xảy ra khi cập nhật kho!');
                        //         }
                        //     } else {
                        //         $itemInInventory = new SizeItemsModel(null, $userCartItem->getProductId(), $userCartItem->getSizeId(), $userCartItem->getQuantity());
                        //         $result = SizeItemsBUS::getInstance()->addModel($itemInInventory);
                        //         if (!$result) {
                        //             ob_end_clean();
                        //             return jsonResponse('error', 'Có lỗi xảy ra khi thêm mới vào kho!');
                        //         }
                        //     }
                        // }
                        ob_end_clean();
                        return jsonResponse('success', 'Tạo mới phiếu nhập thành công!');
                    }
                }

                if (isPost()) {
                    $filterAll = filter();
                    if (isset($filterAll['deleteItemInCart']) && isset($filterAll['cartId'])) {
                        $cartId = $filterAll['cartId'];
                        $result = CartsBUS::getInstance()->deleteModel($cartId);
                        if ($result) {
                            ob_end_clean();
                            return jsonResponse('success', 'Xoá sản phẩm khỏi giỏ hàng thành công!');
                        } else {
                            ob_end_clean();
                            return jsonResponse('error', 'Xoá sản phẩm khỏi giỏ hàng thất bại!');
                        }
                    }
                }
                ?>
                <?php include(__DIR__ . '/../inc/app/app.php'); ?>
                <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/add_product.js"></script>


                <script>
                    document.addEventListener('DOMContentLoaded', function () {

                        // Pagination and filter
                        let thisPage = 1;
                        let limit = 12;
                        let searchInput = document.getElementById('productSearchBar');
                        let areaProduct = document.querySelector('.areaProduct');
                        let areaPagination = document.querySelector('.areaPagination');

                        let filterName = "";
                        let filterCategory = "";
                        let filterGender = "";
                        let filterPriceFrom = "";
                        let filterPriceTo = "";

                        let totalAmountCartAdmin = document.querySelector('.totalAmountCartAdmin');
                        function loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo) {
                            fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=nhaphang.view', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'thisPage=' + thisPage + '&limit=' + limit + '&filterName=' + filterName + '&filterCategory=' + filterCategory + '&filterGender=' + filterGender + '&filterPriceFrom=' + filterPriceFrom + '&filterPriceTo=' + filterPriceTo
                            })
                                .then(function (response) {
                                    return response.json();
                                })
                                .then(function (data) {
                                    areaProduct.innerHTML = toHTMLProductList(data.listProducts);
                                    areaPagination.innerHTML = toHTMLPagination(data.totalQuantity, data.thisPage, data.limit);
                                    totalPage = Math.ceil(data.totalQuantity / data.limit);
                                    changePageIndexLogic(totalPage, data.totalQuantity, data.limit);
                                    addEventToAddToCartBtn(data.listProducts);
                                });
                        }

                        loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);

                        function toHTMLProductList(products) {
                            let productListHTML = '';
                            products.forEach(product => {
                                productListHTML += `
                                <tr>
                                    <td><img src='${product.image}' alt='${product.name}' class='rounded float-start product_img'></td>
                                    <td class='text-center product_id'>${product.id}</td>
                                    <td class="product_name">${product.name}</td>
                                    <td class="product_category_name">${product.categoryName}</td>
                                    <td class="product_description">${product.description}</td>
                                    <td class='text-center'>
                                        <div>
                                        <button type="button" class="addToCartBtn btn btn-sm btn-success align-middle" data-bs-toggle="modal" data-bs-target="#chooseSizeModal">
                                                <span data-feather='eye-off'>Add to cart</span>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="product_status" class='text-center'>${product.status}</td>
                                </tr>
                            `;
                            });
                            return productListHTML;
                        }



                        function toHTMLPagination(totalQuantity, thisPage, limit) {
                            buttonPrev = `<li id="prevPage" class="page-item"><a class="page-link">Previous</a></li>`;
                            buttonNext = `<li id="nextPage" class="page-item"><a class="page-link">Next</a></li>`;

                            pageIndexButtons = '';
                            for (i = 1; i <= Math.ceil(totalQuantity / limit); i++) {
                                if (i == thisPage) {
                                    pageIndexButtons += `<li class="pageIndex page-item active"><a class="page-link">${i}</a></li>`;
                                } else {
                                    pageIndexButtons += `<li class="pageIndex page-item"><a class="page-link">${i}</a></li>`;
                                }
                            }
                            return buttonPrev + pageIndexButtons + buttonNext;
                        }


                        function changePageIndexLogic(totalPage, totalQuantity, limit) {
                            if (totalQuantity < limit && totalQuantity > 0) {
                                document.getElementById('prevPage').classList.add('hideBtn');
                                document.getElementById('nextPage').classList.add('hideBtn');
                            } else if (totalQuantity > limit) {
                                let pageIndexButtons = document.querySelectorAll('.pageIndex');

                                if (thisPage == 1) {
                                    document.getElementById('prevPage').classList.add('hideBtn');
                                } else {
                                    document.getElementById('prevPage').classList.remove('hideBtn');
                                }

                                if (thisPage == totalPage) {
                                    document.getElementById('nextPage').classList.add('hideBtn');
                                } else {
                                    document.getElementById('nextPage').classList.remove('hideBtn');
                                }

                                document.getElementById('prevPage').addEventListener('click', function () {
                                    thisPage--;
                                    loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
                                })

                                document.getElementById('nextPage').addEventListener('click', function () {
                                    thisPage++;
                                    loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
                                })

                                pageIndexButtons.forEach(function (button) {
                                    button.addEventListener('click', function () {
                                        thisPage = parseInt(this.textContent);
                                        loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
                                    });
                                });
                            } else if (totalQuantity == 0) {
                                document.getElementById('prevPage').classList.add('hideBtn');
                                document.getElementById('nextPage').classList.add('hideBtn');
                                areaProduct.innerHTML = `
                                <td colspan='12' class="text-center"> <p >Không tồn tại sản phẩm nào</p> </td>
                            `
                            }
                        }

                        searchInput.addEventListener('input', function () {
                            filterName = searchInput.value;
                            thisPage = 1;
                            loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
                        })




                        // Choose size 
                        let productIdCS = document.getElementById('productIdCS');
                        let productNameCS = document.getElementById('productNameCS');
                        let productCateCS = document.getElementById('productCateCS');
                        let productPriceCS = document.getElementById('productPriceCS');
                        let productGenderCS = document.getElementById('productGenderCS');
                        let productSizeCS = document.getElementById('productSizeCS');
                        let productQuantityCS = document.getElementById('productQuantityCS');



                        function addEventToAddToCartBtn(listProducts) {
                            let addToCartBtns = document.querySelectorAll('.addToCartBtn');
                            addToCartBtns.forEach(function (addToCartBtn) {
                                addToCartBtn.addEventListener('click', function () {
                                    let row = this.closest('tr');
                                    let productId = row.querySelector('.product_id').innerText;
                                    let productChosen = null;
                                    for (var i = 0; i < listProducts.length; i++) {
                                        if (listProducts[i].id == productId) {
                                            productChosen = listProducts[i];
                                        }
                                    }

                                    console.log(productChosen)

                                    productIdCS.value = productChosen.id;
                                    productNameCS.value = productChosen.name;
                                    productCateCS.value = productChosen.categoryName;
                                    productGenderCS.value = productChosen.gender == 1 ? 'Male' : 'Female';
                                    productQuantityCS.value = "";
                                })
                            })
                        }

                        let importBtn = document.getElementById('importBtn');
                        importBtn.addEventListener('click', function (e) {
                            e.preventDefault();
                            let productId = productIdCS.value;
                            let productSize = productSizeCS.value;
                            let productQuantity = productQuantityCS.value;
                            let productPrice = productPriceCS.value;

                            if (productQuantity.trim() === '') {
                                console.log(productQuantity);
                                alert('Số lượng không được để trống!');
                                return;
                            }

                            if (isNaN(productQuantity)) {
                                console.log(productQuantity);
                                alert('Số lượng phải là một số!');
                                return;
                            }

                            let quantityInt = parseInt(productQuantity);
                            if (!Number.isInteger(quantityInt)) {
                                console.log(quantityInt);
                                alert('Số lượng phải là số nguyên!');
                                return;
                            }

                            if (isNaN(productPrice)) {
                                console.log(productPrice);
                                alert('Giá nhập phải là một số!');
                                return;
                            }

                            if (productPrice <= 0) {
                                console.log(productPrice);
                                alert('Giá nhập phải lớn hơn 0!');
                                return;
                            }

                            fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=nhaphang.view', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'productId=' + productId + '&productSize=' + productSize + '&productQuantity=' + productQuantity + '&importBtn=' + true + '&productPrice=' + productPrice
                            })
                                .then(function (response) {
                                    return response.json();
                                })
                                .then(function (data) {
                                    if (data.status == 'alert') {
                                        if (confirm(data.message) == true) {
                                            fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=nhaphang.view', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/x-www-form-urlencoded',
                                                },
                                                body: 'productId=' + productId + '&productSize=' + productSize + '&productQuantity=' + productQuantity + '&productPrice=' + productPrice + '&changeImportPrice=' + true
                                            })
                                                .then(function (response) {
                                                    return response.json();
                                                })
                                                .then(function (data) {
                                                    alert(data.message);
                                                    productPriceCS.value = "";
                                                    document.querySelector('.closeChooseSizeIcon').click();
                                                });
                                        } else {
                                            productPriceCS.value = "";
                                            document.querySelector('.closeChooseSizeIcon').click();
                                        }
                                    } else {
                                        alert(data.message);
                                        productPriceCS.value = "";
                                        document.querySelector('.closeChooseSizeIcon').click();
                                    }
                                });
                        });



                        let cartModal = document.getElementById('cartModal');
                        let closeCartIcon = document.querySelector('.closeCartIcon');
                        let closeCartBtn = document.querySelector('.closeCartBtn');

                        function showCartModal() {
                            cartModal.style.display = 'block';
                            cartModal.setAttribute('role', 'dialog');
                            cartModal.classList.add('show');
                            cartModal.style.backgroundColor = 'rgba(0,0,0,0.4)';
                        }

                        function hideCartModal() {
                            cartModal.style.display = 'none';
                            cartModal.removeAttribute('role');
                            cartModal.classList.remove('show');
                            cartModal.style.backgroundColor = 'rgba(0,0,0,0.4)';
                        }

                        closeCartIcon.addEventListener('click', function () {
                            hideCartModal();
                        })

                        closeCartBtn.addEventListener('click', function () {
                            hideCartModal();
                        })

                        let areaCartItems = document.getElementById('areaCartItems');

                        function totalAmountHandler(userCartItems) {
                            let sum = 0;
                            userCartItems.forEach(function (userCartItem) {
                                sum += userCartItem.import_price * userCartItem.cart_quantity;
                            })
                            return sum;
                        }

                        function loadDataCart() {
                            fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=nhaphang.view', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'loadDataCart=' + true
                            })
                                .then(function (response) {
                                    return response.json();
                                })
                                .then(function (data) {
                                    console.log(data.userCartItems)
                                    totalAmountCartAdmin.innerHTML = "Tổng tiền: " + totalAmountHandler(data.userCartItems);
                                    areaCartItems.innerHTML = toHTMLCartItems(data.userCartItems);
                                    addEventToMinusBtn(data.userCartItems);
                                    addEventToPlusBtn(data.userCartItems);
                                    addEventToDeleteItemInCartBtn();
                                });
                        }

                        function toHTMLCartItems(userCartItems) {
                            let html = '';
                            userCartItems.forEach(function (userCartItem) {
                                html += `
                                <tr>
                                    <td><img src='${userCartItem.product_image}' alt='' class='rounded float-start'></td>
                                    <td class="text-center cartId">${userCartItem.cart_id}</td>
                                    <td class="col-3">${userCartItem.product_name}</td>
                                    <td class="col-2 text-center">${userCartItem.import_price}</td>
                                    <td class="col-3 text-center">
                                    <button class="importButton minusProductQuantity">-</button>
                                    <span class="productInCartQuantity">${userCartItem.cart_quantity}</span>
                                    <button class="importButton plusProductQuantity">+</button>
                                    </td>
                                    <td class="col-1 text-center">${userCartItem.size_name}</td>
                                    <td class="col-1 text-center">
                                        <button class="deleteItemInCart btn btn-danger">Delete</button>
                                    </td>
                                </tr>
                                `
                            })
                            return html;
                        }

                        function addEventToMinusBtn(userCartItems) {
                            let minusProductQuantitys = document.querySelectorAll('.minusProductQuantity');
                            minusProductQuantitys.forEach(function (minusProductQuantity) {
                                minusProductQuantity.addEventListener('click', function () {
                                    let row = this.closest('tr');
                                    let cartId = row.querySelector('.cartId').innerHTML;
                                    fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=nhaphang.view', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded',
                                        },
                                        body: 'minusProductQuantity=' + true + '&cartId=' + cartId
                                    })
                                        .then(function (response) {
                                            return response.json();
                                        })
                                        .then(function (data) {
                                            if (data.status == 'success') {
                                                alert(data.message);
                                            }
                                            loadDataCart();
                                        });
                                })
                            })
                        }

                        function addEventToPlusBtn(userCartItems) {
                            let plusProductQuantitys = document.querySelectorAll('.plusProductQuantity');
                            plusProductQuantitys.forEach(function (plusProductQuantity) {
                                plusProductQuantity.addEventListener('click', function () {
                                    let row = this.closest('tr');
                                    let cartId = row.querySelector('.cartId').innerHTML;
                                    fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=nhaphang.view', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded',
                                        },
                                        body: 'plusProductQuantity=' + true + '&cartId=' + cartId
                                    })
                                        .then(function (response) {
                                            return response.json();
                                        })
                                        .then(function (data) {
                                            if (data.status == 'success') {
                                                alert(data.message);
                                            }
                                            loadDataCart();
                                        });
                                })
                            })
                        }

                        function addEventToDeleteItemInCartBtn() {
                            let deleteItemInCartBtns = document.querySelectorAll('.deleteItemInCart');
                            deleteItemInCartBtns.forEach(function (btn) {
                                btn.addEventListener('click', function () {
                                    let row = this.closest('tr');
                                    let cartId = row.querySelector('.cartId').innerHTML;
                                    fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=nhaphang.view', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded',
                                        },
                                        body: 'deleteItemInCart=' + true + '&cartId=' + cartId
                                    })
                                        .then(function (response) {
                                            return response.json();
                                        })
                                        .then(function (data) {
                                            if (data.status == 'success') {
                                                alert(data.message);
                                            }
                                            loadDataCart();
                                        });
                                })
                            })
                        }

                        let nhapHangBtn = document.getElementById('nhapHangBtn');
                        nhapHangBtn.addEventListener('click', function () {
                            if (confirm('Bạn có chắc muốn nhập hàng không?')) {
                                let nhaCungCap = document.getElementById('nhaCungCap');
                                fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=nhaphang.view', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: 'nhapHang=' + true + '&nhaCungCap=' + nhaCungCap.value
                                })
                                    .then(function (response) {
                                        return response.json();
                                    })
                                    .then(function (data) {
                                        alert(data.message);
                                        areaCartItems.innerHTML = '';
                                        closeCartBtn.click();
                                    });
                            }
                        });

                        let cartProduct = document.getElementById('cartProduct');
                        cartProduct.addEventListener('click', function () {
                            showCartModal();
                            loadDataCart();
                        })
                    });
                </script>
</body>