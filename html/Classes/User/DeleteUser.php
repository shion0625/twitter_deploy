<?php
/**
 *
 * @category  PHP
 * @author  shion0625 <xkaito0912@gmail.com>
 * @link  https://codelikes.com/phpDocumentor
 */

namespace Classes\User;

use Controller\Pdo;
use Controller\Connect;

class DeleteUser extends Connect
{
    /** @var string $user_id */
    private $user_id;

    public function __construct(string $user_id)
    {
        $this-> user_id= $user_id;
    }

    public function deleteUser()
    {
        parent::__construct();
        $dbh = $this->connectDb();

        $flag_info = $this->deleteUserInfo($dbh);
        $flag_delete_posts = $this->deleteUserPosts($dbh);
        $flag_delete_followed = $this->deleteFollowedUser($dbh);
        $flag_delete_follow = $this->deleteFollowUser($dbh);
        if($flag_info && $flag_delete_posts && $flag_delete_followed && $flag_delete_follow){
            $_SESSION['messageAlert'] = "ユーザの削除が成功しました。";
            header("location: /?page=logout");
            exit();
        }else {
            $_SESSION['messageAlert'] = "ユーザの削除に失敗しました。";
            header("location: /?page=delete_user");
            exit();
        }
        return;
    }

    private function deleteUserInfo($dbh)
    {
        try {
            $query = "DELETE u,i FROM users AS u LEFT OUTER JOIN user_image AS i ON u.email_encode = i.user_id WHERE u.email_encode=:user_id";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(":user_id", $this->user_id, PDO::PARAM_STR);
            $flag = $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー DeleteUserInfo');
        }
        return $flag;
    }

    private function deleteUserPosts($dbh)
    {
        try {
            $query = "DELETE FROM users WHERE email_encode = :user_id ";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(":user_id", $this->user_id, PDO::PARAM_STR);
            $flag = $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー DeleteUserPosts');
        }
        return $flag;
    }

    private function deleteFollowedUser($dbh)
    {
        try {
            $query = "DELETE FROM following WHERE followed_id = :user_id ";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(":user_id", $this->user_id, PDO::PARAM_STR);
            $flag = $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー DeleteFollowedUser');
        }
        return $flag;
    }

    private function deleteFollowUser($dbh)
    {
        try {
            $query = "DELETE FROM following WHERE follow_id = :user_id ";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(":user_id", $this->user_id, PDO::PARAM_STR);
            $flag = $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
            exit('データベースエラー DeleteFollowUser');
        }
        return $flag;
    }
}
