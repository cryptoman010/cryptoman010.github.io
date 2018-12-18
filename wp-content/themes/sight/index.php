<?php get_header(); ?>

<div class="content-title">
	<?php _e('Последние новости', 'sight'); ?>
	<a href="javascript: void(0);" id="mode"<?php if (isset($_COOKIE['mode']) && $_COOKIE['mode'] == 'grid') echo ' class="flip"'; ?>></a>
</div>

<?php
global $exl_posts;
$args = array('paged' => $paged);
if (!empty($exl_posts)) $args['post__not_in'] = $exl_posts;
query_posts($args);
?>

<?php get_template_part('loop'); ?>

<?php wp_reset_query(); ?>

<?php get_template_part('pagination'); ?>

<?php get_footer(); ?>
