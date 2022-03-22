<?php
use Classes\Post\InsertPost;
use Classes\Post\GetHomePosts;

//データベースに投稿内容を保存
if (!empty($_POST) && isset($_POST['send'])) {
    $user_id = (string)fun_h($_SESSION['userID']);
    $post_text = (string)fun_h($_POST['tweet-input']);
    $insert_post_db = new InsertPost($user_id, $post_text);
    $insert_post_db->checkInsertTweet();
}
//投稿内容をデータベースから取得
$get_post_db = new GetHomePosts();
$user_posts = $get_post_db->getHomePosts();
?>

<script type="text/javascript">
    const username = <?php echo json_encode($_SESSION['username']);?>;
</script>
<script type="text/javascript"src="../assets/js/websocket.js"></script>


<div class='home-all-contents'>
    <div class=tweet-btn>
        <button id="js-show-popup">ツイートする</button>
    </div>
    <?php if (!empty($_SESSION["userID"])) :?>
    <div class="popup" id="js-popup">
    <div class="popup-inner">
        <div class="close-btn" id="js-close-btn">
            <i class="fas fa-times"></i>
        </div>
        <button
        class="tweet-submit-btn btn"
        name="send"
        form="tweet"
        onclick="socketSend();"
        type="submit">ツイートする</button>
        <form id="tweet" class="tweet-form" method=POST>
            <textarea
            id="js-post-content"
            class="tweet-textarea"
            name="tweet-input"
            cols=""
            rows="10"
            wrap="soft"required ></textarea>
            <p class="tweet-items">
            <span class="tweet-item"><i class="fas fa-bold"></i></span>
            <span class="tweet-item"><i class="fas fa-italic"></i></span>
            <span class="tweet-item"><i class="fas fa-underline"></i></span>
            <span class="tweet-item"><i class="fas fa-link"></i></span>
            <span class="tweet-item"><i class="fas fa-paperclip"></i></span>
            <span class="tweet-item"><i class="far fa-image"></i></span>
        </p>
        </form>
    </div>
    <div class="black-background" id="js-black-bg"></div>
    </div>
    <?php else :?>
    <div class="popup" id="js-popup">
    <div class="popup-inner">
    <div class="close-btn" id="js-close-btn">
        <i class="fas fa-times"></i>
    </div>
        <p class="tweet-not-login">
            ログインしてください。
        </p>
    </div>
    <div class="black-background" id="js-black-bg"></div>
    </div>
    <?php endif;?>
    <div id="js-posts" class="post"></div>
    <div class="user-posts">
        <?php include(__DIR__ . '/component/user_posts.php')?>
    </div>
</div>