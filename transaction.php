<?php
require 'config.php';require_login();$pdo=db();
if(isset($_GET['delete'])){$s=$pdo->prepare('DELETE FROM transactions WHERE id=?');$s->execute([(int)$_GET['delete']]);redirect('index.php');}
$edit=null;if(isset($_GET['edit'])){$s=$pdo->prepare('SELECT * FROM transactions WHERE id=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch();}
if($_SERVER['REQUEST_METHOD']==='POST'){$id=(int)($_POST['id']??0);$data=[$_POST['transaction_date'],$_POST['type'],(float)$_POST['amount'],trim($_POST['description'])];if($id){$s=$pdo->prepare('UPDATE transactions SET transaction_date=?,type=?,amount=?,description=? WHERE id=?');$s->execute([...$data,$id]);}else{$s=$pdo->prepare('INSERT INTO transactions(transaction_date,type,amount,description) VALUES(?,?,?,?)');$s->execute($data);}redirect('index.php');}
include 'partials/header.php';?>
<div class="hero"><div><span class="eyebrow"><?=$edit?'Ralat':'INPUT'?></span><h2><?=$edit?'Perbaiki Transaksi':'Catat Transaksi'?></h2><p><?=$edit?'Perbaiki data yang salah lalu simpan.':'Cukup isi 4 data transaksi.'?></p></div></div>
<section class="panel form-panel"><form method="post" class="form-grid"><input type="hidden" name="id" value="<?=$edit['id']??0?>">
<label>Tanggal<input type="date" name="transaction_date" required value="<?=e($edit['transaction_date']??date('Y-m-d'))?>"></label>
<label>Jenis<select name="type"><option value="in" <?=($edit['type']??'in')==='in'?'selected':''?>>Uang Masuk</option><option value="out" <?=($edit['type']??'')==='out'?'selected':''?>>Uang Keluar</option></select></label>
<label>Nominal<input type="number" name="amount" min="0" step="1" required value="<?=e($edit['amount']??'')?>"></label>
<label>Keterangan<textarea name="description" required placeholder="Contoh: Pembayaran pelanggan, beli kabel, listrik..."><?=e($edit['description']??'')?></textarea></label>
<div class="form-actions"><button class="btn primary">Simpan</button><a class="btn" href="index.php">Batal</a></div></form></section>
<?php include 'partials/footer.php';?>