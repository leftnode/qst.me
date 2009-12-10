<?php
/********************************************************
 * Simply takes a `url` param from the $_GET array and  *
 * returns a URL or a 500 Internal Server error.	*
 ********************************************************/
require_once 'global.php';

$object = trim($_GET['url']);
$object_safe = $qstDb->real_escape_string($object);
$object_hash = sha1(SALT . $object);
$error = NULL;

if ( 0 == preg_match("#^(https?)://#i", $object) ) {
	$error = "It appears that your URL does not start with <strong>http://</strong> or <strong>https://</strong> which is required.";
} else {
	if ( true === qst_test_infinite_redirect($object) ) {
		$error = "It appears that your URL redirects several times, which is an indication of SPAM. Stop it.";
	} else {
		$redirect = qst_create_object_url($object_hash, $object_safe);
	}
}

if(true == empty($error)) {
	echo qst_create_url($redirect);
} else {
	header("HTTP/1.1 500 Internal Server Error");
	echo $error;
}
?>
