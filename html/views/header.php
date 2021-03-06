<?php
use Classes\Image\UsingGetImage;
use Classes\User\UserInfo;

$user_id = $_SESSION['userID'] ?? null;

if ($user_id) {
    $using_get_image = new UsingGetImage('user_id', $user_id);
    $image = $using_get_image->usingGetImage();
    $current_user_info = new UserInfo($user_id);
    $current_profile = $current_user_info->getUserProfile();
    if (isset($image['image_type']) && isset($image['image_content'])) {
        $image_type=$image['image_type'];
        $image_content=$image['image_content'];
        $is_exist_image = true;
    } else {
        $is_exist_image=false;
    }
}

if (isset($_SESSION['userID']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();
} else {
    // header("Location: ?page=login");
    // exit();
}
$deploy_pass = getenv("PASS_DEPLOY");
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?php echo getenv('PASS_DEPLOY');?>/assets/css/style.min.css" rel="stylesheet">

  <script src="https://kit.fontawesome.com/f5a505d08a.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script type="text/javascript" src="<?php echo getenv('PASS_DEPLOY');?>/assets/js/index.js"></script>
  <title>shiontter</title>
</head>

<body>
  <header>
    <div id="header">
      <?php if (isset($_SESSION['messageAlert'])&&($_SESSION['messageAlert'])):?>
      <div class="msg-alert">
        <script type="text/javascript">
        alert_animation();
        </script>
        <?php
        echo fun_h($_SESSION['messageAlert']);
        $_SESSION['messageAlert'] = '';
        ?>
      </div>
      <?php endif;?>
      <div class="msg-alert" id="js-msg-alert"></div>
      <div class="header-logo">
        <a href="/">
          <h1>shiontter</h1>
        </a>
      </div>
      <?php if (isset($_SESSION['userID'])) :?>
      <nav class="header-menu-tab-none">
        <div class=" header-item">
          <a href="?page=your_timeline" style="color: <?php echo fun_h($current_profile['color']);?>;">??????????????????????????????</a>
        </div>

        <div class="header-item">
          <a href="?page=profiles&id=<?php echo $_SESSION['userID']?>"
            style="color: <?php echo fun_h($current_profile['color']);?>;">??????????????????????????????</a>
        </div>
      </nav>
      <?php endif;?>
      <?php if (isset($_SESSION['userID'])) :?>
      <div>
        <?php if ($is_exist_image) :?>
        <img src="data:<?php echo $image_type ?>;base64,<?php echo $image_content; ?>" class="user-top-image"
          style=" border-color: <?php echo fun_h($current_profile['color']);?>;background-color: <?php echo fun_h($current_profile['color']);?>;">
        <?php endif;?>
        <p><?php echo fun_h($current_profile['user_name'])?>??????</p>
      </div>
      <?php endif;?>
      <div class="header-signup header-menu-tab-none">
        <?php if (!isset($_SESSION['userID'])) :?>
        <a href="?page=signUp" class="btn120 btn-flat"><span>????????????</span></a>
        <?php endif; ?>
      </div>
      <div class="header-right">
        <div class="header-login header-menu-tab-none">
          <?php if (isset($_SESSION['userID'])) :?>
          <a href="?page=logout" alt="????????????????????????">
            <i class="fas fa-door-closed"></i>
            <p>???????????????</p>
          </a>
          <?php else :?>
          <a href="?page=login" alt="?????????????????????">
            <i class="fas fa-door-open"></i>
            <p>????????????</p>
          </a>
          <?php endif; ?>
        </div>
        <div class="header-menu">
          <div class="menu-bar">
            <div class="hamburger-menu">
              <span class="hamburger-menu__line"></span>
            </div>
            <p>????????????</p>
          </div>
          <menu class="nav-sp none">
            <ul>
              <?php if (isset($_SESSION['userID'])) :?>
              <li class="li-item">
                <a href="?page=your_timeline"><i class="fa-solid fa-message"></i>??????????????????????????????</a>
              </li>
              <hr color="black">
              <li class="li-item">
                <a href="?page=profiles&id=<?php echo $_SESSION['userID']?>"><i
                    class="fa-solid fa-address-card"></i>??????????????????????????????</a>
              </li>
              <hr color="black">
              <li class="li-item">
                <a href="?page=delete_user">????????????????????????</a>
              </li>
              <hr color="black">
              <?php else: ?>
              <li class="li-item">
                <a href="?page=signUp"><i class="fa-solid fa-registered"></i>????????????</a>
              </li>
              <hr color="black">
              <?php endif; ?>
              <li class="li-item">
                <?php if (isset($_SESSION['userID'])) :?>
                <a href="?page=logout" alt="????????????????????????">
                  <p><i class="fas fa-door-closed"></i>???????????????</p>
                </a>
                <?php else :?>
                <a href="?page=login" alt="?????????????????????">
                  <p><i class="fas fa-door-open"></i>>????????????</p>
                </a>
                <?php endif; ?>
              </li>
              <hr color="black">
            </ul>
          </menu>
        </div>
      </div>
    </div>
  </header>
  <main>
