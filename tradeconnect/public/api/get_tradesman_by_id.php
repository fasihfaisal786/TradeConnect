<?php
require_once 'config.php';
header('Content-Type: application/json');
$id = intval($_GET['id'] ?? 0);
if(!$id){ echo json_encode(['ok'=>false]); exit; }
$db = getDB();
$s  = $db->prepare("
    SELECT tp.id, u.name, u.city, tp.trade_category, tp.hourly_rate,
           tp.total_jobs, tp.is_verified, tp.is_available,
           tp.rating_avg, tp.experience_years, tp.bio
    FROM tradesman_profiles tp
    JOIN users u ON tp.user_id = u.id
    WHERE tp.id = ?
");
$s->bind_param('i',$id);
$s->execute();
$row = $s->get_result()->fetch_assoc();
$s->close(); $db->close();
if(!$row){ echo json_encode(['ok'=>false,'msg'=>'Not found']); exit; }
echo json_encode(['ok'=>true,'tradesman'=>$row]);
?>
