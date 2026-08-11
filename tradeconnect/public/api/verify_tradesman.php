<?php
require_once 'config.php';
header('Content-Type: application/json');
$id     = intval($_POST['id'] ?? 0);
$status = intval($_POST['status'] ?? 1);
if(!$id){ echo json_encode(['ok'=>false]); exit; }
$db = getDB();
$s  = $db->prepare("UPDATE tradesman_profiles SET is_verified=? WHERE id=?");
$s->bind_param('ii',$status,$id);
$ok = $s->execute();
$s->close(); $db->close();
echo json_encode(['ok'=>$ok]);
?>
