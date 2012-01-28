				<div class="post-wrap single">
					<h1 class="post-title"><?php echo $content->title; ?></h1>
					<div class="post-meta">
						Posted <?php echo $content->date; ?>
						<?php
							if (is_array($content->categories) && !empty($content->categories)) {
									echo ' in ';
									foreach ($content->categories as $category) {
										echo '<a href="' . Theme::get_cat_link($category) . '">' . $category . '</a>';
									}
							}
						?>
					</div>
					<div class="post-content"><?php echo $content->html_content; ?></div>
					<div id="disqus_thread"></div>
					<script type="text/javascript">
					    var disqus_shortname = '{{disqus_shortname}}';
						var disqus_identifier = '<?php echo $content->id; ?>';
					    (function() {
					        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
					        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
					        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
					    })();
					</script>
				</div>