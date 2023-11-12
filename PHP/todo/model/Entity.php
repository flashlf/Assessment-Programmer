<?php

namespace Model;

use stdClass;

abstract class Entity
{
    public Storage $storage;

    public function __construct()
    {
        $this->storage = new Storage();
    }

    public function save()
    {
        # ambil semua parameter yg akan disimpan
        # check ke db apakah data tersebut ada
        # tentukan mode insert / update
        # eksekusi data ke db
    }

    public function delete()
    {
        # ambil semua parameter yg akan disimpan
        # check ke db apakah data tersebut ada
        # eksekusi delete
    }

    public function push(stdClass $param) : void
    {
        # Mapping tiap object pada $param ke property object
        $property = get_object_vars($this);
        $mapping = get_object_vars($param);

        foreach ($property as $key => $value) {

            foreach ($mapping as $keyMap => $valueMap) {

                if (isset($this->{$keyMap})) {
                    $this->{$value} = $valueMap;
                }

            }
        }

    }
    public function pull()
    {
        $property = get_object_vars($this);

        foreach ($property as $key => $value) {

            $temp[$value] = $this->{$value};
        }
        return $temp;
    }
}