<?php
session_start();
require_once "soal.php";
require_once "jawaban.php";

// Inisialisasi object class soal dan class jawaban
$soalObj = new soal();
$jawabanObj = new jawaban();

// Penanganan Reset / Play Again
if (isset($_GET['reset']) && $_GET['reset'] == '1') {
    unset($_SESSION['jawaban']);
    $redirect_url = basename($_SERVER['PHP_SELF']);
    header("Location: " . $redirect_url);
    exit();
}

// Penanganan submit Next atau Previous
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = isset($_POST['aksi']) ? $_POST['aksi'] : '';
    $halaman_aktif = isset($_POST['halaman_aktif']) ? (int)$_POST['halaman_aktif'] : 1;

    // Simpan jawaban user ke Session sambil dicek apakah benar atau salah
    if (isset($_POST['jawaban']) && is_array($_POST['jawaban'])) {
        if (!isset($_SESSION['jawaban'])) {
            $_SESSION['jawaban'] = [];
        }
        foreach ($_POST['jawaban'] as $idsoal => $idjawaban) {
            $idsoal = (int)$idsoal;
            $idjawaban = (int)$idjawaban;

            // Periksa jawaban dengan method class jawaban
            $is_benar = $jawabanObj->cekJawaban($idjawaban);

            // Simpan ke Session
            $_SESSION['jawaban'][$idsoal] = [
                'idjawaban' => $idjawaban,
                'benarkah'  => $is_benar
            ];
        }
    }

    // Ambil daftar nomor halaman dari database secara dinamis
    $daftar_halaman = $soalObj->getDaftarHalaman();
    $current_index = array_search($halaman_aktif, $daftar_halaman);
    if ($current_index === false) {
        $current_index = 0;
    }

    $target_file = basename($_SERVER['PHP_SELF']);

    if ($aksi === 'Next') {
        // Jika sudah di halaman terakhir, lanjut ke halaman kesimpulan
        if ($current_index >= count($daftar_halaman) - 1) {
            header("Location: kesimpulan.php");
            exit();
        } else {
            // Lanjut ke halaman berikutnya
            $next_page = $daftar_halaman[$current_index + 1];
            header("Location: " . $target_file . "?halaman=" . $next_page);
            exit();
        }
    } elseif ($aksi === 'Previous') {
        // Lanjut ke halaman sebelumnya
        if ($current_index > 0) {
            $prev_page = $daftar_halaman[$current_index - 1];
            header("Location: " . $target_file . "?halaman=" . $prev_page);
            exit();
        } else {
            header("Location: " . $target_file);
            exit();
        }
    }
}

// Menampilkan Halaman Soal (GET Request)
$daftar_halaman = $soalObj->getDaftarHalaman();
if (empty($daftar_halaman)) {
    die("Belum ada data soal pada database fullstack.");
}

$halaman_aktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : $daftar_halaman[0];
if (!in_array($halaman_aktif, $daftar_halaman)) {
    $halaman_aktif = $daftar_halaman[0];
}

$current_index = array_search($halaman_aktif, $daftar_halaman);
$is_first = ($current_index === 0);
$is_last = ($current_index === count($daftar_halaman) - 1);

// Ambil soal berdasarkan halaman_ke secara dinamis
$soal_list = $soalObj->getSoalByHalaman($halaman_aktif);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuis Online - UTS Full-stack Programming</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header-title">
            <h1>Kuis Online</h1>
            <p>Halaman <?= ($current_index + 1) ?> dari <?= count($daftar_halaman) ?></p>
        </div>

        <form action="" method="POST">
            <input type="hidden" name="halaman_aktif" value="<?= $halaman_aktif ?>">

            <?php foreach ($soal_list as $soal): ?>
                <?php
                $idsoal = (int)$soal['idsoal'];
                $nomor = (int)$soal['nomor'];
                $pertanyaan = htmlspecialchars($soal['pertanyaan']);

                // Opsi jawaban posisi random di tiap refresh (ORDER BY RAND())
                $opsi_list = $jawabanObj->getJawabanBySoal($idsoal);

                // Cek jawaban sebelumnya di Session
                $selected_idjawaban = isset($_SESSION['jawaban'][$idsoal]['idjawaban']) 
                    ? (int)$_SESSION['jawaban'][$idsoal]['idjawaban'] 
                    : null;
                ?>
                <div class="soal-card">
                    <div class="pertanyaan-text">
                        <?= $nomor ?>. <?= $pertanyaan ?>
                    </div>
                    <div class="opsi-list">
                        <?php foreach ($opsi_list as $opsi): ?>
                            <?php
                            $idjawaban = (int)$opsi['idjawaban'];
                            $isi_jawaban = htmlspecialchars($opsi['isi_jawaban']);
                            $is_checked = ($selected_idjawaban === $idjawaban) ? 'checked' : '';
                            $input_id = "opsi_" . $idsoal . "_" . $idjawaban;
                            ?>
                            <label class="opsi-item" for="<?= $input_id ?>">
                                <input type="radio" 
                                       id="<?= $input_id ?>" 
                                       name="jawaban[<?= $idsoal ?>]" 
                                       value="<?= $idjawaban ?>" 
                                       <?= $is_checked ?> 
                                       required>
                                <span class="opsi-label"><?= $isi_jawaban ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="nav-buttons">
                <?php if (!$is_first): ?>
                    <button type="submit" name="aksi" value="Previous" class="btn btn-prev" formnovalidate>Previous</button>
                <?php endif; ?>
                <button type="submit" name="aksi" value="Next" class="btn btn-next">Next</button>
            </div>
        </form>
    </div>
</body>
</html>
