<?php get_header(); ?>

	<?php if (have_posts()): ?>
		<?php while (have_posts()): the_post(); ?>

		<div class="content-title">
			<?php the_category(' <span>/</span> '); ?>
			<a href="http://facebook.com/share.php?u=<?php the_permalink() ?>&amp;t=<?php echo urlencode(the_title('','', false)) ?>" target="_blank" class="f" title="<?php _e('Share on Facebook', 'sight'); ?>"></a>
			<a href="http://twitter.com/home?status=<?php the_title(); ?> <?php echo getTinyUrl(get_permalink($post->ID)); ?>" target="_blank" class="t" title="<?php _e('Spread the word on Twitter', 'sight'); ?>"></a>
			<a href="http://digg.com/submit?phase=2&amp;url=<?php the_permalink() ?>&amp;title=<?php the_title(); ?>" target="_blank" class="di" title="<?php _e('Bookmark on Del.icio.us', 'sight'); ?>"></a>
			<a href="http://stumbleupon.com/submit?url=<?php the_permalink() ?>&amp;title=<?php echo urlencode(the_title('','', false)) ?>" target="_blank" class="su" title="<?php _e('Share on StumbleUpon', 'sight'); ?>"></a>
		</div>

		<div class="entry">
			<div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
				<div class="post-meta">
					<h1><?php the_title(); ?></h1>
					<?php printf(__('by <span class="сделано-автор"><a href="%s" title="Posts by %s">%s</a></span> on <span class="post-date">%s</span> &bull; <span>%s</span>', 'sight'),
						get_author_posts_url(get_the_author_meta('ID')),
						get_the_author(),
						get_the_author(),
						get_the_time('M j, Y'),
						get_the_time()
					); ?>
					<?php edit_post_link(__('Edit entry', 'sight'), '&bull; '); ?><a
						href="#comments" class="post-comms"><?php comments_number(__('No Comments', 'sight'), __('1 Comment', 'sight'), __('% Comments', 'sight'), '', __('Comments Closed', 'sight') ); ?></a></div>
				<div class="post-content"><?php the_content(); ?></div>
				<div class="post-footer"><?php the_tags('<strong>'.__('Tags:', 'sight').' </strong>', ', '); ?></div>
				<?php wp_link_pages(array(
					'before' => '<p class="page-links"><span class="page-links-title">'.__('Pages:', 'sight').'</span>',
					'after' => '</p>',
					'link_before' => '<span>',
					'link_after' => '</span>',
				)); ?>
			</div>
			<div class="post-navigation clear">
				<?php
					$prev_post = get_adjacent_post(false, '', true);
					$next_post = get_adjacent_post(false, '', false); ?>
					<?php if ($prev_post): $prev_post_url = get_permalink($prev_post->ID); $prev_post_title = $prev_post->post_title; ?>
						<a class="post-prev" href="<?php echo $prev_post_url; ?>"><em><?php _e('Предыдущая новость', 'sight'); ?></em><span><?php echo $prev_post_title; ?></span></a>
					<?php endif; ?>
					<?php if ($next_post): $next_post_url = get_permalink($next_post->ID); $next_post_title = $next_post->post_title; ?>
						<a class="post-next" href="<?php echo $next_post_url; ?>"><em><?php _e('Следующая новость', 'sight'); ?></em><span><?php echo $next_post_title; ?></span></a>
					<?php endif; ?>
				<div class="line"></div>
			</div>
		</div>

		<?php endwhile; ?>
	<?php endif; ?>

<?php comments_template(); ?>

<?php get_footer(); ?>
