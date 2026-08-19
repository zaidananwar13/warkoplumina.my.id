<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';

$data=json_decode(file_get_contents("php://input"),true);

$total=0;

foreach($data as $d){
$total+=$d['price']*$d['qty'];
}

$order_code = generate_order_code();
$stmt=$pdo->prepare("
INSERT INTO orders
(order_code,customer_name,room_number,total_price,status,created_at)
VALUES (?,?,?,?,?,NOW())
");

$stmt->execute([$order_code, 'POS','-',$total,'pending']);

$order_id=$pdo->lastInsertId();

foreach($data as $d){

$subtotal=$d['price']*$d['qty'];

$pdo->prepare("
INSERT INTO order_items
(order_id,product_name,quantity,price,subtotal)
VALUES (?,?,?,?,?)
")->execute([
	$order_id,
	$d['name'],
	$d['qty'],
	$d['price'],
	$subtotal
]);

$pdo->prepare("
UPDATE products
SET stock=stock-?
WHERE id=?
")->execute([
$d['qty'],
$d['id']
]);

}

echo "OK";
