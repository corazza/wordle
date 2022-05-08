<?php

// Stvaramo tablice u bazi (ako već ne postoje od ranije).
require_once __DIR__ . '/db.class.php';

// create_table_users();
create_table_words(5);
create_table_words(6);
create_table_words(7);
create_table_contexts();

function create_table_contexts()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS contexts (' .
				'name varchar(50) NOT NULL PRIMARY KEY, ' .
				'serialized BLOB)'
		);

		$st->execute();
	} catch (PDOException $e) {
		exit("PDO error (create_table_contexts): " . $e->getMessage());
	}
}

function create_table_words($length) {
	$table_name = "words_" . $length;
	$db = DB::getConnection();

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS ' . $table_name . ' (' .
				'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
				'content char(' . $length . ') NOT NULL UNIQUE)'
		);

		$st->execute();
	} catch (PDOException $e) {
		exit("PDO error (create_table_words): " . $e->getMessage());
	}
}
