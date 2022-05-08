<?php 

class StartController extends BaseController {
	public function index() {
		$this->registry->template->title = 'Pocetak igre';
        $this->registry->template->show('start_index');
	}
};
