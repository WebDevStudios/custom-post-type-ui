<?php
/**
 * Custom Post Type UI Local JSON.
 * @package    CPTUI
 * @subpackage Local JSON
 * @author     WebDevStudios
 * @since      1.14.0
 * @license    GPL-2.0+
 */

namespace CPTUI;

// phpcs:disable WebDevStudios.All.RequireAuthor

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Save post type data to local JSON file if enabled and able.
 *
 * Saves to suffixed site IDs if in multisite.
 *
 * @since 1.14.0
 * @param $data
 */
function save_local_post_type_data( $data = [] ) {

	if ( ! local_json_is_enabled() ) {
		return;
	}

	$json_path = get_current_site_type_tax_json_file_name( 'post_type' );

	$cptui_post_types = get_option( 'cptui_post_types', [] );
	$content          = json_encode( $cptui_post_types );
	file_put_contents( $json_path, $content );
}
add_action( 'cptui_after_update_post_type', __NAMESPACE__ . '\save_local_post_type_data' );
add_action( 'cptui_after_delete_post_type', __NAMESPACE__ . '\save_local_post_type_data' );

/**
 * Save taxonomy data to local JSON file if enabled and able.
 *
 * Saves to suffixed site IDs if in multisite.
 *
 * @since 1.14.0
 * @param $data
 */
function save_local_taxonomy_data( $data = [] ) {

	if ( ! local_json_is_enabled() ) {
		return;
	}

	$json_path = get_current_site_type_tax_json_file_name( 'taxonomy' );

	$cptui_taxonomies = get_option( 'cptui_taxonomies', [] );
	$content          = json_encode( $cptui_taxonomies );
	file_put_contents( $json_path, $content );
}
add_action( 'cptui_after_update_taxonomy', __NAMESPACE__ . '\save_local_taxonomy_data' );
add_action( 'cptui_after_delete_taxonomy', __NAMESPACE__ . '\save_local_taxonomy_data' );

function load_local_post_type_data( $data = [], $existing_cpts = [] ) {

	if ( ! local_json_is_enabled() ) {
		return $data;
	}

	// We want to prefer database copy first, in case of editing content.
	if ( ! empty( $existing_cpts ) ) {
		return $existing_cpts;
	}

	$loaded = load_local_cptui_data( get_current_site_type_tax_json_file_name( 'post_type' ) );

	if ( false === $loaded ) {
		return $data;
	}

	$data_new = json_decode( $loaded, true );

	if ( $data_new ) {
		return $data_new;
	}

	return $data;
}
add_filter( 'cptui_post_types_override', __NAMESPACE__ . '\load_local_post_type_data', 10, 2 );

function load_local_taxonomies_data( $data = [], $existing_taxes = [] ) {

	if ( ! local_json_is_enabled() ) {
		return $data;
	}

	// We want to prefer database copy first, in case of editing content.
	if ( ! empty( $existing_taxes ) ) {
		return $existing_taxes;
	}

	$loaded = load_local_cptui_data( get_current_site_type_tax_json_file_name( 'taxonomy' ) );

	if ( false === $loaded ) {
		return $data;
	}

	$data_new = json_decode( $loaded, true );

	if ( $data_new ) {
		return $data_new;
	}

	return $data;
}
add_filter( 'cptui_taxonomies_override', __NAMESPACE__ . '\load_local_taxonomies_data', 10, 2 );

function local_get_post_type_data( $cpts = [], $current_site_id = 0 ) {

	if ( ! local_json_is_enabled() ) {
		return $cpts;
	}

	if ( function_exists( 'get_current_screen' ) ) {
		$current_screen = \get_current_screen();

		if ( ! is_object( $current_screen ) || 'cpt-ui_page_cptui_tools' === $current_screen->base ) {
			return $cpts;
		}
	}

	$loaded = load_local_cptui_data( get_current_site_type_tax_json_file_name( 'post_type' ) );

	if ( false === $loaded ) {
		return $cpts;
	}

	$cpts_new = json_decode( $loaded, true );

	if ( $cpts_new ) {
		return $cpts_new;
	}

	return $cpts;
}
add_filter( 'cptui_get_post_type_data', __NAMESPACE__ . '\local_get_post_type_data', 10, 2 );

function local_get_taxonomy_data( $taxes = [], $current_site_id = 0 ) {

	if ( ! local_json_is_enabled() ) {
		return $taxes;
	}

	if ( function_exists( 'get_current_screen' ) ) {
		$current_screen = \get_current_screen();

		if ( ! is_object( $current_screen ) || 'cpt-ui_page_cptui_tools' === $current_screen->base ) {
			return $taxes;
		}
	}

	$loaded = load_local_cptui_data( get_current_site_type_tax_json_file_name( 'taxonomy' ) );

	if ( false === $loaded ) {
		return $taxes;
	}

	$data_new = json_decode( $loaded, true );

	if ( $data_new ) {
		return $data_new;
	}

	return $taxes;
}
add_filter( 'cptui_get_taxonomy_data', __NAMESPACE__ . '\local_get_taxonomy_data', 10, 2 );

/**
 * Check if `cptui-json` is a directory and writable, thus enabled.
 *
 * @since 1.14.0
 * @return bool
 */
function local_json_is_enabled() {
	$dirpath = local_json_get_dirpath();

	$is_enabled = ( is_dir( $dirpath ) && is_writable( $dirpath ) );

	/**
	 * Filters the `$is_enabled value for local JSON status.
	 *
	 * @since 1.14.0
	 *
	 * @oaram bool $is_enabled Whether or not the folder exists and is writeable.
	 */
	return apply_filters( 'cptui_local_json_is_enabled', $is_enabled );
}

/**
 * Return our intended local JSON folder server path.
 *
 * @since 1.14.0
 * @return string
 */
function local_json_get_dirpath() {

	/**
	 * Filters the server directory path to the intended folder in active theme.
	 *
	 * @since 1.14.0
	 *
	 * @param string $value Path to the folder in the active theme.
	 */
	return apply_filters( 'cptui_local_json_dirpath', get_stylesheet_directory() . '/cptui-json' );
}

/**
 * Potentially add an admin notice about `cptui-json` not being writeable.
 * @since 1.14.0
 */
function local_json_is_writable_admin_notice() {
	$dirpath = local_json_get_dirpath();
	if ( ! is_dir( $dirpath ) ) {
		return;
	}
	if ( ! is_writable( $dirpath ) ) {
		add_action( 'admin_notices', "cptui_local_json_not_writable_admin_notice" );
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\local_json_is_writable_admin_notice' );

function get_current_site_type_tax_json_file_name( $content_type ) {
	$theme_dir = local_json_get_dirpath();
	$blog_id   = '';

	if ( is_multisite() ) {
		$blog_id = '_' . get_current_blog_id();
	}
	$full_path = $theme_dir . "/cptui_{$content_type}_data{$blog_id}.json";

	/**
	 * Filters the full path including file for chosen content type for current site.
	 *
	 * @since 1.14.0
	 *
	 * @param string $full_path Full server path including file name.
	 * @param string $content_type Whether or not we are fetching post type or taxonomy
	 * @param string $blog_id Current site ID, with underscore prefix.
	 */
	return apply_filters( 'cptui_current_site_type_tax_json_file_name', $full_path, $content_type, $blog_id );
}

function load_local_cptui_data( $file_name = '' ) {
	if ( empty( $file_name ) || ! file_exists( $file_name ) ) {
		return false;
	}

	$data = file_get_contents( $file_name );
	if ( false === $data ) {
		return false;
	}

	return $data;
}

function local_post_type_listings_note() {

	if ( ! local_json_is_enabled() ) {
		return;
	}

	$db_types = get_option( 'cptui_post_types', [] );
	$loaded = load_local_cptui_data( get_current_site_type_tax_json_file_name( 'post_type' ) );

	if ( ! empty( $db_types ) || false === $loaded ) {
		return;
	}

	printf(
		'<h3>%s</h3>',
		esc_html__( 'These post types were loaded via local JSON in your active theme.', 'custom-post-type-ui' )
	);
}
add_action( 'cptui_before_post_type_listing', __NAMESPACE__ . '\local_post_type_listings_note' );

function local_taxonomy_listings_note() {

	if ( ! local_json_is_enabled() ) {
		return;
	}

	$db_taxes = get_option( 'cptui_taxonomies', [] );
	$loaded   = load_local_cptui_data( get_current_site_type_tax_json_file_name( 'taxonomy' ) );

	if ( ! empty( $db_taxes ) || false === $loaded ) {
		return;
	}

	printf(
		'<h3>%s</h3>',
		esc_html__( 'These taxonomies were loaded via local JSON in your active theme.', 'custom-post-type-ui' )
	);
}
add_action( 'cptui_before_taxonomy_listing', __NAMESPACE__ . '\local_taxonomy_listings_note' );

function local_post_type_tools_export_message( $orig_text ) {

	if ( ! local_json_is_enabled() ) {
		return $orig_text;
	}

	$db_types = get_option( 'cptui_post_types', [] );
	$loaded    = load_local_cptui_data( get_current_site_type_tax_json_file_name( 'post_type' ) );

	if ( ! empty( $db_types ) || false === $loaded ) {
		return $orig_text;
	}

	return esc_html__( 'Post types are registered with local JSON.', 'custom-post-type-ui' );
}
add_filter( 'cptui_no_post_types_registered_message', __NAMESPACE__ . '\local_post_type_tools_export_message' );

function local_taxonomy_tools_export_message( $orig_text ) {

	if ( ! local_json_is_enabled() ) {
		return $orig_text;
	}

	$db_taxes = get_option( 'cptui_taxonomies', [] );
	$loaded   = load_local_cptui_data( get_current_site_type_tax_json_file_name( 'taxonomy' ) );

	if ( ! empty( $db_taxes ) || false === $loaded ) {
		return $orig_text;
	}

	return esc_html__( 'Taxonomies are registered with local JSON.', 'custom-post-type-ui' );
}
add_filter( 'cptui_no_taxonomies_registered_message', __NAMESPACE__ . '\local_taxonomy_tools_export_message' );
