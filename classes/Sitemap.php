<?php
/**
* Sitemap Class
*/
class Sitemap {
	private $itemList;
	
	public function add_item($item) {
		$this->itemList[] = $item;
	}
	
	public function delete_item($itemIndex) {
		unset($this->itemList[$itemIndex]);
	}
	
	public function update_item($itemIndex, $item) {
		$this->itemList[$itemIndex] = $item;
	}
	
	public function save() {
		$output = '<?xml version="1.0" encoding="UTF-8"?>
			<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		if (!empty($this->itemList)) {
			foreach ($this->itemList as $item) {
				$output .= '
						<url>
							<loc>' . $item->permalink . '</loc>
							<lastmod>' . date('Y-m-d', $item->raw_date) . '</lastmod>
							<changefreq>' . $item->change_frequency . '</changefreq>
							<priority>' . $item->priority . '</priority>
						</url>
				';
			}
		}
		$output .= '
				</urlset>';
		
		$file = PUBLISHED_PATH . '/sitemap.xml';
		$test = Filesystem::write_file($file, $output, 'w');
	}
}
