<?php
$password_plain = "admin456";   // ganti dengan password admin yang sebenarnya

$hash = password_hash($password_plain, PASSWORD_DEFAULT);

echo $hash;
