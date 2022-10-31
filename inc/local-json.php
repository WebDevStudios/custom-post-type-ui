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

	$json_path            = get_specific_type_tax_file_name( 'post_type', $data['cpt_custom_post_type']['name'] );
	$cptui_post_types     = get_option( 'cptui_post_types', [] );
	$individual_post_type = $cptui_post_types[ $data['cpt_custom_post_type']['name'] ];
	$content              = json_encode( $individual_post_type );
	file_put_contents( $json_path, $content );
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

	$json_path           = get_specific_type_tax_file_name( 'taxonomy', $data['cpt_custom_tax']['name'] );
	$cptui_taxonomies    = get_option( 'cptui_taxonomies', [] );
	$individual_taxonomy = $cptui_taxonomies[ $data['cpt_custom_tax']['name'] ];
	$content             = json_encode( $individual_taxonomy );
	file_put_contents( $json_path, $content );
}
add_action( 'cptui_after_update_taxonomy', __NAMESPACE__ . '\save_local_taxonomy_data' );

function delete_local_post_type_data( $data = [] ) {

	if ( ! local_json_is_enabled() ) {
		return;
	}

	$json_path = get_specific_type_tax_file_name( 'post_type', $data['name'] );
	unlink( $json_path );
}
#add_action( 'cptui_after_delete_post_type', __NAMESPACE__ . '\delete_local_post_type_data' );

function delete_local_taxonomy_data( $data = [] ) {

	if ( ! local_json_is_enabled() ) {
		return;
	}

	$json_path = get_specific_type_tax_file_name( 'post_type', $data['name'] );
	unlink( $json_path );
}
#add_action( 'cptui_after_delete_taxonomy', __NAMESPACE__ . '\delete_local_taxonomy_data' );

function load_local_post_type_data( $data = [], $existing_cpts = [] ) {

	if ( ! local_json_is_enabled() ) {
		return $data;
	}

	// We want to prefer database copy first, in case of editing content.
	//if ( ! empty( $existing_cpts ) ) {
	//	return $existing_cpts;
	//}

	$data_new = local_combine_post_types();

	if ( empty( $data_new ) ) {
		return $data;
	}

	return array_merge( $data_new, $existing_cpts );
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

	$data_new = local_combine_taxonomies();

	if ( empty( $data_new ) ) {
		return $data;
	}

	return $data_new;
}
add_filter( 'cptui_taxonomies_override', __NAMESPACE__ . '\load_local_taxonomies_data', 10, 2 );

function local_get_post_type_data( $cpts = [], $current_site_id = 0 ) {

	if ( ! local_json_is_enabled() ) {
		return $cpts;
	}

	if ( function_exists( 'get_current_screen' ) ) {
		$current_screen = \get_current_screen();

		if (
			! is_object( $current_screen ) ||
			(
				'cpt-ui_page_cptui_tools' === $current_screen->base &&
				empty( $_GET['action'] )
			)
		) {
			return $cpts;
		}
	}

	$data_new = local_combine_post_types();

	if ( empty( $data_new ) ) {
		return $cpts;
	}

	return $data_new;
}
add_filter( 'cptui_get_post_type_data', __NAMESPACE__ . '\local_get_post_type_data', 10, 2 );

function local_get_taxonomy_data( $taxes = [], $current_site_id = 0 ) {

	if ( ! local_json_is_enabled() ) {
		return $taxes;
	}

	if ( function_exists( 'get_current_screen' ) ) {
		$current_screen = \get_current_screen();

		if ( ! is_object( $current_screen ) ||
			'cpt-ui_page_cptui_tools' === $current_screen->base &&
			$_GET['action'] === 'taxonomies'
		) {
			return $taxes;
		}
	}

	$data_new = local_combine_taxonomies();

	if ( empty( $data_new ) ) {
		return $taxes;
	}

	return $data_new;
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

function get_specific_type_tax_file_name( $content_type = '', $content_slug = '' ) {
	$theme_dir = local_json_get_dirpath();
	$blog_id   = '';

	if ( is_multisite() ) {
		$blog_id = '_' . get_current_blog_id();
	}
	$full_path = $theme_dir . "/cptui_{$content_type}_{$content_slug}_data{$blog_id}.json";

	/**
	 * Filters the full path including file for chosen content type for current site.
	 *
	 * @param string $full_path    Full server path including file name.
	 * @param string $content_type Whether or not we are fetching post type or taxonomy
	 * @param string $content_slug The slug of the content type being managed.
	 * @param string $blog_id      Current site ID, with underscore prefix.
	 *
	 * @since 1.14.0
	 */
	return apply_filters( 'cptui_specific_type_tax_file_name', $full_path, $content_type, $content_slug, $blog_id );
}

function local_has_post_types() {
	if ( ! local_json_is_enabled() ) {
		return;
	}

	$maybe_post_types = local_combine_post_types();
	return ! empty( $maybe_post_types );
}

function local_has_taxonomies() {
	if ( ! local_json_is_enabled() ) {
		return;
	}

	$maybe_taxonomies = local_combine_taxonomies();
	return ! empty( $maybe_taxonomies );
}

function local_combine_post_types() {
	$post_types = [];
	foreach ( new \DirectoryIterator( local_json_get_dirpath() ) as $fileInfo ) {
		if ( $fileInfo->isDot() ) {
			continue;
		}
		if ( false === strpos( $fileInfo->getFilename(), 'post_type' ) ) {
			continue;
		}

		$file_site_id = local_get_site_id_from_json_file( $fileInfo->getFilename() );
		$site_id      = get_current_blog_id();
		if ( $file_site_id !== $site_id ) {
			continue;
		}

		$content = file_get_contents( $fileInfo->getPathname() );
		$content_decoded = json_decode( $content, true );
		$post_types[ $content_decoded['name'] ] = $content_decoded;
	}
	return $post_types;
}

function local_combine_taxonomies() {
	$taxonomies = [];
	foreach ( new \DirectoryIterator( local_json_get_dirpath() ) as $fileInfo ) {
		if ( $fileInfo->isDot() ) {
			continue;
		}
		if ( false === strpos( $fileInfo->getFilename(), 'taxonomy' ) ) {
			continue;
		}

		$file_site_id = local_get_site_id_from_json_file( $fileInfo->getFilename() );
		$site_id      = get_current_blog_id();
		if ( $file_site_id !== $site_id ) {
			continue;
		}

		$content                                = file_get_contents( $fileInfo->getPathname() );
		$content_decoded                        = json_decode( $content, true );
		$taxonomies[ $content_decoded['name'] ] = $content_decoded;
	}

	return $taxonomies;
}

function local_post_type_tools_export_message( $orig_text ) {

	if ( ! local_json_is_enabled() ) {
		return $orig_text;
	}

	$db_types = get_option( 'cptui_post_types', [] );
	$loaded   = local_has_post_types();

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
	$loaded   = local_has_taxonomies();

	if ( ! empty( $db_taxes ) || false === $loaded ) {
		return $orig_text;
	}

	return esc_html__( 'Taxonomies are registered with local JSON.', 'custom-post-type-ui' );
}
add_filter( 'cptui_no_taxonomies_registered_message', __NAMESPACE__ . '\local_taxonomy_tools_export_message' );

function local_get_site_id_from_json_file( $filename = '' ) {
	return (int) substr( basename( $filename, '.json' ), - 1 );
}
