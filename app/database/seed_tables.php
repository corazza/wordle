<?php

require_once __DIR__ . '/db.class.php';
require_once __DIR__ . '/../util.php';

seed_table_words(5);
seed_table_words(6);
seed_table_words(7);

function is_table_empty($table_name, $log = false) {
	$db = DB::getConnection();

	$st = $db->prepare('SELECT count(*) FROM ' . $table_name);
	$st->execute();

	$r = intval($st->fetchColumn());
	
	$empty = ($r === 0);

	if (!$empty && $log) {
		echo "tablica " . $table_name . " nije prazna<br />";
	}

	return $empty;
}

function seed_table_words($length) {
	$table_name = words_table_name($length);

	if (!is_table_empty($table_name, false)) {
		return;
	}

	$words = read_from_aux($table_name . ".txt");
	$words = explode("\n", $words);

	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO ' . $table_name . '(content) VALUES (:content)');

		foreach ($words as $word) {
			if (strlen($word) === $length) {
				$st->execute(array('content' => $word));
			}
		}
	} catch (PDOException $e) {
		exit("PDO error (seed_table_books): " . $e->getMessage());
	}
}
