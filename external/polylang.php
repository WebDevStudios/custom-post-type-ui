<?php

add_action( 'admin_init', function () {
	$cpts = cptui_get_post_type_data();

	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}

	if ( ! empty( $cpts ) ) {
		foreach ( $cpts as $cpt_slug => $cpt_args ) {
			pll_register_string( 'custom-post-type-ui', $cpt_args['label'] );
			pll_register_string( 'custom-post-type-ui', $cpt_args['singular_label'] );
			pll_register_string( 'custom-post-type-ui', $cpt_args['description'], '', true );
			pll_register_string( 'custom-post-type-ui', $cpt_args['rewrite_slug'] );

			if ( is_array( $cpt_args['labels'] ) ) {
				foreach ( $cpt_args['labels'] as $label ) {
					pll_register_string( 'custom-post-type-ui', $label );
				}
			}
		}
	}
} );

add_filter( 'cptui_pre_register_post_type', function ( $args, $post_type_slug, $post_type_obj ) {
	if ( ! function_exists( 'pll__' ) ) {
		return $args;
	}

	foreach ( $args['labels'] as $key => $label ) {
		if ( in_array( $key, [ 'name', 'singular_name' ] ) ) {
			continue;
		}
		$args['labels'][ $key ] = pll__( $label );
	}

	$args['labels']['name']          = pll__( $post_type_obj['label'] );
	$args['labels']['singular_name'] = pll__( $post_type_obj['singular_label'] );
	$args['description']             = pll__( $args['description'] );

	return $args;
}, 10, 3 );
