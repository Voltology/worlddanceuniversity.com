<?php
class User {
  private $_db;
  private $_id;
  private $_token;
  private $_paypal_token;
  private $_paypal_status;
  private $_email;
  private $_password;
  private $_firstname;
  private $_lastname;
  private $_role;
  private $_isloggedin;
  private $_archived;
  private $_creation;

  function __construct($db = null, $email = null, $password = null) {
    $this->_db = $db;
    if ($email && $password) {
      $this->_email = $email;
      if ($this->checkPassword($password)) {
        $this->_isloggedin = true;
        $this->_password = $password;
        $this->set();
        $this->recordLogin();
      }
    } else {
      $this->_isloggedin = false;
    }
  }

  public function add() {
    global $mysqli;
    $query = sprintf("INSERT INTO users SET token='%s', paypal_token='%s', paypal_status='New', email='%s', password='%s', firstname='%s', lastname='%s', role='%s', creation='%s'",
      $mysqli->real_escape_string($this->_token),
      $mysqli->real_escape_string($this->_paypal_token),
      $mysqli->real_escape_string($this->_email),
      $mysqli->real_escape_string($this->_password),
      $mysqli->real_escape_string($this->_firstname),
      $mysqli->real_escape_string($this->_lastname),
      $mysqli->real_escape_string($this->_role),
      $mysqli->real_escape_string(time()));
    $result = $mysqli->query($query);
    $this->_id = $mysqli->insert_id;
    return $this->_id;
  }

  public function changePassword($newpassword) {
    $this->_password = $newpassword;
    $query = sprintf("UPDATE users SET password='%s' WHERE id='%s' LIMIT 1",
      $this->_db->real_escape_string($newpassword),
      $this->_db->real_escape_string($this->_id));
    $result = $this->_db->query($query);
  }

  public function checkPassword($password) {
    $query = sprintf("SELECT id FROM users WHERE email='%s' AND password='%s' ORDER BY id DESC LIMIT 1",
      $this->_db->real_escape_string($this->_email),
      $this->_db->real_escape_string(md5($password)));
    $result = $this->_db->query($query);
    if (!$result->num_rows) {
      return false;
    } else {
      return true;
    }
  }

  public function checkToken($token) {
    if ($token === $this->_token) {
      return true;
    } else {
      return false;
    }
  }

  public function delete($id) {
    $query = sprintf("UPDATE users SET archived='1', paypal_status='Archived' WHERE id='%s' LIMIT 1",
      $this->_db->real_escape_string($id));
    $result = $this->_db->query($query);
    if (!$result) {
      return false;
    } else {
      return true;
    }
  }

  public static function getAll() {
    global $mysqli;
    $users = array();
    $query = sprintf("SELECT users.id,token,paypal_token,paypal_status,email,firstname,lastname,roles.role AS role,creation FROM users LEFT JOIN roles ON users.role=roles.id WHERE users.archived=0 ORDER BY creation DESC");
    $result = $mysqli->query($query);
    while ($user = $result->fetch_array(MYSQLI_ASSOC)) {
      array_push($users, $user);
    }
    return $users;
  }

  public function getArchived() {
    return $this->_archived;
  }

  public function getById($id) {
    global $mysqli;
    $query = sprintf("SELECT * FROM users WHERE id='%s' LIMIT 1",
      $mysqli->real_escape_string($id));
    $result = $mysqli->query($query);
    if (!$result) {
      return false;
    } else {
      $user = $result->fetch_assoc();
      $this->_id = $user['id'];
      $this->_token = $user['token'];
      $this->_paypal_token = $user['paypal_token'];
      $this->_paypal_status = $user['paypal_status'];
      $this->_firstname = $user['firstname'];
      $this->_lastname = $user['lastname'];
      $this->_email = $user['email'];
      $this->_role = $user['role'];
      $this->_archived = $user['archived'];
      $this->_creation = $user['creation'];
      return true;
    }
  }

  public function getCreation() {
    return $this->_creation;
  }

  public function getFirstName() {
    return $this->_firstname;
  }

  public function getEmail() {
    return $this->_email;
  }

  public function getIsLoggedIn() {
    return $this->_isloggedin;
  }

  public function getLastName() {
    return $this->_lastname;
  }

  public function getPaypalStatus() {
    return $this->_paypal_status;
  }

  public function getPaypalToken() {
    return $this->_paypal_token;
  }

  public function getRole() {
    return $this->_role;
  }

  public function getToken() {
    return $this->_token;
  }

  public static function getUserCount($db, $status) {
    $query = sprintf("SELECT id FROM users WHERE paypal_status='%s'",
      $db->real_escape_string($status));
    $result = $db->query($query);
    return $result->num_rows;
  }

  public function recordLogin() {
    $ips = explode("\.", $_SERVER['REMOTE_ADDR']);
    $ipval = ($ips[3] + $ips[2] * 256 + $ips[1] * 256 * 256 + $ips[0] * 256 * 256 * 256);
    $query = sprintf("INSERT INTO user_ips SET user_id='%s', ip='%s', creation='%s'",
      $this->_db->real_escape_string($this->_id),
      $this->_db->real_escape_string($ipval),
      $this->_db->real_escape_string(time()));
    $this->_db->query($query);
  }

  public function remove() {
    $query = sprintf("DELETE FROM users WHERE id='%s'",
      $this->_db->real_escape_string($this->_id));
    $result = $this->_db->query($query);
  }

  public function save() {
    global $mysqli;
    $query = sprintf("UPDATE users SET token='%s', paypal_token='%s', paypal_status='%s', firstname='%s', lastname='%s', email='%s', role='%s' WHERE id='%s'",
      $mysqli->real_escape_string($this->_token),
      $mysqli->real_escape_string($this->_paypal_token),
      $mysqli->real_escape_string($this->_paypal_status),
      $mysqli->real_escape_string($this->_firstname),
      $mysqli->real_escape_string($this->_lastname),
      $mysqli->real_escape_string($this->_email),
      $mysqli->real_escape_string($this->_role),
      $mysqli->real_escape_string($this->_id));
    $result = $mysqli->query($query);
    if (!$result) {
      return false;
    } else {
      return true;
    }
  }

  public static function search($query) {
    global $mysqli;
    $users = array();
    $query = sprintf("SELECT users.id,token,paypal_token,paypal_status,email,firstname,lastname,roles.role AS role,creation FROM users LEFT JOIN roles ON users.role=roles.id WHERE users.archived=0 AND (email LIKE '%%%s%%' OR firstname LIKE '%%%s%%' OR lastname LIKE '%%%s%%') ORDER BY creation DESC",
      $mysqli->real_escape_string($query),
      $mysqli->real_escape_string($query),
      $mysqli->real_escape_string($query));
    $result = $mysqli->query($query);
    while ($user = $result->fetch_array(MYSQLI_ASSOC)) {
      array_push($users, $user);
    }
    return $users;
  }

  public function set() {
    global $mysqli;
    $query = sprintf("SELECT * FROM users WHERE email='%s' AND password='%s' AND archived=0 LIMIT 1",
      $mysqli->real_escape_string($this->_email),
      $mysqli->real_escape_string(md5($this->_password)));
    $result = $mysqli->query($query);
    if (!$result) {
      return false;
    } else {
      $user = $result->fetch_assoc();
      $this->_id = $user['id'];
      $this->_token = $user['token'];
      $this->_paypal_token = $user['paypal_token'];
      $this->_paypal_status = $user['paypal_status'];
      $this->_email = $user['email'];
      $this->_firstname = $user['firstname'];
      $this->_lastname = $user['lastname'];
      $this->_role = $user['role'];
      $this->_archived = $user['archived'];
      $this->_creation = $user['creation'];
      return true;
    }
  }

  public function setCreation($creation) {
    $this->_creation = $creation;
  }

  public function setEmail($email) {
    $this->_email = $email;
  }

  public function setFirstName($firstname) {
    $this->_firstname = $firstname;
  }

  public function setId($id) {
    $this->_id = $id;
  }

  public function setIsLoggedIn($isloggedin) {
    $this->_isloggedin = $isloggedin;
  }

  public function setLastName($lastname) {
    $this->_lastname = $lastname;
  }

  public function setPassword($password) {
    $this->_password = $password;
  }

  public function setPaypalStatus($status) {
    $this->_paypal_status = $status;
  }

  public function setPaypalToken($token) {
    $this->_paypal_token = $token;
  }

  public function setRole($role) {
    $this->_role = $role;
  }

  public function setToken($token) {
    $this->_token = $token;
  }

  public static function validate($data, $role = 1, $fields = null) {
    global $mysqli;
    $errors = array();
    $query = sprintf("SELECT id FROM users WHERE email='%s' AND archived='0' LIMIT 1",
      $mysqli->real_escape_string($data['Email']));
    $result = $mysqli->query($query);
    if ($data['FirstName'] === "" && !($fields)) { $errors[] = "You must enter a first name."; }
    if ($data['LastName'] === "" && !($fields)) { $errors[] = "You must enter a last name."; }
    if ($result->num_rows > 0) { $errors[] = "That email address is already in use."; }
    if (!filter_var($data['Email'], FILTER_VALIDATE_EMAIL) && !($fields)) { $errors[] = "You must enter a valid email."; }
    if (strlen($data['Password']) < 6 && !($fields)) { $errors[] = "The password must be at least 6 characters."; }
    if ($data['Password'] != $data['Password2'] && !($fields)) { $errors[] = "Your passwords don't match."; }
    //if ($role === "null" && !($fields)) { $errors[] = "You must select a role."; }
    return $errors;
  }

  public function validatePaypal($paypal) {
    if ($this->_paypal_token === "FREE-USER-ACCOUNT" || $this->_paypal_token === "FREE-ADMIN-ACCOUNT") {
      $this->setPaypalStatus("Active");
      $this->save();
      return true;
    } else {
      $profile = $paypal->get_profile_details($this->_paypal_token);
      $this->setPaypalStatus($profile['STATUS']);
      $this->save();
      if ($profile['STATUS'] !== "Active") {
        return false;
      } else {
        return true;
      }
    }
  }
}
?>
