# Kawan Tenang --- Product Requirements Document (PRD)

**Versi:** 1.0\
**Tanggal:** 15 November 2026\
**Status:** Draft awal\
**Platform:** Web application\
**Bahasa utama:** Bahasa Indonesia

------------------------------------------------------------------------

## 1. Ringkasan Produk

**Kawan Tenang** adalah platform dukungan emosional dan konseling yang
menyediakan ruang aman bagi pengguna untuk bercerita, berinteraksi
secara anonim di komunitas, dan melakukan percakapan privat dengan
konselor.

Aplikasi memiliki tiga area utama:

1.  **Forum Publik** --- ruang komunitas untuk membagikan cerita dan
    memberi komentar dengan identitas publik anonim.
2.  **Konseling Privat** --- percakapan pribadi antara pengguna dan
    konselor.
3.  **Verifikasi Identitas** --- verifikasi wajah dengan pemeriksaan
    liveness melalui penyedia API pihak ketiga untuk membantu memastikan
    bahwa proses verifikasi dilakukan oleh orang hidup, bukan sekadar
    foto atau rekaman.

Kawan Tenang bukan layanan gawat darurat dan tidak menggantikan bantuan
profesional atau layanan kesehatan mental darurat.

## 2. Masalah yang Ingin Diselesaikan

Sebagian orang merasa sulit menceritakan masalah pribadi karena takut
dikenali, dihakimi, atau tidak memiliki tempat bercerita. Di sisi lain,
konselor membutuhkan sarana komunikasi privat dan pengelolaan percakapan
yang lebih terstruktur.

Kawan Tenang berupaya menyediakan: - ruang berbagi cerita dengan
identitas publik yang disamarkan; - komunikasi privat antara pengguna
dan konselor; - kontrol akses dan perlindungan data pribadi; - proses
verifikasi akun yang dapat membantu mengurangi penyalahgunaan identitas.

## 3. Tujuan Produk

### 3.1 Tujuan utama

-   Memudahkan pengguna berbagi cerita tanpa menampilkan identitas
    aslinya kepada komunitas.
-   Menyediakan komunikasi privat antara pengguna dan konselor.
-   Memberikan sarana moderasi untuk menjaga keamanan komunitas.
-   Menerapkan prinsip privasi dan keamanan sejak perancangan.

### 3.2 Indikator keberhasilan

-   Pengguna dapat mendaftar, masuk, dan mengelola profil.
-   Postingan serta komentar publik tidak menampilkan nama asli, email,
    atau ID pengguna.
-   Pengguna dapat memulai dan melanjutkan percakapan privat dengan
    konselor yang berwenang.
-   Pesan privat tersimpan dengan perlindungan akses yang benar.
-   Hasil verifikasi liveness dapat diterima dan diverifikasi oleh
    backend.
-   Admin dapat menangani laporan dan konten yang melanggar aturan.

## 4. Target Pengguna dan Peran

### 4.1 Pengguna

-   Membuat akun dan mengelola profil.
-   Melakukan verifikasi akun jika diwajibkan oleh kebijakan sistem.
-   Membuat postingan anonim.
-   Memberikan komentar anonim.
-   Mengirim dan menerima pesan konseling privat.
-   Melaporkan postingan, komentar, atau perilaku yang tidak pantas.

### 4.2 Konselor

-   Mengakses daftar percakapan yang ditugaskan kepadanya.
-   Membaca dan membalas pesan dari pengguna yang menjadi tanggung
    jawabnya.
-   Mengubah status percakapan sesuai alur kerja.
-   Menambahkan catatan konseling internal jika fitur ini diaktifkan.
-   Tidak dapat melihat percakapan yang tidak menjadi kewenangannya.

### 4.3 Administrator

-   Mengelola akun pengguna dan konselor.
-   Mengelola laporan dan moderasi konten.
-   Meninjau status verifikasi akun sesuai kewenangan.
-   Mengelola kategori, panduan komunitas, dan konfigurasi aplikasi.
-   Melihat log aktivitas administratif tanpa menampilkan isi pesan
    privat secara default.

## 5. Ruang Lingkup Fitur

### 5.1 Autentikasi dan akun

-   Registrasi menggunakan nama, email, dan kata sandi.
-   Login dan logout.
-   Verifikasi alamat email.
-   Lupa dan reset kata sandi.
-   Pengelolaan profil.
-   Role-based access control untuk pengguna, konselor, dan
    administrator.
-   Kata sandi disimpan menggunakan password hashing yang kuat, bukan
    enkripsi yang dapat dibalik.

### 5.2 Forum Publik

-   Pengguna dapat membuat postingan teks.
-   Pengguna dapat melihat daftar postingan komunitas.
-   Pengguna dapat memberikan komentar.
-   Nama penulis dan komentator ditampilkan sebagai **Anonim**.
-   Avatar publik menggunakan avatar anonim generik, bukan foto profil
    asli.
-   Pemilik postingan dapat mengedit atau menghapus postingannya sesuai
    aturan.
-   Pengguna dapat melaporkan postingan dan komentar.
-   Admin dapat menyembunyikan atau menghapus konten yang melanggar
    aturan.

**Aturan privasi:** identitas asli tetap disimpan secara internal
melalui relasi akun untuk kebutuhan kepemilikan konten, moderasi, dan
penanganan laporan. Identitas tersebut tidak boleh dikirim ke tampilan
publik atau endpoint publik.

Postingan dan komentar memang ditujukan untuk dibaca komunitas. Karena
itu, anonimitas publik tidak sama dengan enkripsi konten. Konten publik
tidak perlu dienkripsi hanya untuk menyamarkan nama penulis. Kontrol
akses dan pemisahan identitas harus diterapkan dengan benar.

### 5.3 Konseling privat

-   Pengguna dapat mengajukan atau memulai sesi konseling.
-   Sistem menghubungkan sesi dengan konselor yang ditugaskan.
-   Pengguna dan konselor dapat bertukar pesan privat.
-   Pesan memiliki penanda waktu dan status pengiriman/baca jika fitur
    tersebut diperlukan.
-   Percakapan hanya dapat diakses oleh pihak yang berwenang.
-   Konselor dapat mengakhiri atau memperbarui status sesi.
-   Catatan internal konselor, jika tersedia, dipisahkan dari pesan yang
    dapat dibaca pengguna.

**Keputusan keamanan awal:** pesan privat dilindungi dengan enkripsi
pada sisi server menggunakan implementasi kriptografi yang telah diaudit
untuk penggunaan produksi. Jika tugas kuliah mengharuskan implementasi
algoritma sendiri, implementasi tersebut dibuat sebagai modul
demonstrasi dan pengujian terpisah, bukan langsung dijadikan
satu-satunya perlindungan data sensitif.

### 5.4 Verifikasi wajah dan liveness

-   Pengguna memulai proses verifikasi dari halaman verifikasi.
-   Backend Laravel membuat sesi verifikasi melalui API penyedia.
-   Pengguna menyelesaikan proses pada halaman atau SDK yang disediakan
    penyedia.
-   Penyedia melakukan pemeriksaan liveness dan, jika workflow dipilih,
    pencocokan wajah 1:1.
-   Penyedia mengirim hasil melalui webhook atau backend mengambil
    status sesi melalui API.
-   Backend memvalidasi keaslian webhook, memeriksa status sesi, dan
    memperbarui status verifikasi pengguna.
-   Pengguna melihat status: belum diverifikasi, menunggu, berhasil,
    atau gagal/perlu ditinjau.

Verifikasi wajah bukan satu-satunya cara untuk memperoleh bantuan.
Jangan menghalangi akses ke bantuan dasar atau informasi darurat hanya
karena pengguna belum melakukan verifikasi wajah.

### 5.5 Moderasi dan pelaporan

-   Pengguna dapat melaporkan konten atau akun.
-   Admin dapat melihat antrean laporan.
-   Admin dapat memberi status laporan: baru, ditinjau, ditindaklanjuti,
    atau ditolak.
-   Admin dapat menyembunyikan konten dan memberikan alasan tindakan.
-   Aktivitas moderasi dicatat dalam audit log.

### 5.6 Dashboard

**Dashboard pengguna** - Ringkasan profil dan status verifikasi. -
Daftar sesi konseling. - Riwayat postingan dan komentar milik sendiri.

**Dashboard konselor** - Daftar sesi yang ditugaskan. - Ringkasan status
sesi. - Akses ke percakapan yang menjadi kewenangannya.

**Dashboard admin** - Ringkasan jumlah pengguna, konselor, sesi,
postingan, dan laporan. - Manajemen akun dan role. - Moderasi
komunitas. - Pengelolaan status verifikasi.

## 6. Alur Pengguna Utama

### 6.1 Registrasi

1.  Pengguna membuka halaman pendaftaran.
2.  Pengguna mengisi data akun.
3.  Sistem memvalidasi input dan membuat akun.
4.  Sistem mengirim email verifikasi.
5.  Pengguna memverifikasi email lalu masuk.

### 6.2 Membuat postingan anonim

1.  Pengguna masuk ke akun.
2.  Pengguna membuka Forum Publik.
3.  Pengguna menulis cerita dan mengirim postingan.
4.  Backend menyimpan konten beserta relasi pemilik internal.
5.  Tampilan publik hanya menampilkan label "Anonim" dan avatar generik.

### 6.3 Konseling privat

1.  Pengguna membuka menu konseling.
2.  Pengguna mengajukan sesi.
3.  Sistem membuat sesi dan menetapkan konselor.
4.  Pengguna dan konselor bertukar pesan.
5.  Backend memeriksa hak akses setiap kali percakapan dibuka atau pesan
    dikirim.
6.  Sesi dapat ditandai selesai sesuai alur kerja.

### 6.4 Verifikasi wajah

1.  Pengguna menyetujui penjelasan tujuan dan pemrosesan data biometrik.
2.  Laravel meminta sesi verifikasi ke penyedia API.
3.  Pengguna menyelesaikan pemeriksaan liveness pada flow penyedia.
4.  Penyedia menjalankan pemeriksaan wajah sesuai konfigurasi.
5.  Penyedia mengirim webhook hasil.
6.  Laravel memvalidasi signature webhook dan mencocokkan ID sesi.
7.  Status verifikasi diperbarui dan ditampilkan kepada pengguna.

## 7. Integrasi Face Recognition dan Liveness

### 7.1 Penyedia awal yang direkomendasikan: Didit

Didit menjadi kandidat integrasi awal karena dokumentasi publiknya
menyediakan API, workflow, sandbox, passive liveness, dan face match
1:1. Informasi paket gratis yang diperiksa pada 15 November 2026
menyebutkan kuota 500 verifikasi lengkap per workspace per bulan untuk
workflow KYC yang mencakup ID verification, passive liveness, face
match, dan analisis IP.

**Catatan penting tentang jenis liveness:** - Kuota gratis yang
dijelaskan Didit mencakup **passive liveness**. - **Active liveness**
(misalnya meminta pengguna melakukan tindakan atau tantangan tertentu)
tercantum sebagai fitur berbayar. - Jangan menyebut active liveness
sebagai gratis. Konfirmasi kembali harga, kuota, wilayah dukungan, dan
syarat penggunaan sebelum deployment karena kebijakan vendor dapat
berubah.

Referensi: - https://didit.me/pricing/ - https://didit.me/developers/ -
https://didit.me/id/solutions/countries/indonesia/ -
https://help.didit.me/getting-started/free-plan

### 7.2 Konfigurasi integrasi

-   API key disimpan di environment variable server, bukan di JavaScript
    frontend atau repository.
-   Laravel membuat sesi verifikasi dari backend.
-   Frontend hanya menerima URL/token sesi yang memang aman untuk
    digunakan oleh browser.
-   Webhook harus memverifikasi signature sesuai dokumentasi penyedia.
-   Backend memeriksa ID sesi, status akhir, dan keterkaitan dengan
    pengguna yang benar.
-   Terapkan idempotency agar webhook berulang tidak memproses hasil
    yang sama berkali-kali.
-   Simpan data minimum: provider, session ID, status, waktu, dan kode
    alasan yang diperlukan.
-   Hindari menyimpan foto selfie mentah atau template biometrik jika
    tidak dibutuhkan.
-   Jika data biometrik harus disimpan, wajib ada dasar pemrosesan,
    persetujuan yang jelas, pembatasan akses, enkripsi, masa retensi,
    dan prosedur penghapusan.

### 7.3 Alternatif

Penyedia lain dapat dievaluasi jika kebutuhan, harga, dukungan
Indonesia, dan kebijakan data Didit tidak sesuai. Jangan mengunci desain
aplikasi pada satu vendor; buat lapisan adapter/provider agar penyedia
dapat diganti tanpa mengubah seluruh alur bisnis.

## 8. Kriptografi dan Perlindungan Data

### 8.1 Modul pembelajaran kriptografi klasik

Untuk kebutuhan pembelajaran, aplikasi dapat memiliki demonstrasi
Vigenère Cipher: - pengguna memasukkan plaintext dan kunci; - sistem
menampilkan ciphertext; - sistem dapat melakukan dekripsi untuk
menunjukkan plaintext kembali; - sistem menampilkan proses perhitungan
untuk keperluan pembelajaran.

**Batasan:** Vigenère adalah algoritma klasik dan tidak aman untuk
melindungi percakapan nyata. Jangan gunakan Vigenère untuk mengenkripsi
pesan konseling, kata sandi, token, atau data biometrik.

### 8.2 Perlindungan produksi

-   Gunakan HTTPS untuk seluruh komunikasi.
-   Gunakan password hashing, bukan enkripsi password.
-   Gunakan enkripsi terautentikasi yang telah diaudit untuk data privat
    saat disimpan.
-   Pisahkan kunci enkripsi dari database dan repository.
-   Terapkan otorisasi per percakapan dan per pesan.
-   Jangan menulis isi pesan, token, API key, atau data biometrik ke
    log.
-   Terapkan backup terenkripsi, kebijakan retensi, dan prosedur
    penghapusan data.
-   Pertimbangkan bahwa enkripsi sisi server berarti server masih
    memiliki kemampuan mendekripsi pesan. Jangan mengklaim end-to-end
    encryption jika sistem bukan E2EE.

## 9. Persyaratan Non-Fungsional

### Keamanan

-   Validasi input di backend.
-   Proteksi CSRF pada form web.
-   Rate limiting untuk login, pengiriman pesan, pelaporan, dan
    permintaan verifikasi.
-   Role-based access control dan policy Laravel.
-   Webhook verification untuk integrasi pihak ketiga.
-   Audit log untuk tindakan sensitif.
-   Secret management melalui environment/configuration server.

### Privasi

-   Identitas publik pengguna selalu disamarkan pada Forum Publik.
-   Data biometrik diperlakukan sebagai data pribadi sensitif.
-   Persetujuan harus menjelaskan tujuan, penyedia, jenis data, retensi,
    dan konsekuensi pemrosesan.
-   Sediakan mekanisme penarikan persetujuan dan penghapusan data sesuai
    kewajiban hukum.
-   Batasi pengumpulan data hanya pada yang benar-benar diperlukan.

### Kinerja dan kompatibilitas

-   Tampilan responsif untuk desktop, tablet, dan ponsel.
-   Pagination untuk feed, komentar, dan riwayat percakapan.
-   Proses verifikasi eksternal tidak boleh membuat request web pengguna
    menggantung tanpa batas.
-   Gunakan queue untuk pekerjaan asinkron seperti email dan pemrosesan
    webhook bila diperlukan.

### Aksesibilitas dan pengalaman

-   Kontras teks harus tetap terbaca meskipun memakai warna lembut.
-   Semua kontrol memiliki label yang jelas.
-   Status tidak hanya dibedakan dengan warna.
-   Sediakan indikator loading, empty state, error state, dan konfirmasi
    tindakan.

## 10. Teknologi yang Digunakan

  Bagian                Teknologi
  --------------------- ----------------------------------
  Backend               Laravel
  Frontend              Blade
  CSS                   Tailwind CSS
  Interaksi ringan      Alpine.js
  Database              MySQL
  Face verification     Didit API (kandidat awal)
  Integrasi eksternal   REST API dan webhook
  Autentikasi           Laravel authentication
  Otorisasi             Laravel Policies / Gates
  Queue                 Laravel Queue (sesuai kebutuhan)

Arsitektur awal menggunakan monolith Laravel agar pengembangan dan
deployment tugas kuliah tetap sederhana. Face verification dijalankan
melalui API penyedia, bukan membangun model pengenalan wajah sendiri.

## 11. Design System

### 11.1 Arah visual

Gaya visual menggunakan **soft neuromorphism** dengan tampilan lembut,
tenang, dan tidak ramai. Efek timbul/cekung digunakan secara terbatas
pada kartu, tombol, input, dan panel. Hindari bayangan berlebihan yang
mengurangi kontras atau keterbacaan.

### 11.2 Palet warna

Palet awal yang disarankan:

  -----------------------------------------------------------------------
  Token             Warna             Hex               Penggunaan
  ----------------- ----------------- ----------------- -----------------
  Primary           Sage Green        `#789B8A`         Tombol utama,
                                                        navigasi aktif,
                                                        aksi utama

  Secondary         Mist Blue         `#A9C7D5`         Panel sekunder,
                                                        informasi, elemen
                                                        pendukung

  Accent            Soft Peach        `#E8B89D`         Sorotan, badge
                                                        penting, aksen
                                                        interaksi

  Background        Warm Ivory        `#F3F1EA`         Latar utama

  Surface           Soft Cream        `#F8F7F2`         Kartu dan
                                                        permukaan

  Text              Deep Slate        `#34434A`         Teks utama

  Muted             Stone Gray        `#78858A`         Teks sekunder

  Success           Muted Green       `#668C72`         Status berhasil

  Danger            Soft Brick        `#B96D68`         Error dan
                                                        tindakan berisiko
  -----------------------------------------------------------------------

Primary, secondary, dan accent adalah warna identitas utama. Warna
status digunakan secara semantik dan tidak menggantikan tiga warna
utama.

### 11.3 Prinsip neuromorphism

-   Gunakan background netral yang konsisten.
-   Gunakan dua bayangan lembut dengan arah berlawanan secara hemat.
-   Gunakan inset shadow hanya untuk input atau elemen yang tampak
    cekung.
-   Jangan memakai shadow sebagai satu-satunya pembeda komponen.
-   Pastikan teks dan ikon tetap memiliki kontras yang memadai.
-   Sediakan focus ring yang jelas untuk navigasi keyboard.
-   Hindari penggunaan neuromorphism pada setiap elemen sekaligus.

### 11.4 Komponen antarmuka

-   Navbar dan sidebar.
-   Card postingan anonim.
-   Avatar anonim.
-   Form postingan dan komentar.
-   Chat bubble untuk pengirim dan penerima.
-   Status badge sesi konseling.
-   Form dan status verifikasi wajah.
-   Modal konfirmasi dan dialog laporan.
-   Toast notification.
-   Skeleton, empty state, dan error state.

## 12. Rancangan Data Awal

Tabel berikut adalah rancangan konseptual dan dapat disesuaikan saat ERD
dibuat.

  -----------------------------------------------------------------------
  Tabel                               Fungsi dan data utama
  ----------------------------------- -----------------------------------
  users                               Identitas akun, email, password
                                      hash, status akun

  roles / user_roles                  Peran pengguna, konselor, dan admin

  profiles                            Informasi profil yang diperlukan

  posts                               Konten postingan dan user_id
                                      pemilik internal

  comments                            Komentar dan user_id pemilik
                                      internal

  post_reports                        Laporan postingan

  comment_reports                     Laporan komentar

  counseling_sessions                 Pengguna, konselor, status, waktu
                                      sesi

  messages                            Sesi, pengirim, ciphertext,
                                      metadata enkripsi, waktu

  counselor_notes                     Catatan internal konselor yang
                                      dibatasi aksesnya

  face_verifications                  Provider, session_id, status,
                                      waktu, metadata minimum

  audit_logs                          Aktor, aksi, target, waktu,
                                      metadata aman
  -----------------------------------------------------------------------

Jangan menyimpan nama asli atau email sebagai atribut yang ditampilkan
di feed publik. Gunakan relasi internal untuk menentukan kepemilikan dan
hak akses.

## 13. Aturan Bisnis dan Validasi

-   Hanya pengguna terautentikasi yang dapat membuat postingan atau
    komentar.
-   Identitas penulis publik selalu dirender sebagai "Anonim".
-   Pengguna hanya dapat mengubah atau menghapus konten miliknya,
    kecuali admin sesuai kewenangan moderasi.
-   Hanya peserta sesi dan pihak berwenang yang dapat membaca
    percakapan.
-   Konselor hanya dapat mengakses sesi yang ditugaskan kepadanya.
-   Status verifikasi hanya diperbarui berdasarkan respons API/webhook
    yang valid.
-   Kegagalan vendor atau timeout tidak boleh otomatis dianggap sebagai
    verifikasi berhasil.
-   Percobaan verifikasi harus dibatasi untuk mencegah penyalahgunaan
    dan biaya tak terduga.
-   Konten komunitas harus memiliki mekanisme pelaporan dan moderasi.

## 14. Di Luar Ruang Lingkup MVP

-   Panggilan suara atau video.
-   Aplikasi native Android dan iOS.
-   Model face recognition yang dilatih sendiri.
-   Pembayaran dan langganan konseling.
-   Rekomendasi konselor berbasis AI.
-   Chatbot yang memberikan diagnosis kesehatan mental.
-   End-to-end encryption penuh.
-   Integrasi rekam medis atau sistem rumah sakit.

## 15. Prioritas Pengembangan MVP

### Tahap 1 --- Fondasi

-   Setup Laravel, Blade, Tailwind CSS, dan MySQL.
-   Autentikasi, role, profil, dan kebijakan akses.
-   Design system dan layout responsif.

### Tahap 2 --- Komunitas

-   Feed Forum Publik.
-   Posting dan komentar anonim.
-   Edit/hapus konten milik sendiri.
-   Pelaporan dan moderasi dasar.

### Tahap 3 --- Konseling

-   Pengajuan dan penugasan sesi.
-   Percakapan privat.
-   Otorisasi akses percakapan.
-   Perlindungan penyimpanan pesan.

### Tahap 4 --- Verifikasi

-   Pembuatan workflow dan sesi Didit.
-   Integrasi halaman/SDK verifikasi.
-   Webhook dan validasi signature.
-   Status verifikasi dan penanganan kegagalan.

### Tahap 5 --- Pengujian dan penyempurnaan

-   Pengujian hak akses dan kebocoran identitas.
-   Pengujian alur webhook berulang dan gagal.
-   Pengujian responsif dan aksesibilitas.
-   Pengujian keamanan dasar dan kebijakan retensi.

## 16. Kriteria Penerimaan MVP

MVP dianggap memenuhi kebutuhan awal jika: - Pengguna dapat mendaftar,
login, dan logout. - Pengguna dapat membuat postingan dan komentar
anonim. - Tidak ada nama, email, atau ID internal yang bocor ke
tampilan/API publik. - Pengguna dan konselor dapat berkomunikasi hanya
dalam sesi yang sah. - Admin dapat meninjau laporan dan melakukan
moderasi. - Proses verifikasi dapat dibuat, diselesaikan, dan hasilnya
diperbarui melalui webhook yang tervalidasi. - API key tidak terekspos
di browser atau repository. - Halaman utama, Forum Publik, chat, dan
dashboard responsif. - Kebijakan privasi menjelaskan penggunaan data,
termasuk pemrosesan biometrik oleh penyedia pihak ketiga.

## 17. Risiko dan Mitigasi

  -----------------------------------------------------------------------
  Risiko                              Mitigasi
  ----------------------------------- -----------------------------------
  Kebocoran identitas dari Forum     DTO/resource publik hanya mengirim
  Publik                              label anonim dan data yang
                                      diperlukan

  Akses percakapan tanpa izin         Policy Laravel dan pemeriksaan
                                      akses pada setiap operasi

  Penyalahgunaan foto/video saat      Gunakan liveness dari penyedia;
  verifikasi                          pahami cakupan passive vs active
                                      liveness

  Webhook palsu atau replay           Verifikasi signature, timestamp
                                      bila tersedia, dan idempotency

  Biaya API setelah kuota gratis      Pantau pemakaian, batasi percobaan,
                                      dan nonaktifkan modul berbayar yang
                                      tidak diperlukan

  Kebocoran data biometrik            Minimalkan penyimpanan, batasi
                                      retensi, enkripsi, dan batasi akses

  Algoritma klasik dianggap aman      Pisahkan demonstrasi Vigenère dari
  untuk produksi                      sistem penyimpanan pesan sebenarnya

  Pengguna membutuhkan bantuan        Tampilkan informasi bantuan darurat
  darurat                             dan jelaskan batas layanan
  -----------------------------------------------------------------------

## 18. Catatan Hukum dan Etika

Data wajah dan data biometrik memerlukan perlindungan khusus. Sebelum
mengaktifkan verifikasi pada pengguna nyata: - jelaskan tujuan dan dasar
pemrosesan; - minta persetujuan yang sah jika diperlukan; - jelaskan
bahwa pemeriksaan dilakukan oleh penyedia pihak ketiga; - tinjau lokasi
pemrosesan, retensi, subprosesor, dan ketentuan vendor; - sediakan
mekanisme akses, koreksi, penarikan persetujuan, dan penghapusan sesuai
ketentuan yang berlaku; - konsultasikan kewajiban berdasarkan UU
Perlindungan Data Pribadi Indonesia dan aturan terkait.

Dokumen ini merupakan PRD produk, bukan nasihat hukum atau jaminan
kepatuhan.

------------------------------------------------------------------------

**Arah produk:** Kawan Tenang dibangun sebagai ruang dukungan emosional
yang mengutamakan privasi, anonimitas publik, akses konseling yang
terkontrol, dan pengalaman visual yang lembut. Verifikasi wajah membantu
proses kepercayaan akun, tetapi tidak boleh menjadi alasan untuk
mengumpulkan data biometrik secara berlebihan.
