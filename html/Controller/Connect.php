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
        // PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
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
     * @return
     */
    protected function connectDb()
    {
        $isLocal = true;////localの時trueに変える。
        if ($isLocal) {
            /// local環境の時
            error_reporting(E_ALL & ~E_NOTICE);
            try {
                $dbh = new Pdo($this->DSN, $this->USER, $this->PASSWORD);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                print_r("Connect 接続失敗: ".$e->getMessage()."\n");
                exit();
            }
            return $dbh;
        } else {
            //For server
            $url = parse_url(getenv('CLEARDB_DATABASE_URL'));
            $server = $url["host"];
            $username = $url["user"];
            $password = $url["pass"];
            $db = substr($url["path"], 1);
            $pdo = new PDO(
                'mysql:host=' . $server . ';dbname=' . $db . ';charset=utf8mb4',
                $username,
                $password,
                [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                ]
            );
            return $pdo;
        }
    }
}