# excel_validator
excel format and content validator

## Add new file type 
simply add a new php file with a class like the following

```php
<?php namespace Adel219\ExcelValidator;
 
 class TypeA extends TypeBase {
 
     public $COLUMNS_HEADERS = ['Field_A*', '#Field_B', 'Field_C', 'Field_D*', 'Field_E*'];
 
}
```

## Run from terminal
Type    `php excel_validator.php` in terminal. 
    
## Output

###################################
src/xlsx_files/Type_A.xlsx
###################################

3       Missing Value in Field_A*, #Field_B should not contain any space, Missing Value in Field_D*

4       Missing Value in Field_A*, Missing Value in Field_E*

###################################
src/xlsx_files/Type_A.xlsx
###################################

1       wrong header against class Adel219\ExcelValidator\TypeB

###################################
src/xlsx_files/Type_B.xlsx
###################################

1       wrong header against class Adel219\ExcelValidator\TypeA

###################################
src/xlsx_files/Type_B.xlsx
###################################

3       Missing Value in Field_A*, #Field_B should not contain any space
