<?php

namespace Model;

use stdClass;

interface Mapper
{
    public function save();
    public function push(stdClass $param) : void;
    public function pull();
}