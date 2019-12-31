<?php namespace Adel219\ExcelValidator;

require_once('vendor/autoload.php');

use Akeneo\Component\SpreadsheetParser\SpreadsheetParser;

include("type_base.php");
include("type_a.php");
include("type_b.php");
// include new types here

class ExcelValidator {

    public function loadFile()
    {
        $excel_types = [$typeA = new TypeA(), $typeB = new TypeB()]; // TypeC can be added to this array

        // Check all the available excel files in xlsx_files directory
        // against all the available types classes
        foreach (glob('src/xlsx_files/*.xlsx') as $file) // type_c.xlsx file can be added in this directory
        {
            $workbook = SpreadsheetParser::open($file);
            $myWorksheetIndex= $workbook->getWorksheetIndex('myworksheet');
            foreach ($excel_types as $excel_type)
            {
                $output = $excel_type->CheckContent($workbook, $myWorksheetIndex, $file);
                $this->pretty_print_output($output);
            }
        }
    }

    private function pretty_print_output($output) {
        echo "\n###################################\n";
        echo $output->file;
        echo "\n###################################";
        foreach ($output->errors as $row_error) {
            echo "\n" . $row_error->row . "\t";
            $errors_string = "";
            foreach ($row_error->errors as $error) {
                $errors_string =  $errors_string . $error . ", ";
            }
            echo rtrim($errors_string, ", ");
        }
        echo "\n";
    }
}

$Func = new ExcelValidator();
$Func->loadFile();

