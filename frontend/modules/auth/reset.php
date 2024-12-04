<?php

use backend\services\session;
use backend\bus\UserBUS;

if (!defined('_CODE')) {
    die('Access denied');
}
$data = [
    'pageTitle' => 'Reset Password'
];

// Kiểm tra token từ URL
if (!empty(filter()['token'])) {
    $token = filter()['token'];
}

if (!empty($token)) {
    // Truy vấn người dùng dựa trên token
    $userQuery = UserBUS::getInstance()->getModelByForgotToken($token);
    if (!empty($userQuery)) {
        // Nếu có form POST
        if (isPost()) {
            $filterAll = filter();
            $errors = [];

            // Xác thực dữ liệu
            $errors = UserBUS::getInstance()->validateResetPassword($filterAll['password'], $filterAll['password_confirm']);

            if (empty($errors)) {
                // Cập nhật mật khẩu mới
                $passwordHash = password_hash($filterAll['password'], PASSWORD_DEFAULT);

                $userQuery->setPassword($passwordHash);
                $userQuery->setForgotToken(null); // Xóa token
                $userQuery->setUpdateAt(date('Y-m-d H:i:s')); // Cập nhật thời gian

                $updateStatus = UserBUS::getInstance()->updateModel($userQuery);
                if ($updateStatus) {
                    echo "<script>
                    alert('Thay đổi mật khẩu thành công!');
                    window.location.href = '?module=auth&action=login';
                    </script>";
                    exit;
                } else {
                    // Thông báo lỗi hệ thống
                    session::getInstance()->setFlashData('msg', 'Lỗi hệ thống, vui lòng thử lại sau!');
                    session::getInstance()->setFlashData('msg_type', 'danger');
                }
            } else {
                // Nếu có lỗi trong quá trình xác thực
                session::getInstance()->setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu!');
                session::getInstance()->setFlashData('msg_type', 'danger');
                session::getInstance()->setFlashData('errors', $errors);
                redirect("?module=auth&action=reset&token=$token");
            }
        }

        // Lấy thông báo và kiểu thông báo từ session
        $msg = session::getInstance()->getFlashData('msg');
        $msg_type = session::getInstance()->getFlashData('msg_type');
        $errors = session::getInstance()->getFlashData('errors');
?>

        ?>
        <!-- Bảng đặt lại mật khẩu -->
        <div id="header">
            <?php layouts('header', $data); ?>
        </div>

        <body>
            <div class="row">
                <div class="col-4" style="margin: 24px auto;">
                    <form class="cw" action="" method="post">
                        <h2 style="text-align:center; text-transform: uppercase;">Reset Password</h2>

                        <!-- Hiển thị thông báo -->
                        <?php if (!empty($msg)) { ?>
                            <div class="text-center alert alert-<?php echo $msg_type; ?>">
                                <?php echo $msg; ?>
                            </div>
                        <?php } ?>

                        <div class="form-group mg-form">
                            <label for="">New password:</label>
                            <input name="password" class="form-control" type="password" placeholder="New password..">
                            <?php echo formError('password', $errors); ?>
                        </div>

                        <div class="form-group mg-form mt-2">
                            <label for="">Re-type your password:</label>
                            <input name="password_confirm" class="form-control" type="password"
                                placeholder="Confirm new password..">
                            <?php echo formError('password_confirm', $errors); ?>
                        </div>

                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <button type="submit" class="btn btn-primary btn-block mg-form mt-3" style="width:100%;">Reset</button>
                        <hr>
                        <p class="text-center"><a href="?module=auth&action=login">Login</a></p>
                    </form>
                </div>
            </div>
        </body>
<?php
    } else {
        getMsg("Liên kết không tồn tại hoặc đã hết hạn.", "danger");
    }
} else {
    getMsg("Liên kết không tồn tại hoặc đã hết hạn.", "danger");
}
?>