<?php
require_once(__DIR__.'/fileHandler.php');
function newLine($numberOfLine = 1){
    for($i = 0; $i<$numberOfLine; $i++){
        echo "\n";
    }
}
function styleDisplayMessage($message, $speed = "default", $color = "default", $line = "\n"){
    $length = strlen($message);
    for($i = 0; $i < $length; $i++){
      echo "\033[".styleSelectConsoleTextColor($color)."m".$message[$i];
      usleep(styleSelectConsoleTextDisplaySpeed($speed));
    }
    echo "\033[39m".$line;
}
function styleSelectConsoleTextColor($color){
    $jsonColor = getJsonFile(__DIR__."/jsonData/consoleTextColor.json");
    if(array_key_exists($color, $jsonColor)){
        return $jsonColor[$color];
    }else{
        return $jsonColor['default'];
    }
}
function styleSelectConsoleTextDisplaySpeed($speed){
    $jsonSpeed = getJsonFile(__DIR__."/jsonData/consoleTextDisplaySpeed.json");
    if(array_key_exists($speed, $jsonSpeed)){
        return $jsonSpeed[$speed];
    }else{
        return $jsonSpeed['default'];
    }
}
?>