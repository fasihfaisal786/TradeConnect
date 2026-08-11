<?php
require_once 'config.php';
header('Content-Type: application/json');
$db = getDB();
$result = $db->query("
    SELECT tp.id, u.name, u.city, tp.trade_category, tp.hourly_rate, 
           tp.total_jobs, tp.is_verified, tp.rating_avg
    FROM tradesman_profiles tp
    JOIN users u ON tp.user_id = u.id
    ORDER BY tp.id ASC
");
$rows = [];
while($row = $result->fetch_assoc()) $rows[] = $row;
$db->close();
echo json_encode(['ok'=>true,'tradesmen'=>$rows]);
?>
