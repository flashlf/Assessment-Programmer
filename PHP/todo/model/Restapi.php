<?php
namespace Model;

class Restapi extends Api
{
    public $method;

    // Ambil request yg dikirim
    public function __construct()
    {
        try {
            parent::__construct();

            if (isset($_SERVER['REQUEST_METHOD'])) {
                $this->method = $_SERVER['REQUEST_METHOD'];

                switch ($_SERVER['REQUEST_METHOD']) {
                    case "GET" :
                    case "POST":
                        parent::ioHandling();
                        break;
                    default :
                        $this->code = 403;
                        $this->info = "Request Method Undefined";
                        break;
                }
            } else {
                $this->code = 200;
                $this->info = "Its Works";
                $this->data = null;
            }
        } catch (\Exception $ex) {
            $this->code = 500;
            $this->info = $ex->getMessage();
            $this->getResponse();
        }
        
    }

}
