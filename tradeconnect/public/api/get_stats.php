<?php
require_once 'config.php';
header('Content-Type: application/json');
$db = getDB();

// Bookings by status
$s1 = $db->query("SELECT status, COUNT(*) as cnt FROM bookings GROUP BY status");
$byStatus = [];
while($r=$s1->fetch_assoc()) $byStatus[$r['status']] = intval($r['cnt']);

// Bookings by service
$s2 = $db->query("SELECT service_type, COUNT(*) as cnt FROM bookings GROUP BY service_type ORDER BY cnt DESC");
$byService = [];
while($r=$s2->fetch_assoc()) $byService[$r['service_type']] = intval($r['cnt']);

// Bookings by month (last 6 months)
$s3 = $db->query("
    SELECT DATE_FORMAT(created_at,'%b %Y') as month, COUNT(*) as cnt
    FROM bookings
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at,'%Y-%m')
    ORDER BY created_at ASC
");
$byMonth = [];
while($r=$s3->fetch_assoc()) $byMonth[] = ['month'=>$r['month'],'count'=>intval($r['cnt'])];

// Users by role
$s4 = $db->query("SELECT role, COUNT(*) as cnt FROM users GROUP BY role");
$byRole = [];
while($r=$s4->fetch_assoc()) $byRole[$r['role']] = intval($r['cnt']);

// Revenue by month
$s5 = $db->query("
    SELECT DATE_FORMAT(created_at,'%b %Y') as month, SUM(total_amount) as rev
    FROM bookings WHERE status='completed'
    AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at,'%Y-%m')
    ORDER BY created_at ASC
");
$revMonth = [];
while($r=$s5->fetch_assoc()) $revMonth[] = ['month'=>$r['month'],'rev'=>floatval($r['rev'])];

$db->close();
echo json_encode(['ok'=>true,'byStatus'=>$byStatus,'byService'=>$byService,'byMonth'=>$byMonth,'byRole'=>$byRole,'revMonth'=>$revMonth]);
?>
