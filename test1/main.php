<?php

function getIndexOf($input, $idx) {
    $openings = 0;
    $closings = 0;
    $chars = str_split($input);
    if ($chars[$idx] == "("){
        foreach ($chars as $key => $value){
            if ($key >= $idx) {
                if ($value == "(") {
                    $openings += 1;
                } elseif ($value == ")") {
                    $closings += 1;
                }
                if ($openings == $closings and $openings != 0){
                    echo $key;
                    break;
                }
            }
        }
    }
}

getIndexOf("a (b c (d e (f) g) h) i (j k)", 2);
