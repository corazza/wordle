<?php

class GameContext
{
	protected $name, $length, $word, $attempts, $won,
			  $hints, $big_hints, $hidden_hints, $hidden_big_hints, $sup_attempts;

	public function addAttempt($attempt) {
		$green_color = correctPositions($this->length, $attempt, $this->word);
		foreach ($green_color as $position) {
			if (!array_key_exists($position, $this->big_hints)) {
				$this->hidden_big_hints[$position] = 1;
			}
		}
		$brown_color = correctNoPositions($this->length, $attempt, str_split($this->word));
		foreach ($brown_color as $position => $value) {
			if (!array_key_exists($position, $this->hints)) {
				$this->hidden_hints[$position] = 1;
			}
		}
		array_push($this->attempts, $attempt);
	}

	public function addHint($hint) {
		$this->hints[$hint] = 1;
	}

	public function addBigHint($hint, $at) {
		$this->big_hints[$at] = $hint;
	}

	function __construct($name, $length, $word)
	{
		$this->name = $name;
		$this->length = $length;
		$this->word = $word;
		$this->attempts = array();
		$this->won = false;
		$this->hints = array();
		$this->hidden_hints = array();
		$this->big_hints = array();
		$this->hidden_big_hints = array();
		$this->sup_attempts = 0;
	}

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }
}

function correctPositions($length, $attempt, $word) {
	$result = array();
	for ($i = 0; $i < $length; ++$i) {
		$attempt_i = mb_substr($attempt, $i, 1);
		$word_i = mb_substr($word, $i, 1);
		if ($attempt_i === $word_i) {
			array_push($result, $i);
		}
	}
	return $result;
}

function correctNoPositions($length, $attempt, $characters) {
	$correct = array();
	for ($i = 0; $i < $length; ++$i) {
		$attempt_i = mb_substr($attempt, $i, 1);
		if (in_array($attempt_i, $characters)) {
			$correct[$attempt_i] = 1;
		}
	}
	return $correct;
}

function getGreenColorIndex($context) {
	$indices = array();
	foreach ($context->attempts as $attempt) {
		$indices[$attempt] = correctPositions($context->length, $attempt, $context->word);
	}
	return $indices;
}

function getBrownColorIndex($context) {
	$correct = array();
	$characters = str_split($context->word);
	foreach ($context->attempts as $attempt) {
		$correctInAttempt = correctNoPositions($context->length, $attempt, $characters);
		foreach ($correctInAttempt as $key => $value) {
			$correct[$key] = 1;
		}
	}
	return $correct;
}

?>
