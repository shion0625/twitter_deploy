<?php
/**
 *ユーザの登録を行います。
 *
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes;

use Controller\Pdo;
use Controller\Connect;

class SignUp extends Connect
{
    /** @param string $username*/
    private $username;
    /** @param string $password*/
    private $password;
    /** @param string $email */
    private $email;
    /** @param string $created_date */
    private $created_date;

    /**
     * 文字列型でユーザの名前、パスワード、メールアドレスを受け取ります。
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $created_date
     */
    public function __construct(string $username, string $password, string $email, string $created_date)
    {
        $this -> username = $username;
        $this -> password = $password;
        $this -> email = $email;
        $this -> created_date = $created_date;
    }

    /**
     * 入力された内容が正しい家判別します。
     *
     * @return mixed
     */
    public function isCheckCondition()
    {
        $regexp_em = '/^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/';
        $regexp_pw = '/^(?=.*[A-Z])(?=.*[.?\/-])[a-zA-Z0-9.?\/-]{8,24}$/';
        if (empty($this->username)) {
            $error['user'] = "ユーザ名を入力してください。";
        }
        if (empty($this->email)) {
            $error['email'] = "メールアドレスを入力してください。";
        } elseif (!preg_match($regexp_em, $this->email)) {
            $error['email'] = "メールアドレスを正しく入力してください。";
        }
        if (empty($this->password)) {
            $error['password'] = "パスワードを入力してください。";
        } elseif (!preg_match($regexp_pw, $this->password)) {
            $error['password'] = "パスワードを正しく入力してください。";
        }
        return $error;
    }

    /**
     * データベースの中に入力されたメールアドレスと一致するものがあるか検索しています。
     *
     * @return mixed
     */
    private function signUpCheck()
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $signup_query = "SELECT * FROM users WHERE email=:email";
            $stmt = $dbh->prepare($signup_query);
            $stmt->bindValue(":email", $this->email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            exit('データベースエラー signUpCheck');
        }
        return $result;
    }

    /**
     * メールアドレスがあった場合はエラーを出します。
     *
     * @return void
     */
    private function isExitEmail():void
    {
        $is_email=$this->signUpCheck();
        echo $is_email;
        if ($is_email) {
            $message_alert = "そのメールアドレスは使用されています。";
            $_SESSION['messageAlert'] = fun_h($message_alert);
            header("Location: /?page=signUp");
            exit();
        }
    }

    /**
     * 入力された情報をデータベースに保存しています。
     * 成功と失敗を返します。
     * @return boolean
     */
    private function insertSignUp():bool
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $query = "INSERT INTO users (user_name, password, email, email_encode, created_date)
            VALUES (:username, :password, :email, :email_encode, :created_date)";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(":username", $this->username);
            $stmt->bindValue(":password", password_hash($this->password, PASSWORD_DEFAULT));
            $stmt->bindValue(":email", $this->email);
            $stmt->bindValue(":email_encode", password_hash($this->email, PASSWORD_DEFAULT));
            $stmt->bindValue(":created_date", $this->created_date);
            $flag = $stmt->execute();
        } catch (PDOException $e) {
            $e->getMessage() . PHP_EOL;//エラーが出たときの処理
            exit('データベースエラー insertSignUp');
        }
        return $flag;
    }

    /**
     *  ユーザの登録に成功した場合と失敗した場合で分岐させています。
     * @return void
     */
    public function resultSignUp():void
    {
        $this->isExitEmail();
        $flag = $this->insertSignUp();
        if ($flag) {
            $message_alert = "ユーザの登録に成功しました。";
            $_SESSION['messageAlert'] = fun_h($message_alert);
            header('Location: /?page=login');
            exit();
            return;
        }
        $message_alert = "ユーザの登録に失敗しました。もう一度お願いします。";
        $_SESSION['messageAlert'] = fun_h($message_alert);
        header("Location: /?page=signUp");
        exit();
        return;
    }
}
