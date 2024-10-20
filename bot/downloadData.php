<?php
$ini_array = parse_ini_file('.env');
foreach ($ini_array as $key => $value) {
  define($key, $value);
}
require_once dirname(__FILE__).'/S3.php';
ini_set('display_errors',0);

if($_SERVER['REQUEST_METHOD']=='GET') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $fileName = time().dechex(mt_rand(0,999999999)).".".$data['type'];
    ob_start();
    echo "https://zeapl-uat.s3.ap-south-1.amazonaws.com/bot_builder/assets/downloads/llama/".$fileName;
    $size = ob_get_length();
    header("Content-Length: $size");
    header('Connection: close');
    ob_end_flush();
    ob_flush();
    flush();
    if (session_id())
    session_write_close();

    $request_headers = array(
      "Content-Type:"."application/json",
      "Authorization: ".DIRECT_CLOUD_WABA_AUTH_TOKEN
    );
    $path = realpath(__DIR__ ).'/downloads/'.$fileName;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, DIRECT_CLOUD_WABA_BASE_URL.DIRECT_CLOUD_WABA_API_VERSION.$data['imageID'].'?phone_number_id='.DIRECT_CLOUD_PHONE_NUMBER_ID);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpcode != 200) {
      return 'Unable to download file!';
    }
  
    $url = json_decode($result,true);
    if(!isset($url['url'])){
      return 'Url not founde!';
    }
   
    $headers = [];
    $headers[]  = "Authorization: ".DIRECT_CLOUD_WABA_AUTH_TOKEN;
    $headers[]  = "Accept-Language:en-US,en;q=0.5";
    $headers[]  = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36";
    $chTemp = curl_init();

    $fp = fopen($path, 'wb');
    curl_setopt($chTemp, CURLOPT_URL, $url['url']);
    curl_setopt($chTemp, CURLOPT_FILE, $fp);
    curl_setopt($chTemp, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($chTemp, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($chTemp, CURLOPT_TIMEOUT, 0);
    curl_setopt($chTemp, CURLOPT_MAXREDIRS, 10);
    curl_setopt($chTemp, CURLOPT_ENCODING, '');
    curl_setopt($chTemp, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($chTemp, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($chTemp, CURLOPT_CUSTOMREQUEST, 'GET');
    $nr = curl_exec($chTemp);
    fclose($fp);
    curl_close($chTemp);
    
    $s3 = new S3();
    $status = $s3->putObject(
        S3::inputFile($path),
        bucket_name,
        folder_name.folder_name_download_attachment."llama/".$fileName,
        S3::ACL_PUBLIC_READ
      );
    if($status){
      unlink(realpath(__DIR__ ).'/downloads/'.$fileName);
    }
    exit();
} else {
    echo "false";
    header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
    exit;
}
?>
