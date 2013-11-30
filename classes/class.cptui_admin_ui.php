<?php

/**
* Custom Post Type UI Admin UI
*/
class cptui_admin_ui {

	function __construct() {

	}

	public function tr_start() {
		return '<tr valign="top">';
	}

	public function tr_end() {
		return '</tr>';
	}

	public function th_start() {
		return '<th scope="row">';
	}

	public function th_end() {
		return '</th>';
	}

	public function td_start() {
		return '<td>';
	}

	public function td_end() {
		return '</td>';
	}

	public function label( $label_for, $label_text ) {
		$label = '<label for="' . $label_for . '"> ' . $label_text . '</label>';

		return $label;
	}

	public function required() {
		return '<span class="required">*</span>';
	}

	public function help( $help_text ) {
		return '<a href="#" title="' . $help_text . '" class="help">?</a>';
	}

	/**
	 * Display a select input with true/false values.
	 * @param  array  $args values to use in the input
	 * @return string       constructed input for the form.
	 */
	public function select_bool_input( $args = '' ) {
		$defaults = array(

		);
		$args = wp_parse_args( $args, $defaults );

		$value = $this->tr_wrap_start('','');
		$value .= '<select name="' . $args['name'] . '">';

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
	public function text_input( $args = '' ) { //TODO: Finish output of other attributes
		$defaults = array(
			'namearray'     => '',
			'name'          => '',
			'textvalue'     => '',
			'maxlength'     => '',
			'onblur'        => '',
			'labeltext'     => '',
			'aftertext'     => '',
			'helptext'      => '',
			'required'      => false
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args );

		$value = $this->tr_start();
		$value .= $this->th_start();
		$value .= $this->label( $name, $labeltext );
		$value .= $this->required( $required );
		$value .= $this->help( $helptext );
		$value .= $this->th_end();
		$value .= $this->td_start();

		$value .= '<input type="text" name="' . $namearray . '[' . $name . ']" value="' . $textvalue . '" /><br/>';

		if ( !empty( $aftertext) )
			$value .= $aftertext;

		$value .= $this->td_end();
		$value .= $this->tr_end();

		return $value;

	}

	/**
	 * Display a textarea input for the user. Will populate if data is available from options
	 * @param  array  $args values to use in the input
	 * @return string       constructed input for the form.
	 */
	public function textarea_input( $args = '' ) {
		$defaults = array(
			'namearray'     => '',
			'name'          => '',
			'textvalue'     => '',
			'maxlength'     => '',
			'onblur'        => '',
			'labeltext'     => '',
			'aftertext'     => '',
			'helptext'      => '',
			'rows'          => '',
			'cols'          => '',
		);
		$args = wp_parse_args( $args, $defaults );

		$value = $this->tr_wrap_start('','');

		$value .= '<textarea name="" rows="" cols=""></textarea>';

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

		$value = '<input type="checkbox" name="" value="" /> <a href="#" title="" class="help">?</a> <br/>';
	}
}
