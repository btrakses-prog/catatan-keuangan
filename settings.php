<?php
require 'config.php';require_login();$pdo=db();$msg='';$err='';
if(isset($_GET['delete_type'])){$s=$pdo->prepare('DELETE FROM percentage_types WHERE id=?');$s->execute([(int)$_GET['delete_type']]);redirect('settings.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(($_POST['action']??'')==='business'){$s=$pdo->prepare('UPDATE settings SET business_name=? WHERE id=1');$s->execute([trim($_POST['business_name'])]);$msg='Nama usaha disimpan.';}
 if(($_POST['action']??'')==='add'){$name=trim($_POST['name']);$pct=(float)$_POST['percentage'];if($name==='')$err='Nama pembagian wajib diisi.';else{$s=$pdo->prepare('INSERT INTO percentage_types(name,percentage) VALUES(?,?)');$s->execute([$name,$pct]);$msg='Jenis pembagian ditambahkan.';}}
 if(($_POST['action']??'')==='save_types'){foreach($_POST['pct']??[] as $id=>$pct){$s=$pdo->prepare('UPDATE percentage_types SET percentage=? WHERE id=?');$s->execute([(float)$pct,(int)$id]);}$msg='Persentase diperbarui.';}
}
$set=$pdo->query('SELECT * FROM settings WHERE id=1')->fetch();$types=$pdo->query('SELECT * FROM percentage_types ORDER BY id')->fetchAll();$total=array_sum(array_column($types,'percentage'));
include 'partials/header.php';?>
<div class="hero"><div><span class="eyebrow">PENGATURAN</span><h2>Pembagian Persentase</h2><p>Jenis pembagian bisa ditambah, diubah, atau dihapus.</p></div></div>
<?php if($msg):?><div class="alert success"><?=$msg?></div><?php endif;?><?php if($err):?><div class="alert danger"><?=$err?></div><?php endif;?>
<div class="settings-grid"><section class="panel"><h3>Nama Usaha</h3><form method="post"><input type="hidden" name="action" value="business"><label>Nama<input name="business_name" value="<?=e($set['business_name'])?>" required></label><button class="btn primary">Simpan</button></form></section>
<section class="panel"><div class="panel-head"><div><h3>Jenis Pembagian</h3><p class="muted">Total saat ini: <b><?=number_format($total,2)?>%</b></p></div><span class="total-chip <?=$total==100?'valid':''?>"><?=$total==100?'✓ 100%':'Periksa total'?></span></div>
<form method="post"><input type="hidden" name="action" value="save_types"><?php foreach($types as $t):?><div class="type-row"><input name="pct[<?=$t['id']?>]" type="number" step=".01" min="0" value="<?=$t['percentage']?>"><span><?=e($t['name'])?></span><a class="danger-text" href="?delete_type=<?=$t['id']?>" onclick="return confirm('Hapus jenis ini?')">Hapus</a></div><?php endforeach;?><button class="btn primary">Simpan Persentase</button></form>
<hr><h4>Tambah Jenis</h4><form method="post" class="add-type"><input type="hidden" name="action" value="add"><input name="name" placeholder="Contoh: Investor" required><input name="percentage" type="number" step=".01" min="0" placeholder="%" required><button class="btn">＋ Tambah</button></form></section></div>
<?php include 'partials/footer.php';?>