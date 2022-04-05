<?php
/**
 * post_idからDBに保存されている投稿を削除します。
 *
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes\Post;

use Controller\Connect;

class DeletePost extends Connect
{
    /**
     * @var string $post_id
     * */
    private $post_id;

    public function __construct($post_id)
    {
        $this->post_id = $post_id;
    }

    public function deletePost()
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $query_delete ="DELETE FROM tweet WHERE post_id=:post_id";
            $post_id=(int)$this->post_id;
            $stmt = $dbh->prepare($query_delete);
            $stmt->bindValue(":post_id", $post_id);
            $flag= $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー deletePost');
        }
        return $flag;
    }
}