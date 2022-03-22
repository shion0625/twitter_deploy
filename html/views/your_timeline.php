<?php
use Classes\Post\GetFollowingPosts;

if (isset($_SESSION['userID'])) {
    $GetFollowingPosts = new GetFollowingPosts($_SESSION['userID']) ;
    $user_posts = $GetFollowingPosts->getFollowPost();
}

?>
<?php if (isset($user_posts)) :?>
    <div class="your-timeline-all-contents">
        <?php include(__DIR__ . '/component/user_posts.php')?>
    </div>
<?php else :?>
    <div>
        <p>ログインしてください</p>
    </div>
<?php endif;?>