<?php
/**
 * Exodus Child Theme 
 * 
 * Code customizations are best made using a child theme so they are not lost during parent theme updates.
 *
 * See the guides for more information on using a child theme (changing styles, overriding templates, etc.).
 * 
 * http://churchthemes.com/guides/developer/child-theming/
 * http://codex.wordpress.org/Child_Themes
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

//Add Custom Post Types

add_action( 'init', 'register_rcumc_post_types' );
//Add Publications
function register_rcumc_post_types() {
     $labels = array(
                 'name' => 'Publications',  
                 'singular_name' => 'Publication',
                 'add_new'            => 'Add New',
		 'add_new_item'       => 'Add Publication',
		 'new_item'           => 'New Publication',
		 'edit_item'          => 'Edit Publication',
		 'view_item'          => 'View Publication',
		 'all_items'          => 'All Publications',
		 'search_items'        => 'Search Publications'
               
                  );
               
     $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'taxonomies' => array('pubtype'), 
            'rewrite'    => array('slug' => 'publications'), 
            'supports' => array( 'title', 'editor', 'author','thumbnail', 'excerpt', 'comments' ),
            'menu_position' => 5,
            'menu_icon' => 'dashicons-media-document',
            );
register_post_type ('rcumc_publications', $args);
flush_rewrite_rules();

}

//Add Custom Taxonomies for Publications

add_action( 'init', 'rcumc_define_taxonomy' );
function rcumc_define_taxonomy() {
     $labels = array(
              'name'               => 'Types',
              'singular_name'      => 'Type',
              'add_new_item'        => 'Add New Type',
              'new_item_name'      => 'New Type Name',
              'edit_item'          => 'Edit Type',
	      'view_item'          => 'View Type',
	      'all_items'          => 'All Types',
	      'search_items'        => 'Search Types',
              'update_item'         => 'Update Type'
             );
     $args = array(
             'labels' => $labels,
             'hierarchical' => true,
             'query_var' => true,
             'show_admin_column' => true,
             'rewrite' => true,
             );

register_taxonomy(
       'pubtype',
       'rcumc_publications',
       $args
       );
flush_rewrite_rules();

//Add Custom Worship Taxonomies for Events

     $labels = array(
              'name'               => 'Music Groups',
              'singular_name'      => 'Music Group',
              'add_new_item'        => 'Add New Music Group',
              'new_item_name'      => 'New Music Group',
              'edit_item'          => 'Edit Music Group',
	      'view_item'          => 'View Music Group',
	      'all_items'          => 'All Music Groups',
	      'search_items'        => 'Search Music Groups',
              'update_item'         => 'Update Music Group'
             );
     $args = array(
             'labels' => $labels,
             'hierarchical' => true,
             'query_var' => true,
             'show_admin_column' => true,
             'rewrite' => true,
             );

register_taxonomy(
       'musicians',
       'event',
       $args
       );

     $labels = array(
              'name'               => 'Liturgical Information',
              'singular_name'      => 'Liturgical Information',
              'add_new_item'        => 'Add New Liturgical Information',
              'new_item_name'      => 'New Liturgical Information Name',
              'edit_item'          => 'Edit Liturgical Information',
	      'view_item'          => 'View Liturgical Information',
	      'all_items'          => 'All Liturgical Information',
	      'search_items'        => 'Search Liturgical Information',
              'update_item'         => 'Update Liturgical Information'
             );
     $args = array(
             'labels' => $labels,
             'hierarchical' => true,
             'query_var' => true,
             'show_admin_column' => true,
             'rewrite' => true,
             );

register_taxonomy(
       'liturgical_info',
       'event',
       $args
       );


 
flush_rewrite_rules();
}


//Create Widget for Worship Events

add_action( 'widgets_init', 'init_worship_widget' );
function init_worship_widget() { return register_widget('worship_widget'); }

class Worship_Widget extends WP_Widget
{
function __construct() {
	parent::__construct(
		'worship_widget', // Base ID
		'Worship Widget', // Name
		array('description' => __( 'Displays upcoming worship services with details: sermon title, scripture, music groups, liturgical information.'))
	   );
}
  

function getWorshipListings($numberOfListings) { //html
		global $post;
		add_image_size( 'worship_widget_size', 85, 45, false );
		$listings = new WP_Query(array( 
                     'post_type' => 'event',
                     'posts_per_page' => $numberOfListings,
                     'event-category' => 'worship',
                     'event_start_after' => 'now',
                     'orderby' =>  'eventstart',
                     'order' => 'ASC',));
                     		
                       	if($listings->found_posts > 0) {
			echo '<ul class="rcumc_widget_entry">';
				while ($listings->have_posts()) {
					$listings->the_post();
				$listItem = '<ul>';  
				if (has_post_thumbnail()) { $image = get_the_post_thumbnail($post->ID, 'worship_widget_size');
				    $listItem .= $image;}
                                    $format = 'F j,  g:i a';
                                    $date= eo_get_the_start('n/j/y  g:i a');
                                    echo  '<h2>' . $date . '</h2>';    
                                                                     
                       		$listItem .= ' <a href="' . get_permalink() . '">';
				$listItem .= get_the_title() . ' </a></ul>';
				echo $listItem;
				if ( $liturgical_info = get_the_term_list( $post->ID, 'liturgical_info', '', __( ', ', 'exodus' ) ) ) 
				{echo "<ul> $liturgical_info </ul>";}
				if ( $musicians = get_the_term_list( $post->ID, 'musicians', 'Music:  ', __( ', ', 'exodus' ) ) )
				{echo "<ul> $musicians </ul>";}	
	       			$content = get_the_content();
	      			echo '<ul class=worship_content>' . $content . '</ul><br/>';
				}				
			echo '</ul>';
			wp_reset_postdata();
		}else{
			echo '<p style="padding:25px;">No listing found</p>';
		}
	}


function form($instance) {
	if( $instance) {
		$title = esc_attr($instance['title']);
		$numberOfListings = esc_attr($instance['numberOfListings']);
	} else {
		$title = '';
		$numberOfListings = '';
	}
	?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'worship_widget'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('numberOfListings'); ?>"><?php _e('Number of Listings:', 'worship_widget'); ?></label>
		<select id="<?php echo $this->get_field_id('numberOfListings'); ?>"  name="<?php echo $this->get_field_name('numberOfListings'); ?>">
			<?php for($x=1;$x<=10;$x++): ?>
			<option <?php echo $x == $numberOfListings ? 'selected="selected"' : '';?> value="<?php echo $x;?>"><?php echo $x; ?></option>
			<?php endfor;?>
		</select>
		</p>
	<?php
	}

function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['numberOfListings'] = strip_tags($new_instance['numberOfListings']);
		return $instance;
}

function widget($args, $instance) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		$numberOfListings = $instance['numberOfListings'];
		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		$this->getWorshipListings($numberOfListings);
		echo $after_widget;
	}
}
//end class Worship_Widget
register_widget('Worship_Widget');


/**
 * Returns HTML mark-up for a list of event meta information.
 *
 * Uses microformat.
 * @since 1.7
 * @ignore
 * @param int $post_id The event (post) ID. Uses current event if not supplied
 * @return string|bool HTML mark-up. False if an invalid $post_is provided.
*/
function eo_get_event_meta_list_rcumc( $event_id = 0 ){

	$event_id = (int) ( empty( $event_id ) ? get_the_ID() : $event_id);

	if( empty( $event_id ) ){ 
		return false;
	}

	$html  = '<ul class="eo-event-byline" style="margin:10px 0px;">';
	$venue = get_taxonomy( 'event-venue' );

	if( ( $venue_id = eo_get_venue( $event_id ) ) && $venue ){
		$html .= sprintf(
			'<ul> <a href="%s">
				<span itemprop="location" itemscope itemtype="http://data-vocabulary.org/Organization">
					<span itemprop="name">%s</span>
					
				</span>
			</a></ul>',
		
			eo_get_venue_link( $venue_id ), 
			eo_get_venue_name( $venue_id )
			
			
		);
	}

	
	$html .='</ul>';

	/**
	 * Filters mark-up for the event details list.
	 *
	 * The event details list is just a simple list containing details pertaining
	 * to the event (venue, categories, tags) etc.
	 *
	 * @param array $html The generated mark-up
	 * @param int $event_id Post ID of the event
	 */
	$html = apply_filters( 'eventorganiser_event_meta_list', $html, $event_id );
	return $html;
}




/**
 * Add more information to the event tooltip in the Calendar.
 * Requires Event Organiser 1.6+
 *
 * Adds additional worship information appearing in the tooltip when hovering over an event in fullcalendar
 * @uses eventorganiser_event_tooltip filter.
 *
*/

add_filter('eventorganiser_event_tooltip', 'rcumc_event_tooltip_content', 10,2);
function rcumc_event_tooltip_content( $description, $post_id){

	$musicians = get_the_terms($post_id, 'musicians');
       if (!is_wp_error($musicians)) 
          {
          if  ($musicians) :
            $musicians_links = array();
	    foreach ( $musicians as $musician ) {
          	$musicians_names[] = $musician->name;
	         }
            $musician_list = join("," , $musicians_names);
            $description = $description . '</br></br>' . 'Musicians:' . $musician_list;
           endif;
	return $description; 
	}     
}



// Perform setup on after_setup_theme hook
// Default priority is 10 so 11 ensures this to run after the parent theme's setup
add_action( 'after_setup_theme', 'exodus_child_setup', 11 );

// Setup theme features, actions, filters, etc.
function exodus_child_setup() {

	// Load child theme language file
	// This will cause $locale.mo (e.g. en_US.mo) in the child theme's directory to load.
	// Optionally, it can go in wp-content/languages/themes/exodus-child-$locale.mo.
	//load_child_theme_textdomain( 'exodus-child' ); // use your own textdomain

		
	// Example of removing a function that is hooked
	// remove_action() works similarly
	// add_filter() and add_action() can subsequently be used to replace the hooked function
	//remove_filter( 'body_class', 'exodus_add_body_classes' ); // extra classes no longer added to <body>

	// Remove support for Events (Church Theme Content plugin)
	// Similarly, you can use add_theme_support to enable features
remove_theme_support( 'ctc-events' ); // removes post type, widgets, etc.

	// Remove a widget (Church Theme Framework)
remove_theme_support( 'ctfw-widget-events' );	

	// Remove a WordPress feature
	//remove_theme_support( 'automatic-feed-links' );

}