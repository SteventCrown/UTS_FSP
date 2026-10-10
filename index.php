<?php
session_start();
require_once "soal.php";
require_once "jawaban.php";

// Initialization
$soalObj = new soal();
$jawabanObj = new jawaban();

if (isset($_GET['reset']) && $_GET['reset'] == '1') {
    unset($_SESSION['jawaban']);
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = isset($_POST['aksi']) ? $_POST['aksi'] : '';
    $halaman_aktif = isset($_POST['halaman_aktif']) ? (int)$_POST['halaman_aktif'] : 1;

    // Simpan jawaban user ke Session sambil dicek apakah benar atau salah
    if (isset($_POST['jawaban']) && is_array($_POST['jawaban'])) {
        if (!isset($_SESSION['jawaban'])) {
            $_SESSION['jawaban'] = array();
        }
        foreach ($_POST['jawaban'] as $idsoal => $idjawaban) {
            $idsoal = (int)$idsoal;
            $idjawaban = (int)$idjawaban;

            // Periksa jawaban dengan method class jawaban
            $is_benar = $jawabanObj->cekJawaban($idjawaban);

            // Simpan ke Session
            $_SESSION['jawaban'][$idsoal] = array(
                'idjawaban' => $idjawaban,
                'benarkah'  => $is_benar
            );
        }
    }

    // Ambil daftar nomor halaman dari database secara dinamis
    $daftar_halaman = $soalObj->getDaftarHalaman();
    $current_index = array_search($halaman_aktif, $daftar_halaman);
    if ($current_index === false) {
        $current_index = 0;
    }

    if ($aksi === 'Next') {
        // Jika sudah di halaman terakhir, lanjut ke halaman kesimpulan
        if ($current_index >= count($daftar_halaman) - 1) {
            header("Location: kesimpulan.php");
            exit();
        } else {
            // Lanjut ke halaman berikutnya
            $next_page = $daftar_halaman[$current_index + 1];
            header("Location: index.php?halaman=" . $next_page);
            exit();
        }
    } elseif ($aksi === 'Previous') {
        // Lanjut ke halaman sebelumnya
        if ($current_index > 0) {
            $prev_page = $daftar_halaman[$current_index - 1];
            header("Location: index.php?halaman=" . $prev_page);
            exit();
        } else {
            header("Location: index.php");
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
    <title>Kuis Online</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Kuis Online</h2>
    <p>Halaman <?php echo ($current_index + 1); ?> dari <?php echo count($daftar_halaman); ?></p>
    <hr>

    <form action="index.php" method="POST">
        <input type="hidden" name="halaman_aktif" value="<?php echo $halaman_aktif; ?>">

        <?php foreach ($soal_list as $soal): ?>
            <?php
            $idsoal = (int)$soal['idsoal'];
            $nomor = (int)$soal['nomor'];
            $pertanyaan = htmlentities((string)$soal['pertanyaan']);

            // Opsi jawaban posisi random di tiap refresh (ORDER BY RAND())
            $opsi_list = $jawabanObj->getJawabanBySoal($idsoal);

            // Cek jawaban sebelumnya di Session
            $selected_idjawaban = isset($_SESSION['jawaban'][$idsoal]['idjawaban']) 
                ? (int)$_SESSION['jawaban'][$idsoal]['idjawaban'] 
                : null;
            ?>
            <div class="soal-card">
                <div class="pertanyaan-text">
                    <?php echo $nomor; ?>. <?php echo $pertanyaan; ?>
                </div>
                <?php foreach ($opsi_list as $opsi): ?>
                    <?php
                    $idjawaban = (int)$opsi['idjawaban'];
                    $isi_jawaban = htmlentities((string)$opsi['isi_jawaban']);
                    $is_checked = ($selected_idjawaban === $idjawaban) ? 'checked' : '';
                    $input_id = "opsi_" . $idsoal . "_" . $idjawaban;
                    ?>
                    <label class="opsi-item" for="<?php echo $input_id; ?>">
                        <input type="radio" 
                               id="<?php echo $input_id; ?>" 
                               name="jawaban[<?php echo $idsoal; ?>]" 
                               value="<?php echo $idjawaban; ?>" 
                               <?php echo $is_checked; ?> 
                               required>
                        <?php echo $isi_jawaban; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <div class="nav-buttons">
            <?php if (!$is_first): ?>
                <input type="submit" name="aksi" value="Previous" class="btn">
            <?php endif; ?>
            <input type="submit" name="aksi" value="Next" class="btn btn-primary">
        </div>
    </form>
</body>
</html>
