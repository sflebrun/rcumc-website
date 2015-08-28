<?php
/**
 * Defines constants used by RCUMC Status Report Plugin.
 */


/**
 * Define name of the Text Domain used by RCUMC Tools,
 * primarily for Internationalization.
 */
define('RCUMC_TOOLS_TD', 'rcumc_sr_namespace');


class RCUMC_Tools
{
	const  SUBMIT_ONCHANGE     = 'rcumc-tools-submit-onchange';

	const  PAGE_TITLE          = 'RCUMC Tools Plugin';
	const  MENU_TITLE          = 'RCUMC Tools';
	const  MENU_SLUG           = 'tools-setup';

	const  OPTION_NAME         = 'rcumc_tools_options';

	// Status Report Options constants
	const  STATUS_REPORT_PAGE  = 'rcumc_status_report_page';
	const  STATUS_REPORT_ID    = 'rcumc_status_report_options';
	const  STATUS_REPORT_GROUP = 'rcumc_status_report_options';
	const  OPTION_ROLE_ID      = 'rcumc_status_report_role';
	const  OPTION_ROLE         = 'role';
}

/**
 * Defines constants used for the custom post type rcumc_status_report.
 */

class RCUMC_Status_Report
{
	const  TYPE_NAME           = 'rcumc_status_report';
	const  METABOX_NAME        = 'rcumc_status_report_metabox';
	const  SLUG                = 'status-report';
}

/**
 * Defines the names of the metadata of a Status Report.
 * These are used as the metadata key names.
 */
class RCUMC_Status_Report_Fields
{
	const TITLE                = 'rcumc_sr_title';
	const DUE_DATE             = 'rcumc_sr_due_date';
	const PERSON_ID            = 'rcumc_sr_person_id';
	const FIRST_NAME           = 'rcumc_sr_first_name';
	const LAST_NAME            = 'rcumc_sr_last_name';
	const DONE_TEXT            = 'rcumc_sr_done';
	const TODO_TEXT            = 'rcumc_sr_todo';
	const HELP_TEXT            = 'rcumc_sr_help';

	const NONCE_FIELD          = 'rcumc_sr_metabox_nonce';
}