<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
define('DB_HOST', getenv('MYSQLHOST'));
define('DB_USER', getenv('MYSQLUSER'));
define('DB_PASS', getenv('MYSQLPASSWORD'));
define('DB_NAME', getenv('MYSQLDATABASE'));
define('DB_PORT', getenv('MYSQLPORT'));

function getDB(){
    $c = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
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
