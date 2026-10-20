<?php
session_start();
require __DIR__.'/config.php';
try{$pdo=new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);}
catch(Exception $x){exit('Database belum tersambung. Periksa config.php');}
const STAFF=['admin','rw','rt'];
const ROLE=['admin'=>'Admin','rw'=>'Ketua RW','rt'=>'Ketua RT','warga'=>'Warga'];
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function user(){return $_SESSION['u']??null;}
function staff(){return user()&&in_array(user()['role'],STAFF);}
function need($r=null){if(!user()){header('Location: login.php');exit;}if($r&&!in_array(user()['role'],$r)){http_response_code(403);exit('Anda tidak punya akses ke halaman ini.');}}
function csrf(){$_SESSION['t']=$_SESSION['t']??bin2hex(random_bytes(16));return '<input type="hidden" name="t" value="'.$_SESSION['t'].'">';}
function chk(){if($_SERVER['REQUEST_METHOD']==='POST'&&!hash_equals($_SESSION['t']??'',$_POST['t']??''))exit('Sesi habis. Muat ulang halaman.');}
function tgl($d){return $d?date('d-m-Y',strtotime($d)):'-';}
function head($t){$u=user();$m=['index.php'=>'🏠 Beranda','berita.php'=>'📰 Berita'];
 if($u){$m['warga.php']=staff()?'👥 Data Warga':'🪪 Data Saya';$m['akun.php']=$u['role']=='admin'?'🔑 Akun':'🔑 Akun Saya';}
 echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.e($t).' - '.APP_NAME.'</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header><b class="logo">'.APP_NAME.'</b><span>RT '.APP_RT.' / RW '.APP_RW.'</span>'.($u?'<small>'.e($u['nama']).' ('.ROLE[$u['role']].') · <a href="logout.php">Keluar</a></small>':'<a class="btn k" href="login.php">Masuk</a>').'</header>
<nav>';foreach($m as $f=>$l)echo '<a href="'.$f.'"'.(basename($_SERVER['PHP_SELF'])==$f?' class="on"':'').'>'.$l.'</a>';echo '</nav><main>';}
function foot(){echo '</main><footer>Aplikasi RT '.APP_RT.' / RW '.APP_RW.'</footer></body></html>';}
