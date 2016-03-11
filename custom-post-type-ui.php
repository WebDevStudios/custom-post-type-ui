<?php
/*
Plugin Name: Custom Post Type UI
Plugin URI: https://github.com/WebDevStudios/custom-post-type-ui/
Description: Admin panel for creating custom post types and custom taxonomies in WordPress
Author: WebDevStudios
Version: 1.1.2
Author URI: http://webdevstudios.com/
Text Domain: cpt-plugin
Domain Path: /languages
License: GPLv2
*/

# Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CPT_VERSION', '1.1.2' );
define( 'CPTUI_WP_VERSION', get_bloginfo( 'version' ) );

/**
 * Load our Admin UI class that powers our form inputs.
 *
 * @since 1.0.0
 */
function cptui_load_ui_class() {
	require_once( plugin_dir_path( __FILE__ ) . 'classes/class.cptui_admin_ui.php' );
}
add_action( 'init', 'cptui_load_ui_class' );

/**
 * Flush our rewrite rules on deactivation.
 *
 * @since 0.8.0
 */
function cptui_deactivation() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'cptui_deactivation' );

/**
 * Register our text domain.
 *
 * @since 0.8.0
 */
function cptui_load_textdomain() {
	load_plugin_textdomain( 'cpt-plugin', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'cptui_load_textdomain' );

/**
 * Load our main menu.
 *
 * Submenu items added in version 1.1.0
 *
 * @since 0.1.0
 */
function cptui_plugin_menu() {
	add_menu_page( __( 'Custom Post Types', 'cpt-plugin' ), __( 'CPT UI', 'cpt-plugin' ), 'manage_options', 'cptui_main_menu', 'cptui_settings' );
	add_submenu_page( 'cptui_main_menu', __( 'Add/Edit Post Types', 'cpt-plugin' ), __( 'Add/Edit Post Types', 'cpt-plugin' ), 'manage_options', 'cptui_manage_post_types', 'cptui_manage_post_types' );
	add_submenu_page( 'cptui_main_menu', __( 'Add/Edit Taxonomies', 'cpt-plugin' ), __( 'Add/Edit Taxonomies', 'cpt-plugin' ), 'manage_options', 'cptui_manage_taxonomies', 'cptui_manage_taxonomies' );
	add_submenu_page( 'cptui_main_menu', __( 'Registered Types and Taxes', 'cpt-plugin' ), __( 'Registered Types/Taxes', 'cpt-plugin' ), 'manage_options', 'cptui_listings', 'cptui_listings' );
	add_submenu_page( 'cptui_main_menu', __( 'Import/Export', 'cpt-plugin' ), __( 'Import/Export', 'cpt-plugin' ), 'manage_options', 'cptui_importexport', 'cptui_importexport' );
	add_submenu_page( 'cptui_main_menu', __( 'Help/Support', 'cpt-plugin' ), __( 'Help/Support', 'cpt-plugin' ), 'manage_options', 'cptui_support', 'cptui_support' );

	# Remove the default one so we can add our customized version.
	remove_submenu_page('cptui_main_menu', 'cptui_main_menu');
	add_submenu_page( 'cptui_main_menu', __( 'About CPT UI', 'cpt-plugin' ), __( 'About CPT UI', 'cpt-plugin' ), 'manage_options', 'cptui_main_menu', 'cptui_settings' );
}
add_action( 'admin_menu', 'cptui_plugin_menu' );

/**
 * Load our submenus.
 *
 * @since 1.0.0
 */
function cptui_create_submenus() {
	require_once( plugin_dir_path( __FILE__ ) . 'inc/post-types.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'inc/taxonomies.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'inc/listings.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'inc/import_export.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'inc/support.php' );
}
add_action( 'init', 'cptui_create_submenus' );

function cptui_add_styles() {
	wp_enqueue_style( 'cptui-css', plugins_url( 'css/cptui.css', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'cptui_add_styles' );

/**
 * Register our users' custom post types.
 *
 * @since 0.5.0
 */
function cptui_create_custom_post_types() {
	$cpts = get_option( 'cptui_post_types' );

	if ( is_array( $cpts ) ) {
		foreach ( $cpts as $post_type ) {
			cptui_register_single_post_type( $post_type );
		}
	}
	return;
}
add_action( 'init', 'cptui_create_custom_post_types', 10 );

/**
 * Helper function to register the actual post_type.
 *
 * @since 1.0.0
 *
 * @param array $post_type Post type array to register.
 *
 * @return null Result of register_post_type.
 */
function cptui_register_single_post_type( $post_type = array() ) {

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

	/**
	 * Filters custom supports parameters for 3rd party plugins.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $value     Empty array to add supports keys to.
	 * @param string $name      Post type slug being registered.
	 * @param array  $post_type Array of post type arguments to be registered.
	 */
	$user_supports_params = apply_filters( 'cptui_user_supports_params', array(), $post_type['name'], $post_type );

	if ( is_array( $user_supports_params ) ) {
		$post_type['supports'] = array_merge( $post_type['supports'], $user_supports_params );
	}

	if ( ! empty( $post_type['custom_supports'] ) ) {
		$custom = explode( ',', $post_type['custom_supports'] );
		foreach( $custom as $part ) {
			$post_type['supports'][] = $part;
		}
	}

	if ( in_array( 'none', $post_type['supports'] ) ) {
		$post_type['supports'] = false;
	}

	$labels = array(
		'name'               => $post_type['label'],
		'singular_name'      => $post_type['singular_label']
	);

	$preserved = cptui_get_preserved_keys( 'post_types' );
	foreach( $post_type['labels'] as $key => $label ) {

		if ( !empty( $label ) ) {
			$labels[ $key ] = $label;
		} elseif ( empty( $label ) && in_array( $key, $preserved ) ) {
			$labels[ $key ] = cptui_get_preserved_label( 'post_types', $key, $post_type['label'], $post_type['singular_label'] );
		}
	}

	$has_archive = get_disp_boolean( $post_type['has_archive'] );
	if ( !empty( $post_type['has_archive_string'] ) ) {
		$has_archive = $post_type['has_archive_string'];
	}

	$show_in_menu = get_disp_boolean( $post_type['show_in_menu'] );
	if ( !empty( $post_type['show_in_menu_string'] ) ) {
		$show_in_menu = $post_type['show_in_menu_string'];
	}

	$rewrite = get_disp_boolean( $post_type['rewrite' ] );
	if ( false !== $rewrite ) {
		//Core converts to an empty array anyway, so safe to leave this instead of passing in boolean true.
		$rewrite = array();
		$rewrite['slug'] = ( !empty( $post_type['rewrite_slug'] ) ) ? $post_type['rewrite_slug'] : $post_type['name'];
		$rewrite['with_front'] = ( 'false' === disp_boolean( $post_type['rewrite_withfront'] ) ) ? false : true;
	}

	$menu_icon = ( !empty( $post_type['menu_icon'] ) ) ? $post_type['menu_icon'] : null;

	if ( in_array( $post_type['query_var'], array( 'true', 'false', '0', '1' ) ) ) {
		$post_type['query_var'] = get_disp_boolean( $post_type['query_var'] );
	}

	$menu_position = null;
	if ( !empty( $post_type['menu_position'] ) ) {
		$menu_position = (int) $post_type['menu_position'];
	}

	if ( ! empty( $post_type['exclude_from_search'] ) ) {
		$exclude_from_search = get_disp_boolean( $post_type['exclude_from_search'] );
	} else {
		$public = get_disp_boolean( $post_type['public'] );
		$exclude_from_search = ( false === $public ) ? true : false;
	}

	$args = array(
		'labels'              => $labels,
		'description'         => $post_type['description'],
		'public'              => get_disp_boolean( $post_type['public'] ),
		'show_ui'             => get_disp_boolean( $post_type['show_ui'] ),
		'has_archive'         => $has_archive,
		'show_in_menu'        => $show_in_menu,
		'exclude_from_search' => $exclude_from_search,
		'capability_type'     => $post_type['capability_type'],
		'map_meta_cap'        => $post_type['map_meta_cap'],
		'hierarchical'        => get_disp_boolean( $post_type['hierarchical'] ),
		'rewrite'             => $rewrite,
		'menu_position'       => $menu_position,
		'menu_icon'           => $menu_icon,
		'query_var'           => $post_type['query_var'],
		'supports'            => $post_type['supports'],
		'taxonomies'          => $post_type['taxonomies']
	);

	/**
	 * Filters the arguments used for a post type right before registering.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args Array of arguments to use for registering post type.
	 * @param string $value Post type slug to be registered.
	 */
	$args = apply_filters( 'cptui_pre_register_post_type', $args, $post_type['name'] );

	return register_post_type( $post_type['name'], $args );
}

/**
 * Register our users' custom taxonomies.
 *
 * @since 0.5.0
 */
function cptui_create_custom_taxonomies() {
	$taxes = get_option('cptui_taxonomies');

	if ( is_array( $taxes ) ) {
		foreach ( $taxes as $tax ) {
			cptui_register_single_taxonomy( $tax );
		}
	}
}
add_action( 'init', 'cptui_create_custom_taxonomies', 9 );

/**
 * Helper function to register the actual taxonomy.
 *
 * @param array $taxonomy Taxonomy array to register.
 *
 * @return null Result of register_taxonomy.
 */
function cptui_register_single_taxonomy( $taxonomy = array() ) {

	$labels = array(
		'name'               => $taxonomy['label'],
		'singular_name'      => $taxonomy['singular_label']
	);

	$preserved = cptui_get_preserved_keys( 'taxonomies' );
	foreach( $taxonomy['labels'] as $key => $label ) {

		if ( !empty( $label ) ) {
			$labels[ $key ] = $label;
		} elseif ( empty( $label ) && in_array( $key, $preserved ) ) {
			$labels[ $key ] = cptui_get_preserved_label( 'taxonomies', $key, $taxonomy['label'], $taxonomy['singular_label'] );
		}
	}

	$rewrite = get_disp_boolean( $taxonomy['rewrite'] );
	if ( false !== get_disp_boolean( $taxonomy['rewrite'] ) ) {
		$rewrite = array();
		$rewrite['slug'] = ( !empty( $taxonomy['rewrite_slug'] ) ) ? $taxonomy['rewrite_slug'] : $taxonomy['name'];
		$rewrite['with_front'] = ( 'false' === disp_boolean( $taxonomy['rewrite_withfront'] ) ) ? false : true;
		$rewrite['hierarchical'] = ( 'true' === disp_boolean( $taxonomy['rewrite_hierarchical'] ) ) ? true : false;
	}

	if ( in_array( $taxonomy['query_var'], array( 'true', 'false', '0', '1' ) ) ) {
		$taxonomy['query_var'] = get_disp_boolean( $taxonomy['query_var'] );
	}
	if ( true === $taxonomy['query_var'] && !empty( $taxonomy['query_var_slug'] ) ) {
		$taxonomy['query_var'] = $taxonomy['query_var_slug'];
	}

	$show_admin_column = ( !empty( $taxonomy['show_admin_column'] ) && false !== get_disp_boolean( $taxonomy['show_admin_column'] ) ) ? true : false;

	$args = array(
		'labels'            => $labels,
		'label'             => $taxonomy['label'],
		'hierarchical'      => get_disp_boolean( $taxonomy['hierarchical'] ),
		'show_ui'           => get_disp_boolean( $taxonomy['show_ui'] ),
		'query_var'         => $taxonomy['query_var'],
		'rewrite'           => $rewrite,
		'show_admin_column' => $show_admin_column
	);

	$object_type = ( !empty( $taxonomy['object_types'] ) ) ? $taxonomy['object_types'] : '';

	/**
	 * Filters the arguments used for a taxonomy right before registering.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args Array of arguments to use for registering taxonomy.
	 * @param string $value Taxonomy slug to be registered.
	 */
	$args = apply_filters( 'cptui_pre_register_taxonomy', $args, $taxonomy['name'] );

	return register_taxonomy( $taxonomy['name'], $object_type, $args );
}

/**
 * Display our primary menu page.
 *
 * @since  0.3.0
 *
 * @return string $value HTML markup for the page.
 */
function cptui_settings() { ?>
	<div class="wrap about-wrap">
		<?php

		/**
		 * Fires inside and at the top of the wrapper for the main plugin landing page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'cptui_main_page_start' ); ?>
		<h1><?php _e( 'Custom Post Type UI', 'cpt-plugin' ); ?> <?php echo CPT_VERSION; ?></h1>

		<div class="about-text cptui-about-text">
			<?php _e( 'Thank you for choosing Custom Post Type UI. We hope that your experience with our plugin provides efficiency and speed in creating post types and taxonomies, to better organize your content, without having to touch code.', 'cpt-plugin' ); ?>
		</div>

		<div class="changelog about-integrations">
			<div class="cptui-feature feature-section col three-col">
				<div>
					<h4><?php _e( 'Post type migration', 'cpt-plugin' ); ?></h4>
					<p><?php _e( 'In the past, if you changed your post type slug, you would lose immediate access to the posts in the post type and need to recover another way. We have now added support for migrating all posts within the old post type to the new post type you renamed it to.', 'cpt-plugin' ); ?></p>
				</div>
				<div>
					<h4><?php _e( 'UI Refinement', 'cpt-plugin' ); ?></h4>
					<p><?php _e( 'After receiving feedback regarding the 1.0.x changes, we have further simplified the UI to reduce the amount of clicking necessary to manage your post types and taxonomies.', 'cpt-plugin' ); ?></p>
				</div>
				<div class="last-feature">
					<h4><?php _e( 'Registered Post Type and Taxonomy Listings', 'cpt-plugin' ); ?></h4>
					<p><?php _e( 'We are bringing back the listing of all CPTUI-registered post types and taxonomies for easier quick view of what you have going.', 'cpt-plugin' ); ?></p>
				</div>
			</div>
		</div>

		<h2><?php _e( 'Help Support This Plugin!', 'cpt-plugin' ); ?></h2>
		<table border="0">
			<tr>
				<td class="one-third valign">
					<h3><?php _e( 'Professional WordPress<br />Third Edition', 'cpt-plugin' ); ?></h3>
					<a href="http://bit.ly/prowp3" target="_blank">
						<img src="<?php echo plugins_url( '/images/professional-wordpress-thirdedition.jpg', __FILE__ ); ?>" width="200">
					</a>
					<br />
					<p><?php _e( 'The leading book on WordPress design and development! Brand new third edition!', 'cpt-plugin' ); ?></p>
				</td>
				<td class="one-third valign">
					<h3><?php _e( 'Professional WordPress<br />Plugin Development', 'cpt-plugin' ); ?></h3>
					<a href="http://amzn.to/plugindevbook" target="_blank">
						<img src="<?php echo plugins_url( '/images/professional-wordpress-plugin-development.png', __FILE__ ); ?>" width="200">
					</a>
					<br />
					<p><?php _e( 'Highest rated WordPress development book on Amazon!', 'cpt-plugin' ); ?></p>
				</td>
				<td class="one-third valign">
					<h3><?php _e( 'PayPal Donation', 'cpt-plugin' ); ?></h3>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="YJEDXPHE49Q3U">
					<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" name="submit" alt="<?php esc_attr_e( 'PayPal - The safer, easier way to pay online!', 'cpt-plugin' ); ?>">
					<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
					<p><?php _e( 'Please donate to the development of Custom Post Type UI:', 'cpt-plugin' ); ?></p>
				</td>
			</tr>
		</table>
	</div>
	<?php
}

/**
 * Display footer links and plugin credits.
 *
 * @since 0.3.0
 *
 * @param string $original Original footer content.
 *
 * @return string $value HTML for footer.
 */
function cptui_footer( $original = '' ) {

	$screen = get_current_screen();

	if ( ! is_object( $screen ) || 'cptui_main_menu' != $screen->parent_base ) {
		return $original;
	}

	return sprintf(
		__( '%s version %s by %s', 'cpt-plugin' ),
		sprintf(
			'<a target="_blank" href="http://wordpress.org/support/plugin/custom-post-type-ui">%s</a>',
			__( 'Custom Post Type UI', 'cpt-plugin' )
		),
		CPT_VERSION,
		'<a href="http://webdevstudios.com" target="_blank">WebDevStudios</a>'
	).
	' - '.
	sprintf(
		'<a href="https://github.com/WebDevStudios/custom-post-type-ui/issues" target="_blank">%s</a>',
		__( 'Please Report Bugs', 'cpt-plugin' )
	).
	' '.
	__( 'Follow on Twitter:', 'cpt-plugin' ).
	sprintf(
		' %s &middot; %s &middot; %s',
		'<a href="http://twitter.com/tw2113" target="_blank">Michael</a>',
		'<a href="http://twitter.com/williamsba" target="_blank">Brad</a>',
		'<a href="http://twitter.com/webdevstudios" target="_blank">WebDevStudios</a>'
	);
}
add_filter( 'admin_footer_text', 'cptui_footer' );

/**
 * Return boolean status depending on passed in value.
 *
 * @since 0.5.0
 *
 * @param mixed $booText text to compare to typical boolean values.
 *
 * @return bool Which bool value the passed in value was.
 */
function get_disp_boolean( $booText ) {
	$booText = (string) $booText;
	if ( empty( $booText ) || $booText == '0' || $booText == 'false' ) {
		return false;
	}

	return true;
}

/**
 * Return string versions of boolean values.
 *
 * @since 0.1.0
 *
 * @param string $booText String boolean value.
 *
 * @return string standardized boolean text.
 */
function disp_boolean( $booText ) {
	$booText = (string) $booText;
	if ( empty( $booText ) || $booText == '0' || $booText == 'false' ) {
		return 'false';
	}

	return 'true';
}

/**
 * Construct and output tab navigation.
 *
 * @since 1.0.0
 *
 * @param string $page Whether it's the CPT or Taxonomy page.
 *
 * @return string $value HTML tabs.
 */
function cptui_settings_tab_menu( $page = 'post_types' ) {

	# initiate our arrays with default classes
	$tab1 = $tab2 = $tab3 = array( 'nav-tab' );
	$has = false;

	if ( 'importexport' == $page ) :
		$title = __( 'Import/Export', 'cpt-plugin' );
	elseif ( 'taxonomies' == $page ) :
		$title = __( 'Manage Taxonomies', 'cpt-plugin' );
		$taxes = get_option( 'cptui_taxonomies' );
		$has = ( !empty( $taxes ) ) ? true : false;
	else :
		$title = __( 'Manage Post Types', 'cpt-plugin' );
		$types = get_option( 'cptui_post_types' );
		$has = ( !empty( $types ) ) ? true : false;
	endif;

	if ( !empty( $_GET['action'] ) ) {
		if ( 'edit' == $_GET['action'] || 'taxonomies' == $_GET['action'] ) {
			$tab2[] = 'nav-tab-active';
		} elseif ( 'get_code' == $_GET['action'] ) {
			$tab3[] = 'nav-tab-active';
		}
	}  else {
		$tab1[] = 'nav-tab-active';
	}

	# implode our arrays for class attributes
	$tab1 = implode( ' ', $tab1 ); $tab2 = implode( ' ', $tab2 ); $tab3 = implode( ' ', $tab3 );

	?>
	<h2 class="nav-tab-wrapper">
	<?php echo $title;

	# Import/Export area is getting different tabs, so we need to separate out.
	if ( 'importexport' != $page ) {
		if ( 'post_types' == $page ) {
			?>
			<a class="<?php echo $tab1; ?>" href="<?php echo admin_url( 'admin.php?page=cptui_manage_' . $page ); ?>"><?php _e( 'Add New Post Type', 'cpt-plugin' ); ?></a>
			<?php
			if ( $has ) { ?>
			<a class="<?php echo $tab2; ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit' ), admin_url( 'admin.php?page=cptui_manage_' . $page ) ) ); ?>"><?php _e( 'Edit Post Types', 'cpt-plugin' ); ?></a>
			<?php }
		} elseif ( 'taxonomies' == $page ) {
			?>
			<a class="<?php echo $tab1; ?>" href="<?php echo admin_url( 'admin.php?page=cptui_manage_' . $page ); ?>"><?php _e( 'Add New Taxonomy', 'cpt-plugin' ); ?></a>
			<?php
			if ( $has ) { ?>
			<a class="<?php echo $tab2; ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit' ), admin_url( 'admin.php?page=cptui_manage_' . $page ) ) ); ?>"><?php _e( 'Edit Taxonomies', 'cpt-plugin' ); ?></a>
			<?php }
		}
	} else { ?>
		<a class="<?php echo $tab1; ?>" href="<?php echo admin_url( 'admin.php?page=cptui_' . $page ); ?>"><?php _e( 'Post Types', 'cpt-plugin' ); ?></a>
		<a class="<?php echo $tab2; ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'taxonomies' ), admin_url( 'admin.php?page=cptui_' . $page ) ) ); ?>"><?php _e( 'Taxonomies', 'cpt-plugin' ); ?></a>
		<a class="<?php echo $tab3; ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'get_code' ), admin_url( 'admin.php?page=cptui_' . $page ) ) ); ?>"><?php _e( 'Get Code', 'cpt-plugin' ); ?></a>
	<?php
	}

	/**
	 * Fires inside and at end of the `<h2>` tag for settings tabs area.
	 *
	 * @since 1.0.0
	 */
	do_action( 'cptui_settings_tabs_after' );
	?>
	</h2>
<?php
}

/**
 * Convert our old settings to the new options keys.
 *
 * @since 1.0.0
 *
 * @return bool Whether or not options were successfully updated.
 */
function cptui_convert_settings() {

	$retval = '';

	if ( false === get_option( 'cptui_post_types' ) && ( $post_types = get_option( 'cpt_custom_post_types' ) ) ) {

		$new_post_types = array();
		foreach( $post_types as $type ) {
            $new_post_types[ $type['name'] ]                = $type; #This one assigns the # indexes       # Named arrays are our friend.
            $new_post_types[ $type['name'] ]['supports']    = ( !empty( $type[0] ) ) ? $type[0] : array(); # Especially
            $new_post_types[ $type['name'] ]['taxonomies']  = ( !empty( $type[1] ) ) ? $type[1] : array(); # for multidimensional
            $new_post_types[ $type['name'] ]['labels']      = ( !empty( $type[2] ) ) ? $type[2] : array(); # arrays
			unset(
				$new_post_types[ $type['name'] ][0],
				$new_post_types[ $type['name'] ][1],
				$new_post_types[ $type['name'] ][2]
			); # Remove our previous indexed versions.
		}

		$retval = update_option( 'cptui_post_types', $new_post_types );
	}

	if ( false === get_option( 'cptui_taxonomies' ) && ( $taxonomies = get_option( 'cpt_custom_tax_types' ) ) ) {

		$new_taxonomies = array();
		foreach( $taxonomies as $tax ) {
            $new_taxonomies[ $tax['name'] ]                 = $tax;    # Yep, still our friend.
            $new_taxonomies[ $tax['name'] ]['labels']       = $tax[0]; # Taxonomies are the only thing with
            $new_taxonomies[ $tax['name'] ]['object_types'] = $tax[1]; # "tax" in the name that I like.
			unset(
				$new_taxonomies[ $tax['name'] ][0],
				$new_taxonomies[ $tax['name'] ][1]
			);
		}

		$retval = update_option( 'cptui_taxonomies', $new_taxonomies );
	}

	if ( !empty( $retval ) ) {
		flush_rewrite_rules();
	}

	return $retval;
}
add_action( 'admin_init', 'cptui_convert_settings' );

/**
 * Edit links that appear on installed plugins list page, for our plugin.
 *
 * @since 1.0.0
 *
 * @param array $links Array of links to display below our plugin listing.
 *
 * @return array Amended array of links.
 */
function cptui_edit_plugin_list_links( $links ) {
	# We shouldn't encourage editing our plugin directly.
	unset( $links['edit'] );

	# Add our custom links to the returned array value.
	return array_merge( array(
		'<a href="' . admin_url( 'admin.php?page=cptui_main_menu' ) . '">' . __( 'Settings', 'cpt-plugin' ) . '</a>', '<a href="' . admin_url( 'admin.php?page=cptui_support' ) . '">' . __( 'Help', 'cpt-plugin' ) . '</a>'
	), $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'cptui_edit_plugin_list_links' );

/**
 * Return a notice based on conditions.
 *
 * @since 1.0.0
 *
 * @param string $action       The type of action that occurred.
 * @param string $object_type  Whether it's from a post type or taxonomy.
 * @param bool   $success      Whether the action succeeded or not.
 * @param string $custom       Custom message if necessary.
 *
 * @return bool|string false on no message, else HTML div with our notice message.
 */
function cptui_admin_notices( $action = '', $object_type = '', $success = true , $custom = '' ) {

	$class = ( $success ) ? 'updated' : 'error';
	$object_type = esc_attr( $object_type );

	$messagewrapstart = '<div id="message" class="' . $class . '"><p>';
	$message = '';

	$messagewrapend = '</p></div>';

	if ( 'add' == $action ) {
		if ( $success ) {
			$message .= sprintf( __( '%s has been successfully added', 'cpt-plugin' ), $object_type );
		} else {
			$message .= sprintf( __( '%s has failed to be added', 'cpt-plugin' ), $object_type );
		}
	} elseif ( 'update' == $action ) {
		if ( $success ) {
			$message .= sprintf( __( '%s has been successfully updated', 'cpt-plugin' ), $object_type );
		} else {
			$message .= sprintf( __( '%s has failed to be updated', 'cpt-plugin' ), $object_type );
		}
	} elseif ( 'delete' == $action ) {
		if ( $success ) {
			$message .= sprintf( __( '%s has been successfully deleted', 'cpt-plugin' ), $object_type );
		} else {
			$message .= sprintf( __( '%s has failed to be deleted', 'cpt-plugin' ), $object_type );
		}
	} elseif ( 'import' == $action ) {
		if ( $success ) {
			$message .= sprintf( __( '%s has been successfully imported', 'cpt-plugin' ), $object_type );
		} else {
			$message .= sprintf( __( '%s has failed to be imported', 'cpt-plugin' ), $object_type );
		}
	} elseif ( 'error' == $action ) {
		if ( !empty( $custom ) ) {
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
 * @param string $type Type to return. Either 'post_types' or 'taxonomies'.
 *
 * @return array Array of keys needing preservered for the requested type.
 */
function cptui_get_preserved_keys( $type = '' ) {

	$preserved_labels = array(
		'post_types' => array(
			'add_new_item',
			'edit_item',
			'new_item',
			'view_item',
			'all_items',
			'search_items',
			'not_found',
			'not_found_in_trash'
		),
		'taxonomies' => array(
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
			'choose_from_most_used'
		)
	);
	return ( !empty( $type ) ) ? $preserved_labels[ $type ] : array();
}

/**
 * Return label for the requested type and label key.
 *
 * @since 1.0.5
 *
 * @param string $type Type to return. Either 'post_types' or 'taxonomies'.
 * @param string $key Requested label key.
 * @param string $plural Plural verbiage for the requested label and type.
 * @param string $singular Singular verbiage for the requested label and type.
 *
 * @return string Internationalized default label.
 */
function cptui_get_preserved_label( $type = '', $key = '', $plural = '', $singular = '' ) {

	$preserved_labels = array(
		'post_types' => array(
			'add_new_item'       => sprintf( __( 'Add new %s', 'cpt-plugin' ), $singular ),
			'edit_item'          => sprintf( __( 'Edit %s', 'cpt-plugin' ), $singular ),
			'new_item'           => sprintf( __( 'New %s', 'cpt-plugin' ), $singular ),
			'view_item'          => sprintf( __( 'View %s', 'cpt-plugin' ), $singular ),
			'all_items'          => sprintf( __( 'All %s', 'cpt-plugin' ), $plural ),
			'search_items'       => sprintf( __( 'Search %s', 'cpt-plugin' ), $plural ),
			'not_found'          => sprintf( __( 'No %s found.', 'cpt-plugin' ), $plural ),
			'not_found_in_trash' => sprintf( __( 'No %s found in trash.', 'cpt-plugin' ), $plural )
		),
		'taxonomies' => array(
			'search_items'               => sprintf( __( 'Search %s', 'cpt-plugin' ), $plural ),
			'popular_items'              => sprintf( __( 'Popular %s', 'cpt-plugin' ), $plural ),
			'all_items'                  => sprintf( __( 'All %s', 'cpt-plugin' ), $plural ),
			'parent_item'                => sprintf( __( 'Parent %s', 'cpt-plugin' ), $singular ),
			'parent_item_colon'          => sprintf( __( 'Parent %s:', 'cpt-plugin' ), $singular ),
			'edit_item'                  => sprintf( __( 'Edit %s', 'cpt-plugin' ), $singular ),
			'update_item'                => sprintf( __( 'Update %s', 'cpt-plugin' ), $singular ),
			'add_new_item'               => sprintf( __( 'Add new %s', 'cpt-plugin' ), $singular ),
			'new_item_name'              => sprintf( __( 'New %s name', 'cpt-plugin' ), $singular ),
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'cpt-plugin' ), $plural ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'cpt-plugin' ), $plural ),
			'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'cpt-plugin' ), $plural )
		)
	);

	return $preserved_labels[ $type ][ $key ];
}
