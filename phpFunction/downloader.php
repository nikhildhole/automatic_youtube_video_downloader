<?php
function getLinkData($link){
  styleDisplayMessage("Getting link data", "default", "green");
  styleDisplayMessage("Waiting for response", "default", "green");
  $ch = curl_init("http://srv13.y2mate.is/listFormats?url=".$link);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Accept: */*',
      'If-None_Match: W/"1757-KLW6qwKeiPPOuakiiuaschgZLzs"',
      'Origin: https: //y2mate.is',
      'Referer: https: //y2mate.is/',
      'Sec-Fetch-Dest: empty',
      'Sec-Fetch-Mode: cors',
      'Sec-Fetch-Site: cors'
  ));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($ch);
  curl_close($ch);
  return json_decode($data, true);
}
function displayLinkData($data, $link){
  newLine();
  styleDisplayMessage("Response is received", "default", "green");
  styleDisplayMessage("Displaying response", "default", "green");
  newLine();
  if($data['error']){
    styleDisplayMessage("error : true","default", "lightRed");
    styleDisplayMessage("status: ".$data['status'],"default", "lightRed");
    throw new Exception("Enter valid url");
  }else{
    styleDisplayMessage("error : false","default", "lightGreen");
    styleDisplayMessage("status: ".$data['status'],"default", "lightGreen");
    styleDisplayMessage("title: ".$data['formats']['title'],"default", "lightGreen");
    styleDisplayMessage("basename: ".$data['formats']['basename'],"default", "lightGreen");
    styleDisplayMessage("duration: ".gmdate("H:i:s",$data['formats']['duration']),"default", "lightGreen");
    newLine();
  }
}
function getLinkToConvert($data){
  while(true){
    styleDisplayMessage("Select option number to download", "default", "lightGreen");
    $option = 1;
    newLine();
    $numberOfVideo = count($data['formats']['video']);
    for($i = 0;$i < $numberOfVideo; $i++){
      styleDisplayMessage($option++.". Video-> Quality: ".$data['formats']['video'][$i]['quality'].", File Size: ".formatSizeUnits($data['formats']['video'][$i]['fileSize']). ", File Type:".$data['formats']['video'][$i]['fileType'] , "faster", "lightGreen");
    }
    newLine();
    $numberOfAudio = count($data['formats']['audio']);
    for($i = 0;$i < $numberOfAudio; $i++){
      styleDisplayMessage($option++.". Audio-> Quality: ".$data['formats']['audio'][$i]['quality'].", File Size: ".formatSizeUnits($data['formats']['audio'][$i]['fileSize']). ", File Type:".$data['formats']['audio'][$i]['fileType'] , "faster", "lightGreen");
    }
    newLine();

    $option = enterOptionNumber();

    for($i = 0;$i < $numberOfVideo; $i++){
      if($option == $i + 1){
        return $data['formats']['video'][$i];
      }
    }
    for($i = 0;$i < $numberOfAudio; $i++){
      if($option == $i + 1 + $numberOfVideo){
        return $data['formats']['audio'][$i];
      }
    }
    enterValidOption();
  }
}
function getLastLink($data){
  if($data['description']['block'] == false && $data['needConvert'] == false){
    styleDisplayMessage("No need to convert link", "default", "green");
    return $data;
  }
  styleDisplayMessage("Link converstion started", "default", "green");
  $link = substr_replace($data['url'], '', 4, 1);
  $curl1 = curl_init($link);
  curl_setopt($curl1, CURLOPT_HEADER, 0);
  curl_setopt($curl1, CURLOPT_HTTPHEADER,  array(
    "Accept: application/json, text/javascript, */*; q=0.01",
    "Accept-Language: en-GB,en;q=0.5",
    "Accept-Encoding: gzip, deflate, br",
    "Origin: https://y2mate.is",
    "Connection: keep-alive",
    "Referer: https://y2mate.is/",
    "Sec-Fetch-Dest: empty",
    "Sec-Fetch-Mode: cors",
    "Sec-Fetch-Site: same-site"
  ));
  curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
  $resp1 = curl_exec($curl1);
  curl_close($curl1);

  $resp1Data = json_decode($resp1, true);

  if($resp1Data['error']){
    styleDisplayMessage("error: true", "fastest", "lightRed");
    styleDisplayMessage("status: ".$resp1Data['status'], "fastest", "lightRed");
    throw new Exception("Something is wrong contact Nikhil Dhole, Email: nikhildadadhole@gmail.com");
  }else{
    newLine();
    styleDisplayMessage("error: false", "fastest", "lightGreen");
    styleDisplayMessage("status: ".$resp1Data['status']." wait", "fastest", "lightGreen");
    newLine();
  }

  $lastLink = substr_replace($resp1Data['url'], '', 4, 1);

  while(true){
    $curl2 = curl_init($lastLink);
    curl_setopt($curl2, CURLOPT_HEADER, 0);
    curl_setopt($curl2, CURLOPT_HTTPHEADER,  array(
      "Accept: application/json, text/javascript, */*; q=0.01",
      "Accept-Language: en-GB,en;q=0.5",
      "Accept-Encoding: gzip, deflate, br",
      "Origin: https://y2mate.is",
      "Connection: keep-alive",
      "Referer: https://y2mate.is/",
      "Sec-Fetch-Dest: empty",
      "Sec-Fetch-Mode: cors",
      "Sec-Fetch-Site: same-site"
    ));
    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
    $resp2 = curl_exec($curl2);
    curl_close($curl2);

    $resp2Data = json_decode($resp2, true);

    if($resp2Data['error']){
      styleDisplayMessage("error: true", "fastest", "lightRed");
      styleDisplayMessage("status: ".$resp2Data['status'], "fastest", "lightRed");
      throw new Exception("Something is wrong contact Nikhil Dhole, Email: nikhildadadhole@gmail.com");
    }else{
      newLine();
      styleDisplayMessage("error: false", "fastest", "lightGreen");
      styleDisplayMessage("status: ".$resp2Data['status'], "fastest", "lightGreen");
      newLine();
    }

    if($resp2Data['status'] == "ready"){
      styleDisplayMessage("Downloaded link is ready", "default", "green");
      return $resp2Data;
    }

    sleep(2);
  }
}
function downloadAndSave($data, $directoryName){
  $link = $data['url'];
  $fileName = str_replace("Y2Mate.is - ", "",$data['filename']);

  $link = loopLocation($link);
  $arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
  );

  $size = retrieve_remote_file_size($link);
  styleDisplayMessage("Total downlaod size: ".formatSizeUnits($size), "default", "green");


  styleDisplayMessage("Download process stated");
  newLine();

  $local_file = $directoryName.$fileName;
  if ($fp_remote = fopen($link, 'rb',false, stream_context_create($arrContextOptions))) {
      $local_file = $directoryName.$fileName;
      if ($fp_local = fopen($local_file, 'wb')) {
        while ($buffer = fread($fp_remote, 1024)) {
          fwrite($fp_local, $buffer);
          clearstatcache($local_file);
          $currentSize = filesize($local_file);
          echo "Downloaded : ".(($currentSize*100)/$size)."% and ".formatSizeUnits($currentSize)."\r";
        }
      fclose($fp_local);  
      }
      fclose($fp_remote);
  } else{
    throw new Exception("Error while download and save file");
  }
  newLine();
  styleDisplayMessage("Download completed");
  styleDisplayMessage("File is saved");
  newLine();
}
function getHighestQualitty($data, $media, $quality){
  if($media == "video"){
    for($i=5;$i>=0;$i--){
      if(isset($data['formats']['video'][$quality])){
        return $data['formats']['video'][$quality];
      }
    }
  }else{
    for($i=5;$i>=0;$i--){
      if(isset($data['formats']['audio'][$quality])){
        return $data['formats']['audio'][$quality];
      }
    }
  }
}
//function
function formatSizeUnits($bytes){
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}
function retrieve_remote_file_size($url){
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, TRUE);
  curl_setopt($ch, CURLOPT_NOBODY, TRUE);

  $data = curl_exec($ch);
  $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

  curl_close($ch);
  return $size;
}
function loopLocation($url){
  while(true){
    $ch = curl_init($url);        
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // follow redirects
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // set referer on redirect
    curl_setopt($ch,CURLOPT_HEADER,false); // if you want to print the header response change false to true
    $response = curl_exec($ch);
    $target = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    if ($target){
        styleDisplayMessage($target);
        $url = $target; // the location you want
    }else{
      return $target;
    }
  }
}
?>