<?php
/**
 * RCUMC Status Report
 *
 * The Status Report is a weekly report that the staff fills out each week .
 *
 * Status Report:
 * ==============
 *
 * Due Date:  Date that the Status is due.
 * Person:    The person filling out the status report.
 * DoneText:  Recap of what has been done since last status report.
 * ToDoText:  Things the person is planning to do for then next status report.
 * CollText:  Request for collaberation.  People and what work to do together.
 * State:     Derived from post_status based on whether the report has been
 *            published or not.
 *            In Progress == Not Published (assumed to still be Draft)
 *            Submitted   == Published.
 *
 * Status Report Meta Data:
 * ========================
 *
 * Role:      The role assigned to people who are filling out status reports.
 * Due Day:   The day of the week that the status reports are due.
 *
 */

require_once('constants.php');
include_once('status-report-meta.php');

/**
 * Creates a Status Report Custom Post Type.
 *
 * This function should be run during the Activation stage since it only
 * needs to run once when the custom post type is created.
 *
 * This function is called during the INIT action phase.
 */
function rcumc_create_status_report_type()
{
	$labels = array(
		'name'               => __('Status Reports',          RCUMC_TOOLS_TD),
		'singular_name'      => __('Status Report',           RCUMC_TOOLS_TD),
        'add_new'            => __('Add New Status Report',   RCUMC_TOOLS_TD),
		'add_new_item'       => __('Add New Status Report',   RCUMC_TOOLS_TD),
		'edit_item'          => __('Edit Status Report',      RCUMC_TOOLS_TD),
		'new_item'           => __('New Status Report',       RCUMC_TOOLS_TD),
		'view_item'          => __('View Status Report',      RCUMC_TOOLS_TD),
        'all_items'          => __('All Status Reports',      RCUMC_TOOLS_TD),
		'menu_name'          => __('Status Reports',          RCUMC_TOOLS_TD),
        'name_admin_bar'     => __('Status Report',           RCUMC_TOOLS_TD),
		'search_items'       => __('Search Status Reports',   RCUMC_TOOLS_TD),
		'not_found'          => __('No Status Reports found', RCUMC_TOOLS_TD),
		'not_found_in_trash' => __('No Status Reports found in Trash', 
								   RCUMC_TOOLS_TD),
		);

	$args = array(
		'description'           => __('Weekly Status Reports by Staff.',
									  RCUMC_TOOLS_TD),
		'public'                => true,
		'show_ui'               => true,
///		'publicly_queryable'    => true,
///		'exclude_from_search'   => false,
		'show_in_nav_menus'     => true,
		'supports'              => array('revisions'),
		'labels'                => $labels,
		'hierarchical'          => false,
		'has_archive'           => true,
		'can_export'            => true,
//		'taxonomies'            => null, // Currently not used.
        'menu_position'         =>  21,
        'menu_icon'             =>  'dashicons-book',
		'show_in_menu'          =>  true,
		'show_in_admin_bar'     =>  true,
		'map_meta_cap'          =>  true,
		'capability_type'       =>  RCUMC_Status_Report::TYPE_NAME,
//		'capabilities'          =>  array(),
///		'query_var'             =>  true,
		'rewrite'               => array(
			'slug'        => RCUMC_Status_Report::SLUG,
			'with_front'  => false,
			),
		);

	register_post_type( RCUMC_Status_Report::TYPE_NAME, $args );

	add_action('add_meta_boxes', 'rcumc_create_status_report_metabox' );

	// NOTE: Action save_post_{post_type} works starting with WordPress 3.7
	add_action('save_post_' . RCUMC_Status_Report::TYPE_NAME,      
			   'rcumc_status_report_meta_save', 10, 3 );

	add_filter( 'wp_insert_post_data', 
				'rcumc_status_report_set_title', 
				10, 2 );

	return;

}   // end of rcumc_create_status_report_type()
