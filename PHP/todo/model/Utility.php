<?php

namespace Model;

require_once "../config/config.php";

use Todo\Config;

class Utility
{
    public bool $passed;
    public string $message;

    public $base64Header;
    public $base64Content;
    public string $fileSize;
    public string $fileExtension;
    public $allowedMimeType = [
        'pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp',
        'docx', 'doc', 'xls', 'xlsx', 'ppt', 'pptx'
    ];

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

    public function getDataBase64(string $base64)
    {
        
        $base64Data = $base64;

        $data = explode(',', $base64Data);

        $this->base64Header = $data[0];

        $this->base64Content = $data[1];

        if (base64_decode($this->base64Content)) {
            list($type, $encoding) = explode(';', $this->base64Header);
            list(, $type) = explode('/', $type);
    
            // Mendapatkan ukuran file dalam bytes
            $this->fileSize = strlen(base64_decode($this->base64Content));
            // Mendapatkan ekstensi file dari tipe file
            // $this->fileExtension = pathinfo($type, PATHINFO_EXTENSION) ?? $type;
            $this->fileExtension = $type;
            if (in_array(strtolower($this->fileExtension), $this->allowedMimeType)) {
                $this->passed = true;
            } else {
                $this->passed = false;
            }
        } else {
            $this->passed = false;
        }
    }

    public function saveBase64ToFile()
    {
        $currDIR = __DIR__;
        $up2LevelDIR = dirname(dirname($currDIR)).UPLOAD_DIR;
        $fileName = uniqid() . ".$this->fileExtension";

        $filePath = $up2LevelDIR . $fileName;
        if (is_writable($up2LevelDIR)) {
            file_put_contents($filePath, base64_decode($this->base64Content));
        } else {
            chmod($up2LevelDIR, 0777);
            file_put_contents($filePath, base64_decode($this->base64Content));
        }

        return UPLOAD_DIR.$fileName;
    }
}