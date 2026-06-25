<?php
require_once '../config/koneksi.php';

$email = $_SESSION['email'];
$role  = $_SESSION['role'];

if($role == 'mahasiswa'){

    $user = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT users.*, mahasiswa.*
    FROM users
    LEFT JOIN mahasiswa 
    ON users.email = mahasiswa.email
    WHERE users.email='$email'
    "));

} elseif($role == 'dosen'){

    $user = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT users.*, dosen.*
    FROM users
    LEFT JOIN dosen 
    ON users.email = dosen.email
    WHERE users.email='$email'
    "));

} else {

    $user = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM users
    WHERE email='$email'
    "));
}


/* ================= HAPUS FOTO ================= */

if(isset($_POST['hapus_foto'])){

    $fotoLama = $user['foto'] ?? '';

    // HAPUS FILE FOTO
    if($fotoLama != '' && file_exists("../assets/profile/".$fotoLama)){

        unlink("../assets/profile/".$fotoLama);

    }

    // HAPUS FOTO DI DATABASE
    if($role == 'mahasiswa'){

        mysqli_query($conn,"
        UPDATE mahasiswa
        SET foto=''
        WHERE email='$email'
        ");

    } elseif($role == 'dosen'){

        mysqli_query($conn,"
        UPDATE dosen
        SET foto=''
        WHERE email='$email'
        ");

    }

    echo "<script>
    alert('Foto berhasil dihapus');
    window.location='layoututama.php?page=dashboard';
    </script>";
}


/* ================= UPLOAD FOTO ================= */

if(isset($_POST['upload_foto'])){

    $namaFile = $_FILES['foto']['name'];
    $tmp      = $_FILES['foto']['tmp_name'];

    if($namaFile != ''){

        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        // VALIDASI FORMAT
        $allowed = ['jpg','jpeg','png'];

        if(in_array($ext, $allowed)){

            $namaBaru = time().".".$ext;

            // UPLOAD FILE
            if(move_uploaded_file($tmp, "../assets/profile/".$namaBaru)){

                // HAPUS FOTO LAMA
                $fotoLama = $user['foto'] ?? '';

                if($fotoLama != '' && file_exists("../assets/profile/".$fotoLama)){

                    unlink("../assets/profile/".$fotoLama);

                }

                // UPDATE DATABASE
                if($role == 'mahasiswa'){

                    mysqli_query($conn,"
                    UPDATE mahasiswa
                    SET foto='$namaBaru'
                    WHERE email='$email'
                    ");

                } elseif($role == 'dosen'){

                    mysqli_query($conn,"
                    UPDATE dosen
                    SET foto='$namaBaru'
                    WHERE email='$email'
                    ");

                }

                echo "<script>
                alert('Foto profil berhasil diupload');
                window.location='layoututama.php?page=dashboard';
                </script>";

            } else {

                echo "<script>
                alert('Upload gagal!');
                </script>";
            }

        } else {

            echo "<script>
            alert('Format foto harus JPG, JPEG, atau PNG');
            </script>";
        }
    }
}
?>

<div class="navbar">

    <h1><?= htmlspecialchars($_SESSION['nama']); ?></h1>

    <!-- FOTO PROFIL -->
    <div class="profile-box">

        <?php
        $foto = $user['foto'] ?? '';
        ?>

        <!-- FOTO -->
        <img 
        src="../assets/profile/<?= $foto != '' ? $foto : 'default.jpg' ?>">

        <!-- FORM UPLOAD -->
        <form method="POST" enctype="multipart/form-data">

            <label class="custom-file-upload">
            Pilih Foto
            <input 
            type="file" 
            name="foto" 
            accept=".jpg,.jpeg,.png">
            </label>

            <div class="profile-buttons">

                <button 
                type="submit" 
                name="upload_foto" 
                class="btn-primary">

                    Upload Foto

                </button>

        </form>

        <!-- FORM HAPUS -->
        <?php if($foto != ''){ ?>

        <form method="POST">

            <button
            type="submit"
            name="hapus_foto"
            class="btn-danger"
            onclick="return confirm('Hapus foto profil?')">

                Hapus Foto

            </button>

        </form>

        <?php } ?>

            </div>

    </div>

    <!-- DATA PROFILE -->
    <table class="table-profile">

        <tr>
            <td><b>Email</b></td>
            <td>: <?= $_SESSION['email']; ?></td>
        </tr>

        <tr>
    <td><b>Pekerjaan</b></td>

    <td>
        :
        <?php
        if($role == 'admin'){
            echo "Administrator";
        } elseif($role == 'dosen'){
            echo "Dosen";
        } else {
            echo "Mahasiswa";
        }
        ?>
    </td>
</tr>

        <!-- KHUSUS DOSEN & MAHASISWA -->
        <?php if($role != 'admin'){ ?>

        <tr>
            <td>
                <b>
                    <?= $role == 'dosen' ? 'NIDN' : 'NIM' ?>
                </b>
            </td>

            <td>
                :
                <?= $role == 'dosen'
                    ? ($user['nidn'] ?? '-')
                    : ($user['nim'] ?? '-') ?>
            </td>
        </tr>

        <tr>
            <td><b>Prodi</b></td>
            <td>: <?= $user['prodi'] ?? '-'; ?></td>
        </tr>

        <?php if($role == 'mahasiswa'){ ?>

        <tr>
            <td><b>Kelas</b></td>
            <td>: <?= $user['kelas'] ?? '-'; ?></td>
        </tr>

        <?php } ?>

        <tr>
            <td><b>No Telepon</b></td>
            <td>: <?= $user['telp'] ?? '-'; ?></td>
        </tr>

        <tr>
            <td><b>Alamat</b></td>
            <td>: <?= $user['alamat'] ?? '-'; ?></td>
        </tr>

        <?php } ?>

    </table>

</div>