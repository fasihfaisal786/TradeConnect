<?php
require_once 'config.php';
header('Content-Type: application/json');
if(!isset($_SESSION['user_id'])){ echo json_encode(['ok'=>false,'msg'=>'Login required']); exit; }
$uid   = intval($_SESSION['user_id']);
$trade = trim($_POST['trade_category']   ?? '');
$rate  = floatval($_POST['hourly_rate']  ?? 0);
$exp   = intval($_POST['experience_years'] ?? 0);
$bio   = trim($_POST['bio'] ?? '');
$db = getDB();
$s  = $db->prepare("UPDATE tradesman_profiles SET trade_category=?,hourly_rate=?,experience_years=?,bio=? WHERE user_id=?");
$s->bind_param('sdisi',$trade,$rate,$exp,$bio,$uid);
$ok = $s->execute(); $s->close(); $db->close();
echo json_encode(['ok'=>$ok]);
?>
