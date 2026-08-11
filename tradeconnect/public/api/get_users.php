<?php
require_once 'config.php';
header('Content-Type: application/json');

$db = getDB();

// Users with tradesman profile info
$result = $db->query("
    SELECT u.id, u.name, u.email, u.phone, u.city, u.role, u.created_at
    FROM users u
    ORDER BY u.created_at ASC
");

$users = [];
while($row = $result->fetch_assoc()) $users[] = $row;

// Stats
$totalUsers    = $db->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$totalTrade    = $db->query("SELECT COUNT(*) as c FROM users WHERE role='tradesman'")->fetch_assoc()['c'];
$totalBookings = $db->query("SELECT COUNT(*) as c FROM bookings")->fetch_assoc()['c'];
$totalRevenue  = $db->query("SELECT SUM(total_amount) as c FROM bookings WHERE status='completed'")->fetch_assoc()['c'] ?? 0;

$db->close();
echo json_encode([
    'ok'       => true,
    'users'    => $users,
    'stats'    => [
        'users'    => $totalUsers,
        'trade'    => $totalTrade,
        'bookings' => $totalBookings,
        'revenue'  => round($totalRevenue),
    ]
]);
?>
