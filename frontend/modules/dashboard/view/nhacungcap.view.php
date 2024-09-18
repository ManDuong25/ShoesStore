<?php
use backend\bus\NhaCungCapBUS;
use backend\bus\TokenLoginBUS;
use backend\bus\UserBUS;
use backend\services\session;
ob_start();

$title = 'Nhà Cung Cấp';

if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission("Q11", "CN1")) {
    die('Access denied');
}

include(__DIR__ . '/../inc/head.php');
include(__DIR__ . '/../inc/app/app.php');

$userList = UserBUS::getInstance()->getAllModels();

//Get current logged in user
$token = session::getInstance()->getSession('tokenLogin');
$tokenModel = TokenLoginBUS::getInstance()->getModelByToken($token);
$userModel = UserBUS::getInstance()->getModelById($tokenModel->getUserId());
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
                    <?php if (checkPermission("Q8", "CN4")) { ?>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <button type="button" class="btn btn-sm btn-success align-middle" id="addAcountBtn">
                                <span data-feather="plus"></span>
                                Add
                            </button>
                        </div>
                    <?php } ?>
                </div>
                <div class="search-group input-group">
                    <input type="text" id="accountSearch" class="searchInput form-control" name="searchValue"
                        placeholder="Search product name here...">
                    <button type="submit" class="btn btn-sm btn-primary align-middle padx-0 pady-0" name="searchBtnName"
                        id="searchBtnId">
                        <span data-feather="search">Tìm kiếm</span>
                    </button>
                </div>
                <!-- BODY DATABASE -->
                <table class="table align-middle table-borderless table-hover">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th class='col-1'>Mã</th>
                            <th class='col-2'>Tên</th>
                            <th class='col-3'>Địa chỉ</th>
                            <th class='col-2'>SĐT</th>
                            <th class='col-2'>Email</th>
                            <th class='col-1'>Status</th>
                            <th class='col-1'>Manage</th>
                        </tr>
                    </thead>
                    <tbody class="areaUserItems">
                        <br>
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

                                $filterEmail = $filterAll['filterEmail'];

                                if ($filterEmail == "") {
                                    $totalQuantity = NhaCungCapBUS::getInstance()->countAllModels();
                                    $listNCCItems = NhaCungCapBUS::getInstance()->paginationTech($beginGet, $limit);

                                    $listNCCItemsArray = array_map(function ($userItem) {
                                        return $userItem->toArray();
                                    }, $listNCCItems);

                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['listNCCItems' => $listNCCItemsArray, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                    exit;
                                } else {
                                    $listNCCItems = NhaCungCapBUS::getInstance()->filterByEmail($beginGet, $limit, $filterEmail);
                                    $totalQuantity = NhaCungCapBUS::getInstance()->countFilterByEmail($filterEmail);
                                    $totalQuantity = isset($totalQuantity) ? $totalQuantity : 0;

                                    $listNCCItemsArray = array_map(function ($userItem) {
                                        return $userItem->toArray();
                                    }, $listNCCItems);

                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['listNCCItems' => $listNCCItemsArray, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                    exit;
                                }
                            }
                        }
                        ?>
                    </tbody>
        </div>
    </div>
    </table>
    </main>

    <!-- Add modal -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div id="addModalWrapper" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add NCC</h1>
                    <button id="closeAddModalIcon" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3">
                        <div class="col-md-4">
                            <label for="inputNameAdd" class="form-label">Name</label>
                            <input type="text" class="form-control" id="inputNameAdd" name="inputNCCNameName">
                        </div>
                        <div class="col-md-8">
                            <label for="inputAdressAdd" class="form-label">Address</label>
                            <input type="password" id="inputAdressAdd" name="inputAdressName" class="form-control">
                        </div>
                        <div class="col-5">
                            <label for="inputEmailAdd" class="form-label">Email</label>
                            <input type="text" class="form-control" id="inputEmailAdd">
                        </div>
                        <div class="col-4">
                            <label for="inputPhoneAdd" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="inputPhoneAdd">
                        </div>
                        <div class="col-md-3">
                            <label for="inputStatus" class="form-label">Status</label>
                            <select id="inputStatus" class="form-select">
                                <option value="0" selected>0: Inactive</option>
                                <option value="1">1: Active</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button id="closeAddModalBtn" type="button" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Update modal -->
    <!-- <div class="modal fade" id="updateModal">
        <div class="modal-dialog modal-lg">
            <div id="updateModalWrapper" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Account</h1>
                    <button id="closeUpdateModalIcon" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3">
                        <div class="col-md-4">
                            <label for="inputUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="inputUsernameUpdate" name="inputUsernameName">
                        </div>
                        <div class="col-md-3">
                            <label for="inputPassword" class="form-label">Password</label>
                            <input type="password" id="inputPasswordUpdate" name="inputPasswordName" class="form-control">
                        </div>
                        <div class="col-5">
                            <label for="inputEmail" class="form-label">Email</label>
                            <input type="text" class="form-control" id="inputEmailUpdate" name="inputEmailName">
                        </div>
                        <div class="col-5">
                            <label for="inputName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="inputNameUpdate" name="inputNameName">
                        </div>
                        <div class="col-3">
                            <label for="inputPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="inputPhoneUpdate" name="inputPhoneName">
                        </div>
                        <div class="col-md-2">
                            <label for="inputGender" class="form-label">Gender</label>
                            <select id="inputGenderUpdate" class="form-select">
                                <option value="0" selected>Male</option>
                                <option value="1">Female</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="inputRole" class="form-label">Role</label>
                            <select name="" id="inputRoleUpdate" class="form-select">
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="inputAddress" class="form-label">Address</label>
                            <input type="text" name="inputAddressName" id="inputAddressUpdate" class="form-control">
                        </div>
                        <div class="col-6  userImg">
                            <img id="imgPreviewUpdate" src="" alt="Preview Image" a class="img-circle">
                        </div>
                        <div class="col-6">
                            <label for="inputImg">Image</label>
                            <input type="file" class="form-control" name="imgaccount" id="inputImgUpdate" accept="image/*">
                        </div>
                </div>
                <div class="modal-footer">
                    <button id="closeUpdateModalBtn" type="button" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-warning" id="updateBtn">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div> -->

    <?php 
    if(isPost()) {
        $filterAll = filter();
        if (isset($filterAll['saveBtn'])) {
            
        }
    }
    ?>
    <?php include(__DIR__ . '/../inc/app/app.php'); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let thisPage = 1;
            let limit = 12;

            let areaUserItems = document.querySelector('.areaUserItems');
            let areaPagination = document.querySelector('.areaPagination');
            let searchInput = document.getElementById('accountSearch');


            // let addModal = document.getElementById('addModal');
            // let addModalWrapper = document.getElementById('addModalWrapper');
            // let addAcountBtn = document.getElementById('addAcountBtn');
            // let closeAddModalIcon = document.getElementById('closeAddModalIcon');
            // let closeAddModalBtn = document.getElementById('closeAddModalBtn');


            let filterEmail = "";

            function loadData(thisPage, limit, filterEmail) {
                fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=nhacungcap.view', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'thisPage=' + thisPage + '&limit=' + limit + '&filterEmail=' + filterEmail
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        console.log(data);
                        areaUserItems.innerHTML = toHTMLUserItem(data.listNCCItems);
                        areaPagination.innerHTML = toHTMLPagination(data.totalQuantity, data.thisPage, data.limit);
                        totalPage = Math.ceil(data.totalQuantity / data.limit);
                        changePageIndexLogic(totalPage, data.totalQuantity, data.limit);
                    });
            }

            loadData(thisPage, limit, filterEmail);

            function toHTMLUserItem(userItems) {
                let html = '';
                userItems.forEach(function (userItem) {
                    html += `
                    <tr id="${userItem.maNCC}">
                        <td class="col-1">${userItem.maNCC}</td>
                        <td class="user_username col-1">${userItem.ten}</td>
                        <td class="user_name col-2">${userItem.diaChi}</td>
                        <td class="user_email col-2">${userItem.sdt}</td>
                        <td class="user_phone col-1">${userItem.email}</td>
                        <td class="user_status col-1">${userItem.trangThai}</td>
                        <td>
                            <?php if (checkPermission("Q8", "CN2")) { ?>
                                    <button class="editAccountBtn btn btn-sm btn-warning">
                                        <i class="fa-solid fa-edit"></i>    
                                    </button>
                            <?php } ?>
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
                        loadData(thisPage, limit, filterEmail);
                    })

                    document.getElementById('nextPage').addEventListener('click', function () {
                        thisPage++;
                        loadData(thisPage, limit, filterEmail);
                    })

                    pageIndexButtons.forEach(function (button) {
                        button.addEventListener('click', function () {
                            if (this.textContent != "...") {
                                thisPage = parseInt(this.textContent);
                            }
                            loadData(thisPage, limit, filterEmail);
                        });
                    });
                } else if (totalQuantity == 0) {
                    document.getElementById('prevPage').classList.add('hideBtn');
                    document.getElementById('nextPage').classList.add('hideBtn');
                    areaSizeItems.innerHTML = `
                        <h1> Không tồn tại tài khoản nào </h1>
                    `
                }
            }

            searchInput.addEventListener('input', function () {
                filterEmail = searchInput.value;
                loadData(thisPage, limit, filterEmail);
            })

            function showAddModal() {
                addModal.style.display = 'block';
                addModal.setAttribute('role', 'dialog');
                addModal.classList.add('show');
                addModal.style.backgroundColor = 'rgba(0,0,0,0.4)';
            }

            function hideAddModal() {
                addModal.style.display = 'none';
                addModal.removeAttribute('role');
                addModal.classList.remove('show');
                addModal.style.backgroundColor = 'rgba(0,0,0,0.4)';
            }

            addModal.addEventListener('click', function () {
                hideAddModal();
            })

            addModalWrapper.addEventListener('click', function (e) {
                e.stopPropagation();
            })

            closeAddModalIcon.addEventListener('click', function () {
                hideAddModal();
            })

            closeAddModalBtn.addEventListener('click', function () {
                hideAddModal();
            })

            addAcountBtn.addEventListener('click', function () {
                showAddModal();
            })

            let nameAdd = document.getElementById("inputNameAdd");
            let addressAdd = document.getElementById("inputAdressAdd")
            let emailAdd = document.getElementById("inputEmailAdd");
            let phoneAdd = document.getElementById("inputPhoneAdd");
            let statusAdd = document.getElementById("inputStatus");

            let saveBtn = document.getElementById("saveBtn");
            if (saveBtn) {
                saveBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (!nameAdd.value || !addressAdd.value || !emailAdd.value || !phoneAdd.value || !statusAdd.value) {
                        alert("Please fill all fields");
                        return;
                    }

                    fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=nhacungcap.view', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'nameAdd=' + nameAdd.value + '&addressAdd=' + addressAdd.value + '&emailAdd=' + emailAdd.value + '&phoneAdd=' + phoneAdd.value + '&statusAdd=' + statusAdd.value + '&saveBtn=' + true
                    })
                        .then(function (response) {
                            return response.json();
                        })
                        .then(function (data) {
                            alert(data.message);
                            if (data.status == 'success') {
                                nameAdd.value = "";
                                addressAdd.value = "";
                                emailAdd.value = "";
                                phoneAdd.value = "";
                                statusAdd.value = "0";
                                hideAddModal();
                                loadData(thisPage, limit, filterEmail);
                            }
                        });
                })
            }
        });
    </script>
</body>

</html>