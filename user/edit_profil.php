<?php
session_start();
require 'function_user.php';

if(!isset($_SESSION["login"])) {
   header("Location: login.php");
   exit;
}

$id_user = $_SESSION["id_user"];

// LOGIKA UPDATE: Jalankan fungsi jika tombol submit diklik
if(isset($_POST["submit"])) {
    if(editProfil($_POST) > 0) {
        echo "<script>
                alert('Profil berhasil diperbarui!');
                document.location.href = 'profil.php';
              </script>";
    } else {
        echo "<script>alert('Tidak ada perubahan data atau gagal update');</script>";
    }
}

// Ambil data user terbaru
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
$user = mysqli_fetch_assoc($query_user);

// ... kode session & query ...

$folder_foto = "../img/profil/";
if (!empty($user['foto_profil']) && file_exists($folder_foto . $user['foto_profil'])) {
    $path_foto = $folder_foto . $user['foto_profil'];
} else {
    $path_foto = $folder_foto . "default.jpg"; 
}

?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Profil</title>
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


        
    </head>
    <body id="page-top">
         <!-- Navigation-->
      <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
           <div class="container px-4 px-lg-5">
                <a class="navbar-brand fw-bolder #181F39" href="#!"><i class="bi bi-box2-heart-fill"></i>  FoundIt</a>
                <form class="mt-2" method="post">
                  <!-- <div class="input-group">
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
                </div> -->
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
                              <a href="profil.php" class="btn btn-light border btn-block w-100">Profile</a>
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
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:  #181F39;" id="sideNav">
            <a class="navbar-brand js-scroll-trigger" href="#page-top">
                <img class="img-fluid img-profile rounded-circle mx-auto mb-2" 
     src="<?= $path_foto; ?>" 
     alt="Profile" 
     style="width: 160px; height: 160px; object-fit: cover; border: 5px solid rgba(255,255,255,0.2);" />
</span>
            </a>
            <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button> -->
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link js-scroll-trigger" href="#about">
            <?= ucfirst($_SESSION["username"]); ?>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link js-scroll-trigger" href="edit_profil.php">
            <i class="bi bi-pencil-square"></i>
        </a>
    </li>

    <li class="nav-item">
        <p><?= $user['bio']; ?></p>
    </li>

    <li class="nav-item">
        <p><?= $user['email']; ?></p>
    </li>

    <li class="nav-item">
        <a class="nav-link js-scroll-trigger" href="../logout.php">
            <i class="bi bi-door-open-fill"></i> Log Out
        </a>
    </li>
</ul>
            </div>
        </nav>
        <!-- Page Content-->
       <section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="justify-content-center">
            <div class="card shadow p-4">
            <form method="POST" enctype="multipart/form-data" class="row g-3">
                <input type="hidden" name="id_user" value="<?= $user['id_user']; ?>">
                <input type="hidden" name="gambarLama" value="<?= $user['foto_profil']; ?>">

                <div class="col-12">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= $user['username']; ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" class="form-control" rows="3"><?= $user['bio']; ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" value="<?= $user['email']; ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kontak</label>
                    <input type="text" name="kontak" class="form-control" value="<?= $user['whatsapp']; ?>">
                </div>

                <div class="col-12">
    <label class="form-label d-block">Foto Profil Saat Ini</label>
    <img src="<?= $path_foto; ?>" 
         class="rounded-circle mb-3 shadow" 
         style="width: 120px; height: 120px; object-fit: cover; border: 2px solid;">
    
    <input type="file" name="gambar" class="form-control">
    <small class="text-muted">Format: JPG, PNG. Maks 2MB</small>
</div>

                <div class="col-12">
                    <button type="submit" name="submit" class="btn " style="background-color: #FDA597;">
                        <b>Simpan Perubahan</b>
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</section>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
