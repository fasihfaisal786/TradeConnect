<?php
require_once 'config.php';
header('Content-Type: application/json');
$id = intval($_POST['id'] ?? 0);
if(!$id){ echo json_encode(['ok'=>false]); exit; }
$db = getDB();
$s = $db->prepare("DELETE FROM users WHERE id=? AND role != 'admin'");
$s->bind_param('i',$id);
$ok = $s->execute();
$s->close(); $db->close();
echo json_encode(['ok'=>$ok]);
?>
