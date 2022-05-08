<?php

class WordleService
{
	public function makeAttempt($name, $attempt)
	{
		$context = $this->getContextFor($name);
		$context->addAttempt($attempt);
		if (strcmp($attempt, $context->word) === 0) {
			$context->won = true;
		}
		$this->updateContext($name, $context);
	}

	public function addSupAttempt($name)
	{
		$context = $this->getContextFor($name);
		$context->sup_attempts += 1;
		$this->updateContext($name, $context);
	}

	public function hintFor($name)
	{
		$context = $this->getContextFor($name);
		$characters = str_split($context->word);
		$start = rand(0, $context->length - 1);
		$found = false;
		for ($i = 0; $i < $context->length; ++$i) {
			$exists_hint = array_key_exists($characters[$start], $context->hints);
			$exists_hidden_hint = array_key_exists($characters[$start], $context->hidden_hints);
			$exists_big_hint = array_key_exists($start, $context->big_hints);
			$exists_hidden_big_hint = array_key_exists($start, $context->hidden_big_hints);
			if (!($exists_hint || $exists_hidden_hint) && !($exists_big_hint || $exists_hidden_big_hint)) {
				$found = true;
				break;
			}
			++$start;
			if (intval($start) === intval($context->length)) {
				$start = 0;
			}
		}
		if ($found) {
			$context->addHint($characters[$start]);
			$this->updateContext($name, $context);
		}
	}

	public function bigHintFor($name)
	{
		$context = $this->getContextFor($name);
		$characters = str_split($context->word);
		$start = rand(0, $context->length - 1);
		$found = false;
		for ($i = 0; $i < $context->length; ++$i) {
			$exists_big_hint = array_key_exists($start, $context->big_hints);
			$exists_hidden_big_hint = array_key_exists($start, $context->hidden_big_hints);
			if (!($exists_big_hint || $exists_hidden_big_hint)) {
				$found = true;
				break;
			}
			++$start;
			if (intval($start) === intval($context->length)) {
				$start = 0;
			}
		}
		if ($found) {
			$context->addBigHint($characters[$start], $start);
			$this->updateContext($name, $context);
		}
	}

	public function hasContextFor($name)
	{
		try {
			$db = DB::getConnection();
			$st = $db->prepare('SELECT COUNT(*) FROM contexts WHERE name="' . $name . '"');
			$st->execute();
		} catch (PDOException $e) {
			exit('PDO error (hasContextFor 1)' . $e->getMessage());
		}

		$row = $st->fetch();
		return intval($row[0]) === 1;
	}

	public function getContextFor($name)
	{
		try {
			$db = DB::getConnection();
			$st = $db->prepare('SELECT * FROM contexts WHERE name ="' . $name . '"');
			$st->execute();
		} catch (PDOException $e) {
			exit('PDO error (getContextFor 1)' . $e->getMessage());
		}

		$row = $st->fetch();

		return unserialize($row["serialized"]);
	}

	private function setContext($name, $context)
	{
		$serialized = serialize($context);

		try {
			$db = DB::getConnection();
			$st = $db->prepare('INSERT INTO contexts (name, serialized) VALUES (:name, :serialized)');
			$st->execute(array('name' => $name, 'serialized' => $serialized));
		} catch (PDOException $e) {
			exit('PDO error (setContext 1)' . $e->getMessage());
		}
	}

	private function updateContext($name, $context)
	{
		$old_context = $this->getContextFor($name);
		if ($old_context->won) {
			return;
		}

		$serialized = serialize($context);

		try {
			$db = DB::getConnection();
			$st = $db->prepare('UPDATE contexts SET serialized=:serialized WHERE name=:name');
			$st->execute(array('name' => $name, 'serialized' => $serialized));
		} catch (PDOException $e) {
			exit('PDO error (updateContext 1)' . $e->getMessage());
		}
	}

	public function resetContext($name)
	{
		try {
			$db = DB::getConnection();
			$st = $db->prepare('DELETE FROM contexts WHERE name=:name');
			$st->execute(array('name' => $name));
		} catch (PDOException $e) {
			exit('PDO error (resetContext 1)' . $e->getMessage());
		}
	}

	public function createNewContext($name, $length)
	{
		$table_name = words_table_name($length);

		try {
			$db = DB::getConnection();
			$st = $db->prepare('SELECT COUNT(*) FROM ' . $table_name);
			$st->execute();
		} catch (PDOException $e) {
			exit('PDO error (createNewContext 1)' . $e->getMessage());
		}

		$row = $st->fetch();
		$word_number = $row[0];
		$to_select = rand(1, $word_number);

		try {
			$db = DB::getConnection();
			$st = $db->prepare('SELECT content FROM ' . $table_name . ' WHERE id = ' . $to_select);
			$st->execute();
		} catch (PDOException $e) {
			exit('PDO error (createNewContext 2)' . $e->getMessage());
		}

		$row = $st->fetch();

		$context = new GameContext($name, $length, $row[0], array());
		$this->setContext($name, $context);
	}
};
