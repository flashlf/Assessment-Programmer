<?php

namespace Model;

abstract class Api
{
    public $info;
    public $code;
    public $data;

    public function __construct($info = "OK", $code = 200, $data = null)
    {
        header('Content-Type: application/json');
        $this->info = $info;
        $this->code = $code;
        $this->data = $data;
    }

    public function ioHandling()
    {
        if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
            
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
            $this->code = 400;
            $this->info = "Invalid Content-Type. Only application/json accepted";
            echo $this->toJson();
            exit;

        } else {

            $this->data = json_decode(file_get_contents('php://input'), true);

            if (is_null($this->data)) {
                $this->code = 400;
                $this->info = "Json format not valid";
            }

            if (!isset($this->data['code'])) {
                $this->code = 400;
                $this->info = "Mandatory attribute code not sent";
            }

            if (!isset($this->data['data'])) {
                $this->code = 400;
                $this->info = "Mandatory attribute data not sent";
            } else {
                if (!is_array($this->data['data']) || empty($this->data['data'])) {
                    $this->code = 400;
                    $this->info = "Mandatory attribute data are empty or invalid format (should be array)";
                }
            }
        }
    }
    
    public function validateMethod($paramMethod, $method)
    {
        if ($paramMethod != $method) {
            $this->code = 400;
            $this->info = "Invalid Method used for this request";

            $this->getResponse();
        }
    }

    final public function getResponse()
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $this->code);
        echo $this->toJson();
        exit;
    }

    final public function toJson()
    {
        return json_encode(
            [
                "code" => $this->code,
                "info" => $this->info,
                "data" => $this->data
            ]
        );
    }
}
