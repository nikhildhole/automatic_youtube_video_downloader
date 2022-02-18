<?php
function getJsonFile($path){
    $json = file_get_contents($path);
  
    // Decode the JSON file
    $json_data = json_decode($json,true);

    // Display data
    return $json_data;
}
?>