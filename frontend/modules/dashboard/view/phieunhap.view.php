<?php
ob_start();

use backend\bus\ChiTietPhieuNhapBUS;
use backend\bus\PaymentMethodsBUS;
use backend\bus\PaymentsBUS;

$title = 'Orders';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission("Q10", "CN1")) {
    die('Access denied');
}

include(__DIR__ . '/../inc/head.php');
include(__DIR__ . '/../inc/app/app.php');

// Namespace
use backend\bus\OrdersBUS;
use backend\bus\OrderItemsBUS;
use backend\bus\PhieuNhapBUS;
use backend\bus\ProductBUS;
use backend\bus\SizeBUS;
use backend\bus\SizeItemsBUS;
use backend\bus\UserBUS;
use backend\enums\OrderStatusEnums;
use backend\models\SizeItemsModel;
use Symfony\Component\VarDumper\VarDumper;

$orderList = OrdersBUS::getInstance()->getAllModels();
$orderListItem = OrderItemsBUS::getInstance();


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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?= $title ?>
                    </h1>
                </div>

                <!-- SEARCH BAR -->
                <div class="container-lg d-flex justify-content-start m-0">
                    <form action="" method="POST" class="m-0 col-lg-6">
                        <div class="input-group">
                            <input type="text" id="filterNameOrder" class="searchInput form-control my-2 rounded-0" placeholder="Search anything here..." style="width: 100%;">
                            <input type="date" id="dateFromOrder" class="form-control rounded-0" style="width: 120px;" placeholder="Start Date">
                            <input type="date" id="dateToOrder" class="form-control rounded-0" style="width: 120px;" placeholder="End Date">
                        </div>
                        <select class="form-control rounded-0 my-2" name="statusSearchName" id="filterStatusOrder">
                            <option value="">---</option>
                            <option value="0">Chưa duyệt</option>
                            <option value="1">Đã duyệt</option>
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
                                <th scope="col" class="col-2">Staff Name</th>
                                <th scope="col" class="col-2">Order Date</th>
                                <th scope="col" class="col-1">Status</th>
                                <th scope="col" class="col-1">Final Price</th>
                                <th scope="col" class="col-1 text-center">Info</th>
                            </tr>
                        </thead>
                        <tbody class="areaPhieuNhapItems">
                            <div><br></div>
                            <nav style="padding-left: 12px;" aria-label='Page navigation example'>
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
                                    $filterDateFrom = $filterAll['dateFrom'];
                                    $filterDateTo = $filterAll['dateTo'];
                                    $filterStatus = $filterAll['filterStatus'];

                                    if (($filterName == "") && ($filterDateFrom == "") && ($filterDateTo == "") && ($filterStatus == "")) {
                                        $totalQuantity = PhieuNhapBUS::getInstance()->countAllModels();
                                        $listPhieuNhapItems = PhieuNhapBUS::getInstance()->paginationTech($beginGet, $limit);

                                        $listPhieuNhapArray = array_map(function ($phieuNhap) {
                                            $userId = $phieuNhap->getUserId();
                                            $userModel = UserBUS::getInstance()->getModelById($userId);
                                            $userName = $userModel->getName();
                                            $phieuNhapArray = $phieuNhap->toArray();
                                            $phieuNhapArray['userName'] = $userName;
                                            return $phieuNhapArray;
                                        }, $listPhieuNhapItems);


                                        ob_end_clean();
                                        header('Content-Type: application/json');
                                        echo json_encode(['listPhieuNhapItems' => $listPhieuNhapArray, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                        exit;
                                    } else {
                                        $listPhieuNhapItems = PhieuNhapBUS::getInstance()->multiFilterModel($beginGet, $limit, $filterName, $filterDateFrom, $filterDateTo, $filterStatus);
                                        $totalQuantity = PhieuNhapBUS::getInstance()->countFilteredModel($filterName, $filterDateFrom, $filterDateTo, $filterStatus);
                                        $totalQuantity = isset($totalQuantity) ? $totalQuantity : 0;


                                        $listPhieuNhapArray = array_map(function ($phieuNhap) {
                                            $userId = $phieuNhap->getUserId();
                                            $userModel = UserBUS::getInstance()->getModelById($userId);
                                            $userName = $userModel->getName();
                                            $phieuNhapArray = $phieuNhap->toArray();
                                            $phieuNhapArray['userName'] = $userName;
                                            return $phieuNhapArray;
                                        }, $listPhieuNhapItems);

                                        ob_end_clean();
                                        header('Content-Type: application/json');
                                        echo json_encode(['listPhieuNhapItems' => $listPhieuNhapArray, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                        exit;
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


                <!-- Chi tiet phieu nhap modal -->
                <div class="modal fade" id="ctpnModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div style="display: flex; justify-content: space-between;" class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Chi Tiết Phiếu Nhập</h1>
                                <button type="button" class="btn-close closeCTPNIcon" aria-label="Close">
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table align-middle table-borderless table-hover">
                                    <thead class="table-light">
                                        <tr class="align-middle">
                                            <th class=''>ID</th>
                                            <th class='col-3'>maPhieuNhap</th>
                                            <th class='col-3 text-center'>Product Name</th>
                                            <th class='col-2 text-center'>Size</th>
                                            <th class='col-2 text-center'>Quantity</th>
                                            <th class='col-2 text-center'>Total price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="areaCTPNItems">
                                        <?php
                                        if (isPost()) {
                                            $filterAll = filter();
                                            if (isset($filterAll['loadDataCTPN']) && isset($filterAll['maPhieuNhap'])) {
                                                $maPhieuNhap = $filterAll['maPhieuNhap'];

                                                $ctpnList = ChiTietPhieuNhapBUS::getInstance()->getModelByIdPhieuNhap($maPhieuNhap);

                                                $ctpnListArray = array_map(function ($ctpn) {
                                                    $productId = $ctpn->getProductId();
                                                    $productModel = ProductBUS::getInstance()->getModelById($productId);
                                                    $ctpnArray = $ctpn->toArray();
                                                    $ctpnArray['productName'] = $productModel->getName();
                                                    $sizeId = $ctpn->getSizeId();
                                                    $sizeModel = SizeBUS::getInstance()->getModelById($sizeId);
                                                    $ctpnArray['sizeName'] = $sizeModel->getName();

                                                    $phieuNhapModel = PhieuNhapBUS::getInstance()->getModelById($ctpn->getMaPhieuNhap());
                                                    $ctpnArray['trangThai'] = $phieuNhapModel->getTrangThai();
                                                    return $ctpnArray;
                                                }, $ctpnList);

                                                ob_end_clean();
                                                header('Content-Type: application/json');
                                                echo json_encode(['ctpnList' => $ctpnListArray]);
                                                exit;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="closeCTPNBtn btn btn-secondary">Cancel</button>
                                <?php if (checkPermission("Q10", "CN2")) { ?>
                                    <button type="button" class="acceptPNBtn btn btn-success">Accept</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                if (isPost()) {
                    $filterAll = filter();
                    if (isset($filterAll['acceptPhieuNhap']) && isset($filterAll['maPhieuNhap'])) {
                        $maPhieuNhap = $filterAll['maPhieuNhap'];
                        $phieuNhapModel = PhieuNhapBUS::getInstance()->getModelById($maPhieuNhap);

                        $chiTietPhieuNhapList = ChiTietPhieuNhapBUS::getInstance()->getModelByIdPhieuNhap($maPhieuNhap);

                        foreach ($chiTietPhieuNhapList as $ctpn) {
                            $itemInInventory = SizeItemsBUS::getInstance()->getModelBySizeIdAndProductId($ctpn->getSizeId(), $ctpn->getProductId());
                            if (isset($itemInInventory)) {
                                $itemInInventory->setQuantity($itemInInventory->getQuantity() + $ctpn->getQuantity());
                                $result = SizeItemsBUS::getInstance()->updateModel($itemInInventory);
                                if (!$result) {
                                    ob_end_clean();
                                    return jsonResponse('error', 'Có lỗi xảy ra khi cập nhật kho!');
                                }
                            } else {
                                $itemInInventory = new SizeItemsModel(null, $ctpn->getProductId(), $ctpn->getSizeId(), $ctpn->getQuantity());
                                $result = SizeItemsBUS::getInstance()->addModel($itemInInventory);
                                if (!$result) {
                                    ob_end_clean();
                                    return jsonResponse('error', 'Có lỗi xảy ra khi thêm mới vào kho!');
                                }
                            }
                        }
                        $phieuNhapModel->setTrangThai(1);
                        $result = PhieuNhapBUS::getInstance()->updateModel($phieuNhapModel);

                        if ($result) {
                            ob_end_clean();
                            return jsonResponse('success', 'Xét duyệt phiếu nhập thành công!');
                        }
                    }
                }
                ?>
            </main>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script>
                let thisPage = 1;
                let limit = 12;
                let areaPhieuNhapItems = document.querySelector('.areaPhieuNhapItems');
                let areaPagination = document.querySelector('.areaPagination');

                let filterNameOrder = document.getElementById('filterNameOrder');
                let dateFromOrder = document.getElementById('dateFromOrder');
                let dateToOrder = document.getElementById('dateToOrder');
                let searchBtn = document.getElementById('searchBtn');
                let filterStatusOrder = document.getElementById('filterStatusOrder');


                let filterName = "";
                let dateFrom = "";
                let dateTo = "";
                let filterStatus = "";


                function loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus) {
                    fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=phieunhap.view', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'thisPage=' + thisPage + '&limit=' + limit + '&filterName=' + filterName + '&dateFrom=' + dateFrom + '&dateTo=' + dateTo + '&filterStatus=' + filterStatus
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            console.log(data);
                            areaPhieuNhapItems.innerHTML = toHTMLPhieuNhapItem(data.listPhieuNhapItems);
                            areaPagination.innerHTML = toHTMLPagination(data.totalQuantity, data.thisPage, data.limit);
                            totalPage = Math.ceil(data.totalQuantity / data.limit);
                            changePageIndexLogic(totalPage, data.totalQuantity, data.limit);
                            addEventToSeeDetailPhieuNhapBtns();
                        });
                }
                loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);

                function toHTMLPhieuNhapItem(listPhieuNhapItems) {
                    let html = '';
                    listPhieuNhapItems.forEach(function(phieuNhapItem) {
                        html += `
                                <tr class="align-middle">
                                    <td class="phieuNhapId" id="${phieuNhapItem.maPhieuNhap}">
                                        ${phieuNhapItem.maPhieuNhap}
                                    </td>
                                    <td>${phieuNhapItem.userId}</td>
                                    <td>${phieuNhapItem.userName}</td>
                                    <td>${phieuNhapItem.phieuNhapDate}</td>
                                    <td>
                                        <span style="${phieuNhapItem.trangThai == 0 ? 'color: grey;' : 'color: green;'}">
                                            ${phieuNhapItem.trangThai == 0 ? 'chưa duyệt' : 'đã duyệt'}
                                        </span>
                                    </td>
                                    <td>${phieuNhapItem.totalAmount}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary view-button seeDetailPhieuNhapBtn">
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

                        document.getElementById('prevPage').addEventListener('click', function() {
                            thisPage--;
                            loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                        })

                        document.getElementById('nextPage').addEventListener('click', function() {
                            thisPage++;
                            loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                        })

                        pageIndexButtons.forEach(function(button) {
                            button.addEventListener('click', function() {
                                if (this.textContent != "...") {
                                    thisPage = parseInt(this.textContent);
                                }
                                loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                            });
                        });
                    } else if (totalQuantity == 0) {
                        document.getElementById('prevPage').classList.add('hideBtn');
                        document.getElementById('nextPage').classList.add('hideBtn');
                        areaPhieuNhapItems.innerHTML = `
                            <td colspan='12' class="text-center"> <p>Không tồn tại đơn hàng nào</p> </td>
                            `
                    }
                }

                let ctpnModal = document.getElementById('ctpnModal');
                let closeCTPNIcon = document.querySelector('.closeCTPNIcon');
                let closeCTPNBtn = document.querySelector('.closeCTPNBtn');
                let areaCTPNItems = document.getElementById('areaCTPNItems');

                function showCartModal() {
                    ctpnModal.style.display = 'block';
                    ctpnModal.setAttribute('role', 'dialog');
                    ctpnModal.classList.add('show');
                    ctpnModal.style.backgroundColor = 'rgba(0,0,0,0.4)';
                }

                function hideCartModal() {
                    ctpnModal.style.display = 'none';
                    ctpnModal.removeAttribute('role');
                    ctpnModal.classList.remove('show');
                    ctpnModal.style.backgroundColor = 'rgba(0,0,0,0.4)';
                }

                closeCTPNIcon.addEventListener('click', function() {
                    hideCartModal();
                })

                closeCTPNBtn.addEventListener('click', function() {
                    hideCartModal();
                })

                function loadDataCTPN(maPhieuNhap) {
                    fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=phieunhap.view', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'loadDataCTPN=' + true + '&maPhieuNhap=' + maPhieuNhap
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            areaCTPNItems.innerHTML = toHTMLCTPNList(data.ctpnList);
                            if (document.querySelector('.acceptPNBtn')) {
                                if (Array.isArray(data.ctpnList) && data.ctpnList.length > 0 && data.ctpnList[0].trangThai == 0) {
                                    document.querySelector('.acceptPNBtn').style.display = 'block';
                                    document.querySelector('.acceptPNBtn').addEventListener('click', acceptButtonClickHandler);
                                } else {
                                    document.querySelector('.acceptPNBtn').style.display = 'none';
                                    document.querySelector('.acceptPNBtn').removeEventListener('click', acceptButtonClickHandler);
                                }
                            }
                        });
                }

                function toHTMLCTPNList(ctpnList) {
                    let html = '';
                    ctpnList.forEach(function(ctpn) {
                        html += `
                            <tr>
                                <td>${ctpn.maCTPN}</td>
                                <td class='maPhieuNhapInList'>${ctpn.maPhieuNhap}</td>
                                <td class='text-center'>${ctpn.productName}</td>
                                <td class='text-center'>${ctpn.sizeName}</td>
                                <td class='text-center'>${ctpn.quantity}</td>
                                <td class='text-center'>${ctpn.price}</td>
                            </tr>
                        `
                    })
                    return html;
                }

                function addEventToSeeDetailPhieuNhapBtns() {
                    seeDetailPNBtns = document.querySelectorAll('.seeDetailPhieuNhapBtn');
                    seeDetailPNBtns.forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            let row = this.closest('tr');
                            let maPhieuNhap = row.querySelector('.phieuNhapId').id;
                            showCartModal();
                            loadDataCTPN(maPhieuNhap);
                        })
                    })
                }

                function acceptButtonClickHandler() {
                    if (confirm('Bạn có chắc chắn muốn duyệt phiếu nhập này không?')) {
                        let maPhieuNhap = document.querySelector('.maPhieuNhapInList').innerHTML;
                        fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=phieunhap.view', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'acceptPhieuNhap=' + true + '&maPhieuNhap=' + maPhieuNhap
                            })
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(data) {
                                if (data.status == 'success') {
                                    alert(data.message);
                                }
                                hideCartModal();
                                loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                            });
                    }
                }


                filterNameOrder.addEventListener('input', function() {
                    filterName = filterNameOrder.value;
                    loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                })

                dateFromOrder.addEventListener('change', function() {
                    dateFrom = dateFromOrder.value;
                    loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                })

                dateToOrder.addEventListener('change', function() {
                    dateTo = dateToOrder.value;
                    loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                })

                filterStatusOrder.addEventListener('change', function() {
                    filterStatus = filterStatusOrder.value;
                    loadData(thisPage, limit, filterName, dateFrom, dateTo, filterStatus);
                })
            </script>
            <?php include(__DIR__ . '/../inc/app/app.php'); ?>
</body>

</html>