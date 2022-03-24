<?php
require __DIR__ .'/../../vendor/autoload.php';

use Classes\Follow\UsingFollow;
use Classes\Follow\GetNumFollow;

if ($_POST['type'] == 'doFollow' && !empty($_POST['profileId']) && !empty($_POST['currentId'])) {
    $profile_user_id = (string)$_POST['profileId'];
    $current_user_id = (string)$_POST['currentId'];
    $UsingFollow = new UsingFollow($current_user_id, $profile_user_id);
    $status = $UsingFollow->changeFollowStatus();
    $GetNumFollow = new GetNumFollow($profile_user_id);
    $follow_num = $GetNumFollow-> numFollow();
    $follower_num = $GetNumFollow-> numFollower();
    $ary = array('status'=>$status,'follow'=>$follow_num,'follower'=>$follower_num);
    echo json_encode($ary);
} else {
    echo json_encode(["follow"]);
}
