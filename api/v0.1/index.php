<?php
header("Content-type: application/json");
require("../../.local.inc.php");
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $json['errors'] = array();
  $json['result'] = "success";
  $method = $_POST['Method'] ? $_POST['Method'] : null;
  switch ($method) {
    case "CheckKey":
      if ($user->checkToken($_COOKIE['token'])) {
        $vimeo = new Vimeo(VIMEO_CONSUMER_TOKEN, VIMEO_CONSUMER_SECRET);
        $vimeo->setToken(VIMEO_ACCESS_TOKEN, VIMEO_ACCESS_SECRET);
        $headers = get_headers("http://player.vimeo.com/external/" . $_POST['vimeo_id'] . "." . $_POST['definition'] . ".mp4?s=" . $_POST['key']);
        if (!preg_match('/302/', $headers[0])) {
          array_push($json['errors'], "The key you entered is not valid!");
          $json['result'] = "failed";
        }
      } else {
        array_push($json['errors'], "Not authorized");
        $json['result'] = "failed";
      }
      break;
    case "GetAlbums":
      if ($user->checkToken($_COOKIE['token'])) {
        $vimeo = new Vimeo(VIMEO_CONSUMER_TOKEN, VIMEO_CONSUMER_SECRET);
        $vimeo->setToken(VIMEO_ACCESS_TOKEN, VIMEO_ACCESS_SECRET);
        $albums = objectToArray($vimeo->call('vimeo.albums.getAll', array('user_id' => VIMEO_USER)));
        $json['albums'] = json_encode($albums);
      } else {
        array_push($json['errors'], "Not authorized");
        $json['result'] = "failed";
      }
      break;
    case "GetVideos":
      if ($user->checkToken($_COOKIE['token'])) {
        $vimeo = new Vimeo(VIMEO_CONSUMER_TOKEN, VIMEO_CONSUMER_SECRET);
        $vimeo->setToken(VIMEO_ACCESS_TOKEN, VIMEO_ACCESS_SECRET);
        $videos = objectToArray($vimeo->call('vimeo.albums.getVideos', array('user_id' => VIMEO_USER)));
        $json['videos'] = json_encode($videos);
      } else {
        array_push($json['errors'], "Not authorized");
        $json['result'] = "failed";
      }
      break;
    case "SaveKey":
      if ($user->checkToken($_COOKIE['token'])) {
        $vimeo = new Vimeo(VIMEO_CONSUMER_TOKEN, VIMEO_CONSUMER_SECRET);
        $vimeo->setToken(VIMEO_ACCESS_TOKEN, VIMEO_ACCESS_SECRET);
        $headers = get_headers("http://player.vimeo.com/external/" . $_POST['vimeo_id'] . "." . $_POST['definition'] . ".mp4?s=" . $_POST['key']);
        if (preg_match('/302/', $headers[0])) {
          $video = new Video($_POST['vimeo_id']);
          $video->setDefinition($_POST['definition']);
          $video->setKey($_POST['key']);
          $video->save();
        } else {
          array_push($json['errors'], "The key you entered is not valid!");
          $json['result'] = "failed";
        }
      } else {
        array_push($json['errors'], "Not authorized");
        $json['result'] = "failed";
      }
      break;
    case "UserContact":
      $contact = new Contact(DB::$conn);
      if (trim($_POST['FullName']) === "") {
        array_push($json['errors'], "Please enter your full name.");
      }
      if (trim($_POST['Email']) === "" || !filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL)) {
        array_push($json['errors'], "Please enter a valid email.");
      }
      if (trim($_POST['Message']) === "") {
        array_push($json['errors'], "Please enter a contact message.");
      }
      if (count($json['errors'])) {
        $json['result'] = "failed";
      } else {
        if (!($contact->add($_POST['FullName'], $_POST['Email'], $_POST['Message']))) {
          array_push($json['errors'], "There was an error submitting your message.  Please try again.");
          $json['result'] = "failed";
        } else {
          $contact->setFullName($_POST['FullName']);
          $contact->setEmail($_POST['Email']);
          $contact->setMessage($_POST['Message']);
          $contact->send();
        }
      }
      break;
    case "UserLogin":
      $user = new User(DB::$conn, $_POST['Email'], $_POST['Password']);
      if (!$user->getIsLoggedIn()) {
        $json['result'] = "failed";
        array_push($json['errors'], "Username/Password Incorrect!");
      } else if ($user->getPaypalStatus() === "New") {
        $user->setIsLoggedIn(false);
        $json['result'] = "failed";
        array_push($json['errors'], "Your account has not completed the payment portion of the registration.  Please try registering again, or email us at Worlddanceu@gmail.com.");
      } else if (!$user->getPaypalToken() && $user->getPaypalToken() == "" || $user->getArchived() == "1") {
        $user->setIsLoggedIn(false);
        $json['result'] = "failed";
        array_push($json['errors'], "Your account has been disabled.  If you think this is in error, please send an email to Worlddanceu@gmail.com.");
      } else if (!$user->validatePaypal($paypal)) {
        $json['result'] = "failed";
        array_push($json['errors'], "Your account has been disabled due to a payment issue with Paypal.  If you think this is in error, please send an email to Worlddanceu@gmail.com.");
      } else {
        $user->setToken(md5($_POST['Email'] . "-" . rand(1,99999) . "-" . microtime()));
        $user->save();
        setcookie('email', $user->getEmail());
        setcookie('token', $user->getToken());
      }
      break;
    case "UserRegister":
      $data = $_POST;
      $user = new User(DB::$conn);
      $errors = $user->validate($data);
      if (count($errors) === 0) {
        $user->setToken(md5($_POST['Email'] . "-" . rand(1,99999) . "-" . microtime()));
        $user->setEmail($_POST['Email']);
        $user->setFirstName($_POST['FirstName']);
        $user->setLastName($_POST['LastName']);
        $user->setPassword(md5($_POST['Password']));
        $user->setRole(1);
        $user->setCreation(time());
        $user->setIsLoggedIn(true);
        $user->add();
        setcookie("email", $user->getEmail());
        setcookie("token", $user->getToken());
      } else {
        $json['result'] = "failed";
        $json['errors'] = $errors;
      }
      break;
    case "UserUnsubscribe":
      if (!$_POST['Password']) {
        $json['result'] = "failed";
        array_push($json['errors'], "Your password is incorrect.");
      } else {
        $paypal->manage_subscription_status($user->getPaypalToken(), "Cancel", "User cancelled the subscription through the account panel.");
        $profile = $paypal->get_profile_details($user->getPaypalToken());
        $user->setPaypalStatus($profile['STATUS']);
        $user->setIsLoggedIn(false);
        $user->save();
      }
      break;
    case "UserUpdate":
      $user->setEmail($_POST['Email']);
      $user->setFirstName($_POST['FirstName']);
      $user->setLastName($_POST['LastName']);
      $user->save();
      break;
    default:
      $json['result'] = "failed";
      array_push($json['errors'], "No method defined");
      break;
  }
} else {
  $json['result'] = "failed";
  array_push($json['errors'], "Not authorized");
}
echo json_encode($json);
?>
