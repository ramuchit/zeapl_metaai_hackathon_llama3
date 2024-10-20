<?php
class db_connect{
    private $con;
    private $wct,$rct = 0;

    function connect() {
        global $wdb;
        $this->wct++;
        $this->con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        mysqli_set_charset($this->con,"utf8");

        if(mysqli_connect_errno() ){
            if($this->wct > 1) {
                $log_Time = date("H:i:s")." IST ".date("Y-m-d");
                $log = "Log Time: ".$log_Time." - Error: Connecting database in DbConnect.php - Attempt No (w): ".$this->wct." -> ".mysqli_connect_error()
                .PHP_EOL."DB HOST: ".DB_HOST.PHP_EOL."DB USER: ".DB_USER.PHP_EOL."DB NAME: ".DB_NAME.PHP_EOL."-------------------------".PHP_EOL;
                file_put_contents('logs/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
                //echo "E404";  //Error contacting database

                //calling mailing
                $jsonArray['to'] = ALERTEMAIL;
                $jsonArray['subject'] = "Event - DB Connection";
                $jsonArray['body'] = $log."\n\n";

                //Now calling CURL for error SMS sending
                $networkCall = new network();
                $output = $networkCall->apiCaller(ERROR_MAIL_URL, json_encode($jsonArray));
            }

            if($this->wct<5){
                sleep(1);
                return $this->connect();
            }   
        }else {
            $wdb = $this->con;
            return $wdb;
        }
    }

    function connect_read() {
        global $rdb;
        $this->rct++;
        $this->con = new mysqli(DB_HOST_READ, DB_USER_READ, DB_PASSWORD_READ, DB_NAME);
        mysqli_set_charset($this->con,"utf8");

        if(mysqli_connect_errno()){
            if($this->rct > 1) {
                $log_Time = date("H:i:s")." IST ".date("Y-m-d");
                $log = "Log Time: ".$log_Time." - Error: Connecting database in DbConnect.php - Attempt No: (r)".$this->rct." -> ".mysqli_connect_error()
                .PHP_EOL."DB HOST: ".DB_HOST.PHP_EOL."DB USER: ".DB_USER.PHP_EOL."DB NAME: ".DB_NAME.PHP_EOL."-------------------------".PHP_EOL;
                file_put_contents('logs/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
                //echo "E404";  //Error contacting database

                //calling mailing
                $jsonArray['to'] = ALERTEMAIL;
                $jsonArray['subject'] = "Event - DB Connection";
                $jsonArray['body'] = $log."\n\n";

                //Now calling CURL for error SMS sending
                $networkCall = new network();
                $output = $networkCall->apiCaller(ERROR_MAIL_URL, json_encode($jsonArray));
            }
            if($this->rct<5){
                sleep(1);
                return $this->connect();
            }
        }else {
            $rdb = $this->con;
            return $rdb;
        }
    }
}
?>
