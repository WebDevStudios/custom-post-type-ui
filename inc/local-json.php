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
	$theme_dir = local_json_get_dirname();
	$blog_id   = '';

	if ( is_multisite() ) {
		$blog_id = '_' . get_current_blog_id();
	}

	if ( array_key_exists( 'cpt_custom_post_type', $data ) ) {
		$cptui_post_types = get_option( 'cptui_post_types', [] );
		$content          = json_encode( $cptui_post_types );
		file_put_contents( $theme_dir . "/cptui_post_type_data{$blog_id}.json", $content );
	}
}
add_action( 'cptui_after_update_post_type', __NAMESPACE__ . '\save_local_post_type_data' );

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

	$theme_dir = local_json_get_dirname();
	$blog_id   = '';

	if ( is_multisite() ) {
		$blog_id = '_' . get_current_blog_id();
	}

	if ( array_key_exists( 'cpt_custom_tax', $data ) ) {
		$cptui_taxonomies = get_option( 'cptui_taxonomies', [] );
		$content          = json_encode( $cptui_taxonomies );
		file_put_contents( $theme_dir . "/cptui_taxonomy_data{$blog_id}.json", $content );
	}
}
add_action( 'cptui_after_update_taxonomy', __NAMESPACE__ . '\save_local_taxonomy_data' );

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
