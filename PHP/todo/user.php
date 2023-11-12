<?php

use Model\User;

require_once "../config/config.php";

spl_autoload_register(function($className){
    require_once  $className . '.php';
});

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['code'])) {
        $user = new User();
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
                    exit;
                } else {
                    return "User Tidak Terdaftar";
                }
                break;
            case "register" :
                $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_SPECIAL_CHARS);

                if (!$token || $token !== $_SESSION['token']) {

                    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed'); exit;
                }
                $user->register($_POST);
                header("Location: ".URLROOT);
                exit;
                break;
            
            case "logout" :
                setcookie("user_info", "", time() - 3600, "/");
                session_destroy();
                header("Location: ".URLROOT);
                exit;
                break;
            default:
                return "Undefined Method";
                break;
        }
    }
}


if (isset($_COOKIE["user_info"])) {
    $user_info = $_COOKIE["user_info"];
    
    // Membagi string menjadi informasi terpisah
    list($user_id, $username, $name) = explode("|", $user_info);

    header("Location: ".URLROOT."/todo");
} else {
    
    // Set Default to Forbidden Access
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
}