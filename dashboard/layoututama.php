<?php 
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$page = $_GET['page'] ?? 'dashboard';
$role = $_SESSION['role'];
$role = $_SESSION['role'];
$email = $_SESSION['email'];

/* DEFAULT FOTO */
$foto = "/MiniProject1Web/assets/profile/default.jpg";

/* AMBIL FOTO DARI DATABASE */
if($role == 'dosen'){

    $qFoto = mysqli_query($conn,
    "SELECT foto FROM dosen WHERE email='$email'");

    $dataFoto = mysqli_fetch_assoc($qFoto);

    if(!empty($dataFoto['foto'])){
        $foto = "/MiniProject1Web/assets/profile/" . $dataFoto['foto'];
    }

}elseif($role == 'mahasiswa'){

    $qFoto = mysqli_query($conn,
    "SELECT foto FROM mahasiswa WHERE email='$email'");

    $dataFoto = mysqli_fetch_assoc($qFoto);

    if(!empty($dataFoto['foto'])){
        $foto = "/MiniProject1Web/assets/profile/" . $dataFoto['foto'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>WEB ABSENSI</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>
<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <ul class="menu">

            <!-- PROFILE -->
                <!-- PROFILE -->
                <li>

                <a href="layoututama.php?page=dashboard" class="profile-link">

                    <img src="<?= $foto; ?>" class="profile-img">

                </a>

                </li>

                <!-- AKADEMIK -->
                <?php if($role != 'mahasiswa'){ ?>

                <li>

                    <div class="menu-toggle
                    <?= in_array($page, ['mahasiswa','dosen','matkul','jadwalkuliah']) ? 'active' : '' ?>"
                    onclick="toggleMenu(this)">
                        Akademik
                    </div>

                    <ul class="submenu
                    <?= in_array($page, ['mahasiswa','dosen','matkul','jadwalkuliah']) ? 'show' : '' ?>">

                        <?php if($role != 'dosen'){ ?>

                        <li>
                        <a href="layoututama.php?page=mahasiswa">
                        Mahasiswa
                        </a>
                        </li>
                        <?php } ?>    
        

                        <?php if($role != 'dosen'){ ?>

                        <li>
                        <a href="layoututama.php?page=dosen">
                        Dosen
                        </a>
                        </li>

                        <?php } ?>

                        <li>
                            <a href="layoututama.php?page=matkul">
                                Mata Kuliah
                            </a>
                        </li>

                        <li>
                            <a href="layoututama.php?page=jadwalkuliah">
                                Jadwal Kuliah
                            </a>
                        </li>

                    </ul>

                </li>

                <?php } ?>

                <!-- ABSENSI -->
                <?php if($role != 'admin'){ ?>

                <li>

                    <div class="menu-toggle"
                    onclick="toggleMenu(this)">
                        Absensi
                    </div>

                    <ul class="submenu">

                        <!-- DOSEN -->
                        <?php if($role == 'dosen'){ ?>

                        <li>
                            <a href="layoututama.php?page=generateqr">
                                Generate QR
                            </a>
                        </li>

                        <?php } ?>

                        <!-- MAHASISWA -->
                        <?php if($role == 'mahasiswa'){ ?>

                        <li>
                            <a href="layoututama.php?page=scan">
                                Scan QR
                            </a>
                        </li>

                        <?php } ?>

                    </ul>

                </li>

                <?php } ?>

            <!-- REKAP -->
            <?php if($role != 'mahasiswa'){ ?>

            <li>

                <div class="menu-toggle
                <?= in_array($page, ['rekap_mhs','rekap_dosen']) ? 'active' : '' ?>"
                onclick="toggleMenu(this)">
                    Rekap
                </div>

                <ul class="submenu
                <?= in_array($page, ['rekap_mhs','rekap_dosen']) ? 'show' : '' ?>">

                    <li>
                        <a href="layoututama.php?page=rekap_mhs">
                            Absensi Mahasiswa
                        </a>
                    </li>

                </ul>

            </li>

            <?php } ?>

            <!-- LOGOUT -->
            <li>
                <a href="../auth/logout.php" class="menu-link logout">
                    Logout
                </a>
            </li>

        </ul>

    </div>

    <!-- MAIN -->
    <div class="main">

        <?php
        if ($page == 'mahasiswa') {
            include '../mahasiswa/index.php';
        } elseif ($page == 'dosen') {
            include '../dosen/index.php';
        } elseif ($page == 'matkul') {
            include '../matkul/index.php';
        } elseif ($page == 'jadwalkuliah') {
            include '../jadwalkuliah/index.php';
        } elseif ($page == 'generateqr') {
            include '../absensi/generate.php';
        } elseif ($page == 'scan') {
            include '../absensi/scan.php';
        } elseif ($page == 'rekap_mhs') {
            include '../rekap/mahasiswa.php';
        } elseif ($page == 'rekap_dosen') {
            include '../rekap/dosen.php';
        } else {
            include 'index.php';
        }
        ?>

    </div>

</div>

<script>

function toggleMenu(element){

    const submenu = element.nextElementSibling;

    // toggle submenu
    submenu.classList.toggle("show");

    // toggle active hanya menu itu
    element.classList.toggle("active");
}

</script>
</body>
</html>