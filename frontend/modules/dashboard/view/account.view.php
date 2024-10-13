<?php
ob_start();

use backend\bus\NhomQuyenBUS;
use backend\bus\TokenLoginBUS;
use backend\bus\UserBUS;
use backend\enums\StatusEnums;
use backend\models\UserModel;
use backend\services\PasswordUtilities;
use backend\services\session;
use backend\services\validation;

$title = 'Accounts';

if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission("Q8", "CN1")) {
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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?= $title ?>
                    </h1>
                    <?php if (checkPermission("Q8", "CN4")) {?>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-success align-middle" id="addAcountBtn">
                            <span data-feather="plus"></span>
                            Add
                        </button>
                    </div>
                    <?php } ?>
                </div>
                <div class="search-group input-group">
                    <input type="text" id="accountSearch" class="searchInput form-control" name="searchValue" placeholder="Search user email here...">
                    <!-- <button type="submit" class="btn btn-sm btn-primary align-middle padx-0 pady-0" name="searchBtnName" id="searchBtnId">
                        <span data-feather="search">Tìm kiếm</span>
                    </button> -->
                </div>
                <!-- BODY DATABASE -->
                <table class="table align-middle table-borderless table-hover text-start">
                    <thead>
                        <tr class="align-middle">
                            <th></th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Manage</th>
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
                                    $totalQuantity = UserBUS::getInstance()->countAllModels();
                                    $listUserItems = UserBUS::getInstance()->paginationTech($beginGet, $limit);

                                    $listUserItemsArray = array_map(function ($userItem) {
                                        return $userItem->toArray();
                                    }, $listUserItems);

                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['listUserItems' => $listUserItemsArray, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                    exit;
                                } else {
                                    $listUserItems = UserBUS::getInstance()->filterByEmail($beginGet, $limit, $filterEmail);
                                    $totalQuantity = UserBUS::getInstance()->countFilterByEmail($filterEmail);
                                    $totalQuantity = isset($totalQuantity) ? $totalQuantity : 0;

                                    $listUserItemsArray = array_map(function ($userItem) {
                                        return $userItem->toArray();
                                    }, $listUserItems);

                                    ob_end_clean();
                                    header('Content-Type: application/json');
                                    echo json_encode(['listUserItems' => $listUserItemsArray, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Account</h1>
                    <button id="closeAddModalIcon" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3">
                        <div class="col-md-4">
                            <label for="inputUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="inputUsernameAdd" name="inputUsernameName">
                        </div>
                        <div class="col-md-3">
                            <label for="inputPasswordAdd" class="form-label">Password</label>
                            <input type="password" id="inputPasswordAdd" name="inputPasswordNameAdd" class="form-control">
                        </div>
                        <div class="col-5">
                            <label for="inputEmail" class="form-label">Email</label>
                            <input type="text" class="form-control" id="inputEmailAdd" name="inputEmailName">
                        </div>
                        <div class="col-5">
                            <label for="inputName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="inputNameAdd" name="inputNameName">
                        </div>
                        <div class="col-3">
                            <label for="inputPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="inputPhoneAdd" name="inputPhoneName">
                        </div>
                        <div class="col-md-2">
                            <label for="inputGender" class="form-label">Gender</label>
                            <select id="inputGenderAdd" class="form-select">
                                <option value="0" selected>Male</option>
                                <option value="1">Female</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="inputRole" class="form-label">Role</label>
                            <select name="" id="inputRoleAdd" class="form-select">
                                <?php
                                $nhomQuyenList = NhomQuyenBUS::getInstance()->getAllModels();
                                foreach ($nhomQuyenList as $nhomQuyen) {
                                    if ($nhomQuyen->getMaNhomQuyen() != 'NQ1')
                                    echo "<option value='" . $nhomQuyen->getMaNhomQuyen() . "'>" . $nhomQuyen->getTenNhomQuyen() . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="inputAddressAdd" class="form-label">Address</label>
                            <input type="text" name="inputAddressNameAdd" id="inputAddressAdd" class="form-control">
                        </div>
                        <div class="col-6  userImg">
                            <img id="imgPreviewAdd" src="" alt="Preview Image" a class="img-circle">
                        </div>
                        <div class="col-6">
                            <label for="inputImg">Image</label>
                            <input type="file" class="form-control" name="imgaccount" id="inputImgAdd" accept="image/*">
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
    <div class="modal fade" id="updateModal">
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
                            <input disabled type="text" class="form-control" id="inputUsernameUpdate" name="inputUsernameName">
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
                                <?php
                                $nhomQuyenList = NhomQuyenBUS::getInstance()->getAllModels();
                                foreach ($nhomQuyenList as $nhomQuyen) {
                                    if ($nhomQuyen->getMaNhomQuyen() != 'NQ1')
                                    echo "<option value='" . $nhomQuyen->getMaNhomQuyen() . "'>" . $nhomQuyen->getTenNhomQuyen() . "</option>";
                                }
                                ?>
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
                    <button hidden id="closeUpdateModalBtn" type="button" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-warning" id="updateBtn">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <?php

    if (isPost()) {
        if (isset($_POST['lockBtn'])) {
            $email = $_POST['userEmail'];
            $user = UserBUS::getInstance()->getModelByEmail($email);
            $user->setStatus(strtolower(StatusEnums::BANNED));
            UserBUS::getInstance()->updateModel($user);
            UserBUS::getInstance()->refreshData();
            ob_end_clean();
            return jsonResponse('success', "Account locked successfully!");
        }

        if (isset($_POST['unlockBtn'])) {
            $email = $_POST['userEmail'];
            $user = UserBUS::getInstance()->getModelByEmail($email);
            $user->setStatus(strtolower(StatusEnums::ACTIVE));
            UserBUS::getInstance()->updateModel($user);
            UserBUS::getInstance()->refreshData();
            ob_end_clean();
            return jsonResponse('success', "Account unlocked successfully!");
        }

        if (isset($_POST['saveBtn'])) {
            $username = $_POST['username'];
            //Check validation:
            if (validation::isValidUsername($username) == false) {
                ob_end_clean();
                return jsonResponse('error', "Username is invalid!");
            }
            $newPassword = $_POST['password'];
            // Check validation:
            if (validation::isValidPassword($newPassword) == false) {
                ob_end_clean();
                return jsonResponse('error', "Password is invalid!");
            }

            $password = PasswordUtilities::hashPassword($newPassword);
            $email = $_POST['email'];
            if (UserBUS::getInstance()->isEmailUsed($email)) {
                ob_end_clean();
                return jsonResponse('error', "Email is already taken!");
            }

            //check validation:
            if (validation::isValidEmail($email) == false) {
                ob_end_clean();
                return jsonResponse('error', "Email is invalid!");
            }

            $name = $_POST['name'];
            //check validation:
            if (validation::isValidName($name) == false) {
                ob_end_clean();
                return jsonResponse('success', "Name is invalid!");
            }

            $phone = $_POST['phone'];
            if (validation::isValidPhoneNumber($phone) == false) {
                ob_end_clean();
                return jsonResponse('error', "Phone number is invalid!");
            }

            if (UserBUS::getInstance()->isPhoneUsed($phone)) {
                ob_end_clean();
                return jsonResponse('error', "Phone number is already taken!");
            }

            $gender = $_POST['gender'];
            $role = $_POST['role'];
            $address = $_POST['address'];
            if (validation::isValidAddress($address) == false) {
                ob_end_clean();
                return jsonResponse('error', "Address is invalid!");
            }
            $image = $_POST['image'];

            $newUser = new UserModel(null, $username, $password, $email, $name, $phone, $gender, $image, $role, StatusEnums::INACTIVE, $address, null, null, null, null);
            UserBUS::getInstance()->addModel($newUser);
            UserBUS::getInstance()->refreshData();
            ob_end_clean();
            return jsonResponse('success', "Account added successfully!");
        }


        if (isset($_POST['updateBtn'])) {
            $userId = $_POST['userId'];

            $username = $_POST['username'];
            //Check validation:
            if (validation::isValidUsername($username) == false) {
                ob_end_clean();
                return jsonResponse('error', "Username is invalid!");
            }
            $newPassword = $_POST['password'];

            if ($newPassword == "") {
                $thisUser = UserBUS::getInstance()->getModelById($userId);
                $password = $thisUser->getPassword();
            } else {
                if (validation::isValidPassword($newPassword) == false) {
                    ob_end_clean();
                    return jsonResponse('error', "Password is invalid!");
                } else {
                    $password = PasswordUtilities::hashPassword($newPassword);
                }
            }
            
            $email = $_POST['email'];
            if (UserBUS::getInstance()->isEmailTaken($email, $userId)) {
                ob_end_clean();
                return jsonResponse('error', "Email is already taken!");
            }

            //check validation:
            if (validation::isValidEmail($email) == false) {
                ob_end_clean();
                return jsonResponse('error', "Email is invalid!");
            }

            $name = $_POST['name'];
            //check validation:
            if (validation::isValidName($name) == false) {
                ob_end_clean();
                return jsonResponse('success', "Name is invalid!");
            }

            $phone = $_POST['phone'];
            if (validation::isValidPhoneNumber($phone) == false) {
                ob_end_clean();
                return jsonResponse('error', "Phone number is invalid!");
            }

            if (UserBUS::getInstance()->isPhoneTaken($phone, $userId)) {
                ob_end_clean();
                return jsonResponse('error', "Phone number is already taken!");
            }

            $gender = $_POST['gender'];
            $role = $_POST['role'];
            $address = $_POST['address'];
            if (validation::isValidAddress($address) == false) {
                ob_end_clean();
                return jsonResponse('error', "Address is invalid!");
            }
            $image = $_POST['image'];

            $newUser = new UserModel($userId, $username, $password, $email, $name, $phone, $gender, $image, $role, StatusEnums::INACTIVE, $address, null, null, null, null);
            UserBUS::getInstance()->updateModel($newUser);
            UserBUS::getInstance()->refreshData();
            ob_end_clean();
            return jsonResponse('success', "Account updated successfully!");
        }
    }
    ?>
    <?php include(__DIR__ . '/../inc/app/app.php'); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <!-- <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/account_status.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/dashboard/add_account.js"></script> -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let thisPage = 1;
            let limit = 12;

            let areaUserItems = document.querySelector('.areaUserItems');
            let areaPagination = document.querySelector('.areaPagination');
            let searchInput = document.getElementById('accountSearch');


            let addModal = document.getElementById('addModal');
            let addModalWrapper = document.getElementById('addModalWrapper');
            let addAcountBtn = document.getElementById('addAcountBtn');
            let closeAddModalIcon = document.getElementById('closeAddModalIcon');
            let closeAddModalBtn = document.getElementById('closeAddModalBtn');


            let filterEmail = "";

            function loadData(thisPage, limit, filterEmail) {
                fetch('http://localhost/ShoesStore/frontend/index.php?module=dashboard&view=account.view', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'thisPage=' + thisPage + '&limit=' + limit + '&filterEmail=' + filterEmail
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        areaUserItems.innerHTML = toHTMLUserItem(data.listUserItems);
                        areaPagination.innerHTML = toHTMLPagination(data.totalQuantity, data.thisPage, data.limit);
                        totalPage = Math.ceil(data.totalQuantity / data.limit);
                        changePageIndexLogic(totalPage, data.totalQuantity, data.limit);
                        addEventToLockBtn();
                        addEventToUnlockBtn();
                        addEventToEditBtn(data.listUserItems);
                        console.log(data);
                    });
            }

            loadData(thisPage, limit, filterEmail);

            function toHTMLUserItem(userItems) {
                let html = '';
                userItems.forEach(function(userItem) {
                    html += `
                    <tr id="${userItem.id}">
                        <td class="col-1"><img class="user_image" src="${userItem.image}" alt="ATR"></td>
                        <td class="user_username col-1">${userItem.username}</td>
                        <td class="user_name col-2">${userItem.name}</td>
                        <td class="user_email col-2">${userItem.email}</td>
                        <td class="user_phone col-1">${userItem.phone}</td>
                        <td class="user_address col-2">${userItem.address}</td>
                        <td class="user_maNhomQuyen col-1">${userItem.maNhomQuyen}</td>
                        <td class="user_status col-1">${userItem.status}</td>
                        <td>
                            <?php if (checkPermission("Q8", "CN2")) {?>
                            <button class="editAccountBtn btn btn-sm btn-warning">
                                <i class="fa-solid fa-edit"></i>    
                            </button>
                            <?php } ?>
                            <?php if (checkPermission("Q8", "CN2")) {?>
                            <button class="lockAccountBtn btn btn-sm btn-danger">
                                <i class="fa-solid fa-lock"></i>
                            </button>
                            <?php } ?>
                            <?php if (checkPermission("Q8", "CN2")) {?>
                            <button class="unlockAccountBtn btn btn-sm btn-success">
                                <i class="fa-solid fa-unlock"></i>
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

                    document.getElementById('prevPage').addEventListener('click', function() {
                        thisPage--;
                        loadData(thisPage, limit, filterEmail);
                    })

                    document.getElementById('nextPage').addEventListener('click', function() {
                        thisPage++;
                        loadData(thisPage, limit, filterEmail);
                    })

                    pageIndexButtons.forEach(function(button) {
                        button.addEventListener('click', function() {
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

            searchInput.addEventListener('input', function() {
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

            addModal.addEventListener('click', function() {
                hideAddModal();
            })

            addModalWrapper.addEventListener('click', function(e) {
                e.stopPropagation();
            })

            closeAddModalIcon.addEventListener('click', function() {
                hideAddModal();
            })

            closeAddModalBtn.addEventListener('click', function() {
                hideAddModal();
            })

            addAcountBtn.addEventListener('click', function() {
                showAddModal();
            })



            // add account
            let username = document.getElementById("inputUsernameAdd");
            let password = document.getElementById("inputPasswordAdd");
            let email = document.getElementById("inputEmailAdd");
            let name = document.getElementById("inputNameAdd");
            let phone = document.getElementById("inputPhoneAdd");
            let gender = document.getElementById("inputGenderAdd");
            let role = document.getElementById("inputRoleAdd");
            let address = document.getElementById("inputAddressAdd");
            let imageUpload = document.getElementById("inputImgAdd");
            let imagePreview = document.getElementById("imgPreviewAdd");


            imageUpload.addEventListener('change', (event) => {
                let file = event.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let base64Image = e.target.result
                        console.log(e.target)
                        imagePreview.src = base64Image;
                    }
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.src = 'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp';
                }
            });

            if (!imageUpload.files.length) {
                // Nếu không có file nào được chọn, gán đường dẫn mặc định cho imagePreview.src
                imagePreview.src = 'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp';
            }

            let saveBtn = document.getElementById("saveBtn");
            if (saveBtn) {
                saveBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!username.value || !password.value || !email.value || !name.value || !phone.value || !address.value) {
                        alert("Please fill all fields");
                        return;
                    }

                    fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=account.view', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'username=' + username.value + '&password=' + password.value + '&email=' + email.value + '&name=' + name.value + '&phone=' + phone.value + '&gender=' + gender.value + '&role=' + role.value + '&address=' + address.value + '&image=' + encodeURIComponent(imagePreview.src) + '&saveBtn=' + true
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            alert(data.message);
                            if (data.status == 'success') {
                                username.value = "";
                                password.value = "";
                                email.value = "";
                                name.value = "";
                                phone.value = "";
                                gender.value = "0";
                                role.value = "NQ1";
                                address.value = "";
                                imagePreview.src = 'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp';
                                hideAddModal();
                                loadData(thisPage, limit, filterEmail);
                            }
                        });
                })
            }



            function addEventToLockBtn() {
                let lockBtns = document.querySelectorAll('.lockAccountBtn');
                lockBtns.forEach(function(lockBtn) {
                    lockBtn.addEventListener('click', function() {
                        let row = this.closest('tr');
                        let userEmail = row.querySelector('.user_email');

                        if (confirm("Are you sure you want to lock this account?")) {
                            fetch('http://localhost/ShoesStore/frontend/index.php?module=dashboard&view=account.view', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: 'lockBtn=' + true + '&userEmail=' + userEmail.innerText
                                })
                                .then(function(response) {
                                    return response.json();
                                })
                                .then(function(data) {
                                    alert(data.message);
                                    loadData(thisPage, limit, filterEmail);
                                });
                        }
                    });
                })
            }

            function addEventToUnlockBtn() {
                let unlockBtns = document.querySelectorAll('.unlockAccountBtn');
                unlockBtns.forEach(function(unlockBtn) {
                    unlockBtn.addEventListener('click', function() {
                        let row = this.closest('tr');
                        let userEmail = row.querySelector('.user_email');

                        if (confirm("Are you sure you want to unlock this account?")) {
                            fetch('http://localhost/ShoesStore/frontend/index.php?module=dashboard&view=account.view', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: 'unlockBtn=' + true + '&userEmail=' + userEmail.innerText
                                })
                                .then(function(response) {
                                    return response.json();
                                })
                                .then(function(data) {
                                    alert(data.message);
                                    loadData(thisPage, limit, filterEmail);
                                });
                        }
                    });
                })
            }

            let updateModal = document.getElementById('updateModal');
            let updateModalWrapper = document.getElementById('updateModalWrapper');
            let closeUpdateModalIcon = document.getElementById('closeUpdateModalIcon');
            let closeUpdateModalBtn = document.getElementById('closeUpdateModalBtn');

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

            closeUpdateModalIcon.addEventListener('click', function() {
                hideUpdateModal();
            })

            closeUpdateModalBtn.addEventListener('click', function() {
                hideUpdateModal();
            })


            // update account
            let userIdUpdate = -1;
            let usernameUpdate = document.getElementById("inputUsernameUpdate");
            let passwordUpdate = document.getElementById("inputPasswordUpdate");
            let emailUpdate = document.getElementById("inputEmailUpdate");
            let nameUpdate = document.getElementById("inputNameUpdate");
            let phoneUpdate = document.getElementById("inputPhoneUpdate");
            let genderUpdate = document.getElementById("inputGenderUpdate");
            let roleUpdate = document.getElementById("inputRoleUpdate");
            let addressUpdate = document.getElementById("inputAddressUpdate");
            let imageUploadUpdate = document.getElementById("inputImgUpdate");
            let imagePreviewUpdate = document.getElementById("imgPreviewUpdate");

            imageUploadUpdate.addEventListener('change', (event) => {
                let file = event.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let base64Image = e.target.result
                        console.log(e.target)
                        imagePreviewUpdate.src = base64Image;
                    }
                    reader.readAsDataURL(file);
                } else {
                    imagePreviewUpdate.src = 'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp';
                }
            });

            if (!imageUploadUpdate.files.length) {
                imagePreviewUpdate.src = 'http://localhost/ShoesStore/frontend/templates/images/avatar_default.webp';
            }


            function addEventToEditBtn(listUser) {
                let editBtns = document.querySelectorAll('.editAccountBtn');
                editBtns.forEach(function(editBtn) {
                    editBtn.addEventListener('click', function() {
                        let row = this.closest('tr');
                        let userId = row.id;
                        let user;
                        listUser.forEach(function(userTemp) {
                            if (userTemp.id == userId) {
                                user = userTemp;
                            }
                        });
                        userIdUpdate = user.id;
                        usernameUpdate.value = user.username;
                        emailUpdate.value = user.email;
                        nameUpdate.value = user.name;
                        phoneUpdate.value = user.phone;
                        genderUpdate.value = user.gender;
                        roleUpdate.value = user.maNhomQuyen;
                        addressUpdate.value = user.address;
                        imagePreviewUpdate.src = user.image;
                        showUpdateModal();
                    })
                })
            }

            let updateBtn = document.getElementById('updateBtn');
            updateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!usernameUpdate.value || !emailUpdate.value || !nameUpdate.value || !phoneUpdate.value || !addressUpdate.value) {
                    alert("Please fill all fields");
                    return;
                }
                if (passwordUpdate.value < 6) {
                    alert("Password minimum length is 6");
                    return;
                }

                if (passwordUpdate.value > 30) {
                    alert("Password maximum length is 30");
                    return;
                }

                fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=account.view', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'username=' + usernameUpdate.value + '&password=' + passwordUpdate.value + '&email=' + emailUpdate.value + '&name=' + nameUpdate.value + '&phone=' + phoneUpdate.value + '&gender=' + genderUpdate.value + '&role=' + roleUpdate.value + '&address=' + addressUpdate.value + '&image=' + encodeURIComponent(imagePreviewUpdate.src) + '&updateBtn=' + true + '&userId=' + userIdUpdate
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        alert(data.message);
                        if (data.status == 'success') {
                            hideUpdateModal();
                            loadData(thisPage, limit, filterEmail);
                        }
                    });
            })
        });
    </script>
</body>

</html>