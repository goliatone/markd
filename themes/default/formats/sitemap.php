<div class="post-wrap sitemap type-<?php echo $content->content_type; ?>">
	<?php
	if ($content->content_type == 'page') {
		global $def_pages_started;
		if (!$def_pages_started) {
			echo '<h2>Pages</h2>';
		}
		$def_pages_started = true;
	?>
		<a href="<?php echo $content->permalink; ?>">
		<?php
		if (strlen($content->title) > 75) {
			echo substr($content->title, 0, 75) . '...';
		} else {
			echo $content->title;
		} ?>
		</a>
	<?php
	} else {
		global $sitemap_year;
		$current_year = date('Y', $content->raw_date);
		if ($current_year != $sitemap_year) {
			echo '<h2>' . $current_year . '</h2>';
		}
		$sitemap_year = $current_year;
		?>
		<?php echo date('F jS', $content->raw_date); ?> &#150; 
		<a href="<?php echo $content->permalink; ?>">
		<?php
		if (strlen($content->title) > 75) {
			echo substr($content->title, 0, 75) . '...';
		} else {
			echo $content->title;
		}
		?>
		</a>
	<?php } ?>
</div>
