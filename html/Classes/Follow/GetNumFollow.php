<?php
/**
 * データベースからフォローしている数を受け取ります。
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes\Follow;

use Controller\Pdo;
use Controller\Connect;

class GetNumFollow extends Connect
{
    private $user_id;

    public function __construct($user_id)
    {
        $this -> user_id = $user_id;
    }

    public function numFollow()
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $query = "SELECT COUNT(*) FROM following WHERE follow_id=:user_id";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(":user_id", $this->user_id);
            $stmt->execute();
            $num_follow = $stmt -> fetchColumn();
        } catch (PDOException $e) {
            $e->getMessage() . PHP_EOL;//エラーが出たときの処理
            exit('データベースエラー numFollow');
        }
        return $num_follow;
    }

    public function numFollower()
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $query = "SELECT COUNT(*) FROM following WHERE followed_id=:user_id";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(":user_id", $this->user_id);
            $stmt->execute();
            $num_follow = $stmt -> fetchColumn();
        } catch (PDOException $e) {
            $e->getMessage() . PHP_EOL;//エラーが出たときの処理
            exit('データベースエラー numFollower');
        }
        return $num_follow;
    }
}
