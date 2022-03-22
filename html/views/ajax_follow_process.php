<?php
require __DIR__ .'/../vendor/autoload.php';


use Classes\Follow\UsingFollow;
use Classes\Follow\GetNumFollow;

if (!empty($_POST['profile_user_id']) && !empty($_POST['current_user_id'])) {
    $profile_user_id = (string)$_POST['profile_user_id'];
    $current_user_id = (string)$_POST['current_user_id'];
    $UsingFollow = new UsingFollow($current_user_id, $profile_user_id);
    $status = $UsingFollow->changeFollowStatus();
    $GetNumFollow = new GetNumFollow($profile_user_id);
    $follow_num = $GetNumFollow-> numFollow();
    $follower_num = $GetNumFollow-> numFollower();
    $ary = array('success'=>$status,'follow'=>$follow_num,'follower'=>$follower_num);
    echo json_encode($ary);
}
