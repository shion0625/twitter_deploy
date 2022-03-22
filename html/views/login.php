<?php
use Classes\Login;

  //ログイン状態の場合ログイン後のページにリダイレクト
fun_require_unlogined_session();
  // if(!empty($_COOKIE['auto_login'])) {

  // }
if (!empty($_POST) && isset($_POST['send'])) {
    //メールアドレスまたはパスワードが送信されて来なかった場合
    $is_pass = true;
    $email = (string) fun_h($_POST['email']);
    $password= (string) fun_h($_POST['password']);
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
    <div class="login-box">
    <div class="sns-contents">
        <h3>SNSアカウントでログイン</h3>
    </div>
    <div class="login-contents">
        <div class="message"><?php echo $message;?></div>
        <form method=POST>
            <div class="message"><?php echo $message_email;?></div>
            <input
            id="input_email"
            class="login-form-input-email"
            name="email"
            type="text"
            placeholder="メールアドレスを入力して下さい">
            <div id="pwBox">
                <div class="message"><?php echo $message_pw;?></div>
                <input
                id="input_password"
                class="login-form-input-pw"
                name="password"
                type="password"
                placeholder="パスワードを入力して下さい">
                <i id="eye-icon"class="fas fa-eye"></i>
            </div>
            <!-- <div>
            <input id="save" type="checkbox" name="save" value="on">
            <label for="save">次回から自動でログインする</label>
            </div> -->
            <button
            name="send"
            class="login-btn">ログイン</button>
    </form>
    </div>
</div>
</div>
