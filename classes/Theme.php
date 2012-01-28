<?php
/**
* Theme Class
*/
class Theme {
	public static function locate_template($templateName, $context = '', $content = '') {
		if ($content->date != '') {
			$content->date = date(THEME_DATE_FORMAT, $content->date);
		}
		if ($content->title != '') {
			$content->permalink = '/' . Helpers::sanitize_slug($content->title) . '.html';
		}

		switch($templateName) {
			case 'header':
				if ($context === '') {
					$file = THEMES_PATH . ACTIVE_THEME . '/header.php';
				}
				break;
			case 'footer':
				$file = THEMES_PATH . ACTIVE_THEME . '/footer.php';
				break;
			case 'post-content':
				if ($context === 'single') {
					$file = THEMES_PATH . ACTIVE_THEME . '/post-content-single.php';
				} else {
					$file = THEMES_PATH . ACTIVE_THEME . '/post-content.php';
					
					if (file_exists(THEMES_PATH . ACTIVE_THEME . '/archive.php') && $context == 'posting-archive') {
						$file = THEMES_PATH . ACTIVE_THEME . '/archive.php';
					}
				}
				break;
			case 'page':
				$file = THEMES_PATH . ACTIVE_THEME . '/page.php';
				$pageFile = (basename($content->content_file));
				$pageFile = substr($pageFile, 0, strpos($pageFile, '.'));

				if (file_exists(THEMES_PATH . ACTIVE_THEME . '/' . $pageFile . '.php')) {
					$file = THEMES_PATH . ACTIVE_THEME . '/' . $pageFile . '.php';
				}

				break;			
			default:
				break;
		}
		
		include($file);

		return;
	}
}