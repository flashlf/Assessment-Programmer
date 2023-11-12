<?php

spl_autoload_register( function($className) {
    require_once $className . '.php';
});

$restapi = new Model\Restapi(); // Full Qualified Namespace
$validate = new Model\Utility();

$restapi->validateMethod($restapi->method, 'POST');

$requiredKey = [
    "title", "user", "description"
];


switch ($restapi->data['code']) {
    case '0' :
        $validate->requiredInput($requiredKey, $restapi->data['data']);
        if ($validate->passed === false) {
            $restapi->code = 400;
            $restapi->info = $validate->message;
        } else {
            $restapi->data = null;
            $restapi->getResponse();
        }
        break;
    case '1' :

        break;
    default :
        $restapi->code = 400;
        $restapi->info = "Code value out of range";
        $restapi->getResponse();
        break;
}

