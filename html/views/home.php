<?php
use Classes\Post\GetHomePosts;
use Classes\Post\AllPostNum;

//最大ページ数を求める。
$all_post_num = new AllPostNum();
$max_page = $all_post_num->allPostNum();

/**
 * 各投稿内容の表示ページで使用している。
 * GETメソッドで送信されたページ番号を取得している。それを元にデータベースからは必要な分取得している。
 */
$page_num = filter_input(INPUT_GET, 'page_num', FILTER_SANITIZE_NUMBER_INT);
$page_num = ($page_num ?: 1);
$start_num = ($page_num - 1) * 15;

//投稿内容をデータベースから取得
$get_post_db = new GetHomePosts();
$user_posts = $get_post_db->getHomePosts($start_num);
?>

<script type="text/javascript">
'use strict';
const username = <?php echo json_encode($_SESSION['username']);?>;
const userId = <?php echo json_encode($_SESSION['userID']);?>;
console.log($('#js-get-post-content'));
</script>
<script type="text/javascript" src="../assets/js/websocket.js"></script>

<div id="js-test-contents"></div>
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
      <button id="js-post-btn" class="tweet-submit-btn btn" name="send" form="tweet"
        onclick="getPostContent();">ツイートする</button>
      <div id="editor"></div>
      <input id="js-get-post-content" type="hidden" value="無">
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
  <div id="js-posts" class="user-posts">
    <?php include(__DIR__ . '/component/user_posts.php')?>
  </div>
</div>
<!-- 012345<span>6789AB</span>CDEFGHIJKLMNOPQRSTUVWXYZ -->
<!-- 私の名前は淀川海都です。\nよろしくお願いします。 -->