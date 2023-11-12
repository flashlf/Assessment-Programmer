<?php

namespace Model;

use stdClass;

class Todolist extends Entity implements Mapper
{
    private array $list;

    public function __construct(Todo ...$todo)
    {
        $this->list = $todo;
        parent::__construct();
    }
    
    public function add(Todo $todo) : void
    {
        $this->list[] = $todo;
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

    public function loadTodoList($id)
    {
        $conn = $this->storage;
        $sql = "SELECT * FROM todos WHERE user_id = :id";
        $conn->query($sql);
        $conn->bind(":id", filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        $result = $conn->resultSet();
        
        if (empty($result)) {
            return false;
        }

        foreach ($result as $value) {
            $localTodo = new Todo($value);
            $localTodo->getTasks();
            unset($localTodo->storage);
            $this->add($localTodo);
        }

        return true;
    }
}