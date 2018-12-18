<?php get_header(); ?>

<div class="content-title">
	<?php _e("404!Мы не можем найти запрашиваемую страницу!", 'sight'); ?>
</div>

<div class="entry">
	<div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
		<div class="post-content">
			<p><?php _e("Страница, которую вы пытаетесь найти отсутствует.Проверьте правильность введеной страницы.", 'sight'); ?></p>
		</div>
	</div>
</div>

<?php get_footer(); ?>
