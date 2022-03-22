<?php
print_r($_SESSION);
require_once __DIR__ . '/../function.php';
use Classes\Image\UsingGetImage;

$user_id = (string)$_SESSION['userID'];
if ($user_id) {
    $using_get_image = new UsingGetImage('user_id', $user_id);
    $image = $using_get_image->usingGetImage();
    $image_type=$image['image_type'];
    $image_content=$image['image_content'];
}

if (isset($_SESSION['userID']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();
} else {
    // header("Location: ?page=login");
    // exit();
}
?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../assets/css/style.min.css"> -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script type="text/javascript"src="../assets/js/index.js"></script>
    <title>twitter</title>
</head>

<body>
    <header>
        <div id="header">
            <?php if (!empty($_SESSION['messageAlert'])) :?>
                <div class="msg-alert" id="msgAlert">
                    <script type="text/javascript">
                        alert_animation();
                    </script>
                    <?php echo $_SESSION['messageAlert'];
                        $_SESSION['messageAlert'] = '';
                    ?>
                </div>
            <?php endif; ?>
            <div class="header-logo">
                <a href="/">
                    <h1>twitter</h1>
                </a>
            </div>
            <div class="header-nav">
                <nav>
                    <div class="header-item"><a href="?page=your_timeline">あなたのタイムライン</a>
                    </div>
                    <div class="header-item">
                        <a href="?page=profiles&id=<?php echo $_SESSION['userID']?>">あなたのプロフィール</a>
                    </div>
                </nav>
            </div>
            <?php if (isset($_SESSION['userID'])) :?>
                <div>
                    <p>
                        <img
                        src="data:<?php echo $image_type ?>;base64,<?php echo $image_content; ?>"
                        class="user-top-image">
                        <?php print($_SESSION['username'])?>さん
                    </p>
                </div>
            <?php endif;?>
            <div class="header-signup">
                <?php if (!isset($_SESSION['userID'])) :?>
                    <a href="?page=signUp" class="btn btn-flat"><span>会員登録</span></a>
                <?php endif; ?>
            </div>
            <div class="header-right">
                <div class="header-login">
                    <?php if (isset($_SESSION['userID'])) :?>
                        <a href="?page=logout" alt="ログアウトボタン">
                            <i class="fas fa-door-closed"></i>
                            <p>ログアウト</p>
                        </a>
                    <?php else :?>
                        <a href="?page=login" alt="ログインボタン">
                            <i class="fas fa-door-open"></i>
                            <p>ログイン</p>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="header-menu">
                    <div class="menu-bar">
                        <div class="hamburger-menu">
                            <span class="hamburger-menu__line"></span>
                        </div>
                        <p>メニュー</p>
                    </div>
                    <nav class="nav-sp">
                        <ul>
                            <li>
                                <a href="?page=profiles">あなたのタイムライン</a>
                            </li>
                            <li>
                                <a href="?page=yourTweets">あなたのツイート</a>
                            </li>
                            <li>
                                <a href="#">あなたのプロフィール</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>


