<?php
/**
 * データベースにユーザの投稿を保存します。
 *
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes\Post;

use Controller\Pdo;
use Controller\Connect;

class InsertPost extends Connect
{
    /** @var string $user_id */
    private $user_id;
    /** @var string $date_time */
    private $date_time;
    /** @var string $post_text */
    private $post_text;

    /**
     * ユーザのIDとユーザが投稿した内容を取得しています。
     *
     * @param string $user_id
     * @param string $post_text
     */
    public function __construct(string $user_id, string $post_text)
    {
        $this -> user_id = $user_id;
        $this -> post_text = $post_text;
        $this -> date_time = date("Y-m-d H:i:s");
    }

    /**
     * ConnectクラスからconnectDbメソッドを継承してデータベースにデータを保存しています。
     *
     * @return bool
     */
    private function dbInsertTweet():bool
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $query = "INSERT INTO tweet (user_id, date_time, post_text) VALUES (:user_id, :date_time, :post_text)";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(":user_id", $this->user_id, PDO::PARAM_STR);
            $stmt->bindValue(":date_time", $this->date_time, PDO::PARAM_STR);
            $stmt->bindValue(":post_text", $this->post_text, PDO::PARAM_STR);
            $flag = $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー InsertPostDB > dbInsertTweet');
        }
        return $flag;
    }

    /**
     * dbInsertTweetメソッドからあったか無かったかをboolで受け取り処理をします。
     *
     * @return void
     */
    public function checkInsertTweet():void
    {
        $flag = $this -> dbInsertTweet();
        if ($flag) {
            $_SESSION['messageAlert'] = fun_h("ツイートに成功しました。");
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
            return;
        }
        $_SESSION['messageAlert'] = fun_h("ツイートに失敗しました。");
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
        return;
    }
}
