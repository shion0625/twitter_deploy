<?php

use Classes\Image\UsingUpdateInsert;
use Classes\Image\UsingGetImage;
use Classes\Follow\CheckFollow;
use Classes\Follow\GetNumFollow;
use Classes\User\UserInfo;

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$self_intro = filter_input(INPUT_POST, 'self-intro', FILTER_SANITIZE_STRING);
$birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_STRING);
$main_color = filter_input(INPUT_POST, 'main-color', FILTER_SANITIZE_STRING);

$page_num = filter_input(INPUT_POST, 'page_num', FILTER_SANITIZE_NUMBER_INT);
$page_num = ($page_num ?: 1);
$start_num = ($page_num - 1) * 15;

$profile_user_id;
$current_user_id = $_SESSION['userID'];
$get_user_info;
$url = $_SERVER['REQUEST_URI'];

if (isset($_GET['id'])) {
    $profile_user_id = (string)$_GET['id'];
    $get_user_info = new UserInfo($profile_user_id);
    [$user_posts, $max_page]= $get_user_info->getUserPost($start_num);
    $user_profile = $get_user_info->getUserProfile();
    $get_image = new UsingGetImage('user_id', $profile_user_id);
    $image = $get_image->usingGetImage();
}

$is_yourself = $current_user_id == $profile_user_id;

if (!isset($_SESSION['userID'])) {
    echo '<script>', 'alert_animation("あなたのユーザIDが設定されていません。ログインしてください。");', '</script>';
    header('Location: ?page=');
    exit();
}
$is_exit_image = false;
if (!empty($image)) {
    $is_exit_image = true;
    $image_type = $image['image_type'];
    $image_content = $image['image_content'];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && $is_yourself) {
    if (!$username) {
        $_SESSION['messageAlert'] = "ユーザ名が入力されていません。";
        header("Location: {$url}");
        exit();
      }
      $username = trim($username);
      if(strlen($username) > 30){
          $_SESSION['messageAlert'] = "ユーザ名の文字数が限界を超過しています。";
          header("Location: {$url}");
          exit();
      }
    if(!empty($self_intro)){
      $trimmed = trim($self_intro);
      $self_intro = $trimmed;
      if(strlen($self_intro) > 255){
          $_SESSION['messageAlert'] = "自己紹介の文字数が限界を超過しています。";
          header("Location: {$url}");
          exit();
      }
    }
    if (!empty($_FILES['image']['name'])) {
      if($_FILES['image']['type']){}
        $using_insert_update = new UsingUpdateInsert($is_exit_image);
        $resultImage = $using_insert_update->actionImage();
    }
      if (empty($birthday)) {
          $birthday =null;
      }
      $resultUser = $get_user_info->updateUserInfo(trim($username), $birthday, trim($self_intro), $main_color);
      if($resultUser){
        $_SESSION['messageAlert'] = "ユーザ情報を更新しました。";
        header("Location: {$url}");
        exit();
      }else{
        $_SESSION['messageAlert'] = "ユーザ情報の変更に失敗しました。";
        header("location: {$url}");
        exit();
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

    require(__DIR__ . getenv("PASS_DEPLOY"). '/header.php');
  ?>

<div class="user-profile-all-contents">
  <div class="user-profile">
    <div class="profile-image">
      <?php if ($is_yourself) :?>
      <?php if ($is_exit_image) :?>
      <img class="user_profile_image" src="data:<?php echo $image_type ?>;base64,<?php echo $image_content; ?>"
        style="border-color:<?php echo fun_h($user_profile['color']);?>; background-color: <?php echo fun_h($user_profile['color']);?>;">
      <?php else :?>
      <p class="common-error-msg">プロフィールの画像を登録してください。</p>
      <?php endif;?>
      <?php endif;?>
    </div>
    <p class=" profile-username"><?php echo fun_h($user_profile['user_name'])?></p>
    <div class="profile-date">
      <span class="profile-birthday"> <i class="fa-solid fa-cake-candles"></i>
        <?php echo fun_h($user_profile['birthday']);?></span>
      <span class="profile-created-date"> <i class="fa-solid fa-calendar-days"></i>
        <?php echo fun_h($user_profile['created_date']);?>
      </span>
    </div>

    <?php if ($user_profile['self_introduction']) :?>
    <div class="profile-self-introduction"><?php echo fun_h($user_profile['self_introduction'])?></div>
    <?php endif;?>
    <div class="follow-container">
      <div class="follow">フォロー: <span id="js-follow"><?php echo fun_h($follow_num)?></span></div>
      <div class="follower">フォロワー: <span id="js-follower"><?php echo fun_h($follower_num)?></span></div>
    </div>
    <?php if ($user_profile['created_date'] || $user_profile['birthday']) :?>
    <?php endif;?>
    <?php if (!$is_yourself) :?>
    <input type="hidden" id="js-current-user-id" value="<?= $current_user_id ?>">
    <input type="hidden" id="js-profile-user-id" value="<?= $profile_user_id ?>">
    <button id="js-submit-btn" class="display-follow-button" type="button" value="doFollow" onclick="followUser()">
      <?php echo fun_h($follow_button_text) ?>
    </button>
    <?php endif;?>
    <?php if ($is_yourself) :?>
    <div class="setting-profile-contents">
      <button id="js-show-popup" class="setting-profile-btn btn120">プロフィールの編集</button>
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
            <div class="username">
              <label for="username"> ユーザ名:</label>
              <p><input type="text" value="<?php echo fun_h($user_profile['user_name']);?>" name="username"
                  maxlength="30">
              </p>
            </div>
            <div class="birthday">
              <label for="birthday"> 誕生日:</label>
              <p><input type="date" value="<?php echo fun_h($user_profile['birthday']);?>" name="birthday"></p>
            </div>
            <div class="form-self-introduction">
              <label for="self-intro"> 自己紹介:</label>
              <p><textarea class="input-self-intro" name="self-intro"><?php echo fun_h($user_profile['self_introduction']);?>
                </textarea></p>
            </div>
            <div class="main-color">
              <label for="main-color"> カラー:</label>
              <p><input id="js-main-color" type="color" value="<?php echo fun_h($user_profile['color']);?>"
                  name="main-color">
              </p>
            </div>
            <button id="submit-btn" class="btn120 btn-gradient" type="submit" class="btn"> 保存
            </button>
          </form>
        </div>
        <div class="black-background" id="js-black-bg"></div>
      </div>
    </div>
    <?php endif;?>
  </div>
  <div class="user-tweets-contents">
    <?php if (empty($user_posts)) :?>
    <p class="common-error-msg"> <?php echo "あなたはまだ投稿していません。";?> </p>
    <?php else :?>
    <div id="js-posts" class="user-posts">
      <?php include(__DIR__ . '/component/user_posts.php')?>
    </div>
    <?php endif;?>
  </div>
</div>

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
    url: "<?php echo getenv('PASS_DEPLOY');?>/views/component/AjaxFollowProcess.php",
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
