<?php
class putter_db {
  private $con;
  public $affected_rows;
  function __construct() {
    require_once dirname(__FILE__).'/db_connect.php';
    $db = new db_connect();
    $this->con = $db->connect();
  }

  function write($table_name, $table_columns, $table_values, $ignore = false) {
    if (count($table_columns) == count($table_values)) {
      $sql = "INSERT".($ignore?" IGNORE":"")." INTO $table_name";
      $sql = $sql."(";
      for($a = 0; $a < count($table_columns); $a++) {
        $sql = $sql.$table_columns[$a];
        if ($a < count($table_columns)-1) {
          $sql = $sql.", ";
        }
      }
      $sql = $sql.") VALUES(";
      for($a = 0; $a < count($table_values); $a++) {
        $sql = $sql."'".str_replace("'","\'",$table_values[$a])."'";
        if ($a < count($table_values)-1) {
          $sql = $sql.", ";
        }
      }
      $sql = $sql.")";
      $stmt = $this->con->query($sql);
      if($stmt){
        $this->affected_rows = $this->con->affected_rows;
        //$this->con->close();
        return "true";
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Column: ".implode(", ",$table_columns).PHP_EOL."Column Data: ".implode(", ",$table_values).PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        //$this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Column: ".implode(", ",$table_columns).PHP_EOL."Column Data: ".implode(", ",$table_values).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      //$this->con->close();
      return "false";
    }
  }

  function writeUpdate($table_name, $table_columns, $table_values, $ignore = false) {
    if (count($table_columns) == count($table_values)) {
      $sql = "INSERT".($ignore?" IGNORE":"")." INTO $table_name";
      $usql = " ON DUPLICATE KEY UPDATE ";
      $sql = $sql."(";
      for($a = 0; $a < count($table_columns); $a++) {
        $sql = $sql.$table_columns[$a];
        $usql = $usql." ".$table_columns[$a]." = VALUES(".$table_columns[$a].")";
        if ($a < count($table_columns)-1) {
          $sql = $sql.", ";
          $usql = $usql.", ";
        }
      }
      $sql = $sql.") VALUES(";
      for($a = 0; $a < count($table_values); $a++) {
        $sql = $sql."'".str_replace("'","\'",$table_values[$a])."'";
        if ($a < count($table_values)-1) {
          $sql = $sql.", ";
        }
      }
      $sql = $sql.")";
      $sql = $sql.$usql;
      $stmt = $this->con->query($sql);
      if($stmt){
        $this->affected_rows = $this->con->affected_rows;
        //$this->con->close();
        return "true";
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Column: ".implode(", ",$table_columns).PHP_EOL."Column Data: ".implode(", ",$table_values).PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        //$this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Column: ".implode(", ",$table_columns).PHP_EOL."Column Data: ".implode(", ",$table_values).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      //$this->con->close();
      return "false";
    }
  }

  function writeReturnID($table_name, $table_columns, $table_values, $ignore = false) {
    if (count($table_columns) == count($table_values)) {
      $sql = "INSERT".($ignore?" IGNORE":"")." INTO $table_name";
      $sql = $sql."(";
      for($a = 0; $a < count($table_columns); $a++) {
        $sql = $sql.$table_columns[$a];
        if ($a < count($table_columns)-1) {
          $sql = $sql.", ";
        }
      }
      $sql = $sql.") VALUES(";
      for($a = 0; $a < count($table_values); $a++) {
        $sql = $sql."'".str_replace("'","\'",$table_values[$a])."'";
        if ($a < count($table_values)-1) {
          $sql = $sql.", ";
        }
      }
      $sql = $sql.")";
      $stmt = $this->con->query($sql);
      if($stmt){
        $last_id = $this->con->insert_id;
        //$this->con->close();
        return $last_id;
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Column: ".implode(", ",$table_columns).PHP_EOL."Column Data: ".implode(", ",$table_values).PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        //$this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Column: ".implode(", ",$table_columns).PHP_EOL."Column Data: ".implode(", ",$table_values).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      //$this->con->close();
      return "false";
    }
  }

  function update($table_name, $q_table_columns, $q_table_values, $table_columns, $table_values) {
    if (count($table_columns) == count($table_values)) {
      $sql = "UPDATE $table_name SET ";
      for($a = 0; $a < count($table_columns); $a++) {
        $sql = $sql."`".$table_columns[$a]."` = "."'".str_replace("'","\'",$table_values[$a])."'";
        if ($a < count($table_columns)-1) {
          $sql = $sql.", ";
        }
      }
      $sql = $sql." WHERE ";
      for($a = 0; $a < count($q_table_columns); $a++) {
        $sql = $sql."`".$q_table_columns[$a]."` = "."'".str_replace("'","\'",$q_table_values[$a])."'";
        if ($a < count($q_table_columns)-1) {
          $sql = $sql." AND ";
        }
      }
      $stmt = $this->con->query($sql);
      if($stmt){
        $this->affected_rows = $this->con->affected_rows;
        //$this->con->close();
        return "true";
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Column: ".implode(", ",$table_columns).PHP_EOL."Column Data: ".implode(", ",$table_values).PHP_EOL."SQL query: ".$sql.PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        //$this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Column: ".implode(", ",$table_columns).PHP_EOL."Column Data: ".implode(", ",$table_values).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      //$this->con->close();
      return "false";
    }
  }

  function updateORDERDSCLIMT($table_name, $q_table_columns, $q_table_values, $table_columns, $table_values, $order_by, $limit) {
    if (count($table_columns) == count($table_values)) {
      $sql = "UPDATE $table_name SET ";
      for($a = 0; $a < count($table_columns); $a++) {
        $sql = $sql."`".$table_columns[$a]."` = "."'".str_replace("'","\'",$table_values[$a])."'";
        if ($a < count($table_columns)-1) {
          $sql = $sql.", ";
        }
      }
      $sql = $sql." WHERE ";
      for($a = 0; $a < count($q_table_columns); $a++) {
        $sql = $sql."`".$q_table_columns[$a]."` = "."'".str_replace("'","\'",$q_table_values[$a])."'";
        if ($a < count($q_table_columns)-1) {
          $sql = $sql."AND ";
        }
      }
      $sql = $sql." ORDER BY $order_by DESC LIMIT $limit";
      $stmt = $this->con->query($sql);
      if($stmt){
        $this->affected_rows = $this->con->affected_rows;
        //$this->con->close();
        return "true";
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Column: ".implode(", ",$table_columns).PHP_EOL."Column Data: ".implode(", ",$table_values).PHP_EOL."SQL query: ".$sql.PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        //$this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Column: ".implode(", ",$table_columns).PHP_EOL."Column Data: ".implode(", ",$table_values).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      //$this->con->close();
      return "false";
    }
  }

  function transaction_write($table_name, $table_columns, $table_values, $type, $p_key) {
    if (count($table_columns) == count($table_values)) {
      $sql = "INSERT INTO db_transaction (`table_name`,	`table_column`,	`table_value`, `type`, `p_key`) VALUES";
      for($a = 0; $a < count($table_columns); $a++) {
        $sql = $sql."('".$table_name."','".$table_columns[$a]."','".str_replace("'","\'",$table_values[$a])."','".$type."','".$p_key."')";
        if ($a < count($table_columns)-1) {
          $sql = $sql.", ";
        }
      }
      $stmt = $this->con->query($sql);
      if($stmt){
        $this->affected_rows = $this->con->affected_rows;
        //$this->con->close();
        return "true";
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Column: ".implode(", ",$table_columns).PHP_EOL."Column Data: ".implode(", ",$table_values).PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        //$this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Column: ".implode(", ",$table_columns).PHP_EOL."Column Data: ".implode(", ",$table_values).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      //$this->con->close();
      return "false";
    }
  }

  function write_query($query){
    $stmt = $this->con->query($query);
    if($stmt){
      $this->affected_rows = $this->con->affected_rows;
      //$this->con->close();
      return "true";
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Error: ".$this->con->error.PHP_EOL."Query: ".$query.PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      //$this->con->close();
      return "false";
    }
  }

  /* Getter DB functions */

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
        //$this->con->close();
        return $data;
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        //$this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      //$this->con->close();
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
        //$this->con->close();
        return $data;
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        //$this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      //$this->con->close();
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
        //$this->con->close();
        return $data;
      } else {
        $log_Time = date("H:i:s")." IST ".date("Y-m-d");
        $log_msg = "Error: ".$this->con->error.PHP_EOL."Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
        $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
        $this->write_error($log);
        //$this->con->close();
        return "false";
      }
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Table name: ".$table_name.PHP_EOL."Query Column: ".implode(", ",$where_column).PHP_EOL."Query Data: ".implode(", ",$where_key).PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      //$this->con->close();
      return "false";
    }
  }

  function read_query($query){
    $stmt = $this->con->query($query);
    if($stmt){
      $data = $stmt->fetch_all(1);
      //$this->con->close();
      return $data;
    } else {
      $log_Time = date("H:i:s")." IST ".date("Y-m-d");
      $log_msg = "Error: ".$this->con->error.PHP_EOL."Query: ".$query.PHP_EOL;
      $log = "Log Time: ".$log_Time.PHP_EOL.$log_msg.PHP_EOL."-------------------------".PHP_EOL;
      $this->write_error($log);
      //$this->con->close();
      return "false";
    }
  }

  function write_error($log){
    file_put_contents(realpath(__DIR__).'/logs/errorlog_'.date("j_n_Y").'.txt', $log, FILE_APPEND);
  }
}
?>
