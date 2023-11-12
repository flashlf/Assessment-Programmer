<?php
namespace Model;

use stdClass;

final class User extends Entity implements Mapper
{
    public $id;
    public $email;
    public $code;
    public $name;
    public $level;
    public $pass;


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
        $property = get_object_vars($this);

        foreach ($property as $key => $value) {

            $temp[$value] = $this->{$value};
        }
        return $temp;
    }

    public function save()
    {
        $conn = $this->storage;
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
        $conn = $this->storage;
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

    public function register(array $data)
    {
        $this->name = filter_var($data['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $this->code = filter_var($data['username'], FILTER_SANITIZE_SPECIAL_CHARS);
        $this->email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $this->pass = filter_var($data['password'], FILTER_SANITIZE_SPECIAL_CHARS);

        $this->save();
    }
}
