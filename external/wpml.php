<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Builds a WPML package for a post type.
 *
 * @since 1.19.3
 *
 * @param string $slug Slug to register package for.
 * @return array
 */
function cptui_wpml_pkg_build_post_type_package( $slug ) {
	return [
		'kind'      => 'CPTUI Post Type',
		'kind_slug' => 'cptui-post-type',
		'name'      => $slug,
		'title'     => sprintf(
			// translators: placeholder holds content name.
			esc_html__( 'Post Type: %s', 'custom-post-type-ui' ),
			$slug,
		),
		'edit_link' => admin_url( 'admin.php?page=cptui_manage_post_types&action=edit' ),
	];
}

/**
 * Builds a WPML package fpr a taxonomy.
 *
 * @since 1.19.3
 *
 * @param string $slug Slug to register package for.
 * @return array
 */
function cptui_wpml_pkg_build_taxonomy_package( $slug ) {
	return [
		'kind'      => 'CPTUI Taxonomy',
		'kind_slug' => 'cptui-taxonomy',
		'name'      => $slug,
		'title'     => sprintf(
			// translators: placeholder holds content name.
			esc_html__( 'Taxonomy: %s', 'custom-post-type-ui' ),
			$slug,
		),
		'edit_link' => admin_url( 'admin.php?page=cptui_manage_taxonomies&action=edit' ),
	];
}

/**
 * Register string package kinds for CPTUI.
 *
 * @since 1.19.3
 *
 * @param $kinds
 * @return mixed
 */
function cptui_register_string_package_kinds( $kinds ) {
	$kinds['cptui-post-type'] = esc_html__( 'CPT UI Post Type', 'custom-post-type-ui' );
	$kinds['cptui-taxonomy']  = esc_html__( 'CPT UI Taxonomy', 'custom-post-type-ui' );

	return $kinds;
}
add_filter( 'wpml_active_string_package_kinds', 'cptui_register_string_package_kinds' );

/**
 * Register and save translateable content to WPML after settings save of post type.
 *
 * @since 1.19.3
 *
 * @param array $data Array of post type data.
 */
function cptui_register_post_type_string_packages( $data ) {
	$package = cptui_wpml_pkg_build_post_type_package( $data['name'] );
	do_action( 'wpml_start_string_package_registration', $package );
	if ( ! empty( $data['label'] ) ) {
		do_action( 'wpml_register_string', $data['label'], 'name', $package, esc_html__( 'Name (plural)', 'custom-post-type-ui' ), 'LINE' );
	}
	if ( ! empty( $data['singular_label'] ) ) {
		do_action( 'wpml_register_string', $data['singular_label'], 'singular_name', $package, esc_html__( 'Singular name', 'custom-post-type-ui' ), 'LINE' );
	}
	if ( ! empty( $data['labels'] ) && is_array( $data['labels'] ) ) {
		foreach ( $data['labels'] as $key => $val ) {
			if ( '' === $val ) {
				continue;
			}
			do_action( 'wpml_register_string', $val, $key, $package, $key, 'LINE' );
		}
	}
	do_action( 'wpml_delete_unused_package_strings', $package );
}
add_action( 'cptui_after_update_post_type', 'cptui_register_post_type_string_packages' );

/**
 * Register and save translateble content to WPML after settings save of taxonomy.
 *
 * @since 1.19.3
 *
 * @param array $data Array of taxonomy data.
 */
function cptui_register_taxonomy_string_packages( $data ) {
	$package = cptui_wpml_pkg_build_taxonomy_package( $data['name'] );
	do_action( 'wpml_start_string_package_registration', $package );
	if ( ! empty( $data['label'] ) ) {
		do_action( 'wpml_register_string', $data['label'], 'name', $package, esc_html__( 'Name (plural)', 'custom-post-type-ui' ), 'LINE' );
	}
	if ( ! empty( $data['singular_label'] ) ) {
		do_action( 'wpml_register_string', $data['singular_label'], 'singular_name', $package, esc_html__( 'Singular name', 'custom-post-type-ui' ), 'LINE' );
	}
	if ( ! empty( $data['labels'] ) && is_array( $data['labels'] ) ) {
		foreach ( $data['labels'] as $key => $val ) {
			if ( '' === $val ) {
				continue;
			}
			do_action( 'wpml_register_string', $val, $key, $package, $key, 'LINE' );
		}
	}
	do_action( 'wpml_delete_unused_package_strings', $package );
}
add_action( 'cptui_after_update_taxonomy', 'cptui_register_taxonomy_string_packages' );

/**
 * Remove post type package after deletion of post type.
 *
 * @since 1.19.3
 *
 * @param array $data Post type data.
 */
function cptui_delete_post_type_string_packages( $data ) {
	do_action( 'wpml_delete_package', $data['name'], 'CPTUI Post Type' );
}
add_action( 'cptui_after_delete_post_type', 'cptui_delete_post_type_string_packages' );

/**
 * Remove taxonomy package after deletion of taxonomy.
 *
 * @since 1.19.3
 *
 * @param array $data Taxonomy data.
 */
function cptui_delete_taxonomy_string_packages( $data ) {
	do_action( 'wpml_delete_package', $data['name'], 'CPTUI Taxonomy' );
}
add_action( 'cptui_after_delete_taxonomy', 'cptui_delete_taxonomy_string_packages' );

/**
 * Filter in translated strings before post type registration.
 *
 * @since 1.19.3
 *
 * @param array  $args      Post type registration args.
 * @param string $slug      Post type slug being registered.
 * @param array  $post_type CPTUI individual post type settings.
 *
 * @return array
 */
function cptui_wpml_apply_post_type_translations( $args, $slug, $post_type ) {
	$package = cptui_wpml_pkg_build_post_type_package( $slug );
	if ( ! empty( $args['labels'] ) && is_array( $args['labels'] ) ) {
		foreach ( $args['labels'] as $key => $val ) {
			$args['labels'][ $key ] = apply_filters( 'wpml_translate_string', $val, $key, $package );
		}
	}
	if ( ! empty( $args['label'] ) ) {
		$args['label'] = apply_filters( 'wpml_translate_string', $args['label'], 'name', $package );
	}

	if ( ! empty( $args['singular_label'] ) ) {
		$args['singular_label'] = apply_filters( 'wpml_translate_string', $args['singular_label'], 'singular_name', $package );
	}
	return $args;
}
add_filter( 'cptui_pre_register_post_type', 'cptui_wpml_apply_post_type_translations', 20, 3 );

/**
 * Filter in translated strings before taxonomy registration.
 *
 * @since 1.19.3
 *
 * @param array  $args     Taxonomy registration args.
 * @param string $slug     Taxonomy slug being registered.
 * @param array  $taxonomy CPTUI individual taxonomy setings.
 *
 * @return array
 */
function cptui_wpml_apply_taxonomy_translations( $args, $slug, $taxonomy ) {
	$package = cptui_wpml_pkg_build_taxonomy_package( $slug );
	if ( ! empty( $args['labels'] ) && is_array( $args['labels'] ) ) {
		foreach ( $args['labels'] as $key => $val ) {
			$args['labels'][ $key ] = apply_filters( 'wpml_translate_string', $val, $key, $package );
		}
	}
	if ( ! empty( $args['label'] ) ) {
		$args['label'] = apply_filters( 'wpml_translate_string', $args['label'], 'name', $package );
	}

	if ( ! empty( $args['singular_label'] ) ) {
		$args['singular_label'] = apply_filters( 'wpml_translate_string', $args['singular_label'], 'singular_name', $package );
	}

	return $args;
}
add_filter( 'cptui_pre_register_taxonomy', 'cptui_wpml_apply_taxonomy_translations', 20, 3 );
