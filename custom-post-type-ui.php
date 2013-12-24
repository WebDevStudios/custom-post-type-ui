<?php
/*
Plugin Name: Custom Post Type UI
Plugin URI: http://webdevstudios.com/plugin/custom-post-type-ui/
Description: Admin panel for creating custom post types and custom taxonomies in WordPress
Author: WebDevStudios.com
Version: 0.9
Author URI: http://webdevstudios.com/
Text Domain: cpt-plugin
License: GPLv2
*/

// Define current version constant
define( 'CPT_VERSION', '0.9' );

// Define current WordPress version constant
define( 'CPTUI_WP_VERSION', get_bloginfo( 'version' ) );

add_action( 'init', 'cpt_load_ui_class' );

//load translated strings
add_action( 'init', 'cpt_load_textdomain' );

// create custom plugin settings menu
add_action( 'admin_menu', 'cpt_plugin_menu' );

add_action( 'init', 'cpt_create_submenus' );

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

/**
 * Display footer links and plugin credits
 *
 * @since  [since]
 *
 * @return mixed  htmls
 */
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
