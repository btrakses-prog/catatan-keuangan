<?php
declare(strict_types=1);
session_start();
const DB_HOST='localhost'; const DB_NAME='wifi_kas'; const DB_USER='root'; const DB_PASS='toor';
function db():PDO{static $p=null;if(!$p)$p=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);return $p;}
function e($v):string{return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function rupiah($v):string{return 'Rp '.number_format((float)$v,0,',','.');}
function redirect($u):never{header('Location: '.$u);exit;}
function require_login():void{if(empty($_SESSION['admin_id']))redirect('login.php');}
