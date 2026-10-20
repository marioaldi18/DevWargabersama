PANDUAN PASANG (hosting PHP 8.1+ & MySQL/MariaDB, mis. cPanel)
1. Buat database + user di cPanel > MySQL Databases.
2. phpMyAdmin > pilih database > Import > pilih database.sql.
3. Edit config.php (DB_NAME, DB_USER, DB_PASS).
4. Upload isi folder ini ke public_html (atau subfolder).
5. Buka website, login: admin / password  -> segera ganti lewat menu "Sandi".
6. Menu Akun Pengguna (admin): buat akun Ketua RT, Ketua RW, dan warga
   (hubungkan akun warga ke Data Warga agar bisa melihat iuran/data sendiri).
Hak akses: Admin = semua. RT = kelola warga, iuran, kas, arisan, berita.
RW = kelola berita, lihat semua data. Warga = lihat berita, kas, dan data/iurannya sendiri.
Pasang HTTPS (SSL gratis di cPanel) sebelum dipakai warga.
