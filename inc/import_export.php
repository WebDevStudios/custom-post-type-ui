<?php
/**
 * This file controls all of the content from the Import/Export page.
 */

# Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add our settings page to the menu.
 *
 * @since 1.0.0
 */
function cptui_importexport_admin_menu() {
	add_submenu_page( 'cptui_main_menu', __( 'Import/Export', 'cpt-plugin' ), __( 'Import/Export', 'cpt-plugin' ), 'manage_options', 'cptui_importexport', 'cptui_importexport' );
}
add_action( 'admin_menu', 'cptui_importexport_admin_menu' );

/**
 * Create our settings page output.
 *
 * @since 1.0.0
 *
 * @return string HTML output for the page.
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

	# Create our tabs.
	cptui_settings_tab_menu( $page = 'importexport' );

	if ( isset( $tab ) && ( 'post_types' == $tab || 'taxonomies' == $tab ) ) {
	?>
	<p><?php _e( 'If you are wanting to migrate registered post types or taxonomies from this site to another, that will also use Custom Post Type UI, use the import and export functionality. If you are moving away from Custom Post Type UI, use the information in the "Get Code" tab.', 'cpt-plugin' ); ?></p>

	<p><?php printf( '<strong>%s</strong>: %s',
			__( 'NOTE', 'cpt-plugin' ),
			__( 'This will not export the associated posts, just the settings.', 'cpt-plugin' )
		); ?>
	</p>
	<table class="form-table cptui-table">
		<?php if ( !empty( $_GET ) && empty( $_GET['action'] ) ) { ?>
		<tr>
			<td>
				<h3><?php _e( 'Import Post Types', 'cpt-plugin' ); ?></h3>
				<form method="post">
					<textarea class="cptui_post_import" placeholder="<?php esc_attr_e( 'Paste content here.', 'cpt-plugin' ); ?>" name="cptui_post_import"></textarea>
					<p class="wp-ui-highlight"><strong><?php _e( 'Note:', 'cpt-plugin' ); ?></strong> <?php _e( 'Importing will overwrite previous registered settings.', 'cpt-plugin' ); ?></p>
					<p><strong><?php _e( 'To import post types from a different WordPress site, paste the exported content from that site and click the "Import" button.', 'cpt-plugin' ); ?></strong></p>
					<p><input class="button button-primary" type="submit" value="<?php esc_attr_e( 'Import', 'cpt-plugin' ); ?>"/></p>
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
					<p><strong><?php _e( 'To import taxonomies from a different WordPress site, paste the exported content from that site and click the "Import" button.', 'cpt-plugin' ); ?></strong></p>
					<p><input class="button button-primary" type="submit" value="<?php esc_attr_e( 'Import', 'cpt-plugin' ); ?>"/></p>
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

		<h3><?php _e( 'All CPT UI Post Types', 'cpt-plugin' ); ?></h3>
		<label for="cptui_post_type_get_code"><?php _e( 'Copy/paste the code below into your functions.php file.', 'cpt-plugin' ); ?></label>
		<textarea name="cptui_post_type_get_code" id="cptui_post_type_get_code" class="cptui_post_type_get_code" onclick="this.focus();this.select()" readonly="readonly"><?php cptui_get_post_type_code(); ?></textarea>

		<h3><?php _e( 'All CPT UI Taxonomies', 'cpt-plugin' ); ?></h3>
		<label for="cptui_tax_get_code"><?php _e( 'Copy/paste the code below into your functions.php file.', 'cpt-plugin' ); ?></label>
		<textarea name="cptui_tax_get_code" id="cptui_tax_get_code" class="cptui_tax_get_code" onclick="this.focus();this.select()" readonly="readonly"><?php cptui_get_taxonomy_code(); ?></textarea>
	<?php
	}

	echo '</div><!-- End .wrap -->';
}

/**
 * Display our copy-able code for registered taxonomies.
 *
 * @since 1.0.0
 *
 * @return string Taxonomy registration text for use elsewhere.
 */
function cptui_get_taxonomy_code() {

	$cptui_taxonomies = get_option( 'cptui_taxonomies' );
	if ( !empty( $cptui_taxonomies ) ) {
	?>
add_action( 'init', 'cptui_register_my_taxes' );
function cptui_register_my_taxes() {
<?php
	foreach( $cptui_taxonomies as $tax ) {
		echo cptui_get_single_taxonomy_registery( $tax ) . "\n";
	} ?>
// End cptui_register_my_taxes
}
<?php
	} else {
		_e( 'No taxonomies to display at this time', 'cpt-plugin' );
	}
}

/**
 * Create output for single taxonomy to be ready for copy/paste from Get Code.
 *
 * @since 1.0.0
 *
 * @param array $taxonomy Taxonomy data to output.
 *
 * @return string Copy/paste ready "php" code.
 */
function cptui_get_single_taxonomy_registery( $taxonomy = array() ) {

	$post_types = "''";
	if ( is_array( $taxonomy['object_types'] ) ) {
		$post_types = 'array( "' . implode( '", "', $taxonomy['object_types'] ) . '" )';
	}

	$rewrite = get_disp_boolean( $taxonomy['rewrite'] );
	if ( false !== get_disp_boolean( $taxonomy['rewrite'] ) ) {
		$rewrite = disp_boolean( $taxonomy['rewrite'] );

		$rewrite_slug = ' \'slug\' => \'' . $taxonomy['name'] . '\',';
		if ( !empty( $taxonomy['rewrite_slug'] ) ) {
			$rewrite_slug = ' \'slug\' => \'' . $taxonomy['rewrite_slug'] . '\',';
		}

		$withfront = disp_boolean( $taxonomy['rewrite_withfront'] );
		if ( !empty( $withfront ) ) {
			$rewrite_withfront = ' \'with_front\' => ' . $withfront . ' ';
		}

		$hierarchical = ( !empty( $taxonomy['rewrite_hierarchical'] ) ) ? disp_boolean( $taxonomy['rewrite_hierarchical'] ) : '';
		if ( !empty( $hierarchical ) ) {
			$rewrite_hierarchcial = ' \'hierarchical\' => ' . $hierarchical . ' ';
		}

		if ( !empty( $taxonomy['rewrite_slug'] ) || false !== disp_boolean( $taxonomy['rewrite_withfront'] ) ) {
			$rewrite_start = 'array(';
			$rewrite_end   = ')';

			$rewrite = $rewrite_start . $rewrite_slug . $rewrite_withfront . $hierarchical . $rewrite_end;
		}
	} else {
		$rewrite = disp_boolean( $taxonomy['rewrite'] );
	}

	?>

	$labels = array(
		"name" => "<?php echo $taxonomy['name']; ?>",
		"label" => "<?php echo $taxonomy['label']; ?>",
		<?php foreach( $taxonomy['labels'] as $key => $label ) {
			if ( !empty( $label ) ) {
			echo '"' . $key . '" => "' . $label . '",' . "\n\t\t";
			}
		} ?>
	);

	$args = array(
		"labels" => $labels,
		"hierarchical" => <?php echo $taxonomy['hierarchical']; ?>,
		"label" => "<?php echo $taxonomy['label']; ?>",
		"show_ui" => <?php echo disp_boolean( $taxonomy['show_ui'] ); ?>,
		"query_var" => <?php echo disp_boolean( $taxonomy['query_var'] );?>,
		"rewrite" => <?php echo $rewrite; ?>,
		"show_admin_column" => <?php echo $taxonomy['show_admin_column']; ?>,
	);
<?php # register_taxonomy( $taxonomy, $object_type, $args ); NEED TO DETERMINE THE $object_type ?>
	register_taxonomy( "<?php echo $taxonomy['name']; ?>", <?php echo $post_types; ?>, $args );
<?php
}

/**
 * Display our copy-able code for registered post types.
 *
 * @since 1.0.0
 *
 * @return string Post type registration text for use elsewhere.
 */
function cptui_get_post_type_code() {

	$cptui_post_types = get_option( 'cptui_post_types' );

	# Whitespace very much matters here, thus why it's all flush against the left side
	if ( !empty( $cptui_post_types ) ) {
	?>
add_action( 'init', 'cptui_register_my_cpts' );
function cptui_register_my_cpts() {
<?php #space before this line reflects in textarea
	foreach( $cptui_post_types as $type ) {
	echo cptui_get_single_post_type_registery( $type ) . "\n";
	} ?>
// End of cptui_register_my_cpts()
}
<?php
	} else {
		_e( 'No post types to display at this time', 'cpt-plugin' );
	}
}

/**
 * Create output for single post type to be ready for copy/paste from Get Code.
 *
 * @since 1.0.0
 *
 * @param array $post_type Post type data to output.
 *
 * @return string Copy/paste ready "php" code.
 */
function cptui_get_single_post_type_registery( $post_type = array() ) {

	/** This filter is documented in custom-post-type-ui/custom-post-type-ui.php */
	$post_type['map_meta_cap'] = apply_filters( 'cptui_map_meta_cap', 'true', $post_type['name'], $post_type );

	$user_supports_params = apply_filters( 'cptui_user_supports_params', array(), $post_type['name'], $post_type );
	if ( is_array( $user_supports_params ) ) {
		$post_type['supports'] = array_merge( $post_type['supports'], $user_supports_params );
	}

	$rewrite = get_disp_boolean( $post_type['rewrite' ] );
	if ( false !== $rewrite ) {
		$rewrite = disp_boolean( $post_type['rewrite'] );

		$rewrite_slug = ' "slug" => "' . $post_type['name'] . '",';
		if ( !empty( $post_type['rewrite_slug'] ) ) {
			$rewrite_slug = ' "slug" => "' . $post_type['rewrite_slug'] . '",';
		}

		$withfront = disp_boolean( $post_type['rewrite_withfront'] );
		if ( !empty( $withfront ) ) {
			$rewrite_withfront = ' "with_front" => ' . $withfront . ' ';
		}

		if ( !empty( $post_type['rewrite_slug'] ) || !empty( $post_type['rewrite_withfront'] ) ) {
			$rewrite_start = 'array(';
			$rewrite_end   = ')';

			$rewrite = $rewrite_start . $rewrite_slug . $rewrite_withfront . $rewrite_end;
		}

	} else {
		$rewrite = disp_boolean( $post_type['rewrite'] );
	}

	$supports = '';
	# Do a little bit of php work to get these into strings.
	if ( !empty( $post_type['supports'] ) && is_array( $post_type['supports'] ) ) {
		$supports = 'array( "' . implode( '", "', $post_type['supports'] ) . '" )';
	}

	if ( in_array( 'none', $post_type['supports'] ) ) {
		$supports = 'false';
	}

	$taxonomies = '';
	if ( !empty( $post_type['taxonomies'] ) && is_array( $post_type['taxonomies'] ) ) {
		$taxonomies = 'array( "' . implode( '", "', $post_type['taxonomies'] ) . '" )';
	}

	if ( in_array( $post_type['query_var'], array( 'true', 'false', '0', '1' ) ) ) {
		$post_type['query_var'] = get_disp_boolean( $post_type['query_var'] );
	}

	$post_type['description'] = addslashes( $post_type['description'] );
	?>
	$labels = array(
		"name" => "<?php echo $post_type['label']; ?>",
		"singular_name" => "<?php echo $post_type['singular_label']; ?>",
		<?php foreach( $post_type['labels'] as $key => $label ) {
			if ( !empty( $label ) ) {
				echo '"' . $key . '" => "' . $label . '",' . "\n\t\t";
			}
		} ?>);

	$args = array(
		"labels" => $labels,
		"description" => "<?php echo $post_type['description']; ?>",
		"public" => <?php echo disp_boolean( $post_type['public'] ); ?>,
		"show_ui" => <?php echo disp_boolean( $post_type['show_ui'] ); ?>,
		"has_archive" => <?php echo disp_boolean( $post_type['has_archive'] ); ?>,
		"show_in_menu" => <?php echo disp_boolean( $post_type['show_in_menu'] ); ?>,
		"exclude_from_search" => <?php echo disp_boolean( $post_type['exclude_from_search'] ); ?>,
		"capability_type" => "<?php echo $post_type['capability_type']; ?>",
		"map_meta_cap" => <?php echo disp_boolean( $post_type['map_meta_cap'] ); ?>,
		"hierarchical" => <?php echo disp_boolean( $post_type['hierarchical'] ); ?>,
		"rewrite" => <?php echo $rewrite; ?>,
		"query_var" => <?php echo disp_boolean( $post_type['query_var'] ); ?>,
		<?php if ( !empty( $post_type['menu_position'] ) ) { ?>"menu_position" => <?php echo $post_type['menu_position']; ?>,<?php } ?>
		<?php if ( !empty( $post_type['menu_icon'] ) ) { ?>"menu_icon" => "<?php echo $post_type['menu_icon']; ?>",<?php } ?>
		<?php if ( !empty( $supports ) ) { ?>"supports" => <?php echo $supports; ?>,<?php } ?>
		<?php if ( !empty( $taxonomies ) ) { ?>"taxonomies" => <?php echo $taxonomies; ?><?php } ?>
	);
	register_post_type( "<?php echo $post_type['name']; ?>", $args );
<?php
}

/**
 * Import the posted JSON data from a separate export.
 *
 * @since 1.0.0
 *
 * @param array $postdata $_POST data as json.
 *
 * @return mixed false on nothing to do, otherwise void.
 */
function cptui_import_types_taxes_settings( $postdata = array() ) {
	if ( !isset( $postdata['cptui_post_import'] ) && !isset( $postdata['cptui_tax_import'] ) ) {
		return false;
	}

	$success = false;

	if ( !empty( $postdata['cptui_post_import'] ) ) {
		$data = stripslashes_deep( trim( $postdata['cptui_post_import'] ) );
		$settings = json_decode( $data, true );

		if ( $settings ) {
			if ( false !== get_option( 'cptui_post_types' ) ) {
				delete_option( 'cptui_post_types' );
			}

			$success = update_option( 'cptui_post_types', $settings );
		}
		return cptui_admin_notices( 'import', __( 'Post types', 'cpt-plugin' ), $success );

  	} elseif ( !empty( $postdata['cptui_tax_import'] ) ) {
  		$data = stripslashes_deep( trim( $postdata['cptui_tax_import'] ) );
		$settings = json_decode( $data, true );

		if ( $settings ) {
			if ( false !== get_option( 'cptui_taxonomies' ) ) {
				delete_option( 'cptui_taxonomies' );
			}

			$success = update_option( 'cptui_taxonomies', $settings );
		}
		return cptui_admin_notices( 'import', __( 'Taxonomies', 'cpt-plugin' ), $success );
  	}

	flush_rewrite_rules();

	return $success;
}
