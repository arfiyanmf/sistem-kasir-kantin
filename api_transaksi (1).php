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
    $query = "SELECT * FROM transaksi ORDER BY id_transaksi DESC";
    $sql = mysqli_query($koneksi, $query);
    
    $result = [];
    while ($row = mysqli_fetch_assoc($sql)) {
        $result[] = $row;
    }
    
    echo json_encode($result);
}

if ($method === 'POST') {
    $id_pelanggan      = $_POST['id_pelanggan'] ?? '';
    $id_menu           = $_POST['id_menu'] ?? '';
    $jumlah_beli       = $_POST['jumlah_beli'] ?? '';
    $total_harga       = $_POST['total_harga'] ?? '';
    $metode_pembayaran = $_POST['metode_pembayaran'] ?? '';

    if (empty($id_pelanggan) || empty($id_menu) || empty($jumlah_beli) || empty($total_harga)) {
        echo json_encode(["status" => false, "message" => "Data input tidak lengkap!"]);
        exit();
    }

    $cek_stok = mysqli_query($koneksi, "SELECT stok FROM menu WHERE id_menu = '$id_menu'");
    $data_menu = mysqli_fetch_assoc($cek_stok);

    if (!$data_menu) {
        echo json_encode(["status" => false, "message" => "Menu makanan tidak ditemukan!"]);
        exit();
    }

    $stok_sekarang = $data_menu['stok'];

    if ($stok_sekarang < $jumlah_beli) {
        echo json_encode(["status" => false, "message" => "Transaksi gagal! Stok tidak mencukupi. Sisa stok: $stok_sekarang"]);
        exit();
    }

    $query_insert = "INSERT INTO transaksi (id_pelanggan, id_menu, jumlah_beli, total_harga, metode_pembayaran) 
                     VALUES ('$id_pelanggan', '$id_menu', '$jumlah_beli', '$total_harga', '$metode_pembayaran')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        $stok_baru = $stok_sekarang - $jumlah_beli;
        mysqli_query($koneksi, "UPDATE menu SET stok = '$stok_baru' WHERE id_menu = '$id_menu'");

        echo json_encode(["status" => true, "message" => "Transaksi berhasil dicatat dan stok menu diperbarui!"]);
    } else {
        echo json_encode(["status" => false, "message" => "Gagal menyimpan transaksi: " . mysqli_error($koneksi)]);
    }
}
?>