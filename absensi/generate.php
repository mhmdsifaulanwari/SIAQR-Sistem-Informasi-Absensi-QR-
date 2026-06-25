<?php
require_once '../config/koneksi.php';
include "../phpqrcode/qrlib.php";
?>

<link rel="stylesheet" href="../assets/dashboard.css">

<h2>Generate QR Absensi</h2>

<!-- FILTER -->
<div class="card">
<form method="GET" action="layoututama.php" class="form-filter">
<input type="hidden" name="page" value="generateqr">

<select name="prodi" onchange="this.form.submit()">
<option value="">Semua Prodi</option>
<option value="Pendidikan Teknologi Informasi" <?= ($_GET['prodi'] ?? '')=='Pendidikan Teknologi Informasi'?'selected':'' ?>>Pendidikan Teknologi Informasi</option>
<option value="Teknik Informatika" <?= ($_GET['prodi'] ?? '')=='Teknik Informatika'?'selected':'' ?>>Teknik Informatika</option>
<option value="Sistem Informasi" <?= ($_GET['prodi'] ?? '')=='Sistem Informasi'?'selected':'' ?>>Sistem Informasi</option>
</select>

<select name="angkatan" onchange="this.form.submit()">
<option value="">Semua Angkatan</option>
<?php for($i=2026;$i>=2021;$i--){ ?>
<option value="<?= $i ?>" <?= ($_GET['angkatan'] ?? '')==$i?'selected':'' ?>><?= $i ?></option>
<?php } ?>
</select>

<select name="kelas" onchange="this.form.submit()">
<option value="">Semua Kelas</option>
<?php foreach(['A','B','C','D','E'] as $k){ ?>
<option value="<?= $k ?>" <?= ($_GET['kelas'] ?? '')==$k?'selected':'' ?>><?= $k ?></option>
<?php } ?>
</select>

<?php $matkul=mysqli_query($conn,"SELECT * FROM matkul"); ?>
<select name="matkul" onchange="this.form.submit()">
<option value="">Semua Matkul</option>
<?php while($m=mysqli_fetch_assoc($matkul)){ ?>
<option value="<?= $m['id'] ?>" <?= ($_GET['matkul'] ?? '')==$m['id']?'selected':'' ?>>
<?= $m['nama_matkul'] ?>
</option>
<?php } ?>
</select>

</form>
</div>

<?php
$role  = $_SESSION['role'] ?? '';
$email = $_SESSION['email'] ?? '';

$where = "WHERE 1=1";


if($role == 'dosen'){

    $dosen = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT id FROM dosen WHERE email='$email'
    "));

    if($dosen){
        $id_dosen = $dosen['id'];
        $where .= " AND jadwal.dosen_id = '$id_dosen'";
    }
}

if(!empty($_GET['prodi'])) $where.=" AND jadwal.prodi LIKE '%".$_GET['prodi']."%'";
if(!empty($_GET['angkatan'])) $where.=" AND jadwal.angkatan='".$_GET['angkatan']."'";
if(!empty($_GET['kelas'])) $where.=" AND jadwal.kelas='".$_GET['kelas']."'";
if(!empty($_GET['matkul'])) $where.=" AND jadwal.matkul_id='".$_GET['matkul']."'";

$jadwal=mysqli_query($conn,"
SELECT jadwal.*, matkul.nama_matkul 
FROM jadwal 
JOIN matkul ON jadwal.matkul_id=matkul.id
$where
");
?>

<!-- PILIH JADWAL -->
<div class="card">
<form method="POST" class="form-jadwal">

<select name="jadwal" class="select-jadwal">
<option value="">Pilih Jadwal</option>
<?php while($j=mysqli_fetch_assoc($jadwal)){ ?>
<option value="<?= $j['id'] ?>">
<?= $j['nama_matkul'] ?> | <?= $j['prodi'] ?> | <?= $j['angkatan'] ?> | Kelas <?= $j['kelas'] ?>
</option>
<?php } ?>
</select>

<button name="generate" class="btn-primary">Generate QR</button>

</form>
</div>

<?php
$id = $_POST['jadwal'] ?? $_GET['jadwal'] ?? null;

if($id){

    $file = "qr_".$id.".png";

    if(!file_exists("../assets/qr/".$file)){
        $data = "http://localhost/MiniProject1Web/absensi/scan.php?jadwal=".$id;
        QRcode::png($data,"../assets/qr/".$file);
    }

    echo "<div class='card'>";
    echo "<h3>QR Code</h3>";
    echo "<img src='../assets/qr/$file' width='200'><br><br>";

    echo "<button onclick='openQR()' class='btn-primary'>
Perbesar QR
</button> ";

    echo "<a href='../absensi/cek_absensi.php?jadwal=$id' 
class='btn-primary' style='background:#27ae60;text-decoration:none;'>
Cek Absensi
</a>";

?>

<!-- MODAL QR -->
<div id="qrModal" class="qr-modal">

    <span class="close-btn" onclick="closeQR()">&times;</span>

    <div class="zoom-container">

        <img 
        src="../assets/qr/<?php echo $file; ?>" 
        id="zoomQR"
        class="zoom-qr">

    </div>

</div>

<style>

.qr-modal{
    display:none;
    position:fixed;
    z-index:9999;
    left:0;
    top:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.9);
}

.zoom-container{
    width:100%;
    height:100%;
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:hidden;
}

.zoom-qr{
    width:400px;
    transition:transform 0.2s ease;
}

.close-btn{
    position:absolute;
    top:20px;
    right:35px;
    color:white;
    font-size:40px;
    cursor:pointer;
}

</style>

<script>

let scale = 1;

function openQR(){
    document.getElementById("qrModal").style.display = "block";
}

function closeQR(){
    document.getElementById("qrModal").style.display = "none";
}

const qr = document.getElementById("zoomQR");

qr.addEventListener("wheel", function(e){

    e.preventDefault();

    if(e.deltaY < 0){
        scale += 0.1;
    } else {
        scale -= 0.1;
    }

    if(scale < 1) scale = 1;
    if(scale > 5) scale = 5;

    qr.style.transform = `scale(${scale})`;

});

</script>

<?php



    echo "</div>";
}
?>