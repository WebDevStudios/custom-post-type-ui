<?php
/*
Plugin Name: Custom Post Type UI
Plugin URI: http://webdevstudios.com/support/wordpress-plugins/
Description: Admin panel for creating custom post types and custom taxonomies in WordPress
Author: WebDevStudios.com
Version: 0.7.2
Author URI: http://webdevstudios.com/
*/

// Define current version constant
define( 'CPT_VERSION', '0.7.2' );

// Define plugin URL constant
$CPT_URL = cpt_check_return( 'add' );

//load translated strings
load_plugin_textdomain( 'cpt-plugin', false, 'custom-post-type-ui/languages' );

// create custom plugin settings menu
add_action('admin_menu', 'cpt_plugin_menu');

//call delete post function
add_action( 'admin_init', 'cpt_delete_post_type' );

//call register settings function
add_action( 'admin_init', 'cpt_register_settings' );

//process custom taxonomies if they exist
add_action( 'init', 'cpt_create_custom_taxonomies', 0 );

//process custom taxonomies if they exist
add_action( 'init', 'cpt_create_custom_post_types', 0 );

function cpt_plugin_menu() {
	//create custom post type menu
	add_menu_page('Custom Post Types', 'Custom Post Types', 'administrator', 'cpt_main_menu', 'cpt_settings');

	//create submenu items
	add_submenu_page('cpt_main_menu', 'Add New', 'Add New', 'administrator', 'cpt_sub_add_new', 'cpt_add_new');
	add_submenu_page('cpt_main_menu', 'Manage Post Types', 'Manage Post Types', 'administrator', 'cpt_sub_manage_cpt', 'cpt_manage_cpt');
	add_submenu_page('cpt_main_menu', 'Manage Taxonomies', 'Manage Taxonomies', 'administrator', 'cpt_sub_manage_taxonomies', 'cpt_manage_taxonomies');
}

//temp fix, should do: http://planetozh.com/blog/2008/04/how-to-load-javascript-with-your-wordpress-plugin/
//only load JS if on a CPT page
if ( strpos( $_SERVER['REQUEST_URI'], 'cpt' ) > 0 ) {
	add_action( 'admin_head', 'cpt_wp_add_styles' );
}

// Add JS Scripts
function cpt_wp_add_styles() {
	
	wp_enqueue_script( 'jquery' );
	?>

        <script type="text/javascript" >
			jQuery(document).ready(function()
			{
				jQuery(".comment_button").click(function(){

					var element = jQuery(this);
					var I = element.attr("id");

					jQuery("#slidepanel"+I).slideToggle(300);
					jQuery(this).toggleClass("active");

					return false;
					
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
			$cpt_label = ( !$cpt_post_type["label"] ) ? esc_html($cpt_post_type["name"]) : esc_html($cpt_post_type["label"]);
			$cpt_singular = ( !$cpt_post_type["singular_label"] ) ? $cpt_label : esc_html($cpt_post_type["singular_label"]);
			$cpt_rewrite_slug = ( !$cpt_post_type["rewrite_slug"] ) ? esc_html($cpt_post_type["name"]) : esc_html($cpt_post_type["rewrite_slug"]);
			$cpt_menu_position = ( !$cpt_post_type["menu_position"] ) ? null : intval($cpt_post_type["menu_position"]);
			$cpt_taxonomies = ( !$cpt_post_type[1] ) ? array() : $cpt_post_type[1];
			$cpt_supports = ( !$cpt_post_type[0] ) ? array() : $cpt_post_type[0];
			//$cpt_show_in_menu  = ( !$cpt_post_type["show_in_menu_string"] ) ? null : $cpt_post_type["show_in_menu_string"];

			if ( isset ( $cpt_post_type["show_in_menu"] ) ) {
			    $cpt_show_in_menu = ( $cpt_post_type["show_in_menu"] == 1 ) ? true : false;
			    $cpt_show_in_menu = ( $cpt_post_type["show_in_menu_string"] ) ? $cpt_post_type["show_in_menu_string"] : $cpt_show_in_menu;
			}else{
			    $cpt_show_in_menu = true;
			}
			//set custom label values
			$cpt_labels['name'] = $cpt_label;
			$cpt_labels['singular_name'] = $cpt_post_type["singular_label"];

			if ( isset ( $cpt_post_type[2]["menu_name"] ) ) {
			    $cpt_labels['menu_name'] = ( $cpt_post_type[2]["menu_name"] ) ? $cpt_post_type[2]["menu_name"] : $cpt_label;
			}

			$cpt_has_archive = ( isset ( $cpt_post_type["has_archive"] ) ) ? get_disp_boolean( $cpt_post_type["has_archive"] ) : null;
			$cpt_exclude_from_search = ( isset ( $cpt_post_type["exclude_from_search"] ) ) ? get_disp_boolean( $cpt_post_type["exclude_from_search"] ) : null;

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

			register_post_type( $cpt_post_type["name"], array(	'label' => __($cpt_label),
				'public' => get_disp_boolean($cpt_post_type["public"]),
				'singular_label' => $cpt_post_type["singular_label"],
				'show_ui' => get_disp_boolean($cpt_post_type["show_ui"]),
				'has_archive' => $cpt_has_archive,
				'show_in_menu' => $cpt_show_in_menu,
				'capability_type' => $cpt_post_type["capability_type"],
				'hierarchical' => get_disp_boolean($cpt_post_type["hierarchical"]),
				'exclude_from_search' => $cpt_exclude_from_search,
				'rewrite' => array('slug' => $cpt_rewrite_slug),
				'query_var' => get_disp_boolean($cpt_post_type["query_var"]),
				'description' => esc_html($cpt_post_type["description"]),
				'menu_position' => $cpt_menu_position,
				'supports' => $cpt_supports,
				'taxonomies' => $cpt_taxonomies,
				'labels' => $cpt_labels
			) );
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
			$cpt_label = ( !$cpt_tax_type["label"] ) ? esc_html($cpt_tax_type["name"]) : esc_html($cpt_tax_type["label"]);
			$cpt_singular_label = ( !$cpt_tax_type["singular_label"] ) ? esc_html($cpt_tax_type["name"]) : esc_html($cpt_tax_type["singular_label"]);
			$cpt_rewrite_slug = ( !$cpt_tax_type["rewrite_slug"] ) ? esc_html($cpt_tax_type["name"]) : esc_html($cpt_tax_type["rewrite_slug"]);
			$cpt_post_types = ( !$cpt_tax_type[1] ) ? $cpt_tax_type["cpt_name"] : $cpt_tax_type[1];

			//set custom label values
			$cpt_labels['name'] = $cpt_label;
			$cpt_labels['singular_name'] = $cpt_tax_type["singular_label"];
			$cpt_labels['search_items'] = ( $cpt_tax_type[0]["search_items"] ) ? $cpt_tax_type[0]["search_items"] : 'Search ' .$cpt_label;
			$cpt_labels['popular_items'] = ( $cpt_tax_type[0]["popular_items"] ) ? $cpt_tax_type[0]["popular_items"] : 'Popular ' .$cpt_label;
			$cpt_labels['all_items'] = ( $cpt_tax_type[0]["all_items"] ) ? $cpt_tax_type[0]["all_items"] : 'All ' .$cpt_label;
			$cpt_labels['parent_item'] = ( $cpt_tax_type[0]["parent_item"] ) ? $cpt_tax_type[0]["parent_item"] : 'Parent ' .$cpt_singular_label;
			$cpt_labels['parent_item_colon'] = ( $cpt_tax_type[0]["parent_item_colon"] ) ? $cpt_tax_type[0]["parent_item_colon"] : 'Parent ' .$cpt_singular_label. ':';
			$cpt_labels['edit_item'] = ( $cpt_tax_type[0]["edit_item"] ) ? $cpt_tax_type[0]["edit_item"] : 'Edit ' .$cpt_singular_label;
			$cpt_labels['update_item'] = ( $cpt_tax_type[0]["update_item"] ) ? $cpt_tax_type[0]["update_item"] : 'Update ' .$cpt_singular_label;
			$cpt_labels['add_new_item'] = ( $cpt_tax_type[0]["add_new_item"] ) ? $cpt_tax_type[0]["add_new_item"] : 'Add New ' .$cpt_singular_label;
			$cpt_labels['new_item_name'] = ( $cpt_tax_type[0]["new_item_name"] ) ? $cpt_tax_type[0]["new_item_name"] : 'New ' .$cpt_singular_label. ' Name';
			$cpt_labels['separate_items_with_commas'] = ( $cpt_tax_type[0]["separate_items_with_commas"] ) ? $cpt_tax_type[0]["separate_items_with_commas"] : 'Separate ' .$cpt_label. ' with commas';
			$cpt_labels['add_or_remove_items'] = ( $cpt_tax_type[0]["add_or_remove_items"] ) ? $cpt_tax_type[0]["add_or_remove_items"] : 'Add or remove ' .$cpt_label;
			$cpt_labels['choose_from_most_used'] = ( $cpt_tax_type[0]["choose_from_most_used"] ) ? $cpt_tax_type[0]["choose_from_most_used"] : 'Choose from the most used ' .$cpt_label;

			//register our custom taxonomies
			register_taxonomy( $cpt_tax_type["name"],
				$cpt_post_types,
				array( 'hierarchical' => get_disp_boolean($cpt_tax_type["hierarchical"]),
				'label' => $cpt_label,
				'show_ui' => get_disp_boolean($cpt_tax_type["show_ui"]),
				'query_var' => get_disp_boolean($cpt_tax_type["query_var"]),
				'rewrite' => array('slug' => $cpt_rewrite_slug),
				'singular_label' => $cpt_singular_label,
				'labels' => $cpt_labels
			) );

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
		}else{
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
		}else{
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

			//save custom post types
			update_option( 'cpt_custom_post_types', $cpt_options );

			if ( isset( $_GET['return'] ) ) {
				$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
			}else{
				$RETURN_URL = $CPT_URL;
			}

			wp_redirect( $RETURN_URL );

		}

	}elseif( isset( $_POST['cpt_submit'] ) ) {
		//create a new custom post type
		
		//nonce security check
		check_admin_referer( 'cpt_add_custom_post_type' );

		//retrieve new custom post type values
		$cpt_form_fields = $_POST['cpt_custom_post_type'];

		if ( empty( $cpt_form_fields["name"] ) ) {
			if ( isset( $_GET['return'] ) ) {
				$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
			}else{
				$RETURN_URL = $CPT_URL;
			}

			wp_redirect( $RETURN_URL .'&cpt_error=1' );
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

		//save new custom post type array in the CPT option
		update_option( 'cpt_custom_post_types', $cpt_options );

		if ( isset( $_GET['return'] ) ) {
			$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
		}else{
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
			}else{
				$RETURN_URL = $CPT_URL;
			}

			wp_redirect( $RETURN_URL );

		}

	}elseif( isset( $_POST['cpt_add_tax'] ) ) {
		//create new custom taxonomy
		
		//nonce security check
		check_admin_referer( 'cpt_add_custom_taxonomy' );

		//retrieve new custom taxonomy values
		$cpt_form_fields = $_POST['cpt_custom_tax'];

		//verify required fields are filled out
		if ( empty( $cpt_form_fields["name"] ) ) {
			if ( isset( $_GET['return'] ) ) {
				$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
			}Else{
				$RETURN_URL = $CPT_URL;
			}

			wp_redirect( $RETURN_URL .'&cpt_error=2' );
			exit();
			
		}elseif ( empty( $_POST['cpt_post_types'] ) ) {
			if ( isset( $_GET['return'] ) ) {
				$RETURN_URL = cpt_check_return( esc_attr( $_GET['return'] ) );
			}else{
				$RETURN_URL = $CPT_URL;
			}

			wp_redirect( $RETURN_URL .'&cpt_error=3' );
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
		}else{
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
        <h2><?php _e('Custom Post Type UI', 'cpt-plugin'); ?></h2>
        <p><?php _e('Plugin version', 'cpt-plugin'); ?>: <?php echo CPT_VERSION; ?></p>
        <p><?php _e('WordPress version', 'cpt-plugin'); ?>: <?php echo get_bloginfo('version'); ?></p>
        <table border="0" width="60%">
	    <tr>
		<td colspan="3"><h2><?php _e( 'Help Support This Plugin!', 'cpt-plugin' ); ?></h2></td>
	    </tr>
	    <tr>
		<td width="33%"><h3><?php _e( 'PayPal Donation', 'cpt-plugin' ); ?></h3></td>
		<td width="33%"><h3><?php _e( 'Professional WordPress', 'cpt-plugin' ); ?></h3></td>
		<td width="33%"><h3><?php _e( 'Professional WordPress<br />Plugin Development', 'cpt-plugin' ); ?></h3></td>
	    </tr>
	    <tr>
		<td valign="top" width="33%">
		    <p>Please donate to the development<br />of Custom Post Type UI:
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="YJEDXPHE49Q3U">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
		    </p>
		</td>
		<td valign="top" width="33%"><a href="http://bit.ly/pro-wp" target="_blank"><img src="<?php echo plugins_url( '/images/professional-wordpress.jpg', __FILE__ ); ?>" width="200"></a><br />The leading book on WordPress design and development!<br /><strong>Second Edition coming December 2012!</strong></td>
		<td valign="top" width="33%"><a href="http://amzn.to/plugindevbook" target="_blank"><img src="<?php echo plugins_url( '/images/professional-wordpress-plugin-development.png', __FILE__ ); ?>" width="200"></a><br />Check out my new book all about plugin development!</td>
	    </tr>
	</table>

        <h3><?php _e('Frequently Asked Questions', 'cpt-plugin'); ?></h3>
        <p>Q: <strong>How can I display content from a custom post type on my website?</strong></p>
        <p>
            A: Justin Tadlock has written some great posts on the topic:<br />
            <a href="http://justintadlock.com/archives/2010/02/02/showing-custom-post-types-on-your-home-blog-page" target="_blank">Showing Custom Post Types on your Home Page</a><br />
            <a href="http://justintadlock.com/archives/2010/04/29/custom-post-types-in-wordpress" target="_blank">Custom Post Types in WordPress</a>
        </p>
        <p>Q: <strong>How can I add custom meta boxes to my custom post types?</strong></p>
		<p>A: The More Fields plugin does a great job at creating custom meta boxes and fully supports custom post types: <a href="http://wordpress.org/extend/plugins/more-fields/" target="_blank">http://wordpress.org/extend/plugins/more-fields/</a></p>
		<p>Q: <strong>I changed my custom post type name and now I can't get to my posts</strong></p>
        <p>A: You can either change the custom post type name back to the original name or try the Post Type Switcher plugin: <a href="http://wordpress.org/extend/plugins/post-type-switcher/" target="_blank">http://wordpress.org/extend/plugins/post-type-switcher/</a></p>
        <h3><?php _e('Slightly Outdated Demo Video', 'cpt-plugin'); ?></h3>
        <object width="400" height="300"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=10187055&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=ff9933&amp;fullscreen=1" /><embed src="http://vimeo.com/moogaloop.swf?clip_id=10187055&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=ff9933&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="400" height="300"></embed></object>
        <h3><?php _e( 'Latest WebDevStudios.com News', 'cpt-plugin' ); ?></h3>
        <?php
        echo '<div class="cp-rss-widget">';

        wp_widget_rss_output( array(
            'url' => esc_url( 'http://webdevstudios.com/feed/' ),
            'title' => __( 'WebDevStudios.com News', 'cpt-plugin' ),
            'items' => 5,
            'show_summary' => 1,
            'show_author' => 0,
            'show_date' => 1
        ) );

        echo '</div>';
        ?>
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
//check for success/error messages
If (isset($_GET['cpt_msg']) && $_GET['cpt_msg']=='del') { ?>
    <div id="message" class="updated">
    	<?php _e('Custom post type deleted successfully', 'cpt-plugin'); ?>
    </div>
    <?php
}
?>
<?php screen_icon( 'plugins' ); ?>
<h2><?php _e('Manage Custom Post Types', 'cpt-plugin') ?></h2>
<p><?php _e('Deleting custom post types does <strong>NOT</strong> delete any content added to those post types.  You can easily recreate your post types and the content will still exist.', 'cpt-plugin') ?></p>
<?php
	$cpt_post_types = get_option('cpt_custom_post_types');
	If (is_array($cpt_post_types)) {
		?>
        <table width="100%" class="widefat">
            <thead>
                <tr>
                    <th><?php _e('Action', 'cpt-plugin');?></th>
                    <th><?php _e('Name', 'cpt-plugin');?></th>
                    <th><?php _e('Label', 'cpt-plugin');?></th>
                    <th><?php _e('Public', 'cpt-plugin');?></th>
                    <th><?php _e('Show UI', 'cpt-plugin');?></th>
                    <th><?php _e('Hierarchical', 'cpt-plugin');?></th>
                    <th><?php _e('Rewrite', 'cpt-plugin');?></th>
                    <th><?php _e('Rewrite Slug', 'cpt-plugin');?></th>
                    <th><?php _e('Total Published', 'cpt-plugin');?></th>
                    <th><?php _e('Total Drafts', 'cpt-plugin');?></th>
                    <th><?php _e('Supports', 'cpt-plugin');?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?php _e('Action', 'cpt-plugin');?></th>
                    <th><?php _e('Name', 'cpt-plugin');?></th>
                    <th><?php _e('Label', 'cpt-plugin');?></th>
                    <th><?php _e('Public', 'cpt-plugin');?></th>
                    <th><?php _e('Show UI', 'cpt-plugin');?></th>
                    <th><?php _e('Hierarchical', 'cpt-plugin');?></th>
                    <th><?php _e('Rewrite', 'cpt-plugin');?></th>
                    <th><?php _e('Rewrite Slug', 'cpt-plugin');?></th>
                    <th><?php _e('Total Published', 'cpt-plugin');?></th>
                    <th><?php _e('Total Drafts', 'cpt-plugin');?></th>
                    <th><?php _e('Supports', 'cpt-plugin');?></th>
                </tr>
            </tfoot>
        <?php
		$thecounter=0;
		$cpt_names = array();
		foreach ($cpt_post_types as $cpt_post_type) {

			$del_url = cpt_check_return( 'cpt' ) .'&deltype=' .$thecounter .'&return=cpt';
			$del_url = ( function_exists('wp_nonce_url') ) ? wp_nonce_url($del_url, 'cpt_delete_post_type') : $del_url;

			$edit_url = $MANAGE_URL .'&edittype=' .$thecounter .'&return=cpt';
			$edit_url = ( function_exists('wp_nonce_url') ) ? wp_nonce_url($edit_url, 'cpt_edit_post_type') : $edit_url;

			$cpt_counts = wp_count_posts($cpt_post_type["name"]);

			$rewrite_slug = ( $cpt_post_type["rewrite_slug"] ) ? $cpt_post_type["rewrite_slug"] : $cpt_post_type["name"];
		?>
        	<tr>
            	<td valign="top"><a href="<?php echo $del_url; ?>">Delete</a> / <a href="<?php echo $edit_url; ?>">Edit</a> / <a href="#" class="comment_button" id="<?php echo $thecounter; ?>">Get Code</a></td>
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
					If (is_array($cpt_post_type[0])) {
						foreach ($cpt_post_type[0] as $cpt_supports) {
							echo $cpt_supports .'<br />';
						}
					}
					?>
                </td>
            </tr>
        	<tr>
            	<td colspan="12">
                    <div style="display:none;" id="slidepanel<?php echo $thecounter; ?>">
                        <?php
                        //display register_post_type code
						$custom_post_type='';
						$cpt_support_array='';
						$cpt_tax_array='';

						$cpt_label = ( !$cpt_post_type["label"] ) ? esc_html($cpt_post_type["name"]) : esc_html($cpt_post_type["label"]);
						$cpt_singular = ( !$cpt_post_type["singular_label"] ) ? $cpt_label : esc_html($cpt_post_type["singular_label"]);
						$cpt_rewrite_slug = ( !$cpt_post_type["rewrite_slug"] ) ? esc_html($cpt_post_type["name"]) : esc_html($cpt_post_type["rewrite_slug"]);
						$cpt_menu_position = ( !$cpt_post_type["menu_position"] ) ? null : intval($cpt_post_type["menu_position"]);
						$cpt_show_in_menu = ( $cpt_post_type["show_in_menu"] == 1 ) ? 'true' : 'false';
						$cpt_show_in_menu = ( $cpt_post_type["show_in_menu_string"] ) ? '\''.$cpt_post_type["show_in_menu_string"].'\'' : $cpt_show_in_menu;

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
							foreach ( $cpt_post_type[0] as $cpt_supports ) {
							//build supports variable
								$cpt_support_array .= '\''.$cpt_supports.'\',';
							}
						}

						if( is_array( $cpt_post_type[1] ) ) {
							foreach ( $cpt_post_type[1] as $cpt_taxes ) {
							//build taxonomies variable
								$cpt_tax_array .= '\''.$cpt_taxes.'\',';
							}
						}

						$custom_post_type = 'register_post_type(\'' .$cpt_post_type["name"]. '\', array(	\'label\' => \''.__($cpt_label).'\',';
						$custom_post_type .= 	'\'description\' => \''. $cpt_post_type["description"].'\',';
						$custom_post_type .= 	'\'public\' => '.disp_boolean($cpt_post_type["public"]).',';
						$custom_post_type .= 	'\'show_ui\' => '.disp_boolean($cpt_post_type["show_ui"]).',';
						$custom_post_type .= 	'\'show_in_menu\' => ' .$cpt_show_in_menu .',';
						$custom_post_type .= 	'\'capability_type\' => \''.$cpt_post_type["capability_type"].'\',';
						$custom_post_type .= 	'\'hierarchical\' => '.disp_boolean($cpt_post_type["hierarchical"]).',';
						$custom_post_type .= 	'\'rewrite\' => array(\'slug\' => \'' .$cpt_post_type["rewrite_slug"]. '\'),';
						$custom_post_type .= 	'\'query_var\' => '. disp_boolean($cpt_post_type["query_var"]).',';

						if ( $cpt_post_type["has_archive"] ) {
						    $custom_post_type .= '\'has_archive\' => '. disp_boolean( $cpt_post_type["has_archive"] ).',';
						}

						if ( isset( $cpt_post_type["exclude_from_search"] ) ) {
						    $custom_post_type .= '\'exclude_from_search\' => '. disp_boolean( $cpt_post_type["exclude_from_search"] ).',';
						}
						
						if ( $cpt_post_type["menu_position"] ) {
						    $custom_post_type .= '\'menu_position\' => '. $cpt_post_type["menu_position"].',';
						}

						$custom_post_type .= 	'\'supports\' => array(' .$cpt_support_array.'),';

						if ( $cpt_tax_array ) {
							$custom_post_type .= 	'\'taxonomies\' => array(' .$cpt_tax_array.'),';
						}

						if ( $cpt_labels ) {
							$custom_post_type .= 	'\'labels\' => '.var_export( $cpt_labels, true ).',';
						}

						$custom_post_type .= ') );';

						echo _e('Place the below code in your themes functions.php file to manually create this custom post type','cpt-plugin').'<br>';
						echo _e('This is a <strong>BETA</strong> feature.  Please <a href="http://webdevstudios.com/support/forum/custom-post-type-ui/">report bugs</a>.','cpt-plugin').'<br>';
						echo '<textarea rows="5" cols="100">' .$custom_post_type .'</textarea>';

                        ?>
                    </div>
				</td>
            </tr>
		<?php
		$thecounter++;
		$cpt_names[] = strtolower( $cpt_post_type["name"] );
		}

            $args=array(
              'public'   => true,
              '_builtin' => false
            );
            $output = 'objects'; // or objects
            $post_types=get_post_types($args,$output);
            if ( $post_types ) {

                ?></table>
                <?php screen_icon( 'plugins' ); ?>
                <h2><?php _e('Additional Custom Post Types', 'cpt-plugin') ?></h2>
                <p><?php _e('The custom post types below are registered in WordPress but were not created by the Custom Post Type UI Plugin.', 'cpt-plugin') ?></p>
                    <?php
                    foreach ($post_types  as $post_type ) {

                          if ( !in_array( strtolower( $post_type->name ), $cpt_names ) ) {
                            if ( isset( $cpt_first ) && !$cpt_first ) {
                                ?>
                                <table width="100%" class="widefat">
                                    <thead>
                                        <tr>
                                            <th><?php _e('Name', 'cpt-plugin');?></th>
                                            <th><?php _e('Label', 'cpt-plugin');?></th>
                                            <th><?php _e('Singular Label', 'cpt-plugin');?></th>
                                            <th><?php _e('Public', 'cpt-plugin');?></th>
                                            <th><?php _e('Show UI', 'cpt-plugin');?></th>
                                            <th><?php _e('Capability Type', 'cpt-plugin');?></th>
                                            <th><?php _e('Hierarchical', 'cpt-plugin');?></th>
                                            <th><?php _e('Rewrite Slug', 'cpt-plugin');?></th>
                                            <th><?php _e('Rewrite Slug', 'cpt-plugin');?></th>
                                            <th><?php _e('Query Var', 'cpt-plugin');?></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th><?php _e('Name', 'cpt-plugin');?></th>
                                            <th><?php _e('Label', 'cpt-plugin');?></th>
                                            <th><?php _e('Singular Label', 'cpt-plugin');?></th>
                                            <th><?php _e('Public', 'cpt-plugin');?></th>
                                            <th><?php _e('Show UI', 'cpt-plugin');?></th>
                                            <th><?php _e('Capability Type', 'cpt-plugin');?></th>
                                            <th><?php _e('Hierarchical', 'cpt-plugin');?></th>
                                            <th><?php _e('Rewrite Slug', 'cpt-plugin');?></th>
                                            <th><?php _e('Rewrite Slug', 'cpt-plugin');?></th>
                                            <th><?php _e('Query Var', 'cpt-plugin');?></th>
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
                                <td valign="top"><?php echo $post_type->singular_label; ?></td>
                                <td valign="top"><?php echo disp_boolean($post_type->public); ?></td>
                                <td valign="top"><?php echo disp_boolean($post_type->show_ui); ?></td>
                                <td valign="top"><?php echo $post_type->capability_type; ?></td>
                                <td valign="top"><?php echo disp_boolean($post_type->hierarchical); ?></td>
                                <td valign="top"><?php echo disp_boolean($post_type->rewrite); ?></td>
                                <td valign="top"><?php echo $rewrite_slug; ?></td>
                                <td valign="top"><?php echo disp_boolean($post_type->query_var); ?></td>
                            </tr>
                            <?php
                        }
                  }
            }

            if ( isset($cpt_first) && !$cpt_first ) {
                echo '<tr><td><strong>';
                _e( 'No additional post types found', 'cpt-plugin' );
                echo '</strong></td></tr>';
            }
            ?>
        </table>

		</div><?php
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
//check for success/error messages
If (isset($_GET['cpt_msg']) && $_GET['cpt_msg']=='del') { ?>
    <div id="message" class="updated">
    	<?php _e('Custom taxonomy deleted successfully', 'cpt-plugin'); ?>
    </div>
    <?php
}
?>
<?php screen_icon( 'plugins' ); ?>
<h2><?php _e('Manage Custom Taxonomies', 'cpt-plugin') ?></h2>
<p><?php _e('Deleting custom taxonomies does <strong>NOT</strong> delete any content added to those taxonomies.  You can easily recreate your taxonomies and the content will still exist.', 'cpt-plugin') ?></p>
<?php
	$cpt_tax_types = get_option('cpt_custom_tax_types');

	If (is_array($cpt_tax_types)) {
		?>
        <table width="100%" class="widefat">
            <thead>
                <tr>
            	<th><?php _e('Action', 'cpt-plugin');?></th>
            	<th><?php _e('Name', 'cpt-plugin');?></th>
                <th><?php _e('Label', 'cpt-plugin');?></th>
                <th><?php _e('Singular Label', 'cpt-plugin');?></th>
                <th><?php _e('Attached Post Types', 'cpt-plugin');?></th>
                <th><?php _e('Hierarchical', 'cpt-plugin');?></th>
                <th><?php _e('Show UI', 'cpt-plugin');?></th>
                <th><?php _e('Rewrite', 'cpt-plugin');?></th>
                <th><?php _e('Rewrite Slug', 'cpt-plugin');?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
            	<th><?php _e('Action', 'cpt-plugin');?></th>
            	<th><?php _e('Name', 'cpt-plugin');?></th>
                <th><?php _e('Label', 'cpt-plugin');?></th>
                <th><?php _e('Singular Label', 'cpt-plugin');?></th>
                <th><?php _e('Attached Post Types', 'cpt-plugin');?></th>
                <th><?php _e('Hierarchical', 'cpt-plugin');?></th>
                <th><?php _e('Show UI', 'cpt-plugin');?></th>
                <th><?php _e('Rewrite', 'cpt-plugin');?></th>
                <th><?php _e('Rewrite Slug', 'cpt-plugin');?></th>
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
            	<td valign="top"><a href="<?php echo $del_url; ?>">Delete</a> / <a href="<?php echo $edit_url; ?>">Edit</a> / <a href="#" class="comment_button" id="<?php echo $thecounter; ?>">Get Code</a></td>
            	<td valign="top"><?php echo stripslashes($cpt_tax_type["name"]); ?></td>
                <td valign="top"><?php echo stripslashes($cpt_tax_type["label"]); ?></td>
                <td valign="top"><?php echo stripslashes($cpt_tax_type["singular_label"]); ?></td>
                <td valign="top">
				<?php
				if ( isset( $cpt_tax_type["cpt_name"] ) ) {
					echo stripslashes($cpt_tax_type["cpt_name"]);
				}elseif ( is_array( $cpt_tax_type[1] ) ) {
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
                        $cpt_tax_types = get_option('cpt_custom_tax_types');
                        $custom_tax = '';

                        //check if option value is an Array before proceeding
                        If (is_array($cpt_tax_types)) {
                            //foreach ($cpt_tax_types as $cpt_tax_type) {

                                If (!$cpt_tax_type["label"]) {
                                    $cpt_label = esc_html($cpt_tax_type["name"]);
                                }Else{
                                    $cpt_label = esc_html($cpt_tax_type["label"]);
                                }

                                //check if singular label was filled out
                                If (!$cpt_tax_type["singular_label"]) {
                                    $cpt_singular_label = esc_html($cpt_tax_type["name"]);
                                }Else{
                                    $cpt_singular_label = esc_html($cpt_tax_type["singular_label"]);
                                }

				$cpt_post_types = ( !$cpt_tax_type[1] ) ? $cpt_tax_type["cpt_name"] : var_export( $cpt_tax_type[1], true );

                                //register our custom taxonomies
                                $custom_tax = 'register_taxonomy(\'' .$cpt_tax_type["name"]. '\',';
                                $custom_tax .= $cpt_post_types .',';
                                $custom_tax .= 'array( \'hierarchical\' => '.disp_boolean($cpt_tax_type["hierarchical"]).', ';
                                $custom_tax .= 	'\'label\' => \''.$cpt_label.'\',';
                                $custom_tax .= 	'\'show_ui\' => '.disp_boolean($cpt_tax_type["show_ui"]).',';
                                $custom_tax .= 	'\'query_var\' => '. disp_boolean($cpt_tax_type["query_var"]).',';
				$custom_tax .= 	'\'rewrite\' => array(\'slug\' => \'' .$cpt_tax_type["rewrite_slug"]. '\'),';
                                $custom_tax .= 	'\'singular_label\' => \''.$cpt_singular_label.'\'';
                                $custom_tax .= ') );';

				echo '<br>';
				echo _e('Place the below code in your themes functions.php file to manually create this custom taxonomy','cpt-plugin').'<br>';
				echo _e('This is a <strong>BETA</strong> feature.  Please <a href="http://webdevstudios.com/support/forum/custom-post-type-ui/">report bugs</a>.','cpt-plugin').'<br>';
                                echo '<textarea rows="5" cols="100">' .$custom_tax .'</textarea>';

                            //}
                        }
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
		//load footer
		cpt_footer();
	}
}

//add new custom post type / taxonomy page
function cpt_add_new() {
	global $cpt_error, $CPT_URL;

	If (isset($_GET['return'])) {
		$RETURN_URL = 'action="' .cpt_check_return(esc_attr($_GET['return'])). '"';
	}Else{
		$RETURN_URL = '';
	}


//check if we are editing a custom post type or creating a new one
If (isset($_GET['edittype']) && !isset($_GET['cpt_edit'])) {
	check_admin_referer('cpt_edit_post_type');

	//get post type to edit
	$editType = intval($_GET['edittype']);

	//load custom posts saved in WP
	$cpt_options = get_option('cpt_custom_post_types');

	//load custom post type values to edit
	$cpt_post_type_name = $cpt_options[$editType]["name"];
	$cpt_label = $cpt_options[$editType]["label"];
	$cpt_singular_label = $cpt_options[$editType]["singular_label"];
	$cpt_public = $cpt_options[$editType]["public"];
	$cpt_showui = $cpt_options[$editType]["show_ui"];
	$cpt_capability = $cpt_options[$editType]["capability_type"];
	$cpt_hierarchical = $cpt_options[$editType]["hierarchical"];
	$cpt_rewrite = $cpt_options[$editType]["rewrite"];
	$cpt_rewrite_slug = $cpt_options[$editType]["rewrite_slug"];
	$cpt_query_var = $cpt_options[$editType]["query_var"];
	$cpt_description = $cpt_options[$editType]["description"];
	$cpt_menu_position = $cpt_options[$editType]["menu_position"];
	$cpt_supports = $cpt_options[$editType][0];
	$cpt_taxes = $cpt_options[$editType][1];
	$cpt_labels = $cpt_options[$editType][2];
	$cpt_has_archive = ( isset( $cpt_options[$editType]["has_archive"] ) ) ? $cpt_options[$editType]["has_archive"] : null;
	$cpt_exclude_from_search = ( isset( $cpt_options[$editType]["exclude_from_search"] ) ) ? $cpt_options[$editType]["exclude_from_search"] : null;
	$cpt_show_in_menu = ( isset( $cpt_options[$editType]["show_in_menu"] ) ) ? $cpt_options[$editType]["show_in_menu"] : null;
	$cpt_show_in_menu_string = ( isset( $cpt_options[$editType]["show_in_menu_string"] ) ) ? $cpt_options[$editType]["show_in_menu_string"] : null;

	$cpt_submit_name = 'Save Custom Post Type';
}Else{
	$cpt_submit_name = 'Create Custom Post Type';
}

If (isset($_GET['edittax']) && !isset($_GET['cpt_edit'])) {
	check_admin_referer('cpt_edit_tax');

	//get post type to edit
	$editTax = intval($_GET['edittax']);

	//load custom posts saved in WP
	$cpt_options = get_option('cpt_custom_tax_types');

	//load custom post type values to edit
	$cpt_tax_name = $cpt_options[$editTax]["name"];
	$cpt_tax_label = $cpt_options[$editTax]["label"];
	$cpt_singular_label = $cpt_options[$editTax]["singular_label"];
	$cpt_tax_object_type = ( isset( $cpt_options[$editTax]["cpt_name"] ) ) ? $cpt_options[$editTax]["cpt_name"] : null;
	$cpt_tax_hierarchical = $cpt_options[$editTax]["hierarchical"];
	$cpt_tax_showui = $cpt_options[$editTax]["show_ui"];
	$cpt_tax_query_var = $cpt_options[$editTax]["query_var"];
	$cpt_tax_rewrite = $cpt_options[$editTax]["rewrite"];
	$cpt_tax_rewrite_slug = $cpt_options[$editTax]["rewrite_slug"];
	$cpt_tax_labels = $cpt_options[$editTax][0];
	$cpt_post_types = $cpt_options[$editTax][1];

	$cpt_tax_submit_name = 'Save Custom Taxonomy';
}Else{
	$cpt_tax_submit_name = 'Create Custom Taxonomy';
}

//flush rewrite rules
flush_rewrite_rules();

?><div class="wrap"><?php
//check for success/error messages
If (isset($_GET['cpt_msg']) && $_GET['cpt_msg']==1) { ?>
    <div id="message" class="updated">
    	<?php _e('Custom post type created successfully.  You may need to refresh to view the new post type.  <a href="' .cpt_check_return( 'cpt' ) .'">Manage custom post types</a>', 'cpt-plugin'); ?>
    </div>
    <?php
}elseIf (isset($_GET['cpt_msg']) && $_GET['cpt_msg']==2) { ?>
    <div id="message" class="updated">
    	<?php _e('Custom taxonomy created successfully.  You may need to refresh to view the new taxonomy.  <a href="' .cpt_check_return( 'tax' ) .'">Manage custom taxonomies</a>', 'cpt-plugin'); ?>
    </div>
    <?php
}else{
	If (isset($_GET['cpt_error']) && $_GET['cpt_error']==1) { ?>
		<div class="error">
			<?php _e('Post type name is a required field.', 'cpt-plugin'); ?>
		</div>
	<?php }ElseIf (isset($_GET['cpt_error']) && $_GET['cpt_error']==2) { ?>
		<div class="error">
			<?php _e('Taxonomy name is a required field.', 'cpt-plugin'); ?>
		</div>
	<?php }ElseIf (isset($_GET['cpt_error']) && $_GET['cpt_error']==3) { ?>
		<div class="error">
			<?php _e('You must assign your custom taxonomy to at least one post type.', 'cpt-plugin'); ?>
		</div>
	<?php }
}
?>
<?php screen_icon( 'plugins' ); ?>
<?php if ( isset( $_GET['edittype'] ) || isset( $_GET['edittax'] ) ) { ?>
    <h2><?php _e('Edit Custom Post Type or Taxonomy', 'cpt-plugin') ?> &middot; <a href="<?php echo cpt_check_return( 'add' ); ?>"><?php _e('Reset', 'cpt-plugin');?></a></h2>
<?php }else{ ?>
    <h2><?php _e('Create New Custom Post Type or Taxonomy', 'cpt-plugin') ?> &middot; <a href="<?php echo cpt_check_return( 'add' ); ?>"><?php _e('Reset', 'cpt-plugin');?></a></h2>
<?php } ?>
<table border="0" cellspacing="10" class="widefat">
	<tr>
    	<td width="50%" valign="top">
            <p><?php _e('If you are unfamiliar with the options below only fill out the <strong>Post Type Name</strong> and <strong>Label</strong> fields and check which meta boxes to support.  The other settings are set to the most common defaults for custom post types.', 'cpt-plugin'); ?></p>
            <form method="post" <?php echo $RETURN_URL; ?>>
                <?php 
				if ( function_exists( 'wp_nonce_field' ) )
                    wp_nonce_field( 'cpt_add_custom_post_type' ); 
				?>
                <?php if ( isset( $_GET['edittype'] ) ) { ?>
                <input type="hidden" name="cpt_edit" value="<?php echo esc_attr( $editType ); ?>" />
                <?php } ?>
                <table class="form-table">
                    <tr valign="top">
						<th scope="row"><?php _e('Post Type Name', 'cpt-plugin') ?> <span style="color:red;">*</span></th>
						<td><input type="text" name="cpt_custom_post_type[name]" tabindex="1" value="<?php If (isset($cpt_post_type_name)) { echo esc_attr($cpt_post_type_name); } ?>" maxlength="20" onblur="this.value=this.value.toLowerCase()" /> <a href="#" title="The post type name.  Used to retrieve custom post type content.  Should be short and sweet" style="cursor: help;">?</a> (e.g. movies)
						<br />
						<p><strong><?php _e( 'Max 20 characters, can not contain capital letters or spaces', 'cpt-plugin' ); ?></strong></p>
						</td>
                    </tr>
					
                    <tr valign="top">
                    <th scope="row"><?php _e('Label', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_custom_post_type[label]" tabindex="2" value="<?php If (isset($cpt_label)) { echo esc_attr($cpt_label); } ?>" /> <a href="#" title="Post type label.  Used in the admin menu for displaying post types." style="cursor: help;">?</a> (e.g. Movies)</td>
                    </tr>

                   <tr valign="top">
                    <th scope="row"><?php _e('Singular Label', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_custom_post_type[singular_label]" tabindex="3" value="<?php If (isset($cpt_singular_label)) { echo esc_attr($cpt_singular_label); } ?>" /> <a href="#" title="Custom Post Type Singular label.  Used in WordPress when a singular label is needed." style="cursor: help;">?</a> (e.g. Movie)</td>

                    </tr>

                   <tr valign="top">
                    <th scope="row"><?php _e('Description', 'cpt-plugin') ?></th>
                    <td><textarea name="cpt_custom_post_type[description]" tabindex="4" rows="4" cols="40"><?php If (isset($cpt_description)) { echo esc_attr($cpt_description); } ?></textarea><a href="#" title="Custom Post Type Description.  Describe what your custom post type is used for." style="cursor: help;">?</a></td>
                    </tr>

            		<tr valign="top">
                        <td colspan="2">
                            <p align="center">
                                <?php echo '<a href="#" class="comment_button" id="1">' . __('Advanced Label Options', 'cpt-plugin') . '</a>'; ?> &middot;
                                <?php echo '<a href="#" class="comment_button" id="2">' . __('Advanced Options', 'cpt-plugin') . '</a>'; ?>
                            </p>
                        </td>
                    </tr>

            	</table>

		<div style="display:none;" id="slidepanel1">
                <p><?php _e('Below are the advanced label options for custom post types.  If you are unfamiliar with these labels the plugin will automatically create labels based off of your custom post type name', 'cpt-plugin'); ?></p>
                <table class="form-table">

                    <tr valign="top">
                    <th scope="row"><?php _e('Menu Name', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_labels[menu_name]" tabindex="2" value="<?php If (isset($cpt_labels["menu_name"])) { echo esc_attr($cpt_labels["menu_name"]); } ?>" /> <a href="#" title="Custom menu name for your custom post type." style="cursor: help;">?</a> (e.g. My Movies)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Add New', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_labels[add_new]" tabindex="2" value="<?php If (isset($cpt_labels["add_new"])) { echo esc_attr($cpt_labels["add_new"]); } ?>" /> <a href="#" title="Post type label.  Used in the admin menu for displaying post types." style="cursor: help;">?</a> (e.g. Add New)</td>
                    </tr>

					<tr valign="top">
                    <th scope="row"><?php _e('Add New Item', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_labels[add_new_item]" tabindex="2" value="<?php If (isset($cpt_labels["add_new_item"])) { echo esc_attr($cpt_labels["add_new_item"]); } ?>" /> <a href="#" title="Post type label.  Used in the admin menu for displaying post types." style="cursor: help;">?</a> (e.g. Add New Movie)</td>
                    </tr>

					<tr valign="top">
                    <th scope="row"><?php _e('Edit', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_labels[edit]" tabindex="2" value="<?php If (isset($cpt_labels["edit"])) { echo esc_attr($cpt_labels["edit"]); } ?>" /> <a href="#" title="Post type label.  Used in the admin menu for displaying post types." style="cursor: help;">?</a> (e.g. Edit)</td>
                    </tr>

					<tr valign="top">
                    <th scope="row"><?php _e('Edit Item', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_labels[edit_item]" tabindex="2" value="<?php If (isset($cpt_labels["edit_item"])) { echo esc_attr($cpt_labels["edit_item"]); } ?>" /> <a href="#" title="Post type label.  Used in the admin menu for displaying post types." style="cursor: help;">?</a> (e.g. Edit Movie)</td>
                    </tr>

					<tr valign="top">
                    <th scope="row"><?php _e('New Item', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_labels[new_item]" tabindex="2" value="<?php If (isset($cpt_labels["new_item"])) { echo esc_attr($cpt_labels["new_item"]); } ?>" /> <a href="#" title="Post type label.  Used in the admin menu for displaying post types." style="cursor: help;">?</a> (e.g. New Movie)</td>
                    </tr>

					<tr valign="top">
                    <th scope="row"><?php _e('View', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_labels[view]" tabindex="2" value="<?php If (isset($cpt_labels["view"])) { echo esc_attr($cpt_labels["view"]); } ?>" /> <a href="#" title="Post type label.  Used in the admin menu for displaying post types." style="cursor: help;">?</a> (e.g. View Movie)</td>
                    </tr>

					<tr valign="top">
                    <th scope="row"><?php _e('View Item', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_labels[view_item]" tabindex="2" value="<?php If (isset($cpt_labels["view_item"])) { echo esc_attr($cpt_labels["view_item"]); } ?>" /> <a href="#" title="Post type label.  Used in the admin menu for displaying post types." style="cursor: help;">?</a> (e.g. View Movie)</td>
                    </tr>

					<tr valign="top">
                    <th scope="row"><?php _e('Search Items', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_labels[search_items]" tabindex="2" value="<?php If (isset($cpt_labels["search_items"])) { echo esc_attr($cpt_labels["search_items"]); } ?>" /> <a href="#" title="Post type label.  Used in the admin menu for displaying post types." style="cursor: help;">?</a> (e.g. Search Movies)</td>
                    </tr>

					<tr valign="top">
                    <th scope="row"><?php _e('Not Found', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_labels[not_found]" tabindex="2" value="<?php If (isset($cpt_labels["not_found"])) { echo esc_attr($cpt_labels["not_found"]); } ?>" /> <a href="#" title="Post type label.  Used in the admin menu for displaying post types." style="cursor: help;">?</a> (e.g. No Movies Found)</td>
                    </tr>

					<tr valign="top">
                    <th scope="row"><?php _e('Not Found in Trash', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_labels[not_found_in_trash]" tabindex="2" value="<?php If (isset($cpt_labels["not_found_in_trash"])) { echo esc_attr($cpt_labels["not_found_in_trash"]); } ?>" /> <a href="#" title="Post type label.  Used in the admin menu for displaying post types." style="cursor: help;">?</a> (e.g. No Movies found in Trash)</td>
                    </tr>

					<tr valign="top">
                    <th scope="row"><?php _e('Parent', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_labels[parent]" tabindex="2" value="<?php If (isset($cpt_labels["parent"])) { echo esc_attr($cpt_labels["parent"]); } ?>" /> <a href="#" title="Post type label.  Used in the admin menu for displaying post types." style="cursor: help;">?</a> (e.g. Parent Movie)</td>
                    </tr>

                </table>
                </div>

            	<div style="display:none;" id="slidepanel2">
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row"><?php _e('Public', 'cpt-plugin') ?></th>
                    <td>
                        <SELECT name="cpt_custom_post_type[public]" tabindex="4">
                            <OPTION value="0" <?php If (isset($cpt_public)) { If ($cpt_public == 0 && $cpt_public != '') { echo 'selected="selected"'; } } ?>>False</OPTION>
                            <OPTION value="1" <?php If (isset($cpt_public)) { If ($cpt_public == 1 || is_null($cpt_public)) { echo 'selected="selected"'; } }Else{ echo 'selected="selected"'; } ?>>True</OPTION>
                        </SELECT> <a href="#" title="Whether posts of this type should be shown in the admin UI" style="cursor: help;">?</a> (default: True)
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Show UI', 'cpt-plugin') ?></th>
                    <td>
                        <SELECT name="cpt_custom_post_type[show_ui]" tabindex="5">
                            <OPTION value="0" <?php If (isset($cpt_showui)) { If ($cpt_showui == 0 && $cpt_showui != '') { echo 'selected="selected"'; } } ?>>False</OPTION>
                            <OPTION value="1" <?php If (isset($cpt_showui)) { If ($cpt_showui == 1 || is_null($cpt_showui)) { echo 'selected="selected"'; } }Else{ echo 'selected="selected"'; } ?>>True</OPTION>
                        </SELECT> <a href="#" title="Whether to generate a default UI for managing this post type" style="cursor: help;">?</a> (default: True)
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Has Archive', 'cpt-plugin') ?></th>
                    <td>
                        <SELECT name="cpt_custom_post_type[has_archive]" tabindex="6">
                            <OPTION value="0" <?php If (isset($cpt_has_archive)) { If ($cpt_has_archive == 0) { echo 'selected="selected"'; } }Else{ echo 'selected="selected"'; } ?>>False</OPTION>
                            <OPTION value="1" <?php If (isset($cpt_has_archive)) { If ($cpt_has_archive == 1) { echo 'selected="selected"'; } } ?>>True</OPTION>
                        </SELECT> <a href="#" title="Whether the post type will have a post type archive page" style="cursor: help;">?</a> (default: False)
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Exclude From Search', 'cpt-plugin') ?></th>
                    <td>
                        <SELECT name="cpt_custom_post_type[exclude_from_search]" tabindex="6">
                            <OPTION value="0" <?php If (isset($cpt_exclude_from_search)) { If ($cpt_exclude_from_search == 0) { echo 'selected="selected"'; } }Else{ echo 'selected="selected"'; } ?>>False</OPTION>
                            <OPTION value="1" <?php If (isset($cpt_exclude_from_search)) { If ($cpt_exclude_from_search == 1) { echo 'selected="selected"'; } } ?>>True</OPTION>
                        </SELECT> <a href="#" title="Whether the post type will be searchable" style="cursor: help;">?</a> (default: False)
                    </td>
                    </tr>
					
                    <tr valign="top">
                    <th scope="row"><?php _e('Capability Type', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_custom_post_type[capability_type]" tabindex="6" value="post" value="<?php if ( isset( $cpt_capability ) ) { echo esc_attr( $cpt_capability ); } ?>" /> <a href="#" title="The post type to use for checking read, edit, and delete capabilities" style="cursor: help;">?</a></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Hierarchical', 'cpt-plugin') ?></th>
                    <td>
                        <SELECT name="cpt_custom_post_type[hierarchical]" tabindex="7">
                            <OPTION value="0" <?php If (isset($cpt_hierarchical)) { If ($cpt_hierarchical == 0) { echo 'selected="selected"'; } }Else{ echo 'selected="selected"'; } ?>>False</OPTION>
                            <OPTION value="1" <?php If (isset($cpt_hierarchical)) { If ($cpt_hierarchical == 1) { echo 'selected="selected"'; } } ?>>True</OPTION>
                        </SELECT> <a href="#" title="Whether the post type is hierarchical" style="cursor: help;">?</a> (default: False)
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Rewrite', 'cpt-plugin') ?></th>
                    <td>
                        <SELECT name="cpt_custom_post_type[rewrite]" tabindex="8">
                            <OPTION value="0" <?php If (isset($cpt_rewrite)) { If ($cpt_rewrite == 0 && $cpt_rewrite != '') { echo 'selected="selected"'; } } ?>>False</OPTION>
                            <OPTION value="1" <?php If (isset($cpt_rewrite)) { If ($cpt_rewrite == 1 || is_null($cpt_rewrite)) { echo 'selected="selected"'; } }Else{ echo 'selected="selected"'; } ?>>True</OPTION>
                        </SELECT> <a href="#" title="" style="cursor: help;">?</a> (default: True)
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Custom Rewrite Slug', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_custom_post_type[rewrite_slug]" tabindex="9" value="<?php If (isset($cpt_rewrite_slug)) { echo esc_attr($cpt_rewrite_slug); } ?>" /> <a href="#" title="Custom Post Type Rewrite Slug" style="cursor: help;">?</a> (default: post type name)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Query Var', 'cpt-plugin') ?></th>
                    <td>
                        <SELECT name="cpt_custom_post_type[query_var]" tabindex="10">
                            <OPTION value="0" <?php If (isset($cpt_query_var)) { If ($cpt_query_var == 0 && $cpt_query_var != '') { echo 'selected="selected"'; } } ?>>False</OPTION>
                            <OPTION value="1" <?php If (isset($cpt_query_var)) { If ($cpt_query_var == 1 || is_null($cpt_query_var)) { echo 'selected="selected"'; } }Else{ echo 'selected="selected"'; } ?>>True</OPTION>
                        </SELECT> <a href="#" title="" style="cursor: help;">?</a> (default: True)
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Menu Position', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_custom_post_type[menu_position]" tabindex="11" size="5" value="<?php If (isset($cpt_menu_position)) { echo esc_attr($cpt_menu_position); } ?>" /> <a href="#" title="The menu position of the custom post type" style="cursor: help;">?</a></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Show in Menu', 'cpt-plugin') ?></th>
                    <td>
                        <SELECT name="cpt_custom_post_type[show_in_menu]" tabindex="10">
                            <OPTION value="0" <?php If (isset($cpt_show_in_menu)) { If ($cpt_show_in_menu == 0 && $cpt_show_in_menu != '') { echo 'selected="selected"'; } } ?>>False</OPTION>
                            <OPTION value="1" <?php If (isset($cpt_show_in_menu)) { If ($cpt_show_in_menu == 1 || is_null($cpt_show_in_menu)) { echo 'selected="selected"'; } }Else{ echo 'selected="selected"'; } ?>>True</OPTION>
                        </SELECT> <a href="#" title="" style="cursor: help;">?</a> Top level page (e.g. 'plugins.php')
						<input type="text" name="cpt_custom_post_type[show_in_menu_string]" tabindex="12" size="5" value="<?php If (isset($cpt_show_in_menu_string)) { echo esc_attr($cpt_show_in_menu_string); } ?>" /> <a href="#" title="Whether to show the post type in the admin menu and where to show that menu. Note that show_ui must be true" style="cursor: help;">?</a></td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Supports', 'cpt-plugin') ?></th>
                    <td>
                        <input type="checkbox" name="cpt_supports[]" tabindex="11" value="title" <?php If (isset($cpt_supports) && is_array($cpt_supports)) { If (in_array('title', $cpt_supports)) { echo 'checked="checked"'; } }Elseif (!isset($_GET['edittype'])) { echo 'checked="checked"'; } ?> />&nbsp;Title <a href="#" title="Adds the title meta box when creating content for this custom post type" style="cursor: help;">?</a> <br/ >
                        <input type="checkbox" name="cpt_supports[]" tabindex="12" value="editor" <?php If (isset($cpt_supports) && is_array($cpt_supports)) { If (in_array('editor', $cpt_supports)) { echo 'checked="checked"'; } }Elseif (!isset($_GET['edittype'])) { echo 'checked="checked"'; } ?> />&nbsp;Editor <a href="#" title="Adds the content editor meta box when creating content for this custom post type" style="cursor: help;">?</a> <br/ >
                        <input type="checkbox" name="cpt_supports[]" tabindex="13" value="excerpt" <?php If (isset($cpt_supports) && is_array($cpt_supports)) { If (in_array('excerpt', $cpt_supports)) { echo 'checked="checked"'; } }Elseif (!isset($_GET['edittype'])) { echo 'checked="checked"'; } ?> />&nbsp;Excerpt <a href="#" title="Adds the excerpt meta box when creating content for this custom post type" style="cursor: help;">?</a> <br/ >
                        <input type="checkbox" name="cpt_supports[]" tabindex="14" value="trackbacks" <?php If (isset($cpt_supports) && is_array($cpt_supports)) { If (in_array('trackbacks', $cpt_supports)) { echo 'checked="checked"'; } }Elseif (!isset($_GET['edittype'])) { echo 'checked="checked"'; } ?> />&nbsp;Trackbacks <a href="#" title="Adds the trackbacks meta box when creating content for this custom post type" style="cursor: help;">?</a> <br/ >
                        <input type="checkbox" name="cpt_supports[]" tabindex="15" value="custom-fields" <?php If (isset($cpt_supports) && is_array($cpt_supports)) { If (in_array('custom-fields', $cpt_supports)) { echo 'checked="checked"'; } }Elseif (!isset($_GET['edittype'])) { echo 'checked="checked"'; }  ?> />&nbsp;Custom Fields <a href="#" title="Adds the custom fields meta box when creating content for this custom post type" style="cursor: help;">?</a> <br/ >
                        <input type="checkbox" name="cpt_supports[]" tabindex="16" value="comments" <?php If (isset($cpt_supports) && is_array($cpt_supports)) { If (in_array('comments', $cpt_supports)) { echo 'checked="checked"'; } }Elseif (!isset($_GET['edittype'])) { echo 'checked="checked"'; }  ?> />&nbsp;Comments <a href="#" title="Adds the comments meta box when creating content for this custom post type" style="cursor: help;">?</a> <br/ >
                        <input type="checkbox" name="cpt_supports[]" tabindex="17" value="revisions" <?php If (isset($cpt_supports) && is_array($cpt_supports)) { If (in_array('revisions', $cpt_supports)) { echo 'checked="checked"'; } }Elseif (!isset($_GET['edittype'])) { echo 'checked="checked"'; }  ?> />&nbsp;Revisions <a href="#" title="Adds the revisions meta box when creating content for this custom post type" style="cursor: help;">?</a> <br/ >
                        <input type="checkbox" name="cpt_supports[]" tabindex="18" value="thumbnail" <?php If (isset($cpt_supports) && is_array($cpt_supports)) { If (in_array('thumbnail', $cpt_supports)) { echo 'checked="checked"'; } }Elseif (!isset($_GET['edittype'])) { echo 'checked="checked"'; }  ?> />&nbsp;Featured Image <a href="#" title="Adds the featured image meta box when creating content for this custom post type" style="cursor: help;">?</a> <br/ >
                        <input type="checkbox" name="cpt_supports[]" tabindex="19" value="author" <?php If (isset($cpt_supports) && is_array($cpt_supports)) { If (in_array('author', $cpt_supports)) { echo 'checked="checked"'; } }Elseif (!isset($_GET['edittype'])) { echo 'checked="checked"'; }  ?> />&nbsp;Author <a href="#" title="Adds the author meta box when creating content for this custom post type" style="cursor: help;">?</a> <br/ >
                        <input type="checkbox" name="cpt_supports[]" tabindex="20" value="page-attributes" <?php If (isset($cpt_supports) && is_array($cpt_supports)) { If (in_array('page-attributes', $cpt_supports)) { echo 'checked="checked"'; } }Elseif (!isset($_GET['edittype'])) { echo 'checked="checked"'; }  ?> />&nbsp;Page Attributes <a href="#" title="Adds the page attribute meta box when creating content for this custom post type" style="cursor: help;">?</a> <br/ >
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Built-in Taxonomies', 'cpt-plugin') ?></th>
                    <td>
		    <?php
		    //load built-in WP Taxonomies
                    $args=array( 'public'   => true );
                    $output = 'objects';
                    $add_taxes = get_taxonomies($args,$output);
                    foreach ($add_taxes  as $add_tax ) {
                        if ( $add_tax->name != 'nav_menu' && $add_tax->name != 'post_format') {
                            ?>
                            <input type="checkbox" name="cpt_addon_taxes[]" tabindex="20" value="<?php echo $add_tax->name; ?>" <?php If (isset($cpt_taxes) && is_array($cpt_taxes)) { If (in_array($add_tax->name, $cpt_taxes)) { echo 'checked="checked"'; } } ?> />&nbsp;<?php echo $add_tax->label; ?><br />
                            <?php
                        }
                    }
                    ?>
                    </td>
                    </tr>

                </table>
            	</div>

                <p class="submit">
                <input type="submit" class="button-primary" tabindex="21" name="cpt_submit" value="<?php _e($cpt_submit_name, 'cpt-plugin') ?>" />
                </p>

            </form>
		</td>
        <td width="50%" valign="top">
        	<?php
			//debug area
			$cpt_options = get_option('cpt_custom_tax_types');
			?>
        	<p><?php _e('If you are unfamiliar with the options below only fill out the <strong>Taxonomy Name</strong> and <strong>Post Type Name</strong> fields.  The other settings are set to the most common defaults for custom taxonomies.', 'cpt-plugin');?></p>
            <form method="post" <?php echo $RETURN_URL; ?>>
                <?php if ( function_exists('wp_nonce_field') )
                    wp_nonce_field('cpt_add_custom_taxonomy'); ?>
                <?php If (isset($_GET['edittax'])) { ?>
                <input type="hidden" name="cpt_edit_tax" value="<?php echo $editTax; ?>" />
                <?php } ?>
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row"><?php _e('Taxonomy Name', 'cpt-plugin') ?> <span style="color:red;">*</span></th>
                    <td><input type="text" name="cpt_custom_tax[name]" tabindex="21" value="<?php If (isset($cpt_tax_name)) { echo esc_attr($cpt_tax_name); } ?>" /> <a href="#" title="The taxonomy name.  Used to retrieve custom taxonomy content.  Should be short and sweet" style="cursor: help;">?</a> (e.g. actors)</td>
                    </tr>

                   <tr valign="top">
                    <th scope="row"><?php _e('Label', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_custom_tax[label]" tabindex="22" value="<?php If (isset($cpt_tax_label)) { echo esc_attr($cpt_tax_label); } ?>" /> <a href="#" title="Taxonomy label.  Used in the admin menu for displaying custom taxonomy." style="cursor: help;">?</a> (e.g. Actors)</td>
                    </tr>

                   <tr valign="top">
                    <th scope="row"><?php _e('Singular Label', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_custom_tax[singular_label]" tabindex="23" value="<?php If (isset($cpt_singular_label)) { echo esc_attr($cpt_singular_label); } ?>" /> <a href="#" title="Taxonomy Singular label.  Used in WordPress when a singular label is needed." style="cursor: help;">?</a> (e.g. Actor)</td>
                    </tr>

                   <tr valign="top">
                    <th scope="row"><?php _e('Attach to Post Type', 'cpt-plugin') ?> <span style="color:red;">*</span></th>
                    <td>
                    <?php if ( isset( $cpt_tax_object_type ) ) { ?>
                    	<strong>This is the old method.  Delete the post type from the textbox and check which post type to attach this taxonomy to</strong>
                    	<input type="text" name="cpt_custom_tax[cpt_name]" tabindex="24" value="<?php If (isset($cpt_tax_object_type)) { echo esc_attr($cpt_tax_object_type); } ?>" /> <a href="#" title="What object to attach the custom taxonomy to.  Can be post, page, or link by default.  Can also be any custom post type name." style="cursor: help;">?</a> (e.g. movies)
                    <?php } ?>
                    <?php
                    $args=array(
                      'public'   => true
                    );
                    $output = 'objects'; // or objects
                    $post_types=get_post_types($args,$output);
					foreach ($post_types  as $post_type ) {
						if ( $post_type->name != 'attachment' ) {
						?>
                        <input type="checkbox" name="cpt_post_types[]" tabindex="20" value="<?php echo $post_type->name; ?>" <?php If (isset($cpt_post_types) && is_array($cpt_post_types)) { If (in_array($post_type->name, $cpt_post_types)) { echo 'checked="checked"'; } } ?> />&nbsp;<?php echo $post_type->label; ?><br />
                        <?php
						}
					}
					?>
                    </td>
                    </tr>

            		<tr valign="top">
                        <td colspan="2">
                            <p align="center">
                            <?php echo '<a href="#" class="comment_button" id="3">' . __('Advanced Label Options', 'cpt-plugin') . '</a>'; ?> &middot;
                            <?php echo '<a href="#" class="comment_button" id="4">' . __('Advanced Options', 'cpt-plugin') . '</a>'; ?>
                            </p>
                        </td>
                    </tr>

				</table>

                <div style="display:none;" id="slidepanel3">
                <p><?php _e('Below are the advanced label options for custom taxonomies.  If you are unfamiliar with these labels the plugin will automatically create labels based off of your custom taxonomy name', 'cpt-plugin'); ?></p>
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row"><?php _e('Search Items', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_tax_labels[search_items]" tabindex="2" value="<?php If (isset($cpt_tax_labels["search_items"])) { echo esc_attr($cpt_tax_labels["search_items"]); } ?>" /> <a href="#" title="Custom taxonomy label.  Used in the admin menu for displaying taxonomies." style="cursor: help;">?</a> (e.g. Search Actors)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Popular Items', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_tax_labels[popular_items]" tabindex="2" value="<?php If (isset($cpt_tax_labels["popular_items"])) { echo esc_attr($cpt_tax_labels["popular_items"]); } ?>" /> <a href="#" title="Custom taxonomy label.  Used in the admin menu for displaying taxonomies." style="cursor: help;">?</a> (e.g. Popular Actors)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('All Items', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_tax_labels[all_items]" tabindex="2" value="<?php If (isset($cpt_tax_labels["all_items"])) { echo esc_attr($cpt_tax_labels["all_items"]); } ?>" /> <a href="#" title="Custom taxonomy label.  Used in the admin menu for displaying taxonomies." style="cursor: help;">?</a> (e.g. All Actors)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Parent Item', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_tax_labels[parent_item]" tabindex="2" value="<?php If (isset($cpt_tax_labels["parent_item"])) { echo esc_attr($cpt_tax_labels["parent_item"]); } ?>" /> <a href="#" title="Custom taxonomy label.  Used in the admin menu for displaying taxonomies." style="cursor: help;">?</a> (e.g. Parent Actor)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Parent Item Colon', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_tax_labels[parent_item_colon]" tabindex="2" value="<?php If (isset($cpt_tax_labels["parent_item_colon"])) { echo esc_attr($cpt_tax_labels["parent_item_colon"]); } ?>" /> <a href="#" title="Custom taxonomy label.  Used in the admin menu for displaying taxonomies." style="cursor: help;">?</a> (e.g. Parent Actor:)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Edit Item', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_tax_labels[edit_item]" tabindex="2" value="<?php If (isset($cpt_tax_labels["edit_item"])) { echo esc_attr($cpt_tax_labels["edit_item"]); } ?>" /> <a href="#" title="Custom taxonomy label.  Used in the admin menu for displaying taxonomies." style="cursor: help;">?</a> (e.g. Edit Actor)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Update Item', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_tax_labels[update_item]" tabindex="2" value="<?php If (isset($cpt_tax_labels["update_item"])) { echo esc_attr($cpt_tax_labels["update_item"]); } ?>" /> <a href="#" title="Custom taxonomy label.  Used in the admin menu for displaying taxonomies." style="cursor: help;">?</a> (e.g. Update Actor)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Add New Item', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_tax_labels[add_new_item]" tabindex="2" value="<?php If (isset($cpt_tax_labels["add_new_item"])) { echo esc_attr($cpt_tax_labels["add_new_item"]); } ?>" /> <a href="#" title="Custom taxonomy label.  Used in the admin menu for displaying taxonomies." style="cursor: help;">?</a> (e.g. Add New Actor)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('New Item Name', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_tax_labels[new_item_name]" tabindex="2" value="<?php If (isset($cpt_tax_labels["new_item_name"])) { echo esc_attr($cpt_tax_labels["new_item_name"]); } ?>" /> <a href="#" title="Custom taxonomy label.  Used in the admin menu for displaying taxonomies." style="cursor: help;">?</a> (e.g. New Actor Name)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Separate Items with Commas', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_tax_labels[separate_items_with_commas]" tabindex="2" value="<?php If (isset($cpt_tax_labels["separate_items_with_commas"])) { echo esc_attr($cpt_tax_labels["separate_items_with_commas"]); } ?>" /> <a href="#" title="Custom taxonomy label.  Used in the admin menu for displaying taxonomies." style="cursor: help;">?</a> (e.g. Separate actors with commas)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Add or Remove Items', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_tax_labels[add_or_remove_items]" tabindex="2" value="<?php If (isset($cpt_tax_labels["add_or_remove_items"])) { echo esc_attr($cpt_tax_labels["add_or_remove_items"]); } ?>" /> <a href="#" title="Custom taxonomy label.  Used in the admin menu for displaying taxonomies." style="cursor: help;">?</a> (e.g. Add or remove actors)</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Choose From Most Used', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_tax_labels[choose_from_most_used]" tabindex="2" value="<?php If (isset($cpt_tax_labels["choose_from_most_used"])) { echo esc_attr($cpt_tax_labels["choose_from_most_used"]); } ?>" /> <a href="#" title="Custom taxonomy label.  Used in the admin menu for displaying taxonomies." style="cursor: help;">?</a> (e.g. Choose from the most used actors)</td>
                    </tr>
                </table>
                </div>

                <div style="display:none;" id="slidepanel4">
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row"><?php _e('Hierarchical', 'cpt-plugin') ?></th>
                    <td>
                        <SELECT name="cpt_custom_tax[hierarchical]" tabindex="25">
                            <OPTION value="0" <?php If (isset($cpt_tax_hierarchical)) { If ($cpt_tax_hierarchical == 0) { echo 'selected="selected"'; } }Else{ echo 'selected="selected"'; } ?>>False</OPTION>
                            <OPTION value="1" <?php If (isset($cpt_tax_hierarchical)) { If ($cpt_tax_hierarchical == 1) { echo 'selected="selected"'; } } ?>>True</OPTION>
                        </SELECT> <a href="#" title="Whether the taxonomy is hierarchical" style="cursor: help;">?</a> (default: False)
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Show UI', 'cpt-plugin') ?></th>
                    <td>
                        <SELECT name="cpt_custom_tax[show_ui]" tabindex="26">
                            <OPTION value="0" <?php If (isset($cpt_tax_showui)) { If ($cpt_tax_showui == 0 && $cpt_tax_showui != '') { echo 'selected="selected"'; } } ?>>False</OPTION>
                            <OPTION value="1" <?php If (isset($cpt_tax_showui)) { If ($cpt_tax_showui == 1 || is_null($cpt_tax_showui)) { echo 'selected="selected"'; } }Else{ echo 'selected="selected"'; } ?>>True</OPTION>
                        </SELECT> <a href="#" title="Whether to generate a default UI for managing this custom taxonomy" style="cursor: help;">?</a> (default: True)
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Query Var', 'cpt-plugin') ?></th>
                    <td>
                        <SELECT name="cpt_custom_tax[query_var]" tabindex="27">
                            <OPTION value="0" <?php If (isset($cpt_tax_query_var)) { If ($cpt_tax_query_var == 0 && $cpt_tax_query_var != '') { echo 'selected="selected"'; } } ?>>False</OPTION>
                            <OPTION value="1" <?php If (isset($cpt_tax_query_var)) { If ($cpt_tax_query_var == 1 || is_null($cpt_tax_query_var)) { echo 'selected="selected"'; } }Else{ echo 'selected="selected"'; } ?>>True</OPTION>
                        </SELECT> <a href="#" title="" style="cursor: help;">?</a> (default: True)
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Rewrite', 'cpt-plugin') ?></th>
                    <td>
                        <SELECT name="cpt_custom_tax[rewrite]" tabindex="28">
                            <OPTION value="0" <?php If (isset($cpt_tax_rewrite)) { If ($cpt_tax_rewrite == 0 && $cpt_tax_rewrite != '') { echo 'selected="selected"'; } } ?>>False</OPTION>
                            <OPTION value="1" <?php If (isset($cpt_tax_rewrite)) { If ($cpt_tax_rewrite == 1 || is_null($cpt_tax_rewrite)) { echo 'selected="selected"'; } }Else{ echo 'selected="selected"'; } ?>>True</OPTION>
                        </SELECT> <a href="#" title="" style="cursor: help;">?</a> (default: True)
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row"><?php _e('Custom Rewrite Slug', 'cpt-plugin') ?></th>
                    <td><input type="text" name="cpt_custom_tax[rewrite_slug]" tabindex="9" value="<?php If (isset($cpt_tax_rewrite_slug)) { echo esc_attr($cpt_tax_rewrite_slug); } ?>" /> <a href="#" title="Custom Taxonomy Rewrite Slug" style="cursor: help;">?</a> (default: taxonomy name)</td>
                    </tr>

                </table>
                </div>

                <p class="submit">
                	<input type="submit" class="button-primary" tabindex="29" name="cpt_add_tax" value="<?php _e($cpt_tax_submit_name, 'cpt-plugin') ?>" />
                </p>
            </form>
        </td>
	</tr>
</table>
</div>
<?php
//load footer
cpt_footer();

}

function cpt_footer() {
	?>
    <hr />
	<p class="cp_about"><a target="_blank" href="http://webdevstudios.com/support/forum/custom-post-type-ui/">Custom Post Type UI</a> v<?php echo CPT_VERSION; ?> - <a href="http://webdevstudios.com/support/forum/custom-post-type-ui/" target="_blank">Please Report Bugs</a> &middot; Follow on Twitter: <a href="http://twitter.com/williamsba" target="_blank">Brad</a> &middot; <a href="http://twitter.com/pluginize" target="_blank">Pluginize</a> &middot; <a href="http://twitter.com/webdevstudios" target="_blank">WDS</a></p>
	<?php
}

function cpt_check_return($return) {
	global $CPT_URL;

	If( $return=='cpt' ) {
		return ( isset( $_GET['return'] ) ) ? admin_url( 'admin.php?page=cpt_sub_manage_cpt&return=cpt' ) : admin_url( 'admin.php?page=cpt_sub_manage_cpt' );
	}Elseif( $return=='tax' ){
		return ( isset( $_GET['return'] ) ) ? admin_url( 'admin.php?page=cpt_sub_manage_taxonomies&return=tax' ) : admin_url( 'admin.php?page=cpt_sub_manage_taxonomies' );
	}Elseif( $return=='add' ) {
		return admin_url( 'admin.php?page=cpt_sub_add_new' );
	}Else{
		return admin_url( 'admin.php?page=cpt_sub_add_new' );
	}
}

function get_disp_boolean($booText) {
	If ($booText == '0') {
		return false;
	}Else{
		return true;
	}
}

function disp_boolean($booText) {
	If ($booText == '0') {
		return 'false';
	}Else{
		return 'true';
	}
}
?>