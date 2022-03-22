<?php
/**
 * データベースへ接続します。
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Controller;

use Controller\Pdo;
use Dotenv\Dotenv;

class Connect extends Pdo
{
    /** @var string $DSN */
    private $DSN;
    /** @var string $USER */
    private $USER;
    /** @var string $PASSWORD */
    private $PASSWORD;
    /** @var array $options */
    private $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
    );
    /**
     * 環境変数からデータベースの情報を受け取ります。
     */
    public function __construct()
    {
        /** .envファイルを読み込みます。 */
        $dotenv = Dotenv::createUnsafeImmutable(__DIR__.'/../');
        $dotenv->load();
        $this-> DSN = getenv('DB_DSN');
        $this-> USER = getenv('DB_USER');
        $this->PASSWORD = getenv('DB_PASSWORD');
    }

    /**
     * データベースと接続します。そしてインスタンスを返します。
     * @return mixed
     */
    protected function connectDb()
    {

        error_reporting(E_ALL & ~E_NOTICE);
        try {
            $dbh = new Pdo($this->DSN, $this->USER, $this->PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print_r("Connect 接続失敗: ".$e->getMessage()."\n");
            exit();
        }
        return $dbh;
    }
}
