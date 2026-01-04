<?php
$conn = mysqli_connect("localhost", "root", "", "lostnfound");


// FUNGSI MENAMPILKAN DATA
function query($query){
    global $conn;

    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }
    return $rows;
}

function uploadGambar() { // Parameter $id_item bisa dihapus jika ingin random total
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    if( $error === 4 ) {
        echo "<script>alert('pilih gambar terlebih dahulu!');</script>";
        return false;
    }

    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
        echo "<script>alert('yang anda upload bukan gambar!');</script>";
        return false;
    }

    if( $ukuranFile > 5000000 ) {
        echo "<script>alert('ukuran gambar terlalu besar!');</script>";
        return false;
    }

    // --- BAGIAN PERUBAHAN NAMA UNIK ---
    // uniqid() akan menghasilkan string unik berdasarkan waktu mikrodetik
    $namaFileBaru = uniqid(); 
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'img/barang/' . $namaFileBaru);

    return $namaFileBaru;
}

// TAMBAH BARANG
function tambahBarang($data, $file) {
    global $conn;

    $nama = mysqli_real_escape_string($conn, $data['nama_barang']);
    $id_kategori = $data['id_kategori'];
    $deskripsi = mysqli_real_escape_string($conn, $data['deskripsi']);
    $lokasi = mysqli_real_escape_string($conn, $data['lokasi_temuan']);
    $tanggal = $data['tanggal_temuan'];
    $id_user = $_SESSION['id_user']; // Mengambil ID orang yang login
    // Perbaikan untuk 'cp' (Contact Person)
    $cp_raw = $data["cp"] ?? '';
    $cp = mysqli_real_escape_string($conn, $cp_raw);
     $gambar = uploadGambar(); 
    if( !$gambar ) {
        return false;
    }

    // --- QUERY INSERT ---
    $query = "INSERT INTO items (id_user, id_kategori, nama_barang, deskripsi, foto_barang, lokasi_temuan, tanggal_temuan, cp, status) 
              VALUES ('$id_user', '$id_kategori', '$nama', '$deskripsi', '$gambar', '$lokasi', '$tanggal', '$cp', 'pending')";

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

// UBAH STATUS BARANG
function statusTersedia($id) {
    global $conn;
    
    $id = (int)$id; // Pastikan ID adalah angka

    $query = "UPDATE items SET status = 'tersedia' WHERE id_item = $id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function statusSelesai($id) {
    global $conn;
    
    $id = (int)$id; // Pastikan ID adalah angka

    $query = "UPDATE items SET status = 'selesai' WHERE id_item = $id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// TOLAK DATA
function tolakItem($id) {
    global $conn;
    $id = (int)$id;
    $query = "DELETE FROM items WHERE id_item = $id";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}


// PENCARIAN
function cariBarang($keyword) {
    global $conn;

    // Bersihkan keyword untuk keamanan
    $keyword = mysqli_real_escape_string($conn, $keyword);

    $query = "SELECT items.*, categories.nama_kategori 
              FROM items 
              LEFT JOIN categories ON items.id_kategori = categories.id_kategori
              WHERE items.nama_barang LIKE '%$keyword%' OR 
                    items.deskripsi LIKE '%$keyword%' OR 
                    items.lokasi_temuan LIKE '%$keyword%' OR
                    categories.nama_kategori LIKE '%$keyword%'";

    $result = mysqli_query($conn, $query);
    
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

//PENGAMBILAN BARANG
function tambahKlaim($data) {
    global $conn;

    // Gunakan null coalescing ?? agar tidak muncul "Undefined array key"
    $id_item = $data["id_item"] ?? null; 
    $id_user = $_SESSION["id_user"] ?? null;

    // Validasi: Jika id_item kosong, hentikan proses
    if (!$id_item || !$id_user) {
        echo "<script>alert('Terjadi kesalahan: ID Barang atau User tidak ditemukan.');</script>";
        return false;
    }

    $tanggal = date("Y-m-d H:i:s");
    $status = "pending";

    // Panggil fungsi upload yang sudah Anda buat
    $bukti = uploadBukti(); 
    if (!$bukti) return false;

    // Jalankan Query
    $query = "INSERT INTO claims 
                (id_item, id_user_pemilik, bukti_kepemilikan, tanggal_klaim, status_klaim)
              VALUES 
                ('$id_item', '$id_user', '$bukti', '$tanggal', '$status')";

    if (!mysqli_query($conn, $query)) {
        die("Kesalahan Database: " . mysqli_error($conn));
    }

    return mysqli_affected_rows($conn);
}

function uploadBukti() {
    $namaFile = $_FILES['bukti_kepemilikan']['name'];
    $ukuranFile = $_FILES['bukti_kepemilikan']['size'];
    $error = $_FILES['bukti_kepemilikan']['error'];
    $tmpName = $_FILES['bukti_kepemilikan']['tmp_name'];

    if ($error === 4) {
        echo "<script>alert('Pilih gambar bukti terlebih dahulu!');</script>";
        return false;
    }

    $ekstensiValid = ['jpg', 'jpeg', 'png'];
    $ekstensi = explode('.', $namaFile);
    $ekstensi = strtolower(end($ekstensi));

    if (!in_array($ekstensi, $ekstensiValid)) {
        echo "<script>alert('Format file harus JPG, JPEG, atau PNG!');</script>";
        return false;
    }

    if ($ukuranFile > 2000000) {
        echo "<script>alert('Ukuran file terlalu besar! (Maks 2MB)');</script>";
        return false;
    }

    $namaFileBaru = uniqid() . '.' . $ekstensi;
    move_uploaded_file($tmpName, 'img/bukti/' . $namaFileBaru);

    return $namaFileBaru;
}

// // FUNGSI UBAH KATEGORI
// function ubah_kategori($data){
//     global $conn;

//     $id = $data['id_kategori'];
//     $nama_kategori = $data['nama_kategori'];

//     $query = "UPDATE kategori SET
//                 nama_kategori = '$nama_kategori'
//               WHERE id_kategori = '$id'
//              ";


//      $result = mysqli_query($conn, $query);
     
//      return mysqli_affected_rows($conn);
// }

function register($data){
    global $conn;

    $username = strtolower($data['username']);
    $email = $data['email'];
    $password = mysqli_real_escape_string($conn, $data['password']);
    $konfirmasi_password = mysqli_real_escape_string($conn, $data['confirm_password']);
    $whatsapp_raw = isset($data['whatsapp']) ? trim($data['whatsapp']) : '';

    // Validasi WhatsApp
    $whatsapp = preg_replace('/[^0-9]/', '', $whatsapp_raw); // Hapus semua selain angka
    if(empty($whatsapp)){
        return "Nomor WhatsApp wajib diisi!";
    }
    if(strlen($whatsapp) < 10 || strlen($whatsapp) > 15){
        return "Nomor WhatsApp tidak valid! Gunakan format 628xxxxxxxxxx";
    }

    // Query cek username/email
    $query = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username' OR email = '$email'");
    $result = mysqli_fetch_assoc($query);

    if($result != NULL){
        return "Username atau email sudah terdaftar, gunakan yang lain";
    }

    // Validasi password
    if($password != $konfirmasi_password){
        return "Konfirmasi password tidak sesuai!";
    }
    if(strlen($password) < 8){
        return "Password minimal 8 karakter!";
    }

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user baru + whatsapp
    $sql = "INSERT INTO users (username, email, password, whatsapp) VALUES('$username', '$email', '$password', '$whatsapp')";
    if(mysqli_query($conn, $sql)){
        // Buat link WA.me
        $wa_link = 'https://wa.me/' . $whatsapp;
        // Optional: simpan link ke session atau return untuk halaman selanjutnya
        $_SESSION['user_wa_link'] = $wa_link;
        return true;
    } else {
        return "Terjadi kesalahan saat registrasi: " . mysqli_error($conn);
    }
}



// fungsi untuk login
function login($data) {
    global $conn;

    $username = $data['username'];
    $password = $data['password'];

    // 1. Gunakan Prepared Statement untuk mencegah SQL Injection
    $stmt = mysqli_prepare($conn, "SELECT id_user, username, password, role FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // 2. Cek apakah username ditemukan
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

       
        if (password_verify($password, $row['password'])) {
            
            
            $_SESSION["login"] = true;
            $_SESSION["id_user"] = $row['id_user']; 
            $_SESSION["username"] = $row['username'];
            $_SESSION["email"] = $row['email'];
            $_SESSION["whatasapp"] = $row['whatsapp'];
            $_SESSION["role"] = strtolower($row["role"]);

            return true;
        } else {
            return "Password salah!";
        }
    } else {
        return "Username tidak terdaftar!";
    }
}


?>