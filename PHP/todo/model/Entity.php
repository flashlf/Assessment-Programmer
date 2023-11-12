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

    public function load($id)
    {
        # ambil parameter yg dibutuhkan
        # load data sesuai parameter
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
                if ($key === $keyMap) {
                    if ($value instanceof Storage) {
                        // Tambahkan logika khusus untuk Storage jika diperlukan
                        // $this->{$key}->someMethod($valueMap);
                    } else {
                        $this->{$key} = $valueMap;
                    }
                }
            }
        }

        unset($this->storage);
    }

    public function pull()
    {
        unset($this->storage);
        return get_object_vars($this);
    }
}