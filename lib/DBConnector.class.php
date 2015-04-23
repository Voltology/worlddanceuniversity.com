<?php
class DBConnector {
  private static $instance;
  public function __construct($host, $user, $password, $db) {
    if (self::$instance) {
      exit("Instance on DBConnection already exists.");
    }
  }
  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new MySQLi(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }
    return self::$instance;
  }
}
?>
