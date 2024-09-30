<?php
use backend\bus\TokenLoginBUS;
use backend\bus\UserBUS;
use backend\services\session;

function isActivePage($currentPage, $pageName)
{
    if ($currentPage === $pageName) {
        return 'active';
    }
    return ''; 
}

$token = session::getInstance()->getSession('tokenLogin');
$tokenModel = TokenLoginBUS::getInstance()->getModelByToken($token);
$userModel = UserBUS::getInstance()->getModelById($tokenModel->getUserId());
?>


<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <?php
                if (checkPermission("Q1", "CN1")) {
                    ?>
                    <a class="nav-link <?php echo isActivePage($_GET['view'], 'dashboard.view'); ?>" aria-current="page"
                        href="?module=dashboard&view=dashboard.view">
                        <span data-feather="home"></span>
                        Dashboard
                    </a>
                    <?php
                }
                ?>
            </li>
            <li class="nav-item">
                <?php
                if (checkPermission("Q2", "CN1")) {
                    ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActivePage($_GET['view'], 'order.view'); ?>"
                        href="?module=dashboard&view=order.view">
                        <span data-feather="file"></span>
                        Orders
                    </a>
                </li>
                <?php
                }
                ?>
            </li>
            <li class="nav-item">
                <?php
                if (checkPermission("Q3", "CN1")) {
                    ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActivePage($_GET['view'], 'product.view'); ?>"
                        href="?module=dashboard&view=product.view">
                        <span data-feather="shopping-cart"></span>
                        Products
                    </a>
                </li>
                <?php
                }
                ?>
            </li>
            <li class="nav-item">
                <?php
                if (checkPermission("Q4", "CN1")) {
                    ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActivePage($_GET['view'], 'category.view'); ?>"
                        href="?module=dashboard&view=category.view">
                        <span data-feather="list"></span>
                        Categories
                    </a>
                </li>
                <?php
                }
                ?>
            </li>
            <li class="nav-item">
                <?php
                if (checkPermission("Q5", "CN1")) {
                    ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActivePage($_GET['view'], 'size.view'); ?>"
                        href="?module=dashboard&view=size.view">
                        <span data-feather="hash"></span>
                        Sizes
                    </a>
                </li>
                <?php
                }
                ?>
            </li>
            <!-- <li class="nav-item">
                <?php
                if (checkPermission("Q6", "CN1")) {
                    ?>
                <a class="nav-link <?php echo isActivePage($_GET['view'], 'coupon.view'); ?>"
                    href="?module=dashboard&view=coupon.view">
                    <span data-feather="star"></span>
                    Coupons
                </a>
                <?php
                }
                ?>
            </li> -->
            <li class="nav-item">
                <?php
                if (checkPermission("Q7", "CN1")) {
                    ?>
                    <a class="nav-link <?php echo isActivePage($_GET['view'], 'inventory.view'); ?>"
                        href="?module=dashboard&view=inventory.view">
                        <span data-feather="package"></span>
                        Inventory
                    </a>
                    <?php
                }
                ?>
            </li>
            <li class="nav-item">
                <?php
                if (checkPermission("Q8", "CN1")) {
                    ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActivePage($_GET['view'], 'account.view'); ?>"
                        href="?module=dashboard&view=account.view">
                        <span data-feather="users"></span>
                        Accounts
                    </a>
                </li>
                <?php
                }
                ?>
            </li>
            <li class="nav-item">
                <?php
                if (checkPermission("Q9", "CN1")) {
                    ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActivePage($_GET['view'], 'role.view'); ?>"
                        href="?module=dashboard&view=role.view">
                        <span data-feather="tool"></span>
                        Roles Setup
                    </a>
                </li>
                <?php
                }
                ?>
            </li>
            <li class="nav-item">
                <?php
                if (checkPermission("Q11", "CN1")) {
                    ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActivePage($_GET['view'], 'nhacungcap.view'); ?>"
                        href="?module=dashboard&view=nhacungcap.view">
                        <span data-feather="users"></span>
                        Nhà cung cấp
                    </a>
                </li>
                <?php
                }
                ?>
            </li>
            <li class="nav-item">
                <?php
                if (checkPermission("Q10", "CN4") && checkPermission("Q10", "CN1")) {
                    ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActivePage($_GET['view'], 'nhaphang.view'); ?>"
                        href="?module=dashboard&view=nhaphang.view">
                        <span data-feather="clipboard"></span>
                        Nhập hàng
                    </a>
                </li>
                <?php
                }
                ?>
            </li>
            <li class="nav-item">
                <?php
                if (checkPermission("Q10", "CN1")) {
                    ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActivePage($_GET['view'], 'phieunhap.view'); ?>"
                        href="?module=dashboard&view=phieunhap.view">
                        <span data-feather="clipboard"></span>
                        Phiếu nhập
                    </a>
                </li>
                <?php
                }
                ?>
            </li>
            <li class="nav-item">
                <?php
                if (checkPermission("Q11", "CN1")) {
                    ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActivePage($_GET['view'], 'nhacungcap.view'); ?>"
                        href="?module=dashboard&view=nhacungcap.view">
                        <span data-feather="users"></span>
                        Nhà Cung Cấp
                    </a>
                </li>
                <?php
                }
                ?>
            </li>
        </ul>
    </div>
</nav>