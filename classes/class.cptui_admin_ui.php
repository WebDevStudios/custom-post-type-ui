<?php

/**
* Custom Post Type UI Admin UI
*/
class ctpui_admin_ui {

	function __construct() {

	}

	/**
	 * Display a select input with true/false values.
	 * @param  array  $args values to use in the input
	 * @return string       constructed input for the form.
	 */
	public function select_bool_input( $args ) {
		$defaults = array(

		);
		$args = wp_parse_args( $args, $defaults );

		$value = '<select name="' . $args['name'] . '" tabindex="' . $args['tabindex'] . '">';

		foreach( $args['values'] as $val ) {
			$value .= '<option value="' . $val['value_int'] . '" ' . selected( $args['selected'], $val['value_int'] ) . '>' . $val['value_string'] . '</option>';
		}
		$value .= '</select>' . $args['help_text'];

		return $value;
	}

	/**
	 * Display a text input for the user. Will populate if data is available from options
	 * @param  array  $args values to use in the input
	 * @return string       constructed input for the form.
	 */
	public function text_input( $args ) {
		$defaults = array(

		);
		$args = wp_parse_args( $args, $defaults );

	}

	/**
	 * Display a textarea input for the user. Will populate if data is available from options
	 * @param  array  $args values to use in the input
	 * @return string       constructed input for the form.
	 */
	public function textarea_input( $args ) {
		$defaults = array(

		);
		$args = wp_parse_args( $args, $defaults );

	}

	/**
	 * Display a checkbox input for the user. Will check the already selected options if data is available from options.
	 * @param  array  $args values to use in the input
	 * @return string       constructed input for the form
	 */
	public function check_input( $args ) {
		$defaults = array(

		);
		$args = wp_parse_args( $args, $defaults );

	}
}