<?php

require_once 'global.php';

qst_check_url();

$url = trim($_GET['url']);
$url_redir_safe = $qstDb->real_escape_string($url);

$sql = "SELECT * FROM `object` o
	WHERE o.redirect = '" . $url_redir_safe . "'
		AND o.status = '1'";
$result_object = $qstDb->query($sql);
if ( 1 == $result_object->num_rows ) {
	$object = $result_object->fetch_assoc();
	$object_id = intval($object['object_id']);
	
	$view_count = 0;
	$sql = "SELECT COUNT(*) AS view_count FROM `view` v
		WHERE v.object_id = '" . $object_id . "'";
	$result_view_count = $qstDb->query($sql);
	if ( $result_view_count->num_rows > 0 ) {
		$view_count = $result_view_count->fetch_assoc();
		$view_count = $view_count['view_count'];
	}
} else {
	$error = "Sorry, that URL can not be found.";
}

require_once 'html-header.php';

if ( true === empty($error) ) {
	$qst_url = qst_create_url($object['redirect']);
	?>
	<h2>Some Stats About <a href="<?=$qst_url; ?>" target="_blank"><?=$qst_url; ?></a></h2>
	<div class="text">
		This URL or Block has been viewed <?=intval($view_count); ?> times since
		it's inception on <?=date('M j, Y'); ?>.
	</div>
	<br />
	<div class="text">
		<?php
		if ( false === empty($object['url']) ) {
			?>
			This object is an URL and points to <?=htmlentities($object['url']); ?>
			<?php
		} else {
			?>
			This object is a block of text, and contains the data:
			<div class="text">
				<?=nl2br(htmlentities($object['block'])); ?>
			</div>
			<?php
		}
		?>
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