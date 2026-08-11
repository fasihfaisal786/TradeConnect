<?php
require_once 'config.php';
header('Content-Type: application/json');
$id = intval($_GET['id'] ?? 0);
if(!$id){ echo json_encode(['ok'=>false]); exit; }
$db = getDB();
$s  = $db->prepare("
    SELECT tp.id, tp.trade_category, tp.hourly_rate, tp.experience_years,
           tp.bio, tp.is_available, tp.is_verified, tp.rating_avg, tp.total_jobs,
           u.name, u.city, u.phone, u.email
    FROM tradesman_profiles tp
    JOIN users u ON tp.user_id = u.id
    WHERE tp.id = ?
");
$s->bind_param('i',$id);
$s->execute();
$t = $s->get_result()->fetch_assoc();
$s->close();

// Get reviews
$r = $db->prepare("
    SELECT r.rating, r.comment, r.created_at, u.name AS client_name
    FROM reviews r
    JOIN users u ON r.client_id = u.id
    WHERE r.tradesman_id = ?
    ORDER BY r.created_at DESC
");
$r->bind_param('i',$id);
$r->execute();
$reviews = [];
$res = $r->get_result();
while($row = $res->fetch_assoc()) $reviews[] = $row;
$r->close();
$db->close();

echo json_encode(['ok'=>true,'tradesman'=>$t,'reviews'=>$reviews]);
?>
