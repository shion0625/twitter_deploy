<?php

use Classes\Post\InsertPost;
use Classes\Post\GetNewestPost;

session_start();

require __DIR__ .'/../../vendor/autoload.php';
require __DIR__ .'/../../function.php';

$user_name;
$post_id;
$user_id;
$date_time;
$image_type;
$image_content;
$send = filter_input(INPUT_POST, 'send', FILTER_SANITIZE_STRING);
$post_text = filter_input(INPUT_POST, 'postHtml', FILTER_SANITIZE_STRING);
$sender_id = filter_input(INPUT_POST, 'sender', FILTER_SANITIZE_STRING);


if ($send  == 'postSend' || $send == 'postInfo') {
    if ($send == 'postSend') {
        $insert_post_db = new InsertPost($sender_id, $post_text);
        $insert_post_db->checkInsertTweet();
    }
    $get_post_db = new GetNewestPost();
    $user_post = $get_post_db->getNewestPost()[0];
    $user_name = fun_h((string)$user_post['user_name']);
    $user_color = fun_h((string)$user_post['color']);
    $post_id = (string)$user_post["post_id"];
    $user_id = (string)$user_post["user_id"];
    $post_text = (string)$user_post["post_text"];
    $date_time = (string)$user_post["date_time"];
    $image_type = (string)$user_post["image_type"];
    $image_content = (string)base64_encode($user_post["image_content"]);
}

if ($send == 'postSend') {
    echo "done";
}

if ($send == 'postInfo') {
    if ($image_type && $image_content) {
        echo "
        <div class='post'>
            <p class='user-header'>
                <a
                href='/?page=profiles&id={$user_id}'
                class='post-user-detail'>
                <img class='user-top-image' src='data:{$image_type};base64,{$image_content}'
                style='border-color: {$user_color}; background-color: {$user_color};'>
                    <span class='tweet-username'>
                        {$user_name}
                    </span>
                </a>
            </p>
            <div class='tweet-content'>
                <div class='tweet-content-inner'>
                    {$post_text}
                </div>
            </div>
                <p class='appendix'>
                    <span>{$date_time}</span>
                </p>
        </div>";
    } else {
        echo "
        <div class='post'>
            <p class='user-header'>
                <a
                href='/?page=profiles&id={$user_id}'
                class='post-user-detail'
                style='border-color: {$user_color}; background-color: {$user_color};'>
                    <span class='tweet-username'>
                        {$user_name}
                    </span>
                </a>
            </p>
            <div class='tweet-content'>
                <div class='tweet-content-inner'>
                    {$post_text}
                </div>
            </div>
            <p class='appendix'>
                <span>{$date_time}</span>
            </p>
        </div>";
    }
    exit;
}
