<?php
require_once 'config.php';
header('Content-Type: application/json');
if(!isset($_SESSION['user_id'])){ echo json_encode(['ok'=>false,'msg'=>'Login required']); exit; }
$uid  = intval($_SESSION['user_id']);
$pass = trim($_POST['new_password'] ?? '');
if(strlen($pass)<6){ echo json_encode(['ok'=>false,'msg'=>'Min 6 characters']); exit; }
$hash = password_hash($pass, PASSWORD_BCRYPT);
$db   = getDB();
$s    = $db->prepare("UPDATE users SET password=? WHERE id=?");
$s->bind_param('si',$hash,$uid);
$ok = $s->execute(); $s->close(); $db->close();
echo json_encode(['ok'=>$ok]);
?>
