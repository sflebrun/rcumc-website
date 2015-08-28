<?php
/**
 * Widget to select Status Reports by User name.
 */


add_action('widgets_init', 'rcumc_add_status_report_by_name_widget');

function rcumc_add_status_report_by_name_widget()
{
	register_widget('RCUMC_Status_Report_By_Name');
}

class RCUMC_Status_Report_By_Name extends WP_Widget
{
	public function __construct()
	{
		$widget_options = array(
			'classname'    => 'status-reports',
			'description'  => 'Lists Status Reports by Name',
			);

		parent::__construct(
			'status-report-by-name',
			'Status Reports by Name',
			$widget_options
			);

	}   // end of __construct()

	// What is displayed on the front end page to the user.
	public function widget( $args, $instance )
	{
		extract( $args );

		$options = get_option( RCUMC_Tools::OPTION_NAME );
		$role_name = ( $options && isset($options[RCUMC_Tools::OPTION_ROLE]) ) ?
			$options[RCUMC_Tools::OPTION_ROLE] :
			'something that should not match any role';

		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if ( $title )
		{
			echo $before_title . $title . $after_title;
		}

		wp_enqueue_script( RCUMC_Tools::SUBMIT_ONCHANGE );

		// Get a list of all the Users who are Staff and hence
		// should have filed status reports.
		$args = array(
			'role'           => $role_name,
			'fields'         => 'all_with_meta',
			);

		$query = new WP_User_Query( $args );

		$users = $query->get_results();

?>
		   <form>
		   <label>Select Name:
		   <select 
				name="person-id" 
				onchange="rcumc_tools_submit_onchange(this);">
				<option value="-1">Select Name</option>
<?php
				foreach ( $users as $user )
				{
					echo '<option value="' . $user->ID . '">';
					echo $user->first_name . ' ' . $user->last_name;
					echo '</option>' . PHP_EOL;
				}
?>
				</select>
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
			'title'        => __('Status Reports by Name', RCUMC_TOOLS_TD),
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
