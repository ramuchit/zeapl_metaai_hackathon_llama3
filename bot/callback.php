<?php
ob_start();
$size = ob_get_length();
header("Content-Length: $size");
header('Connection: close');
ob_end_flush();
ob_flush();
flush();
if (session_id())
    session_write_close();

$json = file_get_contents("php://input");
file_put_contents(realpath(__DIR__).'/logs/responseLog_8447799182_'.date("j_n_Y").'.txt',  "TIME: ".date("d/m/Y h:i:s").PHP_EOL.$json.PHP_EOL.PHP_EOL, FILE_APPEND);

if($_SERVER['REQUEST_METHOD']=='GET')
    $json = $_REQUEST['data'];
$data = json_decode($json, true);

if(array_key_exists("entry", $data)) {
    $data = $data["entry"][0]["changes"][0]["value"];
    if(array_key_exists("metadata", $data) && array_key_exists("display_phone_number", $data["metadata"]) && $data["metadata"]["display_phone_number"] == "918447799182") {
        if(array_key_exists("messages",$data)){
            //MESSAGE RECIEVED
            $dr = array('WABA'=>"8447799182", 'DECODE'=>'DCW01', 'ENCODE'=>'DCW01', 'log_type'=>'W');
            $data['caller'] = $dr;
            require_once dirname(__FILE__).'/process.php';
            $read = new process();
            $read->reciever($data);
        } else if (array_key_exists("statuses",$data)){
            //STATUS UPDATE
            require_once dirname(__FILE__).'/status.php';
            $dr = array('WABA'=>"8447799182", 'DECODE'=>'DCW01', 'ENCODE'=>'DCW01', 'log_type'=>'W');
            $data['caller'] = $dr;
            $read1 = new statusUpdate();
            $read1->updateStatus(json_encode($data));
        }
    }
}
?>
