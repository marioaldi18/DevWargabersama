<?php require 'inc.php';chk();$K=['Pos Ronda','Kerja Bakti','Kriminal','Pengumuman','Kegiatan'];
if(staff()&&isset($_POST['simpan'])&&in_array($_POST['kategori'],$K)){
 $pdo->prepare('INSERT INTO berita(kategori,judul,isi,tanggal,penulis) VALUES(?,?,?,?,?)')->execute([$_POST['kategori'],trim($_POST['judul']),trim($_POST['isi']),$_POST['tanggal'],user()['nama']]);header('Location: berita.php');exit;}
if(staff()&&isset($_POST['hapus'])){$pdo->prepare('DELETE FROM berita WHERE id=?')->execute([(int)$_POST['hapus']]);header('Location: berita.php');exit;}
$k=$_GET['k']??'';$q=$k&&in_array($k,$K)?$pdo->prepare('SELECT * FROM berita WHERE kategori=? ORDER BY tanggal DESC,id DESC'):$pdo->prepare('SELECT * FROM berita WHERE ?="" ORDER BY tanggal DESC,id DESC');
$q->execute([$k&&in_array($k,$K)?$k:'']);$b=$q->fetchAll();head('Berita');?>
<h2>Berita Lingkungan</h2><div class="filter"><a href="berita.php" class="<?=$k?'':'on'?>">Semua</a><?php foreach($K as $c)echo '<a href="berita.php?k='.urlencode($c).'" class="'.($k==$c?'on':'').'">'.$c.'</a>';?></div>
<?php if(staff()):?><details class="card"><summary class="btn">➕ Tulis Berita Baru</summary><form method="post"><?=csrf()?>
<label>Jenis berita<select name="kategori"><?php foreach($K as $c)echo "<option>$c</option>";?></select></label>
<label>Judul<input name="judul" maxlength="150" required></label><label>Tanggal<input type="date" name="tanggal" value="<?=date('Y-m-d')?>" required></label>
<label>Isi berita<textarea name="isi" rows="5" required></textarea></label><button class="btn" name="simpan" value="1">Simpan berita</button></form></details><?php endif;?>
<?php foreach($b as $r):?><article class="card k-<?=e(str_replace(' ','',$r['kategori']))?>"><span class="tag"><?=e($r['kategori'])?></span><h3><?=e($r['judul'])?></h3><small><?=tgl($r['tanggal'])?> · <?=e($r['penulis'])?></small><p><?=nl2br(e($r['isi']))?></p>
<?php if(staff()):?><form method="post" onsubmit="return confirm('Hapus berita ini?')"><?=csrf()?><button class="btn h" name="hapus" value="<?=$r['id']?>">Hapus</button></form><?php endif;?></article>
<?php endforeach;if(!$b)echo '<p class="mut">Belum ada berita di kategori ini.</p>';foot();
