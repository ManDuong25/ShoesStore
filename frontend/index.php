<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
// require config
require __DIR__ . '/config.php';
require __DIR__ . '/includes/function.php';
require __DIR__ . '/../vendor/autoload.php';

$module = _MODULE;
$action = _ACTION;
$view = 'dashboard.view';

if (!empty($_GET['module'])) {
    if (is_string($_GET['module'])) {
        $module = trim($_GET['module']);
    }
}

if (!empty($_GET['action'])) {
    if (is_string($_GET['action'])) {
        $action = trim($_GET['action']);
    }
}

if (!empty($_GET['view'])) {
    if (is_string($_GET['view'])) {
        $view = trim($_GET['view']);
    }
}

if ($module != 'dashboard') {
    $path = 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $action . '.php';
} else {
    $path = 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . $view . '.php';
}


if (file_exists($path))
    require_once ($path);
else {
    require_once ('modules' . DIRECTORY_SEPARATOR . 'error' . DIRECTORY_SEPARATOR . '404page.php');

}