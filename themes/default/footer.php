			</div> <!-- span10 -->
			<div class="span4">
				<?php $hooks->execute_actions('markd_sidebar'); ?>
				<div class="side-buttons">
					<a class="btn primary" href="/feed">RSS</a>
				</div>
			</div> <!-- span4 -->
		</div> <!-- row -->
		<div class="row">
			<div class="span12">
				<div class="pagination">
					<?php Theme::get_pagination($currentPage); ?>
				</div>
			</div>
			<div class="span4"></div>
		</div> <!-- row -->
	</div> <!-- content -->

    <footer>
      	<p>&copy; Matt Walters 2012 - <?php echo date('Y'); ?></p>
		<?php $hooks->execute_actions('markd_footer'); ?>
    </footer>

</div> <!-- container -->

<script src="/common.js" type="text/javascript"></script>

</body>
</html>