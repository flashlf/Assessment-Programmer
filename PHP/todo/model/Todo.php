<?php

namespace Model;

use stdClass;

final class Todo extends Entity implements Mapper
{
    public $todo_id;
    public $user_id;
    public $title;
    public $description;
    public $status = 0;
    public $image_attachment;
    public $background = "FFFFFF";
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