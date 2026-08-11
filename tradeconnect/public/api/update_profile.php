<?php
require_once 'config.php';
header('Content-Type: application/json');
if(!isset($_SESSION['user_id'])){ echo json_encode(['ok'=>false,'msg'=>'Login required']); exit; }
$uid   = intval($_SESSION['user_id']);
$name  = trim($_POST['name']  ?? '');
$phone = trim($_POST['phone'] ?? '');
$city  = trim($_POST['city']  ?? '');
if(!$name){ echo json_encode(['ok'=>false,'msg'=>'Name required']); exit; }
$db = getDB();
$s  = $db->prepare("UPDATE users SET name=?,phone=?,city=? WHERE id=?");
$s->bind_param('sssi',$name,$phone,$city,$uid);
$s->execute(); $s->close();
$_SESSION['user_name'] = $name;
// Update tradesman profile if exists
if(isset($_POST['hourly_rate'])){
  $rate = floatval($_POST['hourly_rate']);
  $exp  = intval($_POST['experience_years']);
  $bio  = trim($_POST['bio'] ?? '');
  $tp   = $db->prepare("UPDATE tradesman_profiles SET hourly_rate=?,experience_years=?,bio=? WHERE user_id=?");
  $tp->bind_param('disi',$rate,$exp,$bio,$uid);
  $tp->execute(); $tp->close();
}
$db->close();
echo json_encode(['ok'=>true]);
?>
