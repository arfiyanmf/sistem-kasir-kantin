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

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $query = "SELECT * FROM pelanggan ORDER BY id_pelanggan DESC";
    $sql = mysqli_query($koneksi, $query);
    
    $result = [];
    while ($row = mysqli_fetch_assoc($sql)) {
        $result[] = $row;
    }
    echo json_encode($result);
}

if ($method === 'POST') {
    $nama_pelanggan = $_POST['nama_pelanggan'] ?? '';
    $nomor_hp       = $_POST['nomor_hp'] ?? '';

    if (empty($nama_pelanggan)) {
        echo json_encode(["status" => false, "message" => "Nama pelanggan tidak boleh kosong!"]);
        exit();
    }

    $query_insert = "INSERT INTO pelanggan (nama_pelanggan, nomor_hp) VALUES ('$nama_pelanggan', '$nomor_hp')";
    if (mysqli_query($koneksi, $query_insert)) {
        echo json_encode(["status" => true, "message" => "Data pelanggan berhasil ditambahkan!"]);
    } else {
        echo json_encode(["status" => false, "message" => "Gagal menambahkan data: " . mysqli_error($koneksi)]);
    }
}
?>