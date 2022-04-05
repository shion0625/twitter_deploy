<?php
/**
*各ページへのルータまたすべてのページで使用するファイルも読み込んでいる
*
*/
ini_set("memory_limit", "3072M");
ob_start();
session_start();
require __DIR__ .'/vendor/autoload.php';
require(__DIR__ . '/function.php');


$page = $_GET['page'] ?? "home.php";

include(__DIR__ . '/views/header.php');
if ($page == 'login') {
    require(__DIR__ . '/views/login.php');
} elseif ($page == 'signUp') {
    require(__DIR__ . '/views/signUp.php');
} elseif ($page == 'menu') {
    require(__DIR__ . '/views/menu.php');
} elseif ($page == 'logout') {
    require(__DIR__ . '/views/logout.php');
} elseif ($page == 'profiles') {
    require("views/user_profile.php");
} elseif ($page == 'delete') {
    require(__DIR__ . '/views/delete.php');
} elseif ($page == "your_timeline") {
    require(__DIR__ . '/views/your_timeline.php');
} else {
    require(__DIR__ .'/views/home.php');
}
require("views/footer.php");