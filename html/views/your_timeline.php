<?php
use Classes\Post\GetFollowingPosts;

$GetFollowingPosts = new GetFollowingPosts($_SESSION['userID']);
$user_posts = $GetFollowingPosts->getFollowPost();
?>

<div class="your-timeline-all-contents">
    <?php include(__DIR__ . '/component/user_posts.php')?>
</div>