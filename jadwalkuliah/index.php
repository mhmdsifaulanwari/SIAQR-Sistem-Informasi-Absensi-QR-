<?php
require_once '../config/koneksi.php';

/* ================= HAPUS ================= */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM jadwal WHERE id='$id'");
    echo "<script>location='layoututama.php?page=jadwal';</script>";
}

/* ================= SIMPAN ================= */
if (isset($_POST['simpan'])) {

    $matkul = $_POST['matkul'];
    $dosen = $_POST['dosen'];
    $prodi = $_POST['prodi'];
    $angkatan = $_POST['angkatan'];
    $kelas = $_POST['kelas'];
    $hari = $_POST['hari'];
    $jam = $_POST['jam'];
    $ruangan = $_POST['ruangan'];

    if ($matkul=='' || $dosen=='' || $prodi=='' || $angkatan=='' || $kelas=='' || $hari=='' || $jam=='') {
        echo "<script>alert('Harap isi semua data!')</script>";
    } else {

        if ($_POST['id'] == '') {

            mysqli_query($conn, "INSERT INTO jadwal
            (matkul_id,dosen_id,prodi,angkatan,kelas,hari,jam,ruangan)
            VALUES ('$matkul','$dosen','$prodi','$angkatan','$kelas','$hari','$jam','$ruangan')");

            echo "<script>alert('Jadwal berhasil ditambahkan')</script>";

        } else {

            $id = $_POST['id'];

            mysqli_query($conn, "UPDATE jadwal SET
            matkul_id='$matkul',
            dosen_id='$dosen',
            prodi='$prodi',
            angkatan='$angkatan',
            kelas='$kelas',
            hari='$hari',
            jam='$jam',
            ruangan='$ruangan'
            WHERE id='$id'");

            echo "<script>alert('Jadwal berhasil diupdate')</script>";
        }
    }
}

/* ================= FILTER ================= */
$prodi = $_GET['prodi'] ?? '';
$angkatan = $_GET['angkatan'] ?? '';
$kelas = $_GET['kelas'] ?? '';
$hari = $_GET['hari'] ?? '';

$where = "WHERE 1=1";

if ($prodi != '') $where .= " AND jadwal.prodi='$prodi'";
if ($angkatan != '') $where .= " AND jadwal.angkatan='$angkatan'";
if ($kelas != '') $where .= " AND jadwal.kelas='$kelas'";
if ($hari != '') $where .= " AND jadwal.hari='$hari'";

/* ================= DATA ================= */
$matkul = mysqli_query($conn, "SELECT * FROM matkul");
$dosen = mysqli_query($conn, "SELECT * FROM dosen");

$data = mysqli_query($conn, "
SELECT jadwal.*, matkul.nama_matkul, dosen.nama as nama_dosen
FROM jadwal
JOIN matkul ON jadwal.matkul_id = matkul.id
JOIN dosen ON jadwal.dosen_id = dosen.id
$where
ORDER BY jadwal.hari ASC
");
?>

<link rel="stylesheet" href="../assets/dashboard.css">
<link rel="stylesheet"
href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

<h2>Kelola Jadwal Kuliah</h2>

<!-- FILTER -->
<div class="card">
<form method="GET" class="form-filter">

    <!-- 🔥 INI YANG PALING PENTING -->
    <input type="hidden" name="page" value="jadwalkuliah">

    <select name="prodi">
        <option value="">Semua Prodi</option>
        <option value="Pendidikan Teknologi Informasi" <?= ($prodi=='Pendidikan Teknologi Informasi'?'selected':'') ?>>Pendidikan Teknologi Informasi</option>
        <option value="Teknik Informatika" <?= ($prodi=='Teknik Informatika'?'selected':'') ?>>Teknik Informatika</option>
        <option value="Sistem Informasi" <?= ($prodi=='Sistem Informasi'?'selected':'') ?>>Sistem Informasi</option>
    </select>

    <select name="angkatan">
        <option value="">Semua Angkatan</option>
        <option value="2026" <?= ($angkatan=='2026'?'selected':'') ?>>2026</option>
        <option value="2025" <?= ($angkatan=='2025'?'selected':'') ?>>2025</option>
        <option value="2024" <?= ($angkatan=='2024'?'selected':'') ?>>2024</option>
        <option value="2023" <?= ($angkatan=='2023'?'selected':'') ?>>2023</option>
        <option value="2022" <?= ($angkatan=='2022'?'selected':'') ?>>2022</option>
        <option value="2021" <?= ($angkatan=='2021'?'selected':'') ?>>2021</option>
    </select>

    <select name="kelas">
        <option value="">Semua Kelas</option>
        <option value="A" <?= ($kelas=='A'?'selected':'') ?>>A</option>
        <option value="B" <?= ($kelas=='B'?'selected':'') ?>>B</option>
        <option value="C" <?= ($kelas=='C'?'selected':'') ?>>C</option>
        <option value="D" <?= ($kelas=='D'?'selected':'') ?>>D</option>
        <option value="E" <?= ($kelas=='E'?'selected':'') ?>>E</option>
    </select>

    <select name="hari">
        <option value="">Semua Hari</option>
        <option value="Senin" <?= ($hari=='Senin'?'selected':'') ?>>Senin</option>
        <option value="Selasa" <?= ($hari=='Selasa'?'selected':'') ?>>Selasa</option>
        <option value="Rabu" <?= ($hari=='Rabu'?'selected':'') ?>>Rabu</option>
        <option value="Kamis" <?= ($hari=='Kamis'?'selected':'') ?>>Kamis</option>
        <option value="Jumat" <?= ($hari=='Jumat'?'selected':'') ?>>Jumat</option>
    </select>

    <button type="submit" class="btn-cari">Cari</button>

    <button type="button" onclick="openModal()" class="btn-tambah">
        + Tambah Jadwal
    </button>
</form>
</div>

<!-- MODAL -->
<div id="modalForm" class="modal">
<div class="modal-box">

<h3>Tambah Jadwal</h3>

<form method="POST" id="formJadwal">
<input type="hidden" name="id">

<select name="matkul" required>
<option value="">-- Pilih Matkul --</option>
<?php while($m = mysqli_fetch_assoc($matkul)) { ?>
<option value="<?= $m['id'] ?>"><?= $m['nama_matkul'] ?></option>
<?php } ?>
</select>

<select name="dosen" required>
<option value="">-- Pilih Dosen --</option>
<?php while($d = mysqli_fetch_assoc($dosen)) { ?>
<option value="<?= $d['id'] ?>"><?= $d['nama'] ?></option>
<?php } ?>
</select>

<select name="prodi" required>
<option>Pendidikan Teknologi Informasi</option>
<option>Teknik Informatika</option>
<option>Sistem Informasi</option>
</select>

<select name="angkatan" required>
<option>2026</option>
<option>2025</option>
<option>2024</option>
<option>2023</option>
<option>2022</option>
<option>2021</option>
</select>

<select name="kelas" required>
<option>A</option><option>B</option><option>C</option>
<option>D</option><option>E</option>
</select>

<select name="hari" required>
<option>Senin</option>
<option>Selasa</option>
<option>Rabu</option>
<option>Kamis</option>
<option>Jumat</option>
</select>

<input name="jam" placeholder="Contoh: 08:00 - 10:00" required>
<input name="ruangan" placeholder="Ruangan / Online">

<div class="modal-footer">
<button type="button" onclick="closeModal()" class="btn-danger">Batal</button>
<button name="simpan" class="btn-primary">Simpan</button>
</div>

</form>

</div>
</div>

<!-- TABLE -->
<div class="card table-card">
<div class="table-scroll">

<table>
<tr>
<th>Matkul</th>
<th>Dosen</th>
<th>Prodi</th>
<th>Angkatan</th>
<th>Kelas</th>
<th>Hari</th>
<th>Jam</th>
<th>Ruangan</th>
<th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)) { ?>
<tr>
<td><?= $row['nama_matkul'] ?></td>
<td><?= $row['nama_dosen'] ?></td>
<td><?= $row['prodi'] ?></td>
<td><?= $row['angkatan'] ?></td>
<td><?= $row['kelas'] ?></td>
<td><?= $row['hari'] ?></td>
<td><?= $row['jam'] ?></td>
<td><?= $row['ruangan'] ?></td>

<td>
<a href="#" onclick="editData(
'<?= $row['id'] ?>',
'<?= $row['matkul_id'] ?>',
'<?= $row['dosen_id'] ?>',
'<?= $row['prodi'] ?>',
'<?= $row['angkatan'] ?>',
'<?= $row['kelas'] ?>',
'<?= $row['hari'] ?>',
'<?= $row['jam'] ?>',
'<?= $row['ruangan'] ?>'
)">

<span class="material-symbols-outlined">
edit_square
</span>

</a>

<a href="layoututama.php?page=jadwal&hapus=<?= $row['id'] ?>">

<span class="material-symbols-outlined">
delete
</span>

</a>


</tr>
<?php } ?>

</table>

</div>
</div>

<script>
function openModal(){
    document.getElementById("modalForm").style.display="flex";
}
function closeModal(){
    document.getElementById("modalForm").style.display="none";
}
function editData(id, matkul, dosen, prodi, angkatan, kelas, hari, jam, ruangan){
    openModal();

    document.querySelector('[name="id"]').value = id;
    document.querySelector('[name="matkul"]').value = matkul;
    document.querySelector('[name="dosen"]').value = dosen;
    document.querySelector('[name="prodi"]').value = prodi;
    document.querySelector('[name="angkatan"]').value = angkatan;
    document.querySelector('[name="kelas"]').value = kelas;
    document.querySelector('[name="hari"]').value = hari;
    document.querySelector('[name="jam"]').value = jam;
    document.querySelector('[name="ruangan"]').value = ruangan;

    document.querySelector(".modal-box h3").innerText = "Edit Jadwal";
}
</script>