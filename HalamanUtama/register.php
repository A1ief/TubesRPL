<?php
include('../koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $confirm = mysqli_real_escape_string($koneksi, $_POST['confirm_password']);

    // Validasi password
    if ($password !== $confirm) {
        echo "<script>alert('Konfirmasi password tidak sesuai!'); window.location.href='register.php';</script>";
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah email sudah digunakan
    $cek_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.location.href='register.php';</script>";
        exit;
    }

    // Simpan data
    $insert = mysqli_query($koneksi, "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$hashed_password')");

    if ($insert) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal!'); window.location.href='register.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="../HalamanAdmin/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

  <div class="container">
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-register-image">
                <img src="assets/img/departments-2.jpg" alt=""
                  style="width: 100%; height: 100vh; object-fit: cover;">
              </div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                  </div>
                  <form class="user" method="POST" action="">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" name="nama" placeholder="Full Name"
                        required>
                    </div>
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user" name="email"
                        placeholder="Email Address" required>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-6 mb-3 mb-sm-0">
                        <input type="password" class="form-control form-control-user" name="password"
                          placeholder="Password" required>
                      </div>
                      <div class="col-sm-6">
                        <input type="password" class="form-control form-control-user" name="confirm_password"
                          placeholder="Repeat Password" required>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      Register Account
                    </button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="login.php">Already have an account? Login!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
