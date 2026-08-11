<?php
// Session sirf ek baar start karo
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
define('DB_HOST','sql205.infinityfree.com');
define('DB_USER','if0_42620569');
define('DB_PASS','akberfaisal786');
define('DB_NAME','if0_42620569_XXX');

function getDB(){
    $c = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    if($c->connect_error){
        http_response_code(500);
        die(json_encode(['error'=>'DB Error: '.$c->connect_error]));
    }
    $c->set_charset('utf8');
    return $c;
}
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, no-store, must-revalidate');
?>

