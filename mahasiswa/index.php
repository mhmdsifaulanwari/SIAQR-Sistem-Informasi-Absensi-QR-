<?php
require_once '../config/koneksi.php';

/* ================= HAPUS ================= */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id='$id'");
    echo "<script>location='layoututama.php?page=mahasiswa';</script>";
}

/* ================= SIMPAN ================= */
if (isset($_POST['simpan'])) {

    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $prodi = $_POST['prodi'];
    $kelas = $_POST['kelas'];
    $email = $_POST['email'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    if (
        $nim=='' || $nama=='' || $prodi=='' || $kelas=='' ||
        $email=='' || $telp=='' || $alamat==''
    ) {
        echo "<script>alert('Harap masukkan semua data dengan benar!')</script>";
    }
    elseif (!is_numeric($nim) || !is_numeric($telp)) {
        echo "<script>alert('NIM dan No HP harus berupa angka!')</script>";
    }
    else {

        if ($_POST['id'] == '') {

            mysqli_query($conn, "INSERT INTO mahasiswa 
            (nim,nama,prodi,kelas,email,telp,alamat)
            VALUES ('$nim','$nama','$prodi','$kelas','$email','$telp','$alamat')");

            mysqli_query($conn, "INSERT INTO users 
            (email,password,role)
            VALUES ('$email','$nim','mahasiswa')");

            echo "<script>alert('Data berhasil ditambahkan')</script>";

        } else {

            $id = $_POST['id'];

            mysqli_query($conn, "UPDATE mahasiswa SET
            nim='$nim',
            nama='$nama',
            prodi='$prodi',
            kelas='$kelas',
            email='$email',
            telp='$telp',
            alamat='$alamat'
            WHERE id='$id'");

            echo "<script>alert('Data berhasil diupdate')</script>";
        }
    }
}

/* ================= FILTER ================= */
$search = $_GET['search'] ?? '';
$prodi_filter = $_GET['prodi'] ?? '';
$kelas_filter = $_GET['kelas'] ?? '';
$angkatan = $_GET['angkatan'] ?? ''; // 🔥 TAMBAHAN

$where = "WHERE 1=1";

if ($search != '') {
    $where .= " AND (nim LIKE '%$search%' OR nama LIKE '%$search%')";
}
if ($prodi_filter != '') {
    $where .= " AND prodi='$prodi_filter'";
}
if ($kelas_filter != '') {
    $where .= " AND kelas='$kelas_filter'";
}

/* 🔥 FILTER ANGKATAN DARI NIM */
if ($angkatan != '') {
    $where .= " AND LEFT(nim,2)='$angkatan'";
}

$data = mysqli_query($conn, "SELECT * FROM mahasiswa $where ORDER BY kelas ASC");
?>

<link rel="stylesheet" href="../assets/dashboard.css">
<link rel="stylesheet"
href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

<div>

<h2>Kelola Data Mahasiswa</h2>

<!-- FILTER -->
<div class="card">
    <form method="GET" class="form-filter">
        <input type="hidden" name="page" value="mahasiswa">

        <input type="text" name="search" placeholder="Cari NIM / Nama">

        <!-- PRODI -->
        <select name="prodi">
            <option value="">Semua Prodi</option>
            <option>Pendidikan Teknologi Informasi</option>
            <option>Sistem Informasi</option>
            <option>Teknik Informatika</option>
        </select>

        <!--  ANGKATAN (BARU DI SINI) -->
        <select name="angkatan">
            <option value="">Semua Angkatan</option>
            <option value="26">2026</option>
            <option value="25">2025</option>
            <option value="24">2024</option>
            <option value="23">2023</option>
            <option value="22">2022</option>
            <option value="21">2021</option>
        </select>

        <!--  KELAS -->
        <select name="kelas">
            <option value="">Semua Kelas</option>
            <option>A</option>
            <option>B</option>
            <option>C</option>
            <option>D</option>
            <option>E</option>
        </select>

        <button type="submit" class="btn-cari">Cari</button>

        <button type="button" onclick="openModal()" class="btn-tambah">
            + Tambah Mahasiswa
        </button>
    </form>
</div>

<!-- MODAL -->
<div id="modalForm" class="modal">
    <div class="modal-box">

        <h3>Tambah Mahasiswa</h3>

        <form method="POST" id="formMahasiswa">
            <input type="hidden" name="id">

            <input name="nim" placeholder="NIM" required pattern="[0-9]+">
            <input name="nama" placeholder="Nama" required>

            <select name="prodi" required>
                <option value="">-- Pilih Prodi --</option>
                <option>Pendidikan Teknologi Informasi</option>
                <option>Sistem Informasi</option>
                <option>Teknik Informatika</option>
            </select>

            <select name="kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <option>A</option><option>B</option><option>C</option>
                <option>D</option><option>E</option>
            </select>

            <input name="email" type="email" placeholder="Email" required>
            <input name="telp" placeholder="No HP" required pattern="[0-9]+">

            <textarea name="alamat" placeholder="Alamat" required></textarea>

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
    <th>NIM</th>
    <th>Nama</th>
    <th>Prodi</th>
    <th>Kelas</th>
    <th>Email</th>
    <th>No HP</th>
    <th>Alamat</th>
    <th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)) { ?>
<tr>
    <td><?= htmlspecialchars($row['nim']) ?></td>
    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= htmlspecialchars($row['prodi']) ?></td>
    <td><?= htmlspecialchars($row['kelas']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['telp']) ?></td>
    <td class="alamat"><?= htmlspecialchars($row['alamat']) ?></td>

    <td>
        <a href="#" onclick="editData(
        '<?= $row['id'] ?>',
        '<?= $row['nim'] ?>',
        '<?= $row['nama'] ?>',
        '<?= $row['prodi'] ?>',
        '<?= $row['kelas'] ?>',
        '<?= $row['email'] ?>',
        '<?= $row['telp'] ?>',
        '<?= $row['alamat'] ?>'
        )">

<span class="material-symbols-outlined">
edit_square
</span>

</a>

<a href="layoututama.php?page=dosen&hapus=<?= $row['id'] ?>">

<span class="material-symbols-outlined">
delete
</span>

</a>
    </td>
</tr>
<?php } ?>
</table>

</div>
</div>

</div>

<script>
function openModal() {
    document.getElementById("modalForm").style.display = "flex";
    document.getElementById("formMahasiswa").reset();
    document.querySelector('[name="id"]').value = '';
    document.querySelector(".modal-box h3").innerText = "Tambah Mahasiswa";
}

function closeModal() {
    document.getElementById("modalForm").style.display = "none";
}

function editData(id, nim, nama, prodi, kelas, email, telp, alamat) {
    openModal();

    document.querySelector('[name="id"]').value = id;
    document.querySelector('[name="nim"]').value = nim;
    document.querySelector('[name="nama"]').value = nama;
    document.querySelector('[name="prodi"]').value = prodi;
    document.querySelector('[name="kelas"]').value = kelas;
    document.querySelector('[name="email"]').value = email;
    document.querySelector('[name="telp"]').value = telp;
    document.querySelector('[name="alamat"]').value = alamat;

    document.querySelector(".modal-box h3").innerText = "Edit Mahasiswa";
}

document.getElementById("formMahasiswa").addEventListener("submit", function(e){
    let nim = document.querySelector('[name="nim"]').value.trim();
    let telp = document.querySelector('[name="telp"]').value.trim();

    if(isNaN(nim) || isNaN(telp)){
        alert("NIM dan No HP harus berupa angka!");
        e.preventDefault();
    }
});
</script>