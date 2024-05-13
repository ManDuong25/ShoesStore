<?php
ob_start();

use backend\bus\NhomQuyenBUS;
use backend\bus\ChucNangBUS;
use backend\bus\QuyenBUS;

$title = 'Roles Setup';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission("Q9", "CN1")) {
    die('Access denied');
}

include(__DIR__ . '/../inc/head.php');

$chucNangList = ChucNangBUS::getInstance()->getAllModels();
$quyenList = QuyenBUS::getInstance()->getAllModels();

if (isPost()) {
    $filterAll = filter();
    if (isset($filterAll['roleId']) && isset($filterAll['hide'])) {
        $roleId = $filterAll['roleId'];
        $result = NhomQuyenBUS::getInstance()->deleteModel($roleId);
        if ($result) {
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Xoá nhóm quyền thành công!']);
            exit;
        } else {
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Lỗi hệ thống, vui lòng thử lại sau!']);
            exit;
        }
    }
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
            <main class="col-9 ms-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?= $title ?>
                    </h1>
                    <div class="btn-toolbar mb-2 mb-0">
                    </div>
                    <div class="btn-toolbar mb-2 mb-0">
                        <button type="button" class="btn btn-sm btn-success align-middle" id="addNhomQuyenBtn" class="addBtn">
                            <span data-feather="plus"></span>
                            Add
                        </button>
                    </div>
                </div>

                <table class="table align-middle table-borderless table-hover">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th>Role ID</th>
                            <th>Role Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="areaRole">
                        <?php
                        if (isPost()) {
                            $filterAll = filter();
                            if (isset($filterAll['loadData'])) {
                                $roleList = NhomQuyenBUS::getInstance()->getAllModels();
                                $roleListArray = array_map(function ($role) {
                                    return $role->toArray();
                                }, $roleList);
                                ob_end_clean();
                                header('Content-Type: application/json');
                                echo json_encode(['listRoles' => $roleListArray]);
                                exit;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <?php include(__DIR__ . '/../inc/app/app.php'); ?>
    <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let areaRole = document.getElementById('areaRole');
            let addNhomQuyenBtn = document.getElementById('addNhomQuyenBtn');


            let roleId = "";

            function loadData() {
                fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=role.view', {
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
                        areaRole.innerHTML = toHTMLNhomQuyen(data.listRoles);
                        addEventToUpdateBtns();
                        addEventToHideBtns();
                    });
            }

            loadData();


            function toHTMLNhomQuyen(list) {
                let html = "";
                list.forEach(function(item) {
                    html += `
                        <tr>
                            <td class='maNhomQuyenCol col-2'>${item.maNhomQuyen}</td>
                            <td class='col-3'>${item.tenNhomQuyen}</td>
                            <td class='col-3'>${item.trangThai == 1 ? 'active' : 'inActive'}</td>
                            <td class='col-1'>
                                <?php if (checkPermission("Q9", "CN2")) {?>
                                <button class="updateRoleBtn btn btn-sm btn-warning">
                                    <span data-feather="tool">Update</span>
                                </button>
                                <?php } ?>

                                <?php if (checkPermission("Q9", "CN3")) {?>
                                <button class="hideRoleBtn btn btn-sm btn-danger">
                                    <span data-feather="delete">Delete</span>
                                </button>
                                <?php } ?>
                            </td>
                        </tr>
                        `
                });
                return html;
            }

            addNhomQuyenBtn.addEventListener('click', function() {
                roleId = '';
                window.location.href = 'http://localhost/ShoesStore/frontend/?module=dashboard&view=add_role.view&roleId=' + roleId;
            })

            function addEventToUpdateBtns() {
                let updateRoleBtns = document.querySelectorAll('.updateRoleBtn');
                updateRoleBtns.forEach(function(updateRoleBtn) {
                    updateRoleBtn.addEventListener('click', function() {
                        let row = updateRoleBtn.closest('tr');
                        let roleIdCol = row.querySelector('.maNhomQuyenCol');
                        roleId = roleIdCol.innerHTML;
                        window.location.href = 'http://localhost/ShoesStore/frontend/?module=dashboard&view=add_role.view&roleId=' + roleId;
                    });
                })
            }

            function addEventToHideBtns() {
                let hideRoleBtns = document.querySelectorAll('.hideRoleBtn');
                hideRoleBtns.forEach(function(hideRoleBtn) {
                    hideRoleBtn.addEventListener('click', function() {
                        let row = hideRoleBtn.closest('tr');
                        let roleIdCol = row.querySelector('.maNhomQuyenCol');
                        roleId = roleIdCol.innerHTML;
                        fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=role.view', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'roleId=' + roleId + '&hide=' + true
                            })
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(data) {
                                alert(data.message);
                                loadData();
                            });
                    });
                })
            }

        });
    </script>
</body>

</html>