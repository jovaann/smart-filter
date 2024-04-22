<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	// Layout: Grid Layout 2
    while ($query->have_posts()) : $query->the_post();

	    global $post;

        // Get data
	    $post_id = get_the_ID();
	    $title   = get_the_title($post_id);
	    $link    = ( $ymc_popup_status === 'off' ) ? get_the_permalink($post_id) : '#';
	    $length_excerpt = !empty($ymc_post_elements['length_excerpt']) ? esc_attr($ymc_post_elements['length_excerpt']) : 30;
		$button_text = !empty($ymc_post_elements['button_text']) ? $ymc_post_elements['button_text'] : 'Read More';
		$class_popup = ( $ymc_popup_status === 'off' ) ? '' : 'ymc-popup';
	    $post_date_format = apply_filters('ymc_post_date_format_'.$filter_id.'_'.$target_id, 'd, M Y');
		$image_post = null;

	    if( has_post_thumbnail($post_id) ) {
		    $image_post = get_the_post_thumbnail($post_id, 'full');
        }

	    $content = $post->post_excerpt;
	    if( empty($content) ) {
		    $content = $post->post_content;
	    }

	    $content  = preg_replace('#\[[^\]]+\]#', '', $content);
	    $c_length = apply_filters('ymc_post_excerpt_length_'.$filter_id.'_'.$target_id, $length_excerpt);
	    $content  = wp_trim_words($content, $c_length);

	    $read_more = apply_filters('ymc_post_read_more_'.$filter_id.'_'.$target_id, __($button_text,'ymc-smart-filter'));
	    $target = "target=" . $ymc_link_target . "";

	    $list_categories = '';

	    if( is_array($taxonomy) && count($taxonomy) > 0 ) {

		    foreach ( $taxonomy as $tax ) {

			    $term_list = get_the_terms($post_id, $tax);

			    if( $term_list ) {
				    foreach($term_list as $term_single) {
					    $list_categories .= '<span class="cat-inner">'. esc_html($term_single->name) .'</span>';
				    }
			    }
		    }
	    }
		
		/* grab the url for the full size featured image */
		$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');

		echo '<article style="background-image: linear-gradient(180deg,rgba(16, 16, 24, 0.3),rgba(16, 16, 24, 1)),url('.esc_url($featured_img_url).')" class="ymc-'.esc_attr($post_layout).' post-'.get_the_id().' post-item '.esc_attr($class_animation).'">';
		
		echo '<div class="post-layout2-content-wrapper">';		

		if( !empty($list_categories) && $ymc_post_elements['tag'] === 'show' ) :
		echo '<div class="category">'. wp_kses_post($list_categories) .'</div>';
		endif;

	    if( $ymc_post_elements['title'] === 'show' ) :
        echo '<a data-postid="'.esc_attr($post_id).'" '. esc_attr($target) .' href="'. esc_url($link) .'"><header class="title">'. esc_html($title) .'</header></a>';
	    endif;

	    if( $ymc_post_elements['date'] === 'show' ) :
        echo '<span class="date"><i class="far fa-calendar-alt"></i> '. get_the_date($post_date_format) . '</span>';
	    endif;

	    if( $ymc_post_elements['author'] === 'show' ) :
        echo '<span class="author"><i class="far fa-user"></i> '. get_the_author() . '</span>';
	    endif;

	    if( $ymc_post_elements['excerpt'] === 'show' ) :
        echo '<div class="excerpt">'. wp_kses_post($content) .'</div>';
	    endif;

	    if( $ymc_post_elements['button'] === 'show' ) :
        echo '<div class="read-more"><a class="btn btn-read-more '.esc_attr($class_popup).'" data-postid="'.esc_attr($post_id).'" '. esc_attr($target) .' href="'. esc_url($link) .'">'. esc_html($read_more) .'</div>';
	    
		endif;
		echo '</div>';
		echo '</article>';

    endwhile;