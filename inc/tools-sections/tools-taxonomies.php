<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display our copy-able code for registered taxonomies.
 *
 * @param array $cptui_taxonomies Array of taxonomies to render. Optional.
 * @param bool  $single           Whether or not we are rendering a single taxonomy. Optional. Default false.
 *
 * @since 1.2.0 Added $single parameter.
 * @since 1.0.0
 * @since 1.2.0 Added $cptui_taxonomies parameter.
 */
function cptui_get_taxonomy_code( $cptui_taxonomies = [], $single = false ) {
	// Whitespace very much matters here, thus why it's all flush against the left side.
	if ( ! empty( $cptui_taxonomies ) ) {
		$callback = 'cptui_register_my_taxes';
		if ( $single ) {
			$key      = key( $cptui_taxonomies );
			$callback = 'cptui_register_my_taxes_unknown'; // new fallback
			if ( ! empty( $cptui_taxonomies[ $key ]['name'] ) ) {
				// If we have a name value.
				$suffix = esc_html( $cptui_taxonomies[ $key ]['name'] );

				// if somehow our escaping is returning a null value.
				if ( ! empty( $suffix ) ) {
					$callback = 'cptui_register_my_taxes_' . str_replace( '-', '_', $suffix );
				}
			}
		}
		ob_start();
		?>
function <?php echo esc_html( $callback ); ?>() {
<?php
	foreach ( $cptui_taxonomies as $tax ) {
		echo cptui_get_single_taxonomy_registery( $tax );
	}
?>
}
add_action( 'init', '<?php echo esc_html( $callback ); ?>' );
<?php
	} else {
		esc_html_e( 'No taxonomies to display at this time', 'custom-post-type-ui' );
	}
	echo trim( ob_get_clean() );
}

/**
 * Create output for single taxonomy to be ready for copy/paste from Get Code.
 *
 * @param array $taxonomy Taxonomy data to output. Optional.
 *
 * @since 1.0.0
 */
function cptui_get_single_taxonomy_registery( $taxonomy = [] ) {

	$post_types = "''";
	if ( is_array( $taxonomy['object_types'] ) ) {
		$post_types = '[ "' . implode( '", "', $taxonomy['object_types'] ) . '" ]';
	}

	if ( false !== get_disp_boolean( $taxonomy['rewrite'] ) ) {
		$rewrite = disp_boolean( $taxonomy['rewrite'] );

		$rewrite_slug = ' \'slug\' => \'' . $taxonomy['name'] . '\',';
		if ( ! empty( $taxonomy['rewrite_slug'] ) ) {
			$rewrite_slug = ' \'slug\' => \'' . $taxonomy['rewrite_slug'] . '\',';
		}

		$rewrite_withfront = '';
		$withfront         = disp_boolean( $taxonomy['rewrite_withfront'] );
		if ( ! empty( $withfront ) ) {
			$rewrite_withfront = ' \'with_front\' => ' . $withfront . ', ';
		}

		$hierarchical         = ! empty( $taxonomy['rewrite_hierarchical'] ) ? disp_boolean( $taxonomy['rewrite_hierarchical'] ) : '';
		$rewrite_hierarchcial = '';
		if ( ! empty( $hierarchical ) ) {
			$rewrite_hierarchcial = ' \'hierarchical\' => ' . $hierarchical . ', ';
		}

		if ( ! empty( $taxonomy['rewrite_slug'] ) || false !== disp_boolean( $taxonomy['rewrite_withfront'] ) ) {
			$rewrite_start = '[';
			$rewrite_end   = ']';

			$rewrite = $rewrite_start . $rewrite_slug . $rewrite_withfront . $rewrite_hierarchcial . $rewrite_end;
		}
	} else {
		$rewrite = disp_boolean( $taxonomy['rewrite'] );
	}
	$public             = isset( $taxonomy['public'] ) ? disp_boolean( $taxonomy['public'] ) : 'true';
	$publicly_queryable = isset( $taxonomy['publicly_queryable'] ) ? disp_boolean( $taxonomy['publicly_queryable'] ) : disp_boolean( $taxonomy['public'] );
	$show_in_quick_edit = isset( $taxonomy['show_in_quick_edit'] ) ? disp_boolean( $taxonomy['show_in_quick_edit'] ) : disp_boolean( $taxonomy['show_ui'] );
	$show_tagcloud      = isset( $taxonomy['show_tagcloud'] ) ? disp_boolean( $taxonomy['show_tagcloud'] ) : disp_boolean( $taxonomy['show_ui'] );

	$show_in_menu = ( ! empty( $taxonomy['show_in_menu'] ) && false !== get_disp_boolean( $taxonomy['show_in_menu'] ) ) ? 'true' : 'false';
	if ( empty( $taxonomy['show_in_menu'] ) ) {
		$show_in_menu = disp_boolean( $taxonomy['show_ui'] );
	}

	$show_in_nav_menus = ( ! empty( $taxonomy['show_in_nav_menus'] ) && false !== get_disp_boolean( $taxonomy['show_in_nav_menus'] ) ) ? 'true' : 'false';
	if ( empty( $taxonomy['show_in_nav_menus'] ) ) {
		$show_in_nav_menus = $public;
	}

	$show_in_rest          = ( ! empty( $taxonomy['show_in_rest'] ) && false !== get_disp_boolean( $taxonomy['show_in_rest'] ) ) ? 'true' : 'false';
	$rest_base             = ! empty( $taxonomy['rest_base'] ) ? $taxonomy['rest_base'] : $taxonomy['name'];
	$rest_controller_class = ! empty( $taxonomy['rest_controller_class'] ) ? $taxonomy['rest_controller_class'] : 'WP_REST_Terms_Controller';
	$rest_namespace        = ! empty( $taxonomy['rest_namespace'] ) ? $taxonomy['rest_namespace'] : 'wp/v2';
	$sort                  = ( ! empty( $taxonomy['sort'] ) && false !== get_disp_boolean( $taxonomy['sort'] ) ) ? 'true' : 'false';

	if ( ! empty( $taxonomy['meta_box_cb'] ) ) {
		$meta_box_cb = ( false !== get_disp_boolean( $taxonomy['meta_box_cb'] ) ) ? '"' . $taxonomy['meta_box_cb'] . '"' : 'false';
	}

	$default_term = '';
	if ( ! empty( $taxonomy['default_term'] ) ) {
		$term_parts         = explode( ',', $taxonomy['default_term'] );
		$default_term_start = '[';
		$default_term_end   = ']';
		if ( ! empty( $term_parts[0] ) ) {
			$default_term .= "'name' => '" . trim( $term_parts[0] ) . "'";
		}
		if ( ! empty( $term_parts[1] ) ) {
			$default_term .= ", 'slug' => '" . trim( $term_parts[1] ) . "'";
		}
		if ( ! empty( $term_parts[2] ) ) {
			$default_term .= ", 'description' => '" . trim( $term_parts[2] ) . "'";
		}

		$default_term = $default_term_start . $default_term . $default_term_end;
	}

	$my_theme   = wp_get_theme();
	$textdomain = $my_theme->get( 'TextDomain' );
	if ( empty( $textdomain ) ) {
		$textdomain = 'custom-post-type-ui';
	}
	?>

	/**
	 * Taxonomy: <?php echo esc_html( $taxonomy['label'] ); ?>.
	 */

	$labels = [
		"name" => esc_html__( "<?php echo esc_html( $taxonomy['label'] ); ?>", "<?php echo esc_html( $textdomain ); ?>" ),
		"singular_name" => esc_html__( "<?php echo esc_html( $taxonomy['singular_label'] ); ?>", "<?php echo esc_html( $textdomain ); ?>" ),
<?php
foreach ( $taxonomy['labels'] as $key => $label ) {
	if ( ! empty( $label ) ) {
		echo "\t\t" . '"' . esc_html( $key ) . '" => esc_html__( "' . esc_html( $label ) . '", "' . esc_html( $textdomain ) . '" ),' . "\n";
	}
}
?>
	];

	<?php
	$show_graphql = isset( $taxonomy['show_in_graphql'] ) ? (bool) $taxonomy['show_in_graphql'] : false;
	?>

	$args = [
		"label" => esc_html__( "<?php echo $taxonomy['label']; ?>", "<?php echo $textdomain; ?>" ),
		"labels" => $labels,
		"public" => <?php echo $public; ?>,
		"publicly_queryable" => <?php echo $publicly_queryable; ?>,
		"hierarchical" => <?php echo $taxonomy['hierarchical']; ?>,
		"show_ui" => <?php echo disp_boolean( $taxonomy['show_ui'] ); ?>,
		"show_in_menu" => <?php echo $show_in_menu; ?>,
		"show_in_nav_menus" => <?php echo $show_in_nav_menus; ?>,
		"query_var" => <?php echo disp_boolean( $taxonomy['query_var'] ); ?>,
		"rewrite" => <?php echo $rewrite; ?>,
		"show_admin_column" => <?php echo $taxonomy['show_admin_column']; ?>,
		"show_in_rest" => <?php echo $show_in_rest; ?>,
		"show_tagcloud" => <?php echo $show_tagcloud; ?>,
		"rest_base" => "<?php echo $rest_base; ?>",
		"rest_controller_class" => "<?php echo $rest_controller_class; ?>",
		"rest_namespace" => "<?php echo $rest_namespace; ?>",
		"show_in_quick_edit" => <?php echo $show_in_quick_edit; ?>,
		"sort" => <?php echo $sort; ?>,
<?php if ( $show_graphql ) : ?>
		"show_in_graphql" => <?php echo disp_boolean( $taxonomy['show_in_graphql'] ); ?>,
		"graphql_single_name" => "<?php echo esc_html( $taxonomy['graphql_single_name'] ); ?>",
		"graphql_plural_name" => "<?php echo esc_html( $taxonomy['graphql_plural_name'] ); ?>",
<?php else: ?>
		"show_in_graphql" => <?php echo disp_boolean( false ); ?>,
<?php endif; ?>
<?php if ( ! empty( $meta_box_cb ) ) { ?>
		"meta_box_cb" => <?php echo $meta_box_cb; ?>,
<?php } ?>
<?php if ( ! empty( $default_term ) ) { ?>
		"default_term" => <?php echo $default_term; ?>,
<?php } ?>
	];
	register_taxonomy( "<?php echo esc_html( $taxonomy['name'] ); ?>", <?php echo $post_types; ?>, $args );
<?php
}
