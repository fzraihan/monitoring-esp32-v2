<?php
header('Content-Type: application/json');

$supabaseUrl = "https://fykjfyieanedfjrctrdi.supabase.co/rest/v1/data_sensor";
$supabaseKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ5a2pmeWllYW5lZGZqcmN0cmRpIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIzMDYxMDksImV4cCI6MjA4Nzg4MjEwOX0.-_HYcQXNrj065l7GO-uRkTviTPv0caYaqNZsfl1aDfQ";

// Ambil limit dari parameter GET, default 50
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

$opts = [
    "http" => [
        "method" => "GET",
        "header" => "apikey: $supabaseKey\r\nAuthorization: Bearer $supabaseKey\r\n"
    ]
];

$context = stream_context_create($opts);
$result = file_get_contents(
    $supabaseUrl."?select=*&order=created_at.desc&limit=".$limit,
    false,
    $context
);

echo $result;
?>
