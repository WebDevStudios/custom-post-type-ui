<?php

/**
* Custom Post Type UI Admin UI
*/
class cptui_admin_ui {

	function __construct() {

	}

	public function get_tr_start() {
		return '<tr valign="top">';
	}

	public function get_tr_end() {
		return '</tr>';
	}

	public function get_th_start() {
		return '<th scope="row">';
	}

	public function get_th_end() {
		return '</th>';
	}

	public function get_td_start() {
		return '<td>';
	}

	public function get_td_end() {
		return '</td>';
	}

	public function get_label( $label_for, $label_text ) {
		$label = '<label for="' . $label_for . '"> ' . $label_text . '</label>';

		return $label;
	}

	public function get_required() {
		return '<span class="required">*</span>';
	}

	public function get_help( $help_text ) {
		return '<a href="#" title="' . $help_text . '" class="help">?</a>';
	}

	/**
	 * Display a select input with true/false values.
	 * @param  array  $args values to use in the input
	 * @return string       constructed input for the form.
	 */
	public function get_select_bool_input( $args = '' ) {
		$defaults = $this->get_default_input_parameters(
			array( 'selections' => array() )
		);

		$args = wp_parse_args( $args, $defaults );

		if ( $args['wrap'] ) {
			$value = $this->get_tr_start();
			$value .= $this->get_th_start();
			$value .= $this->get_label( $args['name'], $args['labeltext'] );
			if ( $args['required'] ) { $value .= $this->get_required(); }
			$value .= $this->get_help( $args['helptext'] );
			$value .= $this->get_th_end();
			$value .= $this->get_td_start();
		}
		$value .= '<select name="' . $args['namearray'] . '[' . $args['name'] . ']">';

		foreach( $args['selections']['options'] as $val ) {
			$set = false;
			//selected="selected" or empty string
			$selected = selected( $args['selections']['selected'], $val['attr'], false );
			$default = ( empty( $selected ) && !empty( $val['default'] ) ) ? true : false;
			if ( !empty( $selected ) ) {
				$result = $selected;
				$set = true;
			} elseif ( !$set && $default ) {
				$result = 'selected="selected"';
			} else {
				$result = '';
			}

			$value .= '<option value="' . $val['attr'] . '" ' . $result . '>' . $val['text'] . '</option>';
		}
		$value .= '</select>';

		if ( !empty( $args['aftertext'] ) )
			$value .= $args['aftertext'];

		if ( $args['wrap'] ) {
			$value .= $this->get_td_end();
			$value .= $this->get_tr_end();
		}

		return $value;
	}

	/**
	 * Display a text input for the user. Will populate if data is available from options
	 * @param  array  $args values to use in the input
	 * @return string       constructed input for the form.
	 */
	public function get_text_input( $args = '' ) { //TODO: Finish output of other attributes
		$defaults = $this->get_default_input_parameters(
			array(
				'maxlength'     => '',
				'onblur'        => '',
			)
		);
		$args = wp_parse_args( $args, $defaults );

		$value = '';
		if ( $args['wrap'] ) {
			$value .= $this->get_tr_start();
			$value .= $this->get_th_start();
			$value .= $this->get_label( $args['name'], $args['labeltext'] );
			if ( $args['required'] ) { $value .= $this->get_required(); }
			$value .= $this->get_help( $args['helptext'] );
			$value .= $this->get_th_end();
			$value .= $this->get_td_start();
		}

		$value .= '<input type="text" id="' . $args['name'] . '" name="' . $args['namearray'] . '[' . $args['name'] . ']" value="' . $args['textvalue'] . '" /><br/>';

		if ( !empty( $args['aftertext'] ) )
			$value .= $args['aftertext'];

		if ( $args['wrap'] ) {
			$value .= $this->get_td_end();
			$value .= $this->get_tr_end();
		}

		return $value;
	}

	/**
	 * Display a textarea input for the user. Will populate if data is available from options
	 * @param  array  $args values to use in the input
	 * @return string       constructed input for the form.
	 */
	public function get_textarea_input( $args = '' ) {
		$defaults = $this->get_default_input_parameters(
			array(
				'rows' => '',
				'cols' => '',
			)
		);
		$args = wp_parse_args( $args, $defaults );

		$value = '';

		if ( $args['wrap'] ) {
			$value .= $this->get_tr_start();
			$value .= $this->get_th_start();
			$value .= $this->get_label( $args['name'], $args['labeltext'] );
			if ( $args['required'] ) { $value .= $this->get_required(); }
			$value .= $this->get_help( $args['helptext'] );
			$value .= $this->get_th_end();
			$value .= $this->get_td_start();
		}

		$value .= '<textarea id="' . $args['name'] . '" name="' . $args['namearray'] . '[' . $args['name'] . ']" rows="' . $args['rows'] . '" cols="' . $args['cols'] . '">' . $args['textvalue'] . '</textarea>';

		if ( !empty ( $args['aftertext'] ) )
			$value .= $args['aftertext'];

		if ( $args['wrap'] ) {
			$value .= $this->get_td_end();
			$value .= $this->get_tr_end();
		}

		return $value;
	}

	/**
	 * Display a checkbox input for the user. Will check the already selected options if data is available from options.
	 * @param  array  $args values to use in the input
	 * @return string       constructed input for the form
	 */
	public function get_check_input( $args = '' ) {
		$defaults = $this->get_default_input_parameters(
			array(
                'checkvalue'        => '',
                'checked'           => true,
                'checklisttext'     => '',
                'default'           => false
			)
		);

		$args = wp_parse_args( $args, $defaults );

		$value = '';
		if ( $args['wrap'] ) {
			$value .= $this->get_tr_start();
			$value .= $this->get_th_start();
			$value .= $args['checklisttext'];
			if ( $args['required'] ) { $value .= $this->get_required(); }
			$value .= $this->get_th_end();
			$value .= $this->get_td_start();
		}
		if ( false === $args['default'] ) {
			$value .= '<input type="checkbox" id="' . $args['name'] . '" name="' . $args['namearray'] . '[]" value="' . $args['checkvalue'] . '"' . checked( $args['checked'], true, false) . ' />';
		} else {
			$value .= '<input type="checkbox" id="' . $args['name'] . '" name="' . $args['namearray'] . '[]" value="' . $args['checkvalue'] . '" checked="checked" />';
		}
		$value .= $this->get_label( $args['name'], $args['labeltext'] );
		$value .= $this->get_help( $args['helptext'] );
		$value .= '<br/>';

		if ( $args['wrap'] ) {
			$value .= $this->get_td_end();
			$value .= $this->get_tr_end();
		}

		return $value;
	}

	/**
	 * Return an array of default input values that all input types have.
	 *
	 * @return array  array of defaults.
	 */
	public function get_default_input_parameters( $additions = array() ) {
		return array_merge(
			array(
				'namearray'     => '',
				'name'          => '',
				'textvalue'     => '',
				'labeltext'     => '',
				'aftertext'     => '',
				'helptext'      => '',
				'required'      => false,
				'wrap'          => true
			),
			(array)$additions
		);
	}
}
