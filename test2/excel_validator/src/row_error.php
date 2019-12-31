<?php namespace Adel219\ExcelValidator;

class RowError {

    public $row;
    public $errors = [];

    function __construct($row) {
        $this->row = $row;
    }

}