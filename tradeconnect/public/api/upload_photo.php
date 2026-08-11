<?php
require_once 'config.php';
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(['ok'=>false,'msg'=>'Login required']);
    exit;
}

if(!isset($_FILES['photo'])){
    echo json_encode(['ok'=>false,'msg'=>'No file uploaded']);
    exit;
}

$file     = $_FILES['photo'];
$uid      = intval($_SESSION['user_id']);
$allowed  = ['image/jpeg','image/png','image/gif','image/webp'];
$maxSize  = 2 * 1024 * 1024; // 2MB

if(!in_array($file['type'], $allowed)){
    echo json_encode(['ok'=>false,'msg'=>'Only JPG, PNG, GIF allowed']);
    exit;
}
if($file['size'] > $maxSize){
    echo json_encode(['ok'=>false,'msg'=>'File too large. Max 2MB']);
    exit;
}

$ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'user_'.$uid.'_'.time().'.'.$ext;
$uploadDir= dirname(__DIR__).'/uploads/profiles/';
$destPath = $uploadDir.$filename;

if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if(!move_uploaded_file($file['tmp_name'], $destPath)){
    echo json_encode(['ok'=>false,'msg'=>'Upload failed']);
    exit;
}

// Save to DB
$db = getDB();
$s  = $db->prepare("UPDATE users SET profile_image=? WHERE id=?");
$imgPath = 'uploads/profiles/'.$filename;
$s->bind_param('si',$imgPath,$uid);
$s->execute();
$s->close();
$db->close();

echo json_encode(['ok'=>true,'path'=>$imgPath]);
?>
