<?php
$host     = "sql308.infinityfree.com"; 
$username = "if0_42130466";            
$password = "KDdqMjZN0xA";             
$dbname   = "if0_42130466_db_kantin";   

$koneksi = mysqli_connect($host, $username, $password, $dbname);

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>