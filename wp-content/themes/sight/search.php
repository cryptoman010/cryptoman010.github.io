<?php get_header(); ?>

<?php if (have_posts()): ?>

	<div class="content-title">
		<?php _e('Результаты поиска', 'sight'); ?> <span>/</span> <?php the_search_query(); ?>
		<a href="javascript: void(0);" id="mode"<?php if (isset($_COOKIE['mode']) && $_COOKIE['mode'] == 'grid') echo ' class="flip"'; ?>></a>
	</div>

	<?php get_template_part('loop'); ?>

<?php else: ?>

	<div class="content-title">
		<?php printf(__('Ваш поиск <strong>%s</strong> did not match any documents', 'sight'), get_search_query()); ?>
	</div>

	<div class="entry">
		<div class="single clear">
			<div class="post-content">
				<div class="search_form">
					<form method="get" id="searchform" action="<?php echo home_url(); ?>">
						<fieldset>
	<?php $button = __('Search with some different keywords', 'sight'); ?>
							<input name="s" type="text" onfocus="if(this.value=='<?php echo $button; ?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo $button; ?>';" value="<?php echo $button; ?>" />
							<button type="submit"></button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>

<?php endif; ?>

<?php get_template_part('pagination'); ?>

<?php get_footer(); ?>
