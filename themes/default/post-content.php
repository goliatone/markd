<div class="post-wrap well">
	<h2 class="post-title"><a href="<?php echo $content->permalink; ?>"><?php echo $content->title; ?></a></h2>
	<div class="post-meta">
		Posted <?php echo $content->date; ?>
		<?php
			if (is_array($content->categories) && !empty($content->categories)) {
					echo ' in ';
					foreach ($content->categories as $category) {
						echo $category;
					}
			}
		?>

	</div>
	<div class="post-content"><?php echo $content->html_content; ?></div>
	<script type="text/javascript">
	    var disqus_shortname = '{{disqus_shortname}}';
	    (function () {
	        var s = document.createElement('script'); s.async = true;
	        s.type = 'text/javascript';
	        s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
	        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
	    }());
	</script>
	<div class="comment-count">
		<a href="<?php echo $content->permalink; ?>#disqus_thread" data-disqus-identifier="<?php echo $content->id; ?>">Comments</a>
	</div>
</div>
