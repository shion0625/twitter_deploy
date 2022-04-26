<?php

use Classes\User\DeleteUser;

$delete = filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_STRING);
if (isset($delete)) {
  $user_id = $_SESSION['userID'];
  $delete_user = new DeleteUser($user_id );
  $delete_user->deleteUser();
}
    require(__DIR__ . getenv("PASS_DEPLOY"). '/header.php');
?>
<div>
  <h2>アカウントを削除しますか？</h2>
      <form method=POST>
          <button name="delete" class="login-btn">削除</button>
      </form>
</div>
