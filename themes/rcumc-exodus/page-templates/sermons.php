<?php
/**
 * Template Name: Sermons
 *
 * This shows a page with custom loop after the content.
 *
 * content.php outputs the page content.
 * content-sermon.php outputs content for each post in the loop.
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

// Query events that ended before today
function exodus_sermons_loop_after_content() {

$args = array(  
                'post_type' => 'ctc_sermon',
                'paged'	=> ctfw_page_num(), // returns/corrects $paged so pagination works on static front page
                'posts_per_page' => 6,
                'meta_key' => 'service_date', 
		            'orderby' => 'meta_value_num',
                'order' => 'DESC',
                         
                  );

	return new WP_Query($args);
	

}

// Make query available via filter
add_filter( 'exodus_loop_after_content_query', 'exodus_sermons_loop_after_content' );

// Load main template to show the page
locate_template( 'index.php', true );

