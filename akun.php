<?php require 'inc.php';need();chk();$u=user();$msg='';
if(isset($_POST['sandi'])){$s=$pdo->prepare('SELECT password FROM users WHERE id=?');$s->execute([$u['id']]);
 if(!password_verify($_POST['lama'],$s->fetchColumn()))$msg='err|Kata sandi lama salah.';elseif(strlen($_POST['baru'])<6)$msg='err|Kata sandi baru minimal 6 karakter.';
 else{$pdo->prepare('UPDATE users SET password=? WHERE id=?')->execute([password_hash($_POST['baru'],PASSWORD_DEFAULT),$u['id']]);$msg='ok|Kata sandi berhasil diganti.';}}
if($u['role']=='admin'){
 if(isset($_POST['buat'])){try{$pdo->prepare('INSERT INTO users(username,password,nama,role,warga_id) VALUES(?,?,?,?,?)')->execute([trim($_POST['username']),password_hash($_POST['pw'],PASSWORD_DEFAULT),trim($_POST['nama']),array_key_exists($_POST['role'],ROLE)?$_POST['role']:'warga',$_POST['warga_id']?:null]);$msg='ok|Akun berhasil dibuat.';}catch(PDOException $x){$msg='err|Nama pengguna sudah dipakai.';}}
 if(isset($_POST['hapus'])&&(int)$_POST['hapus']!=$u['id']){$pdo->prepare('DELETE FROM users WHERE id=?')->execute([(int)$_POST['hapus']]);$msg='ok|Akun dihapus.';}
 if(isset($_POST['reset'])){$pdo->prepare('UPDATE users SET password=? WHERE id=?')->execute([password_hash('warga123',PASSWORD_DEFAULT),(int)$_POST['reset']]);$msg='ok|Kata sandi direset menjadi: warga123';}}
head('Akun');if($msg){[$c,$t]=explode('|',$msg);echo '<p class="'.$c.'">'.e($t).'</p>';}?>
<h2>Ganti Kata Sandi</h2><form method="post" class="card"><?=csrf()?><label>Kata sandi lama<input type="password" name="lama" required></label><label>Kata sandi baru (min. 6 karakter)<input type="password" name="baru" required></label><button class="btn" name="sandi" value="1">Simpan kata sandi</button></form>
<?php if($u['role']=='admin'):$W=$pdo->query('SELECT id,nama,nik FROM warga ORDER BY nama')->fetchAll();?>
<h2>Buat Akun Baru</h2><form method="post" class="card"><?=csrf()?><label>Nama lengkap<input name="nama" required></label><label>Nama pengguna<input name="username" required></label><label>Kata sandi awal<input name="pw" minlength="6" required></label>
<label>Tingkat akses<select name="role"><?php foreach(ROLE as $k=>$l)echo "<option value=\"$k\">$l</option>";?></select></label>
<label>Hubungkan ke data warga<select name="warga_id"><option value="">- tidak dihubungkan -</option><?php foreach($W as $w)echo '<option value="'.$w['id'].'">'.e($w['nama']).' ('.e($w['nik']).')</option>';?></select></label><button class="btn" name="buat" value="1">Buat akun</button></form>
<h2>Daftar Akun</h2><?php foreach($pdo->query('SELECT * FROM users ORDER BY role,nama') as $a):?><div class="card"><h3><?=e($a['nama'])?></h3><small><?=e($a['username'])?> · <?=ROLE[$a['role']]?></small>
<?php if($a['id']!=$u['id']):?><form method="post" onsubmit="return confirm('Yakin?')"><?=csrf()?><button class="btn" name="reset" value="<?=$a['id']?>">Reset sandi</button> <button class="btn h" name="hapus" value="<?=$a['id']?>">Hapus</button></form><?php endif;?></div><?php endforeach;endif;foot();
