<?php require 'inc.php';chk();$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $s=$pdo->prepare('SELECT * FROM users WHERE username=?');$s->execute([trim($_POST['username'])]);$u=$s->fetch();
 if($u&&password_verify($_POST['password'],$u['password'])){session_regenerate_id(true);$_SESSION['u']=['id'=>$u['id'],'nama'=>$u['nama'],'role'=>$u['role'],'warga_id'=>$u['warga_id']];header('Location: index.php');exit;}
 $err='Nama pengguna atau kata sandi salah. Coba lagi.';}
head('Masuk');?>
<div class="card kecil"><h2>Masuk ke Akun Warga</h2><?php if($err)echo '<p class="err">'.e($err).'</p>';?>
<form method="post"><?=csrf()?><label>Nama pengguna<input name="username" autocomplete="username" required></label>
<label>Kata sandi<input type="password" id="p" name="password" autocomplete="current-password" required></label>
<label class="cb"><input type="checkbox" onclick="p.type=this.checked?'text':'password'"> Tampilkan kata sandi</label>
<button class="btn">Masuk</button></form><p class="mut">Lupa kata sandi? Hubungi Ketua RT.</p></div>
<?php foot();
