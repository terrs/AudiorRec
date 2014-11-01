<?php

if(empty($_FILES)) {
  echo "file empty";
}
foreach($_FILES as $file){
  //echo "Upload: " . $file["name"] . "<br />";
  //echo "Type: " . $file["type"] . "<br />";
  //echo "Size: " . ($file["size"] / 1024) . " Kb<br />";
  //echo "Stored in: " . $file["tmp_name"];
  
  move_uploaded_file($file["tmp_name"], "/var/www/upload/" . $file["name"]);
  //FIXME, just process the last voice
  $af = $file["name"];
  $af = "/var/www/upload/".$af;
}

//echo "file: " . $af . "<br />";
define('AUDIO_FILE', $af);
$url = "http://vop.baidu.com/server_api";

//put your params here
$cuid = "4436206";
$apiKey = "raxXyNIG8F4DKGSHco4D5gap";
$secretKey = "qdevOswZEWCIEcbWGzMnaftyasXtyFtP";

$auth_url = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=".$apiKey."&client_secret=".$secretKey;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $auth_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
$response = curl_exec($ch);
if(curl_errno($ch))
{
    //print curl_error($ch);
}
curl_close($ch);
$response = json_decode($response, true);
$token = $response['access_token'];

$audio = file_get_contents(AUDIO_FILE);
$base_data = base64_encode($audio);
$array = array(
        "format" => "pcm",
        "rate" => 8000,
        "channel" => 1,
        //"lan" => "zh",
        "token" => $token,
        "cuid"=> $cuid,
        //"url" => "http://www.xxx.com/sample.pcm",
        //"callback" => "http://www.xxx.com/audio/callback",
        "len" => filesize(AUDIO_FILE),
        "speech" => $base_data,
        );
$json_array = json_encode($array);
$content_len = "Content-Length: ".strlen($json_array);
$header = array ($content_len, 'Content-Type: application/json; charset=utf-8');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_array);
$response = curl_exec($ch);
if(curl_errno($ch))
{
    //print curl_error($ch);
}
curl_close($ch);
//echo $response;
$response = json_decode($response, true);

if($response['err_no'] == 0) {
	echo "result:" . $response['result'][0];
	file_put_contents("/var/www/upload/record.log", $af . "\t" .$response['result'][0]. "\r\n", LOCK_EX | FILE_APPEND);
} else {
	echo "error:" . $response['err_msg'];
}
?>
