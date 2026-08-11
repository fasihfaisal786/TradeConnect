<?php
require_once 'config.php';
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(['ok'=>false,'data'=>[],'msg'=>'Not logged in']);
    exit;
}

$uid = intval($_SESSION['user_id']);
$db  = getDB();

// Session mein saved profile id use karo — ya DB se lo
if(isset($_SESSION['tradesman_profile_id'])){
    $tid = intval($_SESSION['tradesman_profile_id']);
} else {
    $tp = $db->prepare("SELECT id FROM tradesman_profiles WHERE user_id=?");
    $tp->bind_param('i',$uid);
    $tp->execute();
    $tpr = $tp->get_result()->fetch_assoc();
    $tp->close();
    if(!$tpr){
        echo json_encode(['ok'=>true,'data'=>[]]);
        $db->close(); exit;
    }
    $tid = intval($tpr['id']);
    $_SESSION['tradesman_profile_id'] = $tid;
}

$s = $db->prepare("
    SELECT b.id, b.service_type, b.booking_date, b.booking_time,
           b.status, b.total_amount, b.hours, b.address, b.city,
           u.name AS cname, u.phone
    FROM bookings b
    JOIN users u ON b.client_id = u.id
    WHERE b.tradesman_id = ?
    ORDER BY b.id ASC
");
$s->bind_param('i',$tid);
$s->execute();
$rows = [];
$r = $s->get_result();
while($row = $r->fetch_assoc()) $rows[] = $row;
$s->close();
$db->close();

echo json_encode(['ok'=>true,'data'=>$rows]);
?>
