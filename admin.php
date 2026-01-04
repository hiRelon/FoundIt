<?php
session_start();

// Cek role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
        exit;
}
    require("function.php");

//Tangkap keyword pencarian
$keyword = (isset($_GET['keyword'])) ? $_GET['keyword'] : '';

//Hitung jumlah data 
if ($keyword !== '') {
    $queryHitung = "SELECT COUNT(*) AS total FROM items 
                    LEFT JOIN categories ON items.id_kategori = categories.id_kategori
                    WHERE items.nama_barang LIKE '%$keyword%' OR categories.nama_kategori LIKE '%$keyword%'";
} else {
    $queryHitung = "SELECT COUNT(*) AS total FROM items";
}

$resultHitung = mysqli_query($conn, $queryHitung);
$rowHitung = mysqli_fetch_assoc($resultHitung);
$jumlahData = $rowHitung['total'];

//pengaturan Pagination
$jumlahDataPerHalaman = 4;
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = (isset($_GET["halaman"])) ? (int)$_GET["halaman"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

//Ambil data
$sql = "SELECT items.*, categories.nama_kategori 
        FROM items 
        LEFT JOIN categories ON items.id_kategori = categories.id_kategori";

if ($keyword !== '') {
    $sql .= " WHERE items.nama_barang LIKE '%$keyword%' OR categories.nama_kategori LIKE '%$keyword%'";
}

$sql .= " LIMIT $awalData, $jumlahDataPerHalaman";

$result = mysqli_query($conn, $sql);
$items = [];
while($row = mysqli_fetch_assoc($result)){
    $items[] = $row;
}
// Status
if (isset($_POST['tombol_tersedia'])) {
    $id_item = $_POST['id_item'];
    
    $hasil = statusTersedia($id_item); 

    if ($hasil > 0) {
        echo "<script>alert('Barang disetujui dan status tersedia!'); window.location='admin.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal atau status sudah tersedia.');</script>";
    }
}

// Foto PP
$id_user = $_SESSION["id_user"];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
$user = mysqli_fetch_assoc($query_user);

// Tentukan path foto profil
$folder_profil = "img/profil/";
if (!empty($user['foto_profil']) && file_exists($folder_profil . $user['foto_profil'])) {
    $path_foto = $folder_profil . $user['foto_profil'];
} else {
    $path_foto = $folder_profil . "default.jpg"; 
}

// TOLAK DATA
if (isset($_POST['tombol_hapus'])) {
    $id_item = $_POST['id_item'];
    $hasil = tolakItem($id_item);

    if ($hasil > 0) {
        echo "<script>alert('Data laporan ditolak!'); window.location='admin.php';</script>";
        exit;
    }
}



// Menghitung jumlah data
$query_count = "SELECT COUNT(*) as laporan FROM items WHERE status = 'pending'";
$result_count = mysqli_query($conn, $query_count);
$data_count = mysqli_fetch_assoc($result_count);
$laporan = $data_count['laporan'];

$query_count = "SELECT COUNT(*) as hilang FROM items WHERE status = 'tersedia'";
$result_count = mysqli_query($conn, $query_count);
$data_count = mysqli_fetch_assoc($result_count);
$hilang = $data_count['hilang'];

$query_count = "SELECT COUNT(*) as ditemukan FROM items WHERE status = 'selesai'";
$result_count = mysqli_query($conn, $query_count);
$data_count = mysqli_fetch_assoc($result_count);
$ditemukan = $data_count['ditemukan'];

?> 



<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admin | Dashboard</title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <!-- <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" /> -->
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant"
    />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="dist/css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <!-- <li class="nav-item d-none d-md-block">
              <a class="navbar-brand fw-bolder #181F39" href="#!"><i class="bi bi-box2-heart-fill"></i>  FoundIt</a>
            </li> -->


            <!-- <li class="nav-item d-none d-md-block">
              <a href="#" class="nav-link">Contact</a>
            </li> -->
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
           

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
                          <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end text-center">
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
                <!--end::User Image-->
                
                <!--begin::Menu Footer-->
                        <li class="user-footer p-2">
                        <div class="row g-2"> <div class="col-6">
                            <a href="user/profil.php" class="btn btn-light border btn-block w-100">Profile</a>
                          </div>
                          <div class="col-6">
                            <a href="logout.php" class="btn btn-light border btn-block w-100">Log out</a>
                          </div>
                        </div>
                      </li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="index.php" class="brand-link">
            <!--begin::Brand Image-->
            <!-- <img
              src="dist/assets/img/bookiologo.jpeg"
              alt="Logo"
              class="brand-image shadow rounded-circle"
            /> -->
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-bolder"><i class="bi bi-box2-heart-fill"></i>  FoundIt</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item menu-open">
                <a href="#" class="nav-link active">
                  <i class="bi bi-collection-fill"></i>
                  <p>
                    Data Master
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="index.html" class="nav-link active">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Data Barang</p>
                    </a>
                  </li>
                  <!-- <li class="nav-item">
                    <a href="kategori.php" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Data </p>
                    </a>
                  </li> -->
                </ul>
              </li>

              <li class="nav-item">
                <a href="logout.php" class="nav-link">
                  <i class="bi bi-door-open-fill"></i>
                  <p>Log Out</p>
                </a>
              </li>

            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->

      <!-- ====================================================================================== -->
      <!--  MAIN SECTION -->
      <!-- ====================================================================================== -->

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6">
                <h3 class="fw-bold mt-5"><i class="bi bi-box-seam-fill"></i> Data Barang </h3>
                <!-- <a href="laporan.php">
                  <button class="btn-sm btn btn-primary">Tambah Data</button>
                </a> -->
              </div>
              <div class="col-sm-6 d-flex flex-column align-items-end">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Data Barang</li>
                  <!-- <li class="breadcrumb-item"><a href="kategori.php">Data Kategori</a></li> -->
                </ol>
                <form class="mt-2" method="get" action="admin.php">
                  <div class="input-group">
                      <input 
                          type="text" 
                          class="form-control" 
                          name="keyword" 
                          placeholder="Cari barang..." 
                          autocomplete="off"
                          value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" 
                      >
                      <button class="btn" style="background-color: #181F39; color: aliceblue;" type="submit">
                          <i class="bi bi-search"></i> Cari
                      </button>
                      
                      <?php if(isset($_GET['keyword'])): ?>
                          <a href="admin.php" class="btn btn-secondary">Reset</a>
                      <?php endif; ?>
                  </div>
              </form>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->

        <!-- Card -->
         <section>
          <div class="container px-4 px-lg-6 mt-2 mb-4">
    <div class="row justify-content-center g-4">

        <div class="col-md-4 col-sm-6">
            <div class="card h-100">
                <div class="card-header fw-bolder text-center" style="background-color: #FDA597;">Laporan Masuk </div>
                <div class="card-body text-center">
                    <p class="display-4""><?= $laporan; ?></0>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card h-100">
                <div class="card-header fw-bolder text-center" style="background-color: #FEDA90;">Barang Hilang </div>
                <div class="card-body text-center">
                     <p class="display-4""><?= $hilang; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card h-100">
                <div class="card-header fw-bolder text-center" style="background-color: #ADD8CE;">Barang Ditemukan</div>
                <div class="card-body text-center">
                     <p class="display-4""><?= $ditemukan; ?></p>
                </div>
            </div>
        </div>

    </div>
</div>
         </section>

        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <!--begin::Col-->
              <div class="col">
              <!-- =============== ISI TABEL ADA DI SINI =============== -->
                <table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Deskripsi</th>
            <th>Lokasi Temuan</th>
            <th>Tanggal Temuan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php $no = $awalData + 1; ?>
    <?php foreach($items as $row): ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><img src="img/barang/<?= $row['foto_barang']?>" width="50"></td>
        <td><?= $row['nama_barang']; ?></td>
        <td><?= $row['nama_kategori']; ?></td>
        <td><?= $row['deskripsi']; ?></td>
        <td><?= $row['lokasi_temuan']; ?></td>
        <td><?= $row['tanggal_temuan']; ?></td>
        <td>
            <span class="badge" style="background-color: #181f39;">
                <?= ucfirst($row['status']); ?>
            </span>
        </td>
        <td>
            <form method="POST" action="">
                <input type="hidden" name="id_item" value="<?= $row['id_item']; ?>">
                
                <button type="submit" name="tombol_tersedia" class="btn btn-sm btn-success">
                    Setuju
                </button>

                <button type="submit" name="tombol_hapus" class="btn btn-sm btn-danger">
                    Tolak
                </button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table> 

<nav aria-label="Page navigation example">
                    <ul class="pagination">
                            <!-- Tombol Previous --> 
                        <?php if ($halamanAktif > 1) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?halaman=<?= $halamanAktif - 1; ?>">&laquo;</a>
                            </li>
                        <?php endif; ?>


                        <!-- Daftar halaman -->
                        <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                            <?php if ($i == $halamanAktif) : ?>
                                <li class="page-item active">
                                    <a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a>
                                </li>
                            <?php else : ?>
                                <li class="page-item">
                                    <a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>


                        <!-- Tombol Next -->
                        <?php if ($halamanAktif < $jumlahHalaman) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?halaman=<?= $halamanAktif + 1; ?>">&raquo;</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav> 

              </div>
              <!--end::Col-->
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Temukan yang hilang, kembalikan yang ditemukan</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2026&nbsp;
        </strong>
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
   
  </body>
  <!--end::Body-->
</html>
