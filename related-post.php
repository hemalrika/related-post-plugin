<?php

/**
 * Plugin Name: Related Post
 * Plugin URI: hemalrika.com
 * Author: HR Foundation
 * Author URI: hemalrika.com
 * Version: 1.0.0
 * Description: This a plugin for showing related post in wordpress theme.
 * */
/**
 * Register Post Type
 * */
function rp_post_type() {
	 $args = array(
	    'public'    => true,
	    'label'     => __( 'Books', 'textdomain' ),
	    'menu_icon' => 'dashicons-book',
	);
	register_post_type( 'book', $args );
}
add_action('init', 'rp_post_type');

function rp_related_post($default) {
	if(is_single()) {
		$category = get_the_terms(get_the_ID(), 'category');
		$category_in = array();
		foreach($category as $cat) {
			$cat_id = $cat->term_id;
			$category_in[] = $cat_id;
		}
		$args = array(
			'post_type' => 'post',
			'category__in' => $category_in,
			'post__not_in' => array(get_the_ID())
		);
		$related_post = new WP_Query($args);
		while($related_post->have_posts()) : $related_post->the_post();
			$default .= '<ul>';
			$default .= '<li>'.get_the_title().'</li>';
			$default .= '</ul>';
		endwhile;
		return $default;
	}
}
add_filter('the_content', 'rp_related_post');

function rp_assets() {
	wp_enqueue_script(PLUGINS_URL('css/related-post.php', __FILE__));
}
add_action('wp_enqueue_scripts', 'rp_assets');