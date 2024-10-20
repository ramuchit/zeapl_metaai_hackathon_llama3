<?php
class sender {
    function __construct() {
        require_once dirname(__FILE__).'/putter_db.php';
    }
    function send($data,$log=1){
        //Wrapper to endoe all the sms to be sent
        $wrapEncode = new wrapper();
        $EncodedData = $wrapEncode->wrap($data, ENCODE);

        $messageCount = count($EncodedData);
        //Now sending the
        foreach ($EncodedData as $key => $value) {
            $messageCount--;
            $sendingData = json_encode($value);
            $response = $this->sendWhatsapp($sendingData);
            // echo "<pre>"; print_r($response); die;

            if(array_key_exists('messages', $response)){
                $logdata['message_id'] = $response['messages'][0]['id'];
                $logdata['server_code'] = "200";
                $logdata['server_status'] = "SUCCESS";
            } else {
                if(array_key_exists('error', $response)) {
                    $logdata['server_code'] = $response['error']['code'];
                    $logdata['server_status'] = $response['error']['message']." - ".$response['error']['error_data']['details'];
                } else if(array_key_exists('error', $response['message'])) {
                    $logdata['server_code'] = $response['message']['error']['code'];
                    $logdata['server_status'] = $response['message']['error']['message']." - ".$response['message']['error']['error_data']['details'];
                } else {
                    $logdata['server_code'] = $response['code'];
                    $logdata['server_status'] = $response['message'];
                }
            }
            
            $logdata['waba'] = $data['caller']['WABA'];
            $logdata['to'] = $data['data']['from'];
            $logdata['text_body'] = $data['message'][0]['message_content'];
            $logdata['type'] = $data['message'][$key]['type'];
            $logdata['message_type'] = "S";
            if(array_key_exists('clear',$data['data']) && $data['data']['clear']===true){
                $logdata['clear'] = true;
            }

      
            //Logging the message with response
            if($log) {
                $this->_logSMS($logdata);
            }
            if($messageCount > 0 && array_key_exists("wait", $data['actionData']['action_data'])) {
                sleep($data['actionData']['action_data']["wait"]);
            }
        }
    }

    function sendWhatsapp($data_string){
        $startTime = microtime(true);
        $request_headers = array(
          "Content-Type: application/json",
          "Authorization: ".DIRECT_CLOUD_WABA_AUTH_TOKEN
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, DIRECT_CLOUD_WABA_BASE_URL.DIRECT_CLOUD_WABA_API_VERSION.DIRECT_CLOUD_PHONE_NUMBER_ID."/messages");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        if($data_string) {
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        $time_elapsed_secs = microtime(true) - $startTime;
        //Calling sms sender helper file to send sms incase the time exceeds given time
        if(WHATSAPP_API_CALL_TIME_EXCEED <= $time_elapsed_secs){
            $event = new eventTrigger();
            $response = $event->trigger("1", $data_string);
            file_put_contents(realpath(__DIR__).'/logs/Error_log_Direct_Cloud_Whatsapp_API'.date("j_n_Y").'.txt', "TIME: ".date("d/m/Y h:i:s").PHP_EOL."API TAKING TOO LONG: ".$time_elapsed_secs.PHP_EOL."API URL: ".$url.PHP_EOL."MESSAGE ID: ".array_key_exists(json_decode($result, true)['']).PHP_EOL."----------".PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents(realpath(__DIR__).'/logs/Success_log_Direct_Cloud_Whatsapp_API'.date("j_n_Y").'.txt', "TIME: ".date("d/m/Y h:i:s").PHP_EOL."START TIME:".$startTime.PHP_EOL."API TOTAL CALL TIME: ".$time_elapsed_secs.PHP_EOL.$data_string.PHP_EOL.$result.PHP_EOL."----------".PHP_EOL, FILE_APPEND);
        }
        return json_decode($result, true);
    }

    function _logSMS($data){
        $column = array("waba", "ph_number", "text_body", "type", "message_type", "log_type", "server_code", "server_status");
        $values = array($data['waba'], $data['to'], $data['text_body'], $data['type'], $data['message_type'],"A", $data['server_code'], $data['server_status']);

        if(array_key_exists('message_id',$data)){
            array_push($column, "message_id");
            array_push($values, $data['message_id']);
        }
        if(array_key_exists('clear',$data) && $data['clear']===true){
            array_push($column, "state");
            array_push($values, 0);
        }

        $write = new putter_db();
        $status = $write->write(messageLog, $column, $values);
    }
}
?>
