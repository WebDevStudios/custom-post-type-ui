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

	return $cpts;
}
add_filter( 'cptui_get_post_type_data', __NAMESPACE__ . '\local_get_post_type_data', 10, 2 );

function local_get_taxonomy_data( $taxes = [], $current_site_id = 0 ) {

	if ( ! local_json_is_enabled() ) {
		return $taxes;
	}

	return $taxes;
}
add_filter( 'cptui_get_taxonomy_data', __NAMESPACE__ . '\local_get_taxonomy_data', 10, 2 );

/**
 * Check if `cptui_data` is a directory and writable, thus enabled.
 *
 * @since 1.14.0
 * @return bool
 */
function local_json_is_enabled() {
	$dirname = local_json_get_dirname();

	return ( is_dir( $dirname ) && is_writable( $dirname ) );
}

/**
 * Return our intended local JSON folder server path.
 *
 * @since 1.14.0
 * @return string
 */
function local_json_get_dirname() {
	return get_stylesheet_directory() . '/cptui_data';
}

/**
 * Potentially add an admin notice about `cptui_data` not being writeable.
 * @since 1.14.0
 */
function local_json_is_writable_admin_notice() {
	$dirname = local_json_get_dirname();
	if ( ! is_dir( $dirname ) ) {
		return;
	}
	if ( ! is_writable( $dirname ) ) {
		add_action( 'admin_notices', "cptui_local_json_not_writable_admin_notice" );
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\local_json_is_writable_admin_notice' );

function get_current_site_type_tax_json_file_name( $content_type ) {
	$theme_dir = local_json_get_dirname();
	$blog_id   = '';

	if ( is_multisite() ) {
		$blog_id = '_' . get_current_blog_id();
	}
	return $theme_dir . "/cptui_{$content_type}_data{$blog_id}.json";
}

function load_local_cptui_data( $file_name = '' ) {
	if ( empty( $file_name ) ) {
		return false;
	}

	return file_get_contents( $file_name );
}
