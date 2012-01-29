<?php
/**
* Content Class
*/
class Content {
	public $content_file;
	public $raw_content;
	public $id;
	public $title;
	public $raw_date;
	public $date;
	public $published;
	public $html_content;
	public $heading;

	function __construct($content_file) {		
		$this->content_file = $content_file;
		$this->load_content();
		$this->parse_content();
	}

	function load_content() {
		$this->raw_content = Filesystem::read_file($this->content_file);
	}

	function parse_content() {
		$content_start = strpos($this->raw_content, '---', 4);
		$raw_headings = trim(substr($this->raw_content, 3, ($content_start - 3)));
		$raw_headings = explode("\n", $raw_headings);
		
		foreach ($raw_headings as $raw_heading) {
			if ($raw_heading != '') {
				$temp = explode(':', $raw_heading);
				
				if ($temp[0] == 'Date') {
					$temp[1] = trim($temp[1]);
					$dateTime = explode(' ', $temp[1] . ':' . $temp[2]);
					$date = explode('-', $dateTime[0]);
					$time = explode(':', $dateTime[1]);
					$temp[1] = mktime($time[0], $time[1], 0, $date[1], $date[2], $date[0]);
				} elseif (count($temp) > 2) {
					$concatTemp = $temp;
					array_shift($concatTemp);
					$concatTemp = implode(':', $concatTemp);
					$temp = array(
						0 => $temp[0],
						1 => $concatTemp
					);
				}

				if ($temp[0] == 'Category') {
					$temp[1] = explode(',', trim($temp[1]));
					foreach ($temp[1] as &$category) {
						$category = trim($category);
					}
				}
				$this->heading[$temp[0]] = $temp[1];
			}
		}

		$this->id            = md5($this->heading['Date']);
		$this->title         = trim($this->heading['Title']);
		$this->date          = trim($this->heading['Date']);
		$this->raw_date      = trim($this->heading['Date']);
		$this->published     = trim($this->heading['Published']);
		$this->format        = trim($this->heading['Format']);
		$this->permalink     = '/' . Helpers::sanitize_slug($this->heading['Title']) . '.html';
		$this->raw_content   = trim(substr($this->raw_content, ($content_start + 4), strlen($this->raw_content)));
		if (!empty($this->heading['Category'])) { $this->categories = $this->heading['Category']; }
		if (Helpers::feature_enabled('MARKD_DEBUG')) { $this->raw_content  .= "\n\n" . $this->content_file; }
		$this->html_content  = trim(Markdown(substr($this->raw_content, 0, strlen($this->raw_content))));
		$this->html_content = str_replace('<pre><code>', '<pre class="prettyprint">', $this->html_content);
		$this->html_content = str_replace('</code></pre>', '</pre>', $this->html_content);
	}
}