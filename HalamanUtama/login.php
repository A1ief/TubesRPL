<?php
session_start();
include('../koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = mysqli_real_escape_string($koneksi, $_POST['email']);
  $password = mysqli_real_escape_string($koneksi, $_POST['password']);

  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($koneksi, $query);

  if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['nama'] = $user['nama'];

      header("Location: ../HalamanAdmin/index.php");
      exit;
    } else {
      echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
    }
  } else {
    echo "<script>alert('Email tidak ditemukan!'); window.location.href='login.php';</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="../HalamanAdmin/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

  <div class="container">
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image">
                <img src="assets/img/departments-1.jpg" alt=""
                  style="width: 100%; height: 100vh; object-fit: cover;">
              </div>
              <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="p-5" style="width: 100%;">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Login</h1>
                  </div>
                  <form class="user" method="POST" action="">
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user" name="email"
                        placeholder="Enter Email Address..." required>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="password"
                        placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      Login
                    </button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="register.php">Create an Account!</a>
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
