<?php

namespace Model;

use stdClass;

final class Task extends Entity implements Mapper
{
    public $todo_id;
    public $task_id;
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

}