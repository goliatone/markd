<?php
/**
* Theme Class
*/
class Theme {
	public static function locate_template($templateName, $context = '', $content = '') {
		global $hooks;
		if ($content->date != '' && is_numeric($content->date)) {
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
				$currentPage = $content;
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

	public static function get_nav() {
		$pages = Filesystem::directory_to_array(PAGES_PATH);

		foreach ($pages as $k=>$navItem) {
			if (is_array($navItem)) {
				foreach ($navItem as $m=>$n) {
					if (is_array($n)) {
						die("\n\n==============================[ERROR]====================================\nDrop downs for page structure can only support 2 levels.  Publish failed.\n=========================================================================\n\n");
					}
					$page = new Page(PAGES_PATH . '/' . $k . '/' . $n);
					if ($page->published == 'true') {
						$nav[$k][$n]['published_file'] = Helpers::sanitize_slug($page->title) . '.html';
						$nav[$k][$n]['name'] = $page->title;
					}
				}
			} else {
				$page = new Page(PAGES_PATH . '/' . $navItem);
				if ($page->published == 'true' && strpos($page->content_file, '404.md') === false) {
					$nav[$navItem]['published_file'] = Helpers::sanitize_slug($page->title) . '.html';
					$nav[$navItem]['name'] = $page->title;
				}
			}
		}

		$renderedNav = '<ul class="nav">' . "\n";
		foreach ($nav as $k=>$v) {
			if (!isset($v['published_file'])) {
				$renderedNav .= '	<li class="dropdown" data-dropdown="dropdown">' . "\n";
				$renderedNav .= '		<a class="dropdown-toggle" href="#">' . $k . '</a>' . "\n";
				$renderedNav .= '		<ul class="dropdown-menu">' . "\n";
				foreach ($v as $m=>$n) {
					$renderedNav .= '			<li><a href="' . $n['published_file'] . '">' . $n['name'] . '</a></li>' . "\n";
				}
				$renderedNav .= '		</ul>' . "\n";
				$renderedNav .= '	</li>' . "\n";
			} else {
				$renderedNav .= '	<li><a href="' . $v['published_file'] . '">' . $v['name'] . '</a></li>' . "\n";
			}
		}
		$renderedNav .= '</ul>' . "\n";

		return $renderedNav;
	}

	public static function get_pagination($currentPage) {
		echo '<ul>';
		if ($currentPage == 0) {
			echo '<li></li>';
		} elseif ($currentPage == 1) {
			echo '<li class="prev"><a href="/">Previous</a></li>';
		} else {
			echo '<li class="prev"><a href="/archive-' . ($currentPage - 1) . '.html">Previous</a></li>';
		}

		global $currently_processing;
		if ($currently_processing) {
			echo '<li class="next"><a href="/archive-' . ($currentPage + 1) . '.html">Next Page</a></li>';
		} else {
			echo '<li></li>';
		}
		echo '</ul>';
	}
}