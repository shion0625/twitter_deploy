<?php
/**
 * callback url
 * http://localhost:8080/views/githubauth/callback.php
 */
require_once '_config.php';
require_once '../function.php';

use Classes\Login;
use Classes\SignUp;

// ちゃんとlogin.phpからきたかどうか確認
if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
}

// 認証コードからアクセストークンを取得
$token = $provider->getAccessToken('authorization_code', [
    'code' => $_GET['code']
]);

// トークン使って認可した情報を取得できる
$user = $provider->getResourceOwner($token);

$user_array = $user->toArray();

$email = $user_array['node_id'].'@github.shion.com';
$provider_name ="github";

if (!empty($_SESSION['enter_status']) && $_SESSION['enter_status'] == 'login') {
    $get_login= new Login($email, $password, $provider_name);
    $get_login->login();
} elseif (!empty($_SESSION['enter_status']) && $_SESSION['enter_status'] == 'signUp') {
    $username = (string)fun_h($user->getNickname());
    $created_date = (string)fun_h(date('Y-m-d'));
    $sign_up_db = new SignUp($username, $password, $email, $created_date, $provider_name);
    $sign_up_db ->resultSignUp();
}
