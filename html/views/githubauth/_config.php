<?php
require_once __DIR__ .'/../../vendor/autoload.php';
session_start();
$github_keys = require('../../github-app-keys.php');
$provider = new League\OAuth2\Client\Provider\Github([
    'clientId'          => $github_keys['clientId'],
    'clientSecret'      => $github_keys['clientSecret'],
]);

$title = "PHP GitHub Login Sample";