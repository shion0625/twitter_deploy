<?php
/**
 * データベースに保存されている一番新しい投稿を取得します。
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes\Post;

use Controller\Pdo;
use Controller\Connect;

class GetNewestPost extends Connect
{
    /**
     *データベースに保存されているすべての投稿を取得します。
     *投稿者のユーザ名、投稿ID、投稿者のID、投稿テキスト、投稿時間、その投稿しゃのトップ画のタイプとコンテンツ
     *
     * @return mixed $post
     */
    public function getNewestPost()
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $query = "SELECT u.user_name, t.*, i.image_type, i.image_content
            FROM users AS u INNER JOIN tweet AS t ON u.email_encode = t.user_id LEFT OUTER JOIN user_image AS i
            ON t.user_id = i.user_id WHERE post_id = (SELECT MAX(post_id)FROM tweet)";
            $stmt = $dbh->prepare($query);
            $stmt->execute();
            $post = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー getNewestPost');
        }
        return $post;
    }
}
