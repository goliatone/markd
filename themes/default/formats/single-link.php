				<div class="post-wrap well format-link">
					<h2 class="post-title"><a target="_blank" href="<?php echo $content->link; ?>"><?php echo $content->title; ?></a></h2>
					<div class="post-meta">
						Posted <a href="<?php echo $content->permalink; ?>"><?php echo $content->date; ?></a>
						<?php
							if (is_array($content->categories) && !empty($content->categories)) {
									echo ' in ';
									foreach ($content->categories as $category) {
										echo '<a href="' . Theme::get_cat_link($category) . '">' . $category . '</a>';
									}
							}
						?>
					</div>
					<div class="post-content">
						<?php echo $content->html_content; ?>
						<p>
							<small><a target="_blank" href="<?php echo $content->link; ?>"><?php echo $content->title; ?></a></small>
						</p>
					</div>
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