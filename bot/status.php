<?php

    $ini_array = parse_ini_file('.env');
    foreach ($ini_array as $key => $value) {
      define($key, $value);
    }

    class statusUpdate {
        function __construct(){
          require_once dirname(__FILE__).'/localconfig.php';
          require_once dirname(__FILE__).'/wrapper.php';
          require_once dirname(__FILE__).'/putter_db.php';
        }

        function updateStatus($data){
            $recievedData = json_decode($data, true);

            //Wrapper to decode
            $wrapDecode = new wrapper();
            $decodeData = $wrapDecode->wrap($recievedData, DECODE);

            foreach ($decodeData['data'] as $key => $value) {
                $logger['message_id'] = $value['message_id'];
                $logger['status'] = strtoupper($value['status'])[0];
                $logger['time'] = date('Y-m-d H:i:s', $value['timestamp']);
                $logger['ph_number'] = $value['recipient_id'];

                if(in_array($logger['status'],array('S','R','D','F'))) {
                    $q_table_columns = array("message_id");
                    $q_table_values = array($value['message_id']);
                    switch($logger['status']){
                        case 'F':
                            $stable_columns = array("server_code", "server_status");
                            $stable_values = array($value['message_code'], $value['message']);
                            
                            $table_columns = array("error_code");
                            $table_values = array($value['message_code']);
                            break;
                        case 'S':
                            $stable_columns = array("sent_date_time","sent_status");
                            $stable_values = array($logger['time'],"1");

                            $table_columns = array("error_code","sent_date_time","sent_status");
                            $table_values = array("200",$logger['time'],"1");
                            break;
                        case 'D':
                            $stable_columns = array("deliver_date_time","deliver_status");
                            $stable_values = array($logger['time'],"1");

                            $table_columns = array("error_code","deliver_date_time","deliver_status");
                            $table_values = array("200",$logger['time'],"1");
                            break;
                        case 'R':
                            $stable_columns = array("read_date_time","read_status");
                            $stable_values = array($logger['time'],"1");

                            $table_columns = array("error_code","read_date_time","read_status");
                            $table_values = array("200",$logger['time'],"1");
                            break;
                        default:
                            break;
                    }
                    $write = new putter_db();
                    $write->update(messageLog, $q_table_columns, $q_table_values, $stable_columns, $stable_values);
                    break;
                }
            }
            return ;
        }
    }
?>
