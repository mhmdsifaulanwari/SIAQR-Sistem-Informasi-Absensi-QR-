<?php
/** @var mysqli $conn */
$conn = mysqli_connect("localhost", "root", "", "db_kampus", );

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}