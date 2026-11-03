<?php
require 'config.php';require_login();$pdo=db();
$from=$_GET['from']??date('Y-m-01');$to=$_GET['to']??date('Y-m-d');
$q=$pdo->prepare("SELECT COALESCE(SUM(CASE WHEN type='in' THEN amount ELSE 0 END),0) masuk,COALESCE(SUM(CASE WHEN type='out' THEN amount ELSE 0 END),0) keluar,COUNT(*) jumlah FROM transactions WHERE transaction_date BETWEEN ? AND ?");$q->execute([$from,$to]);$sum=$q->fetch();$saldo=$sum['masuk']-$sum['keluar'];
$q=$pdo->prepare("SELECT * FROM transactions WHERE transaction_date BETWEEN ? AND ? ORDER BY transaction_date DESC,id DESC LIMIT 100");$q->execute([$from,$to]);$rows=$q->fetchAll();
include 'partials/header.php';?>
<div class="hero"><div><span class="eyebrow">DASHBOARD</span><h2>Ringkasan Kas</h2><p><?=e($from)?> — <?=e($to)?></p></div><a class="btn primary" href="transaction.php">＋ Transaksi</a></div>
<form class="filter" method="get"><div><label>Dari</label><input type="date" name="from" value="<?=e($from)?>"></div><div><label>Sampai</label><input type="date" name="to" value="<?=e($to)?>"></div><button class="btn">Tampilkan</button><a class="btn" target="_blank" href="report.php?from=<?=e($from)?>&to=<?=e($to)?>">🖨 Print</a><a class="btn" href="report_pdf.php?from=<?=e($from)?>&to=<?=e($to)?>">⬇ PDF</a></form>
<div class="cards">
<div class="stat"><div class="stat-icon in">↗</div><div><span>Uang Masuk</span><strong><?=rupiah($sum['masuk'])?></strong></div></div>
<div class="stat"><div class="stat-icon out">↘</div><div><span>Uang Keluar</span><strong><?=rupiah($sum['keluar'])?></strong></div></div>
<div class="stat highlight"><div class="stat-icon balance">Rp</div><div><span>Saldo</span><strong><?=rupiah($saldo)?></strong></div></div>
</div>
<section class="panel"><div class="panel-head"><div><h3>Transaksi Terbaru</h3><p class="muted"><?=$sum['jumlah']?> transaksi pada periode ini</p></div><a class="btn" href="settings.php">⚙ Pengaturan</a></div>
<div class="table-wrap"><table><thead><tr><th>Tanggal</th><th>Jenis</th><th>Nominal</th><th>Keterangan</th><th>Aksi</th></tr></thead><tbody>
<?php if(!$rows):?><tr><td colspan="5" class="empty">Belum ada transaksi.</td></tr><?php endif;?>
<?php foreach($rows as $r):?><tr><td><?=e(date('d/m/Y',strtotime($r['transaction_date'])))?></td><td><span class="badge <?=$r['type']==='in'?'ok':'out'?>"><?=$r['type']==='in'?'Uang Masuk':'Uang Keluar'?></span></td><td class="amount"><?=rupiah($r['amount'])?></td><td><?=e($r['description'])?></td><td class="actions"><a class="btn sm" href="transaction.php?edit=<?=$r['id']?>">Ralat</a><a class="btn sm danger-text" href="transaction.php?delete=<?=$r['id']?>" onclick="return confirm('Hapus transaksi ini?')">Hapus</a></td></tr><?php endforeach;?>
</tbody></table></div></section>
<?php include 'partials/footer.php';?>