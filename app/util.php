<?php

function read_from_aux($filename) {
	$filename = __DIR__ . '/../aux/' . $filename;
	return file_get_contents($filename);
}

function words_table_name($length) {
	return "words_" . $length;
}

?>
