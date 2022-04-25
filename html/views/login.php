<?php
use Classes\Login;

  //ログイン状態の場合ログイン後のページにリダイレクト
fun_require_unlogined_session();
  // if(!empty($_COOKIE['auto_login'])) {
  // }
    $send = filter_input(INPUT_GET, 'send', FILTER_SANITIZE_STRING);
if (isset($send)) {
    //メールアドレスまたはパスワードが送信されて来なかった場合
    $is_pass = true;
    $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_GET, 'password', FILTER_SANITIZE_STRING);
    if (empty($email)) {
        $message_email = "メールアドレスを入力してください。";
        $is_pass = false;
    }
    if (empty($password)) {
        $message_pw = "パスワードを入力してください。";
        $is_pass = false;
    }
    //メールアドレスとパスワードが送信されて来た場合
    if ($is_pass) {
        //post送信されてきたメールアドレスがデータベースにあ検索
        $get_login= new Login($email, $password);
        $get_login->login();
    }
}
?>
<div class="login-all-contents">
  <h2>ログイン</h2>
  <div class="v_line_fix"></div>
  <div class="login-box">
    <div class="sns-contents">
      <h3>SNSアカウントでログイン</h3>
      <div class="github-auth"><a href="/githubauth/login.php"><i class="fa-brands fa-github"></i>githubでログイン</a></div>
    </div>
    <div class="login-contents">
      <?php if (isset($message)) :?>
      <div class="message"><?php echo $message;?></div>
      <?php endif;?>

      <form method=POST>
        <?php if (isset($message_email)) :?>
        <div class="errMsg"><?php echo $message_email;?></div>
        <?php endif;?>
        <input id="input_email" class="login-form-input-email" name="email" type="text" placeholder="メールアドレスを入力して下さい">
        <?php if (isset($message_pw)) :?>
        <div class="errMsg"><?php echo $message_pw;?></div>
        <?php endif;?>
        <div id="pwBox">
          <input id="js-input-password" class="login-form-input-pw" name="password" type="password"
            placeholder="パスワードを入力して下さい">
          <i id="eye-icon" class="fas fa-eye"></i>
        </div>
        <!-- <div>
            <input id="save" type="checkbox" name="save" value="on">
            <label for="save">次回から自動でログインする</label>
            </div> -->
        <button name="send" class="login-btn">ログイン</button>
      </form>
    </div>
  </div>
</div>
