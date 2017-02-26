<?php

	printArray($_POST);

function printArray($array){
     foreach ($array as $key => $value){
        echo "$key => $value";
        if(is_array($value)){ //If $value is an array, print it as well!
            printArray($value);
        }  
    } 
}

?>
