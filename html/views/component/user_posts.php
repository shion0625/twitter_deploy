<?php if (!$user_posts) :?>
    <div class="error_post">
        <p>投稿する内容はありません。</p>
    </div>
<?php endif;?>
<?php foreach ($user_posts as $post) :?>
    <div class="post">
        <p class="user-header">
            <?php if (strcmp(getenv('ADMIN_USER'), $post['user_name']) > 0) :?>
                <span class="tweet-username">
                    admin
                </span>
            <?php else :?>
                <a
                href="/?page=profiles&id=<?php echo $post['user_id']?>"
                class="post-user-detail">
                    <?php if (isset($post['image_type']) && isset($post['image_content'])) :
                        $image_content = base64_encode($post['image_content']);?>
                        <img src="data:<?php echo $post['image_type'] ?>;base64,<?php echo $image_content; ?>"
                                class="user-top-image">
                    <?php endif;?>
                    <span class="tweet-username">
                        <?php print(fun_h($post['user_name']))?>
                    </span>
                </a>
            <?php endif;?>
        </p>
        <p class="tweet-content">
            <?php print(fun_h($post['post_text']))?>
        </p>
        <p class="appendix">
            <span><?php print(fun_h($post['date_time']))?></span>
            <?php if (isset($_SESSION['username']) &&
            ($post['user_name'] == $_SESSION['username'] ||
            strcmp(getenv('ADMIN_USER'), $_SESSION['username']) > 0)) :?>
                <form action=?page=delete method="POST">
                    <input
                    type="hidden"
                    name="post_id"
                    value="<?php print(fun_h($post['post_id']));?>">
                    <button>削除</button>
                </form>
            <?php endif;?>
        </p>
    </div>
<?php endforeach;?>
    <p>
        <?php if ($page_num>1) :?>
            <a href="?page_num=<?php echo $page_num-1; ?>"><?php echo $page_num-1; ?>ページ目へ</a> |
        <?php endif;?>
        <?php if ($page_num<$max_page) :?>
            <a href="?page_num=<?php echo $page_num+1; ?>"><?php echo $page_num+1; ?>ページ目へ</a>
        <?php endif;?>
    </p>
