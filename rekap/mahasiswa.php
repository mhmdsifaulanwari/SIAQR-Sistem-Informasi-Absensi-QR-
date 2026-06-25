<?php
require_once '../config/koneksi.php';

$email = $_SESSION['email'];
$role  = $_SESSION['role'];

$where = "";

if($role == 'dosen'){

    // ambil data dosen berdasarkan email login
    $dosen = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT id
    FROM dosen
    WHERE email='$email'
    "));

    if($dosen){

        $id_dosen = $dosen['id'];

        // filter jadwal sesuai dosen login
        $where = "WHERE jadwal.dosen_id='$id_dosen'";
    }
}
?>

<link rel="stylesheet" href="../assets/dashboard.css">


<h2>Rekap Absensi Mahasiswa</h2>

<div class="card">

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>NIM</th>
        <th>Prodi</th>
        <th>Kelas</th>
        <th>Angkatan</th>
        <th>Mata Kuliah</th>
        <th>Status</th>
        <th>Tanggal</th>
    </tr>

<?php

$no = 1;

$rekap = mysqli_query($conn,"
SELECT 
absensi.*,
mahasiswa.nama,
mahasiswa.nim,
mahasiswa.prodi,
mahasiswa.kelas,
jadwal.angkatan,
matkul.nama_matkul

FROM absensi

JOIN mahasiswa
ON absensi.mahasiswa_id = mahasiswa.id

JOIN jadwal
ON absensi.jadwal_id = jadwal.id

JOIN matkul
ON jadwal.matkul_id = matkul.id

$where

ORDER BY absensi.id DESC
");

while($d = mysqli_fetch_assoc($rekap)){
?>

<tr>
    <td><?= $no++; ?></td>
    <td><?= $d['nama']; ?></td>
    <td><?= $d['nim']; ?></td>
    <td><?= $d['prodi']; ?></td>
    <td><?= $d['kelas']; ?></td>
    <td><?= $d['angkatan']; ?></td>
    <td><?= $d['nama_matkul']; ?></td>
    <td><?= $d['status']; ?></td>
    <td><?= $d['tanggal']; ?></td>
</tr>

<?php } ?>

</table>

</div>