<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Content for the Debug Info tab.
 * @since 1.2.0
 * @internal
 */
function cptui_render_debuginfo_section() {

	$debuginfo = new CPTUI_Debug_Info();

	echo '<form id="cptui_debug_info" method="post">';
	$debuginfo->tab_site_info();

	wp_nonce_field( 'cptui_debuginfo_nonce_action', 'cptui_debuginfo_nonce_field' );

	if ( ! empty( $_POST ) && isset( $_POST['cptui_debug_info_email'] ) && isset( $_POST['cptui_debuginfo_nonce_field'] ) ) {
		if ( wp_verify_nonce( 'cptui_debuginfo_nonce_field', 'cptui_debuginfo_nonce_action' ) ) {
			$email_args          = [];
			$email_args['email'] = sanitize_text_field( wp_unslash( $_POST['cptui_debug_info_email'] ) );
			$debuginfo->send_email( $email_args );
		}
	}

	echo '<p><label for="cptui_debug_info_email">' . esc_html__( 'Please provide an email address to send debug information to: ', 'custom-post-type-ui' ) . '</label><input type="email" id="cptui_debug_info_email" name="cptui_debug_info_email" value="" /></p>';

	/**
	 * Filters the text value to use on the button when sending debug information.
	 *
	 * @param string $value Text to use for the button.
	 *
	 * @since 1.2.0
	 */
	echo '<p><input type="submit" class="button-primary" name="cptui_send_debug_email" value="' . esc_attr( apply_filters( 'cptui_debug_email_submit_button', __( 'Send debug info', 'custom-post-type-ui' ) ) ) . '" /></p>';
	echo '</form>';

	/**
	 * Fires after the display of the site information.
	 * @since 1.3.0
	 */
	do_action( 'cptui_after_site_info' );
}
