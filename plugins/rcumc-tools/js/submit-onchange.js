/**
 * Javascript that can be used to force a submit if a single field
 * in a form changes.
 *
 * Intended for forms with one field as is often the case in a 
 * Wordpress Widget.
 */

function rcumc_tools_submit_onchange( field )
{
	// alert("OnChange of Widget");
	field.form.submit();

}   // end of submit_onchange()
