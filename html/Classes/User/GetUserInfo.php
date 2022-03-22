<?php
/**
 * データベースからインスタンス化した時のユーザIDno情報を返します。
 *
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes\User;

use Controller\Pdo;
use Controller\Connect;

class GetUserInfo extends Connect
{
    /** @var string $user_id */
    private $user_id;

    public function __construct(string $user_id)
    {
        $this-> user_id= $user_id;
    }

    public function getUserPost()
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $query = "SELECT u.user_name, t.*, i.image_type, i.image_content
            FROM users AS u
            INNER JOIN tweet AS t ON u.email_encode = t.user_id
            LEFT OUTER JOIN user_image AS i ON t.user_id = i.user_id
            WHERE t.user_id=:user_id ORDER BY t.date_time DESC";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue("user_id", $this->user_id);
            $stmt->execute();
            $user_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー getUserPost');
        }
        return $user_posts;
    }

    public function getUserProfile()
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $query = "SELECT u.user_name, u.self_introduction, u.birthday,u.created_date, i.image_type, i.image_content
            FROM users AS u LEFT OUTER JOIN user_image AS i ON u.email_encode = i.user_id
            WHERE u.email_encode=:user_id LIMIT 1";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue("user_id", $this->user_id);
            $stmt->execute();
            $user_profile = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー getUserProfile');
        }
        return $user_profile;
    }
}
