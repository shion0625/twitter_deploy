<?php

use Classes\Post\DeletePost;

if (!empty($_POST) && isset($_POST['post_id']) && isset($_POST['location_url'])) {
    $delete_id = $_POST['post_id'];
    $location_url = $_POST['location_url'];
    $delete_post = new DeletePost($delete_id);
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
