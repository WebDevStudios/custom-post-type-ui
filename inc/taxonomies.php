<?php
/**
 * Add our cptui.js file, with dependencies on jQuery and jQuery UI
 *
 * @since  0.9
 *
 * @return mixed  js scripts
 */
function cpt_taxonomies_enqueue_scripts() {
	wp_enqueue_script( 'cptui', plugins_url( 'js/cptui.js' , dirname(__FILE__) ) . '', array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion' ), '0.9', true );
}
add_action( 'admin_enqueue_scripts', 'cpt_taxonomies_enqueue_scripts' );

add_action( 'admin_menu', 'taxonomies_admin_menu' );
function taxonomies_admin_menu() {
	add_submenu_page( 'cpt_main_menu', __( 'Add/Edit Taxonomies', 'cpt-plugin' ), __( 'Add/Edit Taxonomies', 'cpt-plugin' ), 'manage_options', 'cptui_manage_taxonomies', 'cptui_manage_taxonomies' );
}

function cptui_manage_taxonomies() {

	if ( $success = cptui_get_taxonomy_successes() ) {
		echo $success;
	}

	if ( $errors = cptui_get_taxonomy_errors() ) {
		echo $errors;
	}

	//Create our tabs.
	cpt_settings_tab_menu( $page = 'taxonomies' );

	if ( !empty( $_GET ) && 'edit' == $_GET['action'] ) {
		//Fetch our taxonomies.
		$taxonomies = get_option('cpt_custom_tax_types');
	}

	//Instantiate our UI class.
	$ui = new cptui_admin_ui();

	$ui->get_p( __( 'If you are unfamiliar with the options below only fill out the <strong>Taxonomy Name</strong> and <strong>Post Type Name</strong> fields.  The other settings are set to the most common defaults for custom taxonomies. Hover over the question mark for more details.', 'cpt-plugin' ) );
	$ui->get_p( __('Deleting custom taxonomies does <strong>NOT</strong> delete any content added to those taxonomies.  You can easily recreate your taxonomies and the content will still exist.', 'cpt-plugin') ); ?>
	<form method="post">
		<?php wp_nonce_field('cpt_add_custom_taxonomy');

		if ( !empty( $taxonomies ) && is_array( $taxonomies ) ) {
			//USE UI CLASS HERE.
			foreach( $taxonomies as $tax ) {
				print_r( $tax );
				echo '<hr/>';
			}
		}
		?>

			<table>
				<?php

							echo $ui->get_tr_start() . $ui->get_th_start() . __( 'Taxonomy Name', 'cpt-plugin' );

							echo $ui->get_p( __('Note: Changing the name, after adding terms to the taxonomy, will not update the terms in the database.', 'cpt-plugin' ) );
							echo $ui->get_th_end() . $ui->get_td_start();

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_custom_tax',
								'name'          => 'name',
								'textvalue'     => ( isset( $cpt_tax_name ) ) ? esc_attr( $cpt_tax_name ) : '',
								'aftertext'     => __( '(e.g. actors)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'The taxonomy name. Used to retrieve custom taxonomy content. Should be short and sweet', 'cpt-plugin'),
								'required'      => true,
								'wrap'          => false,
								'maxlength'     => '32',
								'onblur'        => 'this.value=this.value.toLowerCase()'
								) );

							echo $ui->get_p( '<strong>' . __( 'Max 32 characters, should only contain alphanumeric lowercase characters and underscores in place of spaces.', 'cpt-plugin' ) . '</strong>' );
							echo $ui->get_td_end() . $ui->get_tr_end();

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_custom_tax',
								'name'          => 'label',
								'textvalue'     => ( isset( $cpt_tax_label ) ) ? esc_attr( $cpt_tax_label ) : '',
								'aftertext'     => __( '(e.g. Actors)', 'cpt-plugin' ),
								'labeltext'     => __( 'Label', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Taxonomy label. Used in the admin menu for displaying custom taxonomy.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_custom_tax',
								'name'          => 'singular_label',
								'textvalue'     => ( isset( $cpt_singular_label_tax ) ) ? esc_attr( $cpt_singular_label_tax ) : '',
								'aftertext'     => __( '(e.g. Actor)', 'cpt-plugin' ),
								'labeltext'     => __( 'Singular Label', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Taxonomy Singular label.  Used in WordPress when a singular label is needed.', 'cpt-plugin'),
								) );

							echo $ui->get_tr_start() . $ui->get_th_start() . __( 'Attach to Post Type', 'cpt-plugin' ) . $ui->get_required();
							echo $ui->get_th_end() . $ui->get_td_start();

							$args = apply_filters( 'cptui_attach_post_types_to_taxonomy', array( 'public' => true ) );

							//If they don't return an array, fall back to the original default. Don't need to check for empty, because empty array is default for $args param in get_post_types anyway.
							if ( !is_array( $args ) ) {
								$args = array( 'public' => true );
							}
							$output = 'objects'; // or objects
							$post_types = get_post_types( $args, $output );

							foreach ($post_types  as $post_type ) {
								/*
								 * Supports Taxonomies Checkbox
								 */
								echo $ui->get_check_input( array(
									'checkvalue'        => $post_type->name,
									'checked'           => ( !empty( $cpt_post_types ) && is_array( $cpt_post_types ) ) ? in_array( $post_type->name, $cpt_post_types ) : null,
									'name'              => $post_type->name,
									'namearray'         => 'cpt_post_types',
									'textvalue'         => $post_type->name,
									'labeltext'         => $post_type->label,
									'helptext'          => sprintf( esc_attr__( 'Adds %s support', 'cpt-plugin' ), $post_type->name ),
									'wrap'              => false
								) );
							}

							?>

			</table>

			<p class="submit">
			<input type="submit" class="button-primary" name="cpt_submit" value="<?php echo $cpt_submit_name; ?>" />
			</p>

		</form>
	<?php $ui->get_p( __( 'Below are the advanced label options for custom taxonomies.  If you are unfamiliar with these labels the plugin will automatically create labels based off of your custom taxonomy name', 'cpt-plugin' ) ); ?>
	<div id="cptui_accordion">
		<h3>Labels</h3>
		<div>
			<table>
			<?php
				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_tax_labels',
					'name'          => 'search_items',
					'textvalue'     => ( isset( $cpt_tax_labels["search_items"] ) ) ? esc_attr( $cpt_tax_labels["search_items"] ) : '',
					'aftertext'     => __( '(e.g. Search Actors)', 'cpt-plugin' ),
					'labeltext'     => __( 'Search Items', 'cpt-plugin' ),
					'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
					) );

				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_tax_labels',
					'name'          => 'popular_items',
					'textvalue'     => ( isset( $cpt_tax_labels["popular_items"] ) ) ? esc_attr( $cpt_tax_labels["popular_items"] ) : '',
					'aftertext'     => __( '(e.g. Popular Actors)', 'cpt-plugin' ),
					'labeltext'     => __( 'Popular Items', 'cpt-plugin' ),
					'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
					) );

				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_tax_labels',
					'name'          => 'all_items',
					'textvalue'     => ( isset( $cpt_tax_labels["all_items"] ) ) ? esc_attr( $cpt_tax_labels["all_items"] ) : '',
					'aftertext'     => __( '(e.g. All Actors)', 'cpt-plugin' ),
					'labeltext'     => __( 'All Items', 'cpt-plugin' ),
					'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
					) );

				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_tax_labels',
					'name'          => 'parent_item',
					'textvalue'     => ( isset( $cpt_tax_labels["parent_item"] ) ) ? esc_attr( $cpt_tax_labels["parent_item"] ) : '',
					'aftertext'     => __( '(e.g. Parent Actor)', 'cpt-plugin' ),
					'labeltext'     => __( 'Parent Item', 'cpt-plugin' ),
					'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
					) );

				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_tax_labels',
					'name'          => 'parent_item_colon',
					'textvalue'     => ( isset( $cpt_tax_labels["parent_item_colon"] ) ) ? esc_attr( $cpt_tax_labels["parent_item_colon"] ) : '',
					'aftertext'     => __( '(e.g. Parent Actor:)', 'cpt-plugin' ),
					'labeltext'     => __( 'Parent Item Colon', 'cpt-plugin' ),
					'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
					) );

				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_tax_labels',
					'name'          => 'edit_item',
					'textvalue'     => ( isset( $cpt_tax_labels["edit_item"] ) ) ? esc_attr( $cpt_tax_labels["edit_item"] ) : '',
					'aftertext'     => __( '(e.g. Edit Actor)', 'cpt-plugin' ),
					'labeltext'     => __( 'Edit Item', 'cpt-plugin' ),
					'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
					) );

				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_tax_labels',
					'name'          => 'add_new_item',
					'textvalue'     => ( isset( $cpt_tax_labels["add_new_item"] ) ) ? esc_attr( $cpt_tax_labels["add_new_item"] ) : '',
					'aftertext'     => __( '(e.g. Add New Actor)', 'cpt-plugin' ),
					'labeltext'     => __( 'Add New Item', 'cpt-plugin' ),
					'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
					) );

				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_tax_labels',
					'name'          => 'new_item_name',
					'textvalue'     => ( isset( $cpt_tax_labels["new_item_name"] ) ) ? esc_attr( $cpt_tax_labels["new_item_name"] ) : '',
					'aftertext'     => __( '(e.g. New Actor Name)', 'cpt-plugin' ),
					'labeltext'     => __( 'New Item Name', 'cpt-plugin' ),
					'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
					) );

				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_tax_labels',
					'name'          => 'separate_items_with_commas',
					'textvalue'     => ( isset( $cpt_tax_labels["separate_items_with_commas"] ) ) ? esc_attr( $cpt_tax_labels["separate_items_with_commas"] ) : '',
					'aftertext'     => __( '(e.g. Separate actors with commas)', 'cpt-plugin' ),
					'labeltext'     => __( 'Separate Items with Commas', 'cpt-plugin' ),
					'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
					) );

				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_tax_labels',
					'name'          => 'add_or_remove_items',
					'textvalue'     => ( isset( $cpt_tax_labels["add_or_remove_items"] ) ) ? esc_attr( $cpt_tax_labels["add_or_remove_items"] ) : '',
					'aftertext'     => __( '(e.g. Add or remove actors)', 'cpt-plugin' ),
					'labeltext'     => __( 'Add or Remove Items', 'cpt-plugin' ),
					'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
					) );

				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_tax_labels',
					'name'          => 'add_or_remove_items',
					'textvalue'     => ( isset( $cpt_tax_labels["choose_from_most_used"] ) ) ? esc_attr( $cpt_tax_labels["choose_from_most_used"] ) : '',
					'aftertext'     => __( '(e.g. Choose from the most used actors)', 'cpt-plugin' ),
					'labeltext'     => __( 'Choose From Most Used', 'cpt-plugin' ),
					'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
					) );
				?>
			</table>
		</div>
		<h3>Settings</h3>
		<div>
			<table>
				<?php
					$select = array(
						'options' => array(
							array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ), 'default' => 'true' ),
							array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ) )
						)
					);
					$select['selected'] = ( isset( $cpt_tax_hierarchical ) ) ? $cpt_tax_hierarchical : '';
					echo $ui->get_select_bool_input( array(
						'namearray'     => 'cpt_custom_tax',
						'name'          => 'hierarchical',
						'labeltext'     => __( 'Hierarchical', 'cpt-plugin' ),
						'aftertext'     => __( '(default: False)', 'cpt-plugin' ),
						'helptext'      => esc_attr__( 'Whether the taxonomy can have parent-child relationships', 'cpt-plugin' ),
						'selections'    => $select
					) );

					$select = array(
						'options' => array(
							array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
							array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
						)
					);
					$select['selected'] = ( isset( $cpt_tax_showui ) ) ? $cpt_tax_showui : '';
					echo $ui->get_select_bool_input( array(
						'namearray'     => 'cpt_custom_tax',
						'name'          => 'show_ui',
						'labeltext'     => __( 'Show UI', 'cpt-plugin' ),
						'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
						'helptext'      => esc_attr__( 'Whether to generate a default UI for managing this custom taxonomy', 'cpt-plugin' ),
						'selections'    => $select
					) );

					$select = array(
						'options' => array(
							array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
							array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
						)
					);
					$select['selected'] = ( isset( $cpt_tax_query_var ) ) ? $cpt_tax_query_var : '';
					echo $ui->get_select_bool_input( array(
						'namearray'     => 'cpt_custom_tax',
						'name'          => 'show_ui',
						'labeltext'     => __( 'Query Var', 'cpt-plugin' ),
						'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
						'selections'    => $select
					) );

					$select = array(
						'options' => array(
							array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
							array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
						)
					);
					$select['selected'] = ( isset( $cpt_tax_rewrite ) ) ? $cpt_tax_rewrite : '';
					echo $ui->get_select_bool_input( array(
						'namearray'     => 'cpt_custom_tax',
						'name'          => 'rewrite',
						'labeltext'     => __( 'Rewrite', 'cpt-plugin' ),
						'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
						'helptext'      => esc_attr__( 'Triggers the handling of rewrites for this taxonomy', 'cpt-plugin' ),
						'selections'    => $select
					) );

					echo $ui->get_text_input( array(
						'namearray'     => 'cpt_custom_tax',
						'name'          => 'rewrite_slug',
						'textvalue'     => ( isset( $cpt_tax_rewrite_slug ) ) ? esc_attr( $cpt_tax_rewrite_slug ) : '',
						'aftertext'     => __( '(default: taxonomy name)', 'cpt-plugin' ),
						'labeltext'     => __( 'Custom Rewrite Slug', 'cpt-plugin' ),
						'helptext'      => esc_attr__( 'Custom Taxonomy Rewrite Slug', 'cpt-plugin'),
						) );

					if ( version_compare( CPTUI_WP_VERSION, '3.5', '>' ) ) {

						$select = array(
							'options' => array(
								array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ), 'default' => 'true' ),
								array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ) )
							)
						);
						$select['selected'] = ( isset( $cpt_tax_show_admin_column ) ) ? $cpt_tax_show_admin_column : '';
						echo $ui->get_select_bool_input( array(
							'namearray'     => 'cpt_custom_tax',
							'name'          => 'show_admin_column',
							'labeltext'     => __( 'Show Admin Column', 'cpt-plugin' ),
							'aftertext'     => __( '(default: False)', 'cpt-plugin' ),
							'helptext'      => esc_attr__( 'Whether to allow automatic creation of taxonomy columns on associated post-types.', 'cpt-plugin' ),
							'selections'    => $select
						) );
					} ?>
			</table>
		</div>
	</div>
<?php
	cpt_footer();
}
