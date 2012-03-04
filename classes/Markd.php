<?php
/**
* markd Class
* 
*/
class Markd {
	public $publishedPosts;
	private $currentPage;
	private $categoryList;

	function __construct() {
		$this->publishedPosts = 0;

		$this->process_blog_posts();
		$this->process_categories();
		$this->process_pages();
		$this->process_html_sitemap();
		$this->process_xml_sitemap();
		$this->process_stylesheets();
		$this->process_javascripts();
		$this->process_images();
		$this->process_robotstxt();
		
		$this->complete_process();
	}
	
	private function start_buffer() {
		ob_start();
	}

	private function get_buffer() {
		$buffer_contents = ob_get_clean();

		global $themeReplacements;
		foreach ($themeReplacements as $k=>$v) {
			$buffer_contents = str_replace($k, $v, $buffer_contents);
		}

		return $buffer_contents;
	}

	public function process_blog_posts($args = array()) {
		// Process blog posts
		global $currently_processing;
		$currently_processing = TRUE;
		$processed_count = 0;
		$this->currentPage = 0;

		while ($currently_processing) {
			$blogPosts = array();
			if (empty($args)) {
				$get_posts = $this->get_posts((POSTS_PER_PAGE * $this->currentPage), POSTS_PER_PAGE);
			} else {
				$get_posts = $this->get_posts((POSTS_PER_PAGE * $this->currentPage), POSTS_PER_PAGE, $args);
			}

			$blogPosts = $get_posts['blogPosts'];
			$processed_count = $processed_count + count($blogPosts);

			if (empty($args)) {
				if (count($blogPosts) < POSTS_PER_PAGE || $processed_count == Posts::get_total_post_count()) { $currently_processing = FALSE; }		// Keep loop going until we reach a full page of posts
			} elseif (isset($args['category'])) {
				if (count($blogPosts) < POSTS_PER_PAGE || $processed_count == $this->categoryList[$args['category']]) { $currently_processing = FALSE; }
			}
			$this->write_post_list($this->currentPage, $blogPosts, $args);

			if ($this->currentPage == 0 && empty($args)) {
				$this->process_feed($blogPosts);
			}

			$this->currentPage++;
		}
	}

	private function process_categories() {
		foreach ($this->categoryList as $category=>$v) {
			$args = array(
					'category' => $category
				);
			$this->process_blog_posts($args);
		}

		return;
	}
	
	public function process_pages() {
		// Process pages
		$get_pages = $this->get_pages();
		$pages = $get_pages['pages'];

		foreach ($pages as $pageFile) {
			$page = new Page($pageFile);
			$this->write_page($page);
		}
	}

	public function process_html_sitemap() {
		$get_posts = $this->get_posts(0, Posts::get_total_post_count(), array('file-data' => true));
		$contents['blogPosts'] = $get_posts['blogPosts'];
		$pages = $this->get_pages();
		$contents['pages'] = $pages['pages'];

		$this->write_html_sitemap($contents);
	}

	public function process_xml_sitemap() {
		$get_posts = $this->get_posts(0, Posts::get_total_post_count(), array('file-data' => true));
		$contents['blogPosts'] = $get_posts['blogPosts'];
		$pages = $this->get_pages();
		$contents['pages'] = $pages['pages'];

		$sitemap = new Sitemap();
		$item = (object) array(
			'permalink'        => Helpers::trailingslashit(SITE_URL),
			'raw_date'         => time(),
			'change_frequency' => 'hourly',
			'priority'         => '0.4'
		);
		$sitemap->add_item($item);

		foreach ($contents['pages'] as $pageFile) {
			$page = new Page($pageFile);
			if (strpos($page->content_file, '404') === false) { // Leave the 404 page out of things
				$item = (object) array(
					'permalink'        => Helpers::untrailingslashit(SITE_URL) . $page->permalink,
					'raw_date'         => $page->raw_date,
					'change_frequency' => 'weekly',
					'priority'         => '0.8'
				);
				if (strpos($page->permalink, 'search') !== false) { // Deprioritize the search page
					$item->priority = '0.1';
				}
				$sitemap->add_item($item);
			}
		}
		foreach ($contents['blogPosts'] as $post) {
			$item = (object) array(
				'permalink'        => Helpers::untrailingslashit(SITE_URL) . $post->permalink,
				'raw_date'         => $post->raw_date,
				'change_frequency' => 'daily',
				'priority'         => '0.6'
			);
			$sitemap->add_item($item);
		}
		$sitemap->save();
	}
	
	public function process_stylesheets() {
		$cssFiles = Filesystem::list_directory(Helpers::untrailingslashit(THEMES_PATH) . ACTIVE_THEME . '/css');

		if (!empty($cssFiles)) {
			foreach ($cssFiles as $cssFile) {
				$styleSheet = Filesystem::read_file($cssFile);

				if (!file_exists(PUBLISHED_PATH . '/css')) {
					mkdir(PUBLISHED_PATH . '/css', 0755);
				}

				Filesystem::write_file(PUBLISHED_PATH . '/css/' . basename($cssFile), $styleSheet);
			}
		}
	}
	
	public function process_javascripts() {
		$jsFiles = Filesystem::list_directory(Helpers::untrailingslashit(THEMES_PATH) . ACTIVE_THEME . '/js');

		if (!empty($jsFiles)) {
			foreach ($jsFiles as $jsFile) {
				$js = Filesystem::read_file($jsFile);

				if (!file_exists(PUBLISHED_PATH . '/js')) {
					mkdir(PUBLISHED_PATH . '/js', 0755);
				}

				Filesystem::write_file(PUBLISHED_PATH . '/js/' . basename($jsFile), $js);
			}
		}
	}

	public function process_images() {
		$images = Filesystem::list_directory(Helpers::untrailingslashit(THEMES_PATH) . ACTIVE_THEME . '/images');

		if (!empty($images)) {
			foreach ($images as $image) {
				$js = Filesystem::read_file($image);

				if (!file_exists(PUBLISHED_PATH . '/images')) {
					mkdir(PUBLISHED_PATH . '/images', 0755);
				}

				Filesystem::write_file(PUBLISHED_PATH . '/images/' . basename($image), $js);
			}
		}
	}

	public function process_robotstxt() {
		$file = Helpers::untrailingslashit(THEMES_PATH . ACTIVE_THEME . '/robots.txt');
		$content = Filesystem::read_file($file);
		Filesystem::write_file(PUBLISHED_PATH . '/robots.txt', $content);
	}

	public function process_feed($blogPosts) {
		// Create Feed Object and save
		$feed = new Feed();
		$feed->set_title(SITE_TITLE);
		$feed->set_selfLink(SITE_URL . '/rss.xml');
		$feed->set_siteLink(SITE_URL);
		if (SITE_DESC !== '') { $feed->set_description(SITE_DESC); }
		if (!empty($blogPosts)) {
			foreach ($blogPosts as $blogPost) {
				unset($item);
				$item = (object) array(
					'id'           => $blogPost->id,
					'title'        => $blogPost->title,
					'link'         => SITE_URL . '/' . date('Y', $blogPost->raw_date) . '/' . Helpers::sanitize_slug($blogPost->title) . '.html',
					'pubDate'      => $blogPost->date,
					'html_content' => $blogPost->html_content
				);
				$feed->add_item($item);
			}
		}
		$feed->save();
	}
	
	public function get_posts($startPostNum, $numberOfPosts, $args = array()) {
		$posts = Posts::get_posts($startPostNum, $numberOfPosts, $args);
		$this->publishedPosts = $this->publishedPosts + count($posts['blogPosts']);

		return $posts;
	}
	
	public function get_pages() {
		$pages = Filesystem::list_directory(PAGES_PATH);

		foreach ($pages as $pageFile) {
			$page = new Page($pageFile);
			if ($page->published == true) {
				$returnPageListing['pages'][] = $pageFile;
			}
		}

		return $returnPageListing;
	}
	
	public function write_post_list($pageNumber, $contentList, $args = array()) {
		if (count($contentList) < 1) { return FALSE; }
		if ($pageNumber == 0 && !isset($args['category'])) {
			$file = PUBLISHED_PATH . '/index.html';
			$context = 'posting-index';
		} elseif (isset($args['category'])) {
			global $postListingType;
			$postListingType = array('category' => $args['category']);
			if (!file_exists(PUBLISHED_PATH . '/category')) {
				mkdir(PUBLISHED_PATH . '/category', 0755);
			}
			$file = PUBLISHED_PATH . '/category/' . $args['category'] . '-' . $pageNumber . '.html';
		} else {
			$file = PUBLISHED_PATH . '/archive-' . $pageNumber . '.html';
			$context = 'posting-archive';
		}
		$this->start_buffer();
		Theme::locate_template('header');
		if (!empty($contentList)) {
			foreach($contentList as $content) {
				if (is_array($content->categories) && !isset($postListingType['category'])) {
					foreach ($content->categories as $category) {
						if (isset($this->categoryList[$category])) {
							$this->categoryList[$category]++;
						} else {
							$this->categoryList[$category] = 1;
						}
					}
				}
				Theme::locate_template('post-content', $context, $content);
				if (!isset($args['category'])) {
					$this->write_single_post($content);
				}
			}
		}

		Theme::locate_template('footer', '', $pageNumber);

		Filesystem::write_file($file, $this->get_buffer(), 'w');
	}
	
	public function write_single_post($content) {
		$file = Helpers::sanitize_slug($content->title);
		
		$path = Helpers::trailingslashit(date('Y', $content->raw_date) . '/' . date('m', $content->raw_date) . '/' . date('d', $content->raw_date));
		if (!file_exists(PUBLISHED_PATH . '/' . Helpers::trailingslashit(date('Y', $content->raw_date)))) {
			mkdir(PUBLISHED_PATH . '/' . date('Y', $content->raw_date), 0755);
		}
		if (!file_exists(PUBLISHED_PATH . '/' . date('Y', $content->raw_date) . '/' . date('m', $content->raw_date))) {
			mkdir(PUBLISHED_PATH . '/' . date('Y', $content->raw_date) . '/' . date('m', $content->raw_date), 0755);
		}
		if (!file_exists(PUBLISHED_PATH . '/' . date('Y', $content->raw_date) . '/' . date('m', $content->raw_date) . '/' . date('d', $content->raw_date))) {
			mkdir(PUBLISHED_PATH . '/' . date('Y', $content->raw_date) . '/' . date('m', $content->raw_date) . '/' . date('d', $content->raw_date), 0755);
		}

		$file = PUBLISHED_PATH . '/' . $path . $file . '.html';
		
		$this->start_buffer();
		Theme::locate_template('header');
		Theme::locate_template('post-content', 'single', $content);
		Theme::locate_template('footer', 'single');

		Filesystem::write_file($file, $this->get_buffer(), 'w');
	}
	
	public function write_page($page) {
		$pathPrefix = str_replace(PAGES_PATH, '', $page->content_file);
		$pathPrefix = substr($pathPrefix, 1);
		$pathPrefix = substr($pathPrefix, 0, strpos($pathPrefix, '/'));
		$file = Helpers::sanitize_slug($page->title);
		if ($pathPrefix != '') {
			$pathPrefix = Helpers::trailingslashit(PUBLISHED_PATH) . Helpers::trailingslashit($pathPrefix);
		} else {
			$pathPrefix = Helpers::trailingslashit(PUBLISHED_PATH);
		}
		$pathPrefix = strtolower($pathPrefix);

		if (!file_exists($pathPrefix)) {
			mkdir($pathPrefix, 0755);
		}
		$file = $pathPrefix . $file . '.html';

		if (strpos($page->content_file, '404.md') !== false) {
			$file = PUBLISHED_PATH . '/404.html';
		}
		
		$this->start_buffer();
		Theme::locate_template('header');
		Theme::locate_template('page', '', $page);
		Theme::locate_template('footer', 'single');

		Filesystem::write_file($file, $this->get_buffer(), 'w');
	}
	
	public function write_html_sitemap($contents) {
		$blogPosts = $contents['blogPosts'];
		$pages = $contents['pages'];
		$file = PUBLISHED_PATH . '/sitemap.html';
		$this->start_buffer();
		Theme::locate_template('header');

		$page = (object) array(
			'content_type' => 'page',
			'title'        => 'Home',
			'permalink'    => '/'
		);
		Theme::locate_template('post-content', 'sitemap', $page);

		foreach ($pages as $pageFile) {
			$page = new Page($pageFile);
			if (strpos($page->content_file, '404') === false) {
				Theme::locate_template('post-content', 'sitemap', $page);
			}
		}

		foreach($blogPosts as $content) {
			Theme::locate_template('post-content', 'sitemap', $content);
		}

		Theme::locate_template('footer', 'single');
		Filesystem::write_file($file, $this->get_buffer(), 'w');
	}

	private function complete_process() {
		global $filesWritten;
		
		echo "\n\nProcessed " . $this->publishedPosts . " posts.";
		echo "\nWrote " . $filesWritten . " files.";
		echo "\n\n";
	}
}

function markd_add_generator_header() {
	echo "\n<meta name=\"generator\" content=\"markd\" />\n";
	return;
}

$hooks->add_action('markd_header', 'markd_add_generator_header');