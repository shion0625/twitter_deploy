<?php

use Classes\User\DeleteUser;

$delete = filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_STRING);
if (isset($delete)) {
    $user_id = $_SESSION['userID'];
    $delete_user = new DeleteUser($user_id);
    $delete_user->deleteUser();
}
    require(__DIR__ . '/header.php');
?>
<div class="delete-user-all-contents">
  <h2><?php echo $_SESSION['username']?>さん</h2>
  <h3>アカウントを削除しますか？</h3>
  <form method="POST">
    <div class="dlt-btn">
      <div class="dlt-btn-back">
        <p>本当にアカウントを削除してもいいですか>?</p>
        <button class="dlt-yes" name="delete">Yes</button>
        <button class="dlt-no" type="button">No</button>
      </div>
      <div class="dlt-btn-front">削除</div>
    </div>
  </form>
</div>
