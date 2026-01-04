<?php
session_start();
    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }

require 'function.php';

$kategori = [];
$query = mysqli_query($conn, "SELECT * FROM categories");

while($row = mysqli_fetch_assoc($query)){
    $kategori[] = $row;
}

if(isset($_POST['submit'])){
     if(tambahBarang($_POST, $_FILES) > 0){
            echo "
                <script>
                    alert('Laporan berhasil dikirimkan!');
                    document.location.href = 'index.php';
                </script>
            ";
        }else{
            echo "
                <script>
                    alert('Laporan gagal dikirimkan!');
                    document.location.href = 'laporan.php';
                </script>
            ";
        }
    }

$id_user = $_SESSION['id_user'];

// Query ke database untuk mendapatkan data user (termasuk kolom foto_profil)
$result_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user = $id_user");
$user = mysqli_fetch_assoc($result_user);


$folder_profil = "img/profil/";
if (!empty($user['foto_profil']) && file_exists($folder_profil . $user['foto_profil'])) {
    $path_foto = $folder_profil . $user['foto_profil'];
} else {
    $path_foto = $folder_profil . "default.jpg"; 
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
                    <h1 class="display-4 fw-bolder">Apa yang kamu temukan?</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Ayo isi datanya dan temukan pemiliknya</p>
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-2">
                <div class="justify-content-center">
                    <div class="card shadow p-5">
                    <form method="POST" enctype="multipart/form-data" class="row g-3">
                        <input type="hidden" name="id_user" value="<?= $_SESSION['id_user']; ?>">
                        <div class="col-md-6">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" id="nama_barang" placeholder="Nama Barang..." autocomplete="off" required>
                        </div>

                        <div class="col-md-6">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select name="id_kategori" id="kategori" class="form-select" autocomplete="off" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach($kategori as $k) : ?>
                                    <option value="<?= $k['id_kategori']; ?>"><?= $k['nama_kategori']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3" placeholder="Bentuk, warna, ukuran..." autocomplete="off" ></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="lokasi_temuan" class="form-label">Lokasi Temuan</label>
                            <input type="text" name="lokasi_temuan" class="form-control" id="lokasi_temuan" placeholder="Kampus, masjid, kelas..." autocomplete="off" >
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_temuan" class="form-label">Tanggal Temuan</label>
                            <input type="date" name="tanggal_temuan" class="form-control" id="tanggal_temuan" autocomplete="off" required>
                        </div>

                        <div class="col-md-6">
                            <label for="foto_barang" class="form-label">Foto Barang</label>
                            <input type="file" name="gambar" class="form-control" id="foto_barang" required>
                                </div>

                        <div class="col-md-6">
                            <label class="form-label">Kontak</label>
                            <input type="text" 
                                name="cp" 
                                class="form-control"
                                placeholder="628...."
                                autocomplete="off"  
                                required>
                        </div>

                        <div class="col-12">
                            <button type="submit" name="submit" class="btn" style="background-color: #FDA597;"><b>Kirim Laporan</b></button>
                        </div>
                    </form>
                    </div>
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
