<?php
require_once 'config.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: ../register.html');exit;}
$name=trim($_POST['name']??'');$email=trim($_POST['email']??'');
$pass=$_POST['password']??'';$phone=trim($_POST['phone']??'');
$city=trim($_POST['city']??'');$role=$_POST['role']??'client';
if(!$name||!$email||!$pass||!$city){header('Location: ../register.html?error=Fill+all+fields');exit;}
$db=getDB();
$chk=$db->prepare("SELECT id FROM users WHERE email=?");
$chk->bind_param('s',$email);$chk->execute();$chk->store_result();
if($chk->num_rows>0){$chk->close();$db->close();header('Location: ../register.html?error=Email+already+registered');exit;}
$chk->close();
$hash=password_hash($pass,PASSWORD_BCRYPT);
$s=$db->prepare("INSERT INTO users (name,email,password,phone,role,city) VALUES (?,?,?,?,?,?)");
$s->bind_param('ssssss',$name,$email,$hash,$phone,$role,$city);
if(!$s->execute()){$s->close();$db->close();header('Location: ../register.html?error=Registration+failed');exit;}
$uid=$db->insert_id;$s->close();
if($role==='tradesman'){
    $trade=$_POST['trade_category']??'other';
    $exp=intval($_POST['experience_years']??0);
    $rate=floatval($_POST['hourly_rate']??500);
    $bio=trim($_POST['bio']??'');
    $tp=$db->prepare("INSERT INTO tradesman_profiles (user_id,trade_category,experience_years,hourly_rate,bio) VALUES (?,?,?,?,?)");
    $tp->bind_param('isids',$uid,$trade,$exp,$rate,$bio);$tp->execute();$tp->close();
}
$db->close();
header('Location: ../login.html?registered=1');exit;
