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
      <img src="data:<?php echo $post['image_type'] ?>;base64,<?php echo $image_content; ?>" class="user-top-image"
        style="border-color: <?php echo fun_h($post['color']);?>;background-color: <?php echo fun_h($post['color']);?>;">
      <?php endif;?>
      <span class="tweet-username">
        <?php echo fun_h($post['user_name']);?>
      </span>
    </a>
    <?php endif;?>
  </p>
  <div class="tweet-content">
    <div class="tweet-content-inner">
      <?php echo $post['post_text'];?>
    </div>
  </div>

  <p class="appendix">
    <span><?php print(fun_h($post['date_time']))?></span>
    <?php if (isset($_SESSION['username']) &&
            (strcmp($post['user_id'], $_SESSION['userID']) == 0||
            strcmp(getenv('ADMIN_USER'), $_SESSION['userID']) == 0)) :?>
  <form action='?page=delete' method="POST">
    <div class="dlt-btn">
      <div class="dlt-btn-back">
        <p>本当に投稿を削除していいですか>?</p>
        <button class="dlt-yes">Yes</button>
        <button class="dlt-no" type="button">No</button>
        <input type="hidden" name="post_id" value="<?php print(fun_h($post['post_id']));?>">
        <input type="hidden" name="location_url" value="<?php print($url);?>">
      </div>
      <div class="dlt-btn-front">削除</div>
    </div>
  </form>
  <?php endif;?>
  </p>
</div>
<?php endforeach;?>
<p class="change-page">
  <?php if ($page_num>1) :
            $front_num = $page_num-1;
            if (strpos($new_url, '?')) {
                $param = $new_url . '&page_num=' . $front_num;
            } else {
                $param = $new_url . '?page_num=' . $front_num;
            }
            ?>
  <a href="<?php echo $param; ?>"
    style="color: <?php echo fun_h($current_profile['color']);?>;"><?php echo $front_num; ?>ページ目へ</a> |
  <?php endif;?>
  <?php if ($page_num<$max_page) :
            $next_num = $page_num+1;
            if (strpos($new_url, '?')) {
                $param = $new_url . '&page_num=' . $next_num;
            } else {
                $param = $new_url . '?page_num=' . $next_num;
            }
            ?>
  <a href="<?php echo $param; ?>"
    style="color: <?php echo fun_h($current_profile['color']);?>;"><?php echo $next_num; ?>ページ目へ</a>
  <?php endif;?>
</p>

<script>
$(() => {
  let box = $('.tweet-content');
  box.after('<div class="more">MORE</div>');
  let more = $('.more');

  for (let i = 0; i < box.length; i++) {
    var boxInnerH = $('.tweet-content-inner').eq(i).innerHeight();
    if (boxInnerH < 70) {
      more.eq(i).hide();
    } else {
      box.eq(i).css({
        height: '5rem'
      });
    }
  }

  function adClass() {
    $(this).next(more).addClass('is-active');
  }

  function remClass() {
    $(this).next(more).removeClass('is-active');
  }

  more.on('click', function() {
    var index = more.index(this);
    var boxThis = box.eq(index);
    var innerH = $('.tweet-content-inner').eq(index).innerHeight();
    if ($(this).hasClass('is-active')) {
      boxThis.animate({
        height: '5rem'
      }, 200, remClass);
    } else {
      boxThis.animate({
        height: innerH
      }, 200, adClass);
    }
  });
});
</script>
