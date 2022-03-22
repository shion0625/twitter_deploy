<?php
/**
*各ページへのルータまたすべてのページで使用するファイルも読み込んでいる
*
*/
// ini_set("memory_limit", "3072M");
// echo phpinfo();
ob_start();
session_start();
require './vendor/autoload.php';

require_once(__DIR__ . '/function.php');

include(__DIR__ . '/views/header.php');
if ($_GET['page'] == 'login') {
    include(__DIR__ . '/views/login.php');
} elseif ($_GET['page'] == 'signUp') {
    include(__DIR__ . '/views/signUp.php');
} elseif ($_GET['page'] == 'menu') {
    include(__DIR__ . '/views/menu.php');
} elseif ($_GET['page'] == 'logout') {
    include(__DIR__ . '/views/logout.php');
} elseif ($_GET['page'] == 'profiles') {
    include("views/user_profile.php");
} elseif ($_GET['page'] == 'delete') {
    include(__DIR__ . '/views/delete.php');
} elseif ($_GET['page'] == "your_timeline") {
    include(__DIR__ . '/views/your_timeline.php');
} else {
    include(__DIR__ .'/views/home.php');
}
include("views/footer.php");
