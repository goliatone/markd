			</div> <!-- span10 -->
			<div class="span4">
				<h3>Connect</h3>
				<div class="side-buttons">
					<a target="_blank" href="http://twitter.com/mwalters"><img src="/images/twitter_32.png" alt="Matt Walters on twitter" /></a>
					<a target="_blank" href="http://www.linkedin.com/in/mattswalters"><img src="/images/linkedin_32.png" alt="Matt Walters on Linked In" /></a>
					<a target="_blank" href="https://github.com/mwalters"><img src="/images/github_32.png" alt="Matt Walters on github" /></a>
					<a target="_blank" href="/rss.xml"><img src="/images/rss_32.png" alt="RSS Feed for MattWalters.net" /></a>
				</div>
				<?php $hooks->execute_actions('markd_sidebar'); ?>
				<br/>
				<br/>
				<center><a href="http://db.tt/vIzxb0uf" title="Sign up for a free dropbox account"><img src="/images/sm-banner-dropbox.png" alt="Free Dropbox Account" /></a></center>
			</div> <!-- span4 -->
		</div> <!-- row -->
		<div class="row">
			<div class="span12">
				<div class="pagination">
					<?php if ($context != 'single') { Theme::get_pagination($currentPage); } ?>
				</div>
			</div>
			<div class="span4"></div>
		</div> <!-- row -->
	</div> <!-- content -->

    <footer>
      	<p>&copy; Matt Walters <?php echo date('Y'); ?> &#150; <a href="/sitemap.html">Sitemap</a></p>
		<?php $hooks->execute_actions('markd_footer'); ?>
    </footer>

</div> <!-- container -->

<script src="/js/bootstrap-dropdown.js" type="text/javascript"></script>
<script src="/js/prettify.js" type="text/javascript"></script>
<script src="/js/common.js" type="text/javascript"></script>

</body>
</html>