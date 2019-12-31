<?php namespace Adel219\ExcelValidator;

class FileError {

    public $file;
    public $errors = [];

    function __construct($file) {
        $this->file = $file;
    }

}