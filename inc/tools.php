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
			'text'          => esc_html__( 'Post Types', 'custom-post-type-ui' ),
			'classes'       => $classes,
			'url'           => cptui_admin_url( 'admin.php?page=cptui_' . $current_page ),
			'aria-selected' => 'false',
		];

		$tabs['tabs']['taxonomies'] = [
			'text'          => esc_html__( 'Taxonomies', 'custom-post-type-ui' ),
			'classes'       => $classes,
			'url'           => esc_url( add_query_arg( [ 'action' => 'taxonomies' ], cptui_admin_url( 'admin.php?page=cptui_' . $current_page ) ) ),
			'aria-selected' => 'false',
		];

		$tabs['tabs']['get_code'] = [
			'text'          => esc_html__( 'Get Code', 'custom-post-type-ui' ),
			'classes'       => $classes,
			'url'           => esc_url( add_query_arg( [ 'action' => 'get_code' ], cptui_admin_url( 'admin.php?page=cptui_' . $current_page ) ) ),
			'aria-selected' => 'false',
		];

		$tabs['tabs']['debuginfo'] = [
			'text'          => esc_html__( 'Debug Info', 'custom-post-type-ui' ),
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
	if ( ! empty( $_GET ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		if ( ! empty( $_GET['action'] ) && 'taxonomies' === $_GET['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			$tab = 'taxonomies';
		} elseif ( ! empty( $_GET['action'] ) && 'get_code' === $_GET['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			$tab = 'get_code';
		} elseif ( ! empty( $_GET['action'] ) && 'debuginfo' === $_GET['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification
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
	$third_party_taxonomy_data = apply_filters( 'cptui_third_party_taxonomy_import', false );

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
			if ( false === ( $success = apply_filters( 'cptui_post_type_import_delete_save', false, $postdata ) ) ) { // phpcs:ignore.
				$success = delete_option( 'cptui_post_types' );
			}
		}

		if ( $settings ) {
			if ( false !== cptui_get_post_type_data() ) {
				/** This filter is documented in /inc/import-export.php */
				if ( false === ( $success = apply_filters( 'cptui_post_type_import_delete_save', false, $postdata ) ) ) { // phpcs:ignore.
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
			if ( false === ( $success = apply_filters( 'cptui_post_type_import_update_save', false, $postdata ) ) ) { // phpcs:ignore.
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
			if ( false === ( $success = apply_filters( 'cptui_taxonomy_import_delete_save', false, $postdata ) ) ) { // phpcs:ignore.
				$success = delete_option( 'cptui_taxonomies' );
			}
		}

		if ( $settings ) {
			if ( false !== cptui_get_taxonomy_data() ) {
				/** This filter is documented in /inc/import-export.php */
				if ( false === ( $success = apply_filters( 'cptui_taxonomy_import_delete_save', false, $postdata ) ) ) { // phpcs:ignore.
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
			if ( false === ( $success = apply_filters( 'cptui_taxonomy_import_update_save', false, $postdata ) ) ) { // phpcs:ignore.
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
		<?php if ( ! empty( $_GET ) && empty( $_GET['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification ?>
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
						foreach ( $cptui_post_types as $type => $values ) {
							$cptui_post_types[ $type ]['description'] = wp_slash( html_entity_decode( $values['description'] ) );
						}
						$content = wp_json_encode( $cptui_post_types );
					} else {
						$content = esc_html__( 'No post types registered yet.', 'custom-post-type-ui' );
					}
					?>
					<textarea title="<?php esc_attr_e( 'To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'custom-post-type-ui' ); ?>" onclick="this.focus();this.select();" onfocus="this.focus();this.select();" readonly="readonly" aria-readonly="true" class="cptui_post_import" id="cptui_post_export" name="cptui_post_export"><?php echo $content; // phpcs:ignore. ?></textarea>

					<p>
						<strong><?php esc_html_e( 'Use the content above to import current post types into a different WordPress site. You can also use this to simply back up your post type settings.', 'custom-post-type-ui' ); ?></strong>
					</p>
				</td>
			</tr>
		<?php } elseif ( ! empty( $_GET ) && 'taxonomies' === $_GET['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification ?>
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
						foreach ( $cptui_taxonomies as $tax => $values ) {
							$cptui_taxonomies[ $tax ]['description'] = wp_slash( html_entity_decode( $values['description'] ) );
						}
						$content = wp_json_encode( $cptui_taxonomies );
					} else {
						$content = esc_html__( 'No taxonomies registered yet.', 'custom-post-type-ui' );
					}
					?>
					<textarea title="<?php esc_attr_e( 'To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'custom-post-type-ui' ); ?>" onclick="this.focus();this.select()" onfocus="this.focus();this.select();" readonly="readonly" aria-readonly="true" class="cptui_tax_import" id="cptui_tax_export" name="cptui_tax_export"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput ?></textarea>

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
	 // phpcs:ignore.
	if ( ! empty( $_POST ) && // phpcs:ignore WordPress.Security.NonceVerification
		( ! empty( $_POST['cptui_post_import'] ) && isset( $_POST['cptui_post_import'] ) ) || // phpcs:ignore WordPress.Security.NonceVerification
		( ! empty( $_POST['cptui_tax_import'] ) && isset( $_POST['cptui_tax_import'] ) ) // phpcs:ignore WordPress.Security.NonceVerification
	) {
		$data              = [];
		$decoded_post_data = null;
		$decoded_tax_data  = null;
		if ( ! empty( $_POST['cptui_post_import'] ) ) {  // phpcs:ignore.
			$decoded_post_data = json_decode( stripslashes_deep( trim( $_POST['cptui_post_import'] ) ), true ); // phpcs:ignore.
		}

		if ( ! empty( $_POST['cptui_tax_import'] ) ) {  // phpcs:ignore.
			$decoded_tax_data = json_decode( stripslashes_deep( trim( $_POST['cptui_tax_import'] ) ), true ); // phpcs:ignore.
		}

		if (
			empty( $decoded_post_data ) &&
			empty( $decoded_tax_data ) &&
			(
				! empty( $_POST['cptui_post_import'] ) && '{""}' !== stripslashes_deep( trim( $_POST['cptui_post_import'] ) ) // phpcs:ignore.
			) &&
			(
				! empty( $_POST['cptui_tax_import'] ) && '{""}' !== stripslashes_deep( trim( $_POST['cptui_tax_import'] ) ) // phpcs:ignore.
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
		if ( ! empty( $_POST['cptui_post_import'] ) && '{""}' === stripslashes_deep( trim( $_POST['cptui_post_import'] ) ) ) { // phpcs:ignore.
			$data['delete'] = 'type_true';
		}
		if ( ! empty( $_POST['cptui_tax_import'] ) && '{""}' === stripslashes_deep( trim( $_POST['cptui_tax_import'] ) ) ) { // phpcs:ignore.
			$data['delete'] = 'tax_true';
		}
		$success = cptui_import_types_taxes_settings( $data );
		add_action( 'admin_notices', "cptui_{$success}_admin_notice" );
	}
}
add_action( 'init', 'cptui_do_import_types_taxes', 8 );
