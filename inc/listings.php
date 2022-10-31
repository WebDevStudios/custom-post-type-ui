<?php
/**
 * Custom Post Type UI Registered Content.
 *
 * @package CPTUI
 * @subpackage Listings
 * @author WebDevStudios
 * @since 1.1.0
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
 */
function cptui_listings_assets() {
	$current_screen = get_current_screen();

	if ( ! is_object( $current_screen ) || 'cpt-ui_page_cptui_listings' !== $current_screen->base ) {
		return;
	}

	if ( wp_doing_ajax() ) {
		return;
	}

	wp_enqueue_style( 'cptui-css' );
	wp_enqueue_script( 'cptui' );

	wp_localize_script( 'cptui', 'cptui_listings_data',
		[
			'confirm' => esc_html__( 'Are you sure you want to delete this? Deleting will NOT remove created content.', 'custom-post-type-ui' ),
		]
	);
}
add_action( 'admin_enqueue_scripts', 'cptui_listings_assets' );

/**
 * Output the content for the "Registered Types/Taxes" page.
 *
 * @since 1.1.0
 *
 * @internal
 */
function cptui_listings() {
	?>
		<div class="wrap cptui-listings">
			<?php
			/**
			 * Fires right inside the wrap div for the listings screen.
			 *
			 * @since 1.3.0
			 */
			do_action( 'cptui_inside_listings_wrap' );
			?>

			<h1 class="wp-heading-inline"><?php esc_html_e( 'Content types registered with Custom Post Type UI.', 'custom-post-type-ui' ); ?></h1>
			<a href="<?php echo esc_url( cptui_get_add_new_link( 'post_types' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New Post Type', 'custom-post-type-ui' ); ?></a>
			<a href="<?php echo esc_url( cptui_get_add_new_link( 'taxonomies' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New Taxonomy', 'custom-post-type-ui' ); ?></a>
			<?php
			$post_types = cptui_get_post_type_data();
			echo '<h2 id="post-types">' . esc_html__( 'Post Types', 'custom-post-type-ui' ) . '</h2>';
			if ( ! empty( $post_types ) ) {
				?>
			<p>
				<?php
				printf(
				/* translators: %s: Total count of registered CPTUI post types */
					esc_html__( 'Custom Post Type UI registered post types count total: %d', 'custom-post-type-ui' ),
					count( $post_types )
				);
				?>
			</p>

				<?php

				$post_type_table_heads = [
					esc_html__( 'Post Type', 'custom-post-type-ui' ),
					esc_html__( 'Settings', 'custom-post-type-ui' ),
					esc_html__( 'Supports', 'custom-post-type-ui' ),
					esc_html__( 'Taxonomies', 'custom-post-type-ui' ),
					esc_html__( 'Labels', 'custom-post-type-ui' ),
					esc_html__( 'Template Hierarchy', 'custom-post-type-ui' ),
				];

				/**
				 * Fires before the listing of registered post type data.
				 *
				 * @since 1.1.0
				 */
				do_action( 'cptui_before_post_type_listing' );
				?>
			<table class="wp-list-table widefat post-type-listing">
				<thead>
				<tr>
					<?php
					foreach ( $post_type_table_heads as $head ) {
						echo '<th>' . esc_html( $head ) . '</th>';
					}
					?>
				</tr>
				</thead>
				<tbody>
				<?php
				$counter = 1;
				$db_post_type_keys = array_keys( get_option( 'cptui_post_types', [] ) );
				foreach ( $post_types as $post_type => $post_type_settings ) {

					$rowclass = ( 0 === $counter % 2 ) ? '' : 'alternate';

					$strings    = [];
					$supports   = [];
					$taxonomies = [];
					$archive    = '';
					foreach ( $post_type_settings as $settings_key => $settings_value ) {
						if ( 'labels' === $settings_key ) {
							continue;
						}

						if ( is_string( $settings_value ) ) {
							$strings[ $settings_key ] = $settings_value;
						} else {
							if ( 'supports' === $settings_key ) {
								$supports[ $settings_key ] = $settings_value;
							}

							if ( 'taxonomies' === $settings_key ) {
								$taxonomies[ $settings_key ] = $settings_value;

								// In case they are not associated from the post type settings.
								if ( empty( $taxonomies['taxonomies'] ) ) {
									$taxonomies['taxonomies'] = get_object_taxonomies( $post_type );
								}
							}
						}
						$archive = get_post_type_archive_link( $post_type );
					}
					?>
						<tr class="<?php echo esc_attr( $rowclass ); ?>">
							<?php
							$edit_path          = 'admin.php?page=cptui_manage_post_types&action=edit&cptui_post_type=' . $post_type;
							$post_type_link_url = is_network_admin() ? network_admin_url( $edit_path ) : admin_url( $edit_path );
							?>
							<td class="plugins">
								<?php
								printf(
									'<a href="%s">%s</a><br/>
									<a href="%s">%s</a><br/>',
									esc_attr( $post_type_link_url ),
									sprintf(
										/* translators: %s: Post type slug */
										esc_html__( 'Edit %1$s (%2$s)', 'custom-post-type-ui' ),
										esc_html( $post_type_settings['label'] ),
										esc_html( $post_type )
									),
									esc_attr( admin_url( 'admin.php?page=cptui_tools&action=get_code#' . $post_type ) ),
									esc_html__( 'Get code', 'custom-post-type-ui' )
								);

								if ( $archive ) {
									?>
								<a href="<?php echo esc_attr( get_post_type_archive_link( $post_type ) ); ?>"><?php esc_html_e( 'View frontend archive', 'custom-post-type-ui' ); ?></a><br/>
								<?php } ?>

								<?php if ( in_array( $post_type, $db_post_type_keys, true ) ) { ?>
									<a class="cptui-delete-post-type delete" href="<?php echo esc_url( cptui_get_delete_listing_link( 'post_type', $post_type ) ); ?>"><?php esc_html_e( "Delete from database", 'custom-post-type-ui' ); ?></a>
								<?php } else { ?>
									<a href="<?php echo esc_url( cptui_get_impoort_listing_link( 'post_type', $post_type ) ); ?>"><?php esc_html_e( 'Import from Local JSON', 'custom-post-type-ui' ); ?></a>
								<?php } ?>
							</td>
							<td>
								<?php
								foreach ( $strings as $key => $value ) {
									printf( '<strong>%s:</strong> ', esc_html( $key ) );
									if ( in_array( $value, [ '1', '0' ], true ) ) {
										echo esc_html( disp_boolean( $value ) );
									} else {
										echo ! empty( $value ) ? esc_html( $value ) : '""';
									}
									echo '<br/>';
								}
								?>
							</td>
							<td>
								<?php
								foreach ( $supports['supports'] as $support ) {
									echo esc_html( $support ) . '<br/>';
								}
								?>
							</td>
							<td>
								<?php
								if ( ! empty( $taxonomies['taxonomies'] ) ) {
									foreach ( $taxonomies['taxonomies'] as $taxonomy ) {
										echo esc_html( $taxonomy ) . '<br/>';
									}
								} else {
									printf(
										'<span aria-hidden="true">—</span><span class="screen-reader-text">%s</span>',
										esc_html__( 'No associated taxonomies', 'custom-post-type-ui' )
									);
								}
								?>
							</td>
							<td>
								<?php
								$maybe_empty = array_filter( $post_type_settings['labels'] );
								if ( ! empty( $maybe_empty ) ) {
									foreach ( $post_type_settings['labels'] as $key => $value ) {
										if ( 'parent' === $key && array_key_exists( 'parent_item_colon', $post_type_settings['labels'] ) ) {
											continue;
										}
										printf(
											'<strong>%s</strong>: %s<br/>',
											esc_html( $key ),
											esc_html( $value )
										);
									}
								} else {
									printf(
										'<span aria-hidden="true">—</span><span class="screen-reader-text">%s</span>',
										esc_html__( 'No custom labels to display', 'custom-post-type-ui' )
									);
								}
								?>
							</td>
							<td>
								<p><strong><?php esc_html_e( 'Archives file name examples.', 'custom-post-type-ui' ); ?></strong><br/>
								archive-<?php echo esc_html( $post_type ); ?>.php<br/>
								archive.php<br/>
								index.php
								</p>

								<p><strong><?php esc_html_e( 'Single Posts file name examples.', 'custom-post-type-ui' ); ?></strong><br/>
								single-<?php echo esc_html( $post_type ); ?>-post_slug.php *<br/>
								single-<?php echo esc_html( $post_type ); ?>.php<br/>
								single.php<br/>
								singular.php<br/>
								index.php
								</p>

								<p>
									<?php esc_html_e( '*Replace "post_slug" with the slug of the actual post.', 'custom-post-type-ui' ); ?>
								</p>

								<p>
									<?php
									printf(
										'<a href="https://developer.wordpress.org/themes/basics/template-hierarchy/">%s</a>',
										esc_html__( 'Template hierarchy Theme Handbook', 'custom-post-type-ui' )
									);
									?>
								</p>
							</td>
						</tr>

					<?php
					$counter++;
				}
				?>
				</tbody>
				<tfoot>
				<tr>
					<?php
					foreach ( $post_type_table_heads as $head ) {
						echo '<th>' . esc_html( $head ) . '</th>';
					}
					?>
				</tr>
				</tfoot>
			</table>
				<?php
				/**
				 * Fires after the listing of registered post type data.
				 *
				 * @since 1.3.0
				 */
				do_action( 'cptui_after_post_type_listing' );
			} else {

				/**
				 * Fires when there are no registered post types to list.
				 *
				 * @since 1.3.0
				 */
				do_action( 'cptui_no_post_types_listing' );
			}

			$taxonomies = cptui_get_taxonomy_data();
			echo '<h2 id="taxonomies">' . esc_html__( 'Taxonomies', 'custom-post-type-ui' ) . '</h2>';
			if ( ! empty( $taxonomies ) ) {
				?>
				<p>
				<?php
				printf(
					/* translators: %s: Total count of CPTUI registered taxonomies */
					esc_html__( 'Custom Post Type UI registered taxonomies count total: %d', 'custom-post-type-ui' ),
					count( $taxonomies )
				);
				?>
				</p>

				<?php

				$taxonomy_table_heads = [
					esc_html__( 'Taxonomy', 'custom-post-type-ui' ),
					esc_html__( 'Settings', 'custom-post-type-ui' ),
					esc_html__( 'Post Types', 'custom-post-type-ui' ),
					esc_html__( 'Labels', 'custom-post-type-ui' ),
					esc_html__( 'Template Hierarchy', 'custom-post-type-ui' ),
				];

				/**
				 * Fires before the listing of registered taxonomy data.
				 *
				 * @since 1.1.0
				 */
				do_action( 'cptui_before_taxonomy_listing' );
				?>
				<table class="wp-list-table widefat taxonomy-listing">
					<thead>
					<tr>
						<?php
						foreach ( $taxonomy_table_heads as $head ) {
							echo '<th>' . esc_html( $head ) . '</th>';
						}
						?>
					</tr>
					</thead>
					<tbody>
					<?php
					$counter = 1;
					$db_taxonomy_keys = array_keys( get_option( 'cptui_taxonomies', [] ) );
					foreach ( $taxonomies as $taxonomy => $taxonomy_settings ) {

						$rowclass = ( 0 === $counter % 2 ) ? '' : 'alternate';

						$strings      = [];
						$object_types = [];
						foreach ( $taxonomy_settings as $settings_key => $settings_value ) {
							if ( 'labels' === $settings_key ) {
								continue;
							}

							if ( is_string( $settings_value ) ) {
								$strings[ $settings_key ] = $settings_value;
							} else {
								if ( 'object_types' === $settings_key ) {
									$object_types[ $settings_key ] = $settings_value;

									// In case they are not associated from the post type settings.
									if ( empty( $object_types['object_types'] ) ) {
										$types                        = get_taxonomy( $taxonomy );
										$object_types['object_types'] = $types->object_type;
									}
								}
							}
						}
						?>
							<tr class="<?php echo esc_attr( $rowclass ); ?>">
								<?php
								$edit_path         = 'admin.php?page=cptui_manage_taxonomies&action=edit&cptui_taxonomy=' . $taxonomy;
								$taxonomy_link_url = is_network_admin() ? network_admin_url( $edit_path ) : admin_url( $edit_path );
								?>
								<td class="plugins">
									<?php
									printf(
										'<a href="%s">%s</a><br/>
										<a href="%s">%s</a>',
										esc_attr( $taxonomy_link_url ),
										sprintf(
											/* translators: %s: Taxonomy slug */
											esc_html__( 'Edit %1$s (%2$s)', 'custom-post-type-ui' ),
											esc_html( $taxonomy_settings['label'] ),
											esc_html( $taxonomy )
										),
										esc_attr( admin_url( 'admin.php?page=cptui_tools&action=get_code#' . $taxonomy ) ),
										esc_html__( 'Get code', 'custom-post-type-ui' )
									);
									?><br/>
									<?php if ( in_array( $taxonomy, $db_taxonomy_keys, true ) ) { ?>
										<a class="cptui-delete-taxonomy delete" href="<?php echo esc_url( cptui_get_delete_listing_link( 'taxonomy', $taxonomy ) ); ?>"><?php esc_html_e( 'Delete from database', 'custom-post-type-ui' ); ?></a>
									<?php } else { ?>
										<a href="<?php echo esc_url( cptui_get_impoort_listing_link( 'taxonomy', $taxonomy ) ); ?>"><?php esc_html_e( 'Import from Local JSON', 'custom-post-type-ui' ); ?></a>
									<?php } ?>
								</td>
								<td>
									<?php
									foreach ( $strings as $key => $value ) {
										printf( '<strong>%s:</strong> ', esc_html( $key ) );
										if ( in_array( $value, [ '1', '0' ], true ) ) {
											echo esc_html( disp_boolean( $value ) );
										} else {
											echo ! empty( $value ) ? esc_html( $value ) : '""';
										}
										echo '<br/>';
									}
									?>
								</td>
								<td>
									<?php
									if ( ! empty( $object_types['object_types'] ) ) {
										foreach ( $object_types['object_types'] as $type ) {
											echo esc_html( $type ) . '<br/>';
										}
									}
									?>
								</td>
								<td>
									<?php
									$maybe_empty = array_filter( $taxonomy_settings['labels'] );
									if ( ! empty( $maybe_empty ) ) {
										foreach ( $taxonomy_settings['labels'] as $key => $value ) {
											printf(
												'<strong>%s</strong>: %s<br/>',
												esc_html( $key ),
												esc_html( $value )
											);
										}
									} else {
										printf(
											'<span aria-hidden="true">—</span><span class="screen-reader-text">%s</span>',
											esc_html__( 'No custom labels to display', 'custom-post-type-ui' )
										);
									}
									?>
								</td>
								<td>
									<p><strong><?php esc_html_e( 'Archives file name examples.', 'custom-post-type-ui' ); ?></strong><br />
										taxonomy-<?php echo esc_html( $taxonomy ); ?>-term_slug.php *<br />
										taxonomy-<?php echo esc_html( $taxonomy ); ?>.php<br />
										taxonomy.php<br />
										archive.php<br />
										index.php
									</p>

									<p>
										<?php esc_html_e( '*Replace "term_slug" with the slug of the actual taxonomy term.', 'custom-post-type-ui' ); ?>
									</p>
									<p>
										<?php
										printf(
											'<a href="https://developer.wordpress.org/themes/basics/template-hierarchy/">%s</a>',
											esc_html__( 'Template hierarchy Theme Handbook', 'custom-post-type-ui' )
										);
										?>
									</p>
								</td>
							</tr>

						<?php
						$counter++;
					}
					?>
					</tbody>
					<tfoot>
					<tr>
						<?php
						foreach ( $taxonomy_table_heads as $head ) {
							echo '<th>' . esc_html( $head ) . '</th>';
						}
						?>
					</tr>
					</tfoot>
				</table>
				<?php
				/**
				 * Fires after the listing of registered taxonomy data.
				 *
				 * @since 1.3.0
				 */
				do_action( 'cptui_after_taxonomy_listing' );

			} else {

				/**
				 * Fires when there are no registered taxonomies to list.
				 *
				 * @since 1.3.0
				 */
				do_action( 'cptui_no_taxonomies_listing' );
			}
			?>

		</div>
	<?php
}

/**
 * Displays a message for when no post types are registered.
 *
 * Uses the `cptui_no_post_types_listing` hook.
 *
 * @since 1.3.0
 *
 * @internal
 */
function cptui_no_post_types_to_list() {
	echo '<p>' . sprintf(
		/* translators: 1st %s: Link to manage post types section 2nd %s Link text */
		esc_html__( 'No post types registered for display. Visit %s to get started.', 'custom-post-type-ui' ),
		sprintf(
			'<a href="%s">%s</a>',
			esc_attr( admin_url( 'admin.php?page=cptui_manage_post_types' ) ),
			esc_html__( 'Add/Edit Post Types', 'custom-post-type-ui' )
		)
	) . '</p>';
}
add_action( 'cptui_no_post_types_listing', 'cptui_no_post_types_to_list' );

/**
 * Displays a message for when no taxonomies are registered.
 *
 * Uses the `cptui_no_taxonomies_listing` hook.
 *
 * @since 1.3.0
 *
 * @internal
 */
function cptui_no_taxonomies_to_list() {
	echo '<p>' . sprintf(
		/* translators: %s: Link to manage taxonomies section */
		esc_html__( 'No taxonomies registered for display. Visit %s to get started.', 'custom-post-type-ui' ),
		sprintf(
			'<a href="%s">%s</a>',
			esc_attr( admin_url( 'admin.php?page=cptui_manage_taxonomies' ) ),
			esc_html__( 'Add/Edit Taxonomies', 'custom-post-type-ui' )
		)
	) . '</p>';
}
add_action( 'cptui_no_taxonomies_listing', 'cptui_no_taxonomies_to_list' );

/**
 * Handle the deletion of registered content types from within the CPTUI Listings page.
 *
 * @since 1.14.0
 */
function cptui_listings_delete_post_type_or_taxonomy() {

	if ( wp_doing_ajax() ) {
		return;
	}

	if ( empty( $_GET['page'] ) || 'cptui_listings' !== $_GET['page'] ) {
		return;
	}

	$result = false;
	$values = [];
	if ( ! empty( $_GET['delete_post_type'] ) ) {
		$post_type = filter_input( INPUT_GET, 'delete_post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! wp_verify_nonce( $_GET[ 'delete_' . $post_type ], 'do_delete_' . $post_type ) ) {
			return;
		}
		$values['post_type'] = $post_type;
		$result = cptui_delete_post_type( $post_type, true );
	}

	if ( ! empty( $_GET['delete_taxonomy'] ) ) {
		$taxonomy = filter_input( INPUT_GET, 'delete_taxonomy', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! wp_verify_nonce( $_GET[ 'delete_' . $taxonomy ], 'do_delete_' . $taxonomy ) ) {
			return;
		}
		$values['taxonomy'] = $taxonomy;
		$result = cptui_delete_taxonomy( $taxonomy, true );
	}

	if ( $result ) {
		add_filter(
			'cptui_get_object_from_post_global',
			function( $orig_value ) use ( $values ) {
				if ( ! empty( $values['post_type'] ) ) {
					return $values['post_type'];
				}
				if ( ! empty( $values['taxonomy'] ) ) {
					return $values['taxonomy'];
				}
				return $orig_value;
			}
		);
		if ( is_callable( "cptui_{$result}_admin_notice" ) ) {
			add_action( 'admin_notices', "cptui_{$result}_admin_notice" );
		}
	}
}
add_action( 'admin_init', 'cptui_listings_delete_post_type_or_taxonomy', 8 );

/**
 * Handle the import of individual content types from within the CPTUI Listings page.
 *
 * @since 1.14.0
 */
function cptui_listings_import_post_type_or_taxonomy() {

	if ( wp_doing_ajax() ) {
		return;
	}

	if ( empty( $_GET['page'] ) || 'cptui_listings' !== $_GET['page'] ) {
		return;
	}

	$result = false;
	$values = [];
	if ( ! empty( $_GET['import_post_type'] ) ) {
		$post_type = filter_input( INPUT_GET, 'import_post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! wp_verify_nonce( $_GET[ 'import_' . $post_type ], 'do_import_' . $post_type ) ) {
			return;
		}
		$values['post_type'] = $post_type;
		$content = file_get_contents( CPTUI\get_specific_type_tax_file_name( 'post_type', $post_type ) );
		$content_decoded = json_decode( $content, true );
		$database_content = cptui_get_post_type_data();
		$database_content[ $post_type ] = $content_decoded;
		update_option( 'cptui_post_types', $database_content );
	}

	if ( ! empty( $_GET['import_taxonomy'] ) ) {
		$taxonomy = filter_input( INPUT_GET, 'import_taxonomy', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! wp_verify_nonce( $_GET[ 'import_' . $taxonomy ], 'do_import_' . $taxonomy ) ) {
			return;
		}
		$values['taxonomy'] = $taxonomy;
		$content = file_get_contents( CPTUI\get_specific_type_tax_file_name( 'taxonomy', $taxonomy ) );
		$content_decoded = json_decode( $content, true );
		$database_content = cptui_get_taxonomy_data();
		$database_content[ $taxonomy ] = $content_decoded;
		update_option( 'cptui_taxonomies', $database_content );
	}

	if ( $result ) {
		add_filter(
			'cptui_get_object_from_post_global',
			function ( $orig_value ) use ( $values ) {
				if ( ! empty( $values['post_type'] ) ) {
					return $values['post_type'];
				}
				if ( ! empty( $values['taxonomy'] ) ) {
					return $values['taxonomy'];
				}

				return $orig_value;
			}
		);
		if ( is_callable( "cptui_{$result}_admin_notice" ) ) {
			add_action( 'admin_notices', "cptui_{$result}_admin_notice" );
		}
	}
}
add_action( 'admin_init', 'cptui_listings_import_post_type_or_taxonomy', 8 );
