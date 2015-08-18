<?php
$file = dirname(dirname(dirname(dirname(dirname( dirname( __FILE__ ) )))));
require($file . "/wp-load.php");
global $wpdb;
//INCLUDE DEFAULT MAILCHIMP FILES START.
require_once get_template_directory().'/library/classes/MCAPI.class.php';
//INCLUDE DEFAULT MAILCHIMP FILES FINISH.
$apikey = $_REQUEST['api_key'];
//$listId = 'YOUR MAILCHIMP LIST ID - see lists() method';
$listId = $_REQUEST['list_id'];
$apiUrl = 'http://api.mailchimp.com/1.3/';
//CONFIGURE VARIABLES START.
$api = new MCAPI($apikey);
$name = $_REQUEST['name'];
$email = $_REQUEST['email'];
$merge_vars = array('FNAME' => $name);
//CONFIGURE VARIABLES FINISH.
//MAKE API CALLSE FOR MAILCHIMP START.
$retval = $api->listSubscribe( $listId, $email, $merge_vars );
//MAKE API CALLSE FOR MAILCHIMP FINISH.
//CONFIGURE ERROR OR SUCCESS PROCESS START.
if ($api->errorCode){
	echo $api->errorMessage."\n";
} else {
	_e("Successfully Subscribed. Please check confirmation email.",DOMAIN);
}
//CONFIGURE ERROR OR SUCCESS PROCESS FINISH.
?>