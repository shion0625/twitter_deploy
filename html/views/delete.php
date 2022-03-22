<?php
use Classes\Post\GetPost;
use Classes\Post\DeletePost;

print_r($_POST);
if (!empty($_POST) && $_POST['btn_submit'] == 'delete') {
    $delete_id = fun_h($_POST['delete_post_id']);
    $delete_post = new DeletePost($delete_id);
    $flag = $delete_post->deletePost();
    if ($flag) {
        $_SESSION['messageAlert'] = "投稿は正常に削除されました";
        header("location: /");
        exit();
    }
}
$post_id = trim(fun_h($_POST["post_id"]));
$get_post = new GetPost($post_id);
$tweet_data = $get_post->getPost();
?>

<div class="delete-all-contents">
    <form method="post">
        <div>
            <label for="view_name">表示名</label>
            <input
            id="view_name"
            type="text"
            name="view_name"
            value="
                <?php if (!empty($tweet_data['user_name'])) {
                    echo $tweet_data['user_name'];
                } elseif (!empty($tweet_data['user_name'])) {
                    echo fun_h($tweet_data['user_name']);
                } ?>" disabled>
        </div>
        <div>
            <label for="message">投稿内容</label>
            <textarea id="message" name="message" disabled>
            <?php if (!empty($tweet_data['post_text'])) {
                echo $tweet_data['post_text'];
            } elseif (!empty($tweet_data['post_text'])) {
                echo fun_h($tweet_data['post_text']);
            }?>
            </textarea>
        </div>
        <a class="btn_cancel" href="/">キャンセル</a>
        <button name="btn_submit" value="delete">削除</button>
        <input
        type="hidden"
        name="delete_post_id"
        value="
            <?php if (!empty($tweet_data['post_id'])) {
                echo $tweet_data['post_id'];
            } elseif (!empty($post_id)) {
                echo fun_h($post_id);
            } ?>">
    </form>
</div>
