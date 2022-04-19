<?php
/**
 * callback url
 * http://localhost:8080/views/githubauth/callback.php
 */
require_once '_config.php';

// ちゃんとlogin.phpからきたかどうか確認
if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
}

// 認証コードからアクセストークンを取得
$token = $provider->getAccessToken('authorization_code', [
    'code' => $_GET['code']
]);

echo $token."\n";
echo 'Successfully callbacked!!'."\n";

// トークン使って認可した情報を取得できる
$user = $provider->getResourceOwner($token);

echo "<pre>";
var_dump($user);
echo "</pre>";