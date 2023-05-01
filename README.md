# twiter-php

## Usage

Click [see this web application](https://shiontter.shion0625.site)

現在デプロイ先のサーバでwebsocketが使用できないため、正常に動作ができていません。申し訳ございません。

## Deploy

デプロイをするときはhtmlファイル内を送信。
indexファイル内をhtml/vendorとする。
connect.php __DIR__ . /../../.env

### websocket設定

AjaxPostのURL
require __DIR__ . '/../../../function.php';
websocket.js
url: '/html/views/component/AjaxPosts.php',
