<?php
session_start();

require_once __DIR__ . '/../config/koneksi.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // ambil user berdasarkan email
    // ambil user + nama mahasiswa
    $q = mysqli_query($conn, "
    SELECT 
        users.*,
        mahasiswa.nama AS nama_mahasiswa,
        dosen.nama AS nama_dosen

    FROM users

    LEFT JOIN mahasiswa
    ON users.email = mahasiswa.email

    LEFT JOIN dosen
    ON users.email = dosen.email

    WHERE users.email='$email'
    ");

$user = mysqli_fetch_assoc($q);

    if ($user) {

        // cek password (masih plain, sesuai sistem kamu sekarang)
        if ($password == $user['password']) {

            // ================= SESSION =================
            // ================= SESSION =================
            $_SESSION['login']   = true;
            $_SESSION['role']    = $user['role'];
            $_SESSION['id_user'] = $user['id'];
            $_SESSION['email']   = $user['email'];

            if ($user['role'] == 'mahasiswa') {
                $_SESSION['nama'] = $user['nama_mahasiswa'];
            } elseif ($user['role'] == 'dosen') {
                $_SESSION['nama'] = $user['nama_dosen'];
            } else {
                $_SESSION['nama'] = 'Admin';
            }

            // ================= REDIRECT (FIX) =================
            // semua role masuk 1 halaman
            header("Location: ../dashboard/layoututama.php");
            exit;

        } else {
            $error = "Password salah!";
        }

    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>WEB ABSENSI</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="login-card">

    <h2>LOGIN</h2>

    <?php if (isset($error)) { ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">

        <input type="email" name="email" placeholder="Email" required>

        <div class="password-wrapper">
            <input type="password" id="password" name="password" placeholder="Password" required>

            <span onclick="togglePassword()">👁</span>
        </div>

        <button type="submit" name="login">Login</button>

    </form>

    <div class="links">
        <a href="lupapassword.php">Forgot Password ?</a>
        
    </div>

   

</div>

</div>
<script>
function togglePassword() {
    var x = document.getElementById("password");

    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
</script>
</body>
</html>