<?php

/**
* Custom Post Type UI Admin UI
*/
class cptui_admin_ui {

	function __construct() {

	}

	/**
	 * opening <tr> and <td> tags. All of <th> tag with values for label
	 *
	 * @since  0.9
	 *
	 * @param  string  $label_for  input name to associate with the wrapped input.
	 * @param  string  $label_text label text to use with the input
	 * @param  string  $title_text help text for the little question mark.
	 *
	 * @return string              completed opening wrapper
	 */
	public function tr_wrap_start( $label_for, $label_text, $title_text ) {
		return '<tr valign="top"><th scope="row">' . $label_text . '<a href="#" title="' . $title_text . '" class="help">?</a></th><td>';
	}

	/**
	 * closing <tr> and </td> tags
	 *
	 * @since  0.9
	 *
	 * @return string  completed closing wrapper
	 */
	public function tr_wrap_end() {
		return '</td></tr>';
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

		$value = $this->tr_wrap_start('','');
		$value .= '<select name="' . $args['name'] . '" tabindex="' . $args['tabindex'] . '">';

		foreach( $args['values'] as $val ) {
			$value .= '<option value="' . $val['value_int'] . '" ' . selected( $args['selected'], $val['value_int'] ) . '>' . $val['value_string'] . '</option>';
		}
		$value .= '</select>' . $args['help_text'];

		$value .= $this->tr_wrap_end();

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

		$value = $this->tr_wrap_start('','');

		$value .= '<input type="text" name="" tabindex="" value="" /><br/>';

		$value .= $this->tr_wrap_end();

		return $value;

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

		$value = $this->tr_wrap_start('','');

		$value .= '<textarea name="" tabindex="" rows="" cols=""></textarea>';

		$value .= $this->tr_wrap_end();

		return $value;
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

		$value = '<input type="checkbox" name="" tabindex="" value="" /> <a href="#" title="" class="help">?</a> <br/>';
	}
}