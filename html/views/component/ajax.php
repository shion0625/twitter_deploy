<?php
use Classes\Post\InsertPost;
use Classes\Post\GetNewestPost;

session_start();
require __DIR__ .'/../../vendor/autoload.php';
require(__DIR__ . '/../../function.php');

function input_h($str)
{
    $str = trim($str);
    $html_text = "<title>Samurai Engineer</title>";
    if (strcmp($str, $html_text)) {
        echo "yes";
    } else {
        echo "no";
    }

    // echo $str;
    echo $html_text;
    echo preg_match('/<title>/', $html_text);
    // echo preg_match('@<strong>(.*)</strong>@', $str);
    echo preg_match('/<title>/', $str);
    // preg_replace('/<strong>/', 'ŠtrŒÑg;', $str);
    return $str;
    // return $html_text;
}

$user_name;
$post_id;
$post_text;
$user_id;
$date_time;
$image_type;
$image_content;



if (!empty($_POST) && $_POST['send'] == 'postSend' || $_POST['send'] == 'postInfo') {
    if (!empty($_POST) && $_POST['send'] == 'postSend') {
        $sender_id = (string)$_POST['sender'];
        $post_text = (string)$_POST['postText'];
        // $post_text = input_h($post_text);
        echo $post_text;
        $insert_post_db = new InsertPost($sender_id, $post_text);
        $insert_post_db->checkInsertTweet();
    }
    // usleep(500000);
    $get_post_db = new GetNewestPost();
    $user_post = $get_post_db->getNewestPost()[0];
    $user_name = (string)$user_post['user_name'];
    $post_id = (string)$user_post["post_id"];
    $user_id = (string)$user_post["user_id"];
    $post_text = (string)$user_post["post_text"];
    $date_time = (string)$user_post["date_time"];
    $image_type = (string)$user_post["image_type"] || null;
    $image_content = (string)base64_encode($user_post["image_content"])  || null;
}

if (!empty($_POST) && $_POST['send'] == 'postSend') {
    if ($image_type && $image_content) {
        echo "
        <div class='post'>
        <p class='user-header'>
            <a
            href='/?page=profiles&id={$user_id}'
            class='post-user-detail'>
            <img class='user-top-image' src='data:{$image_type};base64,{$image_content}'>
                <span class='tweet-username'>
                    {$user_name}
                </span>
            </a>
        </p>
        <p class='tweet-content'>
            {$post_text}
        </p>
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
            class='post-user-detail'>
                <span class='tweet-username'>
                    {$user_name}
                </span>
            </a>
        </p>
        <p class='tweet-content'>
            {$post_text}
        </p>
            <p class='appendix'>
                <span>{$date_time}</span>
            </p>
        </div>";
    }
    exit;
}

if (!empty($_POST) && $_POST['send'] == 'postInfo') {
    if ($image_type && $image_content) {
        echo "
        <div class='post'>
        <p class='user-header'>
            <a
            href='/?page=profiles&id={$user_id}'
            class='post-user-detail'>
            <img class='user-top-image' src='data:{$image_type};base64,{$image_content}'>
                <span class='tweet-username'>
                    {$user_name}
                </span>
            </a>
        </p>
        <p class='tweet-content'>
            {$post_text}
        </p>
            <p class='appendix'>
                <span>{$date_time}</span>
            </p>
        // </div>";
    } else {
        echo "
        <div class='post'>
        <p class='user-header'>
            <a
            href='/?page=profiles&id={$user_id}'
            class='post-user-detail'>
                <span class='tweet-username'>
                    {$user_name}
                </span>
            </a>
        </p>
        <p class='tweet-content'>
            {$post_text}
        </p>
            <p class='appendix'>
                <span>{$date_time}</span>
            </p>
        </div>";
    }
    exit;
}
