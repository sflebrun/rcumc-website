<?php
/**
 * RCUMC Tools Setup/Options
 */

// Add action to setup plugin options for RCUMC Tools
add_action('admin_menu',  'rcumc_tools_add_option_page');
add_action('admin_init',  'rcumc_tools_register_options');

function rcumc_tools_add_option_page()
{
	add_options_page( RCUMC_Tools::PAGE_TITLE,
					  RCUMC_Tools::MENU_TITLE,
					  'manage_options',
					  RCUMC_Tools::MENU_SLUG,
					  'rcumc_tools_options_setup');
}


function rcumc_tools_options_setup()
{
?>
<div class="wrap">
		<?php screen_icon() ?>
		<h2><?php echo RCUMC_Tools::PAGE_TITLE; ?></h2>
		<form action="options.php" method="post">
		<?php
		settings_fields(      RCUMC_Tools::STATUS_REPORT_GROUP );
	    do_settings_sections( RCUMC_Tools::MENU_SLUG,
                              RCUMC_Tools::STATUS_REPORT_ID );
        ?>
        														
         <p><input type="submit" name="Submit" value="save changes" /></p>
		 </form>
</div>  <!-- .wrap -->

<?php
}   // end of rcumc_tools_options_setup()

function rcumc_tools_register_options()
{
	register_setting(
	    RCUMC_Tools::STATUS_REPORT_GROUP,
		RCUMC_Tools::OPTION_NAME 
			);

	add_settings_section(
	     RCUMC_Tools::STATUS_REPORT_ID,        // HTML ID
		 'Options for Status Report',          // Section Title Text
		 'rcumc_tools_status_report_section',  // Callback
		 RCUMC_Tools::MENU_SLUG                // setting page
	);

	add_settings_field(
	    RCUMC_Tools::OPTION_ROLE_ID,            // HTML ID
		'Status Report User Role' ,             // Label
		'rcumc_tools_status_report_role_input', // Callback to echo form field
		 RCUMC_Tools::MENU_SLUG,                // setting page
	     RCUMC_Tools::STATUS_REPORT_ID          // Section Name
	); 

}   // end of rcumc_tools_register_options()

function rcumc_tools_status_report_section( $args )
{
}

function rcumc_tools_status_report_role_input( $args )
{
	global $wp_roles;

	// Get Option value from database, if it already exists
	$options = get_option( RCUMC_Tools::OPTION_NAME );

	$roles = $wp_roles->get_names();

	$role_value = ( $options && isset($options[ RCUMC_Tools::OPTION_ROLE ]) ) ?
		$options[ RCUMC_Tools::OPTION_ROLE ] : '' ;

?>
	<select name="<?php echo RCUMC_Tools::OPTION_NAME . "[" . 
                             RCUMC_Tools::OPTION_ROLE . "]"; ?>" >
<?php
	 echo PHP_EOL;
	if ( '' == $role_value || '-1' == $role_value )
	{
	echo '<option value="-1" selected>Select Role</option>' . PHP_EOL;
     }
	 foreach  ($roles as $role => $display_name)
	 {
	     if ( $role_value == $role )
	     {
	         echo '<option value="' . $role . '" selected >';
         }
	     else
	     {
	         echo '<option value="' .  $role . '">';
         }
         echo $display_name . '</option>' . PHP_EOL;
     }
?>
     </select>
<?php

}

