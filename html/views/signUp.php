<?php

use Classes\SignUp;

$regexp_em = '/^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/';
$regexp_pw = '/^(?=.*[A-Z])(?=.*[.?\/-])[a-zA-Z0-9.?\/-]{8,24}$/';
fun_require_unlogined_session();

$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_STRING);

if (isset($submit)) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $created_date = filter_input(INPUT_POST, 'created_date', FILTER_SANITIZE_STRING);
    $sign_up_db = new SignUp($username, $password, $email, $created_date);
    $error = $sign_up_db -> isCheckCondition();
    if (!$error) {
        $sign_up_db ->resultSignUp();
    }
}
    require(__DIR__ . '/header.php');
?>

<div class="signUp-all-contents">
  <h2>会員登録</h2>
  <div class="v_line_fix"></div>
  <div class="signUp-box">
    <div class="sns-contents">
      <h3>SNSアカウントで会員登録</h3>
      <div class="github-auth">
        <a href="/githubauth/signUp.php"><i class="fa-brands fa-github"></i>githubで会員登録</a>
      </div>
    </div>
    <div class="signUp-contents">
      <div class="signUp-main">
        <form action="" method=POST>
          <?php if (isset($error['invalid'])) :?>
          <p class="errMsg">
            <?php echo $error['invalid'];?>
          </p>
          <?php endif;?>
          <div class="box-setting">
            <p class="require-pos">
              <label for="input_username">ユーザ名:</label>
              <span class="require">必須</span>
            </p>
            <?php if (isset($error['user'])) :?>
            <p class="errMsg">
              <?php echo $error['user'];?>
            </p>
            <?php endif;?>
            <input type="text" id="input_username" class="input-username" name="username" placeholder="ユーザー名を入力してください"
              spellcheck="true">
          </div>
          <div class="box-setting">
            <p class="require-pos">
              <label for="input_email">メールアドレス:</label>
              <span class="require">必須</span>
            </p>
            <?php if (isset($error['email'])) :?>
            <p class="errMsg">
              <?php echo $error['email'];?>
            </p>
            <?php endif;?>
            <input type="email" id="input_email" class="input-email" name="email" placeholder="メールアドレスを入力してください">
          </div>
          <div class="box-setting">
            <p class="require-pos">
              <label for="inputPassword">パスワード</label>
              <span class="require">必須</span>
            </p>
            <?php if (isset($error['password'])) :?>
            <p class="errMsg">
              <?php echo $error['password'];?>
            </p>
            <?php endif;?>
            <div class="password-box">
              <input type="password" id="js-input-password" class="input-password" name="password"
                placeholder="パスワードを入力して下さい">
              <i id="eye-icon" class="fas fa-eye"></i>
            </div>
            <p>条件:大文字、小文字、数字、記号のすべてを最低一文字は使用して下さい</p>
            <p>パスワードは8文字以上24文字以下で入力してください。使用可能な記号は(. / ? -)です</p>
          </div>
          <input type="hidden" name="created_date" value="<?php echo date('Y-m-d');?>">
          <button name="submit" class="signup-btn" type="submit">会員登録</button>
        </form>
      </div>
    </div>
  </div>
</div>
