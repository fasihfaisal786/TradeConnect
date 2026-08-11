<?php
require_once 'config.php';
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD']!=='POST'){
    echo json_encode(['ok'=>false,'msg'=>'Invalid request']);
    exit;
}

$email   = trim($_POST['email']        ?? '');
$newPass = trim($_POST['new_password'] ?? '');

if(!$email || !$newPass){
    echo json_encode(['ok'=>false,'msg'=>'Email aur password required hain']);
    exit;
}

if(strlen($newPass) < 6){
    echo json_encode(['ok'=>false,'msg'=>'Password kam az kam 6 characters ka hona chahiye']);
    exit;
}

$db = getDB();

// Check karo email exist karta hai
$chk = $db->prepare("SELECT id FROM users WHERE email=?");
$chk->bind_param('s',$email);
$chk->execute();
$chk->store_result();

if($chk->num_rows === 0){
    $chk->close();
    $db->close();
    echo json_encode(['ok'=>false,'msg'=>'Yeh email registered nahi hai!']);
    exit;
}
$chk->close();

// Hash karo naya password
$hash = password_hash($newPass, PASSWORD_BCRYPT);

// Update karo
$s = $db->prepare("UPDATE users SET password=? WHERE email=?");
$s->bind_param('ss',$hash,$email);
$ok = $s->execute();
$s->close();
$db->close();

if($ok){
    echo json_encode(['ok'=>true,'msg'=>'Password reset ho gaya!']);
} else {
    echo json_encode(['ok'=>false,'msg'=>'Password update nahi hua, dobara try karo']);
}
?>
