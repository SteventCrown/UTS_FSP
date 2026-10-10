<?php
session_start();
require_once "soal.php";
require_once "jawaban.php";

$soalObj = new soal();
$jawabanObj = new jawaban();

// Jika belum ada jawaban pada session, arahkan kembali ke index.php
if (!isset($_SESSION['jawaban']) || empty($_SESSION['jawaban'])) {
    header("Location: index.php");
    exit();
}

$semua_soal = $soalObj->getAllSoal();
$skor_akhir = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Kesimpulan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Halaman Kesimpulan</h2>
        <hr>

        <div class="kesimpulan-list">
            <?php foreach ($semua_soal as $s): ?>
                <?php
                $idsoal = (int)$s['idsoal'];
                $nomor = (int)$s['nomor'];
                $pertanyaan = htmlentities((string)$s['pertanyaan']);

                $user_info = isset($_SESSION['jawaban'][$idsoal]) ? $_SESSION['jawaban'][$idsoal] : null;
                $user_jawaban = null;
                $is_benar = false;

                if ($user_info && isset($user_info['idjawaban'])) {
                    $user_jawaban = $jawabanObj->getJawabanById((int)$user_info['idjawaban']);
                    if ($user_jawaban && (int)$user_jawaban['benarkah'] === 1) {
                        $is_benar = true;
                        $skor_akhir += 10;
                    }
                }
                ?>
                <div class="kesimpulan-item">
                    <p><strong><?php echo $nomor; ?>. <?php echo $pertanyaan; ?></strong></p>
                    <?php if ($user_jawaban): ?>
                        <?php if ($is_benar): ?>
                            <div class="jawaban-user">- Jawaban user : <?php echo htmlentities((string)$user_jawaban['isi_jawaban']); ?> (benar)</div>
                        <?php else: ?>
                            <div class="jawaban-user">- Jawaban user : <?php echo htmlentities((string)$user_jawaban['isi_jawaban']); ?> (salah)</div>
                            <?php
                            $kunci = $jawabanObj->getJawabanBenarBySoal($idsoal);
                            if ($kunci):
                            ?>
                                <div class="jawaban-benar">&nbsp;&nbsp;Jawaban benar : <?php echo htmlentities((string)$kunci['isi_jawaban']); ?></div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="jawaban-user">- Jawaban user : - (salah)</div>
                        <?php
                        $kunci = $jawabanObj->getJawabanBenarBySoal($idsoal);
                        if ($kunci):
                        ?>
                            <div class="jawaban-benar">&nbsp;&nbsp;Jawaban benar : <?php echo htmlentities((string)$kunci['isi_jawaban']); ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <hr>
        <p class="skor-box"><strong>Skor Akhir : <?php echo $skor_akhir; ?></strong></p>

        <form action="index.php" method="GET">
            <input type="hidden" name="reset" value="1">
            <input type="submit" value="PLAY AGAIN" class="btn">
        </form>
    </div>
</body>
</html>
