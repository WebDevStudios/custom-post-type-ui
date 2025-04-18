<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display our copy-able code for registered post types.
 *
 * @param array $cptui_post_types Array of post types to render. Optional.
 * @param bool  $single           Whether or not we are rendering a single post type. Optional. Default false.
 *
 * @since 1.2.0 Added $single parameter.
 * @since 1.0.0
 * @since 1.2.0 Added $cptui_post_types parameter.
 */
function cptui_get_post_type_code( $cptui_post_types = [], $single = false ) {
	// Whitespace very much matters here, thus why it's all flush against the left side.
	if ( ! empty( $cptui_post_types ) ) {
		$callback = 'cptui_register_my_cpts';
		if ( $single ) {
			$key      = key( $cptui_post_types );
			$callback = 'cptui_register_my_cpts_unknown'; // new fallback
			if ( ! empty( $cptui_post_types[ $key ]['name'] ) ) {
				// If we have a name value.
				$suffix = esc_html( $cptui_post_types[ $key ]['name'] );

				// if somehow our escaping is returning a null value.
				if ( ! empty( $suffix ) ) {
					$callback = 'cptui_register_my_cpts_' . str_replace( '-', '_', $suffix );
				}
			}
		}
?>

function <?php echo esc_html( $callback ); ?>() {
<?php
// Space before this line reflects in textarea.
		foreach ( $cptui_post_types as $type ) {
			echo cptui_get_single_post_type_registery( $type );
		}
?>
}

add_action( 'init', '<?php echo esc_html( $callback ); ?>' );
<?php
	} else {
		esc_html_e( 'No post types to display at this time', 'custom-post-type-ui' );
	}
}

/**
 * Create output for single post type to be ready for copy/paste from Get Code.
 *
 * @param array $post_type Post type data to output. Optional.
 *
 * @since 1.0.0
 */
function cptui_get_single_post_type_registery( $post_type = [] ) {

	/* This filter is documented in custom-post-type-ui/custom-post-type-ui.php */
	$post_type['map_meta_cap'] = apply_filters( 'cptui_map_meta_cap', 'true', $post_type['name'], $post_type );

	/* This filter is documented in custom-post-type-ui/custom-post-type-ui.php */
	$user_supports_params = apply_filters( 'cptui_user_supports_params', [], $post_type['name'], $post_type );
	if ( is_array( $user_supports_params ) ) {
		$post_type['supports'] = array_merge( $post_type['supports'], $user_supports_params );
	}

	$yarpp = false; // Prevent notices.
	if ( ! empty( $post_type['custom_supports'] ) ) {
		$custom = explode( ',', $post_type['custom_supports'] );
		foreach ( $custom as $part ) {
			// We'll handle YARPP separately.
			if ( in_array( $part, [ 'YARPP', 'yarpp' ], true ) ) {
				$yarpp = true;
				continue;
			}
			$post_type['supports'][] = $part;
		}
	}


	$show_graphql = isset( $post_type['show_in_graphql'] ) ? (bool) $post_type['show_in_graphql'] : false;

	$rewrite_withfront = '';
	$rewrite           = get_disp_boolean( $post_type['rewrite'] );
	if ( false !== $rewrite ) {
		$rewrite = disp_boolean( $post_type['rewrite'] );

		$rewrite_slug = ' "slug" => "' . $post_type['name'] . '",';
		if ( ! empty( $post_type['rewrite_slug'] ) ) {
			$rewrite_slug = ' "slug" => "' . $post_type['rewrite_slug'] . '",';
		}

		$withfront = disp_boolean( $post_type['rewrite_withfront'] );
		if ( ! empty( $withfront ) ) {
			$rewrite_withfront = ' "with_front" => ' . $withfront . ' ';
		}

		if ( ! empty( $post_type['rewrite_slug'] ) || ! empty( $post_type['rewrite_withfront'] ) ) {
			$rewrite_start = '[';
			$rewrite_end   = ']';

			$rewrite = $rewrite_start . $rewrite_slug . $rewrite_withfront . $rewrite_end;
		}
	} else {
		$rewrite = disp_boolean( $post_type['rewrite'] );
	}
	$has_archive = get_disp_boolean( $post_type['has_archive'] );
	if ( false !== $has_archive ) {
		$has_archive = disp_boolean( $post_type['has_archive'] );
		if ( ! empty( $post_type['has_archive_string'] ) ) {
			$has_archive = '"' . $post_type['has_archive_string'] . '"';
		}
	} else {
		$has_archive = disp_boolean( $post_type['has_archive'] );
	}

	$supports = '';
	// Do a little bit of php work to get these into strings.
	if ( ! empty( $post_type['supports'] ) && is_array( $post_type['supports'] ) ) {
		$supports = '[ "' . implode( '", "', $post_type['supports'] ) . '" ]';
	}

	if ( in_array( 'none', $post_type['supports'], true ) ) {
		$supports = 'false';
	}

	$taxonomies = '';
	if ( ! empty( $post_type['taxonomies'] ) && is_array( $post_type['taxonomies'] ) ) {
		$taxonomies = '[ "' . implode( '", "', $post_type['taxonomies'] ) . '" ]';
	}

	if ( in_array( $post_type['query_var'], [ 'true', 'false', '0', '1' ], true ) ) {
		$post_type['query_var'] = disp_boolean( $post_type['query_var'] );
	}
	if ( ! empty( $post_type['query_var_slug'] ) ) {
		$post_type['query_var'] = '"' . $post_type['query_var_slug'] . '"';
	}

	if ( empty( $post_type['show_in_rest'] ) ) {
		$post_type['show_in_rest'] = 'false';
	}
	$rest_controller_class = ! empty( $post_type['rest_controller_class'] ) ? $post_type['rest_controller_class'] : 'WP_REST_Posts_Controller';
	$rest_namespace        = ! empty( $post_type['rest_namespace'] ) ? $post_type['rest_namespace'] : 'wp/v2';

	$show_in_menu = get_disp_boolean( $post_type['show_in_menu'] );
	if ( false !== $show_in_menu ) {
		$show_in_menu = disp_boolean( $post_type['show_in_menu'] );
		if ( ! empty( $post_type['show_in_menu_string'] ) ) {
			$show_in_menu = '"' . $post_type['show_in_menu_string'] . '"';
		}
	} else {
		$show_in_menu = disp_boolean( $post_type['show_in_menu'] );
	}

	$delete_with_user = 'false';
	if ( isset( $post_type['delete_with_user'] ) ) {
		$delete_with_user = disp_boolean( $post_type['delete_with_user'] );
	}

	$can_export = 'true';
	if ( isset( $post_type['can_export'] ) ) {
		$can_export = disp_boolean( $post_type['can_export'] );
	}

	$public            = isset( $post_type['public'] ) ? disp_boolean( $post_type['public'] ) : 'true';
	$show_in_nav_menus = ( ! empty( $post_type['show_in_nav_menus'] ) && false !== get_disp_boolean( $post_type['show_in_nav_menus'] ) ) ? 'true' : 'false';
	if ( empty( $post_type['show_in_nav_menus'] ) ) {
		$show_in_nav_menus = $public;
	}

	$capability_type = '"post"';
	if ( ! empty( $post_type['capability_type'] ) ) {
		$capability_type = '"' . $post_type['capability_type'] . '"';
		if ( false !== strpos( $post_type['capability_type'], ',' ) ) {
			$caps = array_map( 'trim', explode( ',', $post_type['capability_type'] ) );
			if ( count( $caps ) > 2 ) {
				$caps = array_slice( $caps, 0, 2 );
			}
			$capability_type = '[ "' . $caps[0] . '", "' . $caps[1] . '" ]';
		}
	}

	$post_type['description'] = addslashes( $post_type['description'] );

	$my_theme   = wp_get_theme();
	$textdomain = $my_theme->get( 'TextDomain' );
	if ( empty( $textdomain ) ) {
		$textdomain = 'custom-post-type-ui';
	}
	?>

	/**
	 * Post Type: <?php echo $post_type['label']; ?>.
	 */

	$labels = [
		"name" => esc_html__( "<?php echo $post_type['label']; ?>", "<?php echo $textdomain; ?>" ),
		"singular_name" => esc_html__( "<?php echo $post_type['singular_label']; ?>", "<?php echo $textdomain; ?>" ),
<?php
	foreach ( $post_type['labels'] as $key => $label ) {
		if ( ! empty( $label ) ) {
			if ( 'parent' === $key && ! array_key_exists( 'parent_item_colon', $post_type['labels'] ) ) {
				// Fix for incorrect label key. See #439.
				echo "\t\t" . '"' . 'parent_item_colon' . '" => esc_html__( "' . $label . '", "' . $textdomain . '" ),' . "\n";
			} else {
				echo "\t\t" . '"' . $key . '" => esc_html__( "' . $label . '", "' . $textdomain . '" ),' . "\n";
			}
		}
	}
?>
	];

	$args = [
		"label" => esc_html__( "<?php echo $post_type['label']; ?>", "<?php echo $textdomain; ?>" ),
		"labels" => $labels,
		"description" => "<?php echo $post_type['description']; ?>",
		"public" => <?php echo disp_boolean( $post_type['public'] ); ?>,
		"publicly_queryable" => <?php echo disp_boolean( $post_type['publicly_queryable'] ); ?>,
		"show_ui" => <?php echo disp_boolean( $post_type['show_ui'] ); ?>,
		"show_in_rest" => <?php echo disp_boolean( $post_type['show_in_rest'] ); ?>,
		"rest_base" => "<?php echo $post_type['rest_base']; ?>",
		"rest_controller_class" => "<?php echo $rest_controller_class; ?>",
		"rest_namespace" => "<?php echo $rest_namespace; ?>",
		"has_archive" => <?php echo $has_archive; ?>,
		"show_in_menu" => <?php echo $show_in_menu; ?>,
		"show_in_nav_menus" => <?php echo $show_in_nav_menus; ?>,
		"delete_with_user" => <?php echo $delete_with_user; ?>,
		"exclude_from_search" => <?php echo disp_boolean( $post_type['exclude_from_search'] ); ?>,
		"capability_type" => <?php echo $capability_type; ?>,
		"map_meta_cap" => <?php echo disp_boolean( $post_type['map_meta_cap'] ); ?>,
		"hierarchical" => <?php echo disp_boolean( $post_type['hierarchical'] ); ?>,
		"can_export" => <?php echo $can_export; ?>,
		"rewrite" => <?php echo $rewrite; ?>,
		"query_var" => <?php echo $post_type['query_var']; ?>,
<?php if ( ! empty( $post_type['menu_position'] ) ) { ?>
		"menu_position" => <?php echo $post_type['menu_position']; ?>,
<?php } ?>
<?php if ( ! empty( $post_type['menu_icon'] ) ) { ?>
		"menu_icon" => "<?php echo $post_type['menu_icon']; ?>",
<?php } ?>
<?php if ( ! empty( $post_type['register_meta_box_cb'] ) ) { ?>
		"register_meta_box_cb" => "<?php echo $post_type['register_meta_box_cb']; ?>",
<?php } ?>
<?php if ( ! empty( $supports ) ) { ?>
		"supports" => <?php echo $supports; ?>,
<?php } ?>
<?php if ( ! empty( $taxonomies ) ) { ?>
		"taxonomies" => <?php echo $taxonomies; ?>,
<?php } ?>
<?php if ( true === $yarpp ) { ?>
		"yarpp_support" => <?php echo disp_boolean( $yarpp ); ?>,
<?php } ?>
<?php if ( $show_graphql ) : ?>
		"show_in_graphql" => <?php echo disp_boolean( $post_type['show_in_graphql'] ); ?>,
		"graphql_single_name" => "<?php echo esc_html( $post_type['graphql_single_name'] ); ?>",
		"graphql_plural_name" => "<?php echo esc_html( $post_type['graphql_plural_name'] ); ?>",
<?php else: ?>
		"show_in_graphql" => <?php echo disp_boolean( false ); ?>,
<?php endif; ?>
	];

	register_post_type( "<?php echo esc_html( $post_type['name'] ); ?>", $args );
<?php
}
