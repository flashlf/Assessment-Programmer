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
    public $pass;

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

    public function save()
    {
        $conn = new Storage();

        if (!empty($this->id)) {
            $sql = <<<SQL
                UPDATE users SET
                    email = :email, code = :username, name = :name,
                    password = :password
                WHERE id = :id
            SQL;

            $conn->query($sql);
            $conn->bind(":username", $this->code);
            $conn->bind(":email", $this->email);
            $conn->bind(":id", $this->id);
            $conn->bind(":name", $this->name);

        } else {
            $sql = <<<SQL
                INSERT INTO users (code, email, name, password, level) values
                (:code, :email, :name, :password, 2)
            SQL;
            $pass_hashed = password_hash(filter_var($this->pass, FILTER_SANITIZE_SPECIAL_CHARS), PASSWORD_DEFAULT);
            $conn->query($sql);
            $conn->bind(":code", $this->code);
            $conn->bind(":email", $this->email);
            $conn->bind(":name", $this->name);
            $conn->bind(":password", $pass_hashed);
        }

        return $conn->execute();
    }

    public function login(array $data)
    {
        $conn = new Storage();
        $sql = "SELECT * FROM users WHERE (email = :username OR code = :username) LIMIT 1";
        $conn->query($sql);
        $conn->bind(":username", filter_var($data['username'], FILTER_SANITIZE_SPECIAL_CHARS));
        $result = $conn->single();
        $hashed = $result->password;

        $verify = password_verify($data['password'], $hashed);
        if ($result === false || $verify === false) {
            header("Location: ".URLROOT); die;
        }

        $this->push($result);

        return $this;
    }

    public function register(Type $var = null)
    {
        # code...
    }
}
