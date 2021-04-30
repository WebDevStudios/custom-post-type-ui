<?php

namespace CPTUI;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add our cptui.js file, with dependencies on jQuery and jQuery UI.
 * @since 1.0.0
 * @internal
 */
function cptui_langs_enqueue_scripts() {

	$current_screen = get_current_screen();

	if ( ! is_object( $current_screen ) || 'cpt-ui_page_cptui_translations' !== $current_screen->base ) {
		return;
	}

	if ( wp_doing_ajax() ) {
		return;
	}

	wp_enqueue_script( 'cptui' );
	wp_enqueue_style( 'cptui-css' );

	$core                  = get_post_types( [ '_builtin' => true ] );
	$public                = get_post_types( [ '_builtin' => false, 'public' => true ] );
	$private               = get_post_types( [ '_builtin' => false, 'public' => false ] );
	$registered_post_types = array_merge( $core, $public, $private );

	wp_localize_script( 'cptui', 'cptui_type_data',
		[
			'confirm'             => esc_html__( 'Are you sure you want to delete this? Deleting will NOT remove created content.', 'custom-post-type-ui' ),
			'existing_post_types' => $registered_post_types,
		]
	);
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\cptui_langs_enqueue_scripts' );

function set_cptui_post_type_lang_for_user( $args, $post_type_slug, $post_type_args ) {

	return $args;
}
#add_filter( 'cptui_pre_register_post_type', __NAMESPACE__ . '\set_cptui_post_type_lang_for_user', 10, 3 );

function set_cptui_taxonomy_lang_for_user( $args, $taxonomy_slug, $taxonomy_args ) {

	return $args;
}
#add_filter( 'cptui_pre_register_taxonomy', __NAMESPACE__ . '\set_cptui_taxonomy_lang_for_user', 10, 3 );

function cptui_langs_settings_page() {

	$tab = ( ! empty( $_GET ) && ! empty( $_GET['action'] ) && 'edit' === $_GET['action'] ) ? 'edit' : 'new';
	$type_tax = (
		! empty( $_GET ) &&
		! empty( $_GET['type_tax'] ) ) ?
		sanitize_text_field( $_GET['type_tax'] ) :
		'';
	if ( empty( $type_tax ) ) {
		$type_tax = (
			! empty( $_POST ) &&
			! empty( $_POST['cptui_i18n_type_tax']['type_tax'] ) ) ?
			sanitize_text_field( $_POST['cptui_i18n_type_tax']['type_tax'] ) :
			'';
	}
	$selected_type = (
		! empty( $_GET ) &&
		! empty( $_GET['selected_type'] ) ) ?
		sanitize_text_field( $_GET['selected_type'] ) :
		'';
	if ( empty( $selected_type ) ) {
		$selected_type = (
			! empty( $_POST ) &&
			! empty( $_POST['cptui_selected_post_type']['post_type'] ) ) ?
			sanitize_text_field( $_POST['cptui_selected_post_type']['post_type'] ) :
			'';
	}
	$selected_tax = (
		! empty( $_GET ) &&
		! empty( $_GET['selected_tax'] ) ) ?
		sanitize_text_field( $_GET['selected_tax'] ) :
		'';
	if ( empty( $selected_tax ) ) {
		$selected_tax = (
			! empty( $_POST ) &&
			! empty( $_POST['cptui_selected_taxonomy']['taxonomy'] ) ) ?
			sanitize_text_field( $_POST['cptui_selected_taxonomy']['taxonomy'] ) :
			'';
	}
	$ui  = new \cptui_admin_ui();
	?>
	<div class="wrap cptui-i18n">
		<h1 class="wp-heading-inline"><?php echo get_admin_page_title(); ?></h1>

		<p><?php esc_html_e( 'Select language to translate Custom Post Type UI for', 'custom-post-type-ui' ); ?></p>
		<?php
			//$form_args['action'] = 'edit';
			if ( ! empty( $type_tax ) ) {
				$form_args['type_tax'] = $type_tax;
			}
			if ( ! empty( $selected_type ) ) {
				$form_args['selected_type'] = $selected_type;
			}
			if ( ! empty( $selected_tax ) ) {
				$form_args['selected_type'] = $selected_tax;
			}
		?>
		<form id="cptui_select_i18n" method="post" action="<?php echo add_query_arg(
			$form_args,
			cptui_admin_url( 'admin.php?page=cptui_translations' )
		); ?>">
			<?php
			wp_nonce_field( 'cptui_select_i18n_nonce_action', 'cptui_select_i18n_nonce_field' );

			echo '<div class="cptui-i18n-content-select">';
			esc_html_e( 'Content type: ', 'custom-post-type-ui' );
			$selections['options'][] = [ 'attr' => esc_html( 'none' ), 'text' => 'None' ];
			$selections['options'][] = [ 'attr' => esc_html( 'post_type' ), 'text' => 'Post Type' ];
			$selections['options'][] = [ 'attr' => esc_html( 'taxonomy' ), 'text' => 'Taxonomy' ];
			$selections['selected'] = $type_tax;
			echo $ui->get_select_input( [
				'namearray'  => 'cptui_i18n_type_tax',
				'name'       => 'type_tax',
				'selections' => $selections,
				'wrap'       => false,
			] );
			echo '</div>';

			echo '<div class="cptui-i18n-type-select">';
			esc_html_e( 'Post types: ', 'custom-post-type-ui' );
			$post_types = cptui_get_post_type_data();
			cptui_post_types_dropdown( $post_types );
			echo '</div>';
			?>
			STILL NEED TO PASS IN FOUND/$_GET PARAMS FOR CURRENT SELECTED TYPE OR TAX TO DROPDOWN GENERATORS.<br/>
			<?php

			echo '<div class="cptui-i18n-tax-select">';
			esc_html_e( 'Taxonomies: ', 'custom-post-type-ui' );
			$taxonomies = cptui_get_taxonomy_data();
			cptui_taxonomies_dropdown( $taxonomies );
			echo '</div>';
			?>
STILL NEED TO FIGURE OUT SAVING AS A WHOLE AND DELETING AND FILTERING IN.
			<div class="cptui-i18n-lang-select">
			<label for="cptui-i18n"><?php _e( 'Language' ); ?>
				<span class="dashicons dashicons-translation" aria-hidden="true"></span></label>
			<?php
			cptui_langs_dropdown();
			?></div>
			<input class="button-secondary" name="cptui_select_lang_submit" id="cptui_select_lang_submit" type="submit" value="<?php esc_attr_e( 'Select language', 'custom-post-type-ui' ); ?>" />
		</form>
			<?php
			if ( 'edit' === $tab ) {



			?>

			<form class="langsui" method="post" action="">
				<div class="postbox-container">
					<div id="poststuff">
						<p class="submit">
							<?php
							if ( ! empty( $_GET ) && ! empty( $_GET['action'] ) && 'edit' === $_GET['action'] ) { ?>
								<?php

								/**
								 * Filters the text value to use on the button when editing.
								 *
								 * @param string $value Text to use for the button.
								 *
								 * @since 1.0.0
								 */
								?>
								<input type="submit" class="button-primary cptui-i18n-submit" name="cpt_submit" value="<?php echo esc_attr( apply_filters( 'cptui_i18n_submit_edit', esc_attr__( 'Save Translation', 'custom-post-type-ui' ) ) ); ?>" />
								<?php

								/**
								 * Filters the text value to use on the button when deleting.
								 *
								 * @param string $value Text to use for the button.
								 *
								 * @since 1.0.0
								 */
								?>
								<input type="submit" class="button-secondary cptui-delete-bottom" name="cpt_delete" id="cpt_submit_delete" value="<?php echo esc_attr( apply_filters( 'cptui_i18n_submit_delete', __( 'Delete Translation', 'custom-post-type-ui' ) ) ); ?>" />
							<?php } else { ?>
								<?php

								/**
								 * Filters the text value to use on the button when adding.
								 *
								 * @param string $value Text to use for the button.
								 *
								 * @since 1.0.0
								 */
								?>
								<input type="submit" class="button-primary cptui-i18n-submit" name="cpt_submit" value="<?php echo esc_attr( apply_filters( 'cptui_i18n_submit_add', esc_attr__( 'Add Translation', 'custom-post-type-ui' ) ) ); ?>" />
							<?php } ?>
						</p>
						<div class="cptui-section cptui-labels postbox">
							<div class="postbox-header">
								<h2 class="hndle ui-sortable-handle">
									<span><?php esc_html_e( 'Additional Post Type Labels', 'custom-post-type-ui' ); ?></span>
								</h2>
								<div class="handle-actions hide-if-no-js">
									<button type="button" class="handlediv" aria-expanded="true">
										<span class="screen-reader-text"><?php esc_html_e( 'Toggle panel: Post type language labels', 'custom-post-type-ui' ); ?></span>
										<span class="toggle-indicator" aria-hidden="true"></span>
									</button>
								</div>
							</div>
							<div class="inside">
								<div class="main">
									<table class="form-table cptui-table">
										<?php

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'label',
											'textvalue' => isset( $current['label'] ) ? esc_attr( $current['label'] ) : '',
											'labeltext' => esc_html__( 'Plural Label', 'custom-post-type-ui' ),
											'aftertext' => esc_html__( '(e.g. Movies)', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used for the post type admin menu item.', 'custom-post-type-ui' ),
											'required'  => true,
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'singular_label',
											'textvalue' => isset( $current['singular_label'] ) ? esc_attr( $current['singular_label'] ) : '',
											'labeltext' => esc_html__( 'Singular Label', 'custom-post-type-ui' ),
											'aftertext' => esc_html__( '(e.g. Movie)', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used when a singular label is needed.', 'custom-post-type-ui' ),
											'required'  => true,
										] );

										if ( isset( $current['description'] ) ) {
											$current['description'] = stripslashes_deep( $current['description'] );
										}
										echo $ui->get_textarea_input( [
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'description',
											'rows'      => '4',
											'cols'      => '40',
											'textvalue' => isset( $current['description'] ) ? esc_textarea( $current['description'] ) : '',
											'labeltext' => esc_html__( 'Post Type Description', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Perhaps describe what your custom post type is used for?', 'custom-post-type-ui' ),
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Menu Name', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Custom admin menu name for your custom post type.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'menu_name',
											'textvalue' => isset( $current['labels']['menu_name'] ) ? esc_attr( $current['labels']['menu_name'] ) : '',
											'aftertext' => esc_html__( '(e.g. My Movies)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'My %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'All Items', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used in the post type admin submenu.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'all_items',
											'textvalue' => isset( $current['labels']['all_items'] ) ? esc_attr( $current['labels']['all_items'] ) : '',
											'aftertext' => esc_html__( '(e.g. All Movies)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'All %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Add New', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used in the post type admin submenu.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'add_new',
											'textvalue' => isset( $current['labels']['add_new'] ) ? esc_attr( $current['labels']['add_new'] ) : '',
											'aftertext' => esc_html__( '(e.g. Add New)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => esc_attr__( 'Add new', 'custom-post-type-ui' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Add New Item', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used at the top of the post editor screen for a new post type post.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'add_new_item',
											'textvalue' => isset( $current['labels']['add_new_item'] ) ? esc_attr( $current['labels']['add_new_item'] ) : '',
											'aftertext' => esc_html__( '(e.g. Add New Movie)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Add new %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Edit Item', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used at the top of the post editor screen for an existing post type post.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'edit_item',
											'textvalue' => isset( $current['labels']['edit_item'] ) ? esc_attr( $current['labels']['edit_item'] ) : '',
											'aftertext' => esc_html__( '(e.g. Edit Movie)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Edit %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'New Item', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Post type label. Used in the admin menu for displaying post types.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'new_item',
											'textvalue' => isset( $current['labels']['new_item'] ) ? esc_attr( $current['labels']['new_item'] ) : '',
											'aftertext' => esc_html__( '(e.g. New Movie)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'New %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'View Item', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used in the admin bar when viewing editor screen for a published post in the post type.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'view_item',
											'textvalue' => isset( $current['labels']['view_item'] ) ? esc_attr( $current['labels']['view_item'] ) : '',
											'aftertext' => esc_html__( '(e.g. View Movie)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'View %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'View Items', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used in the admin bar when viewing editor screen for a published post in the post type.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'view_items',
											'textvalue' => isset( $current['labels']['view_items'] ) ? esc_attr( $current['labels']['view_items'] ) : '',
											'aftertext' => esc_html__( '(e.g. View Movies)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'View %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Search Item', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used as the text for the search button on post type list screen.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'search_items',
											'textvalue' => isset( $current['labels']['search_items'] ) ? esc_attr( $current['labels']['search_items'] ) : '',
											'aftertext' => esc_html__( '(e.g. Search Movies)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Search %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Not Found', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used when there are no posts to display on the post type list screen.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'not_found',
											'textvalue' => isset( $current['labels']['not_found'] ) ? esc_attr( $current['labels']['not_found'] ) : '',
											'aftertext' => esc_html__( '(e.g. No Movies found)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'No %s found', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Not Found in Trash', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used when there are no posts to display on the post type list trash screen.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'not_found_in_trash',
											'textvalue' => isset( $current['labels']['not_found_in_trash'] ) ? esc_attr( $current['labels']['not_found_in_trash'] ) : '',
											'aftertext' => esc_html__( '(e.g. No Movies found in Trash)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'No %s found in trash', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										// As of 1.4.0, this will register into `parent_item_colon` paramter upon registration and export.
										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Parent', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used for hierarchical types that need a colon.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'parent',
											'textvalue' => isset( $current['labels']['parent'] ) ? esc_attr( $current['labels']['parent'] ) : '',
											'aftertext' => esc_html__( '(e.g. Parent Movie:)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Parent %s:', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Featured Image', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used as the "Featured Image" phrase for the post type.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'featured_image',
											'textvalue' => isset( $current['labels']['featured_image'] ) ? esc_attr( $current['labels']['featured_image'] ) : '',
											'aftertext' => esc_html__( '(e.g. Featured image for this movie)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Featured image for this %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Set Featured Image', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used as the "Set featured image" phrase for the post type.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'set_featured_image',
											'textvalue' => isset( $current['labels']['set_featured_image'] ) ? esc_attr( $current['labels']['set_featured_image'] ) : '',
											'aftertext' => esc_html__( '(e.g. Set featured image for this movie)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Set featured image for this %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Remove Featured Image', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used as the "Remove featured image" phrase for the post type.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'remove_featured_image',
											'textvalue' => isset( $current['labels']['remove_featured_image'] ) ? esc_attr( $current['labels']['remove_featured_image'] ) : '',
											'aftertext' => esc_html__( '(e.g. Remove featured image for this movie)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Remove featured image for this %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Use Featured Image', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used as the "Use as featured image" phrase for the post type.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'use_featured_image',
											'textvalue' => isset( $current['labels']['use_featured_image'] ) ? esc_attr( $current['labels']['use_featured_image'] ) : '',
											'aftertext' => esc_html__( '(e.g. Use as featured image for this movie)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Use as featured image for this %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Archives', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Post type archive label used in nav menus.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'archives',
											'textvalue' => isset( $current['labels']['archives'] ) ? esc_attr( $current['labels']['archives'] ) : '',
											'aftertext' => esc_html__( '(e.g. Movie archives)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( '%s archives', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Insert into item', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used as the "Insert into post" or "Insert into page" phrase for the post type.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'insert_into_item',
											'textvalue' => isset( $current['labels']['insert_into_item'] ) ? esc_attr( $current['labels']['insert_into_item'] ) : '',
											'aftertext' => esc_html__( '(e.g. Insert into movie)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Insert into %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Uploaded to this Item', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used as the "Uploaded to this post" or "Uploaded to this page" phrase for the post type.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'uploaded_to_this_item',
											'textvalue' => isset( $current['labels']['uploaded_to_this_item'] ) ? esc_attr( $current['labels']['uploaded_to_this_item'] ) : '',
											'aftertext' => esc_html__( '(e.g. Uploaded to this movie)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Upload to this %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Filter Items List', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Screen reader text for the filter links heading on the post type listing screen.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'filter_items_list',
											'textvalue' => isset( $current['labels']['filter_items_list'] ) ? esc_attr( $current['labels']['filter_items_list'] ) : '',
											'aftertext' => esc_html__( '(e.g. Filter movies list)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Filter %s list', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Items List Navigation', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Screen reader text for the pagination heading on the post type listing screen.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'items_list_navigation',
											'textvalue' => isset( $current['labels']['items_list_navigation'] ) ? esc_attr( $current['labels']['items_list_navigation'] ) : '',
											'aftertext' => esc_html__( '(e.g. Movies list navigation)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( '%s list navigation', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Items List', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Screen reader text for the items list heading on the post type listing screen.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'items_list',
											'textvalue' => isset( $current['labels']['items_list'] ) ? esc_attr( $current['labels']['items_list'] ) : '',
											'aftertext' => esc_html__( '(e.g. Movies list)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( '%s list', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Attributes', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used for the title of the post attributes meta box.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'attributes',
											'textvalue' => isset( $current['labels']['attributes'] ) ? esc_attr( $current['labels']['attributes'] ) : '',
											'aftertext' => esc_html__( '(e.g. Movies Attributes)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( '%s attributes', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( '"New" menu in admin bar', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used in New in Admin menu bar. Default "singular name" label.', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'name_admin_bar',
											'textvalue' => isset( $current['labels']['name_admin_bar'] ) ? esc_attr( $current['labels']['name_admin_bar'] ) : '',
											'aftertext' => esc_html__( '(e.g. Movie)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => 'item', // not localizing because it's so isolated.
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Item Published', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used in the editor notice after publishing a post. Default "Post published." / "Page published."', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'item_published',
											'textvalue' => isset( $current['labels']['item_published'] ) ? esc_attr( $current['labels']['item_published'] ) : '',
											'aftertext' => esc_html__( '(e.g. Movie published)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( '%s published', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Item Published Privately', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used in the editor notice after publishing a private post. Default "Post published privately." / "Page published privately."', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'item_published_privately',
											'textvalue' => isset( $current['labels']['item_published_privately'] ) ? esc_attr( $current['labels']['item_published_privately'] ) : '',
											'aftertext' => esc_html__( '(e.g. Movie published privately.)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( '%s published privately.', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Item Reverted To Draft', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used in the editor notice after reverting a post to draft. Default "Post reverted to draft." / "Page reverted to draft."', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'item_reverted_to_draft',
											'textvalue' => isset( $current['labels']['item_reverted_to_draft'] ) ? esc_attr( $current['labels']['item_reverted_to_draft'] ) : '',
											'aftertext' => esc_html__( '(e.g. Movie reverted to draft)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( '%s reverted to draft.', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Item Scheduled', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used in the editor notice after scheduling a post to be published at a later date. Default "Post scheduled." / "Page scheduled."', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'item_scheduled',
											'textvalue' => isset( $current['labels']['item_scheduled'] ) ? esc_attr( $current['labels']['item_scheduled'] ) : '',
											'aftertext' => esc_html__( '(e.g. Movie scheduled)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( '%s scheduled', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'labeltext' => esc_html__( 'Item Updated', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used in the editor notice after updating a post. Default "Post updated." / "Page updated."', 'custom-post-type-ui' ),
											'namearray' => 'cptui-i18n-post-types',
											'name'      => 'item_updated',
											'textvalue' => isset( $current['labels']['item_updated'] ) ? esc_attr( $current['labels']['item_updated'] ) : '',
											'aftertext' => esc_html__( '(e.g. Movie updated)', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( '%s updated.', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										?>
									</table>
								</div>
							</div>
						</div>
						<div class="cptui-section cptui-labels postbox">
							<div class="postbox-header">
								<h2 class="hndle ui-sortable-handle">
									<span><?php esc_html_e( 'Additional Taxonomy Labels', 'custom-post-type-ui' ); ?></span>
								</h2>
								<div class="handle-actions hide-if-no-js">
									<button type="button" class="handlediv" aria-expanded="true">
										<span class="screen-reader-text"><?php esc_html_e( 'Toggle panel: Taxonony language labels', 'custom-post-type-ui' ); ?></span>
										<span class="toggle-indicator" aria-hidden="true"></span>
									</button>
								</div>
							</div>
							<div class="inside">
								<div class="main">
									<table class="form-table cptui-table">

										<?php

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'label',
											'textvalue' => isset( $current['label'] ) ? esc_attr( $current['label'] ) : '',
											'aftertext' => esc_html__( '(e.g. Actors)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Plural Label', 'custom-post-type-ui' ),
											'helptext'  => esc_attr__( 'Used for the taxonomy admin menu item.', 'custom-post-type-ui' ),
											'required'  => true,
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'singular_label',
											'textvalue' => isset( $current['singular_label'] ) ? esc_attr( $current['singular_label'] ) : '',
											'aftertext' => esc_html__( '(e.g. Actor)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Singular Label', 'custom-post-type-ui' ),
											'helptext'  => esc_attr__( 'Used when a singular label is needed.', 'custom-post-type-ui' ),
											'required'  => true,
										] );

										if ( isset( $current['description'] ) ) {
											$current['description'] = stripslashes_deep( $current['description'] );
										}
										echo $ui->get_textarea_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'description',
											'rows'      => '4',
											'cols'      => '40',
											'textvalue' => isset( $current['description'] ) ? esc_textarea( $current['description'] ) : '',
											'labeltext' => esc_html__( 'Description', 'custom-post-type-ui' ),
											'helptext'  => esc_attr__( 'Describe what your taxonomy is used for.', 'custom-post-type-ui' ),
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'menu_name',
											'textvalue' => isset( $current['labels']['menu_name'] ) ? esc_attr( $current['labels']['menu_name'] ) : '',
											'aftertext' => esc_attr__( '(e.g. Actors)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Menu Name', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Custom admin menu name for your taxonomy.', 'custom-post-type-ui' ),
											'data'      => [
												'label'     => 'item', // Not localizing because it's isolated.
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'all_items',
											'textvalue' => isset( $current['labels']['all_items'] ) ? esc_attr( $current['labels']['all_items'] ) : '',
											'aftertext' => esc_attr__( '(e.g. All Actors)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'All Items', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used as tab text when showing all terms for hierarchical taxonomy while editing post.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'All %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'edit_item',
											'textvalue' => isset( $current['labels']['edit_item'] ) ? esc_attr( $current['labels']['edit_item'] ) : '',
											'aftertext' => esc_attr__( '(e.g. Edit Actor)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Edit Item', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used at the top of the term editor screen for an existing taxonomy term.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Edit %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'view_item',
											'textvalue' => isset( $current['labels']['view_item'] ) ? esc_attr( $current['labels']['view_item'] ) : '',
											'aftertext' => esc_attr__( '(e.g. View Actor)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'View Item', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used in the admin bar when viewing editor screen for an existing taxonomy term.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'View %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'update_item',
											'textvalue' => isset( $current['labels']['update_item'] ) ? esc_attr( $current['labels']['update_item'] ) : '',
											'aftertext' => esc_attr__( '(e.g. Update Actor Name)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Update Item Name', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Update %s name', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'add_new_item',
											'textvalue' => isset( $current['labels']['add_new_item'] ) ? esc_attr( $current['labels']['add_new_item'] ) : '',
											'aftertext' => esc_attr__( '(e.g. Add New Actor)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Add New Item', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Used at the top of the term editor screen and button text for a new taxonomy term.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Add new %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'new_item_name',
											'textvalue' => isset( $current['labels']['new_item_name'] ) ? esc_attr( $current['labels']['new_item_name'] ) : '',
											'aftertext' => esc_attr__( '(e.g. New Actor Name)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'New Item Name', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'New %s name', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'parent_item',
											'textvalue' => isset( $current['labels']['parent_item'] ) ? esc_attr( $current['labels']['parent_item'] ) : '',
											'aftertext' => esc_attr__( '(e.g. Parent Actor)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Parent Item', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Parent %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'parent_item_colon',
											'textvalue' => isset( $current['labels']['parent_item_colon'] ) ? esc_attr( $current['labels']['parent_item_colon'] ) : '',
											'aftertext' => esc_attr__( '(e.g. Parent Actor:)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Parent Item Colon', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Parent %s:', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'singular',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'search_items',
											'textvalue' => isset( $current['labels']['search_items'] ) ? esc_attr( $current['labels']['search_items'] ) : '',
											'aftertext' => esc_attr__( '(e.g. Search Actors)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Search Items', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Search %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'popular_items',
											'textvalue' => isset( $current['labels']['popular_items'] ) ? esc_attr( $current['labels']['popular_items'] ) : null,
											'aftertext' => esc_attr__( '(e.g. Popular Actors)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Popular Items', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Popular %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'separate_items_with_commas',
											'textvalue' => isset( $current['labels']['separate_items_with_commas'] ) ? esc_attr( $current['labels']['separate_items_with_commas'] ) : null,
											'aftertext' => esc_attr__( '(e.g. Separate Actors with commas)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Separate Items with Commas', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Separate %s with commas', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'add_or_remove_items',
											'textvalue' => isset( $current['labels']['add_or_remove_items'] ) ? esc_attr( $current['labels']['add_or_remove_items'] ) : null,
											'aftertext' => esc_attr__( '(e.g. Add or remove Actors)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Add or Remove Items', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Add or remove %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'choose_from_most_used',
											'textvalue' => isset( $current['labels']['choose_from_most_used'] ) ? esc_attr( $current['labels']['choose_from_most_used'] ) : null,
											'aftertext' => esc_attr__( '(e.g. Choose from the most used Actors)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Choose From Most Used', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Choose from the most used %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'not_found',
											'textvalue' => isset( $current['labels']['not_found'] ) ? esc_attr( $current['labels']['not_found'] ) : null,
											'aftertext' => esc_attr__( '(e.g. No Actors found)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Not found', 'custom-post-type-ui' ),
											'helptext'  => esc_html__( 'Custom taxonomy label. Used in the admin menu for displaying taxonomies.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'No %s found', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'no_terms',
											'textvalue' => isset( $current['labels']['no_terms'] ) ? esc_attr( $current['labels']['no_terms'] ) : null,
											'aftertext' => esc_html__( '(e.g. No actors)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'No terms', 'custom-post-type-ui' ),
											'helptext'  => esc_attr__( 'Used when indicating that there are no terms in the given taxonomy associated with an object.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'No %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'items_list_navigation',
											'textvalue' => isset( $current['labels']['items_list_navigation'] ) ? esc_attr( $current['labels']['items_list_navigation'] ) : null,
											'aftertext' => esc_html__( '(e.g. Actors list navigation)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Items List Navigation', 'custom-post-type-ui' ),
											'helptext'  => esc_attr__( 'Screen reader text for the pagination heading on the term listing screen.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( '%s list navigation', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'items_list',
											'textvalue' => isset( $current['labels']['items_list'] ) ? esc_attr( $current['labels']['items_list'] ) : null,
											'aftertext' => esc_html__( '(e.g. Actors list)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Items List', 'custom-post-type-ui' ),
											'helptext'  => esc_attr__( 'Screen reader text for the items list heading on the term listing screen.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( '%s list', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'not_found',
											'textvalue' => isset( $current['labels']['not_found'] ) ? esc_attr( $current['labels']['not_found'] ) : null,
											'aftertext' => esc_html__( '(e.g. No actors found)', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Not Found', 'custom-post-type-ui' ),
											'helptext'  => esc_attr__( 'The text displayed via clicking ‘Choose from the most used items’ in the taxonomy meta box when no items are available.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'No %s found', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );

										echo $ui->get_text_input( [
											'namearray' => 'cptui-i18n-taxonomies',
											'name'      => 'back_to_items',
											'textvalue' => isset( $current['labels']['back_to_items'] ) ? esc_attr( $current['labels']['back_to_items'] ) : null,
											'aftertext' => esc_html__( '(e.g. &larr; Back to actors', 'custom-post-type-ui' ),
											'labeltext' => esc_html__( 'Back to Items', 'custom-post-type-ui' ),
											'helptext'  => esc_attr__( 'The text displayed after a term has been updated for a link back to main index.', 'custom-post-type-ui' ),
											'data'      => [
												/* translators: Used for autofill */
												'label'     => sprintf( esc_attr__( 'Back to %s', 'custom-post-type-ui' ), 'item' ),
												'plurality' => 'plural',
											],
										] );
										?>
									</table>
								</div>
							</div>
						</div>



						<p class="submit">
							<?php
							wp_nonce_field( 'cptui_addedit_i18n_nonce_action', 'cptui_addedit_i18n_nonce_field' );
							if ( ! empty( $_GET ) && ! empty( $_GET['action'] ) && 'edit' === $_GET['action'] ) { ?>
								<?php

								/**
								 * Filters the text value to use on the button when editing.
								 *
								 * @param string $value Text to use for the button.
								 *
								 * @since 1.0.0
								 */
								?>
								<input type="submit" class="button-primary cptui-i18n-submit" name="cpt_submit" value="<?php echo esc_attr( apply_filters( 'cptui_i18n_submit_edit', esc_attr__( 'Save Translation', 'custom-post-type-ui' ) ) ); ?>" />
								<?php

								/**
								 * Filters the text value to use on the button when deleting.
								 *
								 * @param string $value Text to use for the button.
								 *
								 * @since 1.0.0
								 */
								?>
								<input type="submit" class="button-secondary cptui-delete-bottom" name="cpt_delete" id="cpt_submit_delete" value="<?php echo esc_attr( apply_filters( 'cptui_i18n_submit_delete', __( 'Delete Translation', 'custom-post-type-ui' ) ) ); ?>" />
							<?php } else { ?>yyyy
								<?php

								/**
								 * Filters the text value to use on the button when adding.
								 *
								 * @param string $value Text to use for the button.
								 *
								 * @since 1.0.0
								 */
								?>
								<input type="submit" class="button-primary cptui-i18n-submit" name="cpt_submit" value="<?php echo esc_attr( apply_filters( 'cptui_i18n_submit_add', esc_attr__( 'Add Translation', 'custom-post-type-ui' ) ) ); ?>" />
							<?php } ?>
						</p>
					</div>
				</div>
			</form>
			<?php
			}
			?>
	</div><!-- End .wrap -->
	<?php
}

function cptui_delete_lang() {}

function cptui_update_lang() {}

function cptui_process_lang() {
	if ( wp_doing_ajax() ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}

	if ( ! empty( $_GET ) && isset( $_GET['page'] ) && 'cptui_translations' !== $_GET['page'] ) {
		return;
	}

	if ( ! empty( $_POST ) ) {
		$result = '';
		if ( isset( $_POST['cpt_submit'] ) ) {
			check_admin_referer( 'cptui_addedit_i18n_nonce_action', 'cptui_addedit_i18n_nonce_field' );
			$result = cptui_update_lang( $_POST );
		} elseif ( isset( $_POST['cpt_delete'] ) ) {
			check_admin_referer( 'cptui_addedit_i18n_nonce_action', 'cptui_addedit_i18n_nonce_field' );
			$result = cptui_delete_lang( $_POST );
			#add_filter( 'cptui_post_type_deleted', '__return_true' );
		}

		if ( $result ) {
			if ( is_callable( "cptui_{$result}_admin_notice" ) ) {
				add_action( 'admin_notices', "cptui_{$result}_admin_notice" );
			}
		}
		if ( isset( $_POST['cpt_delete'] ) && empty( cptui_get_post_type_slugs() ) ) {
			wp_safe_redirect(
				add_query_arg(
					[ 'page' => 'cptui_translations' ],
					cptui_admin_url( 'admin.php?page=cptui_translations' )
				)
			);
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\cptui_process_lang' );

function cptui_langs_dropdown() {
	$current_langs = get_available_languages();
	$selected_lang = cptui_get_current_lang( $current_langs );

	wp_dropdown_languages(
		[
			'name'                        => 'cptui_i18n_lang',
			'id'                          => 'cptui_i18n_lang',
			'selected'                    => $selected_lang,
			'languages'                   => $current_langs,
			'translations'                => [],
			'show_available_translations' => false,
			'show_option_en_us'           => false,
			'show_option_site_default'    => true,
		]
	);
}

function cptui_get_current_lang( $available ) {
	$lang = '';
	if ( ! empty( $_POST ) ) {
		if ( ! empty( $_POST['cptui-i18n'] ) ) {
			check_admin_referer( 'cptui_select_i18n_nonce_action', 'cptui_select_i18n_nonce_field' );
		}
		if ( isset( $_POST['cptui-i18n'] ) ) {
			$lang = sanitize_text_field( $_POST['cptui-i18n'] );
		}
	}
	return $lang;
}
