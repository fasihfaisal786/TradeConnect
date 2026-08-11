<?php
require_once 'config.php';
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(['ok'=>false,'msg'=>'Login required']);
    exit;
}

$clientId    = intval($_SESSION['user_id']);
$bookingId   = intval($_POST['booking_id']    ?? 0);
$tradesmanId = intval($_POST['tradesman_id']  ?? 0);
$rating      = intval($_POST['rating']        ?? 0);
$comment     = trim($_POST['comment']         ?? '');

if(!$bookingId || !$tradesmanId || $rating<1 || $rating>5){
    echo json_encode(['ok'=>false,'msg'=>'Invalid data']);
    exit;
}

$db = getDB();

// Already reviewed check
$chk = $db->prepare("SELECT id FROM reviews WHERE booking_id=? AND client_id=?");
$chk->bind_param('ii',$bookingId,$clientId);
$chk->execute(); $chk->store_result();
if($chk->num_rows>0){ $chk->close(); $db->close(); echo json_encode(['ok'=>false,'msg'=>'Aap yeh review pehle de chuke hain!']); exit; }
$chk->close();

// Insert
$s = $db->prepare("INSERT INTO reviews (booking_id,client_id,tradesman_id,rating,comment) VALUES (?,?,?,?,?)");
$s->bind_param('iiiis',$bookingId,$clientId,$tradesmanId,$rating,$comment);
$ok = $s->execute(); $s->close();

if($ok){
    // Update avg rating
    $upd = $db->prepare("UPDATE tradesman_profiles SET rating_avg=(SELECT ROUND(AVG(rating),1) FROM reviews WHERE tradesman_id=?) WHERE id=?");
    $upd->bind_param('ii',$tradesmanId,$tradesmanId);
    $upd->execute(); $upd->close();
}

$db->close();
echo json_encode(['ok'=>$ok]);
?>
