<?php
ob_start();

use backend\bus\ChucNangBUS;
use backend\bus\QuyenBUS;
use backend\bus\ChiTietQuyenBUS;
use backend\bus\NhomQuyenBUS;
use backend\models\ChiTietQuyenModel;
use backend\models\NhomQuyenModel;


$title = 'Permissions';
if (!defined('_CODE')) {
    die('Access denied');
}

if (!isAllowToDashBoard()) {
    die('Access denied');
}

if (!checkPermission("Q9", "CN1") || !checkPermission("Q9", "CN4")){
    die('Access denied');
}

include(__DIR__ . '/../inc/head.php');

$chucNangList = ChucNangBUS::getInstance()->getAllModels();
$quyenList = QuyenBUS::getInstance()->getAllModels();

if (isPost()) {
    if (isset($_POST['data']) && isset($_POST['tenNhomQuyen']) && isset($_POST['createNew'])) {
        $jsonData = $_POST['data'];
        $tenNhomQuyen = $_POST['tenNhomQuyen'];
        $permissionsData = json_decode($jsonData, true);

        $sttMaNhomQuyenTru1 = ChiTietQuyenBUS::getInstance()->getMaxNumberFromMaNhomQuyen();
        $sttMaNhomQuyen = intval($sttMaNhomQuyenTru1) + 1;
        $maNhomQuyen = "NQ" . $sttMaNhomQuyen;

        $NhomQuyenNew = new NhomQuyenModel($maNhomQuyen, $tenNhomQuyen, 1);
        $result = NhomQuyenBUS::getInstance()->addModel($NhomQuyenNew);

        if (!$result) {
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Tên nhóm quyền đã tồn tại!']);
            exit;
        } else {
            foreach ($permissionsData as $quyen => $chucNangList) {
                foreach ($chucNangList as $chucNang) {
                    $ChiTietQuyenNew = new ChiTietQuyenModel($maNhomQuyen, $chucNang, $quyen);
                    ChiTietQuyenBUS::getInstance()->addModel($ChiTietQuyenNew);
                }
            }
            ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Thêm nhóm quyền thành công!']);
            exit;
        }
    }



    if (isset($_POST['data']) && isset($_POST['tenNhomQuyen']) && isset($_POST['updateNQ']) && isset($_POST['roleId'])) {
        $jsonData = $_POST['data'];
        $tenNhomQuyen = $_POST['tenNhomQuyen'];
        $roleId = $_POST['roleId'];
        $permissionsData = json_decode($jsonData, true);

        $nhomQuyen = NhomQuyenBUS::getInstance()->getModelById($roleId);
        if ($nhomQuyen) {
            if ($tenNhomQuyen == $nhomQuyen->getTenNhomQuyen()) {
                $resultDeleteOldCTQ = ChiTietQuyenBUS::getInstance()->deleteModel($roleId);
                if ($resultDeleteOldCTQ) {
                    foreach ($permissionsData as $quyen => $chucNangList) {
                        foreach ($chucNangList as $chucNang) {
                            $ChiTietQuyenNew = new ChiTietQuyenModel($roleId, $chucNang, $quyen);
                            ChiTietQuyenBUS::getInstance()->addModel($ChiTietQuyenNew);
                        }
                    }
                    ob_end_clean();
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'success', 'message' => 'Cập nhật nhóm quyền thành công!']);
                    exit;
                } else {
                    ob_end_clean();
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'Something went wrong 0!']);
                    exit;
                }
            } else {
                $checkIfExistTenNhomQuyen = NhomQuyenBUS::getInstance()->isTenNhomQuyenExists($tenNhomQuyen);
                if ($checkIfExistTenNhomQuyen) {
                    ob_end_clean();
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'Tên nhóm quyền đã tồn tại, cập nhật thất bại!']);
                    exit;
                } else {
                    $nhomQuyenUpdate = new NhomQuyenModel($roleId, $tenNhomQuyen, 1);
                    $resultUpdate = NhomQuyenBUS::getInstance()->updateModel($nhomQuyenUpdate);
                    if ($resultUpdate) {
                        $resultDeleteOldCTQ = ChiTietQuyenBUS::getInstance()->deleteModel($roleId);
                        if ($resultDeleteOldCTQ) {
                            foreach ($permissionsData as $quyen => $chucNangList) {
                                foreach ($chucNangList as $chucNang) {
                                    $ChiTietQuyenNew = new ChiTietQuyenModel($roleId, $chucNang, $quyen);
                                    ChiTietQuyenBUS::getInstance()->addModel($ChiTietQuyenNew);
                                }
                            }
                            ob_end_clean();
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'success', 'message' => 'Cập nhật nhóm quyền thành công!']);
                            exit;
                        } else {
                            ob_end_clean();
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'error', 'message' => 'Something went wrong 1!']);
                            exit;
                        }
                    } else {
                        ob_end_clean();
                        header('Content-Type: application/json');
                        echo json_encode(['status' => 'error', 'message' => 'Something went wrong 2!']);
                        exit;
                    }
                }
            }
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
                </div>
                <input type="text" name="roleName" id="addRoleName" class="form-control" value="<?php

                                                                                                if (isGet()) {
                                                                                                    $filterAll = filter();
                                                                                                    if (isset($filterAll['roleId'])) {
                                                                                                        $roleId = $filterAll['roleId'];
                                                                                                        if ($roleId == "") {
                                                                                                        } else {
                                                                                                            $tenNhomQuyen = NhomQuyenBUS::getInstance()->getModelById($roleId)->getTenNhomQuyen();
                                                                                                            echo $tenNhomQuyen;
                                                                                                        }
                                                                                                    } else {
                                                                                                    }
                                                                                                }
                                                                                                ?>" placeholder="Nhập tên nhóm quyền...">
                <hr>

                <table class="table align-middle table-borderless table-hover">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th>Quyền</th>
                            <?php
                            foreach ($chucNangList as $chucNang) {
                                echo '<th style="text-align: center;">' . $chucNang->getTenChucNang() . '</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isGet()) {
                            $filterAll = filter();
                            if (isset($filterAll['roleId'])) {
                                $roleId = $filterAll['roleId'];
                                if ($roleId == "") {
                                    foreach ($quyenList as $quyen) {
                                        echo '<tr class="align-middle">';
                                        echo '<td>' . $quyen->getTenQuyen() . '</td>';
                                        foreach ($chucNangList as $chucNang) {
                                            echo '<td style="text-align: center;">';
                                            echo '<input type="checkbox" class="' . $chucNang->getMaChucNang() . '" style="transform: scale(2); width:100%; text-align: center;">';
                                            echo '</td>';
                                        }
                                        echo '</tr>';
                                    }
                                } else {
                                    $chiTietQuyenOldList = ChiTietQuyenBUS::getInstance()->getAllWithMaNhomQuyen($roleId);
                                    foreach ($quyenList as $quyen) {
                                        echo '<tr class="align-middle">';
                                        echo '<td>' . $quyen->getTenQuyen() . '</td>';
                                        foreach ($chucNangList as $chucNang) {
                                            $isChecked = false;
                                            echo '<td style="text-align: center;">';
                                            foreach ($chiTietQuyenOldList as $chiTietQuyen) {
                                                if ($chiTietQuyen->getMaQuyen() == $quyen->getMaQuyen() && $chiTietQuyen->getMaChucNang() == $chucNang->getMaChucNang()) {
                                                    $isChecked = true;
                                                    break;
                                                }
                                            }
                                            if ($isChecked)  echo '<input type="checkbox" checked class="' . $chucNang->getMaChucNang() . '" style="transform: scale(2); width:100%; text-align: center;">';
                                            else  echo '<input type="checkbox" class="' . $chucNang->getMaChucNang() . '" style="transform: scale(2); width:100%; text-align: center;">';
                                            echo '</td>';
                                        }
                                        echo '</tr>';
                                    }
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <br>
                <div style="display: flex; justify-content: right; align-items: center  ;">
                    <button type="button" class="btn btn-secondary cancelCreateNewRoleBtn">Cancel</button>
                    <span style="width: 15px; display: block;"></span>
                    <?php
                    if (isGet()) {
                        $filterAll = filter();
                        if (isset($filterAll['roleId'])) {
                            $roleId = $filterAll['roleId'];
                            if ($roleId == "") {
                                echo '<button id="createNewRoleBtn" type="button" class="btn btn-primary">Save</button>';
                            } else {
                                echo '<button id="updateOldRoleBtn" type="button" class="btn btn-primary">Update</button>';
                            }
                        }
                    }
                    ?>

                </div>
            </main>
        </div>
        <?php include(__DIR__ . '/../inc/app/app.php'); ?>
        <script src="https://kit.fontawesome.com/2a9b643027.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let cancelCreateNewRoleBtn = document.querySelector('.cancelCreateNewRoleBtn');
                let checkboxes = document.querySelectorAll('input[type="checkbox"]');
                let permissionsData = {};
                let rows = document.querySelectorAll('tbody tr');
                let createNewRoleBtn = document.getElementById('createNewRoleBtn');
                let addRoleName = document.getElementById('addRoleName');
                let updateOldRoleBtn = document.getElementById('updateOldRoleBtn');

                cancelCreateNewRoleBtn.addEventListener('click', function() {
                    window.location.href = 'http://localhost/ShoesStore/frontend/?module=dashboard&view=role.view';
                })

                function resetAllCheckBox() {
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                }

                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('click', function(event) {
                        let currentRow = event.target.closest('tr');
                        let firstCheckbox = currentRow.querySelector('.CN1');

                        if (checkbox === firstCheckbox && !checkbox.checked) {
                            alert('Bạn phải chọn chức năng XEM trước khi chọn các chức năng KHÁC!');
                            currentRow.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                                checkbox.checked = false;
                            });
                        } else if (checkbox !== firstCheckbox && firstCheckbox && !firstCheckbox.checked) {
                            alert('Bạn phải chọn chức năng XEM trước khi chọn các chức năng KHÁC!');
                            currentRow.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                                checkbox.checked = false;
                            });
                        }
                    });
                });


                if (createNewRoleBtn) {
                    createNewRoleBtn.addEventListener('click', function() {
                        if (addRoleName.value == '') {
                            alert('Bạn phải nhập tên nhóm quyền trước khi tạo!');
                            return;
                        };


                        let rows = document.querySelectorAll('tbody tr');
                        let index = 1;
                        rows.forEach(function(row) {
                            let maQuyen = "Q" + index++;

                            let chucNangList = [];

                            let checkboxes = row.querySelectorAll('input[type="checkbox"]');
                            checkboxes.forEach(function(checkbox) {
                                if (checkbox.checked) {
                                    chucNangList.push(checkbox.className);
                                }
                            });

                            if (chucNangList.length > 0) {
                                permissionsData[maQuyen] = chucNangList;
                            }
                        });

                        // Kiểm tra xem có dữ liệu nào được thêm vào permissionsData không
                        if (Object.keys(permissionsData).length === 0) {
                            alert('Bạn phải chọn ít nhất một chức năng cho nhóm quyền này!');
                            return;
                        }
                        let jsonData = JSON.stringify(permissionsData);

                        fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=add_role.view', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'data=' + jsonData + '&tenNhomQuyen=' + addRoleName.value + '&createNew=' + true
                            })
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(data) {
                                alert(data.message);
                                resetAllCheckBox();
                                addRoleName.value = '';
                            });
                    })
                }

                function getUrlParameter(name) {
                    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                    var results = regex.exec(location.search);
                    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
                }

                if (updateOldRoleBtn) {
                    updateOldRoleBtn.addEventListener('click', function() {
                        if (addRoleName.value == '') {
                            alert('Bạn phải nhập tên nhóm quyền trước khi tạo!');
                            return;
                        };


                        var roleId = getUrlParameter('roleId');

                        let rows = document.querySelectorAll('tbody tr');
                        let index = 1;
                        rows.forEach(function(row) {
                            let maQuyen = "Q" + index++;

                            let chucNangList = [];

                            let checkboxes = row.querySelectorAll('input[type="checkbox"]');
                            checkboxes.forEach(function(checkbox) {
                                if (checkbox.checked) {
                                    chucNangList.push(checkbox.className);
                                }
                            });

                            if (chucNangList.length > 0) {
                                permissionsData[maQuyen] = chucNangList;
                            }
                        });

                        // Kiểm tra xem có dữ liệu nào được thêm vào permissionsData không
                        if (Object.keys(permissionsData).length === 0) {
                            alert('Bạn phải chọn ít nhất một chức năng cho nhóm quyền này!');
                            return;
                        }
                        let jsonData = JSON.stringify(permissionsData);

                        fetch('http://localhost/ShoesStore/frontend/?module=dashboard&view=add_role.view', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'data=' + jsonData + '&tenNhomQuyen=' + addRoleName.value + '&updateNQ=' + true + '&roleId=' + roleId
                            })
                            .then(function(response) {
                                return response.json();
                            })
                            .then(function(data) {
                                alert(data.message);
                            });
                    })
                }
            });
        </script>
</body>