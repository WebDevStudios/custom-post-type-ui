<?php
/**
 * This file controls all of the content from the Import/Export page
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add our settings page to the menu.
 *
 * @since  0.9
 *
 * @return mixed  new menu
 */
function cptui_importexport_admin_menu() {
	add_submenu_page( 'cptui_main_menu', __( 'Import/Export', 'cpt-plugin' ), __( 'Import/Export', 'cpt-plugin' ), 'manage_options', 'cptui_importexport', 'cptui_importexport' );
}
add_action( 'admin_menu', 'cptui_importexport_admin_menu' );

/**
 * Create our settings page output
 *
 * @since  0.9
 *
 * @return mixed  webpage
 */
function cptui_importexport() {

	if ( !empty( $_GET ) ) {
		if ( !empty( $_GET['action'] ) && 'taxonomies' == $_GET['action'] ) {
			$tab = 'taxonomies';
		} elseif ( !empty( $_GET['action'] ) && 'get_code' == $_GET['action'] ) {
			$tab = 'get_code';
		} else {
			$tab = 'post_types';
		}
	}

	if ( !empty( $_POST ) ) {
		$notice = cptui_import_types_taxes_settings( $_POST );
	}

	if ( isset( $notice ) ) {
		echo $notice;
	}
	echo '<div class="wrap">';

	//Create our tabs.
	cptui_settings_tab_menu( $page = 'importexport' );

	if ( isset( $tab ) && ( 'post_types' == $tab || 'taxonomies' == $tab ) ) {
	?>
	<p><?php _e( 'If you are trying to migrate post types and taxonomies from this site to another that will also use Custom Post Type UI, use the import and export functionality. If you are moving away from Custom Post Type UI, use the information in the "Get Code" tab', 'cpt-plugin' ); ?></p>

	<table class="form-table cptui-table">
		<?php if ( !empty( $_GET ) && empty( $_GET['action'] ) ) { ?>
		<tr>
			<td>
				<h3><?php _e( 'Import Post Types', 'cpt-plugin' ); ?></h3>
				<form method="post">
					<textarea class="cptui_post_import" placeholder="<?php esc_attr_e( 'Paste content here.', 'cpt-plugin' ); ?>" name="cptui_post_import"></textarea>
					<p class="wp-ui-highlight"><strong><?php _e( 'Note:', 'cpt-plugin' ); ?></strong> <?php _e( 'Importing will overwrite previous registered settings.', 'cpt-plugin' ); ?></p>
					<p><strong><?php _e( 'To import post types from a different WordPress site, paste the exported content from that site and click the "Import" button.', 'cpt-plugin' ); ?></strong></p>
					<p><input class="button button-primary" type="submit" value="<?php esc_attr_e( 'Import', 'cpt-plugin' ); ?>"/></strong></p>
				</form>
			</td>
			<td>
				<h3><?php _e( 'Export Post Types', 'cpt-plugin' ); ?></h3>
				<?php
					$cptui_post_types = get_option( 'cptui_post_types', array() );
					if ( !empty( $cptui_post_types ) ) {
						$content = esc_html( json_encode( $cptui_post_types ) );
					} else {
						$content = __( 'No post types registered yet.', 'cpt-plugin' );
					}
				?>
				<textarea title="<?php esc_attr_e( 'To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'cpt-plugin' ); ?>" onclick="this.focus();this.select()" readonly="readonly" class="cptui_post_import"><?php echo $content; ?></textarea>
				<p><strong><?php _e( 'Use the content above to import current post types into a different WordPress site. You can also use this to simply back up your post type settings.', 'cpt-plugin' ); ?></strong></p>
			</td>
		</tr>
		<?php } elseif ( !empty( $_GET ) && 'taxonomies' == $_GET['action'] ) { ?>
		<tr>
			<td>
				<h3><?php _e( 'Import Taxonomies', 'cpt-plugin' ); ?></h3>
				<form method="post">
					<textarea class="cptui_tax_import" placeholder="<?php esc_attr_e( 'Paste content here.', 'cpt-plugin' ); ?>" name="cptui_tax_import"></textarea>
					<p class="wp-ui-highlight"><strong><?php _e( 'Note:', 'cpt-plugin' ); ?></strong> <?php _e( 'Importing will overwrite previous registered settings.', 'cpt-plugin' ); ?></p>
					<p><strong><?php _e( 'To import taxonomies from a different WordPress site, paste the exported content from that site and click the "Import" button.', 'cpt-plugin' ); ?>
					<p><input class="button button-primary" type="submit" value="<?php esc_attr_e( 'Import', 'cpt-plugin' ); ?>"/></strong></p>
				</form>
			</td>
			<td>
				<h3><?php _e( 'Export Taxonomies', 'cpt-plugin' ); ?></h3>
				<?php
					$cptui_taxonomies = get_option( 'cptui_taxonomies', array() );
					if ( !empty( $cptui_taxonomies ) ) {
						$content = esc_html( json_encode( $cptui_taxonomies ) );
					} else {
						$content = __( 'No taxonomies registered yet.', 'cpt-plugin' );
					}
				?>
				<textarea title="<?php esc_attr_e( 'To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'cpt-plugin' ); ?>" onclick="this.focus();this.select()" readonly="readonly" class="cptui_tax_import"><?php echo $content; ?></textarea>
				<p><strong><?php _e( 'Use the content above to import current taxonomies into a different WordPress site. You can also use this to simply back up your taxonomy settings.', 'cpt-plugin' ); ?></strong></p>
			</td>
		</tr>
		<?php } ?>
	</table>

	<?php
	} else { ?>
		<h2><?php _e( 'Get Post Type and Taxonomy Code', 'cpt-plugin' ); ?></h2>

		<h3>All CPT UI Post Types</h3>
		<textarea class="cptui_post_type_get_code" onclick="this.focus();this.select()" readonly="readonly"><?php cptui_get_post_type_code(); ?></textarea>

		<h3>All CPT UI Taxonomies</h3>
		<textarea class="cptui_tax_get_code" onclick="this.focus();this.select()" readonly="readonly"><?php cptui_get_taxonomy_code(); ?></textarea>
	<?php
	}

	echo '</div><!-- End .wrap -->';

	cptui_footer();
}

/**
 * Fetch error message based on $_GET parameter
 *
 * @since  0.9
 *
 * @return mixed  false on no error, mixed when there is one.
 */
function cptui_get_importexport_errors() {
	//TODO: Marked for refactoring and admin_notices
	if ( isset( $_GET['cpt_error'] ) ) :
		$error = $_GET['cpt_error'];

		$msg = '<div class="error">';

		if ( 1 == $error ) {
			$msg .= '<p>' . __( 'Taxonomy name is a required field.', 'cpt-plugin' ) . '</p>';
		}
		if ( 2 == $error ) {
			$msg .= '<p>' . __( 'You must assign your custom taxonomy to at least one post type.', 'cpt-plugin' ) . '</p>';
		}
		if ( 3 == $error ) {
			$msg .= '<p>' . __( 'Please do not use quotes in your taxonomy slug or rewrite slug.', 'cpt-plugin' ) . '</p>';
		}
		$msg .= '</div>';

		return $msg;

	endif;

	return false;
}

/**
 * Fetch success message based on $_GET parameter
 *
 * @since  0.9
 *
 * @return mixed  false on no parameter, mixed when there is one.
 */
function cptui_get_importexport_successes() {
	//TODO: Marked for refactoring and admin_notices
	if ( isset( $_GET['cpt_msg'] ) ) :
		$success = $_GET['cpt_msg'];

		$msg = '<div id="message" class="updated">';

		if ( 1 == $success ) {
			$msg .= '<p>' . __( 'Custom taxonomy created successfully.  You may need to refresh to view the new taxonomy in the admin menu.', 'cpt-plugin' ) . '</p>';
		}

		$msg .= '</div>';

		return $msg;

	endif;

	return false;
}

function cptui_get_taxonomy_code() {

	//fetch out taxonomies
	$cptui_taxonomies = get_option( 'cptui_taxonomies', array() );
	?>
add_action( 'init', 'cptui_register_my_taxes' );
function cptui_register_my_taxes() {
<?php
	foreach( $cptui_taxonomies as $tax ) {
		echo cptui_get_single_taxonomy_registery( $tax ) . "\n";
	} ?>
//End cptui_register_my_taxes
}
<?php
}

/**
 * Create output for single taxonomy to be ready for copy/paste from Get Code
 *
 * @since 0.9
 *
 * @param array $taxonomy Taxonomy data to output
 * @return string          Copy/paste ready "php" code
 */
function cptui_get_single_taxonomy_registery( $taxonomy = array() ) {
	$post_types = 'array( ' . implode( ', ', $taxonomy['post_types'] ) . ' )';
	?>

	$labels = array(
		'name' => '<?php echo $taxonomy['name']; ?>',
		'label' => '<?php echo $taxonomy['label']; ?>',
		'singular_label' => '<?php echo $taxonomy['singular_label']; ?>',
		'search_items' => '<?php echo $taxonomy['labels']['search_items']; ?>',
		'popular_items' => '<?php echo $taxonomy['labels']['popular_items']; ?>',
		'all_items' => '<?php echo $taxonomy['labels']['all_items']; ?>',
		'parent_item' => '<?php echo $taxonomy['labels']['parent_item']; ?>',
		'parent_item_colon' => '<?php echo $taxonomy['labels']['parent_item_colon']; ?>',
		'edit_item' => '<?php echo $taxonomy['labels']['edit_item']; ?>',
		'update_item' => '<?php echo $taxonomy['labels']['update_item']; ?>',
		'add_new_item' => '<?php echo $taxonomy['labels']['add_new_item']; ?>',
		'new_item_name' => '<?php echo $taxonomy['labels']['new_item_name']; ?>',
		'separate_items_with_commas' => '<?php echo $taxonomy['labels']['separate_items_with_commas']; ?>',
		'add_or_remove_items' => '<?php echo $taxonomy['labels']['add_or_remove_items']; ?>',
		'choose_from_most_used' => '<?php echo $taxonomy['labels']['choose_from_most_used']; ?>'
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => <?php echo $taxonomy['hierarchical']; ?>,
		'label' => '<?php echo $taxonomy['label']; ?>',
		'show_ui' => <?php echo $taxonomy['show_ui']; ?>,
		'query_var' => <?php echo $taxonomy['query_var'];?>,
		'rewrite' => <?php echo $taxonomy['rewrite']; ?>,
		'show_admin_column' => <?php echo $taxonomy['show_admin_column']; ?>,
	);
<?php //register_taxonomy( $taxonomy, $object_type, $args ); NEED TO DETERMINE THE $object_type ?>
	register_taxonomy( '<?php echo $taxonomy['name']; ?>', <?php echo $post_types; ?>, $args );
<?php
}

/**
 * Create and display all of the "Get code" content for the main textarea
 *
 * @since 0.9
 *
 * @return string    All code for the post types
 */
function cptui_get_post_type_code() {

	//fetch out post types
	$cptui_post_types = get_option( 'cptui_post_types', array() );

	//Whitespace very much matters here, thus why it's all flush against the left side
	?>
add_action( 'init', 'cptui_register_my_cpts' );
function cptui_register_my_cpts() {
<?php //space before this line reflects in textarea
	foreach( $cptui_post_types as $type ) {
	echo cptui_get_single_post_type_registery( $type ) . "\n";
	} ?>
// End of cptui_register_my_cpts()
}
<?php
}

/**
 * Create output for single post type to be ready for copy/paste from Get Code
 *
 * @since 0.9
 *
 * @param array $post_type Post type data to output
 * @return string          Copy/paste ready "php" code
 */
function cptui_get_single_post_type_registery( $post_type = array() ) {
	//Do a little bit of php work to get these into strings.
	$supports = 'array( ' . implode( ', ', $post_type['supports'] ) . ' )';
	$taxonomies = 'array( ' . implode( ', ', $post_type['taxonomies'] ) . ' )';
	?>
	$labels = array(
		'name' => '<?php echo $post_type['label']; ?>',
		'singular_name' => '<?php echo $post_type['singular_label']; ?>',
		'menu_name' => '<?php echo $post_type['labels']['menu_name'] ?>',
		'all_items' => '<?php echo $post_type['labels']['all_items']; ?>',
		'add_new' => '<?php echo $post_type['labels']['add_new']; ?>',
		'add_new_item' => '<?php echo $post_type['labels']['add_new_item']; ?>',
		'edit' => '<?php echo $post_type['labels']['edit']; ?>',
		'edit_item' => '<?php echo $post_type['labels']['edit_item']; ?>',
		'new_item' => '<?php echo $post_type['labels']['new_item']; ?>',
		'view' => '<?php echo $post_type['labels']['view']; ?>',
		'view_item' => '<?php echo $post_type['labels']['view_item']; ?>',
		'search_items' => '<?php echo $post_type['labels']['search_items']; ?>',
		'not_found' => '<?php echo $post_type['labels']['not_found']; ?>',
		'not_found_in_trash' => '<?php echo $post_type['labels']['not_found_in_trash']; ?>',
		'parent_item_colon' => '<?php echo $post_type['labels']['parent']; ?>'
	);

	$args = array(
		'labels' => $labels,
		'description' => '<?php echo $post_type['description']; ?>',
		'public' => <?php echo $post_type['public']; ?>,
		'show_ui' => <?php echo $post_type['show_ui']; ?>,
		'has_archive' => <?php echo $post_type['has_archive']; ?>,
		'show_in_menu' => <?php echo $post_type['show_in_menu']; ?>,
		'exclude_from_search' => <?php echo $post_type['exclude_from_search']; ?>,
		'capability_type' => '<?php echo $post_type['capability_type']; ?>',
		'map_meta_cap' => <?php echo $post_type['map_meta_cap']; ?>,
		'hierarchical' => <?php echo $post_type['hierarchical']; ?>,
		'rewrite' => <?php echo $post_type['rewrite']; ?>,
		'menu_position' => <?php echo $post_type['menu_position']; ?>,
		'menu_icon' => <?php echo $post_type['menu_icon']; ?>,
		'query_var' => <?php echo $post_type['query_var']; ?>,
		'supports' => <?php echo $supports; ?> ,
		'taxonomies' => <?php echo $taxonomies; ?>
	);
	register_post_type( '<?php echo $post_type['name']; ?>', $args );
<?php
}

/**
 * Import the posted JSON data from a separate export.
 *
 * @since  0.9
 *
 * @param string $postdata    $_POST data as json
 * @return mixed              false on nothing to do, otherwise void
 */
function cptui_import_types_taxes_settings( $postdata ) {
	if ( !isset( $postdata['cptui_post_import'] ) && !isset( $postdata['cptui_tax_import'] ) ) {
		return;
	}

	if ( !empty( $postdata['cptui_post_import'] ) ) {
		$data = stripslashes_deep( trim( $postdata['cptui_post_import'] ) );
		$settings = json_decode( $data, true );

		if ( $settings ) {
			$success = update_option( 'cptui_post_types', $settings );
		}
		return cptui_admin_notices( 'import', '', $success );

  	} elseif ( !empty( $postdata['cptui_tax_import'] ) ) {
  		$data = stripslashes_deep( trim( $postdata['cptui_tax_import'] ) );
		$settings = json_decode( $data, true );

		if ( $settings ) {
			$success = update_option( 'cptui_taxonomies', $settings );
		}
		return cptui_admin_notices( 'import', '', $success );
  	}
	//Make them immediately available.
	flush_rewrite_rules();
}
