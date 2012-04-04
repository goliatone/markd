<?php

// Paths and URL's should not have trailing slashes

date_default_timezone_set('America/New_York');												// Timezone
define('CONTENT_PATH', '/Users/mwalters/Dropbox/WriteRoom/BlogPosts/MW-net-Blog-Posts');	// Path to where your MarkDown posts are at
define('PUBLISHED_PATH', '/Users/mwalters/Dropbox/github/mwalters.github.com');             // Path to where generated files should be placed
define('THEMES_PATH', '/Users/mwalters/Dropbox/github/markd/themes');						// Path to themes
define('PLUGINS_PATH', '/Users/mwalters/Dropbox/github/markd/plugins');                     // Path to plugins
define('POSTS_PER_PAGE', 5);																// Number of posts per page for post listing pages
define('MARKD_DEBUG', FALSE);																// Can be used to turn on debug notices during generation
                                                                                    
define('SITE_TITLE', 'Matt Walters');                                                       // Title for site
define('SITE_URL', 'http://mattwalters.net');                                               // URL the generated site will sit at
define('SITE_DESC', 'Senior Web &amp; PHP Developer in Richmond, VA');                      // (Optional) Description/tagline for site
                                                                                    
define('ACTIVE_THEME', '/default');															// Folder name of the active theme

// No need to edit below this line
define('POSTS_PATH', CONTENT_PATH . '/posts');
define('PAGES_PATH', CONTENT_PATH . '/pages');
