<?php if (have_posts()): ?>

	<div id="loop" class="<?php echo isset($_COOKIE['mode']) && $_COOKIE['mode'] == 'grid' ? 'grid' : 'list'; ?> clear">

	<?php while (have_posts()): the_post(); ?>

		<div <?php post_class('post clear'); ?> id="post_<?php the_ID(); ?>">
			<?php if (has_post_thumbnail()): ?>
			<a href="<?php the_permalink() ?>" class="thumb"><?php the_post_thumbnail('thumbnail', array(
					'alt' => trim(strip_tags($post->post_title)),
					'title' => trim(strip_tags($post->post_title)),
				)); ?></a>
			<?php endif; ?>

			<div class="post-category"><?php the_category(' / '); ?></div>
			<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>

			<div class="post-meta"><?php
				printf(__('by <span class="post-author"><a href="%s" title="Posts by %s">%s</a></span> on <span class="post-date">%s</span>', 'sight'),
					get_author_posts_url(get_the_author_meta('ID')),
					get_the_author(),
					get_the_author(),
					get_the_time('M j, Y')
				); ?>
				<em>&bull; </em><?php comments_popup_link(__('Нет комментариев', 'sight'), __('1 Комментарий', 'sight'), __('% Comments', 'sight'), '', __('Комментарии закрыты', 'sight')); ?>
				<?php edit_post_link(__('Edit entry', 'sight'), '<em>&bull; </em>'); ?>
			</div>
			<div class="post-content"><?php if (function_exists('smart_excerpt')) smart_excerpt(get_the_excerpt(), 55); ?></div>
		</div>

	<?php endwhile; ?>

	</div>

<?php endif; ?>
