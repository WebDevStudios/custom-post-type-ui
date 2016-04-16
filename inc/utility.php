<?php
/**
 * Custom Post Type UI Utility Code.
 *
 * @package CPTUI
 * @subpackage Utility
 * @author WebDevStudios
 * @since 1.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Edit links that appear on installed plugins list page, for our plugin.
 *
 * @since 1.0.0
 *
 * @internal
 *
 * @param array $links Array of links to display below our plugin listing.
 * @return array Amended array of links.
 */
function cptui_edit_plugin_list_links( $links ) {
	// We shouldn't encourage editing our plugin directly.
	unset( $links['edit'] );

	// Add our custom links to the returned array value.
	return array_merge( array(
		'<a href="' . admin_url( 'admin.php?page=cptui_main_menu' ) . '">' . __( 'About', 'custom-post-type-ui' ) . '</a>',
		'<a href="' . admin_url( 'admin.php?page=cptui_support' ) . '">' . __( 'Help', 'custom-post-type-ui' ) . '</a>',
	), $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( dirname( dirname( __FILE__ ) ) ) . '/custom-post-type-ui.php', 'cptui_edit_plugin_list_links' );

/**
 * Returns SVG icon for custom menu icon
 *
 * @since 1.2.0
 *
 * @return string
 */
function cptui_menu_icon() {
	return 'dashicons-forms';
}

/**
 * Return boolean status depending on passed in value.
 *
 * @since 0.5.0
 *
 * @param mixed $bool_text text to compare to typical boolean values.
 * @return bool Which bool value the passed in value was.
 */
function get_disp_boolean( $bool_text ) {
	$bool_text = (string) $bool_text;
	if ( empty( $bool_text ) || '0' === $bool_text || 'false' === $bool_text ) {
		return false;
	}

	return true;
}

/**
 * Return string versions of boolean values.
 *
 * @since 0.1.0
 *
 * @param string $bool_text String boolean value.
 * @return string standardized boolean text.
 */
function disp_boolean( $bool_text ) {
	$bool_text = (string) $bool_text;
	if ( empty( $bool_text ) || '0' === $bool_text || 'false' === $bool_text ) {
		return 'false';
	}

	return 'true';
}

/**
 * Display footer links and plugin credits.
 *
 * @since 0.3.0
 *
 * @internal
 *
 * @param string $original Original footer content.
 * @return string $value HTML for footer.
 */
function cptui_footer( $original = '' ) {

	$screen = get_current_screen();

	if ( ! is_object( $screen ) || 'cptui_main_menu' !== $screen->parent_base ) {
		return $original;
	}

	return sprintf(
		__( '%s version %s by %s', 'custom-post-type-ui' ),
		__( 'Custom Post Type UI', 'custom-post-type-ui' ),
		CPTUI_VERSION,
		'<a href="https://webdevstudios.com" target="_blank">WebDevStudios</a>'
	) . ' - ' .
	sprintf(
		'<a href="http://wordpress.org/support/plugin/custom-post-type-ui" target="_blank">%s</a>',
		__( 'Support forums', 'custom-post-type-ui' )
	) . ' - ' .
	__( 'Follow on Twitter:', 'custom-post-type-ui' ) .
	sprintf(
		' %s',
		'<a href="https://twitter.com/webdevstudios" target="_blank">WebDevStudios</a>'
	);
}
add_filter( 'admin_footer_text', 'cptui_footer' );

/**
 * Conditionally flushes rewrite rules if we have reason to.
 *
 * @since 1.3.0
 */
function cptui_flush_rewrite_rules() {

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	/*
	 * Wise men say that you should not do flush_rewrite_rules on init or admin_int. Due to the nature of our plugin
	 * and how new post types or taxonomies can suddenly be introduced, we need to...potentially. For this,
	 * we rely on a short lived transient. Only 5 minutes life span. If it exists, we do a soft flush before
	 * deleting the transient to prevent subsequent flushes. The only times the transient gets created, is if
	 * post types or taxonomies are created, updated, deleted, or imported. Any other time and this condition
	 * should not be met.
	 */
	if ( 'true' === ( $flush_it = get_transient( 'cptui_flush_rewrite_rules' ) ) ) {
		flush_rewrite_rules( false );
		// So we only run this once.
		delete_transient( 'cptui_flush_rewrite_rules' );
	}
}
add_action( 'admin_init', 'cptui_flush_rewrite_rules' );

/**
 * Return the current action being done within CPTUI context.
 *
 * @since 1.3.0
 *
 * @return string Current action being done by CPTUI
 */
function cptui_get_current_action() {
	$current_action = '';
	if ( ! empty( $_GET ) && isset( $_GET['action'] ) ) {
		$current_action .= esc_textarea( $_GET['action'] );
	}

	return $current_action;
}

/**
 * Return an array of all post type slugs from Custom Post Type UI.
 *
 * @since 1.3.0
 *
 * @return array CPTUI post type slugs.
 */
function cptui_get_post_type_slugs() {
	$post_types = get_option( 'cptui_post_types' );
	if ( ! empty ( $post_types ) ) {
		return array_keys( $post_types );
	}
	return array();
}

/**
 * Return an array of all taxonomy slugs from Custom Post Type UI.
 *
 * @since 1.3.0
 *
 * @return array CPTUI taxonomy slugs.
 */
function cptui_get_taxonomy_slugs() {
	$taxonomies = get_option( 'cptui_taxonomies' );
	if ( ! empty ( $taxonomies ) ) {
		return array_keys( $taxonomies );
	}
	return array();
}

/**
 * Return the appropriate admin URL depending on our context.
 *
 * @since 1.3.0
 *
 * @param $path
 * @return string|void
 */
function cptui_admin_url( $path ) {
	if ( is_multisite() && is_network_admin() ) {
		return network_admin_url( $path );
	}

	return admin_url( $path );
}

/**
 * Construct action tag for `<form>` tag.
 *
 * @since 1.3.0
 *
 * @param object|string $ui CPTUI Admin UI instance.
 * @return string
 */
function cptui_get_post_form_action( $ui = '' ) {
	/**
	 * Filters the string to be used in an `action=""` attribute.
	 *
	 * @since 1.3.0
	 */
	return apply_filters( 'cptui_post_form_action', '', $ui );
}

/**
 * Display action tag for `<form>` tag.
 *
 * @since 1.3.0
 *
 * @param object $ui CPTUI Admin UI instance
 */
function cptui_post_form_action( $ui ) {
	echo cptui_get_post_form_action( $ui );
}

/**
 * Fetch our CPTUI post types option.
 *
 * @since 1.3.0
 *
 * @return mixed|void
 */
function cptui_get_post_type_data() {
	return apply_filters( 'cptui_get_post_type_data', get_option( 'cptui_post_types', array() ), get_current_blog_id() );
}

/**
 * Fetch our CPTUI taxonomies option.
 *
 * @since 1.3.0
 *
 * @return mixed|void
 */
function cptui_get_taxonomy_data() {
	return apply_filters( 'cptui_get_taxonomy_data', get_option( 'cptui_taxonomies', array() ), get_current_blog_id() );
}

/**
 * Checks if a post type is already registered.
 *
 * @since 1.3.0
 *
 * @param string       $slug Post type slug to check
 * @param array|string $data Post type data being utilized.
 * @return mixed|void
 */
function cptui_get_post_type_exists( $slug = '', $data = array() ) {

	/**
	 * Filters the boolean value for if a post type exists for 3rd parties.
	 *
	 * @since 1.3.0
	 *
	 * @param string       $slug Post type slug to check.
	 * @param array|string $data Post type data being utilized.
	 */
	return apply_filters( 'cptui_get_post_type_exists', post_type_exists( $slug ), $data );
}

/**
 * Displays WebDevStudios products in a sidebar on the add/edit screens for post types and taxonomies.
 *
 * We hope you don't mind.
 *
 * @since 1.3.0
 *
 * @internal
 */
function cptui_products_sidebar() {
	if ( false === ( $ads = get_transient( 'wds_promos' ) ) ) {
		$ads = wp_remote_get( 'https://webdevstudios.com/assets/wds.json' );

		if ( 200 === wp_remote_retrieve_response_code( $ads ) ) {
			$ads = json_decode( wp_remote_retrieve_body( $ads ) );
			set_transient( 'wds_promos', $ads, DAY_IN_SECONDS );
		}
	}

	if ( ! empty( $ads ) ) {
		echo '<div class="wdspromos">';
		foreach ( $ads as $ad ) {
			$the_ad = $ad->text;
			$image = wp_remote_get( $ad->image );
			if ( 200 === wp_remote_retrieve_response_code( $image ) ) {
				$the_ad = sprintf(
					'<img src="%s" alt="%s">',
					$ad->image,
					$ad->text
				);
			}

			printf(
				'<a href="%s">%s</a>',
				$ad->url,
				$the_ad
			);
		}
		echo '</div>';

	}

}
add_action( 'cptui_below_post_type_tab_menu', 'cptui_products_sidebar' );
add_action( 'cptui_below_taxonomy_tab_menu', 'cptui_products_sidebar' );
