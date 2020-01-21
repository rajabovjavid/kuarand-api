<?php

function write_to_file($file, $text){
    $log_file = fopen($file, "w") or die("Unable to open file!");
    fwrite($log_file, $text);
    fclose($log_file);
}