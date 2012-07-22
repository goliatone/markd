<?php
/*
Plugin Name: Twitter Timeline Widget
Plugin URI: https://github.com/mwalters/markd
Description: Displays recent tweets in your sidebar
Version: 1.0
Author: Matt Walters
Author URI: http://mattwalters.net/
License: GPL v3
*/

function msw_add_twitter_widget() {
	// Set this to your Twitter username
	$twitterUsername = 'mwalters';

	$twitterContent = "<h3>Latest Tweets</h3>";
	$twitterContent .= "
		<script charset=\"utf-8\" src=\"http://widgets.twimg.com/j/2/widget.js\"></script>
		<script>
		new TWTR.Widget({
		  version: 2,
		  type: 'profile',
		  rpp: 4,
		  interval: 30000,
		  width: 'auto',
		  theme: {
		    shell: {
		      background: '#ffffff',
		      color: '#000000'
		    },
		    tweets: {
		      background: '#ffffff',
		      color: '#666666',
		      links: '#0067d6'
		    }
		  },
		  features: {
		    scrollbar: false,
		    loop: false,
		    live: true,
		    behavior: 'all'
		  }
		}).render().setUser('" . $twitterUsername . "').start();
		</script>
	";

	echo $twitterContent;
	return;
	
}

//$hooks->add_action('markd_sidebar', 'msw_add_twitter_widget');
