<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$extra_class_name = '';

$extra_class_name .= ' '.$heading_position;

$columns = (isset($columns) && !empty($columns)) ? $columns : 3;

$data_atts .= ' data-columns="'.esc_attr($columns).'"';
$data_atts .= ' data-layout-style="'.esc_attr($style).'"';
$data_atts .= ' data-item="project"';

if(isset($enabled_autoslideshow) && !empty($enabled_autoslideshow)) {
	$data_atts .= ' data-enable_slideshow="'.esc_attr($enabled_autoslideshow).'"';
	if(isset($carousel_slideshow_speed) && !empty($carousel_slideshow_speed)) {
		$data_atts .= ' data-slideshow_speed="'.esc_attr($carousel_slideshow_speed).'"';
	}
}

$js_scripts .= 'if(typeof jQuery.fn.initPostsCarousel !== "undefined") {
					jQuery("#'.esc_js($uniqid).' .dfd-portfolio-carousel").initPostsCarousel();
				}';

if(isset($items_offset)) {
	$css_rules .= '#'.esc_attr($uniqid).' .dfd-portfolio {margin: -'.esc_attr($items_offset/2).'px;}';
	$css_rules .= '#'.esc_attr($uniqid).' .dfd-portfolio .cover {padding: '.esc_attr($items_offset/2).'px;}';
	$css_rules .= '#'.esc_attr($uniqid).'.dfd-portfolio-loop .dfd-portfolio .project .cover .dfd-folio-heading-wrap {left: '.esc_attr($items_offset/2).'px;right: '.esc_attr($items_offset/2).'px;}';
}

if($folio_hover_plus_bg && !empty($folio_hover_plus_bg)) {
	switch($folio_hover_plus_position) {
		case 'dfd-top-right' :
		case 'dfd-bottom-right' :
			$css_rules .= '#'.esc_attr($uniqid).' .project .entry-thumb .portfolio-custom-hover .plus-link:before {border-right-color: '.esc_attr($options['folio_hover_plus_bg']).';}';
			break;
		case 'dfd-top-left' :
		case 'dfd-bottom-left' :
			$css_rules .= '#'.esc_attr($uniqid).' .project .entry-thumb .portfolio-custom-hover .plus-link:before {border-left-color: '.esc_attr($options['folio_hover_plus_bg']).';}';
			break;
	}
}

?>
<div class="dfd-portfolio-loop dfd-portfolio-module <?php echo esc_attr($el_class) ?>" id="<?php echo esc_attr($uniqid) ?>">
	<div class="dfd-portfolio-wrap">
		
		<div class="dfd-portfolio dfd-portfolio-<?php echo esc_attr($style .' '.$extra_class_name .' '. $anim_class) ?>" <?php echo $data_atts ?>>
		<?php
			while ($wp_query->have_posts()) : $wp_query->the_post();

				$permalink = get_permalink();

				$excerpt = get_the_excerpt();

				if(!empty($excerpt))
					$excerpt = '<div class="entry-content '.esc_attr($content_alignment).'"><p>'.$excerpt.'</p></div>';

				$post_class = 'project';

				$post_class .= ' '.$folio_hover_style_class;
				
				?>
				<div class="<?php echo esc_attr($post_class) ?>" <?php echo $article_data_atts; ?>>
					<div class="cover <?php echo esc_attr($content_alignment) ?>">
						<?php
						$caption = get_the_title();
						if (has_post_thumbnail()) {
							$thumb = get_post_thumbnail_id();
							$img_url = wp_get_attachment_image_src($thumb, 'full'); //get img URL

							$img_src = isset($img_url[0]) && !empty($img_url[0]) ? $img_url[0] : '';

							if(!isset($image_width) || empty($image_width))
								$image_width = 900;

							if(!isset($image_height) || empty($image_height))
								$image_height = 600;

							$img_url = dfd_aq_resize($img_src, $image_width, $image_height, true, true, true);

							if(!$img_url) {
								$img_url = $img_src;
							}
							
							$meta = wp_get_attachment_metadata($thumb);
							if(isset($meta['image_meta']['caption']) && $meta['image_meta']['caption'] != '') {
								$caption = $meta['image_meta']['caption'];
							} else if(isset($meta['image_meta']['title']) && $meta['image_meta']['title'] != '') {
								$caption = $meta['image_meta']['title'];
							}
							
						} else {
							$img_url = get_template_directory_uri() . '/assets/images/no_image_resized_675-450.jpg';
						}
						?>
						<div class="entry-thumb <?php echo esc_attr($media_class) ?>">
							<img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($caption); ?>"/>
							<?php include(locate_template('inc/vc_custom/dfd_vc_addons/templates/portfolio/template_parts/comments_likes.php')); ?>
							<?php include(locate_template('templates/portfolio/custom-hover.php')); ?>
						</div>


						<?php include(locate_template('inc/vc_custom/dfd_vc_addons/templates/portfolio/template_parts/heading.php')); ?>

						<?php
						if($enable_excerpt)
							echo $excerpt;

						if($read_more || $share) : ?>
							<div class="dfd-read-share clearfix">
								<?php if($read_more) : ?>
									<div class="read-more-wrap">
										<a href="<?php echo esc_url($permalink) ?>" class="more-button <?php echo esc_attr($read_more_style) ?>" title="<?php __('Read more','dfd') ?>" data-lang="en"><?php _e('More', 'dfd'); ?></a>
									</div>
								<?php endif; ?>
								<?php if($share) : ?>
									<div class="dfd-share-cover dfd-share-<?php echo esc_attr($share_style);  ?>">
										<?php get_template_part('templates/entry-meta/mini','share-blog') ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();
		?>
		</div>
	</div>
</div>