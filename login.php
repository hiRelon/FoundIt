<?php
session_start();

require("function.php");

$error = "";

if (isset($_SESSION["login"])) {

    if (strtolower($_SESSION["role"]) === 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: index.php");
    }

    exit;
}

if (isset($_POST["login"])) {

    $hasil = login($_POST);

    if ($hasil === true) {
        

        if (strtolower($_SESSION["role"]) === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit;

    } else {
        $error = $hasil;   
    }
}



?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>


    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        body {
            background: #f6f7fb;
        }
        .login-card {
            margin-top: 25%;
            padding: 30px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 18px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">


            <div class="login-card">


                <h3 class="text-center mb-4">Login to Foundit</h3>


                <?php if($error) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>


                <form action="" method="POST">


                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username..." autocomplete="off" required>
                    </div>


                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password..." autocomplete="off" required>
                    </div>


                    <!-- Tombol Register -->
                    <button type="submit" name="login" class="btn btn-dark w-100">Login</button>
                    <p class="mt-2">Belum punya akun? <a href="register.php">Register</a></p>
                </form>


            </div>


        </div>
    </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>


