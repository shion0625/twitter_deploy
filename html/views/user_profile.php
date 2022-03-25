<?php

use Classes\Image\UsingUpdateInsert;
use Classes\Image\UsingGetImage;
use Classes\User\GetUserInfo;
use Classes\Follow\CheckFollow;
use Classes\Follow\GetNumFollow;

$_SESSION['messageAlert'] ='';
print_r($_POST);

if (isset($_GET['id'])) {
    $profile_user_id = (string)$_GET['id'];
    $get_user_info = new GetUserInfo($profile_user_id);
    $user_posts= $get_user_info->getUserPost();
    $user_profile = $get_user_info->getUserProfile();
    $get_image = new UsingGetImage('user_id', $profile_user_id);
    $image = $get_image->usingGetImage();
}

if (!isset($_SESSION['userID'])) {
    $_SESSION['messageAlert'] ='あなたのユーザIDが設定されていません。ログインしてください。';
    header('Location: ?page=');
}
$is_exit_image = false;
if (!empty($image)) {
    $is_exit_image = true;
    $image_type = $image['image_type'];
    $image_content = $image['image_content'];
}

$current_user_id = $_SESSION['userID'];
//このページに送信されたユーザIDが自分だった場合設定ページが表示される。
$is_yourself = $profile_user_id == $current_user_id;

if ($is_yourself) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_FILES['image']['name'])) {
        // 画像を保存 すでに画像がデータベース内にあればupdate,なければinsertされる。
        $using_insert_update = new UsingUpdateInsert($is_exit_image);
        $result = $using_insert_update->actionImage();
        if ($result['update'] || $result['insert']) {
            $_SESSION['messageAlert'] = "画像の保存に成功しました。";
            header("Location: ?page=profiles&id=${current_user_id}");
            exit();
        }
    }
}

if (!$is_yourself) {
    $CheckFollow=new CheckFollow($current_user_id, $profile_user_id);
    $is_follow = $CheckFollow->isCheckFollow();
    if ($is_follow) {
        $follow_button_text="フォロー中";
    } else {
        $follow_button_text="フォロー";
    }
}
$GetNumFollow = new GetNumFollow($profile_user_id);
$follow_num = $GetNumFollow->numFollow();
$follower_num = $GetNumFollow->numFollower();
?>

<script>
    function followUser(){
        let doFollowBtn = $('#js-submit-btn').val();
        let currentId = $('#js-current-user-id').val();
        let profileId = $('#js-profile-user-id').val();
        let $map = {"type" : doFollowBtn, "currentId":currentId, "profileId": profileId};
        $.ajax({
            type: 'POST',
            url: 'views/component/AjaxFollowProcess.php',
            data: $map,
            dataType: 'json'
        }).done(function(data){
            alert("successful: ");
            console.log(data);
            $('#js-follow')[0].textContent = data['follow'];
            $('#js-follower')[0].textContent = data['follower'];
            if (data['status']) {
                $('#js-submit-btn').textContent="フォロー中";
            } else {
                $('#js-submit-btn').textContent="フォロー";
            }
        }).fail(function(msg, XMLHttpRequest, textStatus, errorThrown){
            alert("error: "+msg.responseText);
            console.log(msg);
            console.log(XMLHttpRequest.status);
            console.log(textStatus);
            console.log(errorThrown);
        });
    }

</script>
<div class="user-profile-all-contents">
    <div class="user-profile">
        <div class="profile-image">
            <?php if ($is_exit_image) :?>
                <img
                src="data:<?php echo $image_type ?>;base64,<?php echo $image_content; ?>"
                width="100px"
                height="auto">
            <?php else :?>
                <p>プロフィールの画像を登録してください。</p>
            <?php endif;?>
        </div>
        <p><?php echo $user_profile['user_name']?></p>
        <p> フォロー : <span id="js-follow"> <?php echo $follow_num?></span></p>
        <p>フォロワー : <span id="js-follower"> <?php echo $follower_num?></span></p>
        <?php if (!$is_yourself) :?>
            <form action="#" method="post">
                <input
                type="hidden"
                id="js-current-user-id"
                value="<?= $current_user_id ?>">
                <input
                type="hidden"
                id="js-profile-user-id"
                value="<?= $profile_user_id ?>">
                <button
                id="js-submit-btn"
                class="display-follow-button"
                type="button"
                value="doFollow"
                onclick="followUser()">
                <?php echo $follow_button_text ?>
                </button>
            </form>
        <?php endif;?>
    </div>

    <?php if ($is_yourself) :?>
        <div class="setting-profile-contents">
            <form method="post" enctype="multipart/form-data">
                <div class="form-image">
                    <?php if (isset($result['image']) && $result['image'] === 'type') :?>
                        <p>*写真は「.gif」、「.jpg」、「.png」の画像を指定してください</p>
                    <?php endif; ?>
                    <label>画像を選択:</label>
                    <input type="file" name="image">
                </div>
                <div class="form-self-introduction">
                    <label for="self-intro">自己紹介:</label>
                    <input
                    type="text"
                    id="self-intro"
                    name="self-intro"
                    maxlength="30px"
                    value="こんにちは"
                    size="32px">
                </div>
                <div class="birthday">
                    <label for="birthday">誕生日:</label>
                    <input
                    type="date"
                    id="birthday"
                    value=""
                    name="birthday" >
                </div>
                <button
                id="submit-btn"
                type="submit"
                class="btn">保存</button>
            </form>
        </div>
    <?php endif;?>
</div>

<div class=user-tweets-contents>
        <?php if (empty($user_posts)) :
            echo "あなたはまだ投稿していません。";?>
        <?php else :?>
            <div id="js-posts" class="user-posts">
                <?php include(__DIR__ . '/component/user_posts.php')?>
            </div>
        <?php endif;?>
    </div>