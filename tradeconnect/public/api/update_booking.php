<?php
require_once 'config.php';
header('Content-Type: application/json');
$bid=intval($_POST['booking_id']??0);
$status=trim($_POST['status']??'');
$allowed=['accepted','completed','cancelled'];
if(!$bid||!in_array($status,$allowed)){echo json_encode(['ok'=>false,'error'=>'Invalid']);exit;}
$db=getDB();
$s=$db->prepare("UPDATE bookings SET status=? WHERE id=?");
$s->bind_param('si',$status,$bid);
$ok=$s->execute();$s->close();$db->close();
echo json_encode(['ok'=>$ok]);
