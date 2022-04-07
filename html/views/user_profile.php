<?php

use Classes\Image\UsingUpdateInsert;
use Classes\Image\UsingGetImage;
use Classes\User\GetUserInfo;
use Classes\Follow\CheckFollow;
use Classes\Follow\GetNumFollow;

$_SESSION['messageAlert'] ='';
$page_num = filter_input(INPUT_GET, 'page_num', FILTER_SANITIZE_NUMBER_INT);
$page_num = ($page_num ?: 1);
$start_num = ($page_num - 1) * 15;

if (isset($_GET['id'])) {
    $profile_user_id = (string)$_GET['id'];
    $get_user_info = new GetUserInfo($profile_user_id);
    [$user_posts, $max_page]= $get_user_info->getUserPost($start_num);
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
function followUser() {
  let doFollowBtn = $('#js-submit-btn').val();
  let currentId = $('#js-current-user-id').val();
  let profileId = $('#js-profile-user-id').val();
  let $map = {
    "type": doFollowBtn,
    "currentId": currentId,
    "profileId": profileId
  };
  $.ajax({
    type: 'POST',
    url: 'views/component/AjaxFollowProcess.php',
    data: $map,
    dataType: 'json'
  }).done(function(data) {
    $('#js-follow')[0].textContent = data['follow'];
    $('#js-follower')[0].textContent = data['follower'];
    if (data['status']) {
      $('#js-submit-btn').text("フォロー");
    } else {
      $('#js-submit-btn').text("フォロー中");

    }
  }).fail(function(msg, XMLHttpRequest, textStatus, errorThrown) {
    alert("followUser\n error:\n  " + msg.responseText);
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
      <?php if ($is_exit_image) :?> <img class="user_profile_image"
        src="data:<?php echo $image_type ?>;base64,<?php echo $image_content; ?>">
      <?php else :?>
      <p> プロフィールの画像を登録してください。</p>
      <?php endif;?>
    </div>
    <p>
      <?php echo $user_profile['user_name']?> </p>
    <p> フォロー:
      <span id="js-follow">
        <?php echo $follow_num?> </span>
    </p>
    <p> フォロワー: <span id="js-follower"> <?php echo $follower_num?> </span> </p>
    <?php if (!$is_yourself) :?>
    <input type="hidden" id="js-current-user-id" value="<?= $current_user_id ?>">
    <input type="hidden" id="js-profile-user-id" value="<?= $profile_user_id ?>">
    <button id="js-submit-btn" class="display-follow-button" type="button" value="doFollow" onclick="followUser()">
      <?php echo $follow_button_text ?>
    </button>
    <?php endif;?>
  </div>
  <?php if ($is_yourself) :?>
  <div class="setting-profile-contents">
    <button id="js-show-popup">プロフィールの編集</button>
    <div class="popup" id="js-popup">
      <div class="popup-inner">
        <div class="close-btn" id="js-close-btn">
          <i class="fas fa-times"></i>
        </div>
        <div class="preview-content">
          <img id="js-image-preview" class="image-preview">
        </div>
        <form method="post" enctype="multipart/form-data">
          <div class="form-image">
            <?php if (isset($result['image']) && $result['image'] === 'type') :?>
            <p> * 写真は「.gif」、「.jpg」、「.png」 の画像を指定してください</p>
            <?php endif; ?>
            <label class="choice-image-label">
              <input id="js-user-image" type="file" name="image" accept="image/*">画像を選択
            </label>
          </div>
          <div class="user-name">
            <label for="user-name"> ユーザ名:</label>
            <p><input type="text" id="user-name" value="" name="user-name"></p>
          </div>
          <div class="birthday">
            <label for="birthday"> 誕生日:</label>
            <p><input type="date" id="birthday" value="" name="birthday"></p>
          </div>
          <div class="form-self-introduction">
            <label for="self-intro"> 自己紹介:</label>
            <p><input type="text" id="self-intro" class="input-text" name="self-intro" maxlength="30px" value=""></p>
          </div>
          <div class="main-color">
            <label for="main-color"> カラー:</label>
            <p><input type="color" id="main-color" value="" name="main-color"></p>
          </div>
          <button id="submit-btn" type="submit" class="btn"> 保存
          </button>
        </form>
      </div>
      <div class="black-background" id="js-black-bg"></div>
    </div>
  </div>
  <?php endif;?>
</div>
<div class="user-tweets-contents">
  <?php if (empty($user_posts)) :
      echo "あなたはまだ投稿していません。";?>
  <?php else :?>
  <div id="js-posts" class="user-posts">
    <?php include(__DIR__ . '/component/user_posts.php')?>
  </div>
  <?php endif;?>
</div>

<script>
$(() => {
  $('#js-user-image').on('change', function(e) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $("#js-image-preview").attr('src', e.target.result);
      $("#js-image-preview").addClass('image-preview-size');
    }
    reader.readAsDataURL(e.target.files[0]);
  });
});
</script>