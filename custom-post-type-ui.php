<?php
/*
Plugin Name: Custom Post Type UI
Plugin URI: https://github.com/WebDevStudios/custom-post-type-ui/
Description: Admin panel for creating custom post types and custom taxonomies in WordPress
Author: WebDevStudios.com
Version: 0.9
Author URI: http://webdevstudios.com/
Text Domain: cpt-plugin
License: GPLv2
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CPT_VERSION', '0.9' ); // Define current version constant
define( 'CPTUI_WP_VERSION', get_bloginfo( 'version' ) ); // Define current WordPress version constant

/**
 * Load our OOP class that powers our form inputs
 *
 * @since  0.9
 *
 * @return void
 */
function cptui_load_ui_class() {
	//include our Admin UI class to help make things fabulous, and streamlined.
	require_once( plugin_dir_path( __FILE__ ) . 'classes/class.cptui_admin_ui.php' );
}
add_action( 'init', 'cptui_load_ui_class' );

/**
 * Flush our rewrite rules on deactivation
 *
 * @since  0.8
 *
 * @return void
 */
function cptui_deactivation() {
	// Clear the permalinks to remove our post type's rules
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'cptui_deactivation' );

/**
 * Register our text domain
 *
 * @since  0.8
 *
 * @return void
 */
function cptui_load_textdomain() {
	load_plugin_textdomain( 'cpt-plugin', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'cptui_load_textdomain' );

/**
 * Load our main menu
 *
 * @since  0.1
 *
 * @return void
 */
function cptui_plugin_menu() {
	add_menu_page( __( 'Custom Post Types', 'cpt-plugin' ), __( 'CPT UI (dev)', 'cpt-plugin' ), 'manage_options', 'cptui_main_menu', 'cptui_settings' );
}
add_action( 'admin_menu', 'cptui_plugin_menu' );

/**
 * Load our submenus
 *
 * @since  0.9
 *
 * @return void
 */
function cptui_create_submenus() {
	require_once( plugin_dir_path( __FILE__ ) . 'inc/post-types.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'inc/taxonomies.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'inc/import_export.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'inc/support.php' );
}
add_action( 'init', 'cptui_create_submenus' );

/**
 * Register our users' custom post types
 *
 * @since  0.5
 *
 * @return void
 */
function cptui_create_custom_post_types() {
	//register custom post types
	$cpts = get_option( 'cptui_post_types' );

	//check if option value is an array before proceeding
	if ( is_array( $cpts ) ) {
		foreach ( $cpts as $type ) {
			//set post type values
			$cpt_label              = ( !empty( $type["label"] ) ) ? esc_html( $type["label"] ) : esc_html( $type["name"] ) ;
			$cpt_singular           = ( !empty( $type["singular_label"] ) ) ? esc_html( $type["singular_label"] ) : esc_html( $cpt_label );
			$cpt_rewrite_slug       = ( !empty( $type["rewrite_slug"] ) ) ? esc_html( $type["rewrite_slug"] ) : esc_html( $type["name"] );
			$cpt_rewrite_withfront  = ( !empty( $type["rewrite_withfront"] ) ) ? true : get_disp_boolean( $type["rewrite_withfront"] ); //reversed because false is empty
			$cpt_menu_position      = ( !empty( $type["menu_position"] ) ) ? intval( $type["menu_position"] ) : null; //must be null
			$cpt_menu_icon          = ( !empty( $type["menu_icon"] ) ) ? esc_url( $type["menu_icon"] ) : null; //must be null
			$cpt_taxonomies         = ( !empty( $type[1] ) ) ? $type[1] : array();
			$cpt_supports           = ( !empty( $type[0] ) ) ? $type[0] : array();

			//Show UI must be true
			if ( true == get_disp_boolean( $type["show_ui"] ) ) {
				//If the string is empty, we will need boolean, else use the string.
				if ( empty( $type['show_in_menu_string'] ) ) {
					$cpt_show_in_menu = ( $type["show_in_menu"] == 1 ) ? true : false;
				} else {
					$cpt_show_in_menu = $type['show_in_menu_string'];
				}
			} else {
				$cpt_show_in_menu = false;
			}

			//set custom label values
			$cpt_labels['name']             = $cpt_label;
			$cpt_labels['singular_name']    = $type["singular_label"];

			if ( isset ( $type[2]["menu_name"] ) ) {
				$cpt_labels['menu_name'] = ( !empty( $type[2]["menu_name"] ) ) ? $type[2]["menu_name"] : $cpt_label;
			}

			$cpt_has_archive                    = ( !empty( $type["has_archive"] ) ) ? get_disp_boolean( $type["has_archive"] ) : '';
			$cpt_exclude_from_search            = ( !empty( $type["exclude_from_search"] ) ) ? get_disp_boolean( $type["exclude_from_search"] ) : '';
			$cpt_labels['add_new']              = ( !empty( $type[2]["add_new"] ) ) ? $type[2]["add_new"] : 'Add ' .$cpt_singular;
			$cpt_labels['add_new_item']         = ( !empty( $type[2]["add_new_item"] ) ) ? $type[2]["add_new_item"] : 'Add New ' .$cpt_singular;
			$cpt_labels['edit']                 = ( !empty( $type[2]["edit"] ) ) ? $type[2]["edit"] : 'Edit';
			$cpt_labels['edit_item']            = ( !empty( $type[2]["edit_item"] ) ) ? $type[2]["edit_item"] : 'Edit ' .$cpt_singular;
			$cpt_labels['new_item']             = ( !empty( $type[2]["new_item"] ) ) ? $type[2]["new_item"] : 'New ' .$cpt_singular;
			$cpt_labels['view']                 = ( !empty( $type[2]["view"] ) ) ? $type[2]["view"] : 'View ' .$cpt_singular;
			$cpt_labels['view_item']            = ( !empty( $type[2]["view_item"] ) ) ? $type[2]["view_item"] : 'View ' .$cpt_singular;
			$cpt_labels['search_items']         = ( !empty( $type[2]["search_items"] ) ) ? $type[2]["search_items"] : 'Search ' .$cpt_label;
			$cpt_labels['not_found']            = ( !empty( $type[2]["not_found"] ) ) ? $type[2]["not_found"] : 'No ' .$cpt_label. ' Found';
			$cpt_labels['not_found_in_trash']   = ( !empty( $type[2]["not_found_in_trash"] ) ) ? $type[2]["not_found_in_trash"] : 'No ' .$cpt_label. ' Found in Trash';
			$cpt_labels['parent']               = ( $type[2]["parent"] ) ? $type[2]["parent"] : 'Parent ' .$cpt_singular;

			$cpt_pre_register_post_type_args = array(
				'label' => $cpt_label,
				'public' => get_disp_boolean($type["public"]),
				'singular_label' => $type["singular_label"],
				'show_ui' => get_disp_boolean($type["show_ui"]),
				'has_archive' => $cpt_has_archive,
				'show_in_menu' => $cpt_show_in_menu,
				'capability_type' => $type["capability_type"],
				'map_meta_cap' => true,
				'hierarchical' => get_disp_boolean($type["hierarchical"]),
				'exclude_from_search' => $cpt_exclude_from_search,
				'rewrite' => array( 'slug' => $cpt_rewrite_slug, 'with_front' => $cpt_rewrite_withfront ),
				'query_var' => get_disp_boolean($type["query_var"]),
				'description' => esc_html($type["description"]),
				'menu_position' => $cpt_menu_position,
				'menu_icon' => $cpt_menu_icon,
				'supports' => $cpt_supports,
				'taxonomies' => $cpt_taxonomies,
				'labels' => $cpt_labels
			);

			//pass all of our arguments as well as the future post type name through a filter.
			$cpt_register_post_type_args = apply_filters( 'cptui_register_post_type_args', $cpt_pre_register_post_type_args, $type["name"] );
			/*if ( !is_array( $cpt_register_post_type_args ) )
				wp_die( 'Please return an array to the \'cptui_register_post_type_args\' filter.' );*/
			//finally register the post type.
			register_post_type( $type["name"], $cpt_register_post_type_args );
		}
	}
}
add_action( 'init', 'cptui_create_custom_post_types' );

/**
 * Register our users' custom taxonomies
 *
 * @since  0.5
 *
 * @return void
 */
function cptui_create_custom_taxonomies() {
	//register custom taxonomies
	$taxes = get_option('cptui_taxonomies');

	//check if option value is an array before proceeding
	if ( is_array( $taxes ) ) {
		foreach ($taxes as $tax) {

			//set custom taxonomy values
			$cpt_label              = ( !empty( $tax["label"] ) ) ? esc_html( $tax["label"] ) : esc_html( $tax["name"] );
			$cpt_singular_label     = ( !empty( $tax["singular_label"] ) ) ? esc_html( $tax["singular_label"] ) : esc_html( $tax["name"] );
			$cpt_rewrite_slug       = ( !empty( $tax["rewrite_slug"] ) ) ? esc_html( $tax["rewrite_slug"] ) : esc_html( $tax["name"] );
			$cpt_tax_show_admin_column = ( !empty( $tax["show_admin_column"] ) ) ? esc_html( $tax["show_admin_column"] ) : false;
			$cpt_post_types         = ( !empty( $tax[1] ) ) ? $tax[1] : $tax["cpt_name"];

			//set custom label values
			$cpt_labels['name']                         = $cpt_label;
			$cpt_labels['singular_name']                = $tax["singular_label"];
			$cpt_labels['search_items']                 = ( $tax[0]["search_items"] ) ? $tax[0]["search_items"] : 'Search ' .$cpt_label;
			$cpt_labels['popular_items']                = ( $tax[0]["popular_items"] ) ? $tax[0]["popular_items"] : 'Popular ' .$cpt_label;
			$cpt_labels['all_items']                    = ( $tax[0]["all_items"] ) ? $tax[0]["all_items"] : 'All ' .$cpt_label;
			$cpt_labels['parent_item']                  = ( $tax[0]["parent_item"] ) ? $tax[0]["parent_item"] : 'Parent ' .$cpt_singular_label;
			$cpt_labels['parent_item_colon']            = ( $tax[0]["parent_item_colon"] ) ? $tax[0]["parent_item_colon"] : 'Parent ' .$cpt_singular_label. ':';
			$cpt_labels['edit_item']                    = ( $tax[0]["edit_item"] ) ? $tax[0]["edit_item"] : 'Edit ' .$cpt_singular_label;
			$cpt_labels['update_item']                  = ( $tax[0]["update_item"] ) ? $tax[0]["update_item"] : 'Update ' .$cpt_singular_label;
			$cpt_labels['add_new_item']                 = ( $tax[0]["add_new_item"] ) ? $tax[0]["add_new_item"] : 'Add New ' .$cpt_singular_label;
			$cpt_labels['new_item_name']                = ( $tax[0]["new_item_name"] ) ? $tax[0]["new_item_name"] : 'New ' .$cpt_singular_label. ' Name';
			$cpt_labels['separate_items_with_commas']   = ( $tax[0]["separate_items_with_commas"] ) ? $tax[0]["separate_items_with_commas"] : 'Separate ' .$cpt_label. ' with commas';
			$cpt_labels['add_or_remove_items']          = ( $tax[0]["add_or_remove_items"] ) ? $tax[0]["add_or_remove_items"] : 'Add or remove ' .$cpt_label;
			$cpt_labels['choose_from_most_used']        = ( $tax[0]["choose_from_most_used"] ) ? $tax[0]["choose_from_most_used"] : 'Choose from the most used ' .$cpt_label;

			$cpt_pre_register_taxonomy_args = array(
				'hierarchical' => get_disp_boolean($tax["hierarchical"]),
				'label' => $cpt_label,
				'show_ui' => get_disp_boolean($tax["show_ui"]),
				'query_var' => get_disp_boolean($tax["query_var"]),
				'rewrite' => array('slug' => $cpt_rewrite_slug),
				'singular_label' => $cpt_singular_label,
				'labels' => $cpt_labels,
				'show_admin_column' => $cpt_tax_show_admin_column
			);

			//pass all of our arguments as well as the future taxonomy name and assigned post types through a filter.
			$cpt_register_taxonomy_args = apply_filters( 'cptui_register_taxonomy_args', $cpt_pre_register_taxonomy_args, $tax["name"], $cpt_post_types );
			/*if ( !is_array( $cpt_register_taxonomy_args ) )
				wp_die( 'Please return an array to the \'cptui_register_taxonomy_args\' filter.' );*/
			//register our custom taxonomies
			register_taxonomy( $tax["name"], $cpt_post_types, $cpt_register_taxonomy_args );
		}
	}
}
add_action( 'init', 'cptui_create_custom_taxonomies' );

/**
 * Display our primary menu page
 *
 * @since  0.3
 *
 * @return mixed  htmls
 */
function cptui_settings() { ?>
	<div class="wrap">
		<?php do_action( 'cptui_main_page_start' ); ?>
		<h2><?php _e( 'Custom Post Type UI', 'cpt-plugin' ); ?> <?php echo CPT_VERSION; ?></h2>

		<div class="wdsintro alignleft">
			<p><?php _e( 'Thank you for choosing to create with Custom Post Type UI. We hope that your experience with our plugin provides you efficiency and speed in creating post types and taxonomies, to better organize your content, without having to mess around with code.', 'cpt-plugin' ); ?></p>

			<p><?php echo sprintf( __( 'To get started with creating some post types, please visit %s and for taxonomies, visit %s. If you need some help, check the %s page. If nothing there fits your issue, visit our %s and we will try to get to your question as soon as possible.', 'cpt-plugin' ),
					sprintf( '<a href="' . admin_url( 'admin.php?page=cptui_manage_post_types' ) . '">%s</a>', __( 'Add/Edit Post Types', 'cpt-plugin' ) ),
					sprintf( '<a href="' . admin_url( 'admin.php?page=cptui_manage_taxonomies' ) . '">%s</a>', __( 'Add/Edit Taxonomies', 'cpt-plugin' ) ),
					sprintf( '<a href="' . admin_url( 'admin.php?page=cptui_support' ) . '">%s</a>', __( 'Help/Support', 'cpt-plugin' ) ),
					sprintf( '<a href="http://wordpress.org/support/plugin/custom-post-type-ui">%s</a>', __( 'CPT UI Support Forum', 'cpt-plugin' ) )
				);
			?>
			</p>
		</div>

		<div class="wdsrss alignright">
		<?php do_action( 'cptui_main_page_before_rss' ); ?>
		<h3><?php _e( 'WebDevStudios News', 'cpt-plugin' ); ?></h3>
		<?php

		wp_widget_rss_output( esc_url( 'http://webdevstudios.com/feed/' ), array(
			'items' => 3,
			'show_summary' => 0,
			'show_author' => 0,
			'show_date' => 1
		) );

		do_action( 'cptui_main_page_after_rss' ); ?>
		</div>

		<?php do_action( 'cptui_main_page_before_books' ); ?>
		<table border="0">
			<tr>
			<td colspan="3"><h3><?php _e( 'Help Support This Plugin!', 'cpt-plugin' ); ?></h3></td>
			</tr>
			<tr>
				<td width="33%"><h3><?php _e( 'PayPal Donation', 'cpt-plugin' ); ?></h3></td>
				<td width="33%"><h3><?php _e( 'Professional WordPress<br />Second Edition', 'cpt-plugin' ); ?></h3></td>
				<td width="33%"><h3><?php _e( 'Professional WordPress<br />Plugin Development', 'cpt-plugin' ); ?></h3></td>
			</tr>
			<tr>
				<td valign="top" width="33%">
					<p><?php _e( 'Please donate to the development of Custom Post Type UI:', 'cpt-plugin'); ?>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="YJEDXPHE49Q3U">
					<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="<?php esc_attr_e( 'PayPal - The safer, easier way to pay online!', 'cpt-plugin' ); ?>">
					<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
					</p>
				</td>
				<td valign="top" width="33%">
					<a href="http://bit.ly/prowp2" target="_blank">
						<img src="<?php echo plugins_url( '/images/professional-wordpress-secondedition.jpg', __FILE__ ); ?>" width="200">
					</a>
					<br />
					<?php _e( 'The leading book on WordPress design and development! Brand new second edition!', 'cpt-plugin'); ?>
				</td>
				<td valign="top" width="33%">
					<a href="http://amzn.to/plugindevbook" target="_blank">
						<img src="<?php echo plugins_url( '/images/professional-wordpress-plugin-development.png', __FILE__ ); ?>" width="200">
					</a>
					<br />
					<?php _e( 'Highest rated WordPress development book on Amazon!', 'cpt-plugin' ); ?>
				</td>
			</tr>
		</table>

		<?php do_action( 'cptui_main_page_after_books' ); ?>

	</div>
	<?php
	cptui_footer();
}

/**
 * Display footer links and plugin credits
 *
 * @since  0.3
 *
 * @return mixed  htmls
 */
function cptui_footer() { ?>
	<hr />
	<p class="cp_about">
		<?php
			echo sprintf(
				__( '%s version %s by %s - %s %s &middot; %s &middot; %s &middot; %s', 'cpt-plugin' ),
				sprintf(
					'<a target="_blank" href="http://wordpress.org/support/plugin/custom-post-type-ui">%s</a>',
					__( 'Custom Post Type UI', 'cpt-plugin' )
				),
				CPT_VERSION,
				'<a href="http://webdevstudios.com" target="_blank">WebDevStudios</a>',
				sprintf(
					'<a href="https://github.com/WebDevStudios/custom-post-type-ui" target="_blank">%s</a>',
					__( 'Please Report Bugs', 'cpt-plugin' )
				),
				__( 'Follow on Twitter:', 'cpt-plugin' ),
				'<a href="http://twitter.com/williamsba" target="_blank">Brad</a>',
				'<a href="http://twitter.com/tw2113" target="_blank">Michael</a>',
				'<a href="http://twitter.com/webdevstudios" target="_blank">WebDevStudios</a>'
			);
		?>
	 </p>
<?php
}

/**
 * Return boolean status depending on passed in value
 *
 * @since  0.5
 *
 * @param  mixed  $booText text to compare to typical boolean values
 *
 * @return bool           which bool value the passed in value was.
 */
function get_disp_boolean($booText) {
	$booText = (string) $booText;
	if ( empty( $booText ) || $booText == '0' || $booText == 'false' ) {
		return false;
	}

	return true;
}

/**
 * Return string versions of boolean values.
 *
 * @since  0.1
 *
 * @param  string  $booText string boolean value
 *
 * @return string           standardized boolean text
 */
function disp_boolean($booText) {
	$booText = (string) $booText;
	if ( empty( $booText ) || $booText == '0' || $booText == 'false' ) {
		return 'false';
	}

	return 'true';
}

/**
 * Add some styles to help with our fields
 *
 * @since  0.8
 *
 * @return mixed  html style blocks
 */
function cptui_help_style() { ?>
	<style>
		.help {
			border-radius: 50%;
			display: inline-block;
			height: 15px;
			margin-left: 2px;
			text-align: center;
			width: 15px;
		}
		.help:hover { font-weight: bold; }
		.required { color: rgb(255,0,0); }
		.cptui-table #excerpt { height: 16px; margin-right: 4px; width: auto; }
		.cptui-table td { vertical-align: top; width: 50%; }
		.wdsintro { width: 60%; }
		.wdsrss { width: 33%; }
		#cptui_select_post_type, #cptui_select_taxonomy { margin-top: 15px; }
		.cptui_post_import, .cptui_tax_import {
			height: 200px;
			margin-bottom: 10px;
			resize: vertical;
			width: 100%;
		}
		.cptui_post_type_get_code, .cptui_tax_get_code {
			height: 300px;
			width: 100%;
		}
		#cptui_accordion h3:hover { cursor: pointer; }
	</style>
<?php
}
add_action( 'admin_head', 'cptui_help_style' );

/**
 * Construct and output tab navigation
 *
 * @since  0.9
 *
 * @param  string  $page Whether it's the CPT or Taxonomy page
 *
 * @return mixed        html tabs
 */
function cptui_settings_tab_menu( $page = 'post_types' ) {
	//initiate our arrays with default classes
	$tab1 = $tab2 = $tab3 = array( 'nav-tab' );

	if ( 'importexport' == $page ) :
		$title = __( 'Import/Export', 'cpt-plugin' );
	elseif ( 'taxonomies' == $page ) :
		$title = __( 'Manage Taxonomies', 'cpt-plugin' );
	else :
		$title = __( 'Manage Post Types', 'cpt-plugin' );
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

	//implode our arrays for class attributes
	$tab1 = implode( ' ', $tab1 ); $tab2 = implode( ' ', $tab2 ); $tab3 = implode( ' ', $tab3 );

	?>
	<h2 class="nav-tab-wrapper">
	<?php echo $title;

	//Import/Export area is getting different tabs, so we need to separate out.
	if ( 'importexport' != $page ) { ?>
		<a class="<?php echo $tab1; ?>" href="<?php echo admin_url( 'admin.php?page=cptui_manage_' . $page ); ?>"><?php _e( 'Add New', 'cpt-plugin' ); ?></a>
		<?php
		if ( 'post_types' == $page ) { ?>
			<a class="<?php echo $tab2; ?>" href="<?php echo add_query_arg( array( 'action' => 'edit' ), admin_url( 'admin.php?page=cptui_manage_' . $page ) ); ?>"><?php _e( 'Edit Post Types', 'cpt-plugin' ); ?></a>
		<?php
		} elseif ( 'taxonomies' == $page ) { ?>
			<a class="<?php echo $tab2; ?>" href="<?php echo add_query_arg( array( 'action' => 'edit' ), admin_url( 'admin.php?page=cptui_manage_' . $page ) ); ?>"><?php _e( 'Edit Taxonomies', 'cpt-plugin' ); ?></a>
		<?php
		}
	} else { ?>
		<a class="<?php echo $tab1; ?>" href="<?php echo admin_url( 'admin.php?page=cptui_' . $page ); ?>"><?php _e( 'Post Types', 'cpt-plugin' ); ?></a>
		<a class="<?php echo $tab2; ?>" href="<?php echo add_query_arg( array( 'action' => 'taxonomies' ), admin_url( 'admin.php?page=cptui_' . $page ) ); ?>"><?php _e( 'Taxonomies', 'cpt-plugin' ); ?></a>
		<a class="<?php echo $tab3; ?>" href="<?php echo add_query_arg( array( 'action' => 'get_code' ), admin_url( 'admin.php?page=cptui_' . $page ) ); ?>"><?php _e( 'Get Code', 'cpt-plugin' ); ?></a>
	<?php
	} ?>
	</h2>
<?php
}

/**
 * Convert our old settings to the new options keys.
 *
 * @since  0.9
 *
 * @return bool  Whether or not options were successfully updated
 */
function cptui_convert_settings() {

	//We only want to run this if we don't have our new options.
	if ( false === get_option( 'cptui_post_types' ) && ( $post_types = get_option( 'cpt_custom_post_types' ) ) ) {

		//create a new array for us to store our options in.
		$new_post_types = array();
		foreach( $post_types as $type ) {
            $new_post_types[ $type['name'] ]                = $type;    //Named arrays are our friend.
            $new_post_types[ $type['name'] ]['supports']    = $type[0]; //Especially
            $new_post_types[ $type['name'] ]['taxonomies']  = $type[1]; //for multidimensional
            $new_post_types[ $type['name'] ]['labels']      = $type[2]; //arrays
			unset(
				$new_post_types[ $type['name'] ][0],
				$new_post_types[ $type['name'] ][1],
				$new_post_types[ $type['name'] ][2]
			); //Remove our previous indexed versions.
		}
		//Finally provide our new options.
		return update_option( 'cptui_post_types', $new_post_types );
	}

	//We only want to run this if we don't have our new options.
	if ( false === get_option( 'cptui_taxonomies' ) && ( $taxonomies = get_option( 'cpt_custom_tax_types' ) ) ) {

		//create a new array for us to store our options in.
		$new_taxonomies = array();
		foreach( $taxonomies as $tax ) {
            $new_taxonomies[ $tax['name'] ]                 = $tax;    //Yep, still our friend.
            $new_taxonomies[ $tax['name'] ]['labels']       = $tax[0]; // Taxonomies are the only thing with
            $new_taxonomies[ $tax['name'] ]['post_types']   = $tax[1]; // "tax" in the name that I like.
			unset(
				$new_taxonomies[ $tax['name'] ][0],
				$new_taxonomies[ $tax['name'] ][1]
			); //Remove our previous indexed versions.
		}
		//Finally provide our new options.
		return update_option( 'cptui_taxonomies', $new_taxonomies );
	}
}
add_action( 'admin_init', 'cptui_convert_settings' );

/**
 * Edit links that appear on installed plugins list page, for our plugin.
 *
 * @since  0.9
 *
 * @param  array  $links Array of links to display below our plugin listing.
 *
 * @return array         Amended array of links.
 */
function cptui_edit_plugin_list_links( $links ) {
	//We shouldn't encourage editing directly.
	unset( $links['edit'] );

	//Add our custom links to the returned array value.
	return array_merge( array(
		'<a href="' . admin_url( 'admin.php?page=cptui_main_menu' ) . '">' . __( 'Settings' ) . '</a>', '<a href="' . admin_url( 'admin.php?page=cptui_support' ) . '">' . __( 'Help' ) . '</a>'
	), $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'cptui_edit_plugin_list_links' );
