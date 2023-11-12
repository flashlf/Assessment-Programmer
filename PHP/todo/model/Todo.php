<?php

namespace Model;

use stdClass;

final class Todo extends Entity implements Mapper
{
    const LOADBY_ID = 1;
    const LOADBY_USER = 2;

    public $todo_id;
    public $user_id;
    public $title;
    public $description;
    public $status;
    public $image_attachment;
    public $background;
    public $taskList;

    public function __construct(stdClass $param)
    {
        foreach ($param as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }

        parent::__construct();
    }

    public function save()
    {
        $conn = $this->storage;
        if (!empty($this->todo_id)) {
            $sql = <<<SQL
                UPDATE todos SET
                    status = :status, description = :description, background = :background, title = :title
                WHERE user_id = :user
            SQL;

            $conn->query($sql);
            $conn->bind(":description", filter_var($this->description, FILTER_SANITIZE_SPECIAL_CHARS));
            $conn->bind(":title", filter_var($this->title, FILTER_SANITIZE_SPECIAL_CHARS));
            $conn->bind(":background", filter_var($this->background, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $conn->bind(":status", filter_var($this->status, FILTER_SANITIZE_NUMBER_INT));
            $conn->bind(":user", filter_var($this->user_id, FILTER_SANITIZE_NUMBER_INT));
            $conn->bind(":todo_id", filter_var($this->todo_id, FILTER_SANITIZE_NUMBER_INT));

        } else {
            $sql = <<<SQL
                INSERT INTO todos (user_id, title, status, description, background, image_attachment) values
                (:id, :title, :status, :description, :background, :image)
            SQL;
            $conn->query($sql);
            $conn->bind(":status", filter_var($this->status, FILTER_SANITIZE_NUMBER_INT));
            $conn->bind(":description", filter_var($this->description, FILTER_SANITIZE_SPECIAL_CHARS));
            $conn->bind(":title", filter_var($this->title, FILTER_SANITIZE_SPECIAL_CHARS));
            $conn->bind(":background", filter_var($this->background, FILTER_SANITIZE_SPECIAL_CHARS));
            $conn->bind(":image", filter_var($this->image_attachment, FILTER_SANITIZE_SPECIAL_CHARS));
            $conn->bind(":id", filter_var($this->user_id, FILTER_SANITIZE_NUMBER_INT));
        }

        return $conn->execute();
    }

    public function load($id, $type = self::LOADBY_ID)
    {
        $conn = $this->storage;

        switch ($type) {
            case self::LOADBY_USER :
                $column = 'user_id';
            break;
            case self::LOADBY_ID :
            default :
                $column = 'todo_id';
            break;
        }

        $sql = "SELECT * FROM todos WHERE $column = :id";
        $conn->query($sql);
        $conn->bind(":id", filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        $result = $conn->single();

        if ($result) {
            $this->push($result);
            return true;
        }

        return false;
    }

    public function delete($id, $type = self::LOADBY_ID)
    {
        $conn = $this->storage;

        switch ($type) {
            case self::LOADBY_USER :
                $column = 'user_id';
            break;
            case self::LOADBY_ID :
            default :
                $column = 'todo_id';
            break;
        }

        $sql = "DELETE FROM todos WHERE $column = :id";
        $conn->query($sql);
        $conn->bind(":id", filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        $result = $conn->execute();

        if ($result) {
            return true;
        }
        return false;
    }

    public function update()
    {
        $conn = $this->storage;
        // Mendefinisikan array kolom yang dapat di-update
        $allowedColumns = ['user_id', 'title', 'description', 'background'];

        // Inisialisasi string query UPDATE
        $sql = "UPDATE todos SET ";

        // Mendefinisikan array untuk bind value
        $bindValues = [];

        // Iterasi untuk setiap kolom
        foreach ($allowedColumns as $column) {
            // Cek apakah properti tersebut di-set (tidak null)
            if ($this->{$column} !== null) {
                // Menambahkan kolom ke dalam query
                $sql .= "$column = :$column, ";

                // Menambahkan bind value ke dalam array
                $bindValues[":$column"] = $this->{$column};
            }
        }

        // Menghapus koma ekstra dan menambahkan kondisi WHERE
        $sql = rtrim($sql, ', ') . " WHERE todo_id = :todo_id and user_id = :user_id2";

        // Menambahkan bind value untuk todo_id
        $bindValues[':todo_id'] = $this->todo_id;
        $bindValues[':user_id2'] = $this->user_id;

        // Eksekusi query UPDATE
        $conn->query($sql, $bindValues);

        foreach ($bindValues as $key => $value) {
            $conn->bind($key, $value);
        }

        $result = $conn->execute();
        if ($result) {
            return true;
        }

        return false;
        
    }

    public function getTasks()
    {
        if (empty($this->todo_id)) {
            return false;
        }

        $tasklist = new Tasklist();
        $tasklist->loadTaskList($this->todo_id);
        $this->taskList = $tasklist->all(true);
        return true;
    }

}