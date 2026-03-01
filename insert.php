<?php
include "koneksi.php";

if(!isset($_GET['suhu'])){
    die("Data tidak lengkap");
if($_GET['key'] != "AGRO123")
    die("Unauthorized");
}

$suhu = $_GET['suhu'];
$kelembaban = $_GET['kelembaban'];
$soil = $_GET['soil'];
$angin = $_GET['angin'];

$query = "INSERT INTO data_sensor 
(suhu, kelembaban, soil_moisture, kecepatan_angin, created_at)
VALUES 
('$suhu','$kelembaban','$soil','$angin', NOW())";

if(mysqli_query($conn,$query)){
    echo "Sukses";
}else{
    echo "Gagal: ".mysqli_error($conn);
}
?>