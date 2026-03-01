<?php
$host = "sql213.infinityfree.com";
$user = "if0_41274403";
$pass = "kn9sls17tU423F";
$db   = "if0_41274403_monitoring_agroklimat";

$conn = mysqli_connect($host,$user,$pass,$db);

if(!$conn){
    die("Koneksi gagal: ".mysqli_connect_error());
}
?>