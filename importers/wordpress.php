#!/usr/bin/php
<?php
// NOTE: This is pretty alpha/beta.  You will likely need to do some cleanup of the markdown that is produced and it doesn't convert
// HTML to markdown yet.  It's basically just producing appropriately structured post files for markd
error_reporting(E_ALL ^ E_NOTICE);

require_once('../config.php');
require_once('../classes/Filesystem.php');

$wpContent = Filesystem::read_file($argv[1]);

preg_match_all("#<item>.*?<title>(.*?)</title>.*?<pubDate>(.*?)</pubDate>.*?<content:encoded><!\[CDATA\[(.*?)\]\]></content:encoded>.*?<category.*?><!\[CDATA\[(.*?)\]\]></category>.*?</item>#is", $wpContent, $items, PREG_SET_ORDER); 

array_walk($items, 'filter_wp_posts');

function filter_wp_posts(&$tempPost) {
	$mdPost = "---

Title: " . $tempPost[1] . "

Date: " . date('Y-m-d H:i', strtotime($tempPost[2])) . "

Published: true

Category: " . $tempPost[4] . "

---
" . $tempPost[3];
	
	$filename = date('Y-m-d', strtotime($tempPost[2])) . '.md';
	echo 'Writing: ' . POSTS_PATH . '/' . $filename . "\n";
	Filesystem::write_file(POSTS_PATH . '/' . $filename, $mdPost);
}