<?php require 'inc.php';need();chk();$u=user();
$O=['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'];
$F=['no_kk'=>['No. Kartu Keluarga (16 angka)','n'],'nik'=>['NIK (16 angka)','n'],'nama'=>['Nama Lengkap','t'],'jenis_kelamin'=>['Jenis Kelamin',['Laki-laki','Perempuan']],
'tempat_lahir'=>['Tempat Lahir','t'],'tanggal_lahir'=>['Tanggal Lahir','d'],'agama'=>['Agama',$O],
'pendidikan'=>['Pendidikan Terakhir',['Tidak/Belum Sekolah','SD','SMP','SMA/SMK','D1-D3','S1','S2','S3']],'pekerjaan'=>['Pekerjaan','t'],
'status_kawin'=>['Status Perkawinan',['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati']],'hubungan_keluarga'=>['Hubungan dalam Keluarga',['Kepala Keluarga','Istri','Anak','Orang Tua','Famili Lain']],
'golongan_darah'=>['Golongan Darah',['-','A','B','AB','O']],'kewarganegaraan'=>['Kewarganegaraan',['WNI','WNA']],'status_tinggal'=>['Status Tinggal',['Tetap','Kontrak/Kost']],
'no_hp'=>['No. HP/WhatsApp','t'],'alamat'=>['Alamat Lengkap','t'],'rt'=>['RT','t'],'rw'=>['RW','t']];
if(!staff()){head('Data Saya');$s=$pdo->prepare('SELECT * FROM warga WHERE id=?');$s->execute([$u['warga_id']]);$w=$s->fetch();
 echo '<h2>Data Kependudukan Saya</h2>';if(!$w)echo '<p class="card">Akun Anda belum dihubungkan ke data warga. Hubungi Ketua RT.</p>';else{echo '<div class="card"><table class="dt">';foreach($F as $f=>$d)echo '<tr><th>'.$d[0].'</th><td>'.e($d[1]=='d'?tgl($w[$f]):$w[$f]).'</td></tr>';echo '</table></div>';}foot();exit;}
$err='';$id=(int)($_POST['id']??$_GET['id']??0);
if(isset($_POST['hapus'])&&in_array($u['role'],['admin','rw'])){$pdo->prepare('DELETE FROM warga WHERE id=?')->execute([(int)$_POST['hapus']]);header('Location: warga.php');exit;}
if(isset($_POST['simpan'])){$v=[];foreach($F as $f=>$d)$v[$f]=trim($_POST[$f]??'');
 if(!preg_match('/^\d{16}$/',$v['nik'])||!preg_match('/^\d{16}$/',$v['no_kk']))$err='NIK dan No. KK harus 16 angka.';
 else try{$v['tanggal_lahir']=$v['tanggal_lahir']?:null;
  if($id){$st=implode(',',array_map(fn($f)=>"$f=:$f",array_keys($v)));$v['id']=$id;$pdo->prepare("UPDATE warga SET $st WHERE id=:id")->execute($v);}
  else $pdo->prepare('INSERT INTO warga('.implode(',',array_keys($v)).') VALUES(:'.implode(',:',array_keys($v)).')')->execute($v);
  header('Location: warga.php?ok=1');exit;}catch(PDOException $x){$err='NIK ini sudah terdaftar.';}}
head('Data Warga');$cari=trim($_GET['cari']??'');
if(isset($_GET['baru'])||$id){$w=[];if($id){$s=$pdo->prepare('SELECT * FROM warga WHERE id=?');$s->execute([$id]);$w=$s->fetch()?:[];}
 $w=$_POST?:$w;?><h2><?=$id?'Ubah':'Tambah'?> Data Warga</h2><?php if($err)echo '<p class="err">'.e($err).'</p>';?><form method="post" class="card"><?=csrf()?><input type="hidden" name="id" value="<?=$id?>">
<?php foreach($F as $f=>$d){$val=$w[$f]??($f=='rt'?APP_RT:($f=='rw'?APP_RW:''));echo '<label>'.$d[0];
 if(is_array($d[1])){echo '<select name="'.$f.'">';foreach($d[1] as $o)echo '<option'.($o==$val?' selected':'').'>'.e($o).'</option>';echo '</select>';}
 else echo '<input name="'.$f.'" type="'.($d[1]=='d'?'date':'text').'" value="'.e($val).'"'.($d[1]=='n'?' inputmode="numeric" maxlength="16"':'').(in_array($f,['nik','no_kk','nama'])?' required':'').'>';echo '</label>';}?>
<button class="btn" name="simpan" value="1">Simpan data</button> <a class="btn h" href="warga.php">Batal</a></form><?php foot();exit;}
$s=$pdo->prepare('SELECT * FROM warga WHERE nama LIKE ? OR nik LIKE ? OR no_kk LIKE ? ORDER BY no_kk,nama LIMIT 200');$s->execute(["%$cari%","%$cari%","%$cari%"]);$r=$s->fetchAll();?>
<h2>Data Warga</h2><?php if(isset($_GET['ok']))echo '<p class="ok">Data berhasil disimpan.</p>';?>
<form class="cari"><input name="cari" placeholder="Cari nama / NIK / No. KK" value="<?=e($cari)?>"><button class="btn">Cari</button></form><a class="btn" href="?baru=1">➕ Tambah Warga</a>
<?php foreach($r as $w):?><div class="card"><h3><?=e($w['nama'])?></h3><small>NIK <?=e($w['nik'])?> · KK <?=e($w['no_kk'])?></small>
<p><?=e($w['jenis_kelamin'])?>, <?=tgl($w['tanggal_lahir'])?> · <?=e($w['pekerjaan'])?> · <?=e($w['hubungan_keluarga'])?><br><?=e($w['alamat'])?> · HP <?=e($w['no_hp'])?></p>
<a class="btn" href="?id=<?=$w['id']?>">Lihat / Ubah</a><?php if(in_array($u['role'],['admin','rw'])):?> <form method="post" style="display:inline" onsubmit="return confirm('Hapus data warga ini?')"><?=csrf()?><button class="btn h" name="hapus" value="<?=$w['id']?>">Hapus</button></form><?php endif;?></div>
<?php endforeach;if(!$r)echo '<p class="mut">Data tidak ditemukan.</p>';foot();
