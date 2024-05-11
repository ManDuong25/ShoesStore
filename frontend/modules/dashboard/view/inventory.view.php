<?php
ob_start();

use backend\bus\CategoriesBUS;
use backend\bus\SizeBUS;
use backend\bus\SizeItemsBUS;
use backend\models\SizeItemsModel;

$title = 'Inventory';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission(2)) {
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
                <div class="d-flex justify-content-between flex-wrap flex-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?= $title ?>
                    </h1>

                    <div class="btn-toolbar mb-2 mb-0">
                        <button type="button" class="btn btn-sm btn-success align-middle" data-bs-toggle="modal" data-bs-target="#addModal" id="addSizeItem" class="addBtn">
                            <span data-feather="plus"></span>
                            Add
                        </button>
                    </div>
                </div>

                <div class="search-group input-group">
                    <input type="text" id="productSearch" class="searchInput form-control" name="searchValue" placeholder="Search product name here...">
                    <button type="submit" class="btn btn-sm btn-primary align-middle padx-0 pady-0" name="searchBtnName" id="searchBtnId">
                        <span data-feather="search"></span>
                    </button>
                </div>

                <table class="table align-middle table-borderless table-hover">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th></th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody class="areaSizeItems">
                        <nav aria-label='Page navigation example'>
                            <ul class='pagination justify-content-start areaPagination'>

                            </ul>
                        </nav>
                        <?php
                        if (isPost()) {
                            $filterAll = filter();
                            if (isset($filterAll['thisPage']) && $filterAll['limit']) {
                                $thisPage = $filterAll['thisPage'];
                                $limit = $filterAll['limit'];
                                $beginGet = $limit * ($thisPage - 1);

                                $filterName = $filterAll['filterName'];

                                if (($filterName == "")) {
                                    $totalQuantity = SizeItemsBUS::getInstance()->countAllModels();
                                    $listSizeItem = SizeItemsBUS::getInstance()->paginationTech($beginGet, $limit);

                                    $listSizeItemArray = array_map(function ($sizeItem) {
                                        $product = ProductBUS::getInstance()->getModelById($sizeItem->getProductId());
                                        $productName = $product->getName();
                                        $productImg = $product->getImage();
                                        $categoryId = $product->getCategoryId();
                                        $categoryName = CategoriesBUS::getInstance()->getModelById($categoryId)->getName();
                                        $sizeName = SizeBUS::getInstance()->getModelById($sizeItem->getSizeId())->getName();
                                        $size = preg_replace('/[^0-9]/', '', $sizeName);

                                        return $sizeItem->toArray($productName, $categoryName, $size, $productImg);
                                    }, $listSizeItem);

                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['listSizeItems' => $listSizeItemArray, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                    exit;
                                } else {
                                    $listInventory = SizeItemsBUS::getInstance()->filterByName($beginGet, $limit, $filterName);
                                    $totalQuantity = SizeItemsBUS::getInstance()->countFilterByName($filterName);
                                    $totalQuantity = isset($totalQuantity) ? $totalQuantity : 0;
                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['listSizeItems' => $listInventory, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                    exit;
                                }
                            }
                        }
                        ?>
                    </tbody>

                </table>

                <!-- Add modal -->
                <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Item</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="row g-3">
                                    <div class="col-7">
                                        <label for="inputProductName" class="form-label">Product Name</label>
                                        <select id="inputProductName" class="form-select" name="productName">
                                            <?php
                                            $productsListChecking = ProductBUS::getInstance()->getAllModels();
                                            $sizeListChecking = SizeBUS::getInstance()->getAllModels();
                                            $sizeItemsListChecking = SizeItemsBUS::getInstance()->getAllModels();
                                            $allProductsFullyAssigned = true;
                                            $assignedProducts = [];
                                            foreach ($sizeItemsListChecking as $sizeItem) {
                                                $assignedProducts[$sizeItem->getProductId()][] = $sizeItem->getSizeId();
                                            }

                                            foreach ($productsListChecking as $product) {
                                                $isFullyAssigned = true;
                                                foreach ($sizeListChecking as $size) {
                                                    if (!isset($assignedProducts[$product->getId()]) || !in_array($size->getId(), $assignedProducts[$product->getId()])) {
                                                        $isFullyAssigned = false;
                                                        break;
                                                    }
                                                }
                                                if (!$isFullyAssigned) {
                                                    echo "<option value='" . $product->getId() . "'>" . $product->getName() . "</option>";
                                                    $allProductsFullyAssigned = false;
                                                }
                                            }

                                            if ($allProductsFullyAssigned) {
                                                echo "<option value=''>None</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <label for="inputSize" class="form-label">Sizes</label>
                                        <select id="inputSizeId" class="form-select" name="size">
                                            <?php
                                            $sizeList1 = SizeBUS::getInstance()->getAllModels();
                                            foreach ($sizeList1 as $size1) {
                                                echo "<option value='" . preg_replace(' /[^0-9]/', '', $size1->getId()) .
                                                    "'>" . $size1->getName() . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label for="inputPrice" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="inputQuantity" name="quantity">
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="saveButton" name="saveBtnName">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update model -->
                <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="updateModalWrapper modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Size Quantity</h1>
                                <button type="button" class="btn-close closeUpdateModal" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label for="inputSizeUpdate" class="form-label">Size</label>
                                <input type="text" name="inputSize" id="inputSizeUpdate" class="form-control" value="" readonly>

                                <label for="inputQuantityUpdate" class="form-label">Current
                                    Quantity</label>
                                <input type="number" name="inputQuantity" id="inputQuantityUpdate" class="form-control" value="" readonly>

                                <label for="inputNewQuantityUpdate" class="form-label">New Quantity</label>
                                <input type="number" name="inputNewQuantity" id="inputNewQuantityUpdate" class="form-control">
                                <p class="text-danger"> Tip: Negative number for decreasing quantity, else
                                    increasing quantity from
                                    current quantity</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary closeUpdateModalBtn" data-bs-dismiss="modal">Close</button>
                                <button id="updateSizeItemBtn" type="button" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php
            if (isPost()) {
                //Handle add size item:
                $filterAll = filter();
                if (isset($_POST['saveBtnName'])) {
                    $productId = $_POST['productName'];
                    $sizeId = $_POST['size'];
                    $quantity = $_POST['quantity'];
                    $checkingAssignedSizeItem = SizeItemsBUS::getInstance()->getModelBySizeIdAndProductId($sizeId, $productId);

                    if ($checkingAssignedSizeItem != null) {
                        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>";
                        echo "This size is already assigned to this product!";
                        echo "<button type='button' class='btn-close' data-bs-dismiss='alert' onclick='window.history.back(); aria-label='Close'></button>";
                        echo "</div>";
                        $checkingAssignedSizeItem->setQuantity($checkingAssignedSizeItem->getQuantity() + $quantity);
                        SizeItemsBUS::getInstance()->updateModel($checkingAssignedSizeItem);
                        SizeItemsBUS::getInstance()->refreshData();
                    }

                    $newSizeItemModel = new SizeItemsModel(null, $productId, $sizeId, $quantity);
                    SizeItemsBUS::getInstance()->addModel($newSizeItemModel);
                    SizeItemsBUS::getInstance()->refreshData();
                    ob_end_clean();
                    return jsonResponse('success', 'Add size item successfully!');
                }

                //Handle update quantity:
                if (isset($filterAll['update']) && isset($filterAll['id']) && isset($filterAll['newQuantity'])) {
                    $sizeItemId = $filterAll['id'];
                    $newQuantity = $filterAll['newQuantity'];

                    $sizeItem = SizeItemsBUS::getInstance()->getModelById($sizeItemId);
                    $sizeItem->setQuantity($sizeItem->getQuantity() + $newQuantity);
                    $result = SizeItemsBUS::getInstance()->updateModel($sizeItem);
                    SizeItemsBUS::getInstance()->refreshData();
                    if ($result) {
                        ob_end_clean();
                        return jsonResponse('success', 'Update quantity successfully!');
                    } else {
                        ob_end_clean();
                        return jsonResponse('error', 'Something went wrong!');
                    }
                }

                //Handle delete size item:
                if (isset($filterAll['delete']) && isset($filterAll['id'])) {
                    $sizeItemId = $filterAll['id'];
                    $sizeItem = SizeItemsBUS::getInstance()->getModelById($sizeItemId);
                    $result = SizeItemsBUS::getInstance()->deleteModel($sizeItem);
                    SizeItemsBUS::getInstance()->refreshData();
                    if ($result) {
                        ob_end_clean();
                        return jsonResponse('success', 'Delete size item successfully!');
                    } else {
                        ob_end_clean();
                        return jsonResponse('error', 'Something went wrong!');
                    }
                }
            }
            ?>


            <?php include(__DIR__ . '/../inc/app/app.php'); ?>
            <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/add_sizeitem.js"></script>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let thisPage = 1;
                    let limit = 12;

                    let areaSizeItems = document.querySelector('.areaSizeItems');
                    let areaPagination = document.querySelector('.areaPagination');
                    let searchInput = document.querySelector('#productSearch');

                    let updateModal = document.getElementById('updateModal');
                    let updateModalWrapper = document.querySelector('.updateModalWrapper');
                    let closeUpdateModal = document.querySelector('.closeUpdateModal');
                    let closeUpdateModalBtn = document.querySelector('.closeUpdateModalBtn');
                    let inputSizeUpdate = document.getElementById('inputSizeUpdate');
                    let inputQuantityUpdate = document.getElementById('inputQuantityUpdate');
                    let inputNewQuantityUpdate = document.getElementById('inputNewQuantityUpdate');
                    let updateSizeItemBtn = document.getElementById('updateSizeItemBtn');

                    let filterName = "";
                    let sizeItemId = "";

                    function loadData(thisPage, limit, filterName) {
                        fetch('http://localhost/ShoesStore/frontend/index.php?module=dashboard&view=inventory.view', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'thisPage=' + thisPage + '&limit=' + limit + '&filterName=' + filterName
                            })
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(data) {
                                areaSizeItems.innerHTML = toHTMLSizeItem(data.listSizeItems);
                                areaPagination.innerHTML = toHTMLPagination(data.totalQuantity, data.thisPage, data.limit);
                                totalPage = Math.ceil(data.totalQuantity / data.limit);
                                changePageIndexLogic(totalPage, data.totalQuantity, data.limit);
                                addEventUpdateBtn(data.listSizeItems);
                                addEventDeleteBtn();
                            });
                    }

                    loadData(thisPage, limit, filterName);


                    function toHTMLSizeItem(sizeItems) {
                        let html = '';
                        sizeItems.forEach(function(sizeItem) {
                            html += `
                                <tr id="${sizeItem.id}">
                                    <td><img src='${sizeItem.image}' alt='' class='rounded float-start'></td>
                                    <td>${sizeItem.productName}</td>
                                    <td>${sizeItem.category}</td>
                                    <td>${sizeItem.size}</td>
                                    <td>${sizeItem.quantity}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" name="updateBtn">
                                            <span data-feather="tool">Sửa</span>
                                        </button>
                                        <button class="deleteSizeItemBtn btn btn-sm btn-danger" name="deleteSizeItemBtn">
                                            <span data-feather="trash-2">Xoá</span>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        return html;
                    }

                    function toHTMLPagination(totalQuantity, thisPage, limit) {
                        buttonPrev = `<li id="prevPage" class="page-item"><a class="page-link">Previous</a></li>`;
                        buttonNext = `<li id="nextPage" class="page-item"><a class="page-link">Next</a></li>`;

                        pageIndexButtons = '';
                        totalPage = Math.ceil(totalQuantity / limit);

                        // Nếu tổng số trang lớn hơn 6, chỉ hiển thị một phần của các trang
                        if (totalPage > 6) {
                            // Hiển thị trang đầu
                            if (thisPage == 1) {
                                pageIndexButtons += `<li class="pageIndex page-item active"><a class="page-link">1</a></li>`
                            } else {
                                pageIndexButtons += `<li class="pageIndex page-item"><a class="page-link">1</a></li>`
                            }

                            if (thisPage >= 5) {
                                pageIndexButtons += `<li class="pageIndex page-item"><a class="page-link">...</a></li>`
                            }

                            for (i = Math.max(2, parseInt(thisPage) - 2); i <= Math.min(totalPage - 1, parseInt(thisPage) + 3); i++) {
                                pageIndexButtons += `<li class="pageIndex page-item ${thisPage == i ? 'active' : ''}"><a class="page-link">${i}</a></li>`;
                            }

                            if (thisPage <= totalPage - 5) {
                                pageIndexButtons += `<li class="pageIndex page-item"><a class="page-link">...</a></li>`
                            }
                            if (thisPage == totalPage) {
                                pageIndexButtons += `<li class="pageIndex page-item active"><a class="page-link">${totalPage}</a></li>`
                            } else {
                                pageIndexButtons += `<li class="pageIndex page-item"><a class="page-link">${totalPage}</a></li>`
                            }
                        } else {
                            // Nếu tổng số trang nhỏ hơn hoặc bằng 6, hiển thị tất cả các trang
                            for (i = 1; i <= totalPage; i++) {
                                pageIndexButtons += `<li class="pageIndex page-item ${thisPage == i ? 'active' : ''}"><a class="page-link">${i}</a></li>`;
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

                            document.getElementById('prevPage').addEventListener('click', function() {
                                thisPage--;
                                loadData(thisPage, limit, filterName);
                            })

                            document.getElementById('nextPage').addEventListener('click', function() {
                                thisPage++;
                                loadData(thisPage, limit, filterName);
                            })

                            pageIndexButtons.forEach(function(button) {
                                button.addEventListener('click', function() {
                                    if (this.textContent != "...") {
                                        thisPage = parseInt(this.textContent);
                                    }
                                    loadData(thisPage, limit, filterName);
                                });
                            });
                        } else if (totalQuantity == 0) {
                            document.getElementById('prevPage').classList.add('hideBtn');
                            document.getElementById('nextPage').classList.add('hideBtn');
                            areaSizeItems.innerHTML = `
                            <h1> Không tồn tại sản phẩm nào </h1>
                            `
                        }
                    }

                    searchInput.addEventListener('input', function() {
                        filterName = searchInput.value;
                        loadData(thisPage, limit, filterName);
                    })

                    function showUpdateModal() {
                        updateModal.style.display = 'block';
                        updateModal.setAttribute('role', 'dialog');
                        updateModal.classList.add('show');
                        updateModal.style.backgroundColor = 'rgba(0,0,0,0.4)';
                    }

                    function hideUpdateModal() {
                        updateModal.style.display = 'none';
                        updateModal.removeAttribute('role');
                        updateModal.classList.remove('show');
                        updateModal.style.backgroundColor = 'rgba(0,0,0,0.4)';
                    }

                    updateModal.addEventListener('click', function() {
                        hideUpdateModal();
                    })

                    updateModalWrapper.addEventListener('click', function(e) {
                        e.stopPropagation();
                    })

                    closeUpdateModal.addEventListener('click', function() {
                        hideUpdateModal();
                    })

                    closeUpdateModalBtn.addEventListener('click', function() {
                        hideUpdateModal();
                    })


                    function getElementInListById(list, id) {
                        for (var i = 0; i < list.length; i++) {
                            if (list[i].id == id) {
                                return list[i];
                            }
                        }
                    }


                    function addEventUpdateBtn(list) {
                        let updateButtons = document.querySelectorAll('[name="updateBtn"]');
                        updateButtons.forEach(function(button) {
                            button.addEventListener('click', function() {
                                let sizeItemElement = this.closest('tr');
                                sizeItemId = sizeItemElement.getAttribute('id');

                                let sizeItemInList = getElementInListById(list, sizeItemId);

                                inputSizeUpdate.value = sizeItemInList.size;
                                inputQuantityUpdate.value = sizeItemInList.quantity;
                                showUpdateModal();
                            });
                        });
                    }

                    function addEventDeleteBtn() {
                        let deleteButtons = document.querySelectorAll('.deleteSizeItemBtn');
                        deleteButtons.forEach(function(button) {
                            button.addEventListener('click', function() {
                                let confirmed = confirm('Bạn có chắc chắn muốn xoá không?');
                                if (confirmed) {
                                    let sizeItemElement = this.closest('tr');
                                    sizeItemId = sizeItemElement.getAttribute('id');
                                    fetch('http://localhost/ShoesStore/frontend/index.php?module=dashboard&view=inventory.view', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/x-www-form-urlencoded',
                                            },
                                            body: 'delete=' + true + '&id=' + sizeItemId
                                        })
                                        .then(function(response) {
                                            return response.json();
                                        })
                                        .then(function(data) {
                                            if (data.status == "success") {
                                                alert(data.message);
                                                window.location.href = 'http://localhost/ShoesStore/frontend/index.php?module=dashboard&view=inventory.view';
                                            } else if (data.status == "error") {
                                                alert(data.message);
                                            }
                                        })
                                    loadData(thisPage, limit, filterName);
                                }
                            })
                        })
                    }

                    updateSizeItemBtn.addEventListener('click', function() {
                        let newQuantity = inputNewQuantityUpdate.value;

                        fetch('http://localhost/ShoesStore/frontend/index.php?module=dashboard&view=inventory.view', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'update=' + true + '&id=' + sizeItemId + '&newQuantity=' + newQuantity
                            })
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(data) {
                                if (data.status == "success") {
                                    alert(data.message);
                                } else if (data.status == "error") {
                                    alert(data.message);
                                }
                            });
                        hideUpdateModal();
                        loadData(thisPage, limit, filterName);
                    })
                });
            </script>
</body>