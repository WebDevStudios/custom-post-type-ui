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

			/**
			 * Fires before the listing of registered post type data.
			 *
			 * @since 1.1.0
			 */
			do_action( 'cptui_before_post_type_listing' );
			?>
			<table class="wp-list-table widefat">
				<tr>
					<th><?php _e( 'Post Type', 'custom-post-type-ui' ); ?></th>
					<th><?php _e( 'Settings', 'custom-post-type-ui' ); ?></th>
					<th><?php _e( 'Supports', 'custom-post-type-ui' ); ?></th>
					<th><?php _e( 'Taxonomies', 'custom-post-type-ui' ); ?></th>
					<th><?php _e( 'Labels', 'custom-post-type-ui' ); ?></th>
				</tr>
				<?php
				$counter = 1;
				foreach ( $post_types as $post_type => $post_type_settings ) {

					$rowclass = ( $counter % 2 == 0 ) ? '' : 'alternate';

					$strings = array();
					$supports = array();
					$taxonomies = array();
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
							<td><a href="<?php echo admin_url( 'admin.php?page=cptui_manage_post_types&action=edit&cptui_post_type=' . $post_type ); ?>"><?php echo $post_type; ?></a><br/><hr/>
								<a href="<?php echo admin_url( 'admin.php?page=cptui_manage_post_types&action=edit&cptui_post_type=' . $post_type ); ?>"><?php _e( 'Edit', 'custom-post-type-ui' ); ?></a>
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
						</tr>

					<?php
				$counter++;
				}
				?>
				<tr>
					<th><?php _e( 'Post Type', 'custom-post-type-ui' ); ?></th>
					<th><?php _e( 'Settings', 'custom-post-type-ui' ); ?></th>
					<th><?php _e( 'Supports', 'custom-post-type-ui' ); ?></th>
					<th><?php _e( 'Taxonomies', 'custom-post-type-ui' ); ?></th>
					<th><?php _e( 'Labels', 'custom-post-type-ui' ); ?></th>
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

				/**
				 * Fires before the listing of registered taxonomy data.
				 *
				 * @since 1.1.0
				 */
				do_action( 'cptui_before_taxonomy_listing' );
				?>
				<table class="wp-list-table widefat">
					<tr>
						<th><?php _e( 'Taxonomy', 'custom-post-type-ui' ); ?></th>
						<th><?php _e( 'Settings', 'custom-post-type-ui' ); ?></th>
						<th><?php _e( 'Post Types', 'custom-post-type-ui' ); ?></th>
						<th><?php _e( 'Labels', 'custom-post-type-ui' ); ?></th>
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
									if ( empty( $object_types['taxonomies'] ) ) {
										$types = get_taxonomy( $taxonomy );
										$object_types['types'] = $types->object_type;
									}
								}
							}
						}
						?>
							<tr class="<?php echo $rowclass; ?>">
								<td><a href="<?php echo admin_url( 'admin.php?page=cptui_manage_taxonomies&action=edit&cptui_taxonomy=' . $taxonomy ); ?>"><?php echo $taxonomy; ?></a><br/><hr/>
									<a href="<?php echo admin_url( 'admin.php?page=cptui_manage_taxonomies&action=edit&cptui_taxonomy=' . $taxonomy ); ?>"><?php _e( 'Edit', 'custom-post-type-ui' ); ?></a>
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
									if ( !empty( $object_types['types'] ) ) {
										foreach ( $object_types['types'] as $type ) {
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
							</tr>

						<?php
					$counter++;
					}
					?>
					<tr>
						<th><?php _e( 'Taxonomy', 'custom-post-type-ui' ); ?></th>
						<th><?php _e( 'Settings', 'custom-post-type-ui' ); ?></th>
						<th><?php _e( 'Post Types', 'custom-post-type-ui' ); ?></th>
						<th><?php _e( 'Labels', 'custom-post-type-ui' ); ?></th>
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
