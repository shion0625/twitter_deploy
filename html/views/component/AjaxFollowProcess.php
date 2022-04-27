<?php

require __DIR__ .'/../../vendor/autoload.php';

use Classes\Follow\UsingFollow;
use Classes\Follow\GetNumFollow;
$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
$profile_user_id = filter_input(INPUT_POST, 'profileId', FILTER_SANITIZE_STRING);
$current_user_id = filter_input(INPUT_POST, 'currentId', FILTER_SANITIZE_STRING);


if ($type == 'doFollow' && !is_null($profile_user_id) && !is_null($current_user_id)) {
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
