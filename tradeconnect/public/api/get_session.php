<?php
require_once 'config.php';
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(['ok'=>false,'msg'=>'Not logged in']);
    exit;
}

$uid = intval($_SESSION['user_id']);
$db  = getDB();

$s = $db->prepare("SELECT id,name,email,role,city,phone,profile_image FROM users WHERE id=?");
$s->bind_param('i',$uid);
$s->execute();
$user = $s->get_result()->fetch_assoc();
$s->close();

if(!$user){
    echo json_encode(['ok'=>false,'msg'=>'User not found']);
    $db->close(); exit;
}

$profile = null;
if($user['role']==='tradesman'){
    // Session se profile id lo
    if(isset($_SESSION['tradesman_profile_id'])){
        $pid = intval($_SESSION['tradesman_profile_id']);
        $tp  = $db->prepare("SELECT id,trade_category,hourly_rate,experience_years,rating_avg,total_jobs,is_available FROM tradesman_profiles WHERE id=?");
        $tp->bind_param('i',$pid);
    } else {
        $tp = $db->prepare("SELECT id,trade_category,hourly_rate,experience_years,rating_avg,total_jobs,is_available FROM tradesman_profiles WHERE user_id=?");
        $tp->bind_param('i',$uid);
    }
    $tp->execute();
    $profile = $tp->get_result()->fetch_assoc();
    $tp->close();
    if($profile) $_SESSION['tradesman_profile_id'] = intval($profile['id']);
}

$db->close();
echo json_encode(['ok'=>true,'user'=>$user,'profile'=>$profile]);
?>
