<?php

namespace Model;

use stdClass;

class Tasklist extends Entity implements Mapper
{
    private array $list;


    public function __construct(Task ...$task)
    {
        $this->list = $task;
        parent::__construct();
    }
    
    public function add(Task $task) : void
    {
        $this->list[] = $task;
    }

    public function all(bool $convertToArray = false) : array
    {
        if ($convertToArray) {
            foreach ($this->list as $key => $value) {
                $this->list[$key] = (array) $value;
            }
        }
        return $this->list;
    }

    public function loadTaskList($id)
    {
        $conn = $this->storage;
        $sql = "SELECT * FROM tasks WHERE todo_id = :id";
        $conn->query($sql);
        $conn->bind(":id", filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        $result = $conn->resultSet();

        foreach ($result as $value) {
            $localTask = new Task($value);
            unset($localTask->storage);
            unset($localTask->task_id);
            $this->add($localTask);
        }
    }
}