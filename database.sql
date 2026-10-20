SET NAMES utf8mb4;
CREATE TABLE IF NOT EXISTS warga(
 id INT AUTO_INCREMENT PRIMARY KEY,
 no_kk CHAR(16) NOT NULL, nik CHAR(16) NOT NULL UNIQUE, nama VARCHAR(100) NOT NULL,
 jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
 tempat_lahir VARCHAR(60), tanggal_lahir DATE, agama VARCHAR(20), pendidikan VARCHAR(40),
 pekerjaan VARCHAR(60), status_kawin VARCHAR(30), hubungan_keluarga VARCHAR(40),
 golongan_darah VARCHAR(3) DEFAULT '-', kewarganegaraan VARCHAR(10) DEFAULT 'WNI',
 status_tinggal VARCHAR(30) DEFAULT 'Tetap', no_hp VARCHAR(20), alamat VARCHAR(200),
 rt CHAR(3) DEFAULT '02', rw CHAR(3) DEFAULT '015',
 dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP, INDEX(no_kk), INDEX(nama)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS users(
 id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(40) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL, nama VARCHAR(100) NOT NULL,
 role ENUM('admin','rw','rt','warga') NOT NULL DEFAULT 'warga',
 warga_id INT NULL, FOREIGN KEY(warga_id) REFERENCES warga(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS berita(
 id INT AUTO_INCREMENT PRIMARY KEY,
 kategori ENUM('Pos Ronda','Kerja Bakti','Kriminal','Pengumuman','Kegiatan') NOT NULL,
 judul VARCHAR(150) NOT NULL, isi TEXT NOT NULL, tanggal DATE NOT NULL,
 penulis VARCHAR(100), dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- Akun awal: admin / admin123  (WAJIB diganti setelah login pertama)
INSERT INTO users(username,password,nama,role) VALUES('admin','$2b$10$qOhFkDCMQgJn1XKRIlX0s.1CVtdgMmbfjfG2N7ZBTJduln3YlBtzS','Administrator','admin');
INSERT INTO berita(kategori,judul,isi,tanggal,penulis) VALUES
('Kerja Bakti','Kerja bakti bersih selokan','Seluruh warga diharapkan hadir Minggu pukul 07.00 membawa cangkul dan sapu. Kumpul di pos ronda.',CURDATE(),'Administrator'),
('Pos Ronda','Jadwal ronda minggu ini','Senin-Selasa: Blok A. Rabu-Kamis: Blok B. Jumat-Minggu: Blok C. Ronda dimulai pukul 22.00.',CURDATE(),'Administrator');
