<?php

require_once 'global.php';

$document = $_FILES['document'];
$tmp_file = $document['tmp_name'];
$filename = DOCUMENT_DIR . $document['name'];

$size = intval($_POST['size']);

if ( true === move_uploaded_file($tmp_file, $filename) ) {
	$output = shell_exec('antiword ' . escapeshellarg($filename));
	
	// Quick and dirty parsing
	$output = preg_replace('/[\n\r\t\,\:]/im', ' ', $output);
	$output = preg_replace('/[ ]{2,}/im', ' ', $output);
	$output = preg_replace('/[^a-z\'\-\_]/i', ' ', $output);
	
	$output_bits = explode(' ', $output);
	
	$count_list = array();
	$count = 0;
	foreach ( $output_bits as $word ) {
		$len = strlen($word);
		
		// To make up for the last regex up there
		if ( $len > 0 ) {
			if ( $len >= $size ) {
				$count++;
			}
			
			if ( $len <= $size ) {
				if ( false === isset($count_list[$word]) ) {
					$count_list[$word] = 0;
				}
				$count_list[$word]++;
			}
		}
	}
	
	arsort($count_list);

	unlink($filename);
}

require_once 'html-header.php';

?>
<h2>Words Count Results</h2></h2>

<br />

<div class="text">
	There are <strong><?php echo $count; ?></strong> words of length greater than or equal to
	<?php echo $size; ?> in <em><?php echo htmlentities($document['name']); ?></em>.
</div>

<br />
<h2>Top 25 Common Words Less Than Or Equal To <?php echo $size; ?> Characters</h2>

<table style="width: 50%; border-collapse: collapse;" cellspacing="0" cellpadding="0">
<?php
$i=0;
foreach ( $count_list as $word => $count ) {
	?>
	<tr>
		<td style="width: 50%"><?php echo htmlentities($word); ?></td>
		<td style="width: 50%"><?php echo $count; ?></td>
	</tr>
	<?php
	$i++;
	if ( 25 == $i ) {
		break;
	}
}
?>
</table>

<?php

require_once 'html-footer.php';
require_once 'exit.php';