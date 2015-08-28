<?php
/**
 * Functions for RCUMC Tools
 */

function rcumc_tools_initialize()
{
	rcumc_register_javascripts();
}

function rcumc_register_javascripts()
{
	$submit_onchange_url = plugins_url( 'js/submit-onchange.js', 
										dirname(__FILE__));

	wp_register_script( 
		RCUMC_Tools::SUBMIT_ONCHANGE,   // Handle name
		$submit_onchange_url            // URL of script file
		);
}