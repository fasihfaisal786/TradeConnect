<?php
require_once 'config.php';

if($_SERVER['REQUEST_METHOD']!=='POST'){
    header('Location: ../login.html');
    exit;
}

$email = trim($_POST['email'] ?? '');
$pass  = $_POST['password']   ?? '';

if(!$email || !$pass){
    header('Location: ../login.html?error=Please+fill+all+fields');
    exit;
}

$db = getDB();

$s = $db->prepare("SELECT id,name,password,role FROM users WHERE email=?");
$s->bind_param('s',$email);
$s->execute();
$user = $s->get_result()->fetch_assoc();
$s->close();

if(!$user || !password_verify($pass,$user['password'])){
    $db->close();
    header('Location: ../login.html?error=Invalid+email+or+password');
    exit;
}

// Session clear karo pehle
session_unset();

// Real session set karo
$_SESSION['user_id']    = intval($user['id']);
$_SESSION['user_name']  = $user['name'];
$_SESSION['user_role']  = $user['role'];
$_SESSION['user_email'] = $email;

// Agar tradesman hai toh uski profile ka id bhi save karo
if($user['role'] === 'tradesman'){
    $tp = $db->prepare("SELECT id FROM tradesman_profiles WHERE user_id=?");
    $tp->bind_param('i',$user['id']);
    $tp->execute();
    $tpr = $tp->get_result()->fetch_assoc();
    $tp->close();
    if($tpr){
        $_SESSION['tradesman_profile_id'] = intval($tpr['id']);
    }
}

$db->close();

// Redirect
switch($user['role']){
    case 'admin':     header('Location: ../admin.html');               break;
    case 'tradesman': header('Location: ../tradesman-dashboard.html'); break;
    default:          header('Location: ../client-dashboard.html');
}
exit;
?>
