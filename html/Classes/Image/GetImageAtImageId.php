<?php
/**
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes\Image;

use Controller\Pdo;
use Controller\Connect;

class GetImageAtImageId extends Connect
{
    /** @var string $image_id*/
    private $image_id;

    public function __construct(string $image_id)
    {
        $this->image_id = $image_id;
    }

    /**
     * データベースからイメージIDで探して見つかれば画像データを返します。
     *
     * @return mixed
     */
    public function getImageAtImageId():mixed
    {
        parent::__construct();
        $dbh = $this->connectDb();
        try {
            $query = 'SELECT * FROM user_image WHERE image_id = :id LIMIT 1';
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(':id', (int)$this->image_id, PDO::PARAM_INT);
            $stmt->execute();
            $image = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー getImageAtImageId');
        }
        return $image;
    }
}
