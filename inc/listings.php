<?php

# Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function cptui_listings() {
		?>
		<div class="wrap">

			<h1><?php _e( 'Post Types and Taxonomies registered by Custom Post Type UI.', 'custom-post-type-ui' ); ?></h1>
			<?php
			$post_types = get_option( 'cptui_post_types' );
			echo '<h2>' . __( 'Post Types', 'custom-post-type-ui' ) . '</h2>';
			if ( !empty( $post_types ) ) {
			?>
			<p><?php printf( __( 'Total count: %d', 'custom-post-type-ui' ), count( $post_types ) ); ?></p>

			<?php

			$post_type_table_heads = array(
				__( 'Post Type', 'custom-post-type-ui' ),
				__( 'Settings', 'custom-post-type-ui' ),
				__( 'Supports', 'custom-post-type-ui' ),
				__( 'Taxonomies', 'custom-post-type-ui' ),
				__( 'Labels', 'custom-post-type-ui' ),
				__( 'Template Hierarchy', 'custom-post-type-ui' )
			);

			/**
			 * Fires before the listing of registered post type data.
			 *
			 * @since 1.1.0
			 */
			do_action( 'cptui_before_post_type_listing' );
			?>
			<table class="wp-list-table widefat">
				<tr>
					<?php foreach( $post_type_table_heads as $head ) {
						echo '<th>' . $head . '</th>';
					} ?>
				</tr>
				<?php
				$counter = 1;
				foreach ( $post_types as $post_type => $post_type_settings ) {

					$rowclass = ( $counter % 2 == 0 ) ? '' : 'alternate';

					$strings = array();
					$supports = array();
					$taxonomies = array();
					$archive = '';
					foreach( $post_type_settings as $settings_key => $settings_value ) {
						if ( 'labels' == $settings_key ) {
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

								# In case they are not associated from the post type settings
								if ( empty( $taxonomies['taxonomies'] ) ) {
									$taxonomies['taxonomies'] = get_object_taxonomies( $post_type );
								}
							}
						}
						$archive = get_post_type_archive_link( $post_type );
					}
					?>
						<tr class="<?php echo $rowclass; ?>">
							<?php $post_type_link_url = admin_url( 'admin.php?page=cptui_manage_post_types&action=edit&cptui_post_type=' . $post_type ); ?>
							<td><a href="<?php echo $post_type_link_url; ?>"><?php printf( __( 'Edit %s', 'custom-post-type-ui' ), $post_type ); ?></a>
								<?php if ( $archive ) { ?>
								|
								<a href="<?php echo get_post_type_archive_link( $post_type ); ?>"><?php _e( 'View frontend archive', 'custom-post-type-ui' ); ?></a>
								<?php } ?>
							</td>
							<td>
								<?php foreach ( $strings as $key => $value ) {
									printf( '<strong>%s:</strong> ', $key );
									if ( in_array( $value, array( '1', '0' ) ) ) {
										echo disp_boolean( $value );
									} else {
										echo $value;
									}
									echo '<br/>';
								} ?>
							</td>
							<td>
								<?php foreach ( $supports['supports'] as $support ) {
									echo $support . '<br/>';
								} ?>
							</td>
							<td>
								<?php
								foreach ( $taxonomies['taxonomies'] as $taxonomy ) {
									echo $taxonomy . '<br/>';
								} ?>
							</td>
							<td>
								<?php
								$maybe_empty = array_filter( $post_type_settings['labels'] );
								if ( !empty( $maybe_empty ) ) {
									foreach ( $post_type_settings['labels'] as $key => $value ) {
										echo $key . ': ' . $value . '<br/>';
									}
								} else {
									_e( 'No custom labels to display', 'custom-post-type-ui' );
								}
								?>
							</td>
							<td>
								<p><strong><?php _e( 'Archives file name examples.', 'custom-post-type-ui' ); ?></strong><br/>
								archive-<?php echo $post_type; ?>.php<br/>
								archive.php<br/>
								index.php
								</p>

								<p><strong><?php _e( 'Single Posts file name examples.', 'custom-post-type-ui' ); ?></strong><br/>
								single-<?php echo $post_type; ?>.php<br/>
								single.php<br/>
								singular.php(WP 4.3+)<br/>
								index.php
								</p>

								<p><?php printf(
										'<a href="https://developer.wordpress.org/themes/basics/template-hierarchy/">%s</a>',
										__( 'Template hierarchy Theme Handbook', 'custom-post-type-ui' )
									); ?>
								</p>
							</td>
						</tr>

					<?php
				$counter++;
				}
				?>
				<tr>
					<?php foreach ( $post_type_table_heads as $head ) {
						echo '<th>' . $head . '</th>';
					} ?>
				</tr>
			</table>
			<?php
			} else {
				echo '<p>' . sprintf( __( 'No post types registered for display. Visit %s to get started.', 'custom-post-type-ui' ),
					sprintf( '<a href="%s">%s</a>',
						admin_url( 'admin.php?page=cptui_manage_post_types' ),
						__( 'Add/Edit Post Types', 'custom-post-type-ui' )
					)
				) . '</p>';
			}

			$taxonomies = get_option( 'cptui_taxonomies' );
			echo '<h2>' . __( 'Taxonomies', 'custom-post-type-ui' ) . '</h2>';
			if ( !empty( $taxonomies ) ) {
				?>
				<p><?php printf( __( 'Total count: %d', 'custom-post-type-ui' ), count( $taxonomies ) ); ?></p>

				<?php

				$taxonomy_table_heads = array(
					__( 'Taxonomy', 'custom-post-type-ui' ),
					__( 'Settings', 'custom-post-type-ui' ),
					__( 'Post Types', 'custom-post-type-ui' ),
					__( 'Labels', 'custom-post-type-ui' ),
					__( 'Template Hierarchy', 'custom-post-type-ui' )
				);

				/**
				 * Fires before the listing of registered taxonomy data.
				 *
				 * @since 1.1.0
				 */
				do_action( 'cptui_before_taxonomy_listing' );
				?>
				<table class="wp-list-table widefat">
					<tr>
						<?php foreach ( $taxonomy_table_heads as $head ) {
							echo '<th>' . $head . '</th>';
						} ?>
					</tr>
					<?php
					$counter = 1;
					foreach ( $taxonomies as $taxonomy => $taxonomy_settings ) {

						$rowclass = ( $counter % 2 == 0 ) ? '' : 'alternate';

						$strings = array();
						$object_types = array();
						foreach( $taxonomy_settings as $settings_key => $settings_value ) {
							if ( 'labels' == $settings_key ) {
								continue;
							}

							if ( is_string( $settings_value ) ) {
								$strings[ $settings_key ] = $settings_value;
							} else {
								if ( 'object_types' === $settings_key ) {
									$object_types[ $settings_key ] = $settings_value;

									# In case they are not associated from the post type settings
									if ( empty( $object_types['object_types'] ) ) {
										$types = get_taxonomy( $taxonomy );
										$object_types['object_types'] = $types->object_type;
									}
								}
							}
						}
						?>
							<tr class="<?php echo $rowclass; ?>">
								<?php $taxonomy_link_url = admin_url( 'admin.php?page=cptui_manage_taxonomies&action=edit&cptui_taxonomy=' . $taxonomy ); ?>
								<td><a href="<?php echo $taxonomy_link_url; ?>"><?php echo $taxonomy; ?></a><br/><hr/>
									<a href="<?php echo $taxonomy_link_url; ?>"><?php printf( __( 'Edit %s', 'custom-post-type-ui' ), $taxonomy ); ?></a>
								</td>
								<td>
									<?php foreach ( $strings as $key => $value ) {
										printf( '<strong>%s:</strong> ', $key );
										if ( in_array( $value, array( '1', '0' ) ) ) {
											echo disp_boolean( $value );
										} else {
											echo $value;
										}
										echo '<br/>';
									} ?>
								</td>
								<td>
									<?php
									if ( !empty( $object_types['object_types'] ) ) {
										foreach ( $object_types['object_types'] as $type ) {
											echo $type . '<br/>';
										}
									} ?>
								</td>
								<td>
									<?php
									$maybe_empty = array_filter( $taxonomy_settings['labels'] );
									if ( !empty( $maybe_empty ) ) {
										foreach ( $taxonomy_settings['labels'] as $key => $value ) {
											echo $key . ': ' . $value . '<br/>';
										}
									} else {
										_e( 'No custom labels to display', 'custom-post-type-ui' );
									}
									?>
								</td>
								<td>
									<p><strong><?php _e( 'Archives', 'custom-post-type-ui' ); ?></strong><br />
										taxonomy-<?php echo $taxonomy; ?>-term_slug.php *<br />
										taxonomy-<?php echo $taxonomy; ?>.php<br />
										taxonomy.php<br />
										archive.php<br />
										index.php
									</p>

									<p>
										<?php _e( '*Replace "term_slug" with the slug of the actual taxonomy term.', 'custom-post-type-ui' ); ?>
									</p>
									<p><?php printf(
											'<a href="https://developer.wordpress.org/themes/basics/template-hierarchy/">%s</a>',
											__( 'Template hierarchy Theme Handbook', 'custom-post-type-ui' )
										); ?></p>
								</td>
							</tr>

						<?php
					$counter++;
					}
					?>
					<tr>
						<?php foreach ( $taxonomy_table_heads as $head ) {
							echo '<th>' . $head . '</th>';
						} ?>
					</tr>
				</table>
			<?php
				} else {
					echo '<p>' . sprintf( __( 'No taxonomies registered for display. Visit %s to get started.', 'custom-post-type-ui' ),
							sprintf( '<a href="%s">%s</a>',
								admin_url( 'admin.php?page=cptui_manage_taxonomies' ),
								__( 'Add/Edit Taxonomies', 'custom-post-type-ui' )
							)
						) . '</p>';
				}
			?>

		</div>
	<?php
}
