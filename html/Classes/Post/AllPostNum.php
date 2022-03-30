<?php
/**
 * データベースに保存されているすべての投稿を取得します
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes\Post;

use Controller\Pdo;
use Controller\Connect;

class AllPostNum extends Connect
{
    public function allPostNum()
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $query = "SELECT * FROM tweet";
            $stmt = $dbh->prepare($query);
            $stmt->execute();
            $count = $stmt -> rowCount();
            $max_page = ceil(($count+1)/15);
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー 全ての投稿数を取得できません。');
        }
        return $max_page;
    }
}
