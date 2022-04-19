<?php
require_once '_config.php';

// GitHubの認証画面へリダイレクトするURL取得
$authUrl = $provider->getAuthorizationUrl();

// CSRF対策のためにいまの状態を入れておく
$_SESSION['oauth2state'] = $provider->getState();

header('Location: '.$authUrl);
exit;