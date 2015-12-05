<?php

class CPTUI_Debug_Info {

	public function tab_site_info() {
		?>
		<p><?php _e( 'If you have sought support for Custom Post Type UI on the forums, you may be requested to send the information below to the plugin developer. Simply insert the email they provided in the input field at the bottom and click the "Send debug info" button. Only the data below will be sent to them.', 'custom-post-type-ui' ); ?></p>
		<label for="cptui_audit_textarea">
		<textarea readonly="readonly" id="cptui-audit-textarea" name="cptui_audit_textarea" rows="20" cols="100">
			<?php echo $this->system_status(); ?>
		</textarea></label>
		<?php
	}

	private function system_status() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return '';
		}

		global $wpdb;

		if ( get_bloginfo( 'version' ) < '3.4' ) {
			$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
			$theme = $theme_data['Name'] . ' ' . $theme_data['Version'];
		} else {
			$theme_data = wp_get_theme();
			$theme = $theme_data->Name . ' ' . $theme_data->Version;
		}

		ob_start();
		?>

		### Begin Custom Post Type UI Debug Info ###

		Multisite:                <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ?>

		SITE_URL:                 <?php echo site_url() . "\n"; ?>
		HOME_URL:                 <?php echo home_url() . "\n"; ?>

		WordPress Version:        <?php echo get_bloginfo( 'version' ) . "\n"; ?>
		Permalink Structure:      <?php echo get_option( 'permalink_structure' ) . "\n"; ?>
		Active Theme:             <?php echo $theme . "\n"; ?>

		Registered Post Types:    <?php echo implode( ', ', get_post_types( '', 'names' ) ) . "\n"; ?>

		PHP Version:              <?php echo PHP_VERSION . "\n"; ?>
		MySQL Version:            <?php echo $wpdb->db_version() . "\n"; ?>
		Web Server Info:          <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>

		Show On Front:            <?php echo get_option( 'show_on_front' ) . "\n" ?>
		Page On Front:            <?php $id = get_option( 'page_on_front' );
		echo get_the_title( $id ) . ' (#' . $id . ')' . "\n" ?>
		Page For Posts:           <?php $id = get_option( 'page_for_posts' );
		echo get_the_title( $id ) . ' (#' . $id . ')' . "\n" ?>

		WordPress Memory Limit:   <?php echo ( $this->num_convt( WP_MEMORY_LIMIT ) / ( 1024 ) ) . "MB"; ?><?php echo "\n"; ?>

		<?php
		$plugins  = get_plugins();
		$pg_count = count( $plugins );
		echo 'TOTAL PLUGINS: ' . $pg_count . "\n\n";
		// MU plugins
		$mu_plugins = get_mu_plugins();

		if ( $mu_plugins ) :
			$mu_count = count( $mu_plugins );

			echo 'MU PLUGINS: (' . $mu_count . ')' . "\n\n";

			foreach ( $mu_plugins as $mu_path => $mu_plugin ) {

				echo $mu_plugin['Name'] . ': ' . $mu_plugin['Version'] . "\n";
			}
		endif;
		// standard plugins - active
		echo "\n";

		$active   = get_option( 'active_plugins', array() );
		$ac_count = count( $active );
		$ic_count = $pg_count - $ac_count;

		echo 'ACTIVE PLUGINS: (' . $ac_count . ')' . "\n\n";

		foreach ( $plugins as $plugin_path => $plugin ) {
			// If the plugin isn't active, don't show it.
			if ( ! in_array( $plugin_path, $active ) ) {
				continue;
			}

			echo $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
		}
		// standard plugins - inactive
		echo "\n";
		echo 'INACTIVE PLUGINS: (' . $ic_count . ')' . "\n\n";

		foreach ( $plugins as $plugin_path => $plugin ) {
			// If the plugin isn't active, show it here.
			if ( in_array( $plugin_path, $active ) ) {
				continue;
			}

			echo $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
		}

		// if multisite, grab network as well
		if ( is_multisite() ) :

			$net_plugins = wp_get_active_network_plugins();
			$net_active  = get_site_option( 'active_sitewide_plugins', array() );

			echo "\n";
			echo 'NETWORK ACTIVE PLUGINS: (' . count( $net_plugins ) . ')' . "\n\n";

			foreach ( $net_plugins as $plugin_path ) {
				$plugin_base = plugin_basename( $plugin_path );

				// If the plugin isn't active, don't show it.
				if ( ! array_key_exists( $plugin_base, $net_active ) ) {
					continue;
				}

				$plugin = get_plugin_data( $plugin_path );

				echo $plugin['Name'] . ' :' . $plugin['Version'] . "\n";
			}

		endif;

		echo "\n";
		$cptui_post_types = get_option( 'cptui_post_types', array() );
		echo 'Post Types: ' . "\n";
		echo esc_html( json_encode( $cptui_post_types ) ) . "\n";

		echo "\n\n";

		$cptui_taxonomies = get_option( 'cptui_taxonomies', array() );
		echo 'Taxonomies: ' . "\n";
		echo esc_html( json_encode( $cptui_taxonomies ) ) . "\n";
		echo "\n";
		?>
		### End Debug Info ###
		<?php

		return ob_get_clean();
	}

	/**
	 * helper function for number conversions
	 * @access public
	 *
	 * @param mixed $v
	 * @return int
	 */
	public function num_convt( $v ) {
		$l   = substr( $v, - 1 );
		$ret = substr( $v, 0, - 1 );

		switch ( strtoupper( $l ) ) {
			case 'P': // fall-through
			case 'T': // fall-through
			case 'G': // fall-through
			case 'M': // fall-through
			case 'K': // fall-through
				$ret *= 1024;
				break;
			default:
				break;
		}

		return $ret;
	}

	public function send_email( $args ) {

		if ( ! isset( $args['email'] ) || ! is_email( $args['email'] ) ) {
			return false;
		}

		stripslashes_deep( $args );

		$args['email'] = sanitize_email( $args['email'] );

		$message = $this->system_status();

		$subject = sprintf(
			__( 'CPTUI debug information for %s'),
			home_url( '/' )
		);

		wp_mail( $args['email'], $subject, $message );
	}
}

