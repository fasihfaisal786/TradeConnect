<?php
require_once 'config.php';
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(['ok'=>true,'data'=>[]]);
    exit;
}

$cid = intval($_SESSION['user_id']);
$db  = getDB();

$s = $db->prepare("
    SELECT b.id, b.service_type, b.booking_date, b.booking_time,
           b.status, b.total_amount, b.hours, b.tradesman_id,
           u.name AS tname, tp.trade_category, tp.id AS tradesman_profile_id
    FROM bookings b
    JOIN tradesman_profiles tp ON b.tradesman_id = tp.id
    JOIN users u ON tp.user_id = u.id
    WHERE b.client_id = ?
    ORDER BY b.id ASC
");
$s->bind_param('i',$cid);
$s->execute();
$rows = [];
$r = $s->get_result();
while($row = $r->fetch_assoc()) $rows[] = $row;
$s->close();
$db->close();

echo json_encode(['ok'=>true,'data'=>$rows]);
?>
