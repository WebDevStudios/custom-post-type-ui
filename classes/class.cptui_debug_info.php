<?php
/**
 * Custom Post Type UI Debug Information.
 *
 * @package CPTUI
 * @subpackage Debugging
 * @author WebDevStudios
 * @since 1.2.0
 * @license GPL-2.0+
 */

/**
 * Custom Post Type UI Debug Info
 */
class CPTUI_Debug_Info {

	/**
	 * Tab content for the debug info tab.
	 *
	 * @since 1.2.0
	 */
	public function tab_site_info() {
		?>
		<p><?php esc_html_e( 'If you have sought support for Custom Post Type UI on the forums, you may be requested to send the information below to the plugin developer. Simply insert the email they provided in the input field at the bottom and click the "Send debug info" button. Only the data below will be sent to them.', 'custom-post-type-ui' ); ?></p>
		<label for="cptui_audit_textarea">
		<textarea readonly="readonly" aria-readonly="true" id="cptui-audit-textarea" name="cptui_audit_textarea" rows="20" cols="100" class="large-text code">
			<?php echo esc_html( $this->system_status() ); ?>
		</textarea></label>
		<?php
	}

	/**
	 * Generate the debug information content.
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	private function system_status() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return '';
		}

		global $wpdb;

		$theme_data = wp_get_theme();
		$theme      = $theme_data->Name . ' ' . $theme_data->Version; // phpcs:ignore.

		ob_start();
		?>

		### Begin Custom Post Type UI Debug Info ###

		Multisite:                <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n"; ?>

		SITE_URL:                 <?php echo esc_url( site_url() ) . "\n"; ?>
		HOME_URL:                 <?php echo esc_url( home_url() ) . "\n"; ?>

		WordPress Version:        <?php echo get_bloginfo( 'version' ) . "\n"; // phpcs:ignore. ?>
		Permalink Structure:      <?php echo get_option( 'permalink_structure' ) . "\n"; // phpcs:ignore. ?>
		Active Theme:             <?php echo $theme . "\n"; // phpcs:ignore. ?>

		Registered Post Types:    <?php echo implode( ', ', get_post_types( '', 'names' ) ) . "\n"; // phpcs:ignore. ?>

		PHP Version:              <?php echo PHP_VERSION . "\n"; ?>
		MySQL Version:            <?php echo $wpdb->db_version() . "\n"; // phpcs:ignore. ?>
		Web Server Info:          <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; // phpcs:ignore. ?>

		Show On Front:            <?php echo get_option( 'show_on_front' ) . "\n"; // phpcs:ignore. ?>
		Page On Front:            <?php $id = get_option( 'page_on_front' ); // phpcs:ignore.
		echo get_the_title( $id ) . ' (#' . $id . ')' . "\n"; // phpcs:ignore. ?>
		Page For Posts:           <?php $id = get_option( 'page_for_posts' ); // phpcs:ignore.
		echo get_the_title( $id ) . ' (#' . $id . ')' . "\n"; // phpcs:ignore. ?>

		WordPress Memory Limit:   <?php echo ( $this->num_convt( WP_MEMORY_LIMIT ) / ( 1024 ) ) . 'MB'; ?><?php echo "\n"; // phpcs:ignore. ?>

		<?php
		$plugins  = get_plugins();
		$pg_count = count( $plugins );
		echo 'TOTAL PLUGINS: ' . $pg_count . "\n\n"; // phpcs:ignore.
		// MU plugins.
		$mu_plugins = get_mu_plugins();

		if ( $mu_plugins ) :
			echo "\t\t" . 'MU PLUGINS: (' . count( $mu_plugins ) . ')' . "\n\n";

			foreach ( $mu_plugins as $mu_path => $mu_plugin ) {

				echo "\t\t" . esc_html( $mu_plugin['Name'] ) . ': ' . esc_html( $mu_plugin['Version'] ) . "\n";
			}
		endif;
		// Standard plugins - active.
		echo "\n";

		$active   = get_option( 'active_plugins', [] );
		$ac_count = count( $active );
		$ic_count = $pg_count - $ac_count;

		echo "\t\t" . 'ACTIVE PLUGINS: (' . $ac_count . ')' . "\n\n"; // phpcs:ignore.

		foreach ( $plugins as $plugin_path => $plugin ) {
			// If the plugin isn't active, don't show it.
			if ( ! in_array( $plugin_path, $active, true ) ) {
				continue;
			}

			echo "\t\t" . esc_html( $plugin['Name'] ) . ': ' . esc_html( $plugin['Version'] ) . "\n";
		}
		// Standard plugins - inactive.
		echo "\n";
		echo "\t\t" , 'INACTIVE PLUGINS: (' . $ic_count . ')' . "\n\n"; // phpcs:ignore.

		foreach ( $plugins as $plugin_path => $plugin ) {
			// If the plugin isn't active, show it here.
			if ( in_array( $plugin_path, $active, true ) ) {
				continue;
			}

			echo "\t\t" . esc_html( $plugin['Name'] ) . ': ' . esc_html( $plugin['Version'] ) . "\n";
		}

		// If multisite, grab network as well.
		if ( is_multisite() ) :

			$net_plugins = wp_get_active_network_plugins();
			$net_active  = get_site_option( 'active_sitewide_plugins', [] );

			echo "\n";
			echo 'NETWORK ACTIVE PLUGINS: (' . count( $net_plugins ) . ')' . "\n\n";

			foreach ( $net_plugins as $plugin_path ) {
				$plugin_base = plugin_basename( $plugin_path );

				// If the plugin isn't active, don't show it.
				if ( ! array_key_exists( $plugin_base, $net_active ) ) {
					continue;
				}

				$plugin = get_plugin_data( $plugin_path );

				echo esc_html( $plugin['Name'] ) . ' :' . esc_html( $plugin['Version'] ) . "\n";
			}

		endif;

		echo "\n";
		$cptui_post_types = cptui_get_post_type_data();
		echo "\t\t" . 'Post Types: ' . "\n";
		echo "\t\t" . wp_json_encode( $cptui_post_types ) . "\n";

		echo "\n\n";

		$cptui_taxonomies = cptui_get_taxonomy_data();
		echo "\t\t" . 'Taxonomies: ' . "\n";
		echo "\t\t" . wp_json_encode( $cptui_taxonomies ) . "\n";
		echo "\n";

		if ( has_action( 'cptui_custom_debug_info' ) ) {
			echo "\t\t" . 'EXTRA DEBUG INFO';
		}

		/**
		 * Fires at the end of the debug info output.
		 *
		 * @since 1.3.0
		 */
		do_action( 'cptui_custom_debug_info' );

		echo "\n";
		?>
		### End Debug Info ###
		<?php

		return ob_get_clean();
	}

	/**
	 * Helper function for number conversions.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param mixed $v Value.
	 * @return int
	 */
	public function num_convt( $v ) {
		$l   = substr( $v, - 1 );
		$ret = substr( $v, 0, - 1 );

		switch ( strtoupper( $l ) ) {
			case 'P': // Fall-through.
			case 'T': // Fall-through.
			case 'G': // Fall-through.
			case 'M': // Fall-through.
			case 'K': // Fall-through.
				$ret *= 1024;
				break;
			default:
				break;
		}

		return $ret;
	}

	/**
	 * Sends an email to the specified address, with the system status as the message.
	 *
	 * @since 1.2.0
	 *
	 * @param array $args Array of arguments for the method. Optional.
	 * @return bool
	 */
	public function send_email( $args = [] ) {

		if ( ! isset( $args['email'] ) || ! is_email( $args['email'] ) ) {
			return false;
		}

		stripslashes_deep( $args );

		$args['email'] = sanitize_email( $args['email'] );

		$message = $this->system_status();

		/**
		 * Filters the debug email subject.
		 *
		 * @since 1.3.0
		 *
		 * @param string $value Intended email subject.
		 */
		$subject = apply_filters(
			'cptui_debug_email_subject',
			sprintf(
				// translators: Placeholder will hold site home_url.
				esc_html__( 'Custom Post Type UI debug information for %s', 'custom-post-type-ui' ),
				esc_url( home_url( '/' ) )
			)
		);

		$result = wp_mail( $args['email'], $subject, $message );

		/**
		 * Fires after the debug email has been sent.
		 *
		 * @since 1.3.0
		 */
		do_action( 'cptui_after_debug_email_sent' );

		return $result;
	}
}
