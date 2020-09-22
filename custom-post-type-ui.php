<?php
/**
 * Custom Post Type UI.
 *
 * For all your post type and taxonomy needs.
 *
 * @package CPTUI
 * @subpackage Loader
 * @author WebDevStudios
 * @since 0.1.0.0
 * @license GPL-2.0+
 */

/**
 * Plugin Name: Custom Post Type UI
 * Plugin URI: https://github.com/WebDevStudios/custom-post-type-ui/
 * Description: Admin panel for creating custom post types and custom taxonomies in WordPress
 * Author: WebDevStudios
 * Version: 1.8.1
 * Author URI: https://webdevstudios.com/
 * Text Domain: custom-post-type-ui
 * Domain Path: /languages
 * License: GPL-2.0+
 */

// phpcs:disable WebDevStudios.All.RequireAuthor

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CPT_VERSION', '1.8.1' ); // Left for legacy purposes.
define( 'CPTUI_VERSION', '1.8.1' );
define( 'CPTUI_WP_VERSION', get_bloginfo( 'version' ) );

/**
 * Load our Admin UI class that powers our form inputs.
 *
 * @since 1.0.0
 *
 * @internal
 */
function cptui_load_ui_class() {
	require_once plugin_dir_path( __FILE__ ) . 'classes/class.cptui_admin_ui.php';
	require_once plugin_dir_path( __FILE__ ) . 'classes/class.cptui_debug_info.php';
}
add_action( 'init', 'cptui_load_ui_class' );

/**
 * Set a transient used for redirection upon activation.
 *
 * @since 1.4.0
 */
function cptui_activation_redirect() {
	// Bail if activating from network, or bulk.
	if ( is_network_admin() ) {
		return;
	}

	// Add the transient to redirect.
	set_transient( 'cptui_activation_redirect', true, 30 );
}
add_action( 'activate_' . plugin_basename( __FILE__ ), 'cptui_activation_redirect' );

/**
 * Redirect user to CPTUI about page upon plugin activation.
 *
 * @since 1.4.0
 */
function cptui_make_activation_redirect() {

	if ( ! get_transient( 'cptui_activation_redirect' ) ) {
		return;
	}

	delete_transient( 'cptui_activation_redirect' );

	// Bail if activating from network, or bulk.
	if ( is_network_admin() ) {
		return;
	}

	if ( ! cptui_is_new_install() ) {
		return;
	}

	// Redirect to CPTUI about page.
	wp_safe_redirect(
		add_query_arg(
			[ 'page' => 'cptui_main_menu' ],
			cptui_admin_url( 'admin.php?page=cptui_main_menu' )
		)
	);
}
add_action( 'admin_init', 'cptui_make_activation_redirect', 1 );

/**
 * Flush our rewrite rules on deactivation.
 *
 * @since 0.8.0
 *
 * @internal
 */
function cptui_deactivation() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'cptui_deactivation' );

/**
 * Register our text domain.
 *
 * @since 0.8.0
 *
 * @internal
 */
function cptui_load_textdomain() {
	load_plugin_textdomain( 'custom-post-type-ui' );
}
add_action( 'plugins_loaded', 'cptui_load_textdomain' );

/**
 * Load our main menu.
 *
 * Submenu items added in version 1.1.0
 *
 * @since 0.1.0
 *
 * @internal
 */
function cptui_plugin_menu() {

	/**
	 * Filters the required capability to manage CPTUI settings.
	 *
	 * @since 1.3.0
	 *
	 * @param string $value Capability required.
	 */
	$capability  = apply_filters( 'cptui_required_capabilities', 'manage_options' );
	$parent_slug = 'cptui_main_menu';

	add_menu_page( __( 'Custom Post Types', 'custom-post-type-ui' ), __( 'CPT UI', 'custom-post-type-ui' ), $capability, $parent_slug, 'cptui_settings', cptui_menu_icon() );
	add_submenu_page( $parent_slug, __( 'Add/Edit Post Types', 'custom-post-type-ui' ), __( 'Add/Edit Post Types', 'custom-post-type-ui' ), $capability, 'cptui_manage_post_types', 'cptui_manage_post_types' );
	add_submenu_page( $parent_slug, __( 'Add/Edit Taxonomies', 'custom-post-type-ui' ), __( 'Add/Edit Taxonomies', 'custom-post-type-ui' ), $capability, 'cptui_manage_taxonomies', 'cptui_manage_taxonomies' );
	add_submenu_page( $parent_slug, __( 'Registered Types and Taxes', 'custom-post-type-ui' ), __( 'Registered Types/Taxes', 'custom-post-type-ui' ), $capability, 'cptui_listings', 'cptui_listings' );
	add_submenu_page( $parent_slug, __( 'Custom Post Type UI Tools', 'custom-post-type-ui' ), __( 'Tools', 'custom-post-type-ui' ), $capability, 'cptui_tools', 'cptui_tools' );
	add_submenu_page( $parent_slug, __( 'Help/Support', 'custom-post-type-ui' ), __( 'Help/Support', 'custom-post-type-ui' ), $capability, 'cptui_support', 'cptui_support' );

	/**
	 * Fires after the default submenu pages.
	 *
	 * @since 1.3.0
	 *
	 * @param string $value      Parent slug for Custom Post Type UI menu.
	 * @param string $capability Capability required to manage CPTUI settings.
	 */
	do_action( 'cptui_extra_menu_items', $parent_slug, $capability );

	// Remove the default one so we can add our customized version.
	remove_submenu_page( $parent_slug, 'cptui_main_menu' );
	add_submenu_page( $parent_slug, __( 'About CPT UI', 'custom-post-type-ui' ), __( 'About CPT UI', 'custom-post-type-ui' ), $capability, 'cptui_main_menu', 'cptui_settings' );
}
add_action( 'admin_menu', 'cptui_plugin_menu' );

/**
 * Fire our CPTUI Loaded hook.
 *
 * @since 1.3.0
 *
 * @internal Use `cptui_loaded` hook.
 */
function cptui_loaded() {

	/**
	 * Fires upon plugins_loaded WordPress hook.
	 *
	 * CPTUI loads its required files on this hook.
	 *
	 * @since 1.3.0
	 */
	do_action( 'cptui_loaded' );
}
add_action( 'plugins_loaded', 'cptui_loaded' );

/**
 * Load our submenus.
 *
 * @since 1.0.0
 *
 * @internal
 */
function cptui_create_submenus() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/about.php';
	require_once plugin_dir_path( __FILE__ ) . 'inc/utility.php';
	require_once plugin_dir_path( __FILE__ ) . 'inc/post-types.php';
	require_once plugin_dir_path( __FILE__ ) . 'inc/taxonomies.php';
	require_once plugin_dir_path( __FILE__ ) . 'inc/listings.php';
	require_once plugin_dir_path( __FILE__ ) . 'inc/tools.php';
	require_once plugin_dir_path( __FILE__ ) . 'inc/support.php';

	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		require_once plugin_dir_path( __FILE__ ) . 'inc/wp-cli.php';
	}
}
add_action( 'cptui_loaded', 'cptui_create_submenus' );

/**
 * Fire our CPTUI init hook.
 *
 * @since 1.3.0
 *
 * @internal Use `cptui_init` hook.
 */
function cptui_init() {

	/**
	 * Fires upon init WordPress hook.
	 *
	 * @since 1.3.0
	 */
	do_action( 'cptui_init' );
}
add_action( 'init', 'cptui_init' );

/**
 * Enqueue CPTUI admin styles.
 *
 * @since 1.0.0
 *
 * @internal
 */
function cptui_add_styles() {
	if ( wp_doing_ajax() ) {
		return;
	}

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	wp_register_script( 'cptui', plugins_url( "js/cptui{$min}.js", __FILE__ ), [ 'jquery', 'jquery-ui-dialog', 'postbox' ], CPTUI_VERSION, true );
	wp_register_style( 'cptui-css', plugins_url( "css/cptui{$min}.css", __FILE__ ), [ 'wp-jquery-ui-dialog' ], CPTUI_VERSION );
}
add_action( 'admin_enqueue_scripts', 'cptui_add_styles' );

/**
 * Register our users' custom post types.
 *
 * @since 0.5.0
 *
 * @internal
 */
function cptui_create_custom_post_types() {
	$cpts = get_option( 'cptui_post_types' );

	if ( empty( $cpts ) ) {
		return;
	}

	/**
	 * Fires before the start of the post type registrations.
	 *
	 * @since 1.3.0
	 *
	 * @param array $cpts Array of post types to register.
	 */
	do_action( 'cptui_pre_register_post_types', $cpts );

	if ( is_array( $cpts ) ) {
		foreach ( $cpts as $post_type ) {

			/**
			 * Filters whether or not to skip registration of the current iterated post type.
			 *
			 * Dynamic part of the filter name is the chosen post type slug.
			 *
			 * @since 1.7.0
			 *
			 * @param bool  $value     Whether or not to skip the post type.
			 * @param array $post_type Current post type being registered.
			 */
			if ( (bool) apply_filters( "cptui_disable_{$post_type['name']}_cpt", false, $post_type ) ) {
				continue;
			}

			/**
			 * Filters whether or not to skip registration of the current iterated post type.
			 *
			 * @since 1.7.0
			 *
			 * @param bool  $value     Whether or not to skip the post type.
			 * @param array $post_type Current post type being registered.
			 */
			if ( (bool) apply_filters( 'cptui_disable_cpt', false, $post_type ) ) {
				continue;
			}

			cptui_register_single_post_type( $post_type );
		}
	}

	/**
	 * Fires after the completion of the post type registrations.
	 *
	 * @since 1.3.0
	 *
	 * @param array $cpts Array of post types registered.
	 */
	do_action( 'cptui_post_register_post_types', $cpts );
}
add_action( 'init', 'cptui_create_custom_post_types', 10 ); // Leave on standard init for legacy purposes.

/**
 * Helper function to register the actual post_type.
 *
 * @since 1.0.0
 *
 * @internal
 *
 * @param array $post_type Post type array to register. Optional.
 * @return null Result of register_post_type.
 */
function cptui_register_single_post_type( $post_type = [] ) {

	/**
	 * Filters the map_meta_cap value.
	 *
	 * @since 1.0.0
	 *
	 * @param bool   $value     True.
	 * @param string $name      Post type name being registered.
	 * @param array  $post_type All parameters for post type registration.
	 */
	$post_type['map_meta_cap'] = apply_filters( 'cptui_map_meta_cap', true, $post_type['name'], $post_type );

	if ( empty( $post_type['supports'] ) ) {
		$post_type['supports'] = [];
	}

	/**
	 * Filters custom supports parameters for 3rd party plugins.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $value     Empty array to add supports keys to.
	 * @param string $name      Post type slug being registered.
	 * @param array  $post_type Array of post type arguments to be registered.
	 */
	$user_supports_params = apply_filters( 'cptui_user_supports_params', [], $post_type['name'], $post_type );

	if ( is_array( $user_supports_params ) && ! empty( $user_supports_params ) ) {
		if ( is_array( $post_type['supports'] ) ) {
			$post_type['supports'] = array_merge( $post_type['supports'], $user_supports_params );
		} else {
			$post_type['supports'] = [ $user_supports_params ];
		}
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
			$post_type['supports'][] = trim( $part );
		}
	}

	if ( isset( $post_type['supports'] ) && is_array( $post_type['supports'] ) && in_array( 'none', $post_type['supports'], true ) ) {
		$post_type['supports'] = false;
	}

	$labels = [
		'name'          => $post_type['label'],
		'singular_name' => $post_type['singular_label'],
	];

	$preserved        = cptui_get_preserved_keys( 'post_types' );
	$preserved_labels = cptui_get_preserved_labels();
	foreach ( $post_type['labels'] as $key => $label ) {

		if ( ! empty( $label ) ) {
			if ( 'parent' === $key ) {
				$labels['parent_item_colon'] = $label;
			} else {
				$labels[ $key ] = $label;
			}
		} elseif ( empty( $label ) && in_array( $key, $preserved, true ) ) {
			$singular_or_plural = ( in_array( $key, array_keys( $preserved_labels['post_types']['plural'] ) ) ) ? 'plural' : 'singular';
			$label_plurality    = ( 'plural' === $singular_or_plural ) ? $post_type['label'] : $post_type['singular_label'];
			$labels[ $key ]     = sprintf( $preserved_labels['post_types'][ $singular_or_plural ][ $key ], $label_plurality );
		}
	}

	$has_archive = isset( $post_type['has_archive'] ) ? get_disp_boolean( $post_type['has_archive'] ) : false;
	if ( $has_archive && ! empty( $post_type['has_archive_string'] ) ) {
		$has_archive = $post_type['has_archive_string'];
	}

	$show_in_menu = get_disp_boolean( $post_type['show_in_menu'] );
	if ( ! empty( $post_type['show_in_menu_string'] ) ) {
		$show_in_menu = $post_type['show_in_menu_string'];
	}

	$rewrite = get_disp_boolean( $post_type['rewrite'] );
	if ( false !== $rewrite ) {
		// Core converts to an empty array anyway, so safe to leave this instead of passing in boolean true.
		$rewrite         = [];
		$rewrite['slug'] = ! empty( $post_type['rewrite_slug'] ) ? $post_type['rewrite_slug'] : $post_type['name'];

		$rewrite['with_front'] = true; // Default value.
		if ( isset( $post_type['rewrite_withfront'] ) ) {
			$rewrite['with_front'] = 'false' === disp_boolean( $post_type['rewrite_withfront'] ) ? false : true;
		}
	}

	$menu_icon = ! empty( $post_type['menu_icon'] ) ? $post_type['menu_icon'] : null;

	if ( in_array( $post_type['query_var'], [ 'true', 'false', '0', '1' ], true ) ) {
		$post_type['query_var'] = get_disp_boolean( $post_type['query_var'] );
	}
	if ( ! empty( $post_type['query_var_slug'] ) ) {
		$post_type['query_var'] = $post_type['query_var_slug'];
	}

	$menu_position = null;
	if ( ! empty( $post_type['menu_position'] ) ) {
		$menu_position = (int) $post_type['menu_position'];
	}

	$delete_with_user = null;
	if ( ! empty( $post_type['delete_with_user'] ) ) {
		$delete_with_user = get_disp_boolean( $post_type['delete_with_user'] );
	}

	$capability_type = 'post';
	if ( ! empty( $post_type['capability_type'] ) ) {
		$capability_type = $post_type['capability_type'];
		if ( false !== strpos( $post_type['capability_type'], ',' ) ) {
			$caps = array_map( 'trim', explode( ',', $post_type['capability_type'] ) );
			if ( count( $caps ) > 2 ) {
				$caps = array_slice( $caps, 0, 2 );
			}
			$capability_type = $caps;
		}
	}

	$public = get_disp_boolean( $post_type['public'] );
	if ( ! empty( $post_type['exclude_from_search'] ) ) {
		$exclude_from_search = get_disp_boolean( $post_type['exclude_from_search'] );
	} else {
		$exclude_from_search = false === $public;
	}

	$queryable = ( ! empty( $post_type['publicly_queryable'] ) && isset( $post_type['publicly_queryable'] ) ) ? get_disp_boolean( $post_type['publicly_queryable'] ) : $public;

	if ( empty( $post_type['show_in_nav_menus'] ) ) {
		// Defaults to value of public.
		$post_type['show_in_nav_menus'] = $public;
	}

	if ( empty( $post_type['show_in_rest'] ) ) {
		$post_type['show_in_rest'] = false;
	}

	$rest_base = null;
	if ( ! empty( $post_type['rest_base'] ) ) {
		$rest_base = $post_type['rest_base'];
	}

	$rest_controller_class = null;
	if ( ! empty( $post_type['rest_controller_class'] ) ) {
		$rest_controller_class = $post_type['rest_controller_class'];
	}

	$args = [
		'labels'                => $labels,
		'description'           => $post_type['description'],
		'public'                => get_disp_boolean( $post_type['public'] ),
		'publicly_queryable'    => $queryable,
		'show_ui'               => get_disp_boolean( $post_type['show_ui'] ),
		'show_in_nav_menus'     => get_disp_boolean( $post_type['show_in_nav_menus'] ),
		'has_archive'           => $has_archive,
		'show_in_menu'          => $show_in_menu,
		'delete_with_user'      => $delete_with_user,
		'show_in_rest'          => get_disp_boolean( $post_type['show_in_rest'] ),
		'rest_base'             => $rest_base,
		'rest_controller_class' => $rest_controller_class,
		'exclude_from_search'   => $exclude_from_search,
		'capability_type'       => $capability_type,
		'map_meta_cap'          => $post_type['map_meta_cap'],
		'hierarchical'          => get_disp_boolean( $post_type['hierarchical'] ),
		'rewrite'               => $rewrite,
		'menu_position'         => $menu_position,
		'menu_icon'             => $menu_icon,
		'query_var'             => $post_type['query_var'],
		'supports'              => $post_type['supports'],
		'taxonomies'            => $post_type['taxonomies'],
	];

	if ( true === $yarpp ) {
		$args['yarpp_support'] = $yarpp;
	}

	/**
	 * Filters the arguments used for a post type right before registering.
	 *
	 * @since 1.0.0
	 * @since 1.3.0 Added original passed in values array
	 *
	 * @param array  $args      Array of arguments to use for registering post type.
	 * @param string $value     Post type slug to be registered.
	 * @param array  $post_type Original passed in values for post type.
	 */
	$args = apply_filters( 'cptui_pre_register_post_type', $args, $post_type['name'], $post_type );

	return register_post_type( $post_type['name'], $args );
}

/**
 * Register our users' custom taxonomies.
 *
 * @since 0.5.0
 *
 * @internal
 */
function cptui_create_custom_taxonomies() {
	$taxes = get_option( 'cptui_taxonomies' );

	if ( empty( $taxes ) ) {
		return;
	}

	/**
	 * Fires before the start of the taxonomy registrations.
	 *
	 * @since 1.3.0
	 *
	 * @param array $taxes Array of taxonomies to register.
	 */
	do_action( 'cptui_pre_register_taxonomies', $taxes );

	if ( is_array( $taxes ) ) {
		foreach ( $taxes as $tax ) {
			/**
			 * Filters whether or not to skip registration of the current iterated taxonomy.
			 *
			 * Dynamic part of the filter name is the chosen taxonomy slug.
			 *
			 * @since 1.7.0
			 *
			 * @param bool  $value Whether or not to skip the taxonomy.
			 * @param array $tax   Current taxonomy being registered.
			 */
			if ( (bool) apply_filters( "cptui_disable_{$tax['name']}_tax", false, $tax ) ) {
				continue;
			}

			/**
			 * Filters whether or not to skip registration of the current iterated taxonomy.
			 *
			 * @since 1.7.0
			 *
			 * @param bool  $value Whether or not to skip the taxonomy.
			 * @param array $tax   Current taxonomy being registered.
			 */
			if ( (bool) apply_filters( 'cptui_disable_tax', false, $tax ) ) {
				continue;
			}

			cptui_register_single_taxonomy( $tax );
		}
	}

	/**
	 * Fires after the completion of the taxonomy registrations.
	 *
	 * @since 1.3.0
	 *
	 * @param array $taxes Array of taxonomies registered.
	 */
	do_action( 'cptui_post_register_taxonomies', $taxes );
}
add_action( 'init', 'cptui_create_custom_taxonomies', 9 );  // Leave on standard init for legacy purposes.

/**
 * Helper function to register the actual taxonomy.
 *
 * @since 1.0.0
 *
 * @internal
 *
 * @param array $taxonomy Taxonomy array to register. Optional.
 * @return null Result of register_taxonomy.
 */
function cptui_register_single_taxonomy( $taxonomy = [] ) {

	$labels = [
		'name'          => $taxonomy['label'],
		'singular_name' => $taxonomy['singular_label'],
	];

	$description = '';
	if ( ! empty( $taxonomy['description'] ) ) {
		$description = $taxonomy['description'];
	}

	$preserved        = cptui_get_preserved_keys( 'taxonomies' );
	$preserved_labels = cptui_get_preserved_labels();
	foreach ( $taxonomy['labels'] as $key => $label ) {

		if ( ! empty( $label ) ) {
			$labels[ $key ] = $label;
		} elseif ( empty( $label ) && in_array( $key, $preserved, true ) ) {
			$singular_or_plural = ( in_array( $key, array_keys( $preserved_labels['taxonomies']['plural'] ) ) ) ? 'plural' : 'singular';
			$label_plurality    = ( 'plural' === $singular_or_plural ) ? $taxonomy['label'] : $taxonomy['singular_label'];
			$labels[ $key ]     = sprintf( $preserved_labels['taxonomies'][ $singular_or_plural ][ $key ], $label_plurality );
		}
	}

	$rewrite = get_disp_boolean( $taxonomy['rewrite'] );
	if ( false !== get_disp_boolean( $taxonomy['rewrite'] ) ) {
		$rewrite               = [];
		$rewrite['slug']       = ! empty( $taxonomy['rewrite_slug'] ) ? $taxonomy['rewrite_slug'] : $taxonomy['name'];
		$rewrite['with_front'] = true;
		if ( isset( $taxonomy['rewrite_withfront'] ) ) {
			$rewrite['with_front'] = ( 'false' === disp_boolean( $taxonomy['rewrite_withfront'] ) ) ? false : true;
		}
		$rewrite['hierarchical'] = false;
		if ( isset( $taxonomy['rewrite_hierarchical'] ) ) {
			$rewrite['hierarchical'] = ( 'true' === disp_boolean( $taxonomy['rewrite_hierarchical'] ) ) ? true : false;
		}
	}

	if ( in_array( $taxonomy['query_var'], [ 'true', 'false', '0', '1' ], true ) ) {
		$taxonomy['query_var'] = get_disp_boolean( $taxonomy['query_var'] );
	}
	if ( true === $taxonomy['query_var'] && ! empty( $taxonomy['query_var_slug'] ) ) {
		$taxonomy['query_var'] = $taxonomy['query_var_slug'];
	}

	$public             = ( ! empty( $taxonomy['public'] ) && false === get_disp_boolean( $taxonomy['public'] ) ) ? false : true;
	$publicly_queryable = ( ! empty( $taxonomy['publicly_queryable'] ) && false === get_disp_boolean( $taxonomy['publicly_queryable'] ) ) ? false : true;
	if ( empty( $taxonomy['publicly_queryable'] ) ) {
		$publicly_queryable = $public;
	}

	$show_admin_column = ( ! empty( $taxonomy['show_admin_column'] ) && false !== get_disp_boolean( $taxonomy['show_admin_column'] ) ) ? true : false;

	$show_in_menu = ( ! empty( $taxonomy['show_in_menu'] ) && false !== get_disp_boolean( $taxonomy['show_in_menu'] ) ) ? true : false;

	if ( empty( $taxonomy['show_in_menu'] ) ) {
		$show_in_menu = get_disp_boolean( $taxonomy['show_ui'] );
	}

	$show_in_nav_menus = ( ! empty( $taxonomy['show_in_nav_menus'] ) && false !== get_disp_boolean( $taxonomy['show_in_nav_menus'] ) ) ? true : false;
	if ( empty( $taxonomy['show_in_nav_menus'] ) ) {
		$show_in_nav_menus = $public;
	}

	$show_in_rest = ( ! empty( $taxonomy['show_in_rest'] ) && false !== get_disp_boolean( $taxonomy['show_in_rest'] ) ) ? true : false;

	$show_in_quick_edit = ( ! empty( $taxonomy['show_in_quick_edit'] ) && false !== get_disp_boolean( $taxonomy['show_in_quick_edit'] ) ) ? true : false;

	$rest_base = null;
	if ( ! empty( $taxonomy['rest_base'] ) ) {
		$rest_base = $taxonomy['rest_base'];
	}

	$rest_controller_class = null;
	if ( ! empty( $post_type['rest_controller_class'] ) ) {
		$rest_controller_class = $post_type['rest_controller_class'];
	}

	$meta_box_cb = null;
	if ( ! empty( $taxonomy['meta_box_cb'] ) ) {
		$meta_box_cb = ( false !== get_disp_boolean( $taxonomy['meta_box_cb'] ) ) ? $taxonomy['meta_box_cb'] : false;
	}
	$default_term = null;
	if ( ! empty( $taxonomy['default_term'] ) ) {
		$term_parts = explode(',', $taxonomy['default_term'] );
		if ( ! empty( $term_parts[0] ) ) {
			$default_term['name'] = trim( $term_parts[0] );
		}
		if ( ! empty( $term_parts[1] ) ) {
			$default_term['slug'] = trim( $term_parts[1] );
		}
		if ( ! empty( $term_parts[2] ) ) {
			$default_term['description'] = trim( $term_parts[2] );
		}
	}

	$args = [
		'labels'                => $labels,
		'label'                 => $taxonomy['label'],
		'description'           => $description,
		'public'                => $public,
		'publicly_queryable'    => $publicly_queryable,
		'hierarchical'          => get_disp_boolean( $taxonomy['hierarchical'] ),
		'show_ui'               => get_disp_boolean( $taxonomy['show_ui'] ),
		'show_in_menu'          => $show_in_menu,
		'show_in_nav_menus'     => $show_in_nav_menus,
		'query_var'             => $taxonomy['query_var'],
		'rewrite'               => $rewrite,
		'show_admin_column'     => $show_admin_column,
		'show_in_rest'          => $show_in_rest,
		'rest_base'             => $rest_base,
		'rest_controller_class' => $rest_controller_class,
		'show_in_quick_edit'    => $show_in_quick_edit,
		'meta_box_cb'           => $meta_box_cb,
		'default_term'          => $default_term,
	];

	$object_type = ! empty( $taxonomy['object_types'] ) ? $taxonomy['object_types'] : '';

	/**
	 * Filters the arguments used for a taxonomy right before registering.
	 *
	 * @since 1.0.0
	 * @since 1.3.0 Added original passed in values array
	 * @since 1.6.0 Added $obect_type variable to passed parameters.
	 *
	 * @param array  $args        Array of arguments to use for registering taxonomy.
	 * @param string $value       Taxonomy slug to be registered.
	 * @param array  $taxonomy    Original passed in values for taxonomy.
	 * @param array  $object_type Array of chosen post types for the taxonomy.
	 */
	$args = apply_filters( 'cptui_pre_register_taxonomy', $args, $taxonomy['name'], $taxonomy, $object_type );

	return register_taxonomy( $taxonomy['name'], $object_type, $args );
}

/**
 * Construct and output tab navigation.
 *
 * @since 1.0.0
 *
 * @param string $page Whether it's the CPT or Taxonomy page. Optional. Default "post_types".
 * @return string
 */
function cptui_settings_tab_menu( $page = 'post_types' ) {

	/**
	 * Filters the tabs to render on a given page.
	 *
	 * @since 1.3.0
	 *
	 * @param array  $value Array of tabs to render.
	 * @param string $page  Current page being displayed.
	 */
	$tabs = (array) apply_filters( 'cptui_get_tabs', [], $page );

	if ( empty( $tabs['page_title'] ) ) {
		return '';
	}

	$tmpl = '<h1>%s</h1><nav class="nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">%s</nav>';

	$tab_output = '';
	foreach ( $tabs['tabs'] as $tab ) {
		$tab_output .= sprintf(
			'<a class="%s" href="%s" aria-selected="%s">%s</a>',
			implode( ' ', $tab['classes'] ),
			$tab['url'],
			$tab['aria-selected'],
			$tab['text']
		);
	}

	printf(
		$tmpl,
		$tabs['page_title'],
		$tab_output
	);
}

/**
 * Convert our old settings to the new options keys.
 *
 * These are left with standard get_option/update_option function calls for legacy and pending update purposes.
 *
 * @since 1.0.0
 *
 * @internal
 *
 * @return bool Whether or not options were successfully updated.
 */
function cptui_convert_settings() {

	if ( wp_doing_ajax() ) {
		return;
	}

	$retval = '';

	if ( false === get_option( 'cptui_post_types' ) && ( $post_types = get_option( 'cpt_custom_post_types' ) ) ) {

		$new_post_types = [];
		foreach ( $post_types as $type ) {
			$new_post_types[ $type['name'] ]               = $type; // This one assigns the # indexes. Named arrays are our friend.
			$new_post_types[ $type['name'] ]['supports']   = ! empty( $type[0] ) ? $type[0] : []; // Especially for multidimensional arrays.
			$new_post_types[ $type['name'] ]['taxonomies'] = ! empty( $type[1] ) ? $type[1] : [];
			$new_post_types[ $type['name'] ]['labels']     = ! empty( $type[2] ) ? $type[2] : [];
			unset(
				$new_post_types[ $type['name'] ][0],
				$new_post_types[ $type['name'] ][1],
				$new_post_types[ $type['name'] ][2]
			); // Remove our previous indexed versions.
		}

		$retval = update_option( 'cptui_post_types', $new_post_types );
	}

	if ( false === get_option( 'cptui_taxonomies' ) && ( $taxonomies = get_option( 'cpt_custom_tax_types' ) ) ) {

		$new_taxonomies = [];
		foreach ( $taxonomies as $tax ) {
			$new_taxonomies[ $tax['name'] ]                 = $tax;    // Yep, still our friend.
			$new_taxonomies[ $tax['name'] ]['labels']       = $tax[0]; // Taxonomies are the only thing with.
			$new_taxonomies[ $tax['name'] ]['object_types'] = $tax[1]; // "tax" in the name that I like.
			unset(
				$new_taxonomies[ $tax['name'] ][0],
				$new_taxonomies[ $tax['name'] ][1]
			);
		}

		$retval = update_option( 'cptui_taxonomies', $new_taxonomies );
	}

	if ( ! empty( $retval ) ) {
		flush_rewrite_rules();
	}

	return $retval;
}
add_action( 'admin_init', 'cptui_convert_settings' );

/**
 * Return a notice based on conditions.
 *
 * @since 1.0.0
 *
 * @param string $action       The type of action that occurred. Optional. Default empty string.
 * @param string $object_type  Whether it's from a post type or taxonomy. Optional. Default empty string.
 * @param bool   $success      Whether the action succeeded or not. Optional. Default true.
 * @param string $custom       Custom message if necessary. Optional. Default empty string.
 * @return bool|string false on no message, else HTML div with our notice message.
 */
function cptui_admin_notices( $action = '', $object_type = '', $success = true, $custom = '' ) {

	$class       = [];
	$class[]     = $success ? 'updated' : 'error';
	$class[]     = 'notice is-dismissible';
	$object_type = esc_attr( $object_type );

	$messagewrapstart = '<div id="message" class="' . implode( ' ', $class ) . '"><p>';
	$message = '';

	$messagewrapend = '</p></div>';

	if ( 'add' === $action ) {
		if ( $success ) {
			$message .= sprintf( __( '%s has been successfully added', 'custom-post-type-ui' ), $object_type );
		} else {
			$message .= sprintf( __( '%s has failed to be added', 'custom-post-type-ui' ), $object_type );
		}
	} elseif ( 'update' === $action ) {
		if ( $success ) {
			$message .= sprintf( __( '%s has been successfully updated', 'custom-post-type-ui' ), $object_type );
		} else {
			$message .= sprintf( __( '%s has failed to be updated', 'custom-post-type-ui' ), $object_type );
		}
	} elseif ( 'delete' === $action ) {
		if ( $success ) {
			$message .= sprintf( __( '%s has been successfully deleted', 'custom-post-type-ui' ), $object_type );
		} else {
			$message .= sprintf( __( '%s has failed to be deleted', 'custom-post-type-ui' ), $object_type );
		}
	} elseif ( 'import' === $action ) {
		if ( $success ) {
			$message .= sprintf( __( '%s has been successfully imported', 'custom-post-type-ui' ), $object_type );
		} else {
			$message .= sprintf( __( '%s has failed to be imported', 'custom-post-type-ui' ), $object_type );
		}
	} elseif ( 'error' === $action ) {
		if ( ! empty( $custom ) ) {
			$message = $custom;
		}
	}

	if ( $message ) {

		/**
		 * Filters the custom admin notice for CPTUI.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value            Complete HTML output for notice.
		 * @param string $action           Action whose message is being generated.
		 * @param string $message          The message to be displayed.
		 * @param string $messagewrapstart Beginning wrap HTML.
		 * @param string $messagewrapend   Ending wrap HTML.
		 */
		return apply_filters( 'cptui_admin_notice', $messagewrapstart . $message . $messagewrapend, $action, $message, $messagewrapstart, $messagewrapend );
	}

	return false;
}

/**
 * Return array of keys needing preserved.
 *
 * @since 1.0.5
 *
 * @param string $type Type to return. Either 'post_types' or 'taxonomies'. Optional. Default empty string.
 * @return array Array of keys needing preservered for the requested type.
 */
function cptui_get_preserved_keys( $type = '' ) {

	$preserved_labels = [
		'post_types' => [
			'add_new_item',
			'edit_item',
			'new_item',
			'view_item',
			'view_items',
			'all_items',
			'search_items',
			'not_found',
			'not_found_in_trash',
		],
		'taxonomies' => [
			'search_items',
			'popular_items',
			'all_items',
			'parent_item',
			'parent_item_colon',
			'edit_item',
			'update_item',
			'add_new_item',
			'new_item_name',
			'separate_items_with_commas',
			'add_or_remove_items',
			'choose_from_most_used',
		],
	];
	return ! empty( $type ) ? $preserved_labels[ $type ] : [];
}

/**
 * Return label for the requested type and label key.
 *
 * @since 1.0.5
 *
 * @deprecated
 *
 * @param string $type Type to return. Either 'post_types' or 'taxonomies'. Optional. Default empty string.
 * @param string $key Requested label key. Optional. Default empty string.
 * @param string $plural Plural verbiage for the requested label and type. Optional. Default empty string.
 * @param string $singular Singular verbiage for the requested label and type. Optional. Default empty string.
 * @return string Internationalized default label.
 */
function cptui_get_preserved_label( $type = '', $key = '', $plural = '', $singular = '' ) {

	$preserved_labels = [
		'post_types' => [
			'add_new_item'       => sprintf( __( 'Add new %s', 'custom-post-type-ui' ), $singular ),
			'edit_item'          => sprintf( __( 'Edit %s', 'custom-post-type-ui' ), $singular ),
			'new_item'           => sprintf( __( 'New %s', 'custom-post-type-ui' ), $singular ),
			'view_item'          => sprintf( __( 'View %s', 'custom-post-type-ui' ), $singular ),
			'view_items'         => sprintf( __( 'View %s', 'custom-post-type-ui' ), $plural ),
			'all_items'          => sprintf( __( 'All %s', 'custom-post-type-ui' ), $plural ),
			'search_items'       => sprintf( __( 'Search %s', 'custom-post-type-ui' ), $plural ),
			'not_found'          => sprintf( __( 'No %s found.', 'custom-post-type-ui' ), $plural ),
			'not_found_in_trash' => sprintf( __( 'No %s found in trash.', 'custom-post-type-ui' ), $plural ),
		],
		'taxonomies' => [
			'search_items'               => sprintf( __( 'Search %s', 'custom-post-type-ui' ), $plural ),
			'popular_items'              => sprintf( __( 'Popular %s', 'custom-post-type-ui' ), $plural ),
			'all_items'                  => sprintf( __( 'All %s', 'custom-post-type-ui' ), $plural ),
			'parent_item'                => sprintf( __( 'Parent %s', 'custom-post-type-ui' ), $singular ),
			'parent_item_colon'          => sprintf( __( 'Parent %s:', 'custom-post-type-ui' ), $singular ),
			'edit_item'                  => sprintf( __( 'Edit %s', 'custom-post-type-ui' ), $singular ),
			'update_item'                => sprintf( __( 'Update %s', 'custom-post-type-ui' ), $singular ),
			'add_new_item'               => sprintf( __( 'Add new %s', 'custom-post-type-ui' ), $singular ),
			'new_item_name'              => sprintf( __( 'New %s name', 'custom-post-type-ui' ), $singular ),
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'custom-post-type-ui' ), $plural ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'custom-post-type-ui' ), $plural ),
			'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'custom-post-type-ui' ), $plural ),
		],
	];

	return $preserved_labels[ $type ][ $key ];
}

/**
 * Returns an array of translated labels, ready for use with sprintf().
 *
 * Replacement for cptui_get_preserved_label for the sake of performance.
 *
 * @since 1.6.0
 *
 * @return array
 */
function cptui_get_preserved_labels() {
	return [
		'post_types' => [
			'singular' => [
				'add_new_item' => __( 'Add new %s', 'custom-post-type-ui' ),
				'edit_item'    => __( 'Edit %s', 'custom-post-type-ui' ),
				'new_item'     => __( 'New %s', 'custom-post-type-ui' ),
				'view_item'    => __( 'View %s', 'custom-post-type-ui' ),
			],
			'plural' => [
				'view_items'         => __( 'View %s', 'custom-post-type-ui' ),
				'all_items'          => __( 'All %s', 'custom-post-type-ui' ),
				'search_items'       => __( 'Search %s', 'custom-post-type-ui' ),
				'not_found'          => __( 'No %s found.', 'custom-post-type-ui' ),
				'not_found_in_trash' => __( 'No %s found in trash.', 'custom-post-type-ui' ),
			],
		],
		'taxonomies' => [
			'singular' => [
				'parent_item'       => __( 'Parent %s', 'custom-post-type-ui' ),
				'parent_item_colon' => __( 'Parent %s:', 'custom-post-type-ui' ),
				'edit_item'         => __( 'Edit %s', 'custom-post-type-ui' ),
				'update_item'       => __( 'Update %s', 'custom-post-type-ui' ),
				'add_new_item'      => __( 'Add new %s', 'custom-post-type-ui' ),
				'new_item_name'     => __( 'New %s name', 'custom-post-type-ui' ),
			],
			'plural' => [
				'search_items'               => __( 'Search %s', 'custom-post-type-ui' ),
				'popular_items'              => __( 'Popular %s', 'custom-post-type-ui' ),
				'all_items'                  => __( 'All %s', 'custom-post-type-ui' ),
				'separate_items_with_commas' => __( 'Separate %s with commas', 'custom-post-type-ui' ),
				'add_or_remove_items'        => __( 'Add or remove %s', 'custom-post-type-ui' ),
				'choose_from_most_used'      => __( 'Choose from the most used %s', 'custom-post-type-ui' ),
			],
		],
	];
}
