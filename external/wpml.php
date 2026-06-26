<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cptui_wpml_pkg_build_post_type_package( $slug ) {
	return [
		'kind'      => esc_html__( 'CPT UI Post Type', 'custom-post-type-ui' ),
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

function cptui_wpml_pkg_build_taxonomy_package( $slug ) {
	return [
		'kind'      => esc_html__( 'CPT UI Taxonomy', 'custom-post-type-ui' ),
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

function cptui_register_string_package_kinds( $kinds ) {
	$kinds['cptui-post-type'] = esc_html__( 'CPT UI Post Type', 'custom-post-type-ui' );
	$kinds['cptui-taxonomy']  = esc_html__( 'CPT UI Taxonomy', 'custom-post-type-ui' );

	return $kinds;
}
add_filter( 'wpml_active_string_package_kinds', 'cptui_register_string_package_kinds' );

function cptui_register_post_type_string_packages() {
	$cptui_cpts = get_option( 'cptui_post_types', [] );
	if ( ! empty( $cptui_cpts ) ) {
		foreach ( $cptui_cpts as $cpt ) {
			$package = cptui_wpml_pkg_build_post_type_package( $cpt['name'] );
			do_action( 'wpml_start_string_package_registration', $package );
			if ( ! empty( $cpt['label'] ) ) {
				do_action( 'wpml_register_string', $cpt['label'], 'name', $package, esc_html__( 'Name (plural)', 'custom-post-type-ui' ), 'LINE' );
			}
			if ( ! empty( $cpt['singular_label'] ) ) {
				do_action( 'wpml_register_string', $cpt['singular_label'], 'singular_name', $package, esc_html__( 'Singular name', 'custom-post-type-ui' ), 'LINE' );
			}
			if ( ! empty( $cpt['labels'] ) && is_array( $cpt['labels'] ) ) {
				foreach ( $cpt['labels'] as $key => $val ) {
					if ( '' === $val ) {
						continue;
					}
					do_action( 'wpml_register_string', $val, $key, $package, $key, 'LINE' );
				}
			}
			do_action( 'wpml_delete_unused_package_strings', $package );
		}
	}
}
add_action( 'cptui_after_update_post_type', 'cptui_register_post_type_string_packages' );

function cptui_register_taxonomy_string_packages() {
	$cptui_taxonomies = get_option( 'cptui_taxonomies', [] );
	if ( ! empty( $cptui_taxonomies ) ) {
		foreach ( $cptui_taxonomies as $taxonomy ) {
			$package = cptui_wpml_pkg_build_taxonomy_package( $taxonomy['name'] );
			do_action( 'wpml_start_string_package_registration', $package );
			if ( ! empty( $taxonomy['label'] ) ) {
				do_action( 'wpml_register_string', $taxonomy['label'], 'name', $package, esc_html__( 'Name (plural)', 'custom-post-type-ui' ), 'LINE' );
			}
			if ( ! empty( $taxonomy['singular_label'] ) ) {
				do_action( 'wpml_register_string', $taxonomy['singular_label'], 'singular_name', $package, esc_html__( 'Singular name', 'custom-post-type-ui' ), 'LINE' );
			}
			if ( ! empty( $taxonomy['labels'] ) && is_array( $taxonomy['labels'] ) ) {
				foreach ( $taxonomy['labels'] as $key => $val ) {
					if ( '' === $val ) {
						continue;
					}
					do_action( 'wpml_register_string', $val, $key, $package, $key, 'LINE' );
				}
			}
			do_action( 'wpml_delete_unused_package_strings', $package );
		}
	}
}
add_action( 'cptui_after_update_taxonomy', 'cptui_register_taxonomy_string_packages' );

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
add_filter( 'cptui_pre_register_taxonomy', 'cptui_wpml_apply_post_type_translations', 20, 3 );
