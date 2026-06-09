<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$host     = "sql308.infinityfree.com"; 
$username = "if0_42130466";            
$password = "KDdqMjZN0xA";             
$dbname   = "if0_42130466_db_kantin";   

$koneksi = mysqli_connect($host, $username, $password, $dbname);

if (!$koneksi) {
    echo json_encode(["status" => false, "message" => "Koneksi Database Gagal: " . mysqli_connect_error()]);
    exit();
}

$action = $_GET['action'] ?? '';

if ($action === 'read') {
    $query = "SELECT * FROM menu ORDER BY id_menu DESC";
    $sql = mysqli_query($koneksi, $query);
    
    $result = [];
    while ($row = mysqli_fetch_assoc($sql)) {
        $result[] = $row;
    }
    echo json_encode($result);
} else {
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'POST') {
        $nama_menu = $_POST['nama_menu'] ?? '';
        $kategori  = $_POST['kategori'] ?? '';
        $harga     = $_POST['harga'] ?? '';
        $stok      = $_POST['stok'] ?? '';

        if (empty($nama_menu) || empty($harga) || empty($stok)) {
            echo json_encode(["status" => false, "message" => "Data menu tidak lengkap!"]);
            exit();
        }

        $query_insert = "INSERT INTO menu (nama_menu, kategori, harga, stok) VALUES ('$nama_menu', '$kategori', '$harga', '$stok')";
        if (mysqli_query($koneksi, $query_insert)) {
            echo json_encode(["status" => true, "message" => "Menu makanan berhasil ditambahkan!"]);
        } else {
            echo json_encode(["status" => false, "message" => "Gagal menambahkan menu: " . mysqli_error($koneksi)]);
        }
    }
}
?>