<?php
date_default_timezone_set("America/Chicago");
define("LIB_PATH", __DIR__ . "/lib/");

require(LIB_PATH . "DB.class.php");
require(LIB_PATH . "Album.class.php");
require(LIB_PATH . "Contact.class.php");
require(LIB_PATH . "User.class.php");
require(LIB_PATH . "Utilities.php");
require(LIB_PATH . "Video.class.php");
require(LIB_PATH . "Vimeo.class.php");
require(LIB_PATH . "Paypal/paypal-digital-goods.class.php");
require(LIB_PATH . "Paypal/paypal-subscription.class.php");
require(LIB_PATH . "Paypal/paypal-purchase.class.php");

session_start();
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : preg_replace('/[0-9]{2}$/', "", gethostname());

switch ($host) {
  case "dev.worlddanceuniversity.com":
    define("ENV", "dev");
    break;
  case "stage.worlddanceuniversity.com":
    define("ENV", "stage");
    break;
  case "worlddanceuniversity.com":
  case "www.worlddanceuniversity.com":
    define("ENV", "prod");
    break;
  default:
    define("ENV", "dev");
    break;
}

if (ENV === "dev") {
  define("PAYPAL_API_USER", "wdu-facilitator_api1.shinobicontent.com");
  define("PAYPAL_API_PASS", "9ZU4SLRA8ZQDG9XW");
  define("PAYPAL_API_SIG", "AFcWxV21C7fd0v3bYYYRCpSSRl31Ajzr2d7XGP6VppFJq.N47c-52APt");

  define("VIMEO_USER", "worlddanceuniversity");
  define("VIMEO_CONSUMER_TOKEN", "787a95825d2dec61f5b0a8f1c27be7c7e7617251");
  define("VIMEO_CONSUMER_SECRET", "f431a7c787a36072c2443ddf1233ed46cd0f5b76");
  define("VIMEO_ACCESS_TOKEN", "9c17c3aa916b3be85728fd894fcfb59d");
  define("VIMEO_ACCESS_SECRET", "e195bbf1a75095af9adbbed7e13efdb45791bfb2");

  define("URL", "http://dev.worlddanceuniversity.com/");
  define("GA_CODE", "");
  define("JQUERY_VERSION", "1.9.1");

  define("DB_HOST", "localhost");
  define("DB_NAME", "wdu_dev");
  define("DB_USER", "wdu_dev");
  define("DB_PASS", "j5cgHQUFSqRk");
} else if (ENV === "stage") {
  define("PAYPAL_API_USER", "wdu-facilitator_api1.shinobicontent.com");
  define("PAYPAL_API_PASS", "9ZU4SLRA8ZQDG9XW");
  define("PAYPAL_API_SIG", "AFcWxV21C7fd0v3bYYYRCpSSRl31Ajzr2d7XGP6VppFJq.N47c-52APt");

  define("VIMEO_USER", "worlddanceuniversity");
  define("VIMEO_CONSUMER_TOKEN", "787a95825d2dec61f5b0a8f1c27be7c7e7617251");
  define("VIMEO_CONSUMER_SECRET", "f431a7c787a36072c2443ddf1233ed46cd0f5b76");
  define("VIMEO_ACCESS_TOKEN", "9c17c3aa916b3be85728fd894fcfb59d");
  define("VIMEO_ACCESS_SECRET", "e195bbf1a75095af9adbbed7e13efdb45791bfb2");

  define("URL", "http://stage.worlddanceuniversity.com/");
  define("GA_CODE", "");
  define("JQUERY_VERSION", "1.9.1");

  define("DB_HOST", "localhost");
  define("DB_NAME", "wdu_stage");
  define("DB_USER", "wdu_stage");
  define("DB_PASS", "MkLlqag9m4qA");
} else if (ENV === "prod") {
  define("PAYPAL_API_USER", "wdu_api1.shinobicontent.com");
  define("PAYPAL_API_PASS", "56N4664CUVHJPY8K");
  define("PAYPAL_API_SIG", "AhzSyPtHElHgkm0VNiMbATfNBIPoAsH3xuaSvTOi8LnWFfR4e-Ue7Xhu");

  define("VIMEO_USER", "worlddanceuniversity");
  define("VIMEO_CONSUMER_TOKEN", "787a95825d2dec61f5b0a8f1c27be7c7e7617251");
  define("VIMEO_CONSUMER_SECRET", "f431a7c787a36072c2443ddf1233ed46cd0f5b76");
  define("VIMEO_ACCESS_TOKEN", "9c17c3aa916b3be85728fd894fcfb59d");
  define("VIMEO_ACCESS_SECRET", "e195bbf1a75095af9adbbed7e13efdb45791bfb2");

  define("URL", "http://www.worlddanceuniversity.com/");
  define("GA_CODE", "UA-60548523-1");
  define("JQUERY_VERSION", "1.9.1");

  define("DB_HOST", "localhost");
  define("DB_NAME", "wdu_prod");
  define("DB_USER", "wdu_prod");
  define("DB_PASS", "jyaBq13b2Jag");
  PayPal_Digital_Goods_Configuration::environment("live");
}

PayPal_Digital_Goods_Configuration::username(PAYPAL_API_USER);
PayPal_Digital_Goods_Configuration::password(PAYPAL_API_PASS);
PayPal_Digital_Goods_Configuration::signature(PAYPAL_API_SIG);
PayPal_Digital_Goods_Configuration::return_url(get_script_uri("thankyou.php"));
PayPal_Digital_Goods_Configuration::cancel_url(get_script_uri("error.php"));
PayPal_Digital_Goods_Configuration::notify_url(get_script_uri("error.php"));
PayPal_Digital_Goods_Configuration::business_name("World Dance University");
PayPal_Digital_Goods_Configuration::currency("USD");

$subscription_details = array(
  "description"        => "$1.99 a month!",
  "initial_amount"     => "1.99",
  "amount"             => "1.99",
  "period"             => "Month",
  "frequency"          => "1",
  "total_cycles"       => "0",
);

$paypal = new PayPal_Subscription($subscription_details);

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("MySQL can't establish connection.");
DB::$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!isset($_SESSION['user'])) {
  $_SESSION['user'] = new User();
}
$user =& $_SESSION['user'];

/*
if ($user->getIsLoggedIn()) {
  if (!$user->validatePaypal($paypal)) {
    header("Location: /logout.php");
  }
}
*/

function get_script_uri( $script = 'index.php' ){
	if( empty( $_SERVER['REQUEST_URI'] ) )
		$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];

	$url = preg_replace( '/\?.*$/', '', $_SERVER['REQUEST_URI'] );
	//$url = 'http://'.$_SERVER['HTTP_HOST'].'/'.ltrim(dirname($url), '/').'/';
	$url = 'http://'.$_SERVER['HTTP_HOST'].implode( '/', ( explode( '/', $_SERVER['REQUEST_URI'], -1 ) ) ) . '/';

	return $url . $script;
}
?>
