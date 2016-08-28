<?php

$data = http_build_query($_POST);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1/auth/myresource.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close ($ch);

header('Content-Type: application/json');
header('Access-Control-Allow-Headers: content-type');
header("Access-Control-Allow-Origin: http://127.0.0.1:8000");
header("Access-Control-Allow-Methods: POST");
echo $output;