<?php
/**
 * This file controls all of the content from the Taxonomies page
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add our cptui.js file, with dependencies on jQuery and jQuery UI
 *
 * @since  0.9
 *
 * @return mixed  js scripts
 */
function cptui_taxonomies_enqueue_scripts() {
	wp_enqueue_script( 'cptui', plugins_url( 'js/cptui.js' , dirname(__FILE__) ) . '', array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion' ), '0.9', true );
	wp_localize_script(	'cptui', 'confirmdata', array( 'confirm' => __( 'Are you sure you want to delete this?', 'cpt-plugin' ) ) );
}
add_action( 'admin_enqueue_scripts', 'cptui_taxonomies_enqueue_scripts' );

/**
 * Add our settings page to the menu.
 *
 * @since  0.9
 *
 * @return mixed  new menu
 */
function cptui_taxonomies_admin_menu() {
	add_submenu_page( 'cptui_main_menu', __( 'Add/Edit Taxonomies', 'cpt-plugin' ), __( 'Add/Edit Taxonomies', 'cpt-plugin' ), 'manage_options', 'cptui_manage_taxonomies', 'cptui_manage_taxonomies' );
}
add_action( 'admin_menu', 'cptui_taxonomies_admin_menu' );

/**
 * Create our settings page output
 *
 * @since  0.9
 *
 * @return mixed  webpage
 */
function cptui_manage_taxonomies() {

	$tab = ( !empty( $_GET ) && !empty( $_GET['action'] ) && 'edit' == $_GET['action'] ) ? 'edit' : 'new'; ?>

	<div class="wrap">

	<?php
	//Display any success messages or errors.
	if ( $success = cptui_get_taxonomy_successes() ) {
		echo $success;
	}

	if ( $errors = cptui_get_taxonomy_errors() ) {
		echo $errors;
	}

	//Create our tabs.
	cptui_settings_tab_menu( $page = 'taxonomies' );

	//Fetch and set up our taxonomies if we're in the edit tab.
	if ( 'edit' == $tab ) {
		//Fetch our taxonomies and store in a variable.
		$taxonomies = get_option( 'cptui_taxonomies' );

		//Grab our current selected taxonomy to edit
		$selected_taxonomy = cptui_get_current_taxonomy();

		//fetch out of all of the available taxonomies.
		if ( $selected_taxonomy ) {
			$current = $taxonomies[ $selected_taxonomy ];
		}
	}

	//Only for our add/edit.
	if ( !empty( $_POST ) && isset( $_POST['cpt_submit'] ) ) {
		check_admin_referer( 'cptui_addedit_post_type_nonce_action', 'cptui_addedit_post_type_nonce_field' );
		cptui_update_taxonomy( $_POST );
	}

	if ( !empty( $_POST ) && isset( $_POST['cpt_delete'] ) ) {
		check_admin_referer( 'cptui_addedit_taxonomy_nonce_action', 'cptui_addedit_taxonomy_nonce_field' );
		cptui_delete_taxonomy( $_POST );
	}

	//Instantiate our UI class.
	$ui = new cptui_admin_ui();

	//Will only be set if we're already on the edit screen
	if ( !empty( $taxonomies ) ) { ?>
		<form id="cptui_select_taxonomy" method="post">
			<p>
			<?php
			cptui_taxonomies_dropdown( $taxonomies );
			?>
			<input type="submit" class="button-secondary" name="cptui_select_taxonomy_submit" value="<?php echo esc_attr( apply_filters( 'cptui_taxonomy_submit_select', __( 'Select Taxonomy', 'cpt-plugin' ) ) ); ?>" />
			</p>
		</form>
	<?php
	} ?>

	<form method="post">
		<table class="form-table cptui-table">
			<tr>
				<td><!--LEFT SIDE-->
					<table>
						<?php

						echo $ui->get_tr_start() . $ui->get_th_start() . __( 'Taxonomy Name', 'cpt-plugin' );

						echo $ui->get_th_end() . $ui->get_td_start();

						echo $ui->get_text_input( array(
							'namearray'     => 'cpt_custom_tax',
							'name'          => 'name',
							'textvalue'     => ( isset( $current['name'] ) ) ? esc_attr( $current['name'] ) : '',
							'aftertext'     => __( '(e.g. actors)', 'cpt-plugin' ),
							'helptext'      => esc_attr__( 'The taxonomy name. Used to retrieve custom taxonomy content. Should be short and sweet', 'cpt-plugin'),
							'required'      => true,
							'wrap'          => false,
							'maxlength'     => '32',
							'onblur'        => 'this.value=this.value.toLowerCase()'
						) );

						echo $ui->get_td_end() . $ui->get_tr_end();
						echo $ui->get_text_input( array(
							'namearray'     => 'cpt_custom_tax',
							'name'          => 'label',
							'textvalue'     => ( isset( $current['label'] ) ) ? esc_attr( $current['label'] ) : '',
							'aftertext'     => __( '(e.g. Actors)', 'cpt-plugin' ),
							'labeltext'     => __( 'Label', 'cpt-plugin' ),
							'helptext'      => esc_attr__( 'Taxonomy label. Used in the admin menu for displaying custom taxonomy.', 'cpt-plugin'),
						) );

						echo $ui->get_text_input( array(
							'namearray'     => 'cpt_custom_tax',
							'name'          => 'singular_label',
							'textvalue'     => ( isset( $current['singular_label'] ) ) ? esc_attr( $current['singular_label'] ) : '',
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
								'checked'           => ( !empty( $current['post_types'] ) && is_array( $current['post_types'] ) && in_array( $post_type->name, $current['post_types'] ) ) ? 'true' : 'false',
								'name'              => $post_type->name,
								'namearray'         => 'cpt_post_types',
								'textvalue'         => $post_type->name,
								'labeltext'         => $post_type->label,
								'helptext'          => sprintf( esc_attr__( 'Adds %s support', 'cpt-plugin' ), $post_type->name ),
								'wrap'              => false
							) );
						}
						echo $ui->get_td_end() . $ui->get_tr_end(); ?>
					</table>
				<p class="submit">
					<?php wp_nonce_field( 'cptui_addedit_taxonomy_nonce_action', 'cptui_addedit_taxonomy_nonce_field' );
					if ( !empty( $_GET ) && !empty( $_GET['action'] ) && 'edit' == $_GET['action'] ) { ?>
						<input type="submit" class="button-primary" name="cpt_submit" value="<?php echo esc_attr( apply_filters( 'cptui_taxonomy_submit_edit', __( 'Edit Taxonomy', 'cpt-plugin' ) ) ); ?>" />
						<input type="submit" class="button-secondary" name="cpt_delete" id="cpt_submit_delete" value="<?php echo apply_filters( 'cptui_taxonomy_submit_delete', __( 'Delete Taxonomy', 'cpt-plugin' ) ); ?>" />
					<?php } else { ?>
						<input type="submit" class="button-primary" name="cpt_submit" value="<?php echo esc_attr( apply_filters( 'cptui_taxonomy_submit_add', __( 'Add Taxonomy', 'cpt-plugin' ) ) ); ?>" />
					<?php } ?>
				</p>
			</td>
			<td>
				<p><?php _e( 'Click headings to expand for more settings. Scroll or keyboard tab to navigate all options.', 'cpt-plugin' ); ?></p>

				<div id="cptui_accordion">
					<h3 title="<?php esc_attr_e( 'Click to expand', 'cpt-plugin' ); ?>"><?php _e( 'Labels', 'cpt-plugin' ); ?></h3>
						<div>
							<table>
							<?php

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'menu_name',
								'textvalue'     => ( isset( $current['labels']['menu_name'] ) ) ? esc_attr( $current['labels']['menu_name'] ) : '',
								'labeltext'     => __( 'Menu Name', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'all_items',
								'textvalue'     => ( isset( $current['labels']['all_items'] ) ) ? esc_attr( $current['labels']['all_items'] ) : '',
								'aftertext'     => __( '(e.g. All Actors)', 'cpt-plugin' ),
								'labeltext'     => __( 'All Items', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'edit_item',
								'textvalue'     => ( isset( $current['labels']['edit_item'] ) ) ? esc_attr( $current['labels']['edit_item'] ) : '',
								'aftertext'     => __( '(e.g. Edit Actor)', 'cpt-plugin' ),
								'labeltext'     => __( 'Edit Item', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'view_item',
								'textvalue'     => ( isset( $current['labels']['view_item'] ) ) ? esc_attr( $current['labels']['view_item'] ) : '',
								'aftertext'     => __( '(e.g. View Actor)', 'cpt-plugin' ),
								'labeltext'     => __( 'View Item', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'update_item',
								'textvalue'     => ( isset( $current['labels']['update_item'] ) ) ? esc_attr( $current['labels']['update_item'] ) : '',
								'aftertext'     => __( '(e.g. Update Actor Name)', 'cpt-plugin' ),
								'labeltext'     => __( 'Update Item Name', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'add_new_item',
								'textvalue'     => ( isset( $current['labels']['add_new_item'] ) ) ? esc_attr( $current['labels']['add_new_item'] ) : '',
								'aftertext'     => __( '(e.g. Add New Actor)', 'cpt-plugin' ),
								'labeltext'     => __( 'Add New Item', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'new_item_name',
								'textvalue'     => ( isset( $current['labels']['new_item_name'] ) ) ? esc_attr( $current['labels']['new_item_name'] ) : '',
								'aftertext'     => __( '(e.g. New Actor Name)', 'cpt-plugin' ),
								'labeltext'     => __( 'New Item Name', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'parent_item',
								'textvalue'     => ( isset( $current['labels']['parent_item'] ) ) ? esc_attr( $current['labels']['parent_item'] ) : '',
								'aftertext'     => __( '(e.g. Parent Actor)', 'cpt-plugin' ),
								'labeltext'     => __( 'Parent Item', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'parent_item_colon',
								'textvalue'     => ( isset( $current['labels']['parent_item_colon'] ) ) ? esc_attr( $current['labels']['parent_item_colon'] ) : '',
								'aftertext'     => __( '(e.g. Parent Actor:)', 'cpt-plugin' ),
								'labeltext'     => __( 'Parent Item Colon', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'search_items',
								'textvalue'     => ( isset( $current['labels']['search_items'] ) ) ? esc_attr( $current['labels']['search_items'] ) : '',
								'aftertext'     => __( '(e.g. Search Actors)', 'cpt-plugin' ),
								'labeltext'     => __( 'Search Items', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'popular_items',
								'textvalue'     => ( isset( $current['labels']['popular_items'] ) ) ? esc_attr( $current['labels']['popular_items'] ) : null,
								'aftertext'     => __( '(e.g. Popular Actors)', 'cpt-plugin' ),
								'labeltext'     => __( 'Popular Items', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'separate_items_with_commas',
								'textvalue'     => ( isset( $current['labels']['separate_items_with_commas'] ) ) ? esc_attr( $current['labels']['separate_items_with_commas'] ) : null,
								'aftertext'     => __( '(e.g. Separate actors with commas)', 'cpt-plugin' ),
								'labeltext'     => __( 'Separate Items with Commas', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'add_or_remove_items',
								'textvalue'     => ( isset( $current['labels']['add_or_remove_items'] ) ) ? esc_attr( $current['labels']['add_or_remove_items'] ) : null,
								'aftertext'     => __( '(e.g. Add or remove actors)', 'cpt-plugin' ),
								'labeltext'     => __( 'Add or Remove Items', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'choose_from_most_used',
								'textvalue'     => ( isset( $current['labels']['choose_from_most_used'] ) ) ? esc_attr( $current['labels']['choose_from_most_used'] ) : null,
								'aftertext'     => __( '(e.g. Choose from the most used actors)', 'cpt-plugin' ),
								'labeltext'     => __( 'Choose From Most Used', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'not_found',
								'textvalue'     => ( isset( $current['labels']['not_found'] ) ) ? esc_attr( $current['labels']['not_found'] ) : null,
								'aftertext'     => __( '(e.g. No actors found)', 'cpt-plugin' ),
								'labeltext'     => __( 'Not found', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'cpt-plugin'),
								) );
							?>
						</table>
					</div>
					<h3 title="<?php esc_attr_e( 'Click to expand', 'cpt-plugin' ); ?>"><?php _e( 'Settings', 'cpt-plugin' ); ?></h3>
					<div>
						<table>
							<?php
								$select = array(
									'options' => array(
										array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ), 'default' => 'true' ),
										array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ) )
									)
								);
								$select['selected'] = $current['hierarchical'];
								echo $ui->get_select_input( array(
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
								$select['selected'] = $current['show_ui'];
								echo $ui->get_select_input( array(
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
								$select['selected'] = $current['query_var'];
								echo $ui->get_select_input( array(
									'namearray'     => 'cpt_custom_tax',
									'name'          => 'query_var',
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
								$select['selected'] = $current['rewrite'];
								echo $ui->get_select_input( array(
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
									'textvalue'     => ( isset( $current['rewrite_slug'] ) ) ? esc_attr( $current['rewrite_slug'] ) : '',
									'aftertext'     => __( '(default: taxonomy name)', 'cpt-plugin' ),
									'labeltext'     => __( 'Custom Rewrite Slug', 'cpt-plugin' ),
									'helptext'      => esc_attr__( 'Custom Taxonomy Rewrite Slug', 'cpt-plugin'),
									) );

								$select = array(
									'options' => array(
										array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ), 'default' => 'true' ),
										array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ) )
									)
								);
								$select['selected'] = $current['show_admin_column'];
								echo $ui->get_select_input( array(
									'namearray'     => 'cpt_custom_tax',
									'name'          => 'show_admin_column',
									'labeltext'     => __( 'Show Admin Column', 'cpt-plugin' ),
									'aftertext'     => __( '(default: False)', 'cpt-plugin' ),
									'helptext'      => esc_attr__( 'Whether to allow automatic creation of taxonomy columns on associated post-types.', 'cpt-plugin' ),
									'selections'    => $select
								) );
								?>
						</table>
					</div>

					<?php if ( 'new' == $tab ) { ?>
					<h3 title="<?php esc_attr_e( 'Click to expand', 'cpt-plugin' ); ?>"><?php _e( 'Starter Notes', 'cpt-plugin' ); ?></h3>
						<div><ol>
						<?php
							echo '<li>' . sprintf( __( 'Taxonomy names should have %s max 32 characters %s, and only contain alphanumeric, lowercase, characters and underscores in place of spaces.', 'cpt-plugin' ), '<strong class="wp-ui-highlight">', '</strong>' );
							echo '<li>' . sprintf( __( 'If you are unfamiliar with the advanced taxonomy settings, just fill in the %s Taxonomy Name %s and choose an %s Attach to Post Type %s option. Remaining settings will use default values. Labels, if left blank, will be automatically created based on the taxonomy name. Hover over the question marks for more details.', 'cpt-plugin' ), '<strong class="wp-ui-highlight">', '</strong>', '<strong class="wp-ui-highlight">', '</strong>' ) ;
							echo '<li>' . sprintf( __( 'Deleting custom taxonomies do %s NOT %s delete terms added to those taxonomies. You can recreate your taxonomies and the terms will return. Changing the name, after adding terms to the taxonomy, will not update the terms in the database.', 'cpt-plugin' ), '<strong class="wp-ui-highlight">', '</strong>' ); ?>
						</ol></div>
						<?php } ?>
				</td>
			</tr>
		</table><!-- End outter table -->
	</form>
	</div><!-- End .wrap -->
<?php
	cptui_footer();
}

/**
 * Construct a dropdown of our taxonomies so users can select which to edit.
 *
 * @since  0.9
 *
 * @param  array   $taxonomies array of taxonomies that are registered
 *
 * @return mixed              html select dropdown.
 */
function cptui_taxonomies_dropdown( $taxonomies = array() ) {

	//instantiate our class
	$ui = new cptui_admin_ui();

	if ( !empty( $taxonomies ) ) {
		$select = array();
		$select['options'] = array();

		//Default empty.
		$select['options'][] = array( 'attr' => '', 'text' => '--' );

		foreach( $taxonomies as $tax ) {
			$select['options'][] = array( 'attr' => $tax['name'], 'text' => $tax['label'] );
		}

		//Grab our current selected taxonomy
		$current = cptui_get_current_taxonomy();

		$select['selected'] = $current;
		echo $ui->get_select_input( array(
			'namearray'     => 'cptui_selected_taxonomy',
			'name'          => 'taxonomy',
			'selections'    => $select
		) );
	}
}

/**
 * Get the selected taxonomy from the $_POST global.
 *
 * @since  0.9
 *
 * @return mixed  false on no result, sanitized taxonomy if set.
 */
function cptui_get_current_taxonomy() {
	if ( !empty( $_POST ) && isset( $_POST['cptui_selected_taxonomy']['taxonomy'] ) ) {
		return sanitize_text_field( $_POST['cptui_selected_taxonomy']['taxonomy'] );
	}

	return false;
}

//delete custom post type or custom taxonomy
function cptui_delete_taxonomy( $data ) {
	if ( empty( $data['cpt_custom_tax']['name'] ) ) {
		return;
	}

	$taxonomies = get_option( 'cptui_taxonomies' );

	if ( array_key_exists( strtolower( $data['cpt_custom_tax']['name'] ), $taxonomies ) ) {

		unset( $taxonomies[ $data['cpt_custom_tax']['name'] ] );

		update_option( 'cptui_taxonomies', $taxonomies );
	}
}

function cptui_update_taxonomy( $data ) {
	//clean up $_POST data here

	if ( false !== strpos( $cpt_form_fields["name"], '\'' ) ||
			 false !== strpos( $cpt_form_fields["name"], '\"' ) ||
			 false !== strpos( $cpt_form_fields["rewrite_slug"], '\'' ) ||
			 false !== strpos( $cpt_form_fields["rewrite_slug"], '\"' ) ) {

			//wp_redirect();
			//exit();
		}
}
