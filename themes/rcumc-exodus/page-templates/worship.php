<?php
/**
 * Template Name: Worship Services
 *
 * This shows a page with custom loop after the content.
 *
 * content-event-header.php outputs the header for each post in the loop.
 * content-event.php outputs content for each post in the loop.
 */

// Query listings according to selected options set using Advance Custom Fields

function rcumc_worship_loop_after_content() {

$order = get_field('order');
$condition = get_field ('condition');

     $args          = array( 
                     'post_type' => 'event',
                     'posts_per_page' => 6,
                     'event-category' => 'worship',                 
                     'event_start_after' => 'now',
                     'orderby' =>  'eventstart',
                     'order' => $order,
                    
                                        		
                      ); 

     
     return  new WP_Query($args);
}

// Make query available via filter
add_filter( 'exodus_loop_after_content_query', 'rcumc_worship_loop_after_content' );

// Load main template to show the page
locate_template( 'index.php', true );
