<?php
/**
 * Ideas: $this->get_td( $content ). Returns content wrapped in <td>. Similar with $this->get_tr(), $this->th()
 */

/**
* Custom Post Type UI Admin UI
*/
class cptui_admin_ui {

	/**
	 * Return an opening <tr> tag.
	 *
	 * @since 1.0
	 *
	 * @return string $value Opening <tr> tag with attributes.
	 */
	public function get_tr_start() {
		return '<tr valign="top">';
	}

	/**
	 * Return a closing </tr> tag.
	 *
	 * @since 1.0
	 *
	 * @return string $value Closing </tr> tag.
	 */
	public function get_tr_end() {
		return '</tr>';
	}

	/**
	 * Return an opening <th> tag.
	 *
	 * @since 1.0
	 *
	 * @return string $value Opening <th> tag with attributes.
	 */
	public function get_th_start() {
		return '<th scope="row">';
	}

	/**
	 * Return a closing </th> tag.
	 *
	 * @since 1.0
	 *
	 * @return string $value Closing </th> tag.
	 */
	public function get_th_end() {
		return '</th>';
	}

	/**
	 * Return an opening <td> tag.
	 *
	 * @since 1.0
	 *
	 * @return string $value Opening <td> tag.
	 */
	public function get_td_start() {
		return '<td>';
	}

	/**
	 * Return a closing </td> tag.
	 *
	 * @since 1.0
	 *
	 * @return string $value Closing </td> tag.
	 */
	public function get_td_end() {
		return '</td>';
	}

	/**
	 * Return string wrapped in a <p> tag.
	 *
	 * @since 1.0
	 *
	 * @param string $text Content to wrap in a <p> tag.
	 *
	 * @return string $value Content wrapped in a <p> tag.
	 */
	public function get_p( $text = '' ) {
		return '<p>' . strip_tags( $text ) . '</p>';
	}

	/**
	 * Return a form <label> with for attribute.
	 *
	 * @since 1.0
	 *
	 * @param string $label_for  Form input to associate <label> with.
	 * @param string $label_text Text to display in the <label> tag.
	 *
	 * @return string $value <label> tag with filled out parts.
	 */
	public function get_label( $label_for = '', $label_text = '' ) {
		return '<label for="' . esc_attr( $label_for ) . '">' . strip_tags( $label_text ) . '</label>';
	}

	/**
	 * Return a <span> to indicate required status, with class attribute.
	 *
	 * @since 1.0
	 *
	 * @return string span tag.
	 */
	public function get_required() {
		return '<span class="required">*</span>';
	}

	/**
	 * Return an <a> tag with title attribute holding help text.
	 *
	 * @since 1.0
	 *
	 * @param string $help_text Text to use in the title attribute.
	 *
	 * @return string <a> tag with filled out parts.
	 */
	public function get_help( $help_text = '' ) {
		return '<a href="#" title="' . esc_attr( $help_text ) . '" class="help wp-ui-highlight">?</a>';
	}

	/**
	 * Return a maxlength HTML attribute with a specified length.
	 *
	 * @param string $length How many characters the max length should be set to.
	 *
	 * @return string $value Maxlength HTML attribute.
	 */
	public function get_maxlength( $length = '' ) {
		return 'maxlength="' . esc_attr( $length ) . '"';
	}

	/**
	 * Return a onblur HTML attribute for a specified value.
	 *
	 * @param string $text Text to place in the onblur attribute.
	 *
	 * @return string $value Onblur HTML attribute.
	 */
	public function get_onblur( $text = '' ) {
		return 'onblur="' . esc_attr( $text ) . '"';
	}

	/**
	 * Return a populated <select> input.
	 *
	 * @since 1.0
	 *
	 * @param array $args Arguments to use with the <select> input.
	 *
	 * @return string $value Complete <select> input with options and selected attribute.
	 */
	public function get_select_input( $args = array() ) {
		$defaults = $this->get_default_input_parameters(
			array( 'selections' => array() )
		);

		$args = wp_parse_args( $args, $defaults );

		$value = '';
		if ( $args['wrap'] ) {
			$value  = $this->get_tr_start();
			$value .= $this->get_th_start();
			$value .= $this->get_label( $args['name'], $args['labeltext'] );
			if ( $args['required'] ) { $value .= $this->get_required(); }
			if ( !empty( $args['helptext'] ) ) { $value .= $this->get_help( $args['helptext'] ); }
			$value .= $this->get_th_end();
			$value .= $this->get_td_start();
		}

		$value .= '<select id="' . $args['name'] . '" name="' . $args['namearray'] . '[' . $args['name'] . ']">';
		if ( !empty( $args['selections']['options'] ) && is_array( $args['selections']['options'] ) ) {
			foreach( $args['selections']['options'] as $val ) {
				$result = '';
				$bool = disp_boolean( $val['attr'] );

				if ( is_numeric( $args['selections']['selected'] ) ) {
					$selected = disp_boolean( $args['selections']['selected'] );
				} elseif ( in_array( $args['selections']['selected'], array( 'true', 'false' ) ) ) {
					$selected = $args['selections']['selected'];
				}

				if ( ( !empty( $selected ) ) && $selected === $bool ) {
					$result = ' selected="selected"';
				} else {
					if ( array_key_exists( 'default', $val ) && !empty( $val['default'] ) ) {
						if ( empty( $selected ) ) {
							$result = ' selected="selected"';
						}
					}
				}

				if ( !is_numeric( $args['selections']['selected'] ) && ( !empty( $args['selections']['selected'] ) && $args['selections']['selected'] == $val['attr'] ) ) {
					$result = ' selected="selected"';
				}

				$value .= '<option value="' . $val['attr'] . '"' . $result . '>' . $val['text'] . '</option>';
			}
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
	 * Return a text input.
	 *
	 * @since 1.0
	 *
	 * @param array $args Arguments to use with the text input.
	 *
	 * @return string Complete text <input> with proper attributes.
	 */
	public function get_text_input( $args = array() ) {
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

		$value .= '<input type="text" id="' . $args['name'] . '" name="' . $args['namearray'] . '[' . $args['name'] . ']" value="' . $args['textvalue'] . '"';

		if ( $args['maxlength'] ) {
			$value .= ' ' . $this->get_maxlength( $args['maxlength'] );
		}

		if ( $args['onblur'] ) {
			$value .= ' ' . $this->get_onblur( $args['onblur'] );
		}

		$value .= ' /><br/>';

		if ( !empty( $args['aftertext'] ) )
			$value .= $args['aftertext'];

		if ( $args['wrap'] ) {
			$value .= $this->get_td_end();
			$value .= $this->get_tr_end();
		}

		return $value;
	}

	/**
	 * Return a <textarea> input.
	 *
	 * @since 1.0
	 *
	 * @param array $args Arguments to use with the textarea input.
	 *
	 * @return string $value Complete <textarea> input with proper attributes.
	 */
	public function get_textarea_input( $args = array() ) {
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
	 * Return a checkbox <input>.
	 *
	 * @since 1.0
	 *
	 * @param array $args Arguments to use with the checkbox input.
	 *
	 * @return string $value Complete checkbox <input> with proper attributes.
	 */
	public function get_check_input( $args = array() ) {
		$defaults = $this->get_default_input_parameters(
			array(
				'checkvalue'        => '',
				'checked'           => 'true',
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

		if ( isset( $args['checked'] ) && 'false' == $args['checked'] ) {
			$value .= '<input type="checkbox" id="' . $args['name'] . '" name="' . $args['namearray'] . '[]" value="' . $args['checkvalue'] . '" />';
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
	 * Return some array_merged default arguments for all input types.
	 *
	 * @since 1.0
	 *
	 * @param array $additions Arguments array to merge with our defaults.
	 *
	 * @return array $value Merged arrays for our default parameters.
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
