<?php
include 'phpFunction/file.php';
include 'phpFunction/downloader.php';
require "phpFunction/style.php";

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
   
    ),);
$headers = get_headers("https://rr1---sn-4g5ednz7.googlevideo.com/videoplayback?expire=1643566798&ei=boL2YfD6KvaJ6dsPsYS3yAc&ip=154.194.9.160&id=o-AD7gs_aTB8cX0ZPrrkakVBNidHx6Y_JfiOntwDj6dgzw&itag=22&source=youtube&requiressl=yes&mh=bl&mm=31%2C26&mn=sn-4g5ednz7%2Csn-25ge7nsd&ms=au%2Conr&mv=m&mvi=1&pl=24&initcwndbps=271250&vprv=1&mime=video%2Fmp4&cnr=14&ratebypass=yes&dur=5331.719&lmt=1593668733976943&mt=1643544783&fvip=1&fexp=24001373%2C24007246&c=ANDROID&txp=7211222&sparams=expire%2Cei%2Cip%2Cid%2Citag%2Csource%2Crequiressl%2Cvprv%2Cmime%2Ccnr%2Cratebypass%2Cdur%2Clmt&sig=AOq0QJ8wRgIhAMfAgXEwkIoGVYxn3423sbaSPpdq21SOXX3W1ZiSMTy9AiEArsYsofHoCkiWxNXVxmMzwPpf_Mc7cPZ6cHHhPAWK-6k%3D&lsparams=mh%2Cmm%2Cmn%2Cms%2Cmv%2Cmvi%2Cpl%2Cinitcwndbps&lsig=AG3C_xAwRgIhAMalqzcUXIAdajdFRHrvaWven5npheP58KUVkdoIK7L7AiEAoTkPv98G86bqHYMYX-DbUswdswtGHupyvbrkzCj4Uy4%3D&title=Y2Mate.is%20-%20Core%20Java%20%40%20930%20AM%20by%20Mr.Harikrishna_NEW%20Link-0U61fOTBVPI-720p-1643545506983", true, stream_context_create($arrContextOptions));
$size = $headers['Content-Length'];
var_dump($size);
echo $size[1]/1;
?>
