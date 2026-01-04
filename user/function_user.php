<?php
$conn = mysqli_connect("localhost", "root", "", "lostnfound");

// FUNGSI UPLOAD FOTO PROFIL

// Pastikan koneksi database $conn sudah benar di file ini

function upload() {
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // Jika tidak ada gambar yang diupload
    if( $error === 4 ) {
        return false;
    }

    $ekstensiValid = ['jpg', 'jpeg', 'png'];
    $ekstensi = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    if( !in_array($ekstensi, $ekstensiValid) ) {
        echo "<script>alert('Yang anda upload bukan gambar!');</script>";
        return false;
    }

    if( $ukuranFile > 2000000 ) {
        echo "<script>alert('Ukuran gambar terlalu besar (Maks 2MB)!');</script>";
        return false;
    }

    $namaFileBaru = uniqid() . '.' . $ekstensi;
    
    // PERBAIKAN PATH: Karena file ini di folder 'user/', kita naik satu tingkat ke '../'
    $tujuan = '../img/profil/' . $namaFileBaru;
    
    if (move_uploaded_file($tmpName, $tujuan)) {
        return $namaFileBaru;
    } else {
        return false;
    }
}

function editProfil($data) {
    global $conn;

    // Tambahkan ?? '' untuk semua variabel agar tidak error jika datanya null/kosong
    $id = $data["id_user"] ?? 0;
    $username = htmlspecialchars($data['username'] ?? '');
    $bio = htmlspecialchars($data['bio'] ?? '');
    $email = htmlspecialchars($data['email'] ?? '');
    
    // Pastikan whatsapp terdefinisi sebelum diolah oleh preg_replace
    $wa_raw = $data['whatsapp'] ?? '';
    $whatsapp = preg_replace('/[^0-9]/', '', $wa_raw);
    
    $gambarLama = htmlspecialchars($data['gambarLama'] ?? '');

    // Logika upload foto
    // Cek $_FILES secara aman
    if( !isset($_FILES['gambar']) || $_FILES['gambar']['error'] === 4 ) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
        if (!$gambar) return false; 
    }

    // Cek password
    $password = $data['password'] ?? '';

    if( !empty($password) ) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET 
                username = '$username',
                email = '$email',
                bio = '$bio',
                whatsapp = '$whatsapp',
                foto_profil = '$gambar', 
                password = '$password_hashed'
                WHERE id_user = $id";
    } else {
        $sql = "UPDATE users SET 
                username = '$username',
                email = '$email',
                bio = '$bio',
                whatsapp = '$whatsapp',
                foto_profil = '$gambar'
                WHERE id_user = $id";
    }

    mysqli_query($conn, $sql);
    return mysqli_affected_rows($conn);
}
?>