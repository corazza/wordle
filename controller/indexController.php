<?php

class IndexController extends BaseController
{
	public function index()
	{
		// Samo preusmjeri na users podstranicu.
		header('Location: ' . __SITE_URL . '/wordle.php?rt=start');
	}
};
