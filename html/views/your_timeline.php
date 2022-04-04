<?php
use Classes\Post\GetFollowingPosts;

$page_num = filter_input(INPUT_GET, 'page_num', FILTER_SANITIZE_NUMBER_INT);
$page_num = ($page_num ?: 1);
$start_num = ($page_num - 1) * 15;
if (isset($_SESSION['userID'])) {
    $GetFollowingPosts = new GetFollowingPosts($_SESSION['userID']) ;
    [$user_posts, $max_page] = $GetFollowingPosts->getFollowPost($start_num);
}

?>

<div class='your-timeline-all-contents'>
  <?php if (isset($user_posts) && isset($_SESSION['userID'])) :?>
  <div id="js-posts" class="user-posts">
    <?php include(__DIR__ . '/component/user_posts.php')?>
  </div>
  <?php else :?>
  <div>
    <p>ログインしてください</p>
  </div>
  <?php endif;?>
</div>