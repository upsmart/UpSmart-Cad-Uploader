	</div>
	<!-- END #CONTENT-INNER -->
	</div>
	<!-- END #CONTENT-WRAPPER -->	
    <?php 
        get_template_part( 'content','prefooter' ); 	
    ?>
	<!-- FOOTER -->
	<footer>
		<div id="footer-border"></div>
		<div id="footer-inner">
			<span class="alignleft">
			Copyright &copy; <?php echo date('Y'); ?> <strong><a href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a></strong>
			<div id="site-generator">
			
				<small><?php _e('Proudly powered by', 'gamepress'); ?> <a href="http://wordpress.org" target="_blank"><?php _e('WordPress', 'gamepress'); ?></a>. <a href="http://webtuts.pl/themes/" title="<?php _e('GamePress', 'gamepress'); ?>" target="_blank"><?php _e('GamePress', 'gamepress'); ?></a></small>

			</div><!-- #site-generator -->
			</span>
			<span class="alignright"><a href="#" class="scrollup"><?php _e('BACK TO TOP','gamepress'); ?> &uarr;</a></span>
		</div>
		<!-- END #FOOTER-INNER -->
	</footer>
	<!-- END FOOTER -->
</div>
<!-- END #PAGE -->
<?php wp_footer(); ?>
</body>
</html>