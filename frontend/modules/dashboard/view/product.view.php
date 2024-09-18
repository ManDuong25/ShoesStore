<?php

use backend\bus\CartsBUS;
use backend\enums\StatusEnums;

ob_start();

use backend\bus\CategoriesBUS;
use backend\bus\ChiTietPhieuNhapBUS;
use backend\bus\ChiTietQuyenBUS;
use backend\bus\OrderItemsBUS;
use backend\bus\PhieuNhapBUS;
use backend\bus\SizeItemsBUS;
use backend\models\ProductModel;

$title = 'Product';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission("Q3", "CN1")) {
    die('Access denied');
}

include(__DIR__ . '/../inc/head.php');

use backend\bus\ProductBUS;

$productList = ProductBUS::getInstance()->getAllModels();
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
                    <?php if (checkPermission("Q3", "CN4")) { ?>
                        <div class="btn-toolbar mb-2 mb-0">
                            <button type="button" class="btn btn-sm btn-success align-middle" data-bs-toggle="modal"
                                data-bs-target="#addModal" id="addProduct" class="addBtn">
                                <span data-feather="plus"></span>
                                Add
                            </button>
                        </div>
                    <?php } ?>
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
                            <th class='col-1'>Category</th>
                            <th class='col-4'>Description</th>
                            <th class='col-1 text-center'>Price</th>
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
                                    <div class="col-6">
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
                                    $productGender = $_POST['gender'];
                                    $productDescription = $_POST['description'];
                                    $data = $_POST['image'];

                                    if (ProductBUS::getInstance()->isUsedName($productName)) {
                                        ob_end_clean();
                                        return jsonResponse('error', 'Already have product using this name!');
                                    }

                                    $productModel = new ProductModel(null, $productName, $productCategory, 0, $productDescription, $data, $productGender, strtolower(StatusEnums::INACTIVE));

                                    $result = ProductBUS::getInstance()->addModel($productModel);
                                    if ($result) {
                                        ProductBUS::getInstance()->refreshData();
                                        ob_end_clean();
                                        return jsonResponse('success', 'Product added successfully!');
                                    } else {
                                        ob_end_clean();
                                        return jsonResponse('error', 'Something went wrong!');
                                    }
                                }
                            }
                            ?>
                            </form>
                        </div>
                    </div>
                </div>

                <?php
                //Handle delete product:
                if (isPost()) {
                    $filterAll = filter();
                    if (isset($filterAll['hide']) && isset($filterAll['id'])) {
                        $productId = $filterAll['id'];
                        $updateProductStatus = ProductBUS::getInstance()->getModelById($productId);
                        var_dump($updateProductStatus);
                        $updateProductStatus->setStatus(strtolower(StatusEnums::INACTIVE));
                        $result = ProductBUS::getInstance()->updateModel($updateProductStatus);
                        ProductBUS::getInstance()->refreshData();
                        ob_end_clean();
                        if ($result) {
                            return jsonResponse('success', 'Product hidden successfully!');
                        } else {
                            return jsonResponse('error', 'Something went wrong!');
                        }
                    }
                }


                // Note : Không cho xóa nếu trong inventỏry vẫn còn
                //Handle completely delete product:
                if (isPost()) {
                    $fitlerAll = filter();
                    if (isset($filterAll['delete']) && isset($filterAll['id'])) {
                        $productId = $_POST['id'];
                        $productPreparedToDel = ProductBUS::getInstance()->getModelById($productId);

                        $sizeItemProduct = SizeItemsBUS::getInstance()->getModelByProductId($productId);
                        if (!empty($sizeItemProduct)) {
                            ob_end_clean();
                            return jsonResponse('error', 'Product have in Inventory, can not delete!');
                        }

                        //Check for orders that contain the product:
                        $orders = OrderItemsBUS::getInstance()->getOrderItemsListByProductId($productId);
                        $ctpnListHaveProduct = ChiTietPhieuNhapBUS::getInstance()->getCTPNListByProductId($productId);
                        $cartListHaveProduct = CartsBUS::getInstance()->getCartListByProductId($productId);
                        if (count($orders) > 0 || count($ctpnListHaveProduct) > 0 || count($cartListHaveProduct) > 0) {
                            ob_end_clean();
                            return jsonResponse('error', 'Cannot delete product. Product is in orders/ carts/ ctpn.');
                        } else {
                            foreach (SizeItemsBUS::getInstance()->getModelByProductId($productId) as $sizeItem) {
                                if ($sizeItem->getProductId() == $productId) {
                                    SizeItemsBUS::getInstance()->deleteModel($sizeItem);
                                }
                            }
                            ProductBUS::getInstance()->deleteModel($productPreparedToDel->getId());
                            ProductBUS::getInstance()->refreshData();
                            ob_end_clean();
                            return jsonResponse('success', 'Product deleted successfully!');
                        }
                    }
                }
                ?>
                <?php include(__DIR__ . '/../inc/app/app.php'); ?>
                <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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

                        function loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo) {
                            fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=product.view', {
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
                                    addEventtoHideToBtn();
                                    addEventCompletelyDeleteToBtn();
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
                                    <td class='text-center'>${product.price}</td>
                                    <td class='text-center'>
                                        <div>
                                            <?php if (checkPermission("Q3", "CN2")) { ?>
                                                            <a href='http://localhost/ShoesStore/frontend/index.php?module=dashboard&view=product.update&id=${product.id}' class='btn btn-sm btn-warning'>
                                                                <i class='fas fa-edit'></i>
                                                            </a>
                                            <?php } ?>
                                            <?php if (checkPermission("Q3", "CN3")) { ?>
                                                            <button class='btn btn-sm btn-danger' id='completelyDeleteProduct' name='completelyDeleteProduct'>
                                                                <i class='fas fa-trash-alt'></i>
                                                            </button>
                                            <?php } ?>

                                            <?php if (checkPermission("Q3", "CN2")) { ?>
                                                            <button class='btn btn-sm btn-danger' id='deleteProductButton' name='deleteProductButton'>
                                                                <i class='fas fa-eye-slash'></i>
                                                            </button>
                                            <?php } ?>
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



                        // hide btn
                        function addEventtoHideToBtn() {
                            let hideButtons = document.querySelectorAll('[name="deleteProductButton"]');
                            hideButtons.forEach(function (button) {
                                button.addEventListener('click', function () {
                                    let productElement = this.closest('tr');
                                    let productIdElement = productElement.querySelector('.product_id');
                                    let productId = productIdElement.textContent.trim();
                                    let productStatusElement = productElement.querySelector('.product_status');
                                    let productStatus = productStatusElement.textContent.trim();

                                    if (productStatus === 'inactive') {
                                        alert('Product is already hidden');
                                        return;
                                    }

                                    fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=product.view', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded',
                                        },
                                        body: 'hide=' + true + '&id=' + productId
                                    })
                                        .then(function (response) {
                                            return response.json();
                                        })
                                        .then(function (data) {
                                            if (data.status == "success") {
                                                alert(data.message);
                                            } else if (data.status == "error") {
                                                alert(data.message);
                                            }
                                        });

                                    loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
                                });
                            });
                        }


                        // delete btn
                        function addEventCompletelyDeleteToBtn() {
                            let deleteButtons = document.querySelectorAll('[name="completelyDeleteProduct"]');
                            deleteButtons.forEach(function (button) {
                                button.addEventListener('click', function () {
                                    let confirmed = confirm('Bạn có chắc chắn muốn xoá sản phẩm này hoàn toàn không?');
                                    if (confirmed) {
                                        let productElement = this.closest('tr');
                                        let productIdElement = productElement.querySelector('.product_id');
                                        let productId = productIdElement.textContent.trim();

                                        fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=product.view', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/x-www-form-urlencoded',
                                            },
                                            body: 'delete=' + true + '&id=' + productId
                                        })
                                            .then(function (response) {
                                                return response.json();
                                            })
                                            .then(function (data) {
                                                if (data.status == "success") {
                                                    alert(data.message);
                                                } else if (data.status == "error") {
                                                    alert(data.message);
                                                }
                                            });

                                        loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
                                    }
                                });
                            });
                        }


                        // add btn
                        let productName = document.getElementById("inputProductName");
                        let chosenCategory = document.getElementById("inputProductCate");
                        let chosenGender = document.getElementById("inputGender");
                        let productDescription = document.getElementById("w3review");
                        let productImageUpload = document.getElementById("inputImg");
                        let imageProductReview = document.getElementById("imgPreview");

                        productImageUpload.addEventListener('change', function (event) {
                            let file = event.target.files[0];
                            if (file) {
                                let reader = new FileReader();
                                reader.onload = function (e) {
                                    let base64Image = e.target.result;
                                    imageProductReview.src = base64Image;
                                };
                                reader.readAsDataURL(file);
                            } else {
                                imageProductReview.src = 'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp';
                            }
                        });

                        if (!productImageUpload.files.length) {
                            // Nếu không có file nào được chọn, gán đường dẫn mặc định cho imageProductReview.src
                            imageProductReview.src = 'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp';
                        }

                        let saveBtn = document.getElementById("saveButton");
                        if (saveBtn) {
                            saveBtn.addEventListener('click', function (e) {
                                e.preventDefault();

                                if (!productName.value) {
                                    alert("Please enter product name");
                                    productName.value = "";
                                    return;
                                }

                                if (!chosenCategory.value) {
                                    alert("Please select a category");
                                    return;
                                }

                                if (!productDescription.value) {
                                    alert("Please enter product description");
                                    productDescription.value = "";
                                    return;
                                }

                                let trimmedDescription = productDescription.value.trim();
                                if (trimmedDescription.length < 10) {
                                    alert("Description must be at least 10 characters long");
                                    return;
                                }

                                fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=product.view', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: 'productName=' + productName.value + '&category=' + chosenCategory.value + '&gender=' + chosenGender.value + '&description=' + productDescription.value + '&image=' + encodeURIComponent(imageProductReview.src) + '&saveBtn=' + true
                                })
                                    .then(function (response) {
                                        return response.json();
                                    })
                                    .then(function (data) {
                                        alert(data.message)
                                        if (data.status == 'success') {
                                            productName.value = '';
                                            chosenCategory.value = 1;
                                            chosenGender.value = 0;
                                            productDescription.value = "";
                                            imageProductReview.src = 'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp';
                                            document.getElementById('inputImg').value = "";
                                            document.querySelector('.btn-close').click();
                                        }
                                        loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
                                    });
                            })
                        }


                    });
                </script>
</body>