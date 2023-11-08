<?php
namespace Model;

use stdClass;

class User
{
    public $id;
    public $email;
    public $code;
    public $name;
    public $level;

    public function __construct($param = null)
    {
        $this->id = $param->user_id ?? null;
        $this->email = $param->email ?? null;
        $this->code = $param->code ?? null;
        $this->name = $param->name ?? null;
        $this->level = $param->level ?? null;
    }

    public function push(stdClass $param) : void
    {
        $this->id = $param->user_id ?? null;
        $this->email = $param->email ?? null;
        $this->code = $param->code ?? null;
        $this->name = $param->name ?? null;
        $this->level = $param->level ?? null;
    }

    public function pull() : array
    {
        return (get_object_vars($this));
    }

    public function login(array $data)
    {
        $conn = new Storage();
        $sql = "SELECT * FROM users WHERE (email = :username OR code = :username) AND password = :password LIMIT 1";
        $conn->query($sql);
        $conn->bind(":username", $data['username']);
        $conn->bind(":password", $data['password']);

        $this->push($conn->single());
        
        return $this;
    }

    public function register(Type $var = null)
    {
        # code...
    }
}
