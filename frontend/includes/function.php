<?php
require 'VerifyEmail.php';
use backend\bus\ChiTietQuyenBUS;
use backend\bus\RolePermissionBUS;
use backend\enums\StatusEnums;

if (!defined('_CODE')) {
    die('Access denied');
}

// Thư viện PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use backend\bus\TokenLoginBUS;
use backend\bus\UserBUS;
use backend\services\session;

// Hàm giúp nhúng header và footer nhanh hơn vào các file
function layouts($layoutName, $data = [])
{
    if (file_exists(_WEB_PATH_TEMPLATE . '/layouts/' . $layoutName . '.php')) {
        require_once _WEB_PATH_TEMPLATE . '/layouts/' . $layoutName . '.php';
    }
}


// Hàm gửi mail 
function sendMail($to, $subject, $content, $callback)
{
    // Đây là 1 tiến trình giả lập việc gửi mail không đồng bộ
    $mailCheck = new VerifyEmail();
    $mailCheck->setStreamTimeoutWait(5);
    $mailCheck->setEmailFrom("manshpypro@gmail.com");

    // Kiểm tra email 
    if (!$mailCheck->check($to)) {
        call_user_func($callback, false, "Email không hợp lệ");
        return;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'manshpypro@gmail.com';
        $mail->Password = 'ztbwjqcpunymawdz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('manshpypro@gmail.com', 'ManDuong');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $content;

        // Gửi email
        $mail->send();
        call_user_func($callback, true, null); // Thành công
    } catch (Exception $e) {
        call_user_func($callback, false, $mail->ErrorInfo); // Lỗi
    }
}




// Kiểm tra phương thức GET
function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET')
        return true;
    return false;
}

// Kiểm tra phương thức POST
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
        return true;
    return false;
}

// Hàm filter lọc dữ liệu
function filter()
{
    $filterArr = [];
    if (isGet()) {
        // Xử lí dữ liệu trước khi hiển thị ra
        if (!empty($_GET)) {
            foreach ($_GET as $key => $val) {
                $key = strip_tags($key);
                if (is_array($val)) {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }

    if (isPost()) {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $val) {
                if (is_array($val)) {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    return $filterArr;
}

// Hàm in ra message
function getMsg($msg, $type = 'success')
{
    return "<div class='cw text-center alert alert-$type'>" . htmlspecialchars($msg) . "</div>";
}


// Hàm chuyển hướng
function redirect($path = 'index.php')
{
    header("location: $path");
    exit;
}


// Hàm thông báo lỗi
function formError($name, $errors)
{
    return !empty($errors[$name]) ? '<p style="color:red;">' . reset($errors[$name]) . '</p>' : '';
}

// Hàm hiển thị dữ liệu cũ
function formOldInfor($name, $duLieuDaNhap, $default = '')
{
    return (!empty($duLieuDaNhap[$name])) ? $duLieuDaNhap[$name] : $default;
}

function isLogin()
{
    // Kiểm tra trạng thái đăng nhập
    $checkLogin = false;
    if (session::getInstance()->getSession('tokenLogin')) {
        $tokenLogin = session::getInstance()->getSession('tokenLogin');

        // Kiểm tra tokenLogin có giống token trong database không
        $queryToken = TokenLoginBUS::getInstance()->getModelByToken($tokenLogin);

        if (!empty($queryToken)) {
            $checkLogin = true;
        } else {
            session::getInstance()->removeSession('tokenLogin');
        }
    }

    return $checkLogin;
}

function requireLogin()
{
    if (!isLogin()) {
        redirect('?module=auth&action=login');
    }
}


function isAllowToDashBoard()
{
    $tokenLogin = session::getInstance()->getSession('tokenLogin');
    if ($tokenLogin === null) {
        return false;
    }

    $tokenModel = TokenLoginBUS::getInstance()->getModelByToken($tokenLogin);
    if ($tokenModel === null) {
        return false; // Hoặc xử lý lỗi khác nếu cần
    }

    $userId = TokenLoginBUS::getInstance()->getModelByToken($tokenLogin)->getUserId();
    $userModel = UserBUS::getInstance()->getModelById($userId);
    $roleId = $userModel->getMaNhomQuyen();

    if ($roleId == 'NQ4') {
        return false;
    } else {
        return true;
    }
}

function checkPermission($maQuyen, $maChucNang)
{
    if (isLogin()) {
        $tokenLogin = session::getInstance()->getSession('tokenLogin');
        $userId = TokenLoginBUS::getInstance()->getModelByToken($tokenLogin)->getUserId();
        $userModel = UserBUS::getInstance()->getModelById($userId);
        $maNhomQuyen = $userModel->getMaNhomQuyen();
        $chiTietQuyenList = ChiTietQuyenBUS::getInstance()->getAllModels();

        // echo '<pre>';
        // print_r($chiTietQuyenList);
        // echo '</pre>';

        foreach ($chiTietQuyenList as $chiTietquyen) {
            if ($chiTietquyen->getMaQuyen() == $maQuyen && $chiTietquyen->getMaChucNang() == $maChucNang && $chiTietquyen->getMaNhomQuyen() == $maNhomQuyen) {
                return true;
            }
        }
        return false;
    }
}

function jsonResponse($status, $message)
{
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}