<?php
// Menggunakan library curl untuk mengirimkan permintaan POST
require 'vendor/autoload.php';
use Curl\Curl;

// Membuat objek curl baru
$curl = new Curl();
// Menentukan URL API
$url = "localhost:5000/api/predict";

// Memeriksa ekstensi file yang diinput
$ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION); // Mendapatkan ekstensi file
$allowed_ext = array("gif", "jpeg", "jpg", "png"); // Membuat daftar ekstensi yang diizinkan

if (in_array($ext, $allowed_ext)) { // Membandingkan ekstensi file dengan daftar ekstensi yang diizinkan
    // Membuat array asosiatif untuk menyimpan parameter form
    $data = array(
    "image" => new CURLFile($_FILES["image"]["tmp_name"]) // Menggunakan file gambar yang dipilih dari form
    );

    // Mengirimkan permintaan POST ke URL dan menyimpan respons dalam variabel $response
    $response = $curl->post($url, $data);
    // Mengubah respons JSON menjadi array asosiatif
    $result = get_object_vars($response);

    // Redirect and session data result
    session_start();
    $_SESSION['result'] = $result;
    header("Location: index.php");
} else {
    // File bukan gambar, kembali ke halaman form dengan method get input=fail
    header("Location: index.php?input=fail");
}
?>