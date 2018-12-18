<div class="sidebar">
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar')):
		$widget_args = array(
			'after_widget' => '</div></div>',
			'before_title' => '<h3>',
			'after_title' => '</h3><div class="widget-body clear">'
		);
	?>

	

	<?php the_widget('Recentposts_thumbnail', 'title= Недавно выложенные', $widget_args); ?>

	<div class="widget sponsors">
		<h3><?php _e('Мы в социальных сетях', 'sight'); ?></h3>
		<div class="widget-body">
			<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/example/sponsor01.jpg" width="200" height="125" alt=""/></a>
			<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/example/sponsor02.jpg" width="200" height="125" alt=""/></a>
			<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/example/sponsor03.jpg" width="200" height="125" alt=""/></a>
		</div>
	</div>
			
	<?php endif; ?>
</div>
