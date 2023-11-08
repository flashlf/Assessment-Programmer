<?php

use Model\User;

require_once "../config/config.php";

spl_autoload_register(function($className){
    require_once  $className . '.php';
});

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['code'])) {

        $user = new User();
        $result = $user->login($_POST);
        
        if (!empty($result)) {
            // set Cookie
            $user_cookie = "$result->id|$result->code|$result->name";

            setcookie("user_info", $user_cookie, time() + 3600, "/");

            header("Location: ".URLROOT."/todo");
        }
    } else {
        echo "Restricted Access!."; exit;
    }
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi username dan password (contoh: "admin" dan "password")
    if ($username == 'admin' && $password == 'password') {
        // Autentikasi berhasil, arahkan ke halaman beranda
        header('Location: home.php');
        exit();
    } else {
        echo "<p>Autentikasi gagal. Coba lagi.</p>";
    }
}