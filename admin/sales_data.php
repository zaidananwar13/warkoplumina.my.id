<?php
require_once '../inc/db.php';

$data=$pdo->query("
SELECT DATE(created_at) tgl,
SUM(total_price) total
FROM orders
GROUP BY DATE(created_at)
ORDER BY tgl DESC
LIMIT 7
")->fetchAll();

$labels=[];
$values=[];

foreach(array_reverse($data) as $d){

$labels[]=$d['tgl'];
$values[]=$d['total'];

}

echo json_encode([
'labels'=>$labels,
'values'=>$values
]);
