<?php
require_once '../inc/db.php';

$data=json_decode(file_get_contents("php://input"),true);

$total=0;

foreach($data as $d){
$total+=$d['price']*$d['qty'];
}

$stmt=$pdo->prepare("
INSERT INTO orders
(customer_name,room_number,total_price,status,created_at)
VALUES ('POS','-',?,'pending',NOW())
");

$stmt->execute([$total]);

$order_id=$pdo->lastInsertId();

foreach($data as $d){

$subtotal=$d['price']*$d['qty'];

$pdo->prepare("
INSERT INTO order_items
(order_id,product_id,product_name,price,quantity,subtotal)
VALUES (?,?,?,?,?,?)
")->execute([
$order_id,
$d['id'],
$d['name'],
$d['price'],
$d['qty'],
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
