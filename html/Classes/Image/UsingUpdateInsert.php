<?php
/**
 * データベースに画像を保存します。
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes\Image;

use Classes\Image\InsertImage;
use Classes\Image\UpdateImage;

class UsingUpdateInsert
{
    private $is_update;

    /** @var string $type*/
    private $type;

    /** @var string $ext */
    private $ext;

    public function __construct(bool $is_update)
    {
        $this->is_update =$is_update;
        $this->type = $_FILES['image']['type'];
        $this->ext = explode("/", (string)$this->type)[1];
    }

        /**
     * insertImageメソッドを使用して画像を保存します。
     *
     * @return array $result
     */
    public function actionImage()
    {
        if ($this->isCheckImage()) {
            $result['image'] = 'type';
            return $result;
        }
        if ($this->is_update) {
            $update_image = new UpdateImage();
            $result['update'] = $update_image->updateImage();
            return $result;
        }
        $insert_image = new InsertImage();
        $result['insert'] = $insert_image->insertImage();
        return $result;
    }

    /**
     * 画像の型が等しいか判別します。
     *
     * @param string $ext
     * @return boolean
     */
    private function isCheckImage()
    {
        $result = $this->ext != 'jpeg' && $this->ext != 'png' && $this->ext != 'gif' && $this->ext != 'jpg';
        return $result;
    }
}
