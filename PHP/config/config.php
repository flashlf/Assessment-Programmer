<?php

namespace Todo;

class Config {
    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_NAME = 'assesment_php';
}
    //  DB PARAM
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'assesment_php');
    
    // APP ROOT
    define('APPROOT', dirname(dirname(__FILE__)));

    // URL ROOT
    define('URLROOT', 'https://'.$_SERVER['SERVER_NAME']);

    // SITE NAME
    define('SITENAME', 'Todo Apps');

    // APP VERSION
    define('APPVERSION', '0.0.1b');