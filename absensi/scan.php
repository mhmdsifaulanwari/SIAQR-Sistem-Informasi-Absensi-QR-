<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/koneksi.php';

// ================= CEK LOGIN =================
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'mahasiswa') {
    echo "<h3>Harus login sebagai mahasiswa</h3>";
    exit;
}

/* ================= FIX AMBIL ID MAHASISWA ================= */
$email = $_SESSION['email'];

$mhs = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT id FROM mahasiswa WHERE email='$email'
"));

if(!$mhs){
    echo "<h3>Data mahasiswa tidak ditemukan!</h3>";
    exit;
}

$id_mhs = $mhs['id'];


// ================= PROSES SCAN =================
if (isset($_GET['jadwal'])) {

    $jadwal = $_GET['jadwal'];

    $cek = mysqli_query($conn,"
        SELECT * FROM absensi 
        WHERE mahasiswa_id='$id_mhs' 
        AND jadwal_id='$jadwal'
    ");

    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Kamu sudah absen!');window.location='../dashboard/layoututama.php?page=scan';</script>";
        exit;
    }

    mysqli_query($conn,"
    INSERT INTO absensi (mahasiswa_id, jadwal_id, status)
    VALUES ('$id_mhs','$jadwal','Hadir')
    ");

    echo "<script>alert('Absensi berhasil');window.location='../dashboard/layoututama.php?page=scan';</script>";
    exit;
}


// ================= PROSES UPLOAD =================
if (isset($_POST['kirim'])) {

    $jadwal = $_POST['jadwal'];

    if(empty($jadwal)){
        echo "<script>alert('QR belum dipilih!');</script>";
    } else {

        $cek = mysqli_query($conn,"
        SELECT * FROM absensi 
        WHERE mahasiswa_id='$id_mhs' 
        AND jadwal_id='$jadwal'
        ");

        if(mysqli_num_rows($cek) > 0){
            echo "<script>alert('Kamu sudah absen!');window.location='../dashboard/layoututama.php?page=scan';</script>";
            exit;
        }

        mysqli_query($conn,"
        INSERT INTO absensi (mahasiswa_id, jadwal_id, status)
        VALUES ('$id_mhs','$jadwal','Hadir')
        ");

        echo "<script>alert('Absensi berhasil');window.location='../dashboard/layoututama.php?page=scan';</script>";
        exit;
    }
}
?>

<link rel="stylesheet" href="../assets/dashboard.css">

<h2>Scan QR Absensi</h2>

<div class="card" style="max-width:360px; margin:auto; text-align:center;">

    <!-- CAMERA -->
    <h3>Scan Kamera</h3>

    <button onclick="startScan()" class="btn-primary" style="margin-bottom:10px;">
        Buka Kamera
    </button>

    <div style="border:2px dashed #ccc; padding:8px; border-radius:10px;">
        <div id="reader" style="width:100%; height:250px;"></div>
    </div>

    <br>

    <!-- UPLOAD -->
    <h4>Upload QR jika kamera bermasalah</h4>

    <form method="POST">
        <input type="hidden" name="jadwal">

        <input type="file" id="uploadQR" style="margin-bottom:10px;"><br>

        <p id="loadingText" style="color:gray; display:none;">
            Sedang membaca QR...
        </p>

        <button type="submit" name="kirim" class="btn-primary" id="btnKirim" disabled>
            Kirim
        </button>
    </form>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>

function onScanSuccess(decodedText) {
    // 🔥 LANGSUNG KE URL QR (tidak perlu mhs lagi)
    window.location = decodedText;
}

let html5QrCode = new Html5Qrcode("reader");

function startScan(){

    Html5Qrcode.getCameras().then(devices => {

        if (devices.length) {

            html5QrCode.start(
                devices[0].id,
                { fps: 10, qrbox: 200 },
                onScanSuccess
            );

        } else {
            alert("Kamera tidak ditemukan");
        }

    }).catch(err => {
        alert("Tidak bisa akses kamera!");
    });

}


// ================= UPLOAD =================
document.getElementById('uploadQR').addEventListener('change', function(e){

    const file = e.target.files[0];
    if(!file) return;

    document.getElementById("loadingText").style.display = "block";

    Html5Qrcode.scanFile(file, true)
    .then(decodedText => {

        let url = new URL(decodedText);
        let jadwal = url.searchParams.get("jadwal");

        if(!jadwal){
            alert("QR tidak valid!");
            document.getElementById("loadingText").style.display = "none";
            return;
        }

        document.querySelector("input[name='jadwal']").value = jadwal;

        document.getElementById("btnKirim").disabled = false;

        document.getElementById("loadingText").style.display = "none";

        alert("QR berhasil dibaca, klik Kirim");

    })
    .catch(err => {
        document.getElementById("loadingText").style.display = "none";
        alert("QR tidak terbaca!");
    });

});
</script>