<?php

use Classes\Post\DeletePost;

$post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_STRING);
$location_url = filter_input(INPUT_POST, 'location_url', FILTER_SANITIZE_STRING);

if (isset($post_id) && isset($location_url)) {
    $delete_post = new DeletePost($post_id);
    $flag = $delete_post->deletePost();
    if ($flag) {
        $_SESSION['messageAlert'] = "投稿は正常に削除されました";
        header("location: {$location_url}");
        exit();
    } else {
        $_SESSION['messageAlert'] = "投稿は正常に削除されませんでした。";
        header("location: {$location_url}");
        exit();
    }
}
