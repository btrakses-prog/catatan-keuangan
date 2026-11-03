<?php
require 'config.php';require_login();
$pdo=db();
$from=$_GET['from']??date('Y-m-01');$to=$_GET['to']??date('Y-m-d');
$s=$pdo->prepare("SELECT * FROM transactions WHERE transaction_date BETWEEN ? AND ? ORDER BY transaction_date,id");$s->execute([$from,$to]);$rows=$s->fetchAll();
$in=$out=0;foreach($rows as $r){if($r['type']==='in')$in+=$r['amount'];else$out+=$r['amount'];}
$types=$pdo->query('SELECT * FROM percentage_types ORDER BY id')->fetchAll();
$set=$pdo->query('SELECT * FROM settings WHERE id=1')->fetch();
?><!doctype html><html lang="id"><head><meta charset="utf-8"><title>Laporan Kas</title><link rel="stylesheet" href="assets/style.css"></head><body class="report-body"><div class="report">
<div class="report-head"><div><h1><?=e($set['business_name'])?></h1><h2>Laporan Kas</h2><p><?=e($from)?> s/d <?=e($to)?></p></div><button class="btn no-print" onclick="print()">🖨 Print</button></div>
<table><tr><th>No</th><th>Koreksi</th><th>Tanggal</th><th>Jenis</th><th>Nominal</th><th>Keterangan</th></tr><?php foreach($rows as $i=>$r):?><tr><td><?=$i+1?></td><td class="check-cell"><span class="check-box"></span></td><td><?=e($r['transaction_date'])?></td><td><?=$r['type']==='in'?'Uang Masuk':'Uang Keluar'?></td><td><?=rupiah($r['amount'])?></td><td><?=e($r['description'])?></td></tr><?php endforeach;?></table>
<div class="report-summary"><b>Total Masuk:</b> <?=rupiah($in)?> &nbsp; <b>Total Keluar:</b> <?=rupiah($out)?> &nbsp; <b>Saldo:</b> <?=rupiah($in-$out)?></div>
<section class="report-share"><h3>Hasil Pembagian Persentase</h3><p class="muted">Dasar pembagian: Total Uang Masuk (<?=rupiah($in)?>).</p><table><tr><th>No</th><th>Jenis Pembagian</th><th>Persentase</th><th>Hasil</th></tr><?php foreach($types as $i=>$t):$hasil=$in*((float)$t['percentage']/100);?><tr><td><?=$i+1?></td><td><?=e($t['name'])?></td><td><?=number_format((float)$t['percentage'],2)?>%</td><td><?=rupiah($hasil)?></td></tr><?php endforeach;?></table></section>
</div></body></html>