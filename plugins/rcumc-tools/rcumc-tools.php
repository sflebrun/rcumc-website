<?php
/*
 Plugin Name:      RCUMC Tools
 Plugin URI:       http://www.lebruns.com/WordPress/Plugins/rcumc-tools
 Description:      Manages custom post types used by RCUMC WordPress Website.  These custom post types are made to work in conjunction with the Exodus theme.
 Author:           Steven F. LeBrun
 Author URI:       http://steven.lebruns.com
 Text Domain:      rcumc_tools
 Domain Path:
*/

/*


*/

require_once('includes/constants.php');
include_once('includes/setup.php');
include_once('includes/status-report.php');
include_once('includes/tools.php');

include_once('widgets/date-range.php');
include_once('widgets/status-report-all.php');
include_once('widgets/status-report-by-name.php');
include_once('widgets/status-report-by-week.php');

/**
 * rcumc_tools_init()
 *
 * Runs all the functions that need to be run during the initialization 
 * phase of WordPress.
 */
function rcumc_tools_init()
{
	// Perform Tools Initialization
	rcumc_tools_initialize();

	// Create the Status Report Custom Post Type
	rcumc_create_status_report_type();

	// Refresh for all.
	flush_rewrite_rules();
	
}   // end of rcumc_tools_init()

/**
 * rcumc_tools_activate()
 *
 * This function runs when the plugin is activated.
 */
function rcumc_tools_activate()
{
	// Nothing to do for activation (yet).
}

/**
 * rcumc_tools_deactivate()
 *
 * This function runs when the plugin is deactivated.
 */
function rcumc_tools_deactivate()
{
	if ( post_type_exists( RCUMC_Status_Report::TYPE_NAME ) )
	{
		// can we actually remove a custom post type?
	}
}

// Register Hooks, Actions and Filters that should be run when the 
// Plugin is activated.
register_activation_hook(__FILE__, 'rcumc_tools_activate');


// Register Hooks, Actions and Filters that should be run when the 
// Plugin is deactivated.
register_deactivation_hook(__FILE__, 'rcumc_tools_deactivate');

// Register Hooks, Actions and Filters that should be run during the
// Initialization phase of WordPress every time a page is requested.
// Note:  Initialization runs before Activation.
add_action( 'init', 'rcumc_tools_init' );
