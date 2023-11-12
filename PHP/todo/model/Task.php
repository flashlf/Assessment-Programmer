<?php

namespace Model;

class Task extends Entity implements Mapper
{
    public function __construct()
    {
        $this->Connection = $Datalink;
    }
}