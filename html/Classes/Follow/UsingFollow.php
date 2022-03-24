<?php
/**
 * データベースからインスタンス化した時のユーザIDno情報を返します。
 *
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes\Follow;

use Controller\Pdo;
use Controller\Connect;
use Classes\Follow\CheckFollow;

class UsingFollow extends Connect
{
    private $follow_user;
    private $followed_user;

    public function __construct(string $follow_user, string $followed_user)
    {
        $this->follow_user = $follow_user;
        $this->followed_user = $followed_user;
    }
    /**
     * フォローを解除もしくはフォローします。そしてデータベースに値を保存します。
     *
     * @return bool
     */
    public function changeFollowStatus()
    {
        $CheckFollow = new CheckFollow($this->follow_user, $this->followed_user);
        if ($CheckFollow->isCheckFollow()) {
            $query ="DELETE
                    FROM following
                    WHERE follow_id = :follow_id AND followed_id = :followed_id";
        } else {
            $query ="INSERT INTO following(follow_id,followed_id)
                    VALUES(:follow_id,:followed_id)";
        }
        try {
            parent::__construct();
            $dbh = $this->connectDb();
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(":follow_id", $this->follow_user);
            $stmt->bindValue(":followed_id", $this->followed_user);
            $stmt->execute();
        } catch (PDOException $e) {
            $e->getMessage() . PHP_EOL;//エラーが出たときの処理
            exit('データベースエラー changeFollowStatus');
        }
        return !$CheckFollow->isCheckFollow();
    }
}
