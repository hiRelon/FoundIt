<?php
session_start();
require 'function.php';

if( !isset($_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}

// 1. Validasi ID Item dari URL
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id_item = $_GET["id"]; 

// 2. Ambil Data User untuk Navbar (Agar foto profil muncul)
$id_log = $_SESSION["id_user"];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_log'");
$user = mysqli_fetch_assoc($user_query);

$folder_foto = "img/profil/";
if (!empty($user['foto_profil']) && file_exists($folder_foto . $user['foto_profil'])) {
    $path_foto = $folder_foto . $user['foto_profil'];
} else {
    $path_foto = $folder_foto . "default.jpg"; 
}

// 3. Proses Form Submit
if (isset($_POST["submit"])) {
    if (tambahKlaim($_POST) > 0) {
      $hasil = statusSelesai($id_item); 
        echo "<script>
                alert('Selamat sudah menemukan barangmu kembali!');
                document.location.href='index.php';
              </script>";
    } else {
        echo "<script>alert('Gagal mengirim klaim!');</script>";
    }
}
?>

  <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>FoundIt</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        
    </head>
    <body>
        
        <!-- Navigation-->
      <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
           <div class="container px-4 px-lg-5">
                <a class="navbar-brand fw-bolder #181F39" href="#!"><i class="bi bi-box2-heart-fill"></i>  FoundIt</a>
                <form class="mt-2" method="post">
                  <div class="input-group">
                  <input 
                    type="text" 
                    class="form-control" 
                    name="keyword" 
                    placeholder="Cari barang..." 
                    autocomplete="off"
                    value="<?= isset($_POST['keyword']) ? $_POST['keyword'] : ''; ?>"
                  >
                  <button class="btn" style="background-color: #FDA597;" type="submit" name="search">
                    <i class="bi bi-search"></i> Cari
                  </button>
                </div>
                </form>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <!--begin::User Menu Dropdown-->
                        <li class="nav-item dropdown user-menu">
                          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="<?= $path_foto; ?>" 
                                class="user-image rounded-circle shadow" 
                                alt="User Image"
                                style="width: 35px; height: 35px; object-fit: cover;" />
                            <span class="d-none d-md-inline">
                              <?= ucfirst($_SESSION["username"]); ?>
                            </span>
                          </a>
                          <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end text-center" style="min-width: 300px;">
                            <!--begin::User Image-->
                            <li class="user-header text-center p-4" style="background-color: #ADD8CE;">
                                <img src="<?= $path_foto; ?>" 
                                  class="rounded-circle shadow mb-2" 
                                  alt="User Image"
                                  style="width: 90px; height: 90px; object-fit: cover; border: 3px solid white;" />
                              <p>
                                <?= ucfirst($_SESSION["username"]); ?> <br>
                              </p>
                            </li>
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="../index.php">Home</a></li>
                            <!-- <li class="nav-item"><a class="nav-link" href="#!">About</a></li> -->
                            <li class="user-footer p-2">
                          <div class="row g-2"> <div class="col-6">
                              <a href="user/profil.php" class="btn btn-light border btn-block w-100">Profile</a>
                            </div>
                            <div class="col-6">
                              <a href="logout.php" class="btn btn-light border btn-block w-100">Log out</a>
                            </div>
                          </div>
                        </li>
                          </ul>

                    </ul>
                    </form>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="py-5" style="background-color: #181F39;">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Yeay barangmu ketemu!</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Ayo isi datanya</p>
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-5">
            <div class="container p-4">
    <div class="row justify-content-center">
        <div class="card shadow p-5">
        <form method="POST" enctype="multipart/form-data" class="row g-3 p-4">
                        <input type="hidden" name="id_item" value="<?= $id_item; ?>"> 
                        <input type="hidden" name="id_user" value="<?= $_SESSION['id_user']; ?>">
                        
                        <div class="col-12">
                            <label class="form-label">Deskripsikan Bukti Kuat (Opsional)</label>
                            <textarea name="deskripsi_bukti" class="form-control" rows="3" placeholder="Contoh: Ada goresan di pojok kanan bawah..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Unggah Foto Bukti Kepemilikan</label>
                            <input type="file" name="bukti_kepemilikan" class="form-control" required>
                            <small class="text-muted">Bisa berupa foto kotak barang, nota pembelian, atau foto Anda bersama barang tersebut.</small>
                        </div>
                        <div class="col-12">
                            <button type="submit" name="submit" class="btn fw-bolder" style="background-color: #ADD8CE;">Klaim</button>
                            <a href="index.php" class="btn btn-secondary fw-bolder">Batal</a>
                        </div>

                    </form>
                    </div>
                </div>

        </section>
        <!-- Footer-->
        <footer class="py-5" style="background-color: #181F39;">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; FoundIt 2026</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
