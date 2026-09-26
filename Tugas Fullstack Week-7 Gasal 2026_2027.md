1. Dikerjakan berkelompok (satu KP) sesuai daftar kelompok yang ada 

2. Untuk membuat tugas ini, gunakan pengetahuanmu juga dalam hal mendesain halaman web menggunakan HTML dan CSS 

3. File website dikumpulkan di ULS dalam format .zip selambatnya 10 Oktober 2026 pk. 23.59 

4. File pertama haruslah bernama index.php 

5. Menggunakan class soal dan class jawaban yang anda buat sendiri. Tidak boleh ada perintah SQL di dalam halaman soal dan halaman kesimpulan. Hal-hal yang terkait database diletakkan di dalam class 

6. Gunakan database bernama “fullstack”, userid = root, dan password nya kosong 

7. Kumpulkan juga file .sql yang sudah berisikan contoh data soal dan jawaban 

8. Tidak menggunakan syntax PHP yang tidak diajarkan 

_(contoh: jika anda menggunakan syntax php versi terbaru yang tidak kompatibel dengan php versi lama, hal ini juga dianggap sesuatu yang tidak pernah diajarkan, sehingga gagal dijalankan di komputernya dosen._ 

_Atau jika anda menggunakan syntax php yang tidak pernah diajarkan di kelas, mungkin akan dicurigai syntax tersebut berasal dari chatGPT._ 

_Atau jika anda menggunakan framework PHP apapun, ada controller, view, dll, juga akan dicurigai berasal dari chatGPT)_ 

# Database 

Nama database = fullstack, userid=root, password kosong Nama tabel dan nama atribut harus sama persis 



<!-- Start of picture text -->
—] soal v —] jawaban v<br>idsoal INT idjawaban INT<br>nomor INT<br>pertanyaan TEXT is = =e * idsoalis_jawaban INT  TEXT<br>halaman_ke INT benarkah TINYINT(1)<br>><br>><br><!-- End of picture text -->

## Kedua primary key AUTO_INCREMENT 

CONTOH DATA: 

soal 

|idsoal|nomor|pertanyaan|halaman_ke|
|---|---|---|---|
|1|1|Siapa nama saudara Upin?|1|
|2|2|Siapa nama nenek Upin?|1|
|15<br>….|3|Berikut ini adalah bukan nama teman Upin, kecuali|2|



## <u>jawaban</u> 

|idjawaban|idsoal|isi_jawaban|benarkah|
|---|---|---|---|
|7|15|Unyil|0|
|8|15|Jarjit|1|
|9|15|Android|0|
|10|15|Kak Ros|0|
|11|1|Ipin|1|
|12<br>...|1|Ehsan|0|



Kedua tabel sudah ada datanya. Siapkan secara manual datanya minimal 10 nomor soal. 

# Halaman Soal 

Pada halaman ini akan menampilkan soal dan jawabannya (berupa Radio Button) Per sekali tampil, bisa menampilkan 1, 2 atau berapa pun nomor soal sesuai dengan kolom “halaman_ke”. 

Misal sekarang menampilkan halaman 1: 



<!-- Start of picture text -->
1. Siapakah nama saudara Upin ?<br>© Opsi jawaban1<br>© Opsi jawaban2<br>© Opsi jawaban3<br>O Opsi jawaban4<br>2. Siapakah nama nenek Upin ?<br>© Opsi jawaban1<br>© Opsi jawaban2<br>© Opsi jawaban3<br>O Opsi jawaban4<br><!-- End of picture text -->

Misal sekarang menampilkan halaman 2: 



<!-- Start of picture text -->
3. Berikut ini adalah nama teman Upin, kecuali<br>O Android<br>O Jarjit<br>© Kak Ros<br>O Unyil<br><!-- End of picture text -->

- Opsi Jawaban posisi nya random. Berbeda-beda terus jika halaman direfresh 

- User bisa menjawab tiap soal. Asumsikan tidak ada nomor yang terlewat tidak dijawab oleh user 

- Jawaban user disimpan di **Session** 

- Untuk setiap halaman, ada 2 buah tombol submit, yaitu “Next” dan “Previous” (kecuali halaman pertama, tidak usah ada tombol “Previous”) 

Jika tombol Next diklik, jawaban user akan disimpan di Session sambil dicek apakah jawabannya benar atau salah, lalu lanjut ke halaman berikutnya. 

Jika tombol Previous diklik, awaban user akan disimpan di Session sambil dicek apakah jawabannya benar atau salah, lalu lanjut ke halaman sebelumnya. Ketika halaman sebelumnya diklik, jika di halaman sebelumnya tersebut si user pernah menjawab pertanyaan itu, maka pilihan jawaban user muncul di radio button nya <input type=’radio’ checked …. > 

Jika ini sudah di halaman terakhir, ketika tombol Next diklik, maka akan menuju halaman kesimpulan 

# Halaman Kesimpulan 

Menampilkan semua soal, dan jawaban user, beserta skor akhirnya. Anggap saja satu nomor benar bernilai 10 

1. Pertanyaan nomor 1 ….. 

   - Jawaban user : Lorem ipsum (benar) 

2. Pertanyaan nomor 2 …. 

   - Jawaban user : Lorem ipsum (benar) 

3. Pertanyaan nomor 3 …. Jawaban user : Lorem ipsum (salah) Jawaban benar : Ipsum lorem 

4. ….. 

Skor Akhir : 80 

Kemudian ada satu tombol lagi: 

“PLAY AGAIN” 

Tombol ini jika diklik maka akan kembali ke halaman soal, membersihkan Session, dan menampilkan soal halaman 1 lagi 

# Panduan Penilaian 

1. Halaman soal (80%) 

   - a. Menampilkan nomor-nomor soal per halaman Pada saat uji coba, isi kolom “halaman_ke” akan diubah manual lewat phpmyadmin dan browser akan kembali direfresh untuk melihat apakah halaman soal ini mampu menampilkan soal per halaman secara dinamis sesuai halamannya 

   - b. Pilihan jawaban ditampilkan di radio button secara random urutannya 

   - c. Jika user mengklik tombol Next atau Previous, jawaban akan disimpan di session 

   - d. Jika kembali ke halaman yang sudah pernah dijawab soalnya, maka jawaban user muncul di radio button nya (radio checked) 

2. Halaman kesimpulan (20%) 

   - a. Menampilkan hasil jawaban yang sesuai dengan isian dari user berikut juga skor akhirnya 

