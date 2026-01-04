<?php
session_start();
require 'function_user.php';

if(!isset($_SESSION["login"])) {
   header("Location: login.php");
   exit;
}

// Ambil ID User dari session
$id_user = $_SESSION["id_user"];

// 1. Kueri untuk data PROFIL (hanya satu user yang login)
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
$user = mysqli_fetch_assoc($query_user);

$sql_items = "SELECT DISTINCT
    items.*, 
    categories.nama_kategori
FROM 
    items
LEFT JOIN 
    categories ON items.id_kategori = categories.id_kategori
LEFT JOIN 
    claims ON items.id_item = claims.id_item
WHERE 
    items.id_user = '$id_user' 
    OR claims.id_user_pemilik = '$id_user'"; 
    // Pastikan claims.id_user juga sudah benar sesuai tabel Anda

$result_items = mysqli_query($conn, $sql_items);

$items = [];
while($row = mysqli_fetch_assoc($result_items)){
    $items[] = $row;
}

$folder_foto = "../img/profil/";
if (!empty($user['foto_profil']) && file_exists($folder_foto . $user['foto_profil'])) {
    $path_foto = $folder_foto . $user['foto_profil'];
} else {
    $path_foto = $folder_foto . "default.jpg"; 
}

// Ambil ID User dari session
$id_user = $_SESSION["id_user"];

// Query menggunakan operator IN untuk memfilter dua status sekaligus
$query_count = "SELECT COUNT(*) as total 
                FROM items 
                WHERE id_user = '$id_user'";
$result_count = mysqli_query($conn, $query_count);
$data_count = mysqli_fetch_assoc($result_count);
$temuan = $data_count['total'];

// Ambil ID User dari session
$id_user = $_SESSION["id_user"];

// Query untuk menghitung berapa kali user ini melakukan klaim
$query_claims = "SELECT COUNT(*) as total_klaim 
                 FROM claims 
                 WHERE id_user_pemilik = '$id_user'";
$result_claims = mysqli_query($conn, $query_claims);
$data_claims = mysqli_fetch_assoc($result_claims);
$klaim = $data_claims['total_klaim'];
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
        <div class="container">
            <!-- About-->
            <section class="resume-section" id="about">
               <div class="row">
                <div class="row">
  <div class="col-sm-6 mb-3 mb-sm-0">
    <div class="card shadow">
      <div class="card-body">
        <div class="text-center fw-bolder mb-3 fs-3">
            <p>Barang Temuan</p>
        </div>
        <div class="card-body text-center">
                     <p class="display-4""><?= $temuan; ?></p>
                </div>
        <a href="#" class="btn" style="background-color: #fda597"><i class="bi bi-bag-heart-fill"></i></a>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card shadow">
      <div class="card-body">
        <div class="text-center fw-bolder mb-3 fs-3">
            <p>Barang Milik</p>
        </div>
        <div class="card-body text-center">
                     <p class="display-4""><?= $klaim; ?></p>
                </div>
        <a href="#" class="btn" style="background-color: #feda90"><i class="bi bi-bag-heart-fill"></i></a>
      </div>
    </div>
  </div>
</div>
 <div class="card shadow p-5 mt-5">
    <div class="text-center fw-bolder mb-3 fs-3 p-2 rounded" style="background-color: #ADD8CE; color: #181F39;">
        <p>Data Barang Temuan</p>
    </div>
               <table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Tanggal Temuan</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php $no = 1; ?>
    <?php foreach($items as $row): ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><img src="../img/barang/<?= $row['foto_barang']?>" width="50"></td>
        <td><?= $row['nama_barang']; ?></td>
        <td><?= $row['nama_kategori']; ?></td>
        <td><?= $row['tanggal_temuan']; ?></td>
        <td>
            <span class="badge bg-primary">
                <?= ucfirst($row['status']); ?>
            </span>
        </td>
    </tr>
    <?php endforeach; ?>
    </div>
    </div>
            </section>
            <hr class="m-0" />
        </div>
        
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
