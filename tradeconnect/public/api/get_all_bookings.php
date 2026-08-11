<?php
require_once 'config.php';
header('Content-Type: application/json');
$db = getDB();
$result = $db->query("
    SELECT b.id, b.service_type, b.booking_date, b.status, b.total_amount,
           uc.name AS client_name, ut.name AS tradesman_name
    FROM bookings b
    JOIN users uc ON b.client_id = uc.id
    JOIN tradesman_profiles tp ON b.tradesman_id = tp.id
    JOIN users ut ON tp.user_id = ut.id
    ORDER BY b.id ASC
");
$rows = [];
while($row = $result->fetch_assoc()) $rows[] = $row;
$db->close();
echo json_encode(['ok'=>true,'bookings'=>$rows]);
?>
