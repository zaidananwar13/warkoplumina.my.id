<?php
require_once 'inc/auth.php';
require_once '../inc/db.php';
require_once '../inc/functions.php';

/* ======================
   AMBIL PRODUK AKTIF
====================== */
$products = $pdo->query("
SELECT *
FROM products
WHERE is_active=1
ORDER BY name
")->fetchAll();

include 'inc/header.php';
?>

<h2>POS Kasir</h2>

<style>

.product-grid{
display:grid;
grid-template-columns:repeat(auto-fill,minmax(150px,1fr));
gap:15px;
margin-top:20px;
}

.product{
border:1px solid #ddd;
border-radius:10px;
padding:10px;
text-align:center;
cursor:pointer;
background:#fff;
transition:0.2s;
}

.product:hover{
transform:scale(1.03);
box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

.product img{
width:100%;
height:100px;
object-fit:cover;
border-radius:6px;
margin-bottom:6px;
}

.product-name{
font-size:14px;
font-weight:bold;
}

.product-price{
color:#666;
font-size:13px;
}

.cart{
margin-top:30px;
}

.cart table{
width:100%;
}

.total-box{
margin-top:15px;
font-size:20px;
font-weight:bold;
}

button{
padding:10px 15px;
border:none;
border-radius:6px;
cursor:pointer;
background:#222;
color:white;
}

</style>

<div class="product-grid">

<?php foreach($products as $p): ?>

<div class="product"
onclick="addProduct(
<?= $p['id'] ?>,
'<?= htmlspecialchars($p['name']) ?>',
<?= $p['price'] ?>
)">

<?php if($p['image']): ?>

<img src="../uploads/products/<?= $p['image'] ?>">

<?php endif; ?>

<div class="product-name">
<?= htmlspecialchars($p['name']) ?>
</div>

<div class="product-price">
<?= rupiah($p['price']) ?>
</div>

</div>

<?php endforeach; ?>

</div>

<hr>

<h3>Keranjang Order</h3>

<div class="cart">

<table id="cartTable">

<tr>
<th>Produk</th>
<th>Qty</th>
<th>Harga</th>
<th>Subtotal</th>
<th>Hapus</th>
</tr>

</table>

<div class="total-box">

Total: Rp <span id="total">0</span>

</div>

<br>

<button onclick="submitOrder()">Simpan Order</button>

</div>

<script>

let cart=[];

/* ======================
   TAMBAH PRODUK
====================== */

function addProduct(id,name,price){

let item=cart.find(p=>p.id==id);

if(item){
item.qty++;
}else{
cart.push({
id:id,
name:name,
price:price,
qty:1
});
}

renderCart();
}

/* ======================
   HAPUS ITEM
====================== */

function removeItem(i){

cart.splice(i,1);

renderCart();

}

/* ======================
   RENDER CART
====================== */

function renderCart(){

let table=document.getElementById("cartTable");

table.innerHTML=`
<tr>
<th>Produk</th>
<th>Qty</th>
<th>Harga</th>
<th>Subtotal</th>
<th></th>
</tr>
`;

let total=0;

cart.forEach((p,i)=>{

let subtotal=p.qty*p.price;

total+=subtotal;

table.innerHTML+=`
<tr>
<td>${p.name}</td>
<td>${p.qty}</td>
<td>${p.price}</td>
<td>${subtotal}</td>
<td>
<button onclick="removeItem(${i})">X</button>
</td>
</tr>
`;

});

document.getElementById("total").innerText=total.toLocaleString();

}

/* ======================
   SIMPAN ORDER
====================== */

function submitOrder(){

if(cart.length==0){
alert("Keranjang kosong");
return;
}

fetch('create_order.php',{

method:'POST',

headers:{
'Content-Type':'application/json'
},

body:JSON.stringify(cart)

})
.then(res=>res.text())
.then(res=>{

alert("Order berhasil disimpan");

cart=[];

renderCart();

});

}

</script>

<?php include 'inc/footer.php'; ?>
