		<!-- footer -->
		<footer id="footer" class="footer" role="contentinfo">

			<div class="container">

				<!-- Navigation (if any) -->
				<?php nth_footer_nav(); ?>

				<!-- copyright -->
				<p class="copyright">
					&copy; <?php echo date("Y"); ?> Copyright <?php bloginfo('name'); ?>. <?php _e('Powered by', 'html5blank'); ?>
					<a href="//wordpress.org" title="WordPress">WordPress</a> &amp; <a href="//html5blank.com" title="HTML5 Blank">HTML5 Blank</a>.
				</p>
				<!-- /copyright -->

			</div>

		</footer>
		<!-- /footer -->

		<?php wp_footer(); ?>

		<script type="text/javascript">

			(function(require) {

				require.config({
					baseUrl: '<?php echo get_template_directory_uri(); ?>/scripts',
					urlArgs : "v=0.0.1"
				});

				require(['main']);

			})(require);

		</script>

	</body>
</html>
