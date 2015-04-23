<?php
class Contact {
  private $_db;
  private $_id;
  private $_fullname;
  private $_email;
  private $_message;
  private $_response;
  private $_response_time;
  private $_creation;

  public function __construct($db = null, $id = null) {
    $this->_db = $db;
    if ($id) {
      $this->_id = $id;
      if (!$this->set()) {
        return false;
      } else {
        return true;
      }
    }
  }

  public function add($fullname, $email, $message, $user_id = null) {
    $query = sprintf("INSERT INTO contact SET user_id='%s', fullname='%s', email='%s', message='%s', creation='%s'",
      $this->_db->real_escape_string($user_id),
      $this->_db->real_escape_string($fullname),
      $this->_db->real_escape_string($email),
      $this->_db->real_escape_string($message),
      $this->_db->real_escape_string(time()));
    $result = $this->_db->query($query);
    if (!$result) {
      return false;
    } else {
      return true;
    }
  }

  public function delete($id) {
    $query = sprintf("UPDATE contact SET archived='1' WHERE id='%s'",
      $this->_db->real_escape_string($id));
    $result = $this->_db->query($query);
    if (!$result) {
      return false;
    } else {
      return true;
    }
  }

  public function getAllUnarchived() {
    $contact = array();
    $query = sprintf("SELECT * FROM contact WHERE archived='0' ORDER BY creation DESC");
    $result = $this->_db->query($query);
    while ($row = $result->fetch_assoc()) {
      array_push($contact, $row);
    }
    return $contact;
  }

  public function getCreation() {
    return $this->_creation;
  }

  public function getEmail() {
    return $this->_email;
  }

  public function getFullName() {
    return $this->_fullname;
  }

  public function getId() {
    return $this->_id;
  }

  public function getMessage() {
    return $this->_message;
  }

  public function getResponse() {
    return $this->_response;
  }

  public function getResponseTime() {
    return $this->_response_time;
  }

  public function reply($response) {
    $this->_response = $response;
    $query = sprintf("UPDATE contact SET response='%s', response_time='%s' WHERE id='%s'",
      $this->_db->real_escape_string($this->_response),
      $this->_db->real_escape_string(time()),
      $this->_db->real_escape_string($id));
    $result = $this->_db->query($query);
    if (!$result) {
      return false;
    } else {
      $message = $this->_response . "\n";
      $headers = "From: Worlddanceu@gmail.com\r\n" .
      "Reply-To: Worlddanceu@gmail.com" . "\r\n" .
      'X-Mailer: PHP/' . phpversion();
      mail($this->_email, "WDU Contact Form Reply", $message, $headers);
      return true;
    }
  }

  public function send() {
    $message = "Full Name: " . $this->_fullname . "\n";
    $message .= "Email: " . $this->_email. "\n\n";
    $message .= "Message:\n" . $this->_message . "\n";
    $headers = "From: " . $this->_email . "\r\n" .
    "Reply-To: " . $this->_email . "\r\n" .
    "X-Mailer: PHP/" . phpversion();
    mail("Worlddanceu@gmail.com", "WDU Contact Form", $message, $headers);
  }

  public function set() {
    $query = sprintf("SELECT * FROM contact WHERE id='%s' LIMIT 1",
      $this->_db->real_escape_string($this->_id));
    $result = $this->_db->query($query);
    if (!$result) {
      return false;
    } else {
      $contact = $result->fetch_assoc();
      $this->_id = $contact['id'];
      $this->_email = $contact['email'];
      $this->_fullname = $contact['fullname'];
      $this->_message = $contact['message'];
      $this->_response = $contact['response'];
      $this->_response_time = $contact['response_time'];
      $this->_creation = $contact['creation'];
      return true;
    }
  }

  public function setEmail($email) {
    $this->_email = $email;
  }

  public function setFullName($fullname) {
    $this->_fullname = $fullname;
  }

  public function setMessage($message) {
    $this->_message = $message;
  }
}
?>
