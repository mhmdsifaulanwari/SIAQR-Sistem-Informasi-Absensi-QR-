<?php
header("Refresh: 3");
require_once '../config/koneksi.php';
require_once '../config/koneksi.php';

$id = $_GET['jadwal'];

// JOIN matkul biar judul lengkap
$j = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT jadwal.*, matkul.nama_matkul 
FROM jadwal 
JOIN matkul ON jadwal.matkul_id = matkul.id
WHERE jadwal.id='$id'
"));

$kelas = $j['kelas'];
$prodi = $j['prodi'];
$angkatan = $j['angkatan'];
$angkatan_short = substr($angkatan,2,2);

// ambil mahasiswa urut NIM
$mhs = mysqli_query($conn,"
SELECT * FROM mahasiswa 
WHERE kelas='$kelas'
AND prodi='$prodi'
AND LEFT(nim,2)='$angkatan_short'
ORDER BY nim ASC
");
?>

<link rel="stylesheet" href="../assets/dashboard.css">

<h2>
Absensi - <?= $j['nama_matkul'] ?> | <?= $j['prodi'] ?> | Kelas <?= $j['kelas'] ?>
</h2>

<p style="color:green;">
Data absensi diperbarui otomatis setiap 3 detik
</p>

<form method="POST">

<div class="card">

<table>
<tr>
<th>No</th>
<th>NIM</th>
<th>Nama</th>
<th>Status</th>
</tr>

<?php $no=1; while($m=mysqli_fetch_assoc($mhs)){ ?>

<?php
// 🔥 AMBIL STATUS DARI DATABASE
$absen = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT status FROM absensi 
WHERE mahasiswa_id='{$m['id']}' 
AND jadwal_id='$id'
ORDER BY id DESC
LIMIT 1
"));

$status_now = isset($absen['status'])
? trim($absen['status'])
: 'Belum';
?>

<tr>
<td><?= $no++ ?></td>
<td><?= $m['nim'] ?></td>
<td><?= $m['nama'] ?></td>
<td>

<select name="status[<?= $m['id'] ?>]">

<option value="Belum" <?= ($status_now=='Belum'?'selected':'') ?>>Belum Absen</option>

<option value="Hadir" <?= ($status_now=='Hadir'?'selected':'') ?>>Hadir</option>

<option value="Izin" <?= ($status_now=='Izin'?'selected':'') ?>>Izin</option>

<option value="Sakit" <?= ($status_now=='Sakit'?'selected':'') ?>>Sakit</option>

<option value="Alpha" <?= ($status_now=='Alpha'?'selected':'') ?>>Alpha</option>

</select>

</td>
</tr>

<?php } ?>

</table>

</div>

<div style="margin-top:15px;">
<button name="simpan" class="btn-primary">Simpan Absensi</button>

<a href="../dashboard/layoututama.php?page=generateqr&jadwal=<?= $id ?>" 
class="btn-danger" style="text-decoration:none;">
Kembali
</a>
</div>

</form>

<?php
if(isset($_POST['simpan'])){

    foreach($_POST['status'] as $id_mhs=>$status){

        // 🔥 CEK DULU ADA ATAU BELUM
        $cek = mysqli_query($conn,"
        SELECT * FROM absensi 
        WHERE mahasiswa_id='$id_mhs' 
        AND jadwal_id='$id'
        ");

        if(mysqli_num_rows($cek) > 0){

            // 🔥 UPDATE
            mysqli_query($conn,"
            UPDATE absensi 
            SET status='$status'
            WHERE mahasiswa_id='$id_mhs' 
            AND jadwal_id='$id'
            ");

        } else {

            // 🔥 INSERT
            mysqli_query($conn,"
            INSERT INTO absensi(mahasiswa_id,jadwal_id,status)
            VALUES('$id_mhs','$id','$status')
            ");

        }
    }

    echo "<script>
    alert('Absensi berhasil disimpan');
    window.location='../dashboard/layoututama.php?page=generateqr&jadwal=$id';
    </script>";
}
?>
