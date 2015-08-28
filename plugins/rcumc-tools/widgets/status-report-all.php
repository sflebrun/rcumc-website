<?php
/**
 * Widget to select All Status Reports.
 *
 * Used to reset status report page after other status report by X
 * have been used.
 */


add_action('widgets_init', 'rcumc_add_status_report_all_widget');

function rcumc_add_status_report_all_widget()
{
	register_widget('RCUMC_Status_Report_All');
}

class RCUMC_Status_Report_All extends WP_Widget
{
	public function __construct()
	{
		$widget_options = array(
			'classname'    => 'status-reports',
			'description'  => 'Lists All Status Reports',
			);

		parent::__construct(
			'status-report-all',
			'RCUMC Status Reports',
			$widget_options
			);

	}   // end of __construct()

	// What is displayed on the front end page to the user.
	public function widget( $args, $instance )
	{
		extract( $args );

		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if ( $title )
		{
			echo $before_title . $title . $after_title;
		}

?>
		   <form>
		   <label>Select All Status Reports:
		<input type="submit" value="Ok" />
		   </label>

		   </form>

<?php

		echo $after_widget;

	}   // end of widget()

	// Options that the developer sets from the dashboard.
	public function form ($instance )
	{
		// Setup default widget settings:
		$defaults = array(
			'title'        => __('All Status Reports', RCUMC_TOOLS_TD),
			);

		// set defaults when not already set.
		$instance = wp_parse_args( (array) $instance, $defaults );

?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
		<input  type="text"
				id="<?php  echo $this->get_field_id('title'); ?>"
				name="<?php echo  $this->get_field_name('title'); ?>"
				value="<?php echo $instance['title']; ?>" />


<?php

	}   // end of form()

	// Update from processing form() results.
	public function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;

	}   // end of update()

}   // end of class RCUMC_Status_Report_By_Name
