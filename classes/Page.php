<?php
/**
* Page Class
*/
class Page extends Content {
	public $content_type;

	function __construct($post_file) {		
		$this->content_type = 'page';
		parent::__construct($post_file);
		$this->set_permalink();
	}

	private function set_permalink() {
		if (strpos($this->content_file, '404') !== false) {
			$this->permalink = '/404.html';
		}
	}
}