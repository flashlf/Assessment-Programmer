<?php

use Model\User;

require_once "../config/config.php";

spl_autoload_register(function($className){
    require_once  $className . '.php';
});

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['code'])) {
        $user = new User();
        session_start();
        switch ($_POST['code']) {
            case "login" :
                $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_SPECIAL_CHARS);
                if (!$token || $token !== $_SESSION['token']) {

                    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed'); exit;
                }

                $result = $user->login($_POST);
                
                if (!empty($result)) {
                    // set Cookie
                    $user_cookie = "$result->id|$result->code|$result->name";
        
                    setcookie("user_info", $user_cookie, time() + 3600, "/");
        
                    header("Location: ".URLROOT."/todo");
                } else {
                    return "User Tidak Terdaftar";
                }
                break;
            case "register" :
                $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_SPECIAL_CHARS);

                if (!$token || $token !== $_SESSION['token']) {

                    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed'); exit;
                }

                $user->name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
                $user->code = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
                $user->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
                $user->pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

                $user->save();
                header("Location: ".URLROOT);
                break;
            
            case "logout" :
                setcookie("user_info", "", time() - 3600, "/");
                session_destroy();
                header("Location: ".URLROOT);
                break;
            default:
                return "Undefined Method";
                break;
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