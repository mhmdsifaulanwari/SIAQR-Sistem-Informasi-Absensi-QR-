<?php
require_once '../config/koneksi.php';

/* ================= HAPUS ================= */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // ambil email dulu untuk hapus di users
    $get = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM dosen WHERE id='$id'"));

    mysqli_query($conn, "DELETE FROM dosen WHERE id='$id'");
    mysqli_query($conn, "DELETE FROM users WHERE email='".$get['email']."' AND role='dosen'");

    echo "<script>location='layoututama.php?page=dosen';</script>";
}

/* ================= SIMPAN ================= */
if (isset($_POST['simpan'])) {

    $nidn = $_POST['nidn'];
    $nama = $_POST['nama'];
    $prodi = $_POST['prodi'];
    $email = $_POST['email'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    // VALIDASI
    if ($nidn=='' || $nama=='' || $prodi=='' || $email=='' || $telp=='' || $alamat=='') {
        echo "<script>alert('Harap masukkan semua data dengan benar!')</script>";
    }
    elseif (!is_numeric($nidn) || !is_numeric($telp)) {
        echo "<script>alert('NIDN dan No HP harus berupa angka!')</script>";
    }
    else {

        // TAMBAH
        if ($_POST['id'] == '') {

            // CEK EMAIL
            $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

            if(mysqli_num_rows($cek) > 0){
                echo "<script>alert('Email sudah digunakan!')</script>";
            } else {

                // SIMPAN DOSEN
                mysqli_query($conn, "INSERT INTO dosen 
                (nidn,nama,prodi,email,telp,alamat)
                VALUES ('$nidn','$nama','$prodi','$email','$telp','$alamat')");

                // SIMPAN USERS (LOGIN DOSEN)
                mysqli_query($conn, "INSERT INTO users 
                (email,password,role)
                VALUES ('$email','$nidn','dosen')");

                echo "<script>alert('Data berhasil ditambahkan')</script>";
            }

        } 
        // EDIT
        else {

            $id = $_POST['id'];

            // ambil email lama
            $lama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM dosen WHERE id='$id'"));
            $email_lama = $lama['email'];

            // update dosen
            mysqli_query($conn, "UPDATE dosen SET
            nidn='$nidn',
            nama='$nama',
            prodi='$prodi',
            email='$email',
            telp='$telp',
            alamat='$alamat'
            WHERE id='$id'");

            // update users juga kalau email berubah
            mysqli_query($conn, "UPDATE users SET
            email='$email',
            password='$nidn'
            WHERE email='$email_lama' AND role='dosen'");

            echo "<script>alert('Data berhasil diupdate')</script>";
        }
    }
}

/* ================= FILTER ================= */
$search = $_GET['search'] ?? '';
$prodi_filter = $_GET['prodi'] ?? '';

$where = "WHERE 1=1";

if ($search != '') {
    $where .= " AND (nidn LIKE '%$search%' OR nama LIKE '%$search%')";
}
if ($prodi_filter != '') {
    $where .= " AND prodi='$prodi_filter'";
}

$data = mysqli_query($conn, "SELECT * FROM dosen $where ORDER BY nama ASC");
?>

<link rel="stylesheet" href="../assets/dashboard.css">
<link rel="stylesheet"
href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

<h2>Kelola Data Dosen</h2>

<!-- FILTER -->
<div class="card">
<form method="GET" class="form-filter">
    <input type="hidden" name="page" value="dosen">

    <input type="text" name="search" placeholder="Cari NIDN / Nama">

    <select name="prodi">
        <option value="">Semua Prodi</option>
        <option>Pendidikan Teknologi Informasi</option>
        <option>Sistem Informasi</option>
        <option>Teknik Informatika</option>
    </select>

    <button class="btn-cari">Cari</button>

    <button type="button" onclick="openModal()" class="btn-tambah">
        + Tambah Dosen
    </button>
</form>
</div>

<!-- MODAL -->
<div id="modalForm" class="modal">
<div class="modal-box">

<h3>Tambah Dosen</h3>

<form method="POST" id="formDosen">
    <input type="hidden" name="id">

    <input name="nidn" placeholder="NIDN" required pattern="[0-9]+">
    <input name="nama" placeholder="Nama" required>

    <select name="prodi" required>
        <option value="">-- Pilih Prodi --</option>
        <option>Pendidikan Teknologi Informasi</option>
        <option>Sistem Informasi</option>
        <option>Teknik Informatika</option>
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
    <th>NIDN</th>
    <th>Nama</th>
    <th>Prodi</th>
    <th>Email</th>
    <th>No HP</th>
    <th>Alamat</th>
    <th>Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)) { ?>
<tr>
    <td><?= htmlspecialchars($row['nidn']) ?></td>
    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= htmlspecialchars($row['prodi']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['telp']) ?></td>
    <td class="alamat"><?= htmlspecialchars($row['alamat']) ?></td>

    <td>
        <a href="#" onclick="editData(
        '<?= $row['id'] ?>',
        '<?= $row['nidn'] ?>',
        '<?= $row['nama'] ?>',
        '<?= $row['prodi'] ?>',
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

<script>
function openModal() {
    document.getElementById("modalForm").style.display = "flex";
    document.getElementById("formDosen").reset();
    document.querySelector('[name="id"]').value = '';
    document.querySelector(".modal-box h3").innerText = "Tambah Dosen";
}

function closeModal() {
    document.getElementById("modalForm").style.display = "none";
}

function editData(id, nidn, nama, prodi, email, telp, alamat) {
    openModal();

    document.querySelector('[name="id"]').value = id;
    document.querySelector('[name="nidn"]').value = nidn;
    document.querySelector('[name="nama"]').value = nama;
    document.querySelector('[name="prodi"]').value = prodi;
    document.querySelector('[name="email"]').value = email;
    document.querySelector('[name="telp"]').value = telp;
    document.querySelector('[name="alamat"]').value = alamat;

    document.querySelector(".modal-box h3").innerText = "Edit Dosen";
}

document.getElementById("formDosen").addEventListener("submit", function(e){
    let nidn = document.querySelector('[name="nidn"]').value.trim();
    let telp = document.querySelector('[name="telp"]').value.trim();

    if(isNaN(nidn) || isNaN(telp)){
        alert("NIDN dan No HP harus berupa angka!");
        e.preventDefault();
    }
});
</script>