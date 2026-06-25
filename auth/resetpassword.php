<?php
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_GET['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_GET['email'];

if (isset($_POST['ubah'])) {
    $password = $_POST['password'];

    mysqli_query($conn, "UPDATE users SET password='$password' WHERE email='$email'");

    echo "<script>
        alert('Selamat, password anda berhasil diubah!');
        window.location='login.php';
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">

  <div class="login-card">

    <h2>RESET PASSWORD</h2>

    <form method="POST">

      <!-- EMAIL -->
      <input 
      type="email" 
      value="<?php echo $email; ?>" 
      readonly>

      <!-- PASSWORD -->
      <div class="password-wrapper">

        <input 
        type="password" 
        id="password" 
        name="password" 
        placeholder="Password Baru" 
        required>

        <span onclick="togglePassword()">
          👁
        </span>

      </div>

      <button type="submit" name="ubah">
        Ubah Password
      </button>

    </form>

    <div class="">
    <a href="login.php" class="back-btn">
      <
    </a>
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