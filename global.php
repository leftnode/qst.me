<?php

require_once 'configure.php';
$qstDb = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

$qstFieldList = array('block', 'url');

function qst_check_url() {
	if ( false === array_key_exists('url', $_GET) ) {
		exit("Sorry, no URL was provided.");
	}
}

function qst_check_hash_exists($hash_safe) {
	global $qstDb;
	
	$sql = "SELECT * FROM `object` o WHERE o.hash = '" . $hash_safe . "'";
	$result_object = $qstDb->query($sql);
	
	return ( 1 == $result_object->num_rows ? $result_object->fetch_assoc() : false );
}

function qst_create_object($type, $hash_safe, $object_safe, $wrap=1, $php=0) {
	global $qstDb, $qstFieldList;
	
	if ( false !== ($row_object = qst_check_hash_exists($hash_safe)) ) {
		$redirect = trim($row_object['redirect']);
		return $redirect;
	}
	
	if ( false === in_array($type, $qstFieldList) ) {
		return NULL;
	}

	$i = 0;
	$redirect = NULL;
	do {
		$redirect .= $hash_safe[$i++];
		$sql = "SELECT * FROM `object` o WHERE o.redirect = '" . $redirect . "'";
		$count = $qstDb->query($sql)->num_rows;
	} while ( $count != 0 );
	
	/* Now that we found a hash, insert everything. */
	$wrap = intval($wrap);
	$php = intval($php);
	$sql = "INSERT INTO `object` (
			date_create, redirect, " . $type . ", wrap, php, hash, status
		) VALUES (
			'" . time() . "', '" . $redirect . "', '" . $object_safe . "', '" . $wrap . "', '" . $php . "', '" . $hash_safe . "', '1'
		)";
	$qstDb->query($sql);
	
	return $redirect;
}

function qst_create_object_url($hash_safe, $object_safe) {
	return qst_create_object('url', $hash_safe, $object_safe);
}

function qst_create_object_block($hash_safe, $object_safe, $wrap, $php) {
	return qst_create_object('block', $hash_safe, $object_safe, $wrap, $php);
}

function qst_test_infinite_redirect($url) {
	$curl = curl_init($url);
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_MAXREDIRS => MAX_REDIRECT
		)
	);
	$curl_result = curl_exec($curl);
	
	if ( false === $curl_result ) {
		return true;
	}
	return false;
}

function qst_create_url($redirect) {
	return ROOT_URL . $redirect;
}

function qst_get_ipv4() {
	$ip = NULL;
	if ( true === isset($_SERVER) ) {
		if ( true === array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( true === array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	}
	
	return $ip;
}
