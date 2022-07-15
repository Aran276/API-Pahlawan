<?php

include '../connection/connection.php';

// header hasil berbentuk json
header("Content-Type:application/json");

// tangkap key
$header = apache_request_headers();
$key    = $header['key'];

// tangkap metode
$method = $_SERVER['REQUEST_METHOD'];

// variable hasil
$result = array();

// cek user
$sql    = "SELECT * FROM user WHERE key_token='$key'";
$user   = $conn->query($sql);

if ($user->num_rows > 0) {

    if ($method == 'GET') {

        // pengecekan parameter
        if (isset($_GET['nama'])) {

            // menangkap parameter
            $nama = $_GET['nama'];

            // jika metode sesuai
            $result['status'] = [
                "code" => 200,
                "description" => 'Request Valid'
            ];

            //buat query
            $sql = "SELECT * FROM data_pahlawan WHERE nama LIKE '%$nama%'";
            // eksekusi query
            $hasil_query = $conn->query($sql);

            if ($hasil_query->num_rows > 0) {
                // masukkan ke array result
                $result['results'] = $hasil_query->fetch_all(MYSQLI_ASSOC);
            } else {
                $result['status'] = [
                    "code" => 400,
                    "description" => 'None'
                ];
            }
        } else {
            $result['status'] = [
                "code" => 400,
                "description" => 'Request Invalid'
            ];
        }
    } else {
        // jika metode tidak sesuai
        $result['status'] = [
            "code" => 400,
            "description" => 'Request Not Valid'
        ];
    }
} else {
    $result['status'] = [
        "code" => 400,
        "description" => 'API Key/Token Not Valid'
    ];
}


// tampilkan data dalam format json
echo json_encode($result);