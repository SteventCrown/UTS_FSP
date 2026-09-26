<?php
session_start();
require_once("soal.php");
require_once("jawaban.php");

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Kesimpulan - UTS Full-stack Programming</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header-title">
            <h1>Halaman Kesimpulan</h1>
            <p>Hasil Jawaban dan Skor Akhir</p>
        </div>

        <ul class="kesimpulan-list">
            <?php foreach ($semua_soal as $s): ?>
                <?php
                $idsoal = (int)$s['idsoal'];
                $nomor = (int)$s['nomor'];
                $pertanyaan = htmlspecialchars($s['pertanyaan']);

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
                <li class="kesimpulan-item">
                    <div class="pertanyaan">
                        <?= $nomor ?>. <?= $pertanyaan ?>
                    </div>
                    <?php if ($user_jawaban): ?>
                        <?php if ($is_benar): ?>
                            <div class="jawaban-user benar">
                                - Jawaban user : <?= htmlspecialchars($user_jawaban['isi_jawaban']) ?> (benar)
                            </div>
                        <?php else: ?>
                            <div class="jawaban-user salah">
                                - Jawaban user : <?= htmlspecialchars($user_jawaban['isi_jawaban']) ?> (salah)
                            </div>
                            <?php
                            $kunci = $jawabanObj->getJawabanBenarBySoal($idsoal);
                            if ($kunci):
                            ?>
                                <div class="jawaban-benar">
                                    Jawaban benar : <?= htmlspecialchars($kunci['isi_jawaban']) ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="jawaban-user salah">
                            - Jawaban user : Tidak dijawab (salah)
                        </div>
                        <?php
                        $kunci = $jawabanObj->getJawabanBenarBySoal($idsoal);
                        if ($kunci):
                        ?>
                            <div class="jawaban-benar">
                                Jawaban benar : <?= htmlspecialchars($kunci['isi_jawaban']) ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="skor-box">
            Skor Akhir : <?= $skor_akhir ?>
        </div>

        <div class="play-again-container">
            <a href="index.php?reset=1" class="btn btn-play">PLAY AGAIN</a>
        </div>
    </div>
</body>
</html>
