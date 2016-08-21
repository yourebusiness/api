<?php

$data = $_POST;
$PHP_AUTH_USER = 'yourebusiness';
$PHP_AUTH_PW = '$2y$10$ak2EskLYltM2oiHfGmqje.VtfuBEQpZEDan5NBwO3xqbuSh4V7lZu';	//client_secret

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1/auth/mytoken.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$PHP_AUTH_USER:$PHP_AUTH_PW");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$output = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close ($ch);

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE");
echo $output;