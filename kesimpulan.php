<?php
session_start();
require_once "soal.php";
require_once "jawaban.php";

$soalObj = new soal();
$jawabanObj = new jawaban();

$semua_soal = $soalObj->getAllSoal();
$skor_akhir = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Kesimpulan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Halaman Kesimpulan</h2>
    <p>Berikut adalah hasil evaluasi dari pengerjaan kuis Anda:</p>
    <hr>

    <?php foreach ($semua_soal as $s) { ?>
        <?php
        $idsoal = (int)$s['idsoal'];
        $nomor = (int)$s['nomor'];
        $pertanyaan = htmlentities((string)$s['pertanyaan']);

        $user_info = isset($_SESSION['jawaban'][$idsoal]) ? $_SESSION['jawaban'][$idsoal] : null;
        $user_jawaban = null;
        $is_benar = false;

        if ($user_info && isset($user_info['idjawaban'])) {
            $user_jawaban = $jawabanObj->getJawabanById((int)$user_info['idjawaban']);
            if ($user_jawaban && (int)$user_jawaban['benarkah'] == 1) {
                $is_benar = true;
                $skor_akhir += 10;
            }
        }
        ?>
        <div style="margin-bottom: 15px;">
            <b><?php echo $nomor; ?>. <?php echo $pertanyaan; ?></b><br>
            <?php if ($user_jawaban) { ?>
                <?php if ($is_benar) { ?>
                    &nbsp;&nbsp;- Jawaban user : <?php echo htmlentities((string)$user_jawaban['isi_jawaban']); ?> (benar)<br>
                <?php } else { ?>
                    &nbsp;&nbsp;- Jawaban user : <?php echo htmlentities((string)$user_jawaban['isi_jawaban']); ?> (salah)<br>
                    <?php
                    $kunci = $jawabanObj->getJawabanBenarBySoal($idsoal);
                    if ($kunci) {
                    ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;Jawaban benar : <?php echo htmlentities((string)$kunci['isi_jawaban']); ?><br>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>
                &nbsp;&nbsp;- Jawaban user : - (salah)<br>
                <?php
                $kunci = $jawabanObj->getJawabanBenarBySoal($idsoal);
                if ($kunci) {
                ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;Jawaban benar : <?php echo htmlentities((string)$kunci['isi_jawaban']); ?><br>
                <?php } ?>
            <?php } ?>
        </div>
    <?php } ?>

    <hr>
    <p><b>Skor Akhir : <?php echo $skor_akhir; ?></b></p>

    <form action="index.php" method="GET">
        <input type="hidden" name="reset" value="1">
        <input type="submit" value="PLAY AGAIN" class="btn btn-primary">
    </form>
</body>
</html>
