<?php
/*
Plugin Name: Custom Post Type UI
Plugin URI: http://webdevstudios.com/plugin/custom-post-type-ui/
Description: Admin panel for creating custom post types and custom taxonomies in WordPress
Author: WebDevStudios.com
Version: 0.8.2
Author URI: http://webdevstudios.com/
Text Domain: cpt-plugin
License: GPLv2
*/

// Define current version constant
define( 'CPT_VERSION', '0.8.2' );

// Define current WordPress version constant
define( 'CPTUI_WP_VERSION', get_bloginfo( 'version' ) );

// Define plugin URL constant
$CPT_URL = cpt_check_return( 'add' );

//include our Admin UI class to help make things fabulous, and streamlined.
require_once( plugin_dir_path( __FILE__ ) . 'classes/class.cptui_admin_ui.php' );

//load translated strings
add_action( 'init', 'cpt_load_textdomain' );

// create custom plugin settings menu
add_action( 'admin_menu', 'cpt_plugin_menu' );

//call delete post function
add_action( 'admin_init', 'cpt_delete_post_type' );

//call register settings function
add_action( 'admin_init', 'cpt_register_settings' );

//process custom taxonomies if they exist
add_action( 'init', 'cpt_create_custom_taxonomies', 0 );

//process custom taxonomies if they exist
add_action( 'init', 'cpt_create_custom_post_types', 0 );

add_action( 'admin_head', 'cpt_help_style' );

//flush rewrite rules on deactivation
register_deactivation_hook( __FILE__, 'cpt_deactivation' );

function cpt_deactivation() {
	// Clear the permalinks to remove our post type's rules
	flush_rewrite_rules();
}

function cpt_load_textdomain() {
	load_plugin_textdomain( 'cpt-plugin', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

function cpt_plugin_menu() {
	//create custom post type menu
	add_menu_page( __( 'Custom Post Types', 'cpt-plugin' ), __( 'CPT UI (dev)', 'cpt-plugin' ), 'manage_options', 'cpt_main_menu', 'cpt_settings' );

	//create submenu items
	add_submenu_page( 'cpt_main_menu', __( 'Add New', 'cpt-plugin' ), __( 'Add New', 'cpt-plugin' ), 'manage_options', 'cpt_sub_add_new', 'cpt_add_new' );
	add_submenu_page( 'cpt_main_menu', __( 'Manage Post Types', 'cpt-plugin' ), __( 'Manage Post Types', 'cpt-plugin' ), 'manage_options', 'cpt_sub_manage_cpt', 'cpt_manage_cpt' );
	add_submenu_page( 'cpt_main_menu', __( 'Manage Taxonomies', 'cpt-plugin' ), __( 'Manage Taxonomies', 'cpt-plugin' ), 'manage_options', 'cpt_sub_manage_taxonomies', 'cpt_manage_taxonomies' );


}
function cpt_create_submenus() {
	require_once( plugin_dir_path( __FILE__ ) . 'inc/post-types.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'inc/taxonomies.php' );
}
add_action( 'init', 'cpt_create_submenus' );

//temp fix, should do: http://planetozh.com/blog/2008/04/how-to-load-javascript-with-your-wordpress-plugin/
//only load JS if on a CPT page
if ( strpos( $_SERVER['REQUEST_URI'], 'cpt' ) > 0 ) {
	add_action( 'admin_head', 'cpt_wp_add_styles' );
}

// Add JS Scripts
function cpt_wp_add_styles() {

	wp_enqueue_script( 'jquery' ); ?>

		<script type="text/javascript" >
			jQuery(document).ready(function($) {
				$(".comment_button").on('click', function(e) {
					var element = $(this), I = element.attr("id");
					$("#slidepanel"+I).slideToggle(300);
					$(this).toggleClass("active");

					e.preventDefault();
				});
			});
		</script>
<?php
}

function cpt_create_custom_post_types() {
	//register custom post types
	$cpt_post_types = get_option('cpt_custom_post_types');

	//check if option value is an Array before proceeding
	if ( is_array( $cpt_post_types ) ) {
		foreach ($cpt_post_types as $cpt_post_type) {
			//set post type values
			$cpt_label              = ( !empty( $cpt_post_type["label"] ) ) ? esc_html( $cpt_post_type["label"] ) : esc_html( $cpt_post_type["name"] ) ;
			$cpt_singular           = ( !empty( $cpt_post_type["singular_label"] ) ) ? esc_html( $cpt_post_type["singular_label"] ) : esc_html( $cpt_label );
			$cpt_rewrite_slug       = ( !empty( $cpt_post_type["rewrite_slug"] ) ) ? esc_html( $cpt_post_type["rewrite_slug"] ) : esc_html( $cpt_post_type["name"] );
			$cpt_rewrite_withfront  = ( !empty( $cpt_post_type["rewrite_withfront"] ) ) ? true : get_disp_boolean( $cpt_post_type["rewrite_withfront"] ); //reversed because false is empty
			$cpt_menu_position      = ( !empty( $cpt_post_type["menu_position"] ) ) ? intval( $cpt_post_type["menu_position"] ) : null; //must be null
			$cpt_menu_icon          = ( !empty( $cpt_post_type["menu_icon"] ) ) ? esc_url( $cpt_post_type["menu_icon"] ) : null; //must be null
			$cpt_taxonomies         = ( !empty( $cpt_post_type[1] ) ) ? $cpt_post_type[1] : array();
			$cpt_supports           = ( !empty( $cpt_post_type[0] ) ) ? $cpt_post_type[0] : array();

			//Show UI must be true
			if ( true == get_disp_boolean( $cpt_post_type["show_ui"] ) ) {
				//If the string is empty, we will need boolean, else use the string.
				if ( empty( $cpt_post_type['show_in_menu_string'] ) ) {
					$cpt_show_in_menu = ( $cpt_post_type["show_in_menu"] == 1 ) ? true : false;
				} else {
					$cpt_show_in_menu = $cpt_post_type['show_in_menu_string'];
				}
			} else {
				$cpt_show_in_menu = false;
			}

			//set custom label values
			$cpt_labels['name']             = $cpt_label;
			$cpt_labels['singular_name']    = $cpt_post_type["singular_label"];

			if ( isset ( $cpt_post_type[2]["menu_name"] ) ) {
				$cpt_labels['menu_name'] = ( !empty( $cpt_post_type[2]["menu_name"] ) ) ? $cpt_post_type[2]["menu_name"] : $cpt_label;
			}

			$cpt_has_archive                    = ( !empty( $cpt_post_type["has_archive"] ) ) ? get_disp_boolean( $cpt_post_type["has_archive"] ) : '';
			$cpt_exclude_from_search            = ( !empty( $cpt_post_type["exclude_from_search"] ) ) ? get_disp_boolean( $cpt_post_type["exclude_from_search"] ) : '';
			$cpt_labels['add_new']              = ( !empty( $cpt_post_type[2]["add_new"] ) ) ? $cpt_post_type[2]["add_new"] : 'Add ' .$cpt_singular;
			$cpt_labels['add_new_item']         = ( !empty( $cpt_post_type[2]["add_new_item"] ) ) ? $cpt_post_type[2]["add_new_item"] : 'Add New ' .$cpt_singular;
			$cpt_labels['edit']                 = ( !empty( $cpt_post_type[2]["edit"] ) ) ? $cpt_post_type[2]["edit"] : 'Edit';
			$cpt_labels['edit_item']            = ( !empty( $cpt_post_type[2]["edit_item"] ) ) ? $cpt_post_type[2]["edit_item"] : 'Edit ' .$cpt_singular;
			$cpt_labels['new_item']             = ( !empty( $cpt_post_type[2]["new_item"] ) ) ? $cpt_post_type[2]["new_item"] : 'New ' .$cpt_singular;
			$cpt_labels['view']                 = ( !empty( $cpt_post_type[2]["view"] ) ) ? $cpt_post_type[2]["view"] : 'View ' .$cpt_singular;
			$cpt_labels['view_item']            = ( !empty( $cpt_post_type[2]["view_item"] ) ) ? $cpt_post_type[2]["view_item"] : 'View ' .$cpt_singular;
			$cpt_labels['search_items']         = ( !empty( $cpt_post_type[2]["search_items"] ) ) ? $cpt_post_type[2]["search_items"] : 'Search ' .$cpt_label;
			$cpt_labels['not_found']            = ( !empty( $cpt_post_type[2]["not_found"] ) ) ? $cpt_post_type[2]["not_found"] : 'No ' .$cpt_label. ' Found';
			$cpt_labels['not_found_in_trash']   = ( !empty( $cpt_post_type[2]["not_found_in_trash"] ) ) ? $cpt_post_type[2]["not_found_in_trash"] : 'No ' .$cpt_label. ' Found in Trash';
			$cpt_labels['parent']               = ( $cpt_post_type[2]["parent"] ) ? $cpt_post_type[2]["parent"] : 'Parent ' .$cpt_singular;

			$cpt_pre_register_post_type_args = array(
				'label' => $cpt_label,
				'public' => get_disp_boolean($cpt_post_type["public"]),
				'singular_label' => $cpt_post_type["singular_label"],
				'show_ui' => get_disp_boolean($cpt_post_type["show_ui"]),
				'has_archive' => $cpt_has_archive,
				'show_in_menu' => $cpt_show_in_menu,
				'capability_type' => $cpt_post_type["capability_type"],
				'map_meta_cap' => true,
				'hierarchical' => get_disp_boolean($cpt_post_type["hierarchical"]),
				'exclude_from_search' => $cpt_exclude_from_search,
				'rewrite' => array( 'slug' => $cpt_rewrite_slug, 'with_front' => $cpt_rewrite_withfront ),
				'query_var' => get_disp_boolean($cpt_post_type["query_var"]),
				'description' => esc_html($cpt_post_type["description"]),
				'menu_position' => $cpt_menu_position,
				'menu_icon' => $cpt_menu_icon,
				'supports' => $cpt_supports,
				'taxonomies' => $cpt_taxonomies,
				'labels' => $cpt_labels
			);

			//pass all of our arguments as well as the future post type name through a filter.
			$cpt_register_post_type_args = apply_filters( 'cptui_register_post_type_args', $cpt_pre_register_post_type_args, $cpt_post_type["name"] );
			/*if ( !is_array( $cpt_register_post_type_args ) )
				wp_die( 'Please return an array to the \'cptui_register_post_type_args\' filter.' );*/
			//finally register the post type.
			register_post_type( $cpt_post_type["name"], $cpt_register_post_type_args );
		}
	}
}


function cpt_create_custom_taxonomies() {
	//register custom taxonomies
	$cpt_tax_types = get_option('cpt_custom_tax_types');

	//check if option value is an array before proceeding
	if ( is_array( $cpt_tax_types ) ) {
		foreach ($cpt_tax_types as $cpt_tax_type) {

			//set custom taxonomy values
			$cpt_label              = ( !empty( $cpt_tax_type["label"] ) ) ? esc_html( $cpt_tax_type["label"] ) : esc_html( $cpt_tax_type["name"] );
			$cpt_singular_label     = ( !empty( $cpt_tax_type["singular_label"] ) ) ? esc_html( $cpt_tax_type["singular_label"] ) : esc_html( $cpt_tax_type["name"] );
			$cpt_rewrite_slug       = ( !empty( $cpt_tax_type["rewrite_slug"] ) ) ? esc_html( $cpt_tax_type["rewrite_slug"] ) : esc_html( $cpt_tax_type["name"] );
			$cpt_tax_show_admin_column = ( !empty( $cpt_tax_type["show_admin_column"] ) ) ? esc_html( $cpt_tax_type["show_admin_column"] ) : false;
			$cpt_post_types         = ( !empty( $cpt_tax_type[1] ) ) ? $cpt_tax_type[1] : $cpt_tax_type["cpt_name"];

			//set custom label values
			$cpt_labels['name']                         = $cpt_label;
			$cpt_labels['singular_name']                = $cpt_tax_type["singular_label"];
			$cpt_labels['search_items']                 = ( $cpt_tax_type[0]["search_items"] ) ? $cpt_tax_type[0]["search_items"] : 'Search ' .$cpt_label;
			$cpt_labels['popular_items']                = ( $cpt_tax_type[0]["popular_items"] ) ? $cpt_tax_type[0]["popular_items"] : 'Popular ' .$cpt_label;
			$cpt_labels['all_items']                    = ( $cpt_tax_type[0]["all_items"] ) ? $cpt_tax_type[0]["all_items"] : 'All ' .$cpt_label;
			$cpt_labels['parent_item']                  = ( $cpt_tax_type[0]["parent_item"] ) ? $cpt_tax_type[0]["parent_item"] : 'Parent ' .$cpt_singular_label;
			$cpt_labels['parent_item_colon']            = ( $cpt_tax_type[0]["parent_item_colon"] ) ? $cpt_tax_type[0]["parent_item_colon"] : 'Parent ' .$cpt_singular_label. ':';
			$cpt_labels['edit_item']                    = ( $cpt_tax_type[0]["edit_item"] ) ? $cpt_tax_type[0]["edit_item"] : 'Edit ' .$cpt_singular_label;
			$cpt_labels['update_item']                  = ( $cpt_tax_type[0]["update_item"] ) ? $cpt_tax_type[0]["update_item"] : 'Update ' .$cpt_singular_label;
			$cpt_labels['add_new_item']                 = ( $cpt_tax_type[0]["add_new_item"] ) ? $cpt_tax_type[0]["add_new_item"] : 'Add New ' .$cpt_singular_label;
			$cpt_labels['new_item_name']                = ( $cpt_tax_type[0]["new_item_name"] ) ? $cpt_tax_type[0]["new_item_name"] : 'New ' .$cpt_singular_label. ' Name';
			$cpt_labels['separate_items_with_commas']   = ( $cpt_tax_type[0]["separate_items_with_commas"] ) ? $cpt_tax_type[0]["separate_items_with_commas"] : 'Separate ' .$cpt_label. ' with commas';
			$cpt_labels['add_or_remove_items']          = ( $cpt_tax_type[0]["add_or_remove_items"] ) ? $cpt_tax_type[0]["add_or_remove_items"] : 'Add or remove ' .$cpt_label;
			$cpt_labels['choose_from_most_used']        = ( $cpt_tax_type[0]["choose_from_most_used"] ) ? $cpt_tax_type[0]["choose_from_most_used"] : 'Choose from the most used ' .$cpt_label;

			$cpt_pre_register_taxonomy_args = array(
				'hierarchical' => get_disp_boolean($cpt_tax_type["hierarchical"]),
				'label' => $cpt_label,
				'show_ui' => get_disp_boolean($cpt_tax_type["show_ui"]),
				'query_var' => get_disp_boolean($cpt_tax_type["query_var"]),
				'rewrite' => array('slug' => $cpt_rewrite_slug),
				'singular_label' => $cpt_singular_label,
				'labels' => $cpt_labels,
				'show_admin_column' => $cpt_tax_show_admin_column
			);

			//pass all of our arguments as well as the future taxonomy name and assigned post types through a filter.
			$cpt_register_taxonomy_args = apply_filters( 'cptui_register_taxonomy_args', $cpt_pre_register_taxonomy_args, $cpt_tax_type["name"], $cpt_post_types );
			/*if ( !is_array( $cpt_register_taxonomy_args ) )
				wp_die( 'Please return an array to the \'cptui_register_taxonomy_args\' filter.' );*/
			//register our custom taxonomies
			register_taxonomy( $cpt_tax_type["name"], $cpt_post_types, $cpt_register_taxonomy_args );
		}
	}
}

//delete custom post type or custom taxonomy
function cpt_delete_post_type() {
	global $CPT_URL;

	//check if we are deleting a custom post type
	if( isset( $_GET['deltype'] ) ) {

		//nonce security check
		check_admin_referer( 'cpt_delete_post_type' );

		$delType = intval( $_GET['deltype'] );
		$cpt_post_types = get_option( 'cpt_custom_post_types' );

		unset( $cpt_post_types[$delType] );

		$cpt_post_types = array_values( $cpt_post_types );

		update_option( 'cpt_custom_post_types', $cpt_post_types );

		if ( isset( $_GET['return'] ) ) {
			$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
		} else {
			$RETURN_URL = $CPT_URL;
		}

		wp_redirect( $RETURN_URL .'&cpt_msg=del' );
	}

	//check if we are deleting a custom taxonomy
	if( isset( $_GET['deltax'] ) ) {
		check_admin_referer( 'cpt_delete_tax' );

		$delType = intval( $_GET['deltax'] );
		$cpt_taxonomies = get_option( 'cpt_custom_tax_types' );

		unset( $cpt_taxonomies[$delType] );

		$cpt_taxonomies = array_values( $cpt_taxonomies );

		update_option( 'cpt_custom_tax_types', $cpt_taxonomies );

		if ( isset( $_GET['return'] ) ) {
			$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
		} else {
			$RETURN_URL = $CPT_URL;
		}

		wp_redirect( $RETURN_URL .'&cpt_msg=del' );
	}

}

function cpt_register_settings() {
	global $cpt_error, $CPT_URL;

	if ( isset( $_POST['cpt_edit'] ) ) {
		//edit a custom post type
		check_admin_referer( 'cpt_add_custom_post_type' );

		//custom post type to edit
		$cpt_edit = intval( $_POST['cpt_edit'] );

		//edit the custom post type
		$cpt_form_fields = $_POST['cpt_custom_post_type'];

		//add support checkbox values to array
		$cpt_supports = ( isset( $_POST['cpt_supports'] ) ) ? $_POST['cpt_supports'] : null;
		array_push($cpt_form_fields, $cpt_supports);

		//add taxonomies support checkbox values to array
		$cpt_addon_taxes = ( isset( $_POST['cpt_addon_taxes'] ) ) ? $_POST['cpt_addon_taxes'] : null;
		array_push( $cpt_form_fields, $cpt_addon_taxes );

		//add label values to array
		array_push( $cpt_form_fields, $_POST['cpt_labels'] );

		//load custom posts saved in WP
		$cpt_options = get_option( 'cpt_custom_post_types' );

		if ( is_array( $cpt_options ) ) {

			unset( $cpt_options[$cpt_edit] );

			//insert new custom post type into the array
			array_push( $cpt_options, $cpt_form_fields );

			$cpt_options = array_values( $cpt_options );
			$cpt_options = stripslashes_deep( $cpt_options );

			//save custom post types
			update_option( 'cpt_custom_post_types', $cpt_options );

			if ( isset( $_GET['return'] ) ) {
				$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
			} else {
				$RETURN_URL = $CPT_URL;
			}

			wp_redirect( $RETURN_URL );

		}

	} elseif ( isset( $_POST['cpt_submit'] ) ) {
		//create a new custom post type

		//nonce security check
		check_admin_referer( 'cpt_add_custom_post_type' );

		//retrieve new custom post type values
		$cpt_form_fields = $_POST['cpt_custom_post_type'];

		if ( empty( $cpt_form_fields["name"] ) ) {
			if ( isset( $_GET['return'] ) ) {
				$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
			} else {
				$RETURN_URL = $CPT_URL;
			}

			wp_redirect( $RETURN_URL .'&cpt_error=1' );
			exit();
		}
		if ( false !== strpos( $cpt_form_fields["name"], '\'' ) ||
			 false !== strpos( $cpt_form_fields["name"], '\"' ) ||
			 false !== strpos( $cpt_form_fields["rewrite_slug"], '\'' ) ||
			 false !== strpos( $cpt_form_fields["rewrite_slug"], '\"' ) ) {
			if ( isset( $_GET['return'] ) ) {
				$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
			} else {
				$RETURN_URL = $CPT_URL;
			}

			wp_redirect( $RETURN_URL .'&cpt_error=4' );
			exit();
		}

		//add support checkbox values to array
		$cpt_supports = ( isset( $_POST['cpt_supports'] ) ) ? $_POST['cpt_supports'] : null;
		array_push( $cpt_form_fields, $cpt_supports );

		//add taxonomies support checkbox values to array
		$cpt_addon_taxes = ( isset( $_POST['cpt_addon_taxes'] ) ) ? $_POST['cpt_addon_taxes'] : null;
		array_push( $cpt_form_fields, $cpt_addon_taxes );

		//add label values to array
		array_push( $cpt_form_fields, $_POST['cpt_labels'] );

		//load custom posts saved in WP
		$cpt_options = get_option( 'cpt_custom_post_types' );

		//check if option exists, if not create an array for it
		if ( !is_array( $cpt_options ) ) {
			$cpt_options = array();
		}

		//insert new custom post type into the array
		array_push( $cpt_options, $cpt_form_fields );
		$cpt_options = stripslashes_deep( $cpt_options );

		//save new custom post type array in the CPT option
		update_option( 'cpt_custom_post_types', $cpt_options );

		if ( isset( $_GET['return'] ) ) {
			$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
		} else {
			$RETURN_URL = $CPT_URL;
		}

		wp_redirect( $RETURN_URL .'&cpt_msg=1' );
	}

	if ( isset( $_POST['cpt_edit_tax'] ) ) {
		//edit a custom taxonomy

		//nonce security check
		check_admin_referer( 'cpt_add_custom_taxonomy' );

		//custom taxonomy to edit
		$cpt_edit = intval( $_POST['cpt_edit_tax'] );

		//edit the custom taxonomy
		$cpt_form_fields = $_POST['cpt_custom_tax'];

		//add label values to array
		array_push( $cpt_form_fields, $_POST['cpt_tax_labels'] );

		//add attached post type values to array
		array_push( $cpt_form_fields, $_POST['cpt_post_types'] );

		//load custom posts saved in WP
		$cpt_options = get_option( 'cpt_custom_tax_types' );

		if ( is_array( $cpt_options ) ) {

			unset( $cpt_options[$cpt_edit] );

			//insert new custom post type into the array
			array_push( $cpt_options, $cpt_form_fields );

			$cpt_options = array_values( $cpt_options );

			//save custom post types
			update_option( 'cpt_custom_tax_types', $cpt_options );

			if ( isset( $_GET['return'] ) ) {
				$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
			} else {
				$RETURN_URL = $CPT_URL;
			}

			wp_redirect( $RETURN_URL );

		}

	} elseif ( isset( $_POST['cpt_add_tax'] ) ) {
		//create new custom taxonomy

		//nonce security check
		check_admin_referer( 'cpt_add_custom_taxonomy' );

		//retrieve new custom taxonomy values
		$cpt_form_fields = $_POST['cpt_custom_tax'];

		//verify required fields are filled out
		if ( empty( $cpt_form_fields["name"] ) ) {
			if ( isset( $_GET['return'] ) ) {
				$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
			} else {
				$RETURN_URL = $CPT_URL;
			}

			wp_redirect( $RETURN_URL .'&cpt_error=2' );
			exit();
		} elseif ( empty( $_POST['cpt_post_types'] ) ) {
			if ( isset( $_GET['return'] ) ) {
				$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
			} else {
				$RETURN_URL = $CPT_URL;
			}

			wp_redirect( $RETURN_URL .'&cpt_error=3' );
			exit();
		}

		if ( false !== strpos( $cpt_form_fields["name"], '\'' ) ||
			 false !== strpos( $cpt_form_fields["name"], '\"' ) ||
			 false !== strpos( $cpt_form_fields["rewrite_slug"], '\'' ) ||
			 false !== strpos( $cpt_form_fields["rewrite_slug"], '\"' ) ) {
			if ( isset( $_GET['return'] ) ) {
				$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
			} else {
				$RETURN_URL = $CPT_URL;
			}

			wp_redirect( $RETURN_URL .'&cpt_error=5' );
			exit();
		}

		//add label values to array
		array_push( $cpt_form_fields, $_POST['cpt_tax_labels'] );

		//add attached post type values to array
		array_push( $cpt_form_fields, $_POST['cpt_post_types'] );

		//load custom taxonomies saved in WP
		$cpt_options = get_option( 'cpt_custom_tax_types' );

		//check if option exists, if not create an array for it
		if ( !is_array( $cpt_options ) ) {
			$cpt_options = array();
		}

		//insert new custom taxonomy into the array
		array_push( $cpt_options, $cpt_form_fields );

		//save new custom taxonomy array in the CPT option
		update_option( 'cpt_custom_tax_types', $cpt_options );

		if ( isset( $_GET['return'] ) ) {
			$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
		} else {
			$RETURN_URL = $CPT_URL;
		}

		wp_redirect( $RETURN_URL .'&cpt_msg=2' );

	}
}

//main welcome/settings page
function cpt_settings() {
	global $CPT_URL, $wp_post_types;

	//flush rewrite rules
	flush_rewrite_rules();
?>
	<div class="wrap">
		<?php screen_icon( 'plugins' ); ?>
		<?php do_action( 'cptui_main_page_start' ); ?>
		<h2><?php _e( 'Custom Post Type UI', 'cpt-plugin' ); ?> <?php _e( 'version', 'cpt-plugin' ); ?>: <?php echo CPT_VERSION; ?></h2>

		<?php do_action( 'cptui_main_page_before_faq' ); ?>
		<h2><?php _e( 'Frequently Asked Questions', 'cpt-plugin' ); ?></h2>
		<p><?php _e( 'Please note that this plugin will NOT handle display of registered post types or taxonomies in your current theme. It will simply register them for you.', 'cpt-plugin' ); ?>
		<h3><?php _e( 'Q: <strong>How can I display content from a custom post type on my website?</strong>', 'cpt-plugin' ); ?></h3>
		<p>
			<?php _e( 'A: Justin Tadlock has written some great posts on the topic:', 'cpt-plugin' ); ?><br />
			<a href="http://justintadlock.com/archives/2010/02/02/showing-custom-post-types-on-your-home-blog-page" target="_blank"><?php _e( 'Showing Custom Post Types on your Home Page', 'cpt-plugin' ); ?></a><br />
			<a href="http://justintadlock.com/archives/2010/04/29/custom-post-types-in-wordpress" target="_blank"><?php _e( 'Custom Post Types in WordPress', 'cpt-plugin' ); ?></a>
		</p>
		<h3><?php _e( 'Q: <strong>How can I add custom meta boxes to my custom post types?</strong>', 'cpt-plugin' ); ?></h3>
		<p><?php _e( 'A: The More Fields plugin does a great job at creating custom meta boxes and fully supports custom post types: ', 'cpt-plugin' ); ?><a href="http://wordpress.org/extend/plugins/more-fields/" target="_blank">http://wordpress.org/extend/plugins/more-fields/</a>.  The <a href="https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress" target="_blank">Custom Metaboxes and Fields for WordPress</a> class is a great alternative to a plugin for more advanced users.</p>
		<h3><?php _e( 'Q: <strong>I changed my custom post type name and now I can\'t get to my posts</strong>', 'cpt-plugin' ); ?></h3>
		<p><?php _e( 'A: You can either change the custom post type name back to the original name or try the Post Type Switcher plugin: ', 'cpt-plugin' ); ?><a href="http://wordpress.org/extend/plugins/post-type-switcher/" target="_blank">http://wordpress.org/extend/plugins/post-type-switcher/</a></p>
		<?php do_action( 'cptui_main_page_after_faq' ); ?>

		<div class="cp-rss-widget">

		<?php do_action( 'cptui_main_page_before_books' ); ?>
		<table border="0">
			<tr>
			<td colspan="3"><h2><?php _e( 'Help Support This Plugin!', 'cpt-plugin' ); ?></h2></td>
			</tr>
			<tr>
			<td width="33%"><h3><?php _e( 'PayPal Donation', 'cpt-plugin' ); ?></h3></td>
			<td width="33%"><h3><?php _e( 'Professional WordPress<br />Second Edition', 'cpt-plugin' ); ?></h3></td>
			<td width="33%"><h3><?php _e( 'Professional WordPress<br />Plugin Development', 'cpt-plugin' ); ?></h3></td>
			</tr>
			<tr>
			<td valign="top" width="33%">
				<p><?php _e( 'Please donate to the development<br />of Custom Post Type UI:', 'cpt-plugin'); ?>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="YJEDXPHE49Q3U">
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
				</p>
			</td>
			<td valign="top" width="33%"><a href="http://bit.ly/prowp2" target="_blank"><img src="<?php echo plugins_url( '/images/professional-wordpress-secondedition.jpg', __FILE__ ); ?>" width="200"></a><br /><?php _e( 'The leading book on WordPress design and development!<br /><strong>Brand new second edition!', 'cpt-plugin'); ?></strong></td>
			<td valign="top" width="33%"><a href="http://amzn.to/plugindevbook" target="_blank"><img src="<?php echo plugins_url( '/images/professional-wordpress-plugin-development.png', __FILE__ ); ?>" width="200"></a><br /><?php _e( 'Highest rated WordPress development book on Amazon!', 'cpt-plugin' ); ?></td>
			</tr>
		</table>

		<?php do_action( 'cptui_main_page_after_books' ); ?>

		<?php do_action( 'cptui_main_page_before_rss' ); ?>
		<h2><?php _e( 'WebDevStudios.com Recent News', 'cpt-plugin' ); ?></h2>
		<?php

		wp_widget_rss_output( array(
			'url' => esc_url( 'http://webdevstudios.com/feed/' ),
			'title' => __( 'WebDevStudios.com News', 'cpt-plugin' ),
			'items' => 3,
			'show_summary' => 1,
			'show_author' => 0,
			'show_date' => 1
		) );
		?>

		<?php do_action( 'cptui_main_page_after_rss' ); ?>
		</div>
	</div>
<?php
//load footer
cpt_footer();
}

//manage custom post types page
function cpt_manage_cpt() {
	global $CPT_URL;

	$MANAGE_URL = cpt_check_return( 'add' );

?>
<div class="wrap">
<?php
do_action( 'cptui_manage_post_types_start' );
//check for success/error messages
if ( isset($_GET['cpt_msg'] ) && $_GET['cpt_msg'] == 'del' ) { ?>
	<div id="message" class="updated">
		<?php
			echo apply_filters( 'cptui_delete_post_type_message_success_text', __('Custom post type deleted successfully', 'cpt-plugin') );
		?>
	</div>
	<?php
}
?>
<?php screen_icon( 'plugins' );

do_action( 'cptui_manage_post_types_before_title' ); ?>

<h2><?php _e('Manage Custom Post Types', 'cpt-plugin') ?></h2>

<?php  ?>

<p><?php _e('Deleting custom post types will <strong>NOT</strong> delete any content into the database or added to those post types.  You can easily recreate your post types and the content will still exist.', 'cpt-plugin') ?></p>
<?php
	$cpt_post_types = get_option( 'cpt_custom_post_types', array() );

	if (is_array($cpt_post_types)) {
		$cptui_header_footers = array(
			__( 'Action', 'cpt-plugin' ),
			__( 'Name', 'cpt-plugin' ),
			__( 'Label', 'cpt-plugin' ),
			__( 'Public', 'cpt-plugin' ),
			__( 'Show UI', 'cpt-plugin' ),
			__( 'Hierarchical', 'cpt-plugin' ),
			__( 'Rewrite', 'cpt-plugin' ),
			__( 'Rewrite Slug', 'cpt-plugin' ),
			__( 'Total Published', 'cpt-plugin' ),
			__( 'Total Drafts', 'cpt-plugin' ),
			__( 'Supports', 'cpt-plugin' )
		);

		do_action( 'cptui_manage_post_types_before_post_types_table' );
		?>
		<table width="100%" class="widefat">
			<thead>
				<tr>
					<?php
					foreach( $cptui_header_footers as $header_footer ) {
						echo '<th>' . $header_footer . '</th>';
					}
					?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<?php
					foreach( $cptui_header_footers as $header_footer ) {
						echo '<th>' . $header_footer . '</th>';
					}
					?>
				</tr>
			</tfoot>
		<?php
		$thecounter = 0;
		$cpt_names = array();
		//Create urls for management
		foreach ( $cpt_post_types as $cpt_post_type ) {
			$del_url = cpt_check_return( 'cpt' ) .'&deltype=' .$thecounter .'&return=cpt';
			$del_url = wp_nonce_url( $del_url, 'cpt_delete_post_type' );

			$edit_url = $MANAGE_URL .'&edittype=' .$thecounter .'&return=cpt';
			$edit_url = wp_nonce_url($edit_url, 'cpt_edit_post_type');

			$cpt_counts = wp_count_posts($cpt_post_type["name"]);

			$rewrite_slug = ( $cpt_post_type["rewrite_slug"] ) ? $cpt_post_type["rewrite_slug"] : $cpt_post_type["name"];
		?>
			<tr>
				<td valign="top"><a href="<?php echo $del_url; ?>"><?php _e( 'Delete', 'cpt-plugin' ); ?></a> / <a href="<?php echo $edit_url; ?>"><?php _e( 'Edit', 'cpt-plugin' ); ?></a> / <a href="#" class="comment_button" id="<?php echo $thecounter; ?>"><?php _e( 'Get Code', 'cpt-plugin' ); ?></a></td>
				<td valign="top"><?php echo stripslashes($cpt_post_type["name"]); ?></td>
				<td valign="top"><?php echo stripslashes($cpt_post_type["label"]); ?></td>
				<td valign="top"><?php echo disp_boolean($cpt_post_type["public"]); ?></td>
				<td valign="top"><?php echo disp_boolean($cpt_post_type["show_ui"]); ?></td>
				<td valign="top"><?php echo disp_boolean($cpt_post_type["hierarchical"]); ?></td>
				<td valign="top"><?php echo disp_boolean($cpt_post_type["rewrite"]); ?></td>
				<td valign="top"><?php echo $rewrite_slug; ?></td>
				<td valign="top"><?php echo $cpt_counts->publish; ?></td>
				<td valign="top"><?php echo $cpt_counts->draft; ?></td>
				<td>
					<?php
					if (is_array($cpt_post_type[0])) {
						foreach ($cpt_post_type[0] as $cpt_supports) {
							echo $cpt_supports .'<br />';
						}
					}
					?>
				</td>
			</tr>
			<tr style="display:none;" id="slidepanel<?php echo $thecounter; ?>">
				<td colspan="12">
					<div>
						<?php
						// Begin the display for the "Get code" feature
						//display register_post_type code
						$custom_post_type   = '';
						$cpt_support_array  = '';
						$cpt_tax_array      = '';

						$cpt_label = ( empty( $cpt_post_type["label"] ) ) ? esc_html($cpt_post_type["name"]) : esc_html($cpt_post_type["label"]);
						$cpt_singular = ( empty( $cpt_post_type["singular_label"] ) ) ? $cpt_label : esc_html($cpt_post_type["singular_label"]);
						$cpt_rewrite_slug = ( empty( $cpt_post_type["rewrite_slug"] ) ) ? esc_html($cpt_post_type["name"]) : esc_html($cpt_post_type["rewrite_slug"]);
						$cpt_menu_position = ( empty( $cpt_post_type["menu_position"] ) ) ? null : intval($cpt_post_type["menu_position"]);
						$cpt_menu_icon = ( !empty( $cpt_post_type["menu_icon"] ) ) ? esc_url($cpt_post_type["menu_icon"]) : null;

						if ( true == $cpt_post_type["show_ui"] ) {
							$cpt_show_in_menu = ( $cpt_post_type["show_in_menu"] == 1 ) ? 1 : 0;
							$cpt_show_in_menu = ( $cpt_post_type["show_in_menu_string"] ) ? '\''.$cpt_post_type["show_in_menu_string"].'\'' : $cpt_show_in_menu;
						} else {
							$cpt_show_in_menu = 0;
						}
						//set custom label values
						$cpt_labels['name'] = $cpt_label;
						$cpt_labels['singular_name'] = $cpt_post_type["singular_label"];
						$cpt_labels['menu_name'] = ( $cpt_post_type[2]["menu_name"] ) ? $cpt_post_type[2]["menu_name"] : $cpt_label;
						$cpt_labels['add_new'] = ( $cpt_post_type[2]["add_new"] ) ? $cpt_post_type[2]["add_new"] : 'Add ' .$cpt_singular;
						$cpt_labels['add_new_item'] = ( $cpt_post_type[2]["add_new_item"] ) ? $cpt_post_type[2]["add_new_item"] : 'Add New ' .$cpt_singular;
						$cpt_labels['edit'] = ( $cpt_post_type[2]["edit"] ) ? $cpt_post_type[2]["edit"] : 'Edit';
						$cpt_labels['edit_item'] = ( $cpt_post_type[2]["edit_item"] ) ? $cpt_post_type[2]["edit_item"] : 'Edit ' .$cpt_singular;
						$cpt_labels['new_item'] = ( $cpt_post_type[2]["new_item"] ) ? $cpt_post_type[2]["new_item"] : 'New ' .$cpt_singular;
						$cpt_labels['view'] = ( $cpt_post_type[2]["view"] ) ? $cpt_post_type[2]["view"] : 'View ' .$cpt_singular;
						$cpt_labels['view_item'] = ( $cpt_post_type[2]["view_item"] ) ? $cpt_post_type[2]["view_item"] : 'View ' .$cpt_singular;
						$cpt_labels['search_items'] = ( $cpt_post_type[2]["search_items"] ) ? $cpt_post_type[2]["search_items"] : 'Search ' .$cpt_label;
						$cpt_labels['not_found'] = ( $cpt_post_type[2]["not_found"] ) ? $cpt_post_type[2]["not_found"] : 'No ' .$cpt_label. ' Found';
						$cpt_labels['not_found_in_trash'] = ( $cpt_post_type[2]["not_found_in_trash"] ) ? $cpt_post_type[2]["not_found_in_trash"] : 'No ' .$cpt_label. ' Found in Trash';
						$cpt_labels['parent'] = ( $cpt_post_type[2]["parent"] ) ? $cpt_post_type[2]["parent"] : 'Parent ' .$cpt_singular;

						if( is_array( $cpt_post_type[0] ) ) {
							$counter = 1;
							$count = count( $cpt_post_type[0] );
							foreach ( $cpt_post_type[0] as $cpt_supports ) {
								//build supports variable
								$cpt_support_array .= '\'' . $cpt_supports . '\'';
								if ( $counter != $count )
									$cpt_support_array .= ',';

								$counter++;
							}
						}

						if( is_array( $cpt_post_type[1] ) ) {
							$counter = 1;
							$count = count( $cpt_post_type[1] );
							foreach ( $cpt_post_type[1] as $cpt_taxes ) {
							//build taxonomies variable
								$cpt_tax_array .= '\''.$cpt_taxes.'\'';
								if ( $counter != $count )
									$cpt_tax_array .= ',';
								$counter++;
							}
						}
						$custom_post_type = "add_action('init', 'cptui_register_my_cpt_" . $cpt_post_type["name"] . "');\n";
						$custom_post_type .= "function cptui_register_my_cpt_" . $cpt_post_type["name"] . "() {\n";
						$custom_post_type .= "register_post_type('" . $cpt_post_type["name"] . "', array(\n'label' => '" . $cpt_label . "',\n";
						$custom_post_type .= "'description' => '" . $cpt_post_type["description"] . "',\n";
						$custom_post_type .= "'public' => " . disp_boolean( $cpt_post_type["public"]) . ",\n";
						$custom_post_type .= "'show_ui' => " . disp_boolean( $cpt_post_type["show_ui"]) . ",\n";
						$custom_post_type .= "'show_in_menu' => " . disp_boolean( $cpt_show_in_menu) . ",\n";
						$custom_post_type .= "'capability_type' => '" . $cpt_post_type["capability_type"] . "',\n";
						$custom_post_type .= "'map_meta_cap' => " . disp_boolean( '1' ) . ",\n";
						$custom_post_type .= "'hierarchical' => " . disp_boolean( $cpt_post_type["hierarchical"] ) . ",\n";

						if ( !empty( $cpt_post_type["rewrite_slug"] ) ) {
							$custom_post_type .= "'rewrite' => array('slug' => '" . $cpt_post_type["rewrite_slug"] . "', 'with_front' => " . $cpt_post_type['rewrite_withfront'] . "),\n";
						} else {
							if( empty( $cpt_post_type['rewrite_withfront'] ) ) $cpt_post_type['rewrite_withfront'] = 1;
							$custom_post_type .= "'rewrite' => array('slug' => '" . $cpt_post_type["name"] . "', 'with_front' => " . disp_boolean( $cpt_post_type['rewrite_withfront'] ) . "),\n";
						}

						$custom_post_type .= "'query_var' => " . disp_boolean($cpt_post_type["query_var"]) . ",\n";

						if ( !empty( $cpt_post_type["has_archive"] ) ) {
							$custom_post_type .= "'has_archive' => " . disp_boolean( $cpt_post_type["has_archive"] ) . ",\n";
						}

						if ( !empty( $cpt_post_type["exclude_from_search"] ) ) {
							$custom_post_type .= "'exclude_from_search' => " . disp_boolean( $cpt_post_type["exclude_from_search"] ) . ",\n";
						}

						if ( !empty( $cpt_post_type["menu_position"] ) ) {
							$custom_post_type .= "'menu_position' => '" . $cpt_post_type["menu_position"] . "',\n";
						}

						if ( !empty( $cpt_post_type["menu_icon"] ) ) {
							$custom_post_type .= "'menu_icon' => '" . $cpt_post_type["menu_icon"] . "',\n";
						}

						$custom_post_type .= "'supports' => array(" . $cpt_support_array . "),\n";

						if ( !empty( $cpt_tax_array ) ) {
							$custom_post_type .= "'taxonomies' => array(" . $cpt_tax_array . "),\n";
						}

						if ( !empty( $cpt_labels ) ) {
							$custom_post_type .= "'labels' => " . var_export( $cpt_labels, true ) . "\n";
						}

						$custom_post_type .= ") ); }";

						$custom_post_type = apply_filters( 'cptui_cpt_get_code_output', $custom_post_type, $cpt_post_type["name"] );
						echo '<p>';
						_e( 'Place the below code in your themes functions.php file to manually create this custom post type.', 'cpt-plugin' ).'<br>';
						_e('This is a <strong>BETA</strong> feature. Please <a href="https://github.com/WebDevStudios/custom-post-type-ui">report bugs</a>.','cpt-plugin').'</p>';
						echo '<textarea rows="10" cols="100">' .$custom_post_type .'</textarea>';

						?>
					</div>
				</td>
			</tr>

		<?php
			$thecounter++;
			$cpt_names[] = strtolower( $cpt_post_type["name"] );
		}//end post type tr loop
		echo '</table>';

		do_action( 'cptui_manage_post_types_after_post_types_table' );

			$args = array(
			  'public'   => true,
			  '_builtin' => false
			);
			$output = 'objects'; // or objects
			$post_types = get_post_types( $args, $output );
			$cpt_first = false;
			if ( $post_types ) {
				do_action( 'cptui_manage_post_types_before_additional_post_types' );
				?>
				<h3><?php _e('Additional Custom Post Types', 'cpt-plugin') ?></h3>
				<p><?php _e('The custom post types below are registered in WordPress but were not created by the Custom Post Type UI Plugin.', 'cpt-plugin') ?></p>
					<?php
					foreach ( $post_types as $post_type ) {
						  if ( !in_array( strtolower( $post_type->name ), $cpt_names ) ) {
							if ( isset( $cpt_first ) && !$cpt_first ) {

								$other_registered_cpts = array(
									__( 'Name', 'cpt-plugin' ),
									__( 'Label', 'cpt-plugin' ),
									__( 'Public', 'cpt-plugin' ),
									__( 'Show UI', 'cpt-plugin' ),
									__( 'Hierarchical', 'cpt-plugin' ),
									__( 'Rewrite', 'cpt-plugin' ),
									__( 'Rewrite Slug', 'cpt-plugin' ),
									__( 'Query Var', 'cpt-plugin' )
								);
								?>
								<table width="100%" class="widefat">
									<thead>
										<tr>
											<?php
											foreach( $other_registered_cpts as $cpt ) {
												echo '<th>' . $cpt . '</th>';
											}
											?>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<?php
											foreach( $other_registered_cpts as $cpt ) {
												echo '<th>' . $cpt . '</th>';
											}
											?>
										</tr>
									</tfoot>
								<?php
								$cpt_first = true;
							}
							$rewrite_slug = ( isset( $post_type->rewrite_slug ) ) ? $post_type->rewrite_slug : $post_type->name;
							?>
							<tr>
								<td valign="top"><?php echo $post_type->name; ?></td>
								<td valign="top"><?php echo $post_type->label; ?></td>
								<td valign="top"><?php echo disp_boolean($post_type->public); ?></td>
								<td valign="top"><?php echo disp_boolean($post_type->show_ui); ?></td>
								<td valign="top"><?php echo disp_boolean($post_type->hierarchical); ?></td>
								<td valign="top"><?php echo disp_boolean($post_type->rewrite); ?></td>
								<td valign="top"><?php echo $rewrite_slug; ?></td>
								<td valign="top"><?php echo disp_boolean($post_type->query_var); ?></td>
							</tr>
							</table>
							<?php
						}
				  }
			}
			do_action( 'cptui_manage_post_types_after_additional_post_types' );
			if ( isset($cpt_first) && !$cpt_first ) {
				echo '<tr><td><strong>';
				_e( 'No additional post types found', 'cpt-plugin' );
				echo '</strong></td></tr>';
			}
			?>

		</div><?php
		do_action( 'cptui_manage_post_types_end' );
		//load footer
		cpt_footer();
	}
}

//manage custom taxonomies page
function cpt_manage_taxonomies() {
	global $CPT_URL;

	$MANAGE_URL = cpt_check_return( 'add' );

?>
<div class="wrap">
<?php
do_action( 'cptui_manage_taxonomies_start' );
//check for success/error messages
if (isset($_GET['cpt_msg']) && $_GET['cpt_msg']=='del') { ?>
	<div id="message" class="updated">
		<?php echo apply_filters( 'cptui_delete_post_type_message_success_text', __('Custom taxonomy deleted successfully', 'cpt-plugin') ); ?>
	</div>
	<?php
}

screen_icon( 'plugins' ); ?>
<h2><?php _e('Manage Custom Taxonomies', 'cpt-plugin') ?></h2>
<p><?php _e('Deleting custom taxonomies does <strong>NOT</strong> delete any content added to those taxonomies.  You can easily recreate your taxonomies and the content will still exist.', 'cpt-plugin') ?></p>
	<?php
	$cpt_tax_types = get_option( 'cpt_custom_tax_types', array() );

	if (is_array($cpt_tax_types)) {

		$cptui_header_footers_tax = array(
			__( 'Action', 'cpt-plugin' ),
			__( 'Name', 'cpt-plugin' ),
			__( 'Label', 'cpt-plugin' ),
			__( 'Singular Label', 'cpt-plugin' ),
			__( 'Attached Post Types', 'cpt-plugin' ),
			__( 'Hierarchical', 'cpt-plugin' ),
			__( 'Show UI', 'cpt-plugin' ),
			__( 'Rewrite', 'cpt-plugin' ),
			__( 'Rewrite Slug', 'cpt-plugin' )
		);

		do_action( 'cptui_manage_taxonomies_before_taxonomies_table' );
		?>
		<table width="100%" class="widefat">
			<thead>
				<tr>
				<?php foreach( $cptui_header_footers_tax as $header_footer_tax ) {
					echo '<th>' . $header_footer_tax . '</th>';
				} ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
				<?php foreach( $cptui_header_footers_tax as $header_footer_tax ) {
					echo '<th>' . $header_footer_tax . '</th>';
				} ?>
				</tr>
			</tfoot>
		<?php
		$thecounter=0;
		foreach ($cpt_tax_types as $cpt_tax_type) {

			$del_url = cpt_check_return( 'cpt' ) .'&deltax=' .$thecounter .'&return=tax';
			$del_url = ( function_exists('wp_nonce_url') ) ? wp_nonce_url($del_url, 'cpt_delete_tax') : $del_url;

			$edit_url = $MANAGE_URL .'&edittax=' .$thecounter .'&return=tax';
			$edit_url = ( function_exists('wp_nonce_url') ) ? wp_nonce_url($edit_url, 'cpt_edit_tax') : $edit_url;

			$rewrite_slug = ( $cpt_tax_type["rewrite_slug"] ) ? $cpt_tax_type["rewrite_slug"] : $cpt_tax_type["name"];
		?>
			<tr>
				<td valign="top"><a href="<?php echo $del_url; ?>"><?php _e( 'Delete', 'cpt-plugin' ); ?></a> / <a href="<?php echo $edit_url; ?>"><?php _e( 'Edit', 'cpt-plugin' ); ?></a> / <a href="#" class="comment_button" id="<?php echo $thecounter; ?>"><?php _e( 'Get Code', 'cpt-plugin' ); ?></a></td>
				<td valign="top"><?php echo stripslashes($cpt_tax_type["name"]); ?></td>
				<td valign="top"><?php echo stripslashes($cpt_tax_type["label"]); ?></td>
				<td valign="top"><?php echo stripslashes($cpt_tax_type["singular_label"]); ?></td>
				<td valign="top">
				<?php
				if ( isset( $cpt_tax_type["cpt_name"] ) ) {
					echo stripslashes($cpt_tax_type["cpt_name"]);
				} elseif ( is_array( $cpt_tax_type[1] ) ) {
					foreach ($cpt_tax_type[1] as $cpt_post_types) {
						echo $cpt_post_types .'<br />';
					}
				}
				?>
				</td>
				<td valign="top"><?php echo disp_boolean($cpt_tax_type["hierarchical"]); ?></td>
				<td valign="top"><?php echo disp_boolean($cpt_tax_type["show_ui"]); ?></td>
				<td valign="top"><?php echo disp_boolean($cpt_tax_type["rewrite"]); ?></td>
				<td valign="top"><?php echo $rewrite_slug; ?></td>
			</tr>
			<tr>
				<td colspan="10">
					<div style="display:none;" id="slidepanel<?php echo $thecounter; ?>">
						<?php
						//display register_taxonomy code
						$custom_tax = '';
						$custom_tax = "add_action('init', 'cptui_register_my_taxes_" . $cpt_tax_type['name'] . "');\n";
						$custom_tax .= "function cptui_register_my_taxes_" . $cpt_tax_type['name'] . "() {\n";

						if ( !$cpt_tax_type["label"] ) {
							$cpt_label = esc_html( $cpt_tax_type["name"] );
						} else {
							$cpt_label = esc_html( $cpt_tax_type["label"] );
						}

						//check if singular label was filled out
						if ( !$cpt_tax_type["singular_label"] ) {
							$cpt_singular_label = esc_html( $cpt_tax_type["name"] );
						} else {
							$cpt_singular_label = esc_html( $cpt_tax_type["singular_label"] );
						}

						$labels = var_export( array(
							'search_items' => ( !empty( $cpt_tax_type["singular_label"] ) ) ? esc_html( $cpt_tax_type["singular_label"] ) : '',
							'popular_items' => ( !empty( $cpt_tax_type[0]["popular_items"] ) ) ? esc_html( $cpt_tax_type[0]["popular_items"] ) : '',
							'all_items' => ( !empty( $cpt_tax_type[0]["all_items"] ) ) ? esc_html( $cpt_tax_type[0]["all_items"] ) : '',
							'parent_item' => ( !empty( $cpt_tax_type[0]["parent_item"] ) ) ? esc_html( $cpt_tax_type[0]["parent_item"] ) : '',
							'parent_item_colon' => ( !empty( $cpt_tax_type[0]["parent_item_colon"] ) ) ? esc_html( $cpt_tax_type[0]["parent_item_colon"] ) : '',
							'edit_item' => ( !empty( $cpt_tax_type[0]["edit_item"] ) ) ? esc_html( $cpt_tax_type[0]["edit_item"] ) : '',
							'update_item' => ( !empty( $cpt_tax_type[0]["update_item"] ) ) ? esc_html( $cpt_tax_type[0]["update_item"] ) : '',
							'add_new_item' => ( !empty( $cpt_tax_type[0]["add_new_item"] ) ) ? esc_html( $cpt_tax_type[0]["add_new_item"] ) : '',
							'new_item_name' => ( !empty( $cpt_tax_type[0]["new_item_name"] ) ) ? esc_html( $cpt_tax_type[0]["new_item_name"] ) : '',
							'separate_items_with_commas' => ( !empty( $cpt_tax_type[0]["separate_items_with_commas"] ) ) ? esc_html( $cpt_tax_type[0]["separate_items_with_commas"] ) : '',
							'add_or_remove_items' => ( !empty( $cpt_tax_type[0]["add_or_remove_items"] ) ) ? esc_html( $cpt_tax_type[0]["add_or_remove_items"] ) : '',
							'choose_from_most_used' => ( !empty( $cpt_tax_type[0]["choose_from_most_used"] ) ) ? esc_html( $cpt_tax_type[0]["choose_from_most_used"] ) : ''
						), true );

						$cpt_post_types = ( !$cpt_tax_type[1] ) ? $cpt_tax_type["cpt_name"] : var_export( $cpt_tax_type[1], true );

						//register our custom taxonomies
						$custom_tax .= "register_taxonomy( '" . $cpt_tax_type["name"] . "',";
						$custom_tax .= $cpt_post_types . ",\n";
						$custom_tax .= "array( 'hierarchical' => " . disp_boolean( $cpt_tax_type["hierarchical"] ) . ",\n";
						$custom_tax .= "\t'label' => '" . $cpt_label . "',\n";
						$custom_tax .= "\t'show_ui' => " . disp_boolean( $cpt_tax_type["show_ui"] ) . ",\n";
						$custom_tax .= "\t'query_var' => " . disp_boolean( $cpt_tax_type["query_var"] ) . ",\n";
						if ( !empty( $cpt_tax_type["rewrite_slug"] ) ) {
							$custom_tax .= "\t'rewrite' => array( 'slug' => '" . $cpt_tax_type["rewrite_slug"] . "' ),\n";
						}


						if ( version_compare( CPTUI_WP_VERSION, '3.5', '>' ) ) {
							$custom_tax .= "\t'show_admin_column' => " . disp_boolean( $cpt_tax_type["show_admin_column"] ) . ",\n";
						}


						if ( !empty( $labels ) )
							$custom_tax .= "\t'labels' => " . $labels . "\n";
						$custom_tax .= ") ); \n}";

						$custom_tax = apply_filters( 'cptui_taxonomy_get_code_output', $custom_tax, $cpt_tax_type["name"] );
						echo '<br>';
						echo _e('Place the below code in your themes functions.php file to manually create this custom taxonomy','cpt-plugin').'<br>';
						echo _e('This is a <strong>BETA</strong> feature. Please <a href="http://webdevstudios.com/support/forum/custom-post-type-ui/">report bugs</a>.','cpt-plugin').'<br>';
						echo '<textarea rows="10" cols="100">' .$custom_tax .'</textarea>';
						?>
					</div>
				</td>
			</tr>
		<?php
		$thecounter++;
		}
		?></table>
		</div>
		<?php
		do_action( 'cptui_manage_taxonomies_after_taxonomies_table' );
		//load footer
		cpt_footer();
	}
}

//add new custom post type / taxonomy page
function cpt_add_new() {
	global $cpt_error, $CPT_URL;

	$RETURN_URL = ( isset( $_GET['return'] ) ) ? 'action="' . cpt_check_return( esc_attr( $_GET['return'] ) ) . '"' : '';

	//check if we are editing a custom post type or creating a new one
	if ( isset( $_GET['edittype'] ) && !isset( $_GET['cpt_edit'] ) ) {
		check_admin_referer('cpt_edit_post_type');

		//get post type to edit. This will reference array index for our option.
		$editType = intval($_GET['edittype']);

		//load custom posts saved in WP
		$cpt_options = get_option('cpt_custom_post_types');

		//load custom post type values to edit
		$cpt_post_type_name     = ( isset( $cpt_options[ $editType ]["name"] ) ) ? $cpt_options[ $editType ]["name"] : null;
		$cpt_label              = ( isset( $cpt_options[ $editType ]["label"] ) ) ? $cpt_options[ $editType ]["label"] : null;
		$cpt_singular_label     = ( isset( $cpt_options[ $editType ]["singular_label"] ) ) ? $cpt_options[ $editType ]["singular_label"] : null;
		$cpt_public             = ( isset( $cpt_options[ $editType ]["public"] ) ) ? $cpt_options[ $editType ]["public"] : null;
		$cpt_showui             = ( isset( $cpt_options[ $editType ]["show_ui"] ) ) ? $cpt_options[ $editType ]["show_ui"] : null;
		$cpt_capability         = ( isset( $cpt_options[ $editType ]["capability_type"] ) ) ? $cpt_options[ $editType ]["capability_type"] : null;
		$cpt_hierarchical       = ( isset( $cpt_options[ $editType ]["hierarchical"] ) ) ? $cpt_options[ $editType ]["hierarchical"] : null;
		$cpt_rewrite            = ( isset( $cpt_options[ $editType ]["rewrite"] ) ) ? $cpt_options[ $editType ]["rewrite"] : null;
		$cpt_rewrite_slug       = ( isset( $cpt_options[ $editType ]["rewrite_slug"] ) ) ? $cpt_options[ $editType ]["rewrite_slug"] : null;
		$cpt_rewrite_withfront  = ( isset( $cpt_options[ $editType ]["rewrite_withfront"] ) ) ? $cpt_options[ $editType ]["rewrite_withfront"] : null;
		$cpt_query_var          = ( isset( $cpt_options[ $editType ]["query_var"] ) ) ? $cpt_options[ $editType ]["query_var"] : null;
		$cpt_description        = ( isset( $cpt_options[ $editType ]["description"] ) ) ? $cpt_options[ $editType ]["description"] : null;
		$cpt_menu_position      = ( isset( $cpt_options[ $editType ]["menu_position"] ) ) ? $cpt_options[ $editType ]["menu_position"] : null;
		$cpt_menu_icon			= ( isset( $cpt_options[ $editType ]["menu_icon"] ) ) ? $cpt_options[ $editType ]["menu_icon"] : null;
		$cpt_supports           = ( isset( $cpt_options[ $editType ][0] ) ) ? $cpt_options[ $editType ][0] : null;
		$cpt_taxes              = ( isset( $cpt_options[ $editType ][1] ) )? $cpt_options[ $editType ][1] : null;
		$cpt_labels             = ( isset( $cpt_options[ $editType ][2] ) ) ? $cpt_options[ $editType ][2] : null;
		$cpt_has_archive        = ( isset( $cpt_options[$editType]["has_archive"] ) ) ? $cpt_options[$editType]["has_archive"] : null;
		$cpt_exclude_from_search = ( isset( $cpt_options[$editType]["exclude_from_search"] ) ) ? $cpt_options[$editType]["exclude_from_search"] : null;
		$cpt_show_in_menu        = ( isset( $cpt_options[$editType]["show_in_menu"] ) ) ? $cpt_options[$editType]["show_in_menu"] : true;
		$cpt_show_in_menu_string = ( isset( $cpt_options[$editType]["show_in_menu_string"] ) ) ? $cpt_options[$editType]["show_in_menu_string"] : null;

		$cpt_submit_name = __( 'Save Custom Post Type', 'cpt-plugin' );
	} else {
		$cpt_submit_name = __( 'Create Custom Post Type', 'cpt-plugin' );
	}

	if ( isset( $_GET['edittax'] ) && !isset( $_GET['cpt_edit'] ) ) {
		check_admin_referer('cpt_edit_tax');

		//get post type to edit
		$editTax = intval($_GET['edittax']);

		//load custom posts saved in WP
		$cpt_options = get_option('cpt_custom_tax_types');

		//load custom taxonomy values to edit
		$cpt_tax_name = $cpt_options[$editTax]["name"];
		$cpt_tax_label = stripslashes( $cpt_options[$editTax]["label"] );
		$cpt_singular_label_tax = stripslashes( $cpt_options[$editTax]["singular_label"] );
		$cpt_tax_object_type = ( isset( $cpt_options[$editTax]["cpt_name"] ) ) ? $cpt_options[$editTax]["cpt_name"] : null;
		$cpt_tax_hierarchical = $cpt_options[$editTax]["hierarchical"];
		$cpt_tax_showui = $cpt_options[$editTax]["show_ui"];
		$cpt_tax_query_var = $cpt_options[$editTax]["query_var"];
		$cpt_tax_rewrite = $cpt_options[$editTax]["rewrite"];
		$cpt_tax_rewrite_slug = $cpt_options[$editTax]["rewrite_slug"];
		$cpt_tax_show_admin_column = $cpt_options[$editTax]["show_admin_column"];
		$cpt_tax_labels = stripslashes_deep( $cpt_options[$editTax][0] );
		$cpt_post_types = $cpt_options[$editTax][1];

		$cpt_tax_submit_name = __( 'Save Custom Taxonomy', 'cpt-plugin' );
	} else {
		$cpt_tax_submit_name = __( 'Create Custom Taxonomy', 'cpt-plugin' );
	}

	//flush rewrite rules
	flush_rewrite_rules();

	/*
	BEGIN 'ADD NEW' PAGE OUTPUT
	 */
	?>
	<div class="wrap">
		<?php
		do_action( 'cptui_before_add_new_page' );
		//check for success/error messages
		if ( isset( $_GET['cpt_msg'] ) ) : ?>

			<div id="message" class="updated">
			<?php if ( $_GET['cpt_msg'] == 1 ) {
				_e( 'Custom post type created successfully. You may need to refresh to view the new post type in the admin menu.', 'cpt-plugin' );
				echo '<a href="' . cpt_check_return( 'cpt' ) . '"> ' . __( 'Manage custom post types', 'cpt-plugin') . '</a>';
			} elseif ( $_GET['cpt_msg'] == 2 ) {
				_e('Custom taxonomy created successfully.  You may need to refresh to view the new taxonomy in the admin menu.', 'cpt-plugin' );
				echo '<a href="' . cpt_check_return( 'tax' ) . '"> ' . __( 'Manage custom taxonomies', 'cpt-plugin') . '</a>';
			} ?>
			</div>
		<?php
		else :
			if ( isset( $_GET['cpt_error'] ) ) : ?>
			<div class="error">
				<?php if ( $_GET['cpt_error'] == 1 ) {
					_e( 'Post type name is a required field.', 'cpt-plugin' );
				}
				if ( $_GET['cpt_error'] == 2 ) {
					_e( 'Taxonomy name is a required field.', 'cpt-plugin' );
				}
				if ( $_GET['cpt_error'] == 3 ) {
					_e( 'You must assign your custom taxonomy to at least one post type.', 'cpt-plugin' );
				}
				if ( $_GET['cpt_error'] == 4 ) {
					_e( 'Please doe not use quotes in your post type slug or rewrite slug.', 'cpt-plugin' );
				}
				if ( $_GET['cpt_error'] == 5 ) {
					_e( 'Please doe not use quotes in your taxonomy slug or rewrite slug.', 'cpt-plugin' );
				} ?>
			</div>
			<?php
			endif;
		endif;


		screen_icon( 'plugins' );


		do_action( 'cptui_after_add_new_page' ); ?>
	</div>
<?php
//load footer
cpt_footer();
}

function cpt_footer() {
	?>
	<hr />
	<p class="cp_about"><a target="_blank" href="http://webdevstudios.com/support/forum/custom-post-type-ui/"><?php _e( 'Custom Post Type UI', 'cpt-plugin' ); ?></a> <?php _e( 'version', 'cpt-plugin' ); echo ' '.CPT_VERSION; ?> by <a href="http://webdevstudios.com" target="_blank">WebDevStudios</a> - <a href="https://github.com/WebDevStudios/custom-post-type-ui" target="_blank"><?php _e( 'Please Report Bugs', 'cpt-plugin' ); ?></a> &middot; <?php _e( 'Follow on Twitter:', 'cpt-plugin' ); ?> <a href="http://twitter.com/williamsba" target="_blank">Brad</a> &middot; <a href="http://twitter.com/tw2113" target="_blank">Michael</a> &middot; <a href="http://twitter.com/webdevstudios" target="_blank">WebDevStudios</a> </p>
	<?php
}

function cpt_check_return( $return ) {
	global $CPT_URL;

	if ( $return == 'cpt' ) {
		return ( isset( $_GET['return'] ) ) ? admin_url( 'admin.php?page=cpt_sub_manage_cpt&return=cpt' ) : admin_url( 'admin.php?page=cpt_sub_manage_cpt' );
	} elseif ( $return == 'tax' ){
		return ( isset( $_GET['return'] ) ) ? admin_url( 'admin.php?page=cpt_sub_manage_taxonomies&return=tax' ) : admin_url( 'admin.php?page=cpt_sub_manage_taxonomies' );
	} elseif ( $return == 'add' ) {
		return admin_url( 'admin.php?page=cpt_sub_add_new' );
	} else {
		return admin_url( 'admin.php?page=cpt_sub_add_new' );
	}
}

function get_disp_boolean($booText) {
	$booText = (string) $booText;
	if ( empty( $booText ) || $booText == '0' || $booText == 'false' ) {
		return false;
	}

	return true;
}

//Return string versions of boolean values. Used in get_code
function disp_boolean($booText) {
	$booText = (string) $booText;
	if ( empty( $booText ) || $booText == '0' || $booText == 'false' ) {
		return 'false';
	}

	return 'true';
}

function cpt_help_style() { ?>
	<style>
		.help:hover {
			font-weight: bold;
		}
		.required { color: rgb(255,0,0); }
		#slidepanel2 #excerpt { height: 16px; margin-right: 4px; width: auto; }
	</style>
<?php
}


function cpt_settings_tab_menu( $page = 'post_types' ) {
	//initiate our arrays
	$tab1 = $tab2 = array( 'nav-tab' );

	//Set up variables depending on where we are.
	if ( 'post_types' == $page ) :
		$title = __( 'Manage Post Types', 'cpt-plugin' );
	else :
		$title = __( 'Manage Taxonomies', 'cpt-plugin' );
	endif;

	if ( !empty( $_GET['action'] ) && 'edit' == $_GET['action'] ) {
		$tab2[] = 'nav-tab-active';
	} else {
		$tab1[] = 'nav-tab-active';
	}

	//implode our arrays for class attributes
	$tab1 = implode( ' ', $tab1 ); $tab2 = implode( ' ', $tab2 );

	?>
	<h2 class="nav-tab-wrapper">
	<?php echo $title; ?>
	<a class="<?php echo $tab1; ?>" href="<?php echo admin_url( 'admin.php?page=cptui_manage_' . $page ); ?>"><?php _e( 'Add New', 'cpt-plugin' ); ?></a>
	<?php
	if ( 'post_types' == $page ) { ?>
		<a class="<?php echo $tab2; ?>" href="<?php echo add_query_arg( array( 'action' => 'edit' ) ); ?>"><?php _e( 'Edit Post Types', 'cpt-plugin' ); ?></a>
	<?php
	} elseif ( 'taxonomies' == $page ) { ?>
		<a class="<?php echo $tab2; ?>" href="<?php echo add_query_arg( array( 'action' => 'edit' ) ); ?>"><?php _e( 'Edit Taxonomies', 'cpt-plugin' ); ?></a>
	<?php
	} ?>
	</h2>
<?php
}
