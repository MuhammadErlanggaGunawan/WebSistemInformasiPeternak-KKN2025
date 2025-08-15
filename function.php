<?php
session_start();
$admin_id = $_SESSION['id_user'] ?? null;

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "stokternak");


?>