<?php
session_start();
require_once "soal.php";
require_once "jawaban.php";

$soalObj = new soal();
$jawabanObj = new jawaban();

if (isset($_GET['reset']) && $_GET['reset'] == '1') {
    unset($_SESSION['jawaban']);
    header("Location: index.php");
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = isset($_POST['aksi']) ? $_POST['aksi'] : '';
    $halaman_aktif = isset($_POST['halaman_aktif']) ? (int)$_POST['halaman_aktif'] : 1;

    if (isset($_POST['jawaban']) && is_array($_POST['jawaban'])) {
        if (!isset($_SESSION['jawaban'])) {
            $_SESSION['jawaban'] = array();
        }
        foreach ($_POST['jawaban'] as $idsoal => $idjawaban) {
            $idsoal = (int)$idsoal;
            $idjawaban = (int)$idjawaban;

            $is_benar = $jawabanObj->cekJawaban($idjawaban);

            $_SESSION['jawaban'][$idsoal] = array(
                'idjawaban' => $idjawaban,
                'benarkah'  => $is_benar
            );
        }
    }

    $daftar_halaman = $soalObj->getDaftarHalaman();

    $current_index = 0;
    for ($i = 0; $i < count($daftar_halaman); $i++) {
        if ($daftar_halaman[$i] == $halaman_aktif) {
            $current_index = $i;
            break;
        }
    }

    if ($aksi == 'Next') {
        if ($current_index >= count($daftar_halaman) - 1) {
            header("Location: kesimpulan.php");
        } else {
            $next_page = $daftar_halaman[$current_index + 1];
            header("Location: index.php?halaman=" . $next_page);
        }
    } elseif ($aksi == 'Previous') {
        if ($current_index > 0) {
            $prev_page = $daftar_halaman[$current_index - 1];
            header("Location: index.php?halaman=" . $prev_page);
        } else {
            header("Location: index.php");
        }
    }
} else {
    $daftar_halaman = $soalObj->getDaftarHalaman();
    $soal_list = array();
    $current_index = 0;
    $is_first = true;
    $is_last = true;

    if (count($daftar_halaman) > 0) {
        $halaman_aktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : $daftar_halaman[0];
        if (!in_array($halaman_aktif, $daftar_halaman)) {
            $halaman_aktif = $daftar_halaman[0];
        }

        for ($i = 0; $i < count($daftar_halaman); $i++) {
            if ($daftar_halaman[$i] == $halaman_aktif) {
                $current_index = $i;
                break;
            }
        }

        $is_first = ($current_index == 0);
        $is_last = ($current_index == count($daftar_halaman) - 1);

        $soal_list = $soalObj->getSoalByHalaman($halaman_aktif);
    }
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

    <?php if (count($daftar_halaman) == 0 || count($soal_list) == 0) { ?>
        <p>Belum ada data soal pada database fullstack.</p>
    <?php } else { ?>
        <p>Halaman <?php echo ($current_index + 1); ?> dari <?php echo count($daftar_halaman); ?></p>
        <hr>

        <form action="index.php" method="POST">
            <input type="hidden" name="halaman_aktif" value="<?php echo $halaman_aktif; ?>">

            <?php foreach ($soal_list as $soal) { ?>
                <?php
                $idsoal = (int)$soal['idsoal'];
                $nomor = (int)$soal['nomor'];
                $pertanyaan = htmlentities((string)$soal['pertanyaan']);

                $opsi_list = $jawabanObj->getJawabanBySoal($idsoal);

                $selected_idjawaban = isset($_SESSION['jawaban'][$idsoal]['idjawaban']) 
                    ? (int)$_SESSION['jawaban'][$idsoal]['idjawaban'] 
                    : null;
                ?>
                <div class="soal-card">
                    <div class="pertanyaan-text">
                        <?php echo $nomor; ?>. <?php echo $pertanyaan; ?>
                    </div>
                    <?php foreach ($opsi_list as $opsi) { ?>
                        <?php
                        $idjawaban = (int)$opsi['idjawaban'];
                        $isi_jawaban = htmlentities((string)$opsi['isi_jawaban']);
                        $is_checked = ($selected_idjawaban == $idjawaban) ? 'checked' : '';
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
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="nav-buttons">
                <?php if (!$is_first) { ?>
                    <input type="submit" name="aksi" value="Previous" class="btn">
                <?php } ?>
                <input type="submit" name="aksi" value="Next" class="btn btn-primary">
            </div>
        </form>
    <?php } ?>
</body>
</html>
<?php
}
?>
