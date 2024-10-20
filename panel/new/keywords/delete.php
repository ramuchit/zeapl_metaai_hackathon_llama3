<?php 
$key = base64_decode($_REQUEST['key']);
$keywordData = file_get_contents("../data/keywords/keywords.json"); 
$keywords = json_decode($keywordData,TRUE);
unset($keywords[$key]);
$keywords = array_values($keywords);
file_put_contents("../data/keywords/keywords.json", json_encode($keywords,JSON_PRETTY_PRINT));
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://llama.zeapl.com/panel/update_model.php',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
curl_close($curl);
header('Location: index.php');
 ?>