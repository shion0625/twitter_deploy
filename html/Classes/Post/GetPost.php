<?php
/**
 *
 *
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes\Post;

use Controller\Pdo;
use Controller\Connect;

class GetPost extends Connect
{
    /** @var string $post_id */
    private $post_id;

    /**
     * ユーザがポストメソッドで送信した投稿のIDを取得しています。
     *
     * @param string $post_id
     */

    public function __construct(string $post_id)
    {
        $this->post_id = $post_id;
    }

    public function getPost()
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $query = "SELECT u.user_name, t.* FROM tweet t INNER JOIN users u ON t.user_id = u.email_encode WHERE t.post_id=:post_id";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(":post_id", $this->post_id);
            $stmt->execute();
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            /**配列の0番目に投稿の情報が入っている */
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー getPost');
        }
        return $post;
    }
}
