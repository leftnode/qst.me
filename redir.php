<?php

require_once 'global.php';

qst_check_url();

$blacklist = true;

$url = trim($_GET['url']);
$url_safe = $qstDb->real_escape_string($url);

$sql = "SELECT * FROM `object` o
	WHERE (o.redirect = '" . $url_safe . "' OR o.name = '" . $url_safe . "')
		AND o.status = '1'";
$result_object = $qstDb->query($sql);
if ( 1 == $result_object->num_rows ) {
	$blacklist = false;
	$object = $result_object->fetch_assoc();
	$object_id = intval($object['object_id']);

	$ipv4_safe = $qstDb->real_escape_string(qst_get_ipv4());
	$sql = "INSERT INTO `view` (
		object_id, date_view, ip_address
	) VALUES (
		'" . $object_id . "', '" . time() . "', '" . $ipv4_safe . "'
	)";
	$qstDb->query($sql);
	
	if ( false === empty($object['url']) ) {
		$url_redir = $object['url'];
		header("Location: " . $url_redir);
		exit;
	}
}


require_once 'html-header.php';

if ( true === $blacklist ) {
	?>
	<h2>Blacklisted</h2>
	<div class="text">
		It appears this domain has been blacklisted for SPAM content or simply doesn't exist yet.
		If you feel this is in error, please contact us.
	</div>
	<?php
} else {
	if ( false === empty($object) ) {
		$block = $object['block'];
		$wrap = intval($object['wrap']);
		$php = intval($object['php']);
		?>
		<h2>Block of Text</h2>
		<div class="text">
			<?php if ( 1 == $php ): ?>
				<?php $block = highlight_string($block, true); ?>
			<?php else: ?>
				<?php $block = htmlentities($block); ?>
			<?php endif; ?>
			<?php if ( 1 == $wrap ): ?>
				<pre><?php echo wordwrap(str_replace("\t", "  ", $block), 80, '<br>'); ?></pre>
			<?php else: ?>
				<pre><?php echo str_replace("\t", "  ", $block); ?></pre>
			<?php endif; ?>
		</div>
		<?php
	}
}

require_once 'html-footer.php';
require_once 'exit.php';
