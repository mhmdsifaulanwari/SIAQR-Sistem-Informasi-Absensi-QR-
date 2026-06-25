<?php
require_once __DIR__ . '/../config/koneksi.php';

if (isset($_POST['cek'])) {
    $email = $_POST['email'];

    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($q) > 0) {
        header("Location: resetpassword.php?email=$email");
        exit;
    } else {
        $error = "Email tidak terdaftar!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>LupaPassword</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">

  <div class="login-card">

    <h2>LUPA PASSWORD</h2>

    <?php if(isset($error)) { ?>
      <p class="error"><?= $error ?></p>
    <?php } ?>

    <form method="POST">

      <input 
      type="email" 
      name="email" 
      placeholder="Masukkan Email"
      required>

      <button type="submit" name="cek">
        Lanjut
      </button>

    </form>

    <div class="">
    <a href="login.php" class="back-btn">
      <
    </a>
    </div>

  </div>

</div>

</body>
</html>