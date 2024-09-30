<?php
use backend\bus\ProductBUS;
use backend\bus\SizeBUS;
use backend\models\SizeItemsModel;
ob_start();

use backend\bus\PaymentMethodsBUS;
use backend\bus\PaymentsBUS;

$title = 'Orders';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission("Q2", "CN1")) {
    die('Access denied');
}

include(__DIR__ . '/../inc/head.php');
include(__DIR__ . '/../inc/app/app.php');

// Namespace
use backend\bus\OrdersBUS;
use backend\bus\OrderItemsBUS;
use backend\bus\SizeItemsBUS;
use backend\enums\OrderStatusEnums;

$orderList = OrdersBUS::getInstance()->getAllModels();
$orderListItem = OrderItemsBUS::getInstance();


function cancelOrderHandler($orderId)
{
    $orderModel = OrdersBUS::getInstance()->getModelById($orderId);
    $orderModel->setStatus(OrderStatusEnums::CANCELED);
    $orderItemsList = OrderItemsBUS::getInstance()->getOrderItemsListByOrderId($orderId);


    foreach ($orderItemsList as $orderItem) {
        $itemInInventory = SizeItemsBUS::getInstance()->getAllModelBySizeIdAndProductId($orderItem->getSizeId(), $orderItem->getProductId());
        $flag = 0;
        foreach ($itemInInventory as $item) {
            if ($item->getImportPrice() == $orderItem->getImportPrice()) {
                $item->setQuantity($item->getQuantity() + $orderItem->getQuantity());
                SizeItemsBUS::getInstance()->updateModel($item);
                $flag = 1;
                break;
            }
        }
        if ($flag == 0) {
            $newItemInInventory = new SizeItemsModel(null, $orderItem->getProductId(), $orderItem->getSizeId(), $orderItem->getQuantity(), $orderItem->getImportPrice());
            SizeItemsBUS::getInstance()->addModel($newItemInInventory);
        }
    }
    OrdersBUS::getInstance()->updateModel($orderModel);

    return true;
}

function shippingOrderHandler($orderId)
{
    $orderModel = OrdersBUS::getInstance()->getModelById($orderId);
    $orderModel->setStatus(OrderStatusEnums::SHIPPING);
    OrdersBUS::getInstance()->updateModel($orderModel);
    return true;
}

function completeOrderHandler($orderId)
{
    $orderModel = OrdersBUS::getInstance()->getModelById($orderId);
    $orderModel->setStatus(OrderStatusEnums::COMPLETED);
    OrdersBUS::getInstance()->updateModel($orderModel);
    return true;
}
?>



<body>
    <!-- HEADER -->
    <?php include(__DIR__ . '/../inc/header.php'); ?>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR MENU -->
            <?php include(__DIR__ . '/../inc/sidebar.php'); ?>

            <!-- MAIN -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?= $title ?>
                    </h1>
                </div>

                <!-- SEARCH BAR -->
                <div class="container-lg d-flex justify-content-start m-0">
                    <form action="" method="POST" class="m-0 col-lg-6">
                        <div class="input-group">
                            <input type="text" id="filterNameOrder" class="searchInput form-control my-2 rounded-0"
                                placeholder="Search anything here..." style="width: 100%;">
                            <input type="date" id="dateFromOrder" class="form-control rounded-0" style="width: 120px;"
                                placeholder="Start Date">
                            <input type="date" id="dateToOrder" class="form-control rounded-0" style="width: 120px;"
                                placeholder="End Date">
                        </div>
                        <select class="form-control rounded-0 my-2" name="statusSearchName" id="filterStatusOrder">
                            <option value="NONE">---</option>
                            <option value="pending">Pending</option>
                            <option value="shipping">Shipping</option>
                            <option value="completed">Completed</option>
                            <option value="canceled">Canceled</option>
                            <option value="accepted">Accepted</option>
                        </select>
                    </form>
                </div>

                <!-- BODY DATABASE -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <!-- HEADER TABLE -->
                        <thead>
                            <tr>
                                <th scope="col" class="col-1">ID Order</th>
                                <th scope="col" class="col-1">User ID</th>
                                <th scope="col" class="col-2">Customer Name</th>
                                <th scope="col" class="col-1">Order Date</th>
                                <th scope="col" class="col-2">Address</th>
                                <th scope="col" class="col-2">Payment method</th>
                                <th scope="col" class="col-1">Import Price</th>
                                <th scope="col" class="col-1">Final Price</th>
                                <th scope="col" class="col-2 text-center">Status</th>
                                <th scope="col" class="col-1 text-center">Info</th>
                            </tr>
                        </thead>
                        <tbody class="areaOrderItems">
                            <nav style="padding-left: 12px;" aria-label='Page navigation example'>
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
                                    $dateFrom = $filterAll['dateFrom'];
                                    $dateTo = $filterAll['dateTo'];
                                    $filterStatus = $filterAll['filterStatus'];
                                    if (($filterName == "") && ($dateFrom == "") && ($dateTo == "") && ($filterStatus == "")) {
                                        $totalQuantity = OrdersBUS::getInstance()->countAllModels();
                                        $listOrderItem = OrdersBUS::getInstance()->paginationTech($beginGet, $limit);
                                        ob_end_clean();
                                        header('Content-Type: application/json');
                                        echo json_encode(['listOrderItems' => $listOrderItem, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                        exit;
                                    } else {
                                        $totalQuantity = OrdersBUS::getInstance()->countFilteredModel($filterName, $dateFrom, $dateTo, $filterStatus);
                                        $listOrderItem = OrdersBUS::getInstance()->multiFilterModel($from, $limit, $filterName, $dateFrom, $dateTo, $filterStatus);
                                        ob_end_clean();
                                        header('Content-Type: application/json');
                                        echo json_encode(['listOrderItems' => $listOrderItem, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                        exit;
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Chi Tiet Hoa Don -->
                <style>
                    #cthdModal .modal-dialog {
                        max-width: 70%;
                    }

                    #cthdModal .modal-content {
                        /* height: 90vh; */
                        overflow: hidden;
                    }
                </style>

                <div class="modal fade" id="cthdModal">
                    <div class="modal-dialog modal-lg"> <!-- Thay đổi lớp nếu cần, ví dụ: modal-xl -->
                        <div class="modal-content">
                            <div style="display: flex; justify-content: space-between;" class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Chi Tiết Phiếu Nhập</h1>
                                <button type="button" class="btn-close closeCTHDIcon" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table align-middle table-borderless table-hover">
                                    <thead class="table-light">
                                        <tr class="align-middle">
                                            <th>ID</th>
                                            <th>Order ID</th>
                                            <th>Name</th>
                                            <th>Size</th>
                                            <th>Quantity</th>
                                            <th>Import Price</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="areaCTHDItems">
                                        <?php
                                        if (isPost()) {
                                            $filterAll = filter();
                                            if (isset($filterAll['loadDataCTHD']) && isset($filterAll['maHoaDon'])) {
                                                $maHoaDon = $filterAll['maHoaDon'];

                                                $cthdList = OrderItemsBUS::getInstance()->getOrderItemsListByOrderId($maHoaDon);

                                                $cthdListArray = array_map(function ($cthd) {
                                                    $productId = $cthd->getProductId();
                                                    $productModel = ProductBUS::getInstance()->getModelById($productId);
                                                    $cthdArray = $cthd->toArray();
                                                    $cthdArray['productName'] = $productModel->getName();
                                                    $sizeId = $cthd->getSizeId();
                                                    $sizeModel = SizeBUS::getInstance()->getModelById($sizeId);
                                                    $cthdArray['sizeName'] = $sizeModel->getName();
                                                    return $cthdArray;
                                                }, $cthdList);

                                                ob_end_clean();
                                                header('Content-Type: application/json');
                                                echo json_encode(['cthdList' => $cthdListArray]);
                                                exit;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <hr>
                            </div>
                            <div class="modal-footer">
                                <button hidden type="button" class="closeCTHDBtn btn btn-secondary">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>


                <?php
                if (isPost()) {
                    $filterAll = filter();
                    if (isset($filterAll['orderId']) && isset($filterAll['status']) && isset($filterAll['previousStatus'])) {
                        $orderId = $filterAll['orderId'];
                        $status = $filterAll['status'];
                        switch ($status) {
                            case 'CANCELED':
                                if (cancelOrderHandler($orderId)) {
                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được huỷ thành công!']);
                                    exit;
                                }
                                break;
                            case 'SHIPPING':
                                if (shippingOrderHandler($orderId)) {
                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được đổi trạng thái thành công!']);
                                    exit;
                                } else {
                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['status' => 'error', 'message' => 'Không đủ số lượng trong kho hàng!']);
                                    exit;
                                }
                                break;
                            case 'COMPLETED':
                                if (completeOrderHandler($orderId)) {
                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được đổi trạng thái thành công!']);
                                    exit;
                                } else {
                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['status' => 'error', 'message' => 'Không đủ số lượng trong kho hàng!']);
                                    exit;
                                }
                                break;
                            // case 'ACCEPTED':
                            //     if (acceptOrderHandler($orderId)) {
                            //         ob_end_clean();
                            //         header('Content-Type: application/json');
                            //         echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được đổi trạng thái thành công!']);
                            //         exit;
                            //     } else {
                            //         ob_end_clean();
                            //         header('Content-Type: application/json');
                            //         echo json_encode(['status' => 'error', 'message' => 'Không đủ số lượng trong kho hàng!']);
                            //         exit;
                            //     }
                            //     break;
                            // case 'PENDING':
                            //     if (pendingOrderHandler($orderId)) {
                            //         ob_end_clean();
                            //         header('Content-Type: application/json');
                            //         echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được huỷ thành công!']);
                            //         exit;
                            //     }
                            //     break;
                            default:
                                break;
                        }
                    }
                }
                ?>
            </main>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script>
                let thisPage = 1;
                let limit = 12;
                let areaOrderItems = document.querySelector('.areaOrderItems');
                let areaPagination = document.querySelector('.areaPagination');

                let filterNameOrder = document.getElementById('filterNameOrder');
                let dateFromOrder = document.getElementById('dateFromOrder');
                let dateToOrder = document.getElementById('dateToOrder');
                let filterStatusOrder = document.getElementById('filterStatusOrder');
                let searchBtn = document.getElementById('searchBtn');


                let filterName = "";
                let dateFrom = "";
                let dateTo = "";
                let filterStatus = "";


                function loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus) {
                    fetch('http://localhost/ShoesStore/frontend/index.php?module=dashboard&view=order.view', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'thisPage=' + thisPage + '&limit=' + limit + '&filterName=' + filterName + '&dateFrom=' + dateFrom + '&dateTo=' + dateTo + '&filterStatus=' + filterStatus
                    })
                        .then(function (response) {
                            return response.json();
                        })
                        .then(function (data) {
                            console.log(data);
                            areaOrderItems.innerHTML = toHTMLOrderItem(data.listOrderItems);
                            areaPagination.innerHTML = toHTMLPagination(data.totalQuantity, data.thisPage, data.limit);
                            totalPage = Math.ceil(data.totalQuantity / data.limit);
                            changePageIndexLogic(totalPage, data.totalQuantity, data.limit);
                            addEventToChangeStatusOrder();
                            addEventToSeeDetailHoaDonBtns();
                        });
                }
                loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);


                function toHTMLOrderItem(listOrderItems) {
                    let html = '';
                    listOrderItems.forEach(function (orderItem) {
                        html += `
                                <tr class="align-middle">
                                    <td class="order hoaDonId" id="${orderItem.id}" data-order-id="${orderItem.id}">
                                        ${orderItem.id}
                                    </td>
                                    <td>${orderItem.userId}</td>
                                    <td>${orderItem.customerName}</td>
                                    <td>${orderItem.orderDate}</td>
                                    <td>${orderItem.customerAddress}</td>
                                    <td>${orderItem.paymentMethod}</td>
                                    <td>${orderItem.totalImportPrice}</td>
                                    <td>${orderItem.totalAmount}</td>
                                    <td class="text-center">
                                        <select ${orderItem.status === 'completed' || orderItem.status === 'canceled' ? 'disabled' : ''} class="form-control orderStatusSelect" name="status" id="orderStatus">
                                            <option disabled value="PENDING" ${orderItem.status === 'pending' ? 'selected' : ''}>Pending</option>
                                            <option value="SHIPPING" ${orderItem.status === 'shipping' ? 'selected' : ''}>Shipping</option>
                                            <option value="COMPLETED" ${orderItem.status === 'completed' ? 'selected' : ''}>Completed</option>
                                            <option value="CANCELED" ${orderItem.status === 'canceled' ? 'selected' : ''}>Canceled</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary view-button seeDetailHoaDonBtn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                `
                    })
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

                        document.getElementById('prevPage').addEventListener('click', function () {
                            thisPage--;
                            loadData(thisPage, limit, filterName);
                        })

                        document.getElementById('nextPage').addEventListener('click', function () {
                            thisPage++;
                            loadData(thisPage, limit, filterName);
                        })

                        pageIndexButtons.forEach(function (button) {
                            button.addEventListener('click', function () {
                                if (this.textContent != "...") {
                                    thisPage = parseInt(this.textContent);
                                }
                                loadData(thisPage, limit, filterName);
                            });
                        });
                    } else if (totalQuantity == 0) {
                        document.getElementById('prevPage').classList.add('hideBtn');
                        document.getElementById('nextPage').classList.add('hideBtn');
                        areaOrderItems.innerHTML = `
                                <td colspan='12' class="text-center"> <p>Không tồn tại đơn hàng nào</p> </td>
                            `
                    }
                }

                filterNameOrder.addEventListener('input', function () {
                    filterName = filterNameOrder.value;
                    loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                })

                dateFromOrder.addEventListener('change', function () {
                    dateFrom = dateFromOrder.value;
                    loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                })

                dateToOrder.addEventListener('change', function () {
                    dateTo = dateToOrder.value;
                    loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                })

                filterStatusOrder.addEventListener('change', function () {
                    filterStatus = filterStatusOrder.value;
                    loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                })

                function disablePreviousOptions(selectElement) {
                    const selectedIndex = selectElement.selectedIndex;
                    for (let i = 0; i < selectElement.options.length; i++) {
                        if (i <= selectedIndex) {
                            selectElement.options[i].disabled = true;
                        }
                    }
                }

                function addEventToChangeStatusOrder() {
                    let orderStatusSelects = document.querySelectorAll('.orderStatusSelect');

                    orderStatusSelects.forEach(function (orderStatus) {
                        disablePreviousOptions(orderStatus);
                        let initialValue = orderStatus.value;

                        orderStatus.addEventListener('change', function (e) {
                            if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái của đơn hàng này không?')) {
                                let row = orderStatus.closest('tr');
                                let orderId = row.querySelector('.order').innerText;
                                fetch('http://localhost/ShoesStore/frontend/index.php?module=dashboard&view=order.view', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: 'orderId=' + orderId + '&status=' + orderStatus.value + '&previousStatus=' + initialValue
                                })
                                    .then(function (response) {
                                        return response.json();
                                    })
                                    .then(function (data) {
                                        alert(data.message);
                                        if (data.status == 'success') initialValue = orderStatus.value;
                                        else orderStatus.value = initialValue;
                                        loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                                    });
                            } else {
                                orderStatus.value = initialValue;
                            }
                        });
                    });
                }


                let cthdModal = document.getElementById('cthdModal');
                let closeCTHDIcon = document.querySelector('.closeCTHDIcon');
                let closeCTHDBtn = document.querySelector('.closeCTHDBtn');
                let areaCTHDItems = document.getElementById('areaCTHDItems');

                function showCartModal() {
                    cthdModal.style.display = 'block';
                    cthdModal.setAttribute('role', 'dialog');
                    cthdModal.classList.add('show');
                    cthdModal.style.backgroundColor = 'rgba(0,0,0,0.4)';
                }

                function hideCartModal() {
                    cthdModal.style.display = 'none';
                    cthdModal.removeAttribute('role');
                    cthdModal.classList.remove('show');
                    cthdModal.style.backgroundColor = 'rgba(0,0,0,0.4)';
                }

                closeCTHDIcon.addEventListener('click', function () {
                    hideCartModal();
                })

                closeCTHDBtn.addEventListener('click', function () {
                    hideCartModal();
                })

                function addEventToSeeDetailHoaDonBtns() {
                    seeDetailHDBtns = document.querySelectorAll('.seeDetailHoaDonBtn');
                    seeDetailHDBtns.forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            let row = this.closest('tr');
                            let maHoaDon = row.querySelector('.hoaDonId').id;
                            showCartModal();
                            loadDataCTHD(maHoaDon);
                        })
                    })
                }

                function loadDataCTHD(maHoaDon) {
                    fetch('http://localhost/ShoesStore/frontend/index.php?module=dashboard&view=order.view', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'loadDataCTHD=' + true + '&maHoaDon=' + maHoaDon
                    })
                        .then(function (response) {
                            return response.json();
                        })
                        .then(function (data) {
                            areaCTHDItems.innerHTML = toHTMLCTHDList(data.cthdList);
                        });
                }

                function toHTMLCTHDList(cthdList) {
                    let html = '';
                    cthdList.forEach(function (cthd) {
                        html += `
                            <tr>
                                <td>${cthd.id}</td>
                                <td class='maPhieuNhapInList'>${cthd.orderId}</td>
                                <td>${cthd.productName}</td>
                                <td>${cthd.sizeName}</td>
                                <td>${cthd.quantity}</td>
                                <td>${cthd.importPrice}</td>
                                <td>${cthd.price}</td>
                            </tr>
                        `
                    })
                    return html;
                }
            </script>
            <?php include(__DIR__ . '/../inc/app/app.php'); ?>
</body>

</html>