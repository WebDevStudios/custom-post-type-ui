<?php
/**
 * This file controls all of the content from the Taxonomies page.
 */

# Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add our cptui.js file, with dependencies on jQuery and jQuery UI.
 *
 * @since 1.0.0
 */
function cptui_taxonomies_enqueue_scripts() {

	$currentScreen = get_current_screen();

	if ( ! is_object( $currentScreen ) || $currentScreen->base == "post" ) {
		return;
	}

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	wp_enqueue_script( 'cptui', plugins_url( 'js/cptui.js' , dirname(__FILE__) ) . '', array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion' ), CPTUI_VERSION, true );
	wp_localize_script(	'cptui', 'cptui_tax_data',
		array(
			'confirm' => __( 'Are you sure you want to delete this?', 'custom-post-type-ui' ),
			#'tax_change_name' => '<div class="typetax-rename">' . __( 'Changing this will rename the taxonomy.', 'custom-post-type-ui' ) . '</div>'
		)
	);
}
add_action( 'admin_enqueue_scripts', 'cptui_taxonomies_enqueue_scripts' );

/**
 * Create our settings page output.
 *
 * @since 1.0.0
 *
 * @return string HTML output for the page.
 */
function cptui_manage_taxonomies() {

	$taxonomy_deleted = false;

	if ( !empty( $_POST ) ) {
		if ( isset( $_POST['cpt_submit'] ) ) {
			check_admin_referer( 'cptui_addedit_taxonomy_nonce_action', 'cptui_addedit_taxonomy_nonce_field' );
			$notice = cptui_update_taxonomy( $_POST );
		} elseif ( isset( $_POST['cpt_delete'] ) ) {
			check_admin_referer( 'cptui_addedit_taxonomy_nonce_action', 'cptui_addedit_taxonomy_nonce_field' );
			$notice = cptui_delete_taxonomy( $_POST );
			$taxonomy_deleted = true;
		}
	}

	$tab = ( !empty( $_GET ) && !empty( $_GET['action'] ) && 'edit' == $_GET['action'] ) ? 'edit' : 'new'; ?>

	<div class="wrap">

	<?php

	if ( isset( $notice ) ) {
		echo $notice;
	}

	# Create our tabs.
	cptui_settings_tab_menu( $page = 'taxonomies' );

	if ( 'edit' == $tab ) {

		$taxonomies = get_option( 'cptui_taxonomies' );

		$selected_taxonomy = cptui_get_current_taxonomy( $taxonomy_deleted );

		if ( $selected_taxonomy ) {
			if ( array_key_exists( $selected_taxonomy, $taxonomies ) ) {
				$current = $taxonomies[ $selected_taxonomy ];
			}
		}
	}

	$ui = new cptui_admin_ui();

	# Will only be set if we're already on the edit screen
	if ( !empty( $taxonomies ) ) { ?>
		<form id="cptui_select_taxonomy" method="post">
			<p><?php _e( 'DO NOT EDIT the taxonomy slug unless necessary. Changing that value registers a new taxonomy entry for your install.', 'custom-post-type-ui' ); ?></p>
			<label for="taxonomy"><?php _e( 'Select: ', 'custom-post-type-ui' ); ?></label>
			<?php
			cptui_taxonomies_dropdown( $taxonomies );

			/**
			 * Filters the text value to use on the select taxonomy button.
			 *
			 * @since 1.0.0
			 *
			 * @param string $value Text to use for the button.
			 */
			?>
			<input type="submit" class="button-secondary" name="cptui_select_taxonomy_submit" value="<?php echo esc_attr( apply_filters( 'cptui_taxonomy_submit_select', __( 'Select', 'custom-post-type-ui' ) ) ); ?>" />
		</form>
	<?php

        /**
         * Fires below the taxonomy select input.
         *
         * @since 1.1.0
         *
         * @param string $value Current taxonomy selected.
         */
        do_action( 'cptui_below_taxonomy_select', $current['name'] );
	} ?>

	<form method="post">
		<table class="form-table cptui-table">
			<tr>
				<td class="outter">
					<table>
						<?php

						echo $ui->get_text_input( array(
							'namearray'     => 'cpt_custom_tax',
							'name'          => 'name',
							'textvalue'     => ( isset( $current['name'] ) ) ? esc_attr( $current['name'] ) : '',
							'maxlength'     => '32',
							'onblur'        => 'this.value=this.value.toLowerCase()',
							'labeltext'     => __( 'Taxonomy Slug', 'custom-post-type-ui' ),
							'aftertext'     => __( '(e.g. actor)', 'custom-post-type-ui' ),
							'helptext'      => esc_attr__( 'The taxonomy name. Used to retrieve custom taxonomy content. Should be short and unique', 'custom-post-type-ui'),
							'required'      => true,
						) );

						echo $ui->get_text_input( array(
							'namearray'     => 'cpt_custom_tax',
							'name'          => 'label',
							'textvalue'     => ( isset( $current['label'] ) ) ? esc_attr( $current['label'] ) : '',
							'aftertext'     => __( '(e.g. Actors)', 'custom-post-type-ui' ),
							'labeltext'     => __( 'Plural Label', 'custom-post-type-ui' ),
							'helptext'      => esc_attr__( 'Taxonomy label. Used in the admin menu for displaying custom taxonomy.', 'custom-post-type-ui'),
						) );

						echo $ui->get_text_input( array(
							'namearray'     => 'cpt_custom_tax',
							'name'          => 'singular_label',
							'textvalue'     => ( isset( $current['singular_label'] ) ) ? esc_attr( $current['singular_label'] ) : '',
							'aftertext'     => __( '(e.g. Actor)', 'custom-post-type-ui' ),
							'labeltext'     => __( 'Singular Label', 'custom-post-type-ui' ),
							'helptext'      => esc_attr__( 'Taxonomy Singular label.  Used in WordPress when a singular label is needed.', 'custom-post-type-ui'),
						) );

						/*
						 * Post Description
						 */
						if ( isset( $current['description'] ) ) {
							$current['description'] = stripslashes_deep( $current['description'] );
						}

						echo $ui->get_textarea_input( array(
							'namearray' => 'cpt_custom_tax',
							'name'      => 'description',
							'rows'      => '4',
							'cols'      => '40',
							'textvalue' => ( isset( $current['description'] ) ) ? esc_textarea( $current['description'] ) : '',
							'labeltext' => __( 'Description', 'custom-post-type-ui' ),
							'helptext'  => esc_attr__( 'Taxonomy Description. Describe what your taxonomy is used for.', 'custom-post-type-ui' )
						) );

						echo $ui->get_tr_start() . $ui->get_th_start() . __( 'Attach to Post Type', 'custom-post-type-ui' ) . $ui->get_required();
						echo $ui->get_th_end() . $ui->get_td_start() . $ui->get_fieldset_start();

						/**
						 * Filters the arguments for post types to list for taxonomy association.
						 *
						 * @since 1.0.0
						 *
						 * @param array $value Array of default arguments.
						 */
						$args = apply_filters( 'cptui_attach_post_types_to_taxonomy', array( 'public' => true ) );

						# If they don't return an array, fall back to the original default. Don't need to check for empty, because empty array is default for $args param in get_post_types anyway.
						if ( !is_array( $args ) ) {
							$args = array( 'public' => true );
						}
						$output = 'objects'; # or objects
						$post_types = get_post_types( $args, $output );

						foreach ( $post_types  as $post_type ) {
							/*
							 * Supports Taxonomies Checkbox
							 */
							echo $ui->get_check_input( array(
								'checkvalue'        => $post_type->name,
								'checked'           => ( !empty( $current['object_types'] ) && is_array( $current['object_types'] ) && in_array( $post_type->name, $current['object_types'] ) ) ? 'true' : 'false',
								'name'              => $post_type->name,
								'namearray'         => 'cpt_post_types',
								'textvalue'         => $post_type->name,
								'labeltext'         => $post_type->label,
								'helptext'          => sprintf( esc_attr__( 'Adds %s support', 'custom-post-type-ui' ), $post_type->label ),
								'wrap'              => false
							) );
						}

						echo $ui->get_fieldset_end() . $ui->get_td_end() . $ui->get_tr_end(); ?>
					</table>
				<p class="submit">
					<?php wp_nonce_field( 'cptui_addedit_taxonomy_nonce_action', 'cptui_addedit_taxonomy_nonce_field' );
					if ( !empty( $_GET ) && !empty( $_GET['action'] ) && 'edit' == $_GET['action'] ) { ?>
						<?php

						/**
						 * Filters the text value to use on the button when editing.
						 *
						 * @since 1.0.0
						 *
						 * @param string $value Text to use for the button.
						 */
						?>
						<input type="submit" class="button-primary" name="cpt_submit" value="<?php echo esc_attr( apply_filters( 'cptui_taxonomy_submit_edit', __( 'Save Taxonomy', 'custom-post-type-ui' ) ) ); ?>" />
						<?php

						/**
						 * Filters the text value to use on the button when deleting.
						 *
						 * @since 1.0.0
						 *
						 * @param string $value Text to use for the button.
						 */
						?>
						<input type="submit" class="button-secondary" name="cpt_delete" id="cpt_submit_delete" value="<?php echo apply_filters( 'cptui_taxonomy_submit_delete', __( 'Delete Taxonomy', 'custom-post-type-ui' ) ); ?>" />
					<?php } else { ?>
						<?php

						/**
						 * Filters the text value to use on the button when adding.
						 *
						 * @since 1.0.0
						 *
						 * @param string $value Text to use for the button.
						 */
						?>
						<input type="submit" class="button-primary" name="cpt_submit" value="<?php echo esc_attr( apply_filters( 'cptui_taxonomy_submit_add', __( 'Add Taxonomy', 'custom-post-type-ui' ) ) ); ?>" />
					<?php } ?>
					<input type="hidden" name="cpt_tax_status" id="cpt_tax_status" value="<?php echo $tab; ?>" />
				</p>

				<?php if ( 'new' == $tab ) { ?>
					<h2><?php _e( 'Starter Notes', 'custom-post-type-ui' ); ?></h2>
						<div><ol>
						<?php
							echo '<li>' . sprintf( __( 'Taxonomy names should have %smax 32 characters%s, and only contain alphanumeric, lowercase, characters, underscores in place of spaces, and letters that do not have accents.', 'custom-post-type-ui' ), '<strong class="wp-ui-highlight">', '</strong>' );
							echo '<li>' . sprintf( __( 'If you are unfamiliar with the advanced taxonomy settings, just fill in the %sTaxonomy Name%s and choose an %sAttach to Post Type%s option. Remaining settings will use default values. Labels, if left blank, will be automatically created based on the taxonomy name. Hover over the question marks for more details.', 'custom-post-type-ui' ), '<strong class="wp-ui-highlight">', '</strong>', '<strong class="wp-ui-highlight">', '</strong>' ) ;
							echo '<li>' . sprintf( __( 'Deleting custom taxonomies do %sNOT%s delete terms added to those taxonomies. You can recreate your taxonomies and the terms will return. Changing the name, after adding terms to the taxonomy, will not update the terms in the database.', 'custom-post-type-ui' ), '<strong class="wp-ui-highlight">', '</strong>' ); ?>
						</ol></div>
						<?php } ?>
			</td>
			<td class="outter">
				<div>
					<h2><?php _e( 'Labels', 'custom-post-type-ui' ); ?></h2>
						<div>
							<table>
							<?php

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'menu_name',
								'textvalue'     => ( isset( $current['labels']['menu_name'] ) ) ? esc_attr( $current['labels']['menu_name'] ) : '',
								'aftertext'     => __( '(e.g. Actors)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'Menu Name', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'all_items',
								'textvalue'     => ( isset( $current['labels']['all_items'] ) ) ? esc_attr( $current['labels']['all_items'] ) : '',
								'aftertext'     => __( '(e.g. All Actors)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'All Items', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'edit_item',
								'textvalue'     => ( isset( $current['labels']['edit_item'] ) ) ? esc_attr( $current['labels']['edit_item'] ) : '',
								'aftertext'     => __( '(e.g. Edit Actor)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'Edit Item', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'view_item',
								'textvalue'     => ( isset( $current['labels']['view_item'] ) ) ? esc_attr( $current['labels']['view_item'] ) : '',
								'aftertext'     => __( '(e.g. View Actor)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'View Item', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'update_item',
								'textvalue'     => ( isset( $current['labels']['update_item'] ) ) ? esc_attr( $current['labels']['update_item'] ) : '',
								'aftertext'     => __( '(e.g. Update Actor Name)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'Update Item Name', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'add_new_item',
								'textvalue'     => ( isset( $current['labels']['add_new_item'] ) ) ? esc_attr( $current['labels']['add_new_item'] ) : '',
								'aftertext'     => __( '(e.g. Add New Actor)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'Add New Item', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'new_item_name',
								'textvalue'     => ( isset( $current['labels']['new_item_name'] ) ) ? esc_attr( $current['labels']['new_item_name'] ) : '',
								'aftertext'     => __( '(e.g. New Actor Name)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'New Item Name', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'parent_item',
								'textvalue'     => ( isset( $current['labels']['parent_item'] ) ) ? esc_attr( $current['labels']['parent_item'] ) : '',
								'aftertext'     => __( '(e.g. Parent Actor)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'Parent Item', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'parent_item_colon',
								'textvalue'     => ( isset( $current['labels']['parent_item_colon'] ) ) ? esc_attr( $current['labels']['parent_item_colon'] ) : '',
								'aftertext'     => __( '(e.g. Parent Actor:)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'Parent Item Colon', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'search_items',
								'textvalue'     => ( isset( $current['labels']['search_items'] ) ) ? esc_attr( $current['labels']['search_items'] ) : '',
								'aftertext'     => __( '(e.g. Search Actors)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'Search Items', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'popular_items',
								'textvalue'     => ( isset( $current['labels']['popular_items'] ) ) ? esc_attr( $current['labels']['popular_items'] ) : null,
								'aftertext'     => __( '(e.g. Popular Actors)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'Popular Items', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'separate_items_with_commas',
								'textvalue'     => ( isset( $current['labels']['separate_items_with_commas'] ) ) ? esc_attr( $current['labels']['separate_items_with_commas'] ) : null,
								'aftertext'     => __( '(e.g. Separate Actors with commas)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'Separate Items with Commas', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'add_or_remove_items',
								'textvalue'     => ( isset( $current['labels']['add_or_remove_items'] ) ) ? esc_attr( $current['labels']['add_or_remove_items'] ) : null,
								'aftertext'     => __( '(e.g. Add or remove Actors)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'Add or Remove Items', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'choose_from_most_used',
								'textvalue'     => ( isset( $current['labels']['choose_from_most_used'] ) ) ? esc_attr( $current['labels']['choose_from_most_used'] ) : null,
								'aftertext'     => __( '(e.g. Choose from the most used Actors)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'Choose From Most Used', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );

							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_tax_labels',
								'name'          => 'not_found',
								'textvalue'     => ( isset( $current['labels']['not_found'] ) ) ? esc_attr( $current['labels']['not_found'] ) : null,
								'aftertext'     => __( '(e.g. No Actors found)', 'custom-post-type-ui' ),
								'labeltext'     => __( 'Not found', 'custom-post-type-ui' ),
								'helptext'      => esc_attr__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui'),
								) );
							?>
						</table>
					</div>
					<h2><?php _e( 'Settings', 'custom-post-type-ui' ); ?></h2>
					<div>
						<table>
							<?php
								$select = array(
									'options' => array(
										array( 'attr' => '0', 'text' => __( 'False', 'custom-post-type-ui' ), 'default' => 'true' ),
										array( 'attr' => '1', 'text' => __( 'True', 'custom-post-type-ui' ) )
									)
								);
								$selected = ( isset( $current ) ) ? disp_boolean( $current['hierarchical'] ) : '';
								$select['selected'] = ( !empty( $selected ) ) ? $current['hierarchical'] : '';
								echo $ui->get_select_input( array(
									'namearray'     => 'cpt_custom_tax',
									'name'          => 'hierarchical',
									'labeltext'     => __( 'Hierarchical', 'custom-post-type-ui' ),
									'aftertext'     => __( '(default: False)', 'custom-post-type-ui' ),
									'helptext'      => esc_attr__( 'Whether the taxonomy can have parent-child relationships', 'custom-post-type-ui' ),
									'selections'    => $select
								) );

								$select = array(
									'options' => array(
										array( 'attr' => '0', 'text' => __( 'False', 'custom-post-type-ui' ) ),
										array( 'attr' => '1', 'text' => __( 'True', 'custom-post-type-ui' ), 'default' => 'true' )
									)
								);
								$selected = ( isset( $current ) ) ? disp_boolean( $current['show_ui'] ) : '';
								$select['selected'] = ( !empty( $selected ) ) ? $current['show_ui'] : '';
								echo $ui->get_select_input( array(
									'namearray'     => 'cpt_custom_tax',
									'name'          => 'show_ui',
									'labeltext'     => __( 'Show UI', 'custom-post-type-ui' ),
									'aftertext'     => __( '(default: True)', 'custom-post-type-ui' ),
									'helptext'      => esc_attr__( 'Whether to generate a default UI for managing this custom taxonomy.', 'custom-post-type-ui' ),
									'selections'    => $select
								) );

								$select = array(
									'options' => array(
										array( 'attr' => '0', 'text' => __( 'False', 'custom-post-type-ui' ) ),
										array( 'attr' => '1', 'text' => __( 'True', 'custom-post-type-ui' ), 'default' => 'true' )
									)
								);
								$selected = ( isset( $current ) ) ? disp_boolean( $current['query_var'] ) : '';
								$select['selected'] = ( !empty( $selected ) ) ? $current['query_var'] : '';
								echo $ui->get_select_input( array(
									'namearray'     => 'cpt_custom_tax',
									'name'          => 'query_var',
									'labeltext'     => __( 'Query Var', 'custom-post-type-ui' ),
									'aftertext'     => __( '(default: True)', 'custom-post-type-ui' ),
									'helptext'      => esc_attr__( 'Sets the query_var key for this taxonomy.', 'custom-post-type-ui' ),
									'selections'    => $select
								) );

								echo $ui->get_text_input( array(
									'namearray'     => 'cpt_custom_tax',
									'name'          => 'query_var_slug',
									'textvalue'     => ( isset( $current['query_var_slug'] ) ) ? esc_attr( $current['query_var_slug'] ) : '',
									'aftertext'     => __( '(default: taxonomy slug). Query var needs to be true to use.', 'custom-post-type-ui' ),
									'labeltext'     => __( 'Custom Query Var String', 'custom-post-type-ui' ),
									'helptext'      => esc_attr__( 'Sets a custom query_var slug for this taxonomy.', 'custom-post-type-ui'),
									) );

								$select = array(
									'options' => array(
										array( 'attr' => '0', 'text' => __( 'False', 'custom-post-type-ui' ) ),
										array( 'attr' => '1', 'text' => __( 'True', 'custom-post-type-ui' ), 'default' => 'true' )
									)
								);
								$selected = ( isset( $current ) ) ? disp_boolean( $current['rewrite'] ) : '';
								$select['selected'] = ( !empty( $selected ) ) ? $current['rewrite'] : '';
								echo $ui->get_select_input( array(
									'namearray'     => 'cpt_custom_tax',
									'name'          => 'rewrite',
									'labeltext'     => __( 'Rewrite', 'custom-post-type-ui' ),
									'aftertext'     => __( '(default: True)', 'custom-post-type-ui' ),
									'helptext'      => esc_attr__( 'Whether or not WordPress should use rewrites for this taxonomy.', 'custom-post-type-ui' ),
									'selections'    => $select
								) );

								echo $ui->get_text_input( array(
									'namearray'     => 'cpt_custom_tax',
									'name'          => 'rewrite_slug',
									'textvalue'     => ( isset( $current['rewrite_slug'] ) ) ? esc_attr( $current['rewrite_slug'] ) : '',
									'aftertext'     => __( '(default: taxonomy name)', 'custom-post-type-ui' ),
									'labeltext'     => __( 'Custom Rewrite Slug', 'custom-post-type-ui' ),
									'helptext'      => esc_attr__( 'Custom taxonomy rewrite slug.', 'custom-post-type-ui'),
									) );

								$select = array(
									'options' => array(
										array( 'attr' => '0', 'text' => __( 'False', 'custom-post-type-ui' ) ),
										array( 'attr' => '1', 'text' => __( 'True', 'custom-post-type-ui' ), 'default' => 'true' )
									)
								);
								$selected = ( isset( $current ) ) ? disp_boolean( $current['rewrite_withfront'] ) : '';
								$select['selected'] = ( !empty( $selected ) ) ? $current['rewrite_withfront'] : '';
								echo $ui->get_select_input( array(
									'namearray'     => 'cpt_custom_tax',
									'name'          => 'rewrite_withfront',
									'labeltext'     => __( 'Rewrite With Front', 'custom-post-type-ui' ),
									'aftertext'     => __( '(default: true)', 'custom-post-type-ui' ),
									'helptext'      => esc_attr__( 'Should the permastruct be prepended with the front base.', 'custom-post-type-ui' ),
									'selections'    => $select
								) );

								$select = array(
									'options' => array(
										array( 'attr' => '0', 'text' => __( 'False', 'custom-post-type-ui' ), 'default' => 'false' ),
										array( 'attr' => '1', 'text' => __( 'True', 'custom-post-type-ui' ) )
									)
								);
								$selected = ( isset( $current ) ) ? disp_boolean( $current['rewrite_hierarchical'] ) : '';
								$select['selected'] = ( !empty( $selected ) ) ? $current['rewrite_hierarchical'] : '';
								echo $ui->get_select_input( array(
									'namearray'     => 'cpt_custom_tax',
									'name'          => 'rewrite_hierarchical',
									'labeltext'     => __( 'Rewrite Hierarchical', 'custom-post-type-ui' ),
									'aftertext'     => __( '(default: false)', 'custom-post-type-ui' ),
									'helptext'      => esc_attr__( 'Should the permastruct allow hierarchical urls.', 'custom-post-type-ui' ),
									'selections'    => $select
								) );

								$select = array(
									'options' => array(
										array( 'attr' => '0', 'text' => __( 'False', 'custom-post-type-ui' ), 'default' => 'true' ),
										array( 'attr' => '1', 'text' => __( 'True', 'custom-post-type-ui' ) )
									)
								);
								$selected = ( isset( $current ) ) ? disp_boolean( $current['show_admin_column'] ) : '';
								$select['selected'] = ( !empty( $selected ) ) ? $current['show_admin_column'] : '';
								echo $ui->get_select_input( array(
									'namearray'     => 'cpt_custom_tax',
									'name'          => 'show_admin_column',
									'labeltext'     => __( 'Show Admin Column', 'custom-post-type-ui' ),
									'aftertext'     => __( '(default: False)', 'custom-post-type-ui' ),
									'helptext'      => esc_attr__( 'Whether to allow automatic creation of taxonomy columns on associated post-types.', 'custom-post-type-ui' ),
									'selections'    => $select
								) );

								/*
								 * show_in_rest Boolean
								 */
								$select = array(
									'options' => array(
										array( 'attr'    => '0', 'text'    => __( 'False', 'custom-post-type-ui' ), 'default' => 'false' ),
										array( 'attr' => '1', 'text' => __( 'True', 'custom-post-type-ui' ) )
									)
								);
								$selected           = ( isset( $current ) ) ? disp_boolean( $current['show_in_rest'] ) : '';
								$select['selected'] = ( ! empty( $selected ) ) ? $current['show_in_rest'] : '';
								echo $ui->get_select_input( array(
									'namearray'  => 'cpt_custom_tax',
									'name'       => 'show_in_rest',
									'labeltext'  => __( 'Show in REST API', 'custom-post-type-ui' ),
									'aftertext'  => __( '(default: False)', 'custom-post-type-ui' ),
									'helptext'   => esc_attr__( 'Whether to show this taxonomy data in the WP REST API.', 'custom-post-type-ui' ),
									'selections' => $select
								) );

								/*
								 * rest_base slug.
								 */
								echo $ui->get_text_input( array(
									'labeltext' => __( 'REST API base slug', 'custom-post-type-ui' ),
									'helptext'  => esc_attr__( 'Slug to use in REST API URLs.', 'custom-post-type-ui' ),
									'namearray' => 'cpt_custom_tax',
									'name'      => 'rest_base',
									'textvalue' => ( isset( $current['rest_base'] ) ) ? esc_attr( $current['rest_base'] ) : '',
								) );
								?>
						</table>
					</div>
				</div>
				</td>
			</tr>
		</table><!-- End outter table -->
	</form>
	</div><!-- End .wrap -->
<?php
}

/**
 * Construct a dropdown of our taxonomies so users can select which to edit.
 *
 * @since 1.0.0
 *
 * @param array $taxonomies Array of taxonomies that are registered.
 *
 * @return string HTML select dropdown.
 */
function cptui_taxonomies_dropdown( $taxonomies = array() ) {

	$ui = new cptui_admin_ui();

	if ( !empty( $taxonomies ) ) {
		$select = array();
		$select['options'] = array();

		foreach( $taxonomies as $tax ) {
			$text = ( ! empty( $tax['label'] ) ) ? $tax['label'] : $tax['name'];
			$select['options'][] = array( 'attr' => $tax['name'], 'text' => $text );
		}

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
 * @since 1.0.0
 *
 * @param bool $taxonomy_deleted Whether or not a taxonomy was recently deleted.
 *
 * @return bool|string False on no result, sanitized taxonomy if set.
 */
function cptui_get_current_taxonomy( $taxonomy_deleted = false ) {
	if ( !empty( $_POST ) ) {
		if ( isset( $_POST['cptui_selected_taxonomy']['taxonomy'] ) ) {
			return sanitize_text_field( $_POST['cptui_selected_taxonomy']['taxonomy'] );
		}

		if ( $taxonomy_deleted ) {
			$taxonomies = get_option( 'cptui_taxonomies' );
			return key( $taxonomies );
		}

		if ( isset( $_POST['cpt_custom_tax']['name'] ) ) {
			return sanitize_text_field( $_POST['cpt_custom_tax']['name'] );
		}
	} else if ( !empty( $_GET ) && isset( $_GET['cptui_taxonomy'] ) ) {
		return sanitize_text_field( $_GET['cptui_taxonomy'] );
	} else {
		$taxonomies = get_option( 'cptui_taxonomies' );
		if ( !empty( $taxonomies ) ) {
			# Will return the first array key
			return key( $taxonomies );
		}
	}

	return false;
}

/**
 * Delete our custom taxonomy from the array of taxonomies.
 *
 * @since 1.0.0
 *
 * @param $data array $_POST values.
 *
 * @return bool|string False on failure, string on success.
 */
function cptui_delete_taxonomy( $data = array() ) {

	/**
	 * Fires before a taxonomy is deleted from our saved options.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Array of taxonomy data we are deleting.
	 */
	do_action( 'cptui_before_delete_taxonomy', $data );

	#Check if they selected one to delete
	if ( empty( $data['cpt_custom_tax']['name'] ) ) {
		return cptui_admin_notices(	'error', '', false, __( 'Please provide a taxonomy to delete', 'custom-post-type-ui' ) );
	}

	$taxonomies = get_option( 'cptui_taxonomies' );

	if ( array_key_exists( strtolower( $data['cpt_custom_tax']['name'] ), $taxonomies ) ) {

		unset( $taxonomies[ $data['cpt_custom_tax']['name'] ] );

		$success = update_option( 'cptui_taxonomies', $taxonomies );
	}

	/**
	 * Fires after a taxonomy is deleted from our saved options.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Array of taxonomy data that was deleted.
	 */
	do_action( 'cptui_after_delete_taxonomy', $data );

	flush_rewrite_rules();

	if ( isset( $success ) ) {
		return cptui_admin_notices( 'delete', $data['cpt_custom_tax']['name'], $success );
	}
	return false;
}

/**
 * Add to or update our CPTUI option with new data.
 *
 * @since 1.0.0
 *
 * @param array $data Array of taxonomy data to update.
 *
 * @return bool|string False on failure, string on success.
 */
function cptui_update_taxonomy( $data = array() ) {

	/**
	 * Fires before a taxonomy is updated to our saved options.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Array of taxonomy data we are updating.
	 */
	do_action( 'cptui_before_update_taxonomy', $data );

	# They need to provide a name
	if ( empty( $data['cpt_custom_tax']['name'] ) ) {
		return cptui_admin_notices(	'error', '', false, __( 'Please provide a taxonomy name', 'custom-post-type-ui' ) );
	}

	foreach( $data as $key => $value ) {
		if ( is_string( $value ) ) {
			$data[ $key ] = sanitize_text_field( $value );
		} else {
			array_map( 'sanitize_text_field', $data[ $key ] );
		}
	}

	if ( false !== strpos( $data['cpt_custom_tax']['name'], '\'' ) ||
		false !== strpos( $data['cpt_custom_tax']['name'], '\"' ) ||
		false !== strpos( $data['cpt_custom_tax']['rewrite_slug'], '\'' ) ||
		false !== strpos( $data['cpt_custom_tax']['rewrite_slug'], '\"' ) ) {

		return cptui_admin_notices(	'error', '', false, __( 'Please do not use quotes in taxonomy names or rewrite slugs', 'custom-post-type-ui' ) );
	}

	$taxonomies = get_option( 'cptui_taxonomies', array() );

	if ( 'new' == $data['cpt_tax_status'] && array_key_exists( strtolower( $data['cpt_custom_tax']['name'] ), $taxonomies ) ) {
		return cptui_admin_notices(	'error', '', false, sprintf( __( 'Please choose a different taxonomy name. %s is already used.', 'custom-post-type-ui' ), $data['cpt_custom_tax']['name'] ) );
	}

	if ( empty( $data['cpt_post_types'] ) || !is_array( $data['cpt_post_types'] ) ) {
		$data['cpt_post_types'] = '';
	}

	foreach( $data['cpt_tax_labels'] as $key => $label ) {
		if ( empty( $label ) ) {
			unset( $data['cpt_tax_labels'][ $key ] );
		}
		$label = str_replace( '"', '', htmlspecialchars_decode( $label ) );
		$label = htmlspecialchars( $label, ENT_QUOTES );
		$label = trim( $label );
		$data['cpt_tax_labels'][ $key ] = stripslashes_deep( $label );
	}

	$label = str_replace( '"', '', htmlspecialchars_decode( $data['cpt_custom_tax']['label'] ) );
	$label = htmlspecialchars( stripslashes( $label ), ENT_QUOTES );

	$name = trim( $data['cpt_custom_tax']['name'] );
	$singular_label = str_replace( '"', '', htmlspecialchars_decode( $data['cpt_custom_tax']['singular_label'] ) );
	$singular_label = htmlspecialchars( stripslashes( $singular_label ) );
	$description = stripslashes_deep( $data['cpt_custom_tax']['description'] );
	$query_var_slug = trim( $data['cpt_custom_tax']['query_var_slug'] );
	$rewrite_slug = trim( $data['cpt_custom_tax']['rewrite_slug'] );
	$rest_base = trim( $data['cpt_custom_tax']['rest_base'] );

	$taxonomies[ $data['cpt_custom_tax']['name'] ] = array(
		'name'                 => $name,
		'label'                => $label,
		'singular_label'       => $singular_label,
		'description'          => $description,
		'hierarchical'         => disp_boolean( $data['cpt_custom_tax']['hierarchical'] ),
		'show_ui'              => disp_boolean( $data['cpt_custom_tax']['show_ui'] ),
		'query_var'            => disp_boolean( $data['cpt_custom_tax']['query_var'] ),
		'query_var_slug'       => $query_var_slug,
		'rewrite'              => disp_boolean( $data['cpt_custom_tax']['rewrite'] ),
		'rewrite_slug'         => $rewrite_slug,
		'rewrite_withfront'    => $data['cpt_custom_tax']['rewrite_withfront'],
		'rewrite_hierarchical' => $data['cpt_custom_tax']['rewrite_hierarchical'],
		'show_admin_column'    => disp_boolean( $data['cpt_custom_tax']['show_admin_column'] ),
		'show_in_rest'         => disp_boolean( $data['cpt_custom_tax']['show_in_rest'] ),
		'rest_base'            => $rest_base,
		'labels'               => $data['cpt_tax_labels']
	);

	$taxonomies[ $data['cpt_custom_tax']['name'] ]['object_types'] = $data['cpt_post_types'];

	$success = update_option( 'cptui_taxonomies', $taxonomies );

	/**
	 * Fires after a taxonomy is updated to our saved options.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Array of taxonomy data that was updated.
	 */
	do_action( 'cptui_after_update_taxonomy', $data );

	flush_rewrite_rules();

	if ( isset( $success ) ) {
		if ( 'new' == $data['cpt_tax_status'] ) {
			return cptui_admin_notices( 'add', $data['cpt_custom_tax']['name'], $success );
		}
	}

	return cptui_admin_notices( 'update', $data['cpt_custom_tax']['name'], true );
}

/**
 * Convert taxonomies.
 * @param string $original_slug
 * @param string $new_slug
 */
function cptui_convert_taxonomy_terms( $original_slug = '', $new_slug = '' ) {}
