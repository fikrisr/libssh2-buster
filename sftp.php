<?php
$host = 'your host';
$port = 22;
$username = 'username';

// Buat koneksi SSH
$connection = ssh2_connect($host, $port);
if (!$connection) {
    die("Connection failed\n");
}

// Autentikasi pakai file key
if (!ssh2_auth_pubkey_file(
    $connection,
    $username,
    '/app/id_rsa.pub',
    '/app/id_rsa',
    ''
)) {
    die("Authentication failed\n");
}

echo "SUCCESS: Connected and Authenticated\n";
