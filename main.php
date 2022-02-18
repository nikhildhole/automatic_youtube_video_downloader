<?php declare(strict_types=1);
require_once(__DIR__.'/phpFunction/fileHandler.php');

require 'phpFunction/file.php';
require 'phpFunction/downloader.php';
require 'phpFunction/style.php';


styleDisplayMessage("Youtube video downloader started", "default", "green");
newLine();

while(true){
  styleDisplayMessage("Select one option:", "faster");
  styleDisplayMessage("1.Download from links.txt file", "faster");
  styleDisplayMessage("2.Enter youtube video link to download video", "faster");
  styleDisplayMessage("3.Exit from program", "faster");
  newLine();

  $option = enterOptionNumber();

  switch ($option) {
    case "1":
      menuLinksFile();
      break;
    case "2":
      menuEnterLink();
      break;
    case "3":
      exitFromProgram();
      break 2;
    default:
      enterValidOption();
  }
}





//functions
function enterOptionNumber(){
  styleDisplayMessage("Enter your option number: ", "default", "lightCyan", "");
  $option = readline();
  newLine();
  return $option;
}
function exitFromProgram(){
  styleDisplayMessage("Exiting from Youtube video downloader.", "default", "green");
  exit();
}
function exitFromLoop(){
  styleDisplayMessage("Exiting from loop.", "default", "green");
  newLine();
}
function enterValidOption(){
  styleDisplayMessage("Please enter valid option number", "default", "yellow");
  newLine();
}


//main menu
function menuLinksFile(){
  while(true){
    displayLinksFile();

    styleDisplayMessage("Select one option:", "faster");
    styleDisplayMessage("1.Auto Download", "faster");
    styleDisplayMessage("2.Ask every time before download", "faster");
    styleDisplayMessage("3.Exit from this loop", "faster");
    styleDisplayMessage("4.Exit from program", "faster");
    newLine();

    $option = enterOptionNumber();

    switch ($option) {
      case "1":
        autoDownload();
        break;
      case "2":
        askBeforeDownload();
        break;
      case "3":
        exitFromLoop();
        break 2;
      case "4":
        exitFromProgram();
        break;
      default:
        enterValidOption();
    }
  }
}
function menuEnterLink(){
  while(true){
    styleDisplayMessage("Enter your direct youtube video link OR", "faster");
    styleDisplayMessage("Select option number", "faster");
    styleDisplayMessage("1.Exit from this loop ", "faster");
    styleDisplayMessage("2.Exit from program", "faster");
    newLine();

    $option = enterOptionNumber();

    switch ($option) {
      case "1":
        exitFromLoop();
        break 2;
      case "2":
        exitFromProgram();
        break 2;
      default:
        try{
          if($option == ""){
            throw new Exception("Please enter youtube video link");
          }
          $linkData = getLinkData($option);
          displayLinkData($linkData, $option);
          $linkToConvert = getLinkToConvert($linkData);
          $lastLink = getLastLink($linkToConvert);
          downloadAndSave($lastLink, "Downloads/");
        }
        catch(Exception $e){
            styleDisplayMessage($e->getMessage(),"default", "lightRed");
            newLine();
        }
    }

  }
}




//download from links file
function autoDownload(){
  $media = selectMedia();
  $quality = "";

  if($media == "audio"){
    $quality = selectAudioQuality();
  }else{
    $quality = selectVideoQuality();
  }

  try{
    while(true){
      $data = getFile();
      $line1 = getLine($data, 0);
      if(!validateDirectoryName($line1)){
        throw new Exception("Directory name is not valide add # before directory name");
      }
      createDirectory($line1);
      $line2 = getLine($data, 1);
      if(validateDirectoryName($line2)){
        deleteLine($data, 0);
        break;
      }else{
        $linkData = getLinkData($line2);
        displayLinkData($linkData, $line2);
        $linkToConvert = getHighestQualitty($data, $media, $quality);
        $lastLink = getLastLink($linkToConvert);
        downloadAndSave($lastLink, "Downloads/");
        deleteLine($data, 1);
      }
    }
  }
  catch(Exception $e){
    styleDisplayMessage($e->getMessage(), "default", "lightRed");
  }
}
function askBeforeDownload(){
  try{
    while(true){
      $data = getFile();
      $line1 = getLine($data, 0);
      if(!validateDirectoryName($line1)){
        throw new Exception("Directory name is not valide add # before directory name");
      }
      createDirectory($line1);
      $line2 = getLine($data, 1);
      if(validateDirectoryName($line2)){
        deleteLine($data, 0);
        break;
      }else{
        styleDisplayMessage("Do you want to download youtube video with link ".$line2);
        newLine();
        while(true){
          styleDisplayMessage("Press enter button to download OR", "faster");
          styleDisplayMessage("Select option number", "faster");
          styleDisplayMessage("1.Exit from this loop ", "faster");
          styleDisplayMessage("2.Exit from program", "faster");
          newLine();
      
          $option = enterOptionNumber();
      
          switch ($option) {
            case "";
              break 2;
            case "1":
              exitFromLoop();
              break 3;
            case "2":
              exitFromProgram();
              break 3;
            default:
              enterValidOption();
          }
        }
        $linkData = getLinkData($line2);
        displayLinkData($linkData, $line2);
        $linkToConvert = getLinkToConvert($linkData);
        $lastLink = getLastLink($linkToConvert);
        downloadAndSave($lastLink, "Downloads/".$line1."/");
        deleteLine($data, 1);
      }
    }
  }
  catch(Exception $e){
    styleDisplayMessage($e->getMessage(), "default", "lightRed");
  }
}



//select video or audio
function  selectMedia(){
  while(true){
    styleDisplayMessage("Select one option", "faster");
    styleDisplayMessage("1.Video", "faster");
    styleDisplayMessage("2.Audio", "faster");
    newLine();

    $option = enterOptionNumber();

    switch ($option) {
      case "1":
        return "audio";
        break 2;
      case "2":
        return "video";
        break 2;
      default:
        enterValidOption();
    }
  }
}
//select quality
function selectAudioQuality(){
  while(true){
    styleDisplayMessage("Select one option", "faster");
    styleDisplayMessage("1. 48k", "faster");
    styleDisplayMessage("2. 64k", "faster");
    styleDisplayMessage("3. 128k", "faster");
    styleDisplayMessage("4. 160k", "faster");
    newLine();

    $option = enterOptionNumber();

    switch ($option) {
      case "1":
        return 0;
        break 2;
      case "2":
        return 1;
        break 2;
      case "3":
        return 2;
        break 2;
      case "4":
        return 3;
        break 2;
      default:
        enterValidOption();
    }
  }
}
function selectVideoQuality(){
  while(true){
    styleDisplayMessage("Select one option", "faster");
    styleDisplayMessage("1. 144p", "faster");
    styleDisplayMessage("2. 240p", "faster");
    styleDisplayMessage("3. 360p", "faster");
    styleDisplayMessage("4. 480p", "faster");
    styleDisplayMessage("5. 720p", "faster");
    styleDisplayMessage("6. 1080p", "faster");
    newLine();

    $option = enterOptionNumber();

    switch ($option) {
      case "1":
        return 0;
        break 2;
      case "2":
        return 1;
        break 2;
      case "3":
        return 2;
        break 2;
      case "4":
        return 3;
        break 2;
      case "5":
        return 4;
        break 2;
      case "6":
        return 5;
        break 2;
      default:
        enterValidOption();
    }
  }
}
?>
