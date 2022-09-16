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

function local_json_is_enabled() {
	$dirname = local_json_get_dirname();
	return  ( is_dir( $dirname ) && is_writable( $dirname ) );
}

function local_json_get_dirname() {
	return get_stylesheet_directory() . '/cptui_data';
}

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
