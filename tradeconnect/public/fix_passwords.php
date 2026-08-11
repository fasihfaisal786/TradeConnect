<?php
$c = new mysqli('localhost','root','','tradeconnect');
if($c->connect_error) die("<h2 style='font-family:sans-serif;color:red'>DB Error: ".$c->connect_error."<br>XAMPP Apache+MySQL start karo aur tradeconnect.sql import karo!</h2>");
$d = password_hash('demo123',PASSWORD_BCRYPT);
$a = password_hash('admin123',PASSWORD_BCRYPT);
$c->query("UPDATE users SET password='$a' WHERE email='admin@tradeconnect.pk'");
$c->query("UPDATE users SET password='$d' WHERE email='client@demo.com'");
$c->query("UPDATE users SET password='$d' WHERE email='tradesman@demo.com'");
$c->query("UPDATE users SET password='$d' WHERE email='raza@demo.com'");
$c->query("UPDATE users SET password='$d' WHERE email='salman@demo.com'");
$c->query("UPDATE users SET password='$d' WHERE email='bilal@demo.com'");
$c->close();
?>
<!DOCTYPE html><html><head><meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">
<style>*{margin:0;padding:0;box-sizing:border-box;}body{font-family:'Inter',sans-serif;background:#000;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;}.card{background:#0f0f0f;border:1px solid #1e1e1e;border-radius:20px;padding:44px;max-width:480px;width:100%;text-align:center;}.icon{font-size:3rem;margin-bottom:16px;}.h2{font-size:1.6rem;font-weight:800;margin-bottom:8px;}.sub{color:#555;font-size:.9rem;margin-bottom:28px;}.list{background:#111;border:1px solid #1e1e1e;border-radius:12px;overflow:hidden;margin-bottom:24px;text-align:left;}.row{padding:14px 18px;border-bottom:1px solid #1a1a1a;display:flex;justify-content:space-between;align-items:center;}.row:last-child{border:none;}.em{color:#aaa;font-size:.85rem;}.tag{background:#0d1f14;color:#3bcc6e;border:1px solid #1a3a24;padding:3px 10px;border-radius:6px;font-size:.78rem;font-weight:700;}.btn{display:block;background:#3bcc6e;color:#000;padding:13px;border-radius:10px;font-weight:800;text-decoration:none;font-size:1rem;}</style>
</head><body>
<div class="card">
  <div class="icon">✅</div>
  <div class="h2">Passwords Fix Ho Gaye!</div>
  <div class="sub">Ab in accounts se login karo</div>
  <div class="list">
    <div class="row"><div><div style="font-weight:700">Muhammad Fasih</div><div class="em">client@demo.com</div></div><span class="tag">demo123</span></div>
    <div class="row"><div><div style="font-weight:700">Ahmed Khan</div><div class="em">tradesman@demo.com</div></div><span class="tag">demo123</span></div>
    <div class="row"><div><div style="font-weight:700">Admin</div><div class="em">admin@tradeconnect.pk</div></div><span class="tag">admin123</span></div>
  </div>
  <a href="login.html" class="btn">Login Page Pe Jao →</a>
</div>
</body></html>
