<?php
/**
 * Custom Post Type UI Tools.
 *
 * @package CPTUI
 * @subpackage Tools
 * @author WebDevStudios
 * @since 1.0.0
 * @license GPL-2.0+
 */

// phpcs:disable WebDevStudios.All.RequireAuthor

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue our Custom Post Type UI assets.
 *
 * @since 1.6.0
 *
 * @return void
 */
function cptui_tools_assets() {
	$current_screen = get_current_screen();

	if ( ! is_object( $current_screen ) || 'cpt-ui_page_cptui_tools' !== $current_screen->base ) {
		return;
	}

	if ( wp_doing_ajax() ) {
		return;
	}

	wp_enqueue_style( 'cptui-css' );
}
add_action( 'admin_enqueue_scripts', 'cptui_tools_assets' );

/**
 * Register our tabs for the Tools screen.
 *
 * @since 1.3.0
 * @since 1.5.0 Renamed to "Tools"
 *
 * @internal
 *
 * @param array  $tabs         Array of tabs to display. Optional.
 * @param string $current_page Current page being shown. Optional. Default empty string.
 * @return array Amended array of tabs to show.
 */
function cptui_tools_tabs( $tabs = [], $current_page = '' ) {

	if ( 'tools' === $current_page ) {
		$classes = [ 'nav-tab' ];

		$tabs['page_title']         = get_admin_page_title();
		$tabs['tabs']               = [];
		$tabs['tabs']['post_types'] = [
			'text'          => __( 'Post Types', 'custom-post-type-ui' ),
			'classes'       => $classes,
			'url'           => cptui_admin_url( 'admin.php?page=cptui_' . $current_page ),
			'aria-selected' => 'false',
		];

		$tabs['tabs']['taxonomies'] = [
			'text'          => __( 'Taxonomies', 'custom-post-type-ui' ),
			'classes'       => $classes,
			'url'           => esc_url( add_query_arg( [ 'action' => 'taxonomies' ], cptui_admin_url( 'admin.php?page=cptui_' . $current_page ) ) ),
			'aria-selected' => 'false',
		];

		$tabs['tabs']['get_code'] = [
			'text'          => __( 'Get Code', 'custom-post-type-ui' ),
			'classes'       => $classes,
			'url'           => esc_url( add_query_arg( [ 'action' => 'get_code' ], cptui_admin_url( 'admin.php?page=cptui_' . $current_page ) ) ),
			'aria-selected' => 'false',
		];

		$tabs['tabs']['debuginfo'] = [
			'text'          => __( 'Debug Info', 'custom-post-type-ui' ),
			'classes'       => $classes,
			'url'           => esc_url( add_query_arg( [ 'action' => 'debuginfo' ], cptui_admin_url( 'admin.php?page=cptui_' . $current_page ) ) ),
			'aria-selected' => 'false',
		];

		$active_class = 'nav-tab-active';
		$action       = cptui_get_current_action();
		if ( ! empty( $action ) ) {
			if ( 'taxonomies' === $action ) {
				$tabs['tabs']['taxonomies']['classes'][]     = $active_class;
				$tabs['tabs']['taxonomies']['aria-selected'] = 'true';
			} elseif ( 'get_code' === $action ) {
				$tabs['tabs']['get_code']['classes'][]     = $active_class;
				$tabs['tabs']['get_code']['aria-selected'] = 'true';
			} elseif ( 'debuginfo' === $action ) {
				$tabs['tabs']['debuginfo']['classes'][]     = $active_class;
				$tabs['tabs']['debuginfo']['aria-selected'] = 'true';
			}
		} else {
			$tabs['tabs']['post_types']['classes'][]     = $active_class;
			$tabs['tabs']['post_types']['aria-selected'] = 'true';
		}

		/**
		 * Filters the tabs being added for the tools area.
		 *
		 * @since 1.5.0
		 *
		 * @param array  $tabs         Array of tabs to show.
		 * @param string $action       Current tab being shown.
		 * @param string $active_class Class to use to mark the tab active.
		 */
		$tabs = apply_filters( 'cptui_tools_tabs', $tabs, $action, $active_class );
	}

	return $tabs;
}
add_filter( 'cptui_get_tabs', 'cptui_tools_tabs', 10, 2 );

/**
 * Create our settings page output.
 *
 * @since 1.0.0
 *
 * @internal
 */
function cptui_tools() {

	$tab = 'post_types';
	if ( ! empty( $_GET ) ) {
		if ( ! empty( $_GET['action'] ) && 'taxonomies' === $_GET['action'] ) {
			$tab = 'taxonomies';
		} elseif ( ! empty( $_GET['action'] ) && 'get_code' === $_GET['action'] ) {
			$tab = 'get_code';
		} elseif ( ! empty( $_GET['action'] ) && 'debuginfo' === $_GET['action'] ) {
			$tab = 'debuginfo';
		}
	}

	echo '<div class="wrap">';

	/**
	 * Fires right inside the wrap div for the import/export pages.
	 *
	 * @since 1.3.0
	 *
	 * @deprecated 1.5.0
	 */
	do_action_deprecated( 'cptui_inside_importexport_wrap', [], '1.5.0', 'cptui_inside_tools_wrap' );

	/**
	 * Fires right inside the wrap div for the tools pages.
	 *
	 * @since 1.5.0
	 */
	do_action( 'cptui_inside_tools_wrap' );

	// Create our tabs.
	cptui_settings_tab_menu( 'tools' );

	/**
	 * Fires inside the markup for the import/export section.
	 *
	 * Allows for more modular control and adding more sections more easily.
	 *
	 * @since 1.2.0
	 *
	 * @deprecated 1.5.0
	 *
	 * @param string $tab Current tab being displayed.
	 */
	do_action_deprecated( 'cptui_import_export_sections', [ $tab ], '1.5.0', 'cptui_tools_sections' );

	/**
	 * Fires inside the markup for the tools section.
	 *
	 * Allows for more modular control and adding more sections more easily.
	 *
	 * @since 1.5.0
	 *
	 * @param string $tab Current tab being displayed.
	 */
	do_action( 'cptui_tools_sections', $tab );

	echo '</div><!-- End .wrap -->';
}

/**
 * Display our copy-able code for registered taxonomies.
 *
 * @since 1.0.0
 * @since 1.2.0 Added $cptui_taxonomies parameter.
 * @since 1.2.0 Added $single parameter.
 *
 * @param array $cptui_taxonomies Array of taxonomies to render. Optional.
 * @param bool  $single           Whether or not we are rendering a single taxonomy. Optional. Default false.
 */
function cptui_get_taxonomy_code( $cptui_taxonomies = [], $single = false ) {
	if ( ! empty( $cptui_taxonomies ) ) {
		$callback = 'cptui_register_my_taxes';
		if ( $single ) {
			$key      = key( $cptui_taxonomies );
			$callback = 'cptui_register_my_taxes_' . str_replace( '-', '_', esc_html( $cptui_taxonomies[ $key ]['name'] ) );
		}
	?>
function <?php echo esc_html( $callback ); ?>() {
<?php
foreach ( $cptui_taxonomies as $tax ) {
	echo cptui_get_single_taxonomy_registery( $tax );
} ?>
}
add_action( 'init', '<?php echo esc_html( $callback ); ?>' );
<?php
	} else {
		esc_html_e( 'No taxonomies to display at this time', 'custom-post-type-ui' );
	}
}

/**
 * Create output for single taxonomy to be ready for copy/paste from Get Code.
 *
 * @since 1.0.0
 *
 * @param array $taxonomy Taxonomy data to output. Optional.
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

	if ( ! empty( $taxonomy['meta_box_cb'] ) ) {
		$meta_box_cb = ( false !== get_disp_boolean( $taxonomy['meta_box_cb'] ) ) ? '"' . $taxonomy['meta_box_cb'] . '"' : 'false';
	}

	$default_term = '';
	if ( ! empty( $taxonomy['default_term'] ) ) {
		$term_parts = explode( ',', $taxonomy['default_term'] );
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
		"name" => __( "<?php echo esc_html( $taxonomy['label'] ); ?>", "<?php echo esc_html( $textdomain ); ?>" ),
		"singular_name" => __( "<?php echo esc_html( $taxonomy['singular_label'] ); ?>", "<?php echo esc_html( $textdomain ); ?>" ),
<?php
foreach ( $taxonomy['labels'] as $key => $label ) {
	if ( ! empty( $label ) ) {
		echo "\t\t" . '"' . esc_html( $key ) . '" => __( "' . esc_html( $label ) . '", "' . esc_html( $textdomain ) . '" ),' . "\n";
	}
}
?>
	];

	<?php
	$show_graphql = isset( $taxonomy['show_in_graphql'] ) ? (bool) $taxonomy['show_in_graphql'] : false;
	?>

	$args = [
		"label" => __( "<?php echo $taxonomy['label']; ?>", "<?php echo $textdomain; ?>" ),
		"labels" => $labels,
		"public" => <?php echo $public; ?>,
		"publicly_queryable" => <?php echo $publicly_queryable; ?>,
		"hierarchical" => <?php echo $taxonomy['hierarchical']; ?>,
		"show_ui" => <?php echo disp_boolean( $taxonomy['show_ui'] ); ?>,
		"show_in_menu" => <?php echo $show_in_menu; ?>,
		"show_in_nav_menus" => <?php echo $show_in_nav_menus; ?>,
		"query_var" => <?php echo disp_boolean( $taxonomy['query_var'] );?>,
		"rewrite" => <?php echo $rewrite; ?>,
		"show_admin_column" => <?php echo $taxonomy['show_admin_column']; ?>,
		"show_in_rest" => <?php echo $show_in_rest; ?>,
		"show_tagcloud" => <?php echo $show_tagcloud; ?>,
		"rest_base" => "<?php echo $rest_base; ?>",
		"rest_controller_class" => "<?php echo $rest_controller_class; ?>",
		"show_in_quick_edit" => <?php echo $show_in_quick_edit; ?>,
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

/**
 * Display our copy-able code for registered post types.
 *
 * @since 1.0.0
 * @since 1.2.0 Added $cptui_post_types parameter.
 * @since 1.2.0 Added $single parameter.
 *
 * @param array $cptui_post_types Array of post types to render. Optional.
 * @param bool  $single           Whether or not we are rendering a single post type. Optional. Default false.
 */
function cptui_get_post_type_code( $cptui_post_types = [], $single = false ) {
	// Whitespace very much matters here, thus why it's all flush against the left side.
	if ( ! empty( $cptui_post_types ) ) {
		$callback = 'cptui_register_my_cpts';
		if ( $single ) {
			$key      = key( $cptui_post_types );
			$callback = 'cptui_register_my_cpts_' . str_replace( '-', '_', esc_html( $cptui_post_types[ $key ]['name'] ) );
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
 * @since 1.0.0
 *
 * @param array $post_type Post type data to output. Optional.
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
		"name" => __( "<?php echo $post_type['label']; ?>", "<?php echo $textdomain; ?>" ),
		"singular_name" => __( "<?php echo $post_type['singular_label']; ?>", "<?php echo $textdomain; ?>" ),
<?php
	foreach ( $post_type['labels'] as $key => $label ) {
		if ( ! empty( $label ) ) {
			if ( 'parent' === $key && ! array_key_exists( 'parent_item_colon', $post_type['labels'] ) ) {
				// Fix for incorrect label key. See #439.
				echo "\t\t" . '"' . 'parent_item_colon' . '" => __( "' . $label . '", "' . $textdomain . '" ),' . "\n";
			} else {
				echo "\t\t" . '"' . $key . '" => __( "' . $label . '", "' . $textdomain . '" ),' . "\n";
			}
		}
	}
?>
	];

	$args = [
		"label" => __( "<?php echo $post_type['label']; ?>", "<?php echo $textdomain; ?>" ),
		"labels" => $labels,
		"description" => "<?php echo $post_type['description']; ?>",
		"public" => <?php echo disp_boolean( $post_type['public'] ); ?>,
		"publicly_queryable" => <?php echo disp_boolean( $post_type['publicly_queryable'] ); ?>,
		"show_ui" => <?php echo disp_boolean( $post_type['show_ui'] ); ?>,
		"show_in_rest" => <?php echo disp_boolean( $post_type['show_in_rest'] ); ?>,
		"rest_base" => "<?php echo $post_type['rest_base']; ?>",
		"rest_controller_class" => "<?php echo $rest_controller_class; ?>",
		"has_archive" => <?php echo $has_archive; ?>,
		"show_in_menu" => <?php echo $show_in_menu; ?>,
		"show_in_nav_menus" => <?php echo $show_in_nav_menus; ?>,
		"delete_with_user" => <?php echo $delete_with_user; ?>,
		"exclude_from_search" => <?php echo disp_boolean( $post_type['exclude_from_search'] ); ?>,
		"capability_type" => <?php echo $capability_type; ?>,
		"map_meta_cap" => <?php echo disp_boolean( $post_type['map_meta_cap'] ); ?>,
		"hierarchical" => <?php echo disp_boolean( $post_type['hierarchical'] ); ?>,
		"rewrite" => <?php echo $rewrite; ?>,
		"query_var" => <?php echo $post_type['query_var']; ?>,
<?php if ( ! empty( $post_type['menu_position'] ) ) { ?>
		"menu_position" => <?php echo $post_type['menu_position']; ?>,
<?php } ?>
<?php if ( ! empty( $post_type['menu_icon'] ) ) { ?>
		"menu_icon" => "<?php echo $post_type['menu_icon']; ?>",
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

/**
 * Import the posted JSON data from a separate export.
 *
 * @since 1.0.0
 *
 * @internal
 *
 * @param array $postdata $_POST data as json. Optional.
 * @return mixed false on nothing to do, otherwise void.
 */
function cptui_import_types_taxes_settings( $postdata = [] ) {
	if ( ! isset( $postdata['cptui_post_import'] ) && ! isset( $postdata['cptui_tax_import'] ) && ! array_key_exists( 'delete', $postdata ) ) {
		return false;
	}

	$doing_wp_cli = ( defined( 'WP_CLI' ) && WP_CLI );
	if ( ! $doing_wp_cli && ! check_admin_referer( 'cptui_typetaximport_nonce_action', 'cptui_typetaximport_nonce_field' ) ) {
		return 'nonce_fail';
	}

	$status  = 'import_fail';
	$success = false;

	/**
	 * Filters the post type data to import.
	 *
	 * Allows third parties to provide their own data dump and import instead of going through our UI.
	 *
	 * @since 1.2.0
	 *
	 * @param bool $value Default to no data.
	 */
	$third_party_post_type_data = apply_filters( 'cptui_third_party_post_type_import', false );

	/**
	 * Filters the taxonomy data to import.
	 *
	 * Allows third parties to provide their own data dump and import instead of going through our UI.
	 *
	 * @since 1.2.0
	 *
	 * @param bool $value Default to no data.
	 */
	$third_party_taxonomy_data  = apply_filters( 'cptui_third_party_taxonomy_import', false );

	if ( false !== $third_party_post_type_data ) {
		$postdata['cptui_post_import'] = $third_party_post_type_data;
	}

	if ( false !== $third_party_taxonomy_data ) {
		$postdata['cptui_tax_import'] = $third_party_taxonomy_data;
	}

	if ( ! empty( $postdata['cptui_post_import'] ) || ( isset( $postdata['delete'] ) && 'type_true' === $postdata['delete'] ) ) {
		$settings = null;
		if ( ! empty( $postdata['cptui_post_import'] ) ) {
			$settings = $postdata['cptui_post_import'];
		}

		// Add support to delete settings outright, without accessing database.
		// Doing double check to protect.
		if ( null === $settings && ( isset( $postdata['delete'] ) && 'type_true' === $postdata['delete'] ) ) {

			/**
			 * Filters whether or not 3rd party options were deleted successfully within post type import.
			 *
			 * @since 1.3.0
			 *
			 * @param bool  $value    Whether or not someone else deleted successfully. Default false.
			 * @param array $postdata Post type data.
			 */
			if ( false === ( $success = apply_filters( 'cptui_post_type_import_delete_save', false, $postdata ) ) ) {
				$success = delete_option( 'cptui_post_types' );
			}
		}

		if ( $settings ) {
			if ( false !== cptui_get_post_type_data() ) {
				/** This filter is documented in /inc/import-export.php */
				if ( false === ( $success = apply_filters( 'cptui_post_type_import_delete_save', false, $postdata ) ) ) {
					delete_option( 'cptui_post_types' );
				}
			}

			/**
			 * Filters whether or not 3rd party options were updated successfully within the post type import.
			 *
			 * @since 1.3.0
			 *
			 * @param bool  $value    Whether or not someone else updated successfully. Default false.
			 * @param array $postdata Post type data.
			 */
			if ( false === ( $success = apply_filters( 'cptui_post_type_import_update_save', false, $postdata ) ) ) {
				$success = update_option( 'cptui_post_types', $settings );
			}
		}
		// Used to help flush rewrite rules on init.
		set_transient( 'cptui_flush_rewrite_rules', 'true', 5 * 60 );

		if ( $success ) {
			$status = 'import_success';
		}
	} elseif ( ! empty( $postdata['cptui_tax_import'] ) || ( isset( $postdata['delete'] ) && 'tax_true' === $postdata['delete'] ) ) {
		$settings = null;

		if ( ! empty( $postdata['cptui_tax_import'] ) ) {
			$settings = $postdata['cptui_tax_import'];
		}
		// Add support to delete settings outright, without accessing database.
		// Doing double check to protect.
		if ( null === $settings && ( isset( $postdata['delete'] ) && 'tax_true' === $postdata['delete'] ) ) {

			/**
			 * Filters whether or not 3rd party options were deleted successfully within taxonomy import.
			 *
			 * @since 1.3.0
			 *
			 * @param bool  $value    Whether or not someone else deleted successfully. Default false.
			 * @param array $postdata Taxonomy data
			 */
			if ( false === ( $success = apply_filters( 'cptui_taxonomy_import_delete_save', false, $postdata ) ) ) {
				$success = delete_option( 'cptui_taxonomies' );
			}
		}

		if ( $settings ) {
			if ( false !== cptui_get_taxonomy_data() ) {
				/** This filter is documented in /inc/import-export.php */
				if ( false === ( $success = apply_filters( 'cptui_taxonomy_import_delete_save', false, $postdata ) ) ) {
					delete_option( 'cptui_taxonomies' );
				}
			}
			/**
			 * Filters whether or not 3rd party options were updated successfully within the taxonomy import.
			 *
			 * @since 1.3.0
			 *
			 * @param bool  $value    Whether or not someone else updated successfully. Default false.
			 * @param array $postdata Taxonomy data.
			 */
			if ( false === ( $success = apply_filters( 'cptui_taxonomy_import_update_save', false, $postdata ) ) ) {
				$success = update_option( 'cptui_taxonomies', $settings );
			}
		}
		// Used to help flush rewrite rules on init.
		set_transient( 'cptui_flush_rewrite_rules', 'true', 5 * 60 );
		if ( $success ) {
			$status = 'import_success';
		}
	}

	return $status;
}

/**
 * Content for the Post Types/Taxonomies Tools tab.
 *
 * @since 1.2.0
 *
 * @internal
 */
function cptui_render_posttypes_taxonomies_section() {
?>

	<p><?php esc_html_e( 'If you are wanting to migrate registered post types or taxonomies from this site to another, that will also use Custom Post Type UI, use the import and export functionality. If you are moving away from Custom Post Type UI, use the information in the "Get Code" tab.', 'custom-post-type-ui' ); ?></p>

<p>
<?php
	printf(
		'<strong>%s</strong>: %s',
		esc_html__( 'NOTE', 'custom-post-type-ui' ),
		esc_html__( 'This will not export the associated posts or taxonomy terms, just the settings.', 'custom-post-type-ui' )
	);
?>
</p>
<table class="form-table cptui-table">
	<?php if ( ! empty( $_GET ) && empty( $_GET['action'] ) ) { ?>
		<tr>
			<td class="outer">
				<h2><label for="cptui_post_import"><?php esc_html_e( 'Import Post Types', 'custom-post-type-ui' ); ?></label></h2>

				<form method="post">
					<textarea class="cptui_post_import" placeholder="<?php esc_attr_e( 'Paste content here.', 'custom-post-type-ui' ); ?>" id="cptui_post_import" name="cptui_post_import"></textarea>

					<p class="wp-ui-highlight">
						<strong><?php esc_html_e( 'Note:', 'custom-post-type-ui' ); ?></strong> <?php esc_html_e( 'Importing will overwrite previous registered settings.', 'custom-post-type-ui' ); ?>
					</p>

					<p>
						<strong><?php esc_html_e( 'To import post types from a different WordPress site, paste the exported content from that site and click the "Import" button.', 'custom-post-type-ui' ); ?></strong>
					</p>

					<p>
						<input class="button button-primary" type="submit" value="<?php esc_attr_e( 'Import', 'custom-post-type-ui' ); ?>" />
					</p>
					<?php wp_nonce_field( 'cptui_typetaximport_nonce_action', 'cptui_typetaximport_nonce_field' ); ?>
				</form>
			</td>
			<td class="outer">
				<h2><label for="cptui_post_export"><?php esc_html_e( 'Export Post Types settings', 'custom-post-type-ui' ); ?></label></h2>
				<?php
				$cptui_post_types = cptui_get_post_type_data();
				if ( ! empty( $cptui_post_types ) ) {
					$content = esc_html( json_encode( $cptui_post_types ) );
				} else {
					$content = esc_html__( 'No post types registered yet.', 'custom-post-type-ui' );
				}
				?>
				<textarea title="<?php esc_attr_e( 'To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'custom-post-type-ui' ); ?>" onclick="this.focus();this.select();" onfocus="this.focus();this.select();" readonly="readonly" aria-readonly="true" class="cptui_post_import" id="cptui_post_export" name="cptui_post_export"><?php echo $content; // WPCS: XSS ok, sanitization ok. ?></textarea>

				<p>
					<strong><?php esc_html_e( 'Use the content above to import current post types into a different WordPress site. You can also use this to simply back up your post type settings.', 'custom-post-type-ui' ); ?></strong>
				</p>
			</td>
		</tr>
	<?php } elseif ( ! empty( $_GET ) && 'taxonomies' === $_GET['action'] ) { ?>
		<tr>
			<td class="outer">
				<h2><label for="cptui_tax_import"><?php esc_html_e( 'Import Taxonomies', 'custom-post-type-ui' ); ?></label></h2>

				<form method="post">
					<textarea class="cptui_tax_import" placeholder="<?php esc_attr_e( 'Paste content here.', 'custom-post-type-ui' ); ?>" id="cptui_tax_import" name="cptui_tax_import"></textarea>

					<p class="wp-ui-highlight">
						<strong><?php esc_html_e( 'Note:', 'custom-post-type-ui' ); ?></strong> <?php esc_html_e( 'Importing will overwrite previous registered settings.', 'custom-post-type-ui' ); ?>
					</p>

					<p>
						<strong><?php esc_html_e( 'To import taxonomies from a different WordPress site, paste the exported content from that site and click the "Import" button.', 'custom-post-type-ui' ); ?></strong>
					</p>

					<p>
						<input class="button button-primary" type="submit" value="<?php esc_attr_e( 'Import', 'custom-post-type-ui' ); ?>" />
					</p>
					<?php wp_nonce_field( 'cptui_typetaximport_nonce_action', 'cptui_typetaximport_nonce_field' ); ?>
				</form>
			</td>
			<td class="outer">
				<h2><label for="cptui_tax_export"><?php esc_html_e( 'Export Taxonomies settings', 'custom-post-type-ui' ); ?></label></h2>
				<?php
				$cptui_taxonomies = cptui_get_taxonomy_data();
				if ( ! empty( $cptui_taxonomies ) ) {
					$content = esc_html( json_encode( $cptui_taxonomies ) );
				} else {
					$content = esc_html__( 'No taxonomies registered yet.', 'custom-post-type-ui' );
				}
				?>
				<textarea title="<?php esc_attr_e( 'To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'custom-post-type-ui' ); ?>" onclick="this.focus();this.select()" onfocus="this.focus();this.select();" readonly="readonly" aria-readonly="true" class="cptui_tax_import" id="cptui_tax_export" name="cptui_tax_export"><?php echo $content; //WPCS: XSS ok, sanitization ok.?></textarea>

				<p>
					<strong><?php esc_html_e( 'Use the content above to import current taxonomies into a different WordPress site. You can also use this to simply back up your taxonomy settings.', 'custom-post-type-ui' ); ?></strong>
				</p>
			</td>
		</tr>
	<?php } ?>
</table>
<?php
}

/**
 * Content for the Get Code tab.
 *
 * @since 1.2.0
 *
 * @internal
 */
function cptui_render_getcode_section() {
?>
	<h1><?php esc_html_e( 'Get Post Type and Taxonomy Code', 'custom-post-type-ui' ); ?></h1>

		<h2><?php esc_html_e( 'All Custom Post Type UI Post Types', 'custom-post-type-ui' ); ?></h2>

		<p><?php esc_html_e( 'All of the selectable code snippets below are useful if you wish to migrate away from Custom Post Type UI and retain your existing registered post types or taxonomies.', 'custom-post-type-ui' ); ?></p>

		<?php $cptui_post_types = cptui_get_post_type_data(); ?>
		<p><label for="cptui_post_type_get_code"><?php esc_html_e( 'Copy/paste the code below into your functions.php file.', 'custom-post-type-ui' ); ?></label></p>
		<textarea name="cptui_post_type_get_code" id="cptui_post_type_get_code" class="large-text cptui_post_type_get_code" onclick="this.focus();this.select()" onfocus="this.focus();this.select();" readonly="readonly" aria-readonly="true"><?php cptui_get_post_type_code( $cptui_post_types ); ?></textarea>

		<?php
		if ( ! empty( $cptui_post_types ) ) {
			foreach ( $cptui_post_types as $post_type ) {
			?>
				<h2 id="<?php echo esc_attr( $post_type['name'] ); ?>">
					<?php
					$type = ! empty( $post_type['label'] ) ? esc_html( $post_type['label'] ) : esc_html( $post_type['name'] );
					printf( esc_html__( '%s Post Type', 'custom-post-type-ui' ), esc_html( $type ) ); ?></h2>
				<p><label for="cptui_post_type_get_code_<?php echo esc_attr( $post_type['name'] ); ?>"><?php esc_html_e( 'Copy/paste the code below into your functions.php file.', 'custom-post-type-ui' ); ?></label></p>
				<textarea name="cptui_post_type_get_code_<?php echo esc_attr( $post_type['name'] ); ?>" id="cptui_post_type_get_code_<?php echo esc_attr( $post_type['name'] ); ?>" class="large-text cptui_post_type_get_code" onclick="this.focus();this.select()" onfocus="this.focus();this.select();" readonly="readonly" aria-readonly="true"><?php cptui_get_post_type_code( [ $post_type ], true ); ?></textarea>
			<?php
			}
		}
		?>

		<h2><?php esc_html_e( 'All Custom Post Type UI Taxonomies', 'custom-post-type-ui' ); ?></h2>

		<?php $cptui_taxonomies = cptui_get_taxonomy_data(); ?>
		<p><label for="cptui_tax_get_code"><?php esc_html_e( 'Copy/paste the code below into your functions.php file.', 'custom-post-type-ui' ); ?></label></p>
		<textarea name="cptui_tax_get_code" id="cptui_tax_get_code" class="large-text cptui_tax_get_code" onclick="this.focus();this.select()" onfocus="this.focus();this.select();" readonly="readonly" aria-readonly="true"><?php cptui_get_taxonomy_code( $cptui_taxonomies ); ?></textarea>

		<?php
		if ( ! empty( $cptui_taxonomies ) ) {
			foreach ( $cptui_taxonomies as $taxonomy ) {
			?>
				<h2 id="<?php echo esc_attr( $taxonomy['name'] ); ?>">
					<?php
					$tax = ! empty( $taxonomy['label'] ) ? esc_html( $taxonomy['label'] ) : esc_html( $taxonomy['name'] );
					printf( esc_html__( '%s Taxonomy', 'custom-post-type-ui' ), esc_html( $tax ) );
					?>
				</h2>
				<p><label for="cptui_tax_get_code_<?php echo esc_attr( $taxonomy['name'] ); ?>"><?php esc_html_e( 'Copy/paste the code below into your functions.php file.', 'custom-post-type-ui' ); ?></label></p>
				<textarea name="cptui_tax_get_code_<?php echo esc_attr( $taxonomy['name'] ); ?>" id="cptui_tax_get_code_<?php echo esc_attr( $taxonomy['name'] ); ?>" class="large-text cptui_tax_get_code" onclick="this.focus();this.select()" onfocus="this.focus();this.select();" readonly="readonly" aria-readonly="true"><?php cptui_get_taxonomy_code( [ $taxonomy ], true ); ?></textarea>
			<?php
			}
		}
		?>
	<?php
}

/**
 * Content for the Debug Info tab.
 *
 * @since 1.2.0
 *
 * @internal
 */
function cptui_render_debuginfo_section() {

	$debuginfo = new CPTUI_Debug_Info();

	echo '<form id="cptui_debug_info" method="post">';
	$debuginfo->tab_site_info();

	wp_nonce_field( 'cptui_debuginfo_nonce_action', 'cptui_debuginfo_nonce_field' );

	if ( ! empty( $_POST ) && isset( $_POST['cptui_debug_info_email'] ) && isset( $_POST['cptui_debuginfo_nonce_field'] ) ) {
		wp_verify_nonce( 'cptui_debuginfo_nonce_field', 'cptui_debuginfo_nonce_action' );

		$email_args          = [];
		$email_args['email'] = sanitize_text_field( $_POST['cptui_debug_info_email'] );
		$debuginfo->send_email( $email_args );
	}

	echo '<p><label for="cptui_debug_info_email">' . esc_html__( 'Please provide an email address to send debug information to: ', 'custom-post-type-ui' ) . '</label><input type="email" id="cptui_debug_info_email" name="cptui_debug_info_email" value="" /></p>';

	/**
	 * Filters the text value to use on the button when sending debug information.
	 *
	 * @since 1.2.0
	 *
	 * @param string $value Text to use for the button.
	 */
	echo '<p><input type="submit" class="button-primary" name="cptui_send_debug_email" value="' . esc_attr( apply_filters( 'cptui_debug_email_submit_button', __( 'Send debug info', 'custom-post-type-ui' ) ) ) . '" /></p>';
	echo '</form>';

	/**
	 * Fires after the display of the site information.
	 *
	 * @since 1.3.0
	 */
	do_action( 'cptui_after_site_info' );
}

/**
 * Renders various tab sections for the Tools page, based on current tab.
 *
 * @since 1.2.0
 *
 * @internal
 *
 * @param string $tab Current tab to display.
 */
function cptui_render_tools( $tab ) {
	if ( 'post_types' === $tab || 'taxonomies' === $tab ) {
		cptui_render_posttypes_taxonomies_section();
	}

	if ( 'get_code' === $tab ) {
		cptui_render_getcode_section();
	}

	if ( 'debuginfo' === $tab ) {
		cptui_render_debuginfo_section();
	}
}
add_action( 'cptui_tools_sections', 'cptui_render_tools' );

/**
 * Handle the import of transferred post types and taxonomies.
 *
 * @since 1.5.0
 */
function cptui_do_import_types_taxes() {

	if ( ! empty( $_POST ) &&
	     ( ! empty( $_POST['cptui_post_import'] ) && isset( $_POST['cptui_post_import'] ) ) ||
	     ( ! empty( $_POST['cptui_tax_import'] ) && isset( $_POST['cptui_tax_import'] ) )
	) {
		$data              = [];
		$decoded_post_data = null;
		$decoded_tax_data  = null;
		if ( ! empty( $_POST['cptui_post_import'] ) ) {
			$decoded_post_data = json_decode( stripslashes_deep( trim( $_POST['cptui_post_import'] ) ), true );
		}

		if ( ! empty( $_POST['cptui_tax_import'] ) ) {
			$decoded_tax_data = json_decode( stripslashes_deep( trim( $_POST['cptui_tax_import'] ) ), true );
		}

		if (
			empty( $decoded_post_data ) &&
			empty( $decoded_tax_data ) &&
			(
				! empty( $_POST['cptui_post_import'] ) && '{""}' !== stripslashes_deep( trim( $_POST['cptui_post_import'] ) )
			) &&
			(
				! empty( $_POST['cptui_tax_import'] ) && '{""}' !== stripslashes_deep( trim( $_POST['cptui_tax_import'] ) )
			)
		) {
			return;
		}
		if ( null !== $decoded_post_data ) {
			$data['cptui_post_import'] = $decoded_post_data;
		}
		if ( null !== $decoded_tax_data ) {
			$data['cptui_tax_import'] = $decoded_tax_data;
		}
		if ( ! empty( $_POST['cptui_post_import'] ) && '{""}' === stripslashes_deep( trim( $_POST['cptui_post_import'] ) ) ) {
			$data['delete'] = 'type_true';
		}
		if ( ! empty( $_POST['cptui_tax_import'] ) && '{""}' === stripslashes_deep( trim( $_POST['cptui_tax_import'] ) ) ) {
			$data['delete'] = 'tax_true';
		}
		$success = cptui_import_types_taxes_settings( $data );
		add_action( 'admin_notices', "cptui_{$success}_admin_notice" );
	}
}
add_action( 'init', 'cptui_do_import_types_taxes', 8 );
