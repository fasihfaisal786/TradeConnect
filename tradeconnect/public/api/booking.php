<?php
require_once 'config.php';

if($_SERVER['REQUEST_METHOD']!=='POST'){
    header('Location: ../booking.html');
    exit;
}

// Session check — login hona zaroori hai
if(!isset($_SESSION['user_id'])){
    header('Location: ../login.html?error=Please+login+first+to+make+a+booking');
    exit;
}

$clientId = intval($_SESSION['user_id']);
$tid      = intval($_POST['tradesman_id'] ?? 0);
$svc      = trim($_POST['service_type']   ?? 'other');
$desc     = trim($_POST['description']    ?? '');
$date     = trim($_POST['booking_date']   ?? '');
$time     = trim($_POST['booking_time']   ?? '10:00');
$addr     = trim($_POST['address']        ?? '');
$city     = trim($_POST['city']           ?? 'Karachi');
$hrs      = intval($_POST['hours']        ?? 1);

if(!$desc || !$date || !$addr || !$tid){
    $tid2 = $tid ?: 1;
    header('Location: ../booking.html?error=Please+fill+all+required+fields&id='.$tid2);
    exit;
}

$db = getDB();

// Hourly rate lo
$rs = $db->prepare("SELECT hourly_rate FROM tradesman_profiles WHERE id=?");
$rs->bind_param('i',$tid);
$rs->execute();
$rr = $rs->get_result()->fetch_assoc();
$rs->close();
$rate  = floatval($rr['hourly_rate'] ?? 500);
$total = $rate * $hrs;

// Booking insert karo
$s = $db->prepare("INSERT INTO bookings (client_id,tradesman_id,service_type,description,booking_date,booking_time,address,city,hours,total_amount,status) VALUES (?,?,?,?,?,?,?,?,?,?,'pending')");
$s->bind_param('iissssssid',$clientId,$tid,$svc,$desc,$date,$time,$addr,$city,$hrs,$total);

if($s->execute()){
    $s->close(); $db->close();
    header('Location: ../client-dashboard.html?booked=1');
} else {
    $err = urlencode($s->error);
    $s->close(); $db->close();
    header('Location: ../booking.html?error='.$err.'&id='.$tid);
}
exit;
?>
