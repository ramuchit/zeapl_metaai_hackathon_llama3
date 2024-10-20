<?php
class getter_db {
  private $con;
  function __construct() {
    require_once dirname(__FILE__).'/db_connect.php';
    $db = new db_connect();
    $this->con = $db->connect_read();
  }

  function read_where($table_name, $where_column, $where_key) {
    if (count($where_column) == count($where_key)) {
      $sql = "SELECT * FROM $table_name WHERE ";
      for($a = 0; $a < count($where_column); $a++) {
        $sql = $sql.$where_column[$a]." = '".$where_key[$a]."'";
        if ($a < count($where_column)-1) {
          $sql = $sql." AND ";
        }
      }
      $stmt = $this->con->query($sql);
      if($stmt){
        $data = $stmt->fetch_all(1);
        $this->con->close();
        return $data;
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        $this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      $this->con->close();
      return "false";
    }
  }

  function read_columns_where($table_name, $columns, $where_column, $where_key) {
    if (count($where_column) == count($where_key)) {
      $sql = "SELECT ";
      for($a = 0; $a < count($columns); $a++) {
        $sql = $sql.$columns[$a];
        if ($a < count($columns)-1) {
          $sql = $sql.",";
        }
      }
      $sql = $sql." FROM $table_name WHERE ";

      for($a = 0; $a < count($where_column); $a++) {
        $sql = $sql.$where_column[$a]." = '".$where_key[$a]."'";
        if ($a < count($where_column)-1) {
          $sql = $sql." AND ";
        }
      }
      $stmt = $this->con->query($sql);
      if($stmt){
        $data = $stmt->fetch_all(1);
        $this->con->close();
        return $data;
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        $this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      $this->con->close();
      return "false";
    }
  }

  function read_where_desc($table_name, $where_column, $where_key, $order_by, $limit) {
    if (count($where_column) == count($where_key)) {
      $sql = "SELECT * FROM $table_name WHERE ";
      for($a = 0; $a < count($where_column); $a++) {
        $sql = $sql.$where_column[$a]." = '".$where_key[$a]."'";
        if ($a < count($where_column)-1) {
          $sql = $sql." AND ";
        }
      }
      $sql = $sql." ORDER BY $order_by DESC LIMIT $limit";
      $stmt = $this->con->query($sql);
      if($stmt){
        $data = $stmt->fetch_all(1);
        $this->con->close();
        return $data;
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        $this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      $this->con->close();
      return "false";
    }
  }

  function read_where_asc($table_name, $where_column, $where_key, $order_by, $limit) {
    if (count($where_column) == count($where_key)) {
      $sql = "SELECT * FROM $table_name WHERE ";
      for($a = 0; $a < count($where_column); $a++) {
        $sql = $sql.$where_column[$a]." = '".$where_key[$a]."'";
        if ($a < count($where_column)-1) {
          $sql = $sql." AND ";
        }
      }
      $sql = $sql." ORDER BY $order_by ASC LIMIT $limit";
      $stmt = $this->con->query($sql);
      if($stmt){
        $data = $stmt->fetch_all(1);
        $this->con->close();
        return $data;
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        $this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      $this->con->close();
      return "false";
    }
  }

  function read_where_symbol($table_name, $where_column, $where_key, $symbol) {
    if (count($where_column) == count($where_key) && count($where_key) == count($symbol)) {
      $sql = "SELECT * FROM $table_name WHERE ";
      for($a = 0; $a < count($where_column); $a++) {
        $sql = $sql.$where_column[$a]." ".$symbol[$a]." '".$where_key[$a]."'";
        if ($a < count($where_column)-1) {
          $sql = $sql." AND ";
        }
      }
      $stmt = $this->con->query($sql);
      if($stmt){
        $data = $stmt->fetch_all(1);
        $this->con->close();
        return $data;
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        $this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      $this->con->close();
      return "false";
    }
  }

  function read_columns_where_symbol_type($table_name, $columns, $where_column, $where_key, $symbol, $type) {
    if (count($where_column) == count($where_key)) {
      $sql = "SELECT ";
      for($a = 0; $a < count($columns); $a++) {
        $sql = $sql.$columns[$a];
        if ($a < count($columns)-1) {
          $sql = $sql.",";
        }
      }
      $sql = $sql." FROM $table_name WHERE ";

      for($a = 0; $a < count($where_column); $a++) {
        $sql = $sql.$where_column[$a]." ".$symbol[$a]." '".$where_key[$a]."'";
        if ($a < count($where_column)-1) {
          $sql = $sql." ".$type[$a]." ";
        }
      }
      $stmt = $this->con->query($sql);
      if($stmt){
        $data = $stmt->fetch_all(1);
        $this->con->close();
        return $data;
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL."Query".$sql.PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        $this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      $this->con->close();
      return "false";
    }
  }

  function read_query($query){
    $stmt = $this->con->query($query);
    if($stmt){
      $data = $stmt->fetch_all(1);
      $this->con->close();
      return $data;
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Error: ".$this->con->error.PHP_EOL."Query: ".$query.PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      $this->con->close();
      return "false";
    }
  }

  function read_multi_query($query){
    $stmt = $this->con->multi_query($query);
    if($stmt){
      $data = array();
      do {
          if ($result = $this->con->store_result()) {
            $data[] = $result->fetch_all(1);
            $result->free();
          }
      } while ($this->con->next_result());
      $this->con->close();
      return $data;
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Error: ".$this->con->error.PHP_EOL."Query: ".$query.PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      $this->con->close();
      return "false";
    }
  }

  function write_error($log){
    file_put_contents(realpath(__DIR__).'/logs/errorlog_'.date("j_n_Y").'.txt', $log, FILE_APPEND);
  }
}
?>
