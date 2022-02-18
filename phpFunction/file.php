<?php
function getFile(){
  $filePath = "links.txt";
  if(!file_exists($filePath)){
    throw new Exception("links.txt file not found");
  }
  $linksFile = file($filePath);
  styleDisplayMessage("links.txt file is loaded","default", "green");
  newLine();
  return $linksFile;
}
function displayLinksFile(){
  $linksFile = getFile();
  $numberOfLine = count($linksFile);
  if($numberOfLine == 0){
    throw new Exception("links.txt file is empty");
  }
  styleDisplayMessage("Display links file started");
  newLine();
  for($i = 0;$i<$numberOfLine;$i++){
    styleDisplayMessage($linksFile[$i], "fastest", "magenta", "");
  }
  newLine(2);
  styleDisplayMessage("Display links file ended");
  newLine(2);
}
function getLine($linksFile, $lineNumber){
  if(count($linksFile) <= $lineNumber){
    throw new Exception("Line number ".($lineNumber+1)." does not exist");
  }
  if($linksFile[$lineNumber] == "\n"){
    throw new Exception("Line number ".($lineNumber + 1)." is empty");
  }
  styleDisplayMessage("Line ".($lineNumber + 1)." is reading", "default", "green");
  newLine();
  return trim($linksFile[$lineNumber]);
}
function deleteLine($linksFile, $lineNumber){
  styleDisplayMessage(getLine($linksFile, $lineNumber)." is deleted");
  unset($linksFile[$lineNumber]);
  $filePath = "links.txt";
  file_put_contents($filePath, implode("", $linksFile));
}
function validateDirectoryName($name){
  if($name[0] == "#"){
    return true;
  }else{
    return false;
  }
}
function createDirectory($directoryName){
  $directoryName = substr_replace($directoryName, '', 0, 1);
  if(!is_dir("Downloads")){
    styleDisplayMessage("Downloads directory not present","default", "lightYellow");
    styleDisplayMessage("Creating Downloads directory","default", "green");
    mkdir("Downloads/");
    styleDisplayMessage("Downloads directory created","default", "green");
  }
  if(is_dir("Downloads/".$directoryName)){
    styleDisplayMessage("Directory is already present with name '".$directoryName."' No need to create.","default", "green");
    newLine();
  }else{
    styleDisplayMessage("Creating directory with name '".$directoryName."'.","default", "green");
    newLine();
    mkdir("Downloads/".$directoryName);
    styleDisplayMessage($directoryName." is created","default", "green");
    newLine();
  }
}
?>
