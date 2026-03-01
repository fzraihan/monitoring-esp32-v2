<?php

$supabaseUrl = "https://fykjfyieanedfjrctrdi.supabase.co/rest/v1/data_sensor?select=*&order=id.desc&limit=20";
$supabaseKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ5a2pmeWllYW5lZGZqcmN0cmRpIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIzMDYxMDksImV4cCI6MjA4Nzg4MjEwOX0.-_HYcQXNrj065l7GO-uRkTviTPv0caYaqNZsfl1aDfQ";

$options = [
    "http" => [
        "method"  => "GET",
        "header"  => "apikey: $supabaseKey\r\n" .
                     "Authorization: Bearer $supabaseKey\r\n"
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($supabaseUrl, false, $context);

echo $response;