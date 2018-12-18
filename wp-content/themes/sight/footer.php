			</div>
			<!-- /Content -->

			<?php get_sidebar(); ?>

			</div>
			<!-- /Container -->

			<div class="footer">
				<p class="copyright">&copy; 2018 <a href="<?php echo home_url(); ?>"><?php bloginfo('Denis'); ?></a>.
					<?php _e('Все права защищены.', 'sight'); ?><br />
					
				</p>
				<p class="credits"><?php _e('Сделано руками', 'sight'); ?> <a href="http://wpshower.com">Denis Kazuro</a></p>
			</div>
		</div>
		<!-- Page generated: <?php timer_stop(1); ?> s, <?php echo get_num_queries(); ?> queries -->
		<?php wp_footer(); ?>

		<?php echo (get_option('ga')) ? get_option('ga') : '' ?>
	</body>
</html>
