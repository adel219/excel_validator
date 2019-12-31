<?php namespace Adel219\ExcelValidator;

include("file_error.php");
include("row_error.php");

abstract class TypeBase{

    public function checkHeader(array $headers){
        return $this->checkColumnsCount($headers) and
            $this->checkColumnNamesAndOrder($headers);
    }

    public function checkColumnsCount(array $headers){
        return count($headers) === count($this->COLUMNS_HEADERS);
    }

    public function checkColumnNamesAndOrder(array $headers){
        $order = true;
        for ($x = 0; $x < count($this->COLUMNS_HEADERS); $x++) {
            if ( $headers[$x] !== $this->COLUMNS_HEADERS[$x]){
                $order = false;
            }
        }
        return $order;
    }

    public function CheckCellData($rowIndex, $cellIndex, $cellContent){
        if (substr( $this->COLUMNS_HEADERS[$cellIndex], 0, 1 ) === "#") {
            if (count(explode(' ', $cellContent)) > 1) {
                $row_error = new RowError($rowIndex);
                array_push($row_error->errors, $this->COLUMNS_HEADERS[$cellIndex] . " should not contain any space");
                return $row_error;
            }
        }
        if (substr( $this->COLUMNS_HEADERS[$cellIndex], -1 ) === "*") {
            if ($cellContent === '') {
                $row_error = new RowError($rowIndex);
                array_push($row_error->errors, "Missing Value in " . $this->COLUMNS_HEADERS[$cellIndex]);
                return $row_error;
            }
        }
    }

    public function AddRowErrorToTotalErrors($total_errors, $new_errors) {
        $row_error_not_existed = true;
        if ($new_errors !== NULL) {
            foreach ($total_errors->errors as $existing_error) {
                if ($existing_error->row === $new_errors->row) {
                    $existing_error->errors = array_merge($existing_error->errors, $new_errors->errors);
                    $row_error_not_existed = false;
                }
            }
            if ($row_error_not_existed) {
                array_push($total_errors->errors, $new_errors);
            }
        }
        return $total_errors;
    }

    public function CheckContent($workbook, $myWorksheetIndex, $file) {
        $total_errors = new FileError($file);
        foreach ($workbook->createRowIterator($myWorksheetIndex) as $rowIndex => $values) {
            if ($rowIndex == 1) {
                If (!$this->checkHeader($values))
                {
                    $row_error = new RowError($rowIndex);
                    array_push($row_error->errors, "wrong header against class " . get_class($this));
                    array_push($total_errors->errors, $row_error);
                    break;
                }
            }
            for ($x = 0; $x < count($this->COLUMNS_HEADERS); $x++) {
                if ($values[$x] === NULL) { $values[$x] = ''; } // Turn around for non initialized cell in excel
                // Turn around of date object
                // value "1 1" in source file is parsed as date
                // reverted back to string as it meant to be
                // all this if block could be removed if sources don't have date cell
                if (gettype($values[$x]) === 'object') {
                    $date = $values[$x];
                    $result = $date->format('Y-m-d');
                    $result = "1 1";
                    $row_errors = $this->CheckCellData($rowIndex, $x, $result);
                    $total_errors = $this->AddRowErrorToTotalErrors($total_errors, $row_errors);
                }
                else {
                    $row_errors = $this->CheckCellData($rowIndex, $x, $values[$x]);
                    $total_errors = $this->AddRowErrorToTotalErrors($total_errors, $row_errors);
                }
            }
        }
        return $total_errors;
    }
}