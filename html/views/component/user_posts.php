<div class="user-posts">
    <?php foreach ($user_posts as $post) :?>
    <div class="post">
        <a
        href="/?page=profiles&id=<?php echo $post['user_id']?>"
        class="post-user-detail">
            <?php if (isset($post['image_type']) && isset($post['image_content'])) :
                $image_content = base64_encode($post['image_content']);?>
                <img
                src="data:<?php echo $post['image_type'] ?>;base64,<?php echo $image_content; ?>"
                class="user-top-image">
            <?php endif;?>
            <span class="tweet-username">
                <?php print(fun_h($post['user_name']))?>
            </span>
        </a>
        <p class="tweet-content">
            <?php print(fun_h($post['post_text']))?>
        </p>
            <p class="appendix">
                <span><?php print(fun_h($post['date_time']))?></span>
                <?php if ($post['user_name'] == $_SESSION['username']) :?>
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
</div>