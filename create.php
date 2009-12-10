<?php

require_once 'global.php';

if ( false === array_key_exists('url', $_REQUEST) && false === array_key_exists('block', $_REQUEST) ) {
	exit("No URL or Block was present in the request. Exiting.");
}

reset($_REQUEST);
$type = key($_REQUEST);
$object = trim($_REQUEST[$type]);
$object_safe = $qstDb->real_escape_string($object);
$object_hash = sha1(SALT . $object);
$wrap = ( isset($_REQUEST['wrap']) ? intval($_REQUEST['wrap']) : 0 );
$php = ( isset($_REQUEST['php']) ? intval($_REQUEST['php']) : 0 );

$error = NULL;

switch ( $type ) {
	case 'url': {
		if ( 0 == preg_match("#^(https?)://#i", $object) ) {
			$error = "It appears that your URL does not start with <strong>http://</strong> or <strong>https://</strong> which is required.";
		} else {
			if ( true === qst_test_infinite_redirect($object) ) {
				$error = "It appears that your URL redirects several times, which is an indication of SPAM. Stop it.";
			} else {
				$redirect = qst_create_object_url($object_hash, $object_safe);
			}
		}
		break;
	}
	
	case 'block': {
		/**
		 * Blocks are pretty self explanitory. Just hash it, search for the hash.
		 * If the hash isn't found, create a new one, otherwise, just show 
		 * the URL for the block.
		 */
		$redirect = qst_create_object_block($object_hash, $object_safe, $wrap, $php);
	}
}


require_once 'html-header.php';

if ( true === empty($error) ) {
	$final_url = qst_create_url($redirect);
	?>
	<h2>Your Shortened URL</h2>
	<a href="<?=$final_url; ?>"><?=$final_url; ?></a><br />

	<div class="text">
		<input type="text" value="<?=$final_url; ?>" style="width: 100%;" />
	</div>

	<br />
	
	<h2>View Stats About Your Shortened URL</h2>
	<div class="text">
		<input type="text" value="<?=$final_url."$"; ?>" style="width: 100%;" />
	</div>
	<?php
} else {
	?>
	<h2>Error!</h2>
	<div class="text"><?=$error; ?></div>
	<?php
}

require_once 'html-footer.php';
require_once 'exit.php';
