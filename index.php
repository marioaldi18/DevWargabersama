<?php require 'inc.php';head('Beranda');
$n=$pdo->query("SELECT COUNT(*) j,COUNT(DISTINCT no_kk) kk,SUM(jenis_kelamin='Laki-laki') l,SUM(jenis_kelamin='Perempuan') p FROM warga")->fetch();
$b=$pdo->query('SELECT * FROM berita ORDER BY tanggal DESC,id DESC LIMIT 5')->fetchAll();?>
<section class="hero"><h1>Selamat Datang<?=user()?', '.e(explode(' ',user()['nama'])[0]):''?>!</h1><p>Informasi dan administrasi warga RT <?=APP_RT?> / RW <?=APP_RW?></p></section>
<div class="stat"><div><b><?=(int)$n['j']?></b>Warga</div><div><b><?=(int)$n['kk']?></b>Keluarga (KK)</div><div><b><?=(int)$n['l']?></b>Laki-laki</div><div><b><?=(int)$n['p']?></b>Perempuan</div></div>
<h2>Menu Cepat</h2><div class="menu">
<a href="berita.php?k=Pos+Ronda" class="m1">🔦<span>Pos Ronda</span></a><a href="berita.php?k=Kerja+Bakti" class="m2">🧹<span>Kerja Bakti</span></a>
<a href="berita.php?k=Kriminal" class="m3">🚨<span>Info Kriminal</span></a><a href="berita.php?k=Pengumuman" class="m4">📢<span>Pengumuman</span></a>
<a href="<?=user()?'warga.php':'login.php'?>" class="m5">🪪<span><?=staff()?'Data Warga':(user()?'Data Saya':'Masuk Warga')?></span></a></div>
<h2>Berita Terbaru</h2>
<?php foreach($b as $r):?><article class="card k-<?=e(str_replace(' ','',$r['kategori']))?>"><span class="tag"><?=e($r['kategori'])?></span><h3><?=e($r['judul'])?></h3><small><?=tgl($r['tanggal'])?></small><p><?=nl2br(e(mb_strimwidth($r['isi'],0,220,'...')))?></p></article>
<?php endforeach;if(!$b)echo '<p class="mut">Belum ada berita.</p>';?><a class="btn" href="berita.php">Lihat semua berita</a>
<?php foot();
