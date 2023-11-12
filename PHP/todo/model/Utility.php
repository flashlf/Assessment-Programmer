<?php

namespace Model;

class Utility
{
    public bool $passed;
    public string $message;

    public function __construct()
    {
        $this->passed = false;
    }

    public function requiredInput($keyToValidate, $outputData)
    {
        $this->passed = true;
        foreach ($keyToValidate as $item) {
            if (!isset($outputData[$item]) || empty($outputData[$item])) {
                $this->passed = false;
                $this->message = "Mandatory parameter required! ".$item;
            }
        }
        
        return $this->passed;
    }
}