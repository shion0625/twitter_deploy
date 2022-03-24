<?php
/**
 *emailから登録されてるユーザが判別します。
 *
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes;

use Controller\Pdo;
use Controller\Connect;

class Login extends Connect
{
    /** @var string $email */
    private $email;
    /** @var string  $password*/
    private $password;

    /**
     * 文字列型でメールアドレス、パスワードを受け取ります。
     *
     * @param string $email
     * @param string $password
     */
    public function __construct(string $email, string $password)
    {
        $this-> email = $email;
        $this->  password = $password;
    }

    /**
     * データベースからemailが一致するデータベースの情報を取得しています。
     *
     * @return mixed $login_information
     * mixed(object)
     * int id:
     * string user_name
     * string email
     * string email_encode
     */

    private function getLoginInfo()
    {
        /**
         * Connect classのコンストラクタ
         */
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $login_query = "SELECT * FROM users WHERE email=:email";
            $stmt = $dbh->prepare($login_query);
            $stmt->bindValue(":email", $this-> email, PDO::PARAM_STR);
            $stmt->execute();
            $login_information = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            exit($e.'データベースエラー LoginDB > getLoginInfo');
        }
        return $login_information;
    }

    /**
     * 検索したユーザー名に対してパスワードが正しいかを検証
     *
     * @return void
     */
    public function login():void
    {
        $login_info = $this->getLoginInfo();
        if (!password_verify($this->password, $login_info['password'])) {
            $_SESSION['messageAlert'] = fun_h('ログインに失敗しました。');
            header('Location: /?page=login');
            exit();
        } else {
            session_regenerate_id(true); //セッションidを再発行
            //セッションにログイン情報を登録
            $_SESSION['userID'] = $login_info['email_encode'];
            $_SESSION['username']=$login_info['user_name'];
            $_SESSION['messageAlert'] = fun_h('ログインに成功しました。');
            $_SESSION['time'] = time();
            header('Location: /');//ログイン後のページにリダイレクト
            exit();
        }
        return;
    }
}
