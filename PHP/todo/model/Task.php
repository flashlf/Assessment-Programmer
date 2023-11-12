<?php

namespace Model;

use stdClass;

final class Task extends Entity implements Mapper
{
    const LOADBY_ID = 1;
    const LOADBY_TODO = 2;

    public $task_id;
    public $todo_id;
    public $status;
    public $description;

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
        if (!empty($this->task_id)) {
            $sql = <<<SQL
                UPDATE tasks SET
                    status = :status, description = :description
                WHERE task_id = :id and todo_id = :todo_id
            SQL;

            $conn->query($sql);
            $conn->bind(":description", filter_var($this->description, FILTER_SANITIZE_SPECIAL_CHARS));
            $conn->bind(":status", filter_var($this->status, FILTER_SANITIZE_NUMBER_INT));
            $conn->bind(":id", filter_var($this->task_id, FILTER_SANITIZE_NUMBER_INT));
            $conn->bind(":todo_id", filter_var($this->todo_id, FILTER_SANITIZE_NUMBER_INT));

        } else {
            $sql = <<<SQL
                INSERT INTO tasks (todo_id, status, description) values
                (:id, :status, :description)
            SQL;
            $conn->query($sql);
            $conn->bind(":status", filter_var($this->status, FILTER_SANITIZE_NUMBER_INT));
            $conn->bind(":description", filter_var($this->description, FILTER_SANITIZE_SPECIAL_CHARS));
            $conn->bind(":id", filter_var($this->todo_id, FILTER_SANITIZE_NUMBER_INT));
        }

        return $conn->execute();
    }

    public function load($id, $type = self::LOADBY_ID)
    {
        $conn = $this->storage;

        switch ($type) {
            case self::LOADBY_TODO :
                $column = 'todo_id';
            break;
            case self::LOADBY_ID :
            default :
                $column = 'task_id';
            break;
        }

        $sql = "SELECT * FROM tasks WHERE $column = :id";
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
            case self::LOADBY_TODO :
                $column = 'todo_id';
            break;
            case self::LOADBY_ID :
            default :
                $column = 'task_id';
            break;
        }

        $sql = "SELECT * FROM tasks WHERE $column = :id";
        $conn->query($sql);
        $conn->bind(":id", filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        $result = $conn->execute();

        if ($result) {
            return true;
        }

        return false;
    }

}