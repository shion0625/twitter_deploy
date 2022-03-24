<?php
/**
*各ページへのルータまたすべてのページで使用するファイルも読み込んでいる
*
*/
ini_set("memory_limit", "3072M");
ob_start();
session_start();
echo __DIR__;
require __DIR__ .'/vendor/autoload.php';
require(__DIR__ . '/function.php');


$page = $_GET['page'] ?? "home.php";

include(__DIR__ . '/views/header.php');
if ($page == 'login') {
    include(__DIR__ . '/views/login.php');
} elseif ($page == 'signUp') {
    include(__DIR__ . '/views/signUp.php');
} elseif ($page == 'menu') {
    include(__DIR__ . '/views/menu.php');
} elseif ($page == 'logout') {
    include(__DIR__ . '/views/logout.php');
} elseif ($page == 'profiles') {
    include("views/user_profile.php");
} elseif ($page == 'delete') {
    include(__DIR__ . '/views/delete.php');
} elseif ($page == "your_timeline") {
    include(__DIR__ . '/views/your_timeline.php');
} else {
    include(__DIR__ .'/views/home.php');
}
include("views/footer.php");
