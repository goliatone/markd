<?php
/**
* Post Class
*/
class Post extends Content {
	public $content_type;
	public $categories;
	public $link;

	function __construct($post_file) {		
		$this->content_type = 'post';
		$this->categories = array();
		parent::__construct($post_file);
		$this->set_permalink();

		$this->process_post_type();
	}

	private function set_permalink() {
		$this->permalink = '/' . date('Y', $this->raw_date) . '/' . date('m', $this->raw_date) . '/' . date('d', $this->raw_date) . $this->permalink . "\n";
	}

	private function process_post_type() {
		switch($this->format) {
			case 'Link':
				$this->link = $this->heading['Link'];
				break;
			default:
				break;
		}
	}
}