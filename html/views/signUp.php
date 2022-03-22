<?php

use Classes\SignUp;

$regexp_em = '/^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/';
$regexp_pw = '/^(?=.*[A-Z])(?=.*[.?\/-])[a-zA-Z0-9.?\/-]{8,24}$/';
fun_require_unlogined_session();

if (isset($_POST['submit'])) {
    $username = (string)fun_h($_POST['username']);
    $password = (string)fun_h($_POST['password']);
    $email = (string)fun_h($_POST['email']);
    $created_date = (string)fun_h($_POST['created_date']);
    $sign_up_db = new SignUp($username, $password, $email, $created_date);
    $error = $sign_up_db -> isCheckCondition();
    if (!$error) {
        $sign_up_db ->resultSignUp();
    }
}
?>

<div class="signUp-all-contents">
    <div class="sns-contents">
        <h2>SNSアカウントで会員登録</h2>
    </div>
    <div class="signup-contents">
        <h2>会員登録</h2>
        <div class="signup-main">
            <form action="" method=POST>
                <?php if ($error['invalid']) :?>
                    <p class="errMsg">
                        <?php echo $error['invalid'];?>
                    </p>
                <?php endif;?>
                <div class="box-setting">
                    <p class="require-pos">
                        <label for="input_username">ユーザ名:</label>
                        <span class="require">必須</span>
                    </p>
                    <?php if ($error['user']) :?>
                        <p class="errMsg">
                            <?php echo $error['user'];?>
                        </p>
                    <?php endif;?>
                    <input
                    type="text"
                    id="input_username"
                    name="username"
                    placeholder="ユーザー名を入力してください"
                    spellcheck="true"
                    value= "<?php print($username)?>">
                </div>
                <div class="box-setting">
                    <p class="require-pos">
                        <label for="input_email">メールアドレス:</label>
                        <span class="require">必須</span>
                    </p>
                    <?php if ($error['email']) :?>
                        <p class="errMsg">
                            <?php echo $error['email'];?>
                        </p>
                    <?php endif;?>
                    <input
                    type="email"
                    id="input_email"
                    name="email"
                    placeholder="メールアドレスを入力してください">
                </div>
                <div class="box-setting">
                    <p class="require-pos">
                        <label for="inputPassword">パスワード</label>
                        <span class="require">必須</span>
                    </p>
                    <?php if ($error['password']) :?>
                        <p class="errMsg">
                            <?php echo $error['password'];?>
                        </p>
                    <?php endif;?>
                    <div class="password-box">
                        <input
                        type="password"
                        id="inputPassword"
                        name="password"
                        placeholder="パスワードを入力して下さい">
                        <i id="eye-icon"class="fas fa-eye"></i>
                    </div>
                    <p>条件:大文字、小文字、数字、記号のすべてを最低一文字は使用して下さい</p>
                    <p>パスワードは8文字以上24文字以下で入力してください。使用可能な記号は(. / ? -)です</p>
                </div>
                <input
                type="hidden"
                name="created_date"
                value="<?php echo date('Y-m-d');?>">
                <button name="submit"
                id="signup-btn"
                type = "submit">会員登録</button>
            </form>
        </div>
    </div>
</div>