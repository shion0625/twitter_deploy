<?php
/**
*各ページへのルータまたすべてのページで使用するファイルも読み込んでいる
*
*/
session_start();

require __DIR__ .'/vendor/autoload.php';
require_once __DIR__ . '/function.php';
use Dotenv\Dotenv;

/** .envファイルを読み込みます。 */
$dotenv = Dotenv::createUnsafeImmutable(__DIR__.'/');
$dotenv->load();
$page = $_GET['page'] ?? "home.php";

if ($page == 'logout') {
    require(__DIR__ . getenv("PASS_DEPLOY"). '/views/logout.php');
}

require(__DIR__ . getenv("PASS_DEPLOY"). '/views/header.php');
if ($page == 'login') {
    require(__DIR__ . getenv("PASS_DEPLOY"). '/views/login.php');
} elseif ($page == 'signUp') {
    require(__DIR__ . getenv("PASS_DEPLOY"). '/views/signUp.php');
} elseif ($page == 'menu') {
    require(__DIR__ . getenv("PASS_DEPLOY"). '/views/menu.php');
} elseif ($page == 'profiles') {
    require(__DIR__ . getenv("PASS_DEPLOY"). '/views/user_profile.php');
} elseif ($page == 'delete') {
    require(__DIR__ . getenv("PASS_DEPLOY"). '/views/delete.php');
} elseif ($page == "your_timeline") {
    require(__DIR__ . getenv("PASS_DEPLOY"). '/views/your_timeline.php');
} else {
    require(__DIR__ . getenv("PASS_DEPLOY"). '/views/home.php');
}
require(__DIR__ . getenv("PASS_DEPLOY"). '/views/footer.php');