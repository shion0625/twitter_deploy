<?php
$url = $_SERVER['REQUEST_URI'];
$replacement = '/(\?|&)page_num=[0-9]+/';
$new_url = preg_replace($replacement, '', $url);
?>
<?php if (!$user_posts) :?>
<div class="error_post">
  <p>投稿する内容はありません。</p>
</div>
<?php endif;?>
<?php foreach ($user_posts as $post) :?>
<div class="post">
  <p class="user-header">
    <?php if (strcmp(getenv('ADMIN_USER'), $post['user_name']) == 0) :?>
    <span class="tweet-username">
      admin
    </span>
    <?php else :?>
    <a href="/?page=profiles&id=<?php echo $post['user_id']?>" class="post-user-detail">
      <?php if (isset($post['image_type']) && isset($post['image_content'])) :
                        $image_content = base64_encode($post['image_content']);?>
      <img src="data:<?php echo $post['image_type'] ?>;base64,<?php echo $image_content; ?>" class="user-top-image">
      <?php endif;?>
      <span class="tweet-username">
        <?php print(fun_h($post['user_name']))?>
      </span>
    </a>
    <?php endif;?>
  </p>
  <div class="tweet-content">
    <?php print($post['post_text'])?>
  </div>
  <p class="appendix">
    <span><?php print(fun_h($post['date_time']))?></span>
    <?php if (isset($_SESSION['username']) &&
            ($post['user_name'] == $_SESSION['username'] ||
            strcmp(getenv('ADMIN_USER'), $_SESSION['username']) == 0)) :?>
  <form action=?page=delete method="POST">
    <input type="hidden" name="post_id" value="<?php print(fun_h($post['post_id']));?>">
    <button>削除</button>
  </form>
  <?php endif;?>
  </p>
</div>
<?php endforeach;?>
<p>
  <?php if ($page_num>1) :
            $next_num = $page_num-1;
            if (strpos($new_url, '?')) {
                $param = $new_url . '&page_num=' . $next_num;
            } else {
                $param = $new_url . '?page_num=' . $next_num;
            }
            ?>
  <a href="<?php echo $param; ?>"><?php echo $next_num; ?>ページ目へ</a> |
  <?php endif;?>
  <?php if ($page_num<$max_page) :
            $next_num = $page_num+1;
            if (strpos($new_url, '?')) {
                $param = $new_url . '&page_num=' . $next_num;
            } else {
                $param = $new_url . '?page_num=' . $next_num;
            }
            ?>
  <a href="<?php echo $param; ?>"><?php echo $next_num; ?>ページ目へ</a>
  <?php endif;?>
</p>