<?php
/**
* Posts Class
*/
class Posts {
	public static function get_posts($startPostNum, $numberOfPosts, $args = array()) {
		if ($startPostNum === '' || $numberOfPosts == '' || $numberOfPosts < 1) { return FALSE; }

		$returnPostListing = array();

		$loopCtr = 0;
		do {
			$loopCtr++;
			// Get listing of all posts in the directory
			$postListing = Filesystem::list_directory(POSTS_PATH);

			// Check that for published posts and get their published date
			foreach ($postListing as $postFile) {
				$returnPost = true;
				$post = new Post($postFile);
				
				if ($post->published != true) {
					$returnPost = false;
				}

				if ($post->raw_date > time()) {
					$returnPost = false;
				}

				if (isset($args['category'])) {
					if (!in_array($args['category'], $post->categories)) {
						$returnPost = false;
					}
				}

				if ($returnPost) {
					$sortedPostListing[$post->date] = $postFile;	
				}
			}

			// Sort Postings by published date in reverse chronological order
			arsort($sortedPostListing);
			// Slice the array of Posts down to what was requested
			$sortedPostListing = array_slice($sortedPostListing, $startPostNum, $numberOfPosts);

			// Create array of Post objects to be returned
			if (!empty($postListing)) {
				foreach ($sortedPostListing as $postFile) {
					$post = new Post($postFile);
					if ($post->published == 'true' && count($returnPostListing['blogPosts']) < $numberOfPosts) {
						$returnPostListing['blogPosts'][] = $post;
					}
				}
			}
			
			$startPostNum = $startPostNum + $numberOfPosts;
		} while (count($returnPostListing['blogPosts']) < $numberOfPosts && count($postListing) == $numberOfPosts && $loopCtr <= Posts::get_total_post_count());

		return $returnPostListing;
	}

	public static function get_total_post_count($args = array()) {
		$postListing = Filesystem::list_directory(POSTS_PATH);
		return count($postListing);
	}
}
