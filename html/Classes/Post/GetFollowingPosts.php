<?php
/**
 * データベースからインスタンス化した時のユーザIDno情報を返します。
 *
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes\Post;

use Controller\Pdo;
use Controller\Connect;

class GetFollowingPosts extends Connect
{
    private $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    private function getFollowingUser()
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $query = "SELECT followed_id FROM following WHERE follow_id=:follow_id";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(":follow_id", $this->user_id);
            $stmt->execute();
            $following_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー getFollowingUser');
        }
        return $following_users;
    }

    public function getFollowPost(int $start_post)
    {
        parent::__construct();
        $dbh = $this->connectDb();
        $following_users = $this->getFollowingUser();
        if (is_array($following_users) && empty($following_users)) {
            return ['',''];
        }
        $whereClause="";
        foreach ($following_users as $user) {
            if ($whereClause != "") {
                $whereClause.= " OR";
            }
            if ($whereClause == "") {
                $whereClause = " WHERE";
            }
            $whereClause .= " u.email_encode = '".$user['followed_id']."'";
        }
        try {
            $query = 'SELECT u.user_name,u.color, t.*, i.image_type, i.image_content
            FROM users AS u INNER JOIN tweet AS t ON u.email_encode = t.user_id
            LEFT OUTER JOIN user_image AS i ON t.user_id = i.user_id
            '.$whereClause.' ORDER BY t.date_time DESC LIMIT :post_num ,15';
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(':post_num', $start_post, PDO::PARAM_INT);
            $stmt->execute();
            $following_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt -> rowCount();
            $max_page = ceil(($count+1)/15);
        } catch (PDOException $e) {
            exit('データベースエラー getFollowPost');
        }
        return array($following_posts, $max_page);
    }
}
