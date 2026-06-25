<?php
require_once '../config/koneksi.php';

/* HAPUS */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM matkul WHERE id='$id'");
    echo "<script>location='layoututama.php?page=matkul';</script>";
}

/* SIMPAN */
if (isset($_POST['simpan'])) {

    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $sks = $_POST['sks'];
    $prodi = $_POST['prodi'];

    if ($kode=='' || $nama=='' || $sks=='' || $prodi=='') {
        echo "<script>alert('Harap isi semua data!')</script>";
    }
    elseif (!is_numeric($sks)) {
        echo "<script>alert('SKS harus angka!')</script>";
    }
    else {

        if ($_POST['id'] == '') {

            mysqli_query($conn, "INSERT INTO matkul 
            (kode_matkul,nama_matkul,sks,prodi)
            VALUES ('$kode','$nama','$sks','$prodi')");

            echo "<script>alert('Data berhasil ditambahkan')</script>";

        } else {

            $id = $_POST['id'];

            mysqli_query($conn, "UPDATE matkul SET
            kode_matkul='$kode',
            nama_matkul='$nama',
            sks='$sks',
            prodi='$prodi'
            WHERE id='$id'");

            echo "<script>alert('Data berhasil diupdate')</script>";
        }
    }
}

/* FILTER */
$search = $_GET['search'] ?? '';
$prodi_filter = $_GET['prodi'] ?? ''; // ✅ TAMBAHAN

$where = "WHERE 1=1";

if ($search != '') {
    $where .= " AND (kode_matkul LIKE '%$search%' OR nama_matkul LIKE '%$search%')";
}

// ✅ FILTER PRODI
if ($prodi_filter != '') {
    $where .= " AND prodi='$prodi_filter'";
}

$data = mysqli_query($conn, "SELECT * FROM matkul $where ORDER BY nama_matkul ASC");
?>

<link rel="stylesheet" href="../assets/dashboard.css">
<link rel="stylesheet"
href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
<h2>Kelola Mata Kuliah</h2>

<!-- FILTER -->
<div class="card">
<form method="GET" class="form-filter">
    <input type="hidden" name="page" value="matkul">

    <!-- SEARCH -->
    <input type="text" name="search" placeholder="Cari Kode / Nama Matkul"
    value="<?= $search ?>">

    <!-- ✅ FILTER PRODI -->
    <select name="prodi">
        <option value="">Semua Prodi</option>

        <option value="Pendidikan Teknologi Informasi"
        <?= $prodi_filter=='Pendidikan Teknologi Informasi'?'selected':'' ?>>
        Pendidikan Teknologi Informasi
        </option>

        <option value="Sistem Informasi"
        <?= $prodi_filter=='Sistem Informasi'?'selected':'' ?>>
        Sistem Informasi
        </option>

        <option value="Teknik Informatika"
        <?= $prodi_filter=='Teknik Informatika'?'selected':'' ?>>
        Teknik Informatika
        </option>
    </select>

    <button class="btn-cari">Cari</button>

    <button type="button" onclick="openModal()" class="btn-tambah">
        + Tambah Matkul
    </button>
</form>
</div>

<!-- MODAL (TIDAK DIUBAH) -->
<div id="modalForm" class="modal">
<div class="modal-box">

<h3>Tambah Mata Kuliah</h3>

<form method="POST" id="formMatkul">
    <input type="hidden" name="id">

    <input name="kode" placeholder="Kode Matkul" required>
    <input name="nama" placeholder="Nama Matkul" required>
    <input name="sks" placeholder="SKS" required>

    <select name="prodi" required>
        <option value="">-- Pilih Prodi --</option>
        <option>Pendidikan Teknologi Informasi</option>
        <option>Sistem Informasi</option>
        <option>Teknik Informatika</option>
    </select>

    <div class="modal-footer">
        <button type="button" onclick="closeModal()" class="btn-danger">Batal</button>
        <button name="simpan" class="btn-primary">Simpan</button>
    </div>
</form>

</div>
</div>

<!-- TABLE (TIDAK DIUBAH) -->
<div class="card table-card">
<div class="table-scroll">

<table>
<tr>
    <th>Kode</th>
    <th>Nama Matkul</th>
    <th>SKS</th>
    <th>Prodi</th>
    <th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)) { ?>
<tr>
    <td><?= $row['kode_matkul'] ?></td>
    <td><?= $row['nama_matkul'] ?></td>
    <td><?= $row['sks'] ?></td>
    <td><?= $row['prodi'] ?></td>

    <td>
        <a href="#" onclick="editData(
        '<?= $row['id'] ?>',
        '<?= $row['kode_matkul'] ?>',
        '<?= $row['nama_matkul'] ?>',
        '<?= $row['sks'] ?>',
        '<?= $row['prodi'] ?>'
        )">

<span class="material-symbols-outlined">
edit_square
</span>

</a>

<a href="layoututama.php?page=matkul&hapus=<?= $row['id'] ?>">

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

<script>
function openModal() {
    document.getElementById("modalForm").style.display = "flex";
    document.getElementById("formMatkul").reset();
    document.querySelector('[name="id"]').value = '';
    document.querySelector(".modal-box h3").innerText = "Tambah Mata Kuliah";
}

function closeModal() {
    document.getElementById("modalForm").style.display = "none";
}

function editData(id, kode, nama, sks, prodi) {
    openModal();

    document.querySelector('[name="id"]').value = id;
    document.querySelector('[name="kode"]').value = kode;
    document.querySelector('[name="nama"]').value = nama;
    document.querySelector('[name="sks"]').value = sks;
    document.querySelector('[name="prodi"]').value = prodi;

    document.querySelector(".modal-box h3").innerText = "Edit Mata Kuliah";
}
</script>