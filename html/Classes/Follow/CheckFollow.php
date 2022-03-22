<?php
/**
 * データベースにユーザの投稿を保存します。
 *
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */
namespace Classes\Follow;

use Controller\Pdo;
use Controller\Connect;

class CheckFollow extends Connect
{
    private $follow_user;
    private $followed_user;

    public function __construct(string $follow_user, string $followed_user)
    {
        $this->follow_user = $follow_user;
        $this->followed_user = $followed_user;
    }
    public function isCheckFollow()
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $sql = "SELECT follow_id,followed_id
            FROM following
            WHERE follow_id = :follow_id AND followed_id = :followed_id LIMIT 1";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(":follow_id", $this->follow_user);
            $stmt->bindValue(":followed_id", $this->followed_user);
            $stmt->execute();
            $flag = $stmt->fetch();
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー checkFollow');
        }
        return $flag;
    }
}
