<?php
// Only set session ini and start session when no session is active.
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_httponly', 1);
    session_start();
}

function rupiah($angka){
    return 'Rp'.number_format($angka,0,',','.');
}

function cart(){
    return $_SESSION['cart'] ?? [];
}

function cart_total(){
    $t=0;
    foreach(cart() as $i){ $t+=$i['price']*$i['qty']; }
    return $t;
}

function generate_order_code(){
    return 'LUM-'.date('Ymd-His');
}

function csrf_token(){
    if(empty($_SESSION['csrf'])){
        $_SESSION['csrf']=bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check($token){
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'],$token);
}
