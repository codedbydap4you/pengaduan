<?php

$koneksi = mysqli_connect("localhost", "root", "", "pengaduan");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>