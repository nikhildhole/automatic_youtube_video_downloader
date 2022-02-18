<?php
function validateDirectoyName($directoryName){
    if(!preg_match('/[ 0-9a-zA-Z]/', $directoryName)){
        throw new Exception($directoryName."is not valid. Directory name should only make up of letters, number and space\n");
    }
    return $directoryName;
}
function validateYoutubeLink($link){
    if(preg_match('/youtu/', $link)){
      return $link;
    }else{
      throw new Exception("Invalid this ".$link." youtu link. Link you should 'contain youtube.'");
    }
  }
?>