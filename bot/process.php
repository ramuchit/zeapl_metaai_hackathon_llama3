<?php
$ini_array = parse_ini_file('.env');
foreach ($ini_array as $key => $value) {
  define($key, $value);
}


class process {
    var $trackContentVar = true;
    function __construct(){
        require_once dirname(__FILE__).'/wrapper.php';
        require_once dirname(__FILE__).'/localconfig.php';
        require_once dirname(__FILE__).'/sender.php';
        require_once dirname(__FILE__).'/putter_db.php';
        require_once dirname(__FILE__).'/getter_db.php';
    }

    function reciever($recievedData){
        //Wrapper to decode
        $send_hsm=true;
        $wrapDecode = new wrapper();
        $decodeData = $wrapDecode->wrap($recievedData, DECODE);
       
        //Downloading image here
        if($decodeData['data']['type'] == typeImage OR $decodeData['data']['type'] == typeDocument OR $decodeData['data']['type'] == typeVideo OR $decodeData['data']['type'] == typeAudio OR $decodeData['data']['type'] == typeVoice){
            $decodeData = $this->downloadData($decodeData);
            sleep(2);
            if($decodeData['data']['type'] == typeDocument) {
                try {
                    $local_path=$this->downloadFile($decodeData['data']['body']);
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://llamachat.zeapl.com/api/v1/files/',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('file'=> new CURLFILE($local_path)),
                    CURLOPT_HTTPHEADER => array(
                            'Accept: application/json',
                            'Authorization: '.OPEN_WEBUI_AUTH_TOKEN
                        ),
                    ));
                    $response = curl_exec($curl);
                    $decodeData['data']['file_id']=json_decode($response,true)['id'];
                    curl_close($curl);
                    $send_hsm=false;
                } catch (Exception $e) {
                    // Handle any exceptions that occurred during the execution
                    print_r(['error' => $e->getMessage()]);
                } finally {
                    // Removing file in case of success or exception
                    unlink($local_path);
                }
            }
        }

        //Logging of data along with the segmentID
        $this->_logSMS($decodeData);


        if($decodeData['data']['type'] =='audio') {
            $decodeData['message'][0] = array(
                "type"=>"text",
                "message_content" => "Please ⏳ wait a moment while I fetch the details..."
            );
            $sendMessage = new sender();
            $sendMessage->send($decodeData,0);

            $speechRes = $this->speechToText($decodeData);

            $decodeData['data']['type'] = 'text';
            $decodeData['data']['body'] = $speechRes['transcript'];
            $this->_logSMS($decodeData);

            $decodeData['message'][0] = array(
                "type"=>"text",
                "message_content" => "Your speech text is :- *".$decodeData['data']['body']."*"
            );
            $sendMessage = new sender();
            $sendMessage->send($decodeData,0);

            $send_hsm=true;
        }

        $decodeData['data']['body'] = strtolower($decodeData['data']['body']);

        //Avoiding send_hsm in case of file upload
        if($send_hsm==false || $this->clear($decodeData)) return;

        if(array_key_exists("subType",$decodeData['data']) && $decodeData['data']['subType'] == typeFlowRes){
            if($decodeData['data']['nfm_reply_res']['flow_token']=='warranty') {
                $message = $this->saveWarranty($decodeData);
            }
            if($decodeData['data']['nfm_reply_res']['flow_token']=='tyre_reminder') {
                $message = $this->saveTyreReminder($decodeData);
            }
            if($decodeData['data']['nfm_reply_res']['flow_token']=='nearest_tyre_locator') {
                $message = $this->saveNearestTyreLocator($decodeData);
            }
            
            $decodeData['message'][0] = array(
                "type"=>"text",
                "message_content" => $message
            );
        } else if((str_contains($decodeData['data']['body'],"warranty") && str_contains($decodeData['data']['body'],"register")) || (str_contains($decodeData['data']['body'],"warranty") && str_contains($decodeData['data']['body'],"registration")) || $decodeData['data']['body'] =="Warranty Registration" || $decodeData['data']['body'] =="Warranty registration.") {
            $decodeData['message'][0] = array(
                "type"=>"flow",
                "message_content" => "To complete the Warranty Registration, Please fill the form.",
                "flow_token" => "warranty",
                "flow_id" => "408665555436427",
                "button_cta" => "Start",
                "screen" => "screen_spkiso"

            );
        } else if ((str_contains($decodeData['data']['body'],"tyre") && str_contains($decodeData['data']['body'],"maintenance")) || (str_contains($decodeData['data']['body'],"maintenance") && str_contains($decodeData['data']['body'],"reminder")) || (str_contains($decodeData['data']['body'],"tyre") && str_contains($decodeData['data']['body'],"reminder")) || (str_contains($decodeData['data']['body'],"tyre") && str_contains($decodeData['data']['body'],"maintenance") && str_contains($decodeData['data']['body'],"reminder")) || $decodeData['data']['body'] =="Tyre Maintanance Reminder") {
            $decodeData['message'][0] = array(
                "type"=>"flow",
                "message_content" => "If you need tyre maintenance reminder then please fill the form",
                "flow_token" => "tyre_reminder",
                "flow_id" => "4060066487546819",
                "button_cta" => "Start",
                "screen" => "screen_yedcel"

            );
        } else if ((str_contains($decodeData['data']['body'],"nearest") && str_contains($decodeData['data']['body'],"service")) || (str_contains($decodeData['data']['body'],"tyre") && str_contains($decodeData['data']['body'],"service"))  || (str_contains($decodeData['data']['body'],"tyre") && str_contains($decodeData['data']['body'],"service") && str_contains($decodeData['data']['body'],"locator")) || $decodeData['data']['body'] =="Warranty Registration" || $decodeData['data']['body'] =="Nearest tyre service locator." || $decodeData['data']['body'] =="Nearest Tyre Service Locator") {
            $decodeData['message'][0] = array(
                "type"=>"flow",
                "message_content" => "To get near by tyre service locator, please fill the form",
                "flow_token" => "nearest_tyre_locator",
                "flow_id" => "1051733323414591",
                "button_cta" => "Start",
                "screen" => "screen_zooqho"

            );
        } else {
            $openWebUiResponse = $this->openWebUiResponse($decodeData['data']);
            $openWebUiRes = json_decode($openWebUiResponse,TRUE);
            $openWebUiConent = $openWebUiRes['choices'][0]['message']['content'];
            $openWebUiConent = substr($openWebUiConent, 0, 1000);
            $decodeData['message'][0] = array(
                "type"=>"text",
                "message_content" => $openWebUiConent
            );
            /*$bedrockResponse = $this->getBadrockRes($decodeData['data']);
            $bedrockRes = json_decode($bedrockResponse,TRUE);
            $decodeData['message'][0] = array(
                "type"=>"text",
                "message_content" => $bedrockRes['generation']
            );*/
        }

        $sendMessage = new sender();
        $sendMessage->send($decodeData);
    }

    function writeLOG($data){
        $file = fopen("/home/ubuntu/botlog.txt","a+");
        fwrite($file,print_r($data,true));
        fclose($file);
    }

    function decodeCMS($data){
        $response = array();
        switch($data['data']['type']){
            case typeText:
                $response['type'] = $data['data']['type'];
                $response['message_content'] = $data['data']['body'];
                break;
            case typeImage:
            case typeVideo:
            case typeAudio:
            case typeDocument:
                $response['type'] = $data['data']['type'];
                $response['url'] = $data['data']['body'];
                if(array_key_exists("caption",$data['data']))
                    $response['caption'] = $data['data']['caption'];
                if(array_key_exists("filename",$data['data']))
                    $response['filename'] = $data['data']['filename'];
                break;
            case typeLocation:
                $response['type'] = $data['data']['type'];
                $response['latitude'] = $data['data']['latitude'];
                $response['longitude'] = $data['data']['longitude'];
                if(array_key_exists("name",$data['data']))
                    $response['name'] = $data['data']['name'];
                if(array_key_exists("address",$data['data']))
                    $response['address'] = $data['data']['address'];
                break;
                break;
        }
        return $response;
    }

    function downloadData($decodeData){
        if(($decodeData['data']['type'] == typeVoice || $decodeData['data']['type'] == typeAudio) && strstr($decodeData['data']['mime_type'], "audio/ogg")) {
            $decodeData['data']['mime_type'] = "audio/ogg";
        }
        $request_headers = array(
            "Content-Type:" . "application/json"
        );
        $mimeValue = explode('/',$decodeData['data']['mime_type']);
        $data_string = array(
            'imageID' => $decodeData['data']['typeid'],
            'type' => $this->mime2ext($decodeData['data']['mime_type']),
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, HELPER_DIRECT_CLOUD_DOWNLOAD_FILES);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_string));
        $result = curl_exec($ch);
        curl_close($ch);
        $decodeData['data']['body'] = $result;
        return $decodeData;
    }

    function mime2ext($mime) {
        $mime_map = [
            'image/jpeg'=>'jpeg',
            'image/pjpeg'=>'jpeg',
            'image/jpg'=>'jpg',
            'video/3gpp2'=>'3g2',
            'video/3gp'=>'3gp',
            'video/3gpp'=>'3gp',
            'application/x-compressed'=>'7zip',
            'audio/x-acc'=>'aac',
            'audio/ac3'=>'ac3',
            'application/postscript'=>'ai',
            'audio/x-aiff'=>'aif',
            'audio/aiff'=>'aif',
            'audio/x-au'=>'au',
            'video/x-msvideo'=>'avi',
            'video/msvideo'=>'avi',
            'video/avi'=>'avi',
            'application/x-troff-msvideo'=>'avi',
            'application/macbinary'=>'bin',
            'application/mac-binary'=>'bin',
            'application/x-binary'=>'bin',
            'application/x-macbinary'=>'bin',
            'image/bmp'=>'bmp',
            'image/x-bmp'=>'bmp',
            'image/x-bitmap'=>'bmp',
            'image/x-xbitmap'=>'bmp',
            'image/x-win-bitmap'=>'bmp',
            'image/x-windows-bmp'=>'bmp',
            'image/ms-bmp'=>'bmp',
            'image/x-ms-bmp'=>'bmp',
            'application/bmp'=>'bmp',
            'application/x-bmp'=>'bmp',
            'application/x-win-bitmap'=>'bmp',
            'application/cdr'=>'cdr',
            'application/coreldraw'=>'cdr',
            'application/x-cdr'=>'cdr',
            'application/x-coreldraw'=>'cdr',
            'image/cdr'=>'cdr',
            'image/x-cdr'=>'cdr',
            'zz-application/zz-winassoc-cdr'=>'cdr',
            'application/mac-compactpro'=>'cpt',
            'application/pkix-crl'=>'crl',
            'application/pkcs-crl'=>'crl',
            'application/x-x509-ca-cert'=>'crt',
            'application/pkix-cert'=>'crt',
            'text/css'=>'css',
            'text/x-comma-separated-values'=>'csv',
            'text/comma-separated-values'=>'csv',
            'application/vnd.msexcel'=>'csv',
            'application/x-director'=>'dcr',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'=>'docx',
            'application/x-dvi'=>'dvi',
            'message/rfc822'=>'eml',
            'application/x-msdownload'=>'exe',
            'video/x-f4v'=>'f4v',
            'audio/x-flac'=>'flac',
            'video/x-flv'=>'flv',
            'image/gif'=>'gif',
            'application/gpg-keys'=>'gpg',
            'application/x-gtar'=>'gtar',
            'application/x-gzip'=>'gzip',
            'application/mac-binhex40'=>'hqx',
            'application/mac-binhex'=>'hqx',
            'application/x-binhex40'=>'hqx',
            'application/x-mac-binhex40'=>'hqx',
            'text/html'=>'html',
            'image/x-icon'=>'ico',
            'image/x-ico'=>'ico',
            'image/vnd.microsoft.icon'=>'ico',
            'text/calendar'=>'ics',
            'application/java-archive'=>'jar',
            'application/x-java-application'=>'jar',
            'application/x-jar'=>'jar',
            'image/jp2'=>'jp2',
            'video/mj2'=>'jp2',
            'image/jpx'=>'jp2',
            'image/jpm'=>'jp2',
            'image/jpeg'=>'jpeg',
            'image/pjpeg'=>'jpeg',
            'application/x-javascript'=>'js',
            'application/json'=>'json',
            'text/json'=>'json',
            'application/vnd.google-earth.kml+xml'=>'kml',
            'application/vnd.google-earth.kmz'=>'kmz',
            'text/x-log'=>'log',
            'audio/x-m4a'=>'m4a',
            'application/vnd.mpegurl'=>'m4u',
            'audio/midi'=>'mid',
            'application/vnd.mif'=>'mif',
            'video/quicktime'=>'mov',
            'video/x-sgi-movie'=>'movie',
            'audio/mpeg'=>'mp3',
            'audio/mpg'=>'mp3',
            'audio/mpeg3'=>'mp3',
            'audio/mp3'=>'mp3',
            'video/mp4'=>'mp4',
            'video/mpeg'=>'mpeg',
            'application/oda'=>'oda',
            'application/vnd.oasis.opendocument.text'=>'odt',
            'application/vnd.oasis.opendocument.spreadsheet'=>'ods',
            'application/vnd.oasis.opendocument.presentation'=>'odp',
            'audio/ogg'=>'ogg',
            'video/ogg'=>'ogg',
            'application/ogg'=>'ogg',
            'application/x-pkcs10'=>'p10',
            'application/pkcs10'=>'p10',
            'application/x-pkcs12'=>'p12',
            'application/x-pkcs7-signature'=>'p7a',
            'application/pkcs7-mime'=>'p7c',
            'application/x-pkcs7-mime'=>'p7c',
            'application/x-pkcs7-certreqresp'=>'p7r',
            'application/pkcs7-signature'=>'p7s',
            'application/pdf'=>'pdf',
            'application/octet-stream'=>'pdf',
            'application/x-x509-user-cert'=>'pem',
            'application/x-pem-file'=>'pem',
            'application/pgp'=>'pgp',
            'application/x-httpd-php'=>'php',
            'application/php'=>'php',
            'application/x-php'=>'php',
            'text/php'=>'php',
            'text/x-php'=>'php',
            'application/x-httpd-php-source'=>'php',
            'image/png'=>'png',
            'image/x-png'=>'png',
            'application/powerpoint'=>'ppt',
            'application/vnd.ms-powerpoint'=>'ppt',
            'application/vnd.ms-office'=>'ppt',
            'application/msword'=>'doc',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'=>'pptx',
            'application/x-photoshop'=>'psd',
            'image/vnd.adobe.photoshop'=>'psd',
            'audio/x-realaudio'=>'ra',
            'audio/x-pn-realaudio'=>'ram',
            'application/x-rar'=>'rar',
            'application/rar'=>'rar',
            'application/x-rar-compressed'=>'rar',
            'audio/x-pn-realaudio-plugin'=>'rpm',
            'application/x-pkcs7'=>'rsa',
            'text/rtf'=>'rtf',
            'text/richtext'=>'rtx',
            'video/vnd.rn-realvideo'=>'rv',
            'application/x-stuffit'=>'sit',
            'application/smil'=>'smil',
            'text/srt'=>'srt',
            'image/svg+xml'=>'svg',
            'application/x-shockwave-flash'=>'swf',
            'application/x-tar'=>'tar',
            'application/x-gzip-compressed'=>'tgz',
            'image/tiff'=>'tiff',
            'text/plain'=>'txt',
            'text/x-vcard'=>'vcf',
            'application/videolan'=>'vlc',
            'text/vtt'=>'vtt',
            'audio/x-wav'=>'wav',
            'audio/wave'=>'wav',
            'audio/wav'=>'wav',
            'application/wbxml'=>'wbxml',
            'video/webm'=>'webm',
            'audio/x-ms-wma'=>'wma',
            'application/wmlc'=>'wmlc',
            'video/x-ms-wmv'=>'wmv',
            'video/x-ms-asf'=>'wmv',
            'application/xhtml+xml'=>'xhtml',
            'application/excel'=>'xl',
            'application/msexcel'=>'xls',
            'application/x-msexcel'=>'xls',
            'application/x-ms-excel'=>'xls',
            'application/x-excel'=>'xls',
            'application/x-dos_ms_excel'=>'xls',
            'application/xls'=>'xls',
            'application/x-xls'=>'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'=>'xlsx',
            'application/vnd.ms-excel'=>'xlsx',
            'application/xml'=>'xml',
            'text/xml'=>'xml',
            'text/xsl'=>'xsl',
            'application/xspf+xml'=>'xspf',
            'application/x-compress'=>'z',
            'application/x-zip'=>'zip',
            'application/zip'=>'zip',
            'application/x-zip-compressed'=>'zip',
            'application/s-compressed'=>'zip',
            'multipart/x-zip'=>'zip',
            'text/x-scriptzsh'=>'zsh',
            'application/vnd.oasis.opendocument.text'=>'odt',
            'application/vnd.oasis.opendocument.spreadsheet'=>'ods',
            'application/vnd.oasis.opendocument.presentation'=>'odp',
        ];
        return isset($mime_map[$mime]) === true ? $mime_map[$mime] : false;
    }

    function _logSMS($data){
        $column = array("waba", "ph_number", "text_body", "type", "message_id", "message_type", "log_type");
        $values = array($data['caller']['WABA'], $data['data']['from'], $data['data']['body'], $data['data']['type'], $data['data']['msgID'], "R", 'W');
        if(array_key_exists('file_id',$data['data'])){
            array_push($column, "file_id");
            array_push($values, $data['data']['file_id']);
        }
        $write = new putter_db();
        $status = $write->write(messageLog, $column, $values);
    }

    function getIntentByLlama($text) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => LLAMA_API_URL,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{"text":"'.$text.'"}',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    function getBadrockRes($data) {
        $curl = curl_init();

        $jsonText = array(
            "text" => $data['body'],
            "session_id" => $data['from']
        );

        curl_setopt_array($curl, array(
          CURLOPT_URL => LLAMA_API_URL.'/generate',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($jsonText),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function processIntentData($result,$data) {
        $res = json_decode($result,true);

        $column = array("waba", "ph_number", );
        $values = array($data['caller']['WABA'], $data['data']['from']);
        if(array_key_exists('intent',$res)) {
            array_push($column, "intent");
            array_push($values, $res['intent']);
        }
        if(array_key_exists('req_entity',$res) && $res['req_entity'] !='') {
            array_push($column, "req_entity");
            $reqString = json_encode($res['req_entity']);
            echo $reqString = str_replace("\\n","",$reqString);
            array_push($values, $reqString);
            if(count($res['req_entity']) > 0) {
                array_push($column, "form");
                array_push($values, 1);

                array_push($column, "start");
                array_push($values, 0);

                array_push($column, "end");
                array_push($values, count($res['req_entity'])-1);

                $data['message'][0] = array(
                    "type"=>"text",
                    "message_content" => $res['req_entity'][0]['message']
                );
            }
        } else {
            $data['message'][0] = array(
                "type"=>"text",
                "message_content" => "Not understand your concern please type again"
            );
        }
        $write = new putter_db();
        $status = $write->write(userAction, $column, $values);

        return $data;
    }

    function openWebUiResponse($data) {
        $curl = curl_init();
        $query = "SELECT * FROM `wa_messages` WHERE `ph_number` = '".$data['from']."' AND `state` = 1 LIMIT 50";
        $reader = new getter_db();
        $querynData = $reader->read_query($query);
        $files=[];
        $conversations=[];
        $knowlegbases=["4a25a7c5-fff9-438a-91de-72e0ebdc3c33"];
        foreach($knowlegbases as $knowlege){
            $files[]=[
                "type"=>"collection",
                "id"=>$knowlege
            ];
        }
        foreach($querynData as $msg){
            if($msg['type']=='text'){
                $conversations[]=[
                    "role"=>($msg['message_type']=='S')? "assistant": "user",
                    "content"=>$msg['text_body']
                ];
            }
            if($msg['type']=='document' && !empty($msg['file_id'])){
                $files[]=[
                    "type"=>"file",
                    "id"=>$msg['file_id']
                ];
            }
        }

        $conversations[]=[
            "role"=>"user",
            "content"=>$data['body']
        ];
        $jsonText = array(
            "model" => "meta.llama3-1-70b-instruct-v1:0",
            "messages" =>$conversations
        );

        if(!empty($files)){
            $jsonText['files']=$files;
        }

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://llamachat.zeapl.com/api/chat/completions',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($jsonText),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: '.OPEN_WEBUI_AUTH_TOKEN
          ),
        ));

        $response = curl_exec($curl);

        $json =json_encode($jsonText);
        file_put_contents(realpath(__DIR__).'/logs/bedrock_'.date("j_n_Y").'.txt',  "TIME: ".date("d/m/Y h:i:s").PHP_EOL."REQUEST: ".$json.PHP_EOL."RESPONSE: ".$response.PHP_EOL.PHP_EOL, FILE_APPEND);
        curl_close($curl);
        return $response;
    }

    function downloadFile($url) {
        // open file in rb mode
        if ($fp_remote = fopen($url, 'rb')) {
            // local filename
            $file_parts = explode(".",basename($url));
            $file_name = $file_parts[0]."-".time().".".$file_parts[1];
            $local_file = realpath(__DIR__ ).'/downloads/'. $file_name;
            // read buffer, open in wb mode for writing
            if ($fp_local = fopen($local_file, 'wb')) {
                // read the file, buffer size 8k
                while ($buffer = fread($fp_remote, 8192)) {
                    fwrite($fp_local, $buffer);
                }
                // close local
                fclose($fp_local);
            } else {
                // could not open the local URL
                fclose($fp_remote);
                return false;    
            }
            // close remote
            fclose($fp_remote);
            return $local_file;
        } else {
            // could not open the remote URL
            return false;
        }
    }

    function clear($decodeData){
        if(strtolower($decodeData['data']['body'])!='clear') return false;
        $write = new putter_db();
        $query = "UPDATE `wa_messages` SET `state` = 0 WHERE `ph_number`= '".$decodeData['data']['from']."'";
        $write->write_query($query);
        $decodeData['message'][0] = array(
            "type"=>"text",
            "message_content" => "Your conversation session has been cleared. You can now start a new session."
        );
        $decodeData['data']['clear'] = true;
        $sendMessage = new sender();
        $sendMessage->send($decodeData);
        return true;
    }

    function speechToText($decodeData) {
        $local_path=$this->downloadFile($decodeData['data']['body']);
        $get_name = explode("/",$local_path); 
        $file_name = end($get_name);
        $new_file_name = str_replace("ogg", "wav", $file_name);
        $new_file_path = realpath(__DIR__ ).'/downloads/'.$new_file_name;
        exec("ffmpeg -i ".$local_path." ".$new_file_path);
        sleep(2);

        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.sarvam.ai/speech-to-text-translate',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('model' => 'saaras:v1','file'=> new CURLFILE($new_file_path,'audio/wav')),
              CURLOPT_HTTPHEADER => array(
                'api-subscription-key: d25628ce-1eb0-4292-94c9-31a15a49a258'
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return json_decode($response,true);
        } catch (Exception $e) {
            // Handle any exceptions that occurred during the execution
            print_r(['error' => $e->getMessage()]);
        } finally {
            // Removing file in case of success or exception
            unlink($local_path);
            unlink($new_file_path);
        }
    }

    function saveWarranty($data) {
        $column = array("whatsapp_number", "purchase_date", "purchase_from", "stencil_number", "tyre_type");
        $values = array($data['data']['from'], date('Y-m-d',substr($data['data']['nfm_reply_res']['screen_0_DatePicker_0'],0,-3)), $data['data']['nfm_reply_res']['screen_0_TextInput_1'], $data['data']['nfm_reply_res']['screen_0_TextInput_2'], $data['data']['nfm_reply_res']['screen_0_TextInput_3']);
        $write = new putter_db();
        $status = $write->write('warranty', $column, $values);

        return "Your warranty registration has been successful";
    }

    function saveTyreReminder($data) {
        $column = array("whatsapp_number", "car_model", "tyre_type", "maintenance_date", "monthly_mileage");
        $tyre_type = explode("_", $data['data']['nfm_reply_res']['screen_0_Dropdown_1'])[1];
        $date = date('Y-m-d',substr($data['data']['nfm_reply_res']['screen_0_DatePicker_2'],0,-3));
        $values = array($data['data']['from'], $data['data']['nfm_reply_res']['screen_0_TextInput_0'], $tyre_type, $date, $data['data']['nfm_reply_res']['screen_0_TextInput_3']);
        $write = new putter_db();
        $status = $write->write('tyre_maintenance_reminder', $column, $values);

        return "Your next maintenance reminder date is ".date('Y-m-d',strtotime($date."+4 months"));
    }

    function saveNearestTyreLocator($data) {
        $column = array("whatsapp_number", "pincode", "service", "response_type", "response");
        $pincode = $data['data']['nfm_reply_res']['screen_0_TextInput_0'];
        $service = explode("_", $data['data']['nfm_reply_res']['screen_0_Dropdown_1'])[1];
        $response_type = explode("_", $data['data']['nfm_reply_res']['screen_0_Dropdown_2'])[1];

        $query = "SELECT * FROM locations WHERE pincode = '".$pincode."' ORDER BY id DESC LIMIT 1";
        $reader = new getter_db();
        $result = $reader->read_query($query);
        $message = $result[0][$response_type];

        $values = array($data['data']['from'], $pincode, $service,$response_type,$message);
        $write = new putter_db();
        $status = $write->write('user_log_nearest_locator', $column, $values);
        return $message;
    }

}
 ?>