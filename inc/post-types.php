<?php
/*
 * TODO: help bubble link hover color
 */
/**
 * Add our cptui.js file, with dependencies on jQuery and jQuery UI
 *
 * @since  0.9
 *
 * @return mixed  js scripts
 */
function cpt_post_type_enqueue_scripts() {
	wp_enqueue_script( 'cptui', plugins_url( 'js/cptui.js' , dirname(__FILE__) ) . '', array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion' ), '0.9', true );
}
add_action( 'admin_enqueue_scripts', 'cpt_post_type_enqueue_scripts' );

/**
 * Add our settings page to the menu.
 *
 * @since  0.9
 *
 * @return mixed  new menu
 */
function cpt_post_types_admin_menu() {
	add_submenu_page( 'cptui_main_menu', __( 'Add/Edit Post Types', 'cpt-plugin' ), __( 'Add/Edit Post Types', 'cpt-plugin' ), 'manage_options', 'cptui_manage_post_types', 'cptui_manage_post_types' );
}
add_action( 'admin_menu', 'cpt_post_types_admin_menu' );

/**
 * Create our settings page output
 *
 * @since  0.9
 *
 * @return mixed  webpage
 */
function cptui_manage_post_types() {

	$tab = ( !empty( $_GET ) && !empty( $_GET['action'] ) && 'edit' == $_GET['action'] ) ? 'edit' : 'new';

	echo '<div class="wrap">';

	//Display any success messages or errors.
	if ( $success = cptui_get_post_type_successes() ) {
		echo $success;
	}

	if ( $errors = cptui_get_post_type_errors() ) {
		echo $errors;
	}

	//Create our tabs.
	cpt_settings_tab_menu();

	//Fetch and set up our post types if we're in the edit tab.
	if ( 'edit' == $tab ) {
		//Fetch our post types and store in a variable.
		$post_types = get_option( 'cptui_post_types' );

		//Grab our current selected post type to edit
		$selected_post_type = cpt_get_current_post_type();

		//fetch current post type out of all available post types.
		if ( $selected_post_type ) {
			$current = $post_types[ $selected_post_type ];
		}
	}

	//Instantiate our UI class.
	$ui = new cptui_admin_ui();

	//Will only be set if we're already on the edit screen
	if ( !empty( $post_types ) ) { ?>
		<form id="cptui_select_post_type" method="post">
			<?php
			cpt_post_types_dropdown( $post_types );
			?>
			<input type="submit" class="button-secondary" name="cptui_select_post_type_submit" value="<?php echo apply_filters( 'cptui_post_type_submit_select', __( 'Select Post Type', 'cpt-plugin' ) ); ?>" />
		</form>
	<?php
	} ?>

	<form method="post">
		<table class="form-table cptui-table">
			<tr>
				<td><!--LEFT SIDE-->
					<table>
						<?php
						/*
						 * Post Slug
						 */
						echo $ui->get_text_input( array(
							'namearray'     => 'cpt_custom_post_type',
							'name'          => 'name',
							'textvalue'     => ( isset( $current['name'] ) ) ? esc_attr( $current['name'] ) : '',
							'maxlength'     => '20',
							'onblur'        => 'this.value=this.value.toLowerCase()',
							'labeltext'     => __( 'Post Type Name', 'cpt-plugin' ),
							'aftertext'     => __( '(e.g. movie)', 'cpt-plugin' ),
							'helptext'      => esc_attr__( 'The post type name.  Used to retrieve custom post type content.  Should be short and sweet', 'cpt-plugin'),
							'required'      => true
							) );

						/*
						 * Post Label
						 */
						echo $ui->get_text_input( array(
							'namearray'     => 'cpt_custom_post_type',
							'name'          => 'label',
							'textvalue'     => ( isset( $current['label'] ) ) ? esc_attr( $current['label'] ) : '',
							'labeltext'     => __( 'Label', 'cpt-plugin' ),
							'aftertext'     => __( '(e.g. Movies)', 'cpt-plugin' ),
							'helptext'      => esc_attr__( 'Post type label.  Used in the admin menu for displaying post types.', 'cpt-plugin' ),
							) );

						/*
						 * Post Singular Slug
						 */
						echo $ui->get_text_input( array(
							'namearray'     => 'cpt_custom_post_type',
							'name'          => 'singular_label',
							'textvalue'     => ( isset( $current['singular_label'] ) ) ? esc_attr( $current['singular_label'] ) : '',
							'labeltext'     => __( 'Singular Label', 'cpt-plugin' ),
							'aftertext'     => __( '(e.g. Movie)', 'cpt-plugin' ),
							'helptext'      => esc_attr__( 'Custom Post Type Singular label. Used in WordPress when a singular label is needed.', 'cpt-plugin' ),
							) );

						/*
						 * Post Description
						 */
						echo $ui->get_textarea_input( array(
							'namearray' => 'cpt_custom_post_type',
							'name' => 'description',
							'rows' => '4',
							'cols' => '40',
							'textvalue' => ( isset( $current['description'] ) ) ?  esc_textarea( $current['description'] ) : '',
							'labeltext' => __('Description', 'cpt-plugin'),
							'helptext' => esc_attr__( 'Custom Post Type Description. Describe what your custom post type is used for.', 'cpt-plugin' )
							) );
						?>
					</table>
				<p class="submit">
					<?php if ( !empty( $_GET ) && !empty( $_GET['action'] ) && 'edit' == $_GET['action'] ) { ?>
						<input type="submit" class="button-primary" name="cpt_submit" value="<?php echo apply_filters( 'cptui_post_type_submit_edit', __( 'Edit Post Type', 'cpt-plugin' ) ); ?>" />
					<?php } else { ?>
						<input type="submit" class="button-primary" name="cpt_submit" value="<?php echo apply_filters( 'cptui_post_type_submit_add', __( 'Add Post Type', 'cpt-plugin' ) ); ?>" />
					<?php } ?>
				</p>
			</td>
			<td>
				<?php echo '<small>' . __( 'Click headings to toggle. Scroll or keyboard tab to see all options.', 'cpt-plugin' ) . '</small>'; ?>

				<div id="cptui_accordion">
					<h3>Labels</h3>
						<div>
							<table>
							<?php
							/*
							 * Post Admin Menu Name
							 */
							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_labels',
								'name'          => 'menu_name',
								'textvalue'     => ( isset( $current['labels']['menu_name'] ) ) ? esc_attr( $current['labels']['menu_name'] ) : '',
								'labeltext'     => __( 'Menu Name', 'cpt-plugin' ),
								'aftertext'     => __( '(e.g. My Movies)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom menu name for your custom post type.', 'cpt-plugin')
								) );

							/*
							 * Add New Label
							 */
							echo $ui->get_text_input( array(
								'labeltext'     => __( 'Add New', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Post type label.  Used in the admin menu for displaying post types.', 'cpt-plugin' ),
								'namearray'     => 'cpt_labels',
								'name'          => 'add_new',
								'textvalue'     => ( isset( $current['labels']['add_new'] ) ) ? esc_attr( $current['labels']['add_new'] ) : '',
								'aftertext'     => __( '(e.g. Add New)', 'cpt-plugin' )
								) );

							/*
							 * Add New Item Label
							 */
							echo $ui->get_text_input( array(
								'labeltext'     => __( 'Add New Item', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Post type label.  Used in the admin menu for displaying post types.', 'cpt-plugin' ),
								'namearray'     => 'cpt_labels',
								'name'          => 'add_new_item',
								'textvalue'     => ( isset( $current['labels']['add_new_item'] ) ) ? esc_attr( $current['labels']['add_new_item'] ) : '',
								'aftertext'     => __( '(e.g. Add New Movie)', 'cpt-plugin' )
								) );

							/*
							 * Edit Label
							 */
							echo $ui->get_text_input( array(
								'labeltext'     => __( 'Edit', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Post type label.  Used in the admin menu for displaying post types.', 'cpt-plugin' ),
								'namearray'     => 'cpt_labels',
								'name'          => 'edit',
								'textvalue'     => ( isset( $current['labels']['edit'] ) ) ? esc_attr( $current['labels']['edit'] ) : '',
								'aftertext'     => __( '(e.g. Edit)', 'cpt-plugin' )
								) );

							/*
							 * Edit Item Label
							 */
							echo $ui->get_text_input( array(
								'labeltext'     => __( 'Edit Item', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Post type label.  Used in the admin menu for displaying post types.', 'cpt-plugin' ),
								'namearray'     => 'cpt_labels',
								'name'          => 'edit_item',
								'textvalue'     => ( isset( $current['labels']['edit_item'] ) ) ? esc_attr( $current['labels']['edit_item'] ) : '',
								'aftertext'     => __( '(e.g. Edit Movie)', 'cpt-plugin' )
								) );

							/*
							 * New Item Label
							 */
							echo $ui->get_text_input( array(
								'labeltext'     => __( 'New Item', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Post type label.  Used in the admin menu for displaying post types.', 'cpt-plugin' ),
								'namearray'     => 'cpt_labels',
								'name'          => 'new_item',
								'textvalue'     => ( isset( $current['labels']['new_item'] ) ) ? esc_attr( $current['labels']['new_item'] ) : '',
								'aftertext'     => __( '(e.g. New Movie)', 'cpt-plugin' )
								) );

							/*
							 * View Label
							 */
							echo $ui->get_text_input( array(
								'labeltext'     => __( 'View', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Post type label.  Used in the admin menu for displaying post types.', 'cpt-plugin' ),
								'namearray'     => 'cpt_labels',
								'name'          => 'view',
								'textvalue'     => ( isset( $current['labels']['view'] ) ) ? esc_attr( $current['labels']['view'] ) : '',
								'aftertext'     => __( '(e.g. View Movie)', 'cpt-plugin' )
								) );

							/*
							 * View Item Label
							 */
							echo $ui->get_text_input( array(
								'labeltext'     => __( 'View Item', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Post type label.  Used in the admin menu for displaying post types.', 'cpt-plugin' ),
								'namearray'     => 'cpt_labels',
								'name'          => 'view_item',
								'textvalue'     => ( isset( $current['labels']['view_item'] ) ) ? esc_attr( $current['labels']['view_item'] ) : '',
								'aftertext'     => __( '(e.g. View Movie)', 'cpt-plugin' )
								) );

							/*
							 * Search Item Label
							 */
							echo $ui->get_text_input( array(
								'labeltext'     => __( 'Search Item', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Post type label.  Used in the admin menu for displaying post types.', 'cpt-plugin' ),
								'namearray'     => 'cpt_labels',
								'name'          => 'search_items',
								'textvalue'     => ( isset( $current['labels']['search_items'] ) ) ? esc_attr( $current['labels']['search_items'] ) : '',
								'aftertext'     => __( '(e.g. Search Movie)', 'cpt-plugin' )
								) );

							/*
							 * Not Found Label
							 */
							echo $ui->get_text_input( array(
								'labeltext'     => __( 'Not Found', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Post type label.  Used in the admin menu for displaying post types.', 'cpt-plugin' ),
								'namearray'     => 'cpt_labels',
								'name'          => 'not_found',
								'textvalue'     => ( isset( $current['labels']['not_found'] ) ) ? esc_attr( $current['labels']['not_found'] ) : '',
								'aftertext'     => __( '(e.g. No Movies Found)', 'cpt-plugin' )
								) );

							/*
							 * Not Found In Trash Label
							 */
							echo $ui->get_text_input( array(
								'labeltext'     => __( 'Not Found in Trash', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Post type label.  Used in the admin menu for displaying post types.', 'cpt-plugin' ),
								'namearray'     => 'cpt_labels',
								'name'          => 'not_found_in_trash',
								'textvalue'     => ( isset( $current['labels']['not_found_in_trash'] ) ) ? esc_attr( $current['labels']['not_found_in_trash'] ) : '',
								'aftertext'     => __( '(e.g. No Movies found in Trash)', 'cpt-plugin' )
								) );

							/*
							 * Parent Label
							 */
							echo $ui->get_text_input( array(
								'labeltext'     => __( 'Parent', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Post type label.  Used in the admin menu for displaying post types.', 'cpt-plugin' ),
								'namearray'     => 'cpt_labels',
								'name'          => 'parent',
								'textvalue'     => ( isset( $current['labels']['parent'] ) ) ? esc_attr( $current['labels']['parent'] ) : '',
								'aftertext'     => __( '(e.g. Parent Movie)', 'cpt-plugin' )
								) );
							?>
							</table>
						</div>
					<h3>Settings</h3>
						<div>
							<table>
							<?php
							/*
							 * Public Boolean
							 */
							$select = array(
								'options' => array(
									array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
									array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
								)
							);
							$select['selected'] = ( isset( $current['public'] ) ) ? $current['public'] : '';
							echo $ui->get_select_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'public',
								'labeltext'     => __( 'Public', 'cpt-plugin' ),
								'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Whether posts of this type should be shown in the admin UI', 'cpt-plugin' ),
								'selections'    => $select
							) );

							/*
							 * Show UI Boolean
							 */
							$select = array(
								'options' => array(
									array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
									array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
								)
							);
							$select['selected'] = ( isset( $current['show_ui'] ) ) ? $current['show_ui'] : '';
							echo $ui->get_select_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'show_ui',
								'labeltext'     => __( 'Show UI', 'cpt-plugin' ),
								'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Whether to generate a default UI for managing this post type', 'cpt-plugin' ),
								'selections'    => $select
							) );

							/*
							 * Has Archive Boolean
							 */
							$select = array(
								'options' => array(
									array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ), 'default' => 'true' ),
									array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ) )
								)
							);
							$select['selected'] = ( isset( $current['has_archive'] ) ) ? $current['has_archive'] : '';
							echo $ui->get_select_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'has_archive',
								'labeltext'     => __( 'Has Archive', 'cpt-plugin' ),
								'aftertext'     => __( '(default: False)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Whether the post type will have a post type archive page', 'cpt-plugin' ),
								'selections'    => $select
							) );

							/*
							 * Exclude From Search Boolean
							 */
							$select = array(
								'options' => array(
									array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ), 'default' => 'true' ),
									array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ) )
								)
							);
							$select['selected'] = ( isset( $current['exclude_from_search'] ) ) ? $current['exclude_from_search'] : '';
							echo $ui->get_select_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'exclude_from_search',
								'labeltext'     => __( 'Exclude From Search', 'cpt-plugin' ),
								'aftertext'     => __( '(default: False)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Whether the post type will be searchable', 'cpt-plugin' ),
								'selections'    => $select
							) );

							/*
							 * Capability Type Input
							 */
							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'capability_type',
								'textvalue'     => ( isset( $current['capability_type'] ) ) ? esc_attr( $current['capability_type'] ) : 'post',
								'labeltext'     => __( 'Capability Type', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'The post type to use for checking read, edit, and delete capabilities', 'cpt-plugin' ),
								) );

							/*
							 * Hierarchical Boolean
							 */
							$select = array(
								'options' => array(
									array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ), 'default' => 'true' ),
									array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ) )
								)
							);
							$select['selected'] = ( isset( $current['hierarchical'] ) ) ? $current['hierarchical'] : '';
							echo $ui->get_select_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'hierarchical',
								'labeltext'     => __( 'Hierarchical', 'cpt-plugin' ),
								'aftertext'     => __( '(default: False)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Whether the post type can have parent-child relationships', 'cpt-plugin' ),
								'selections'    => $select
							) );

							/*
							 * Rewrite Boolean
							 */
							$select = array(
								'options' => array(
									array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
									array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
								)
							);
							$select['selected'] = ( isset( $current['rewrite'] ) ) ? $current['rewrite'] : '';
							echo $ui->get_select_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'rewrite',
								'labeltext'     => __( 'Rewrite', 'cpt-plugin' ),
								'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Triggers the handling of rewrites for this post type', 'cpt-plugin' ),
								'selections'    => $select
							) );

							/*
							 * Rewrite Slug Input
							 */
							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'rewrite_slug',
								'textvalue'     => ( isset( $current['rewrite_slug'] ) ) ? esc_attr( $current['rewrite_slug'] ) : '',
								'labeltext'     => __( 'Custom Rewrite Slug', 'cpt-plugin' ),
								'aftertext'     => __( '(default: post type name)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Custom slug to use instead of the default.', 'cpt-plugin' ),
							) );

							/*
							 * Rewrite With Front Boolean
							 */
							$select = array(
								'options' => array(
									array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
									array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
								)
							);
							$select['selected'] = ( isset( $current['rewrite_withfront'] ) ) ? $current['rewrite_withfront'] : '';
							echo $ui->get_select_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'rewrite_withfront',
								'labeltext'     => __( 'With Front', 'cpt-plugin' ),
								'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Should the permastruct be prepended with the front base.', 'cpt-plugin' ),
								'selections'    => $select
							) );

							/*
							 * Query Var Boolean
							 */
							$select = array(
								'options' => array(
									array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
									array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
								)
							);
							$select['selected'] = ( isset( $current['query_var'] ) ) ? $current['query_var'] : '';
							echo $ui->get_select_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'query_var',
								'labeltext'     => __( 'Query Var', 'cpt-plugin' ),
								'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Should the permastruct be prepended with the front base.', 'cpt-plugin' ),
								'selections'    => $select
							) );

							echo $ui->get_tr_start() . $ui->get_th_start() . __('Menu Position', 'cpt-plugin');

							echo $ui->get_help( esc_attr__( 'The position in the menu order the post type should appear. show_in_menu must be true.', 'cpt-plugin' ) );
							echo $ui->get_p( __( 'See <a href="http://codex.wordpress.org/Function_Reference/register_post_type#Parameters">Available options</a> in the "menu_position" section. Range of 5-100', 'cpt-plugin' ) );

							echo $ui->get_th_end() . $ui->get_td_start();
							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'menu_position',
								'textvalue'     => ( isset( $current['menu_position'] ) ) ? esc_attr( $current['menu_position'] ) : '',
								'helptext'      => esc_attr__( 'URL to image to be used as menu icon.', 'cpt-plugin' ),
								'wrap'          => false
							) );
							echo $ui->get_td_end() . $ui->get_tr_end();

							echo $ui->get_tr_start() . $ui->get_th_start() . __('Show in Menu', 'cpt-plugin');
							echo $ui->get_p( __( '"Show UI" must be "true". If an existing top level page such as "tools.php" is indicated for second input, post type will be sub menu of that.', 'cpt-plugins' ) );
							echo $ui->get_th_end() . $ui->get_td_start();

							/*
							 * Show In Menu Boolean
							 */
							$select = array(
								'options' => array(
									array( 'attr' => '0', 'text' => __( 'False', 'cpt-plugin' ) ),
									array( 'attr' => '1', 'text' => __( 'True', 'cpt-plugin' ), 'default' => 'true' )
								)
							);
							$select['selected'] = ( isset( $current['show_in_menu'] ) ) ? $current['show_in_menu'] : '';
							echo $ui->get_select_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'show_in_menu',
								'labeltext'     => __( 'Show In Menu', 'cpt-plugin' ),
								'aftertext'     => __( '(default: True)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Whether to show the post type in the admin menu and where to show that menu. Note that show_ui must be true', 'cpt-plugin' ),
								'selections'    => $select,
								'wrap'          => false
							) );

							/*
							 * Show In Menu Input
							 */
							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'show_in_menu_string',
								'textvalue'     => ( isset( $current['show_in_menu_string'] ) ) ? esc_attr( $current['show_in_menu_string'] ) : '',
								'helptext'      => esc_attr__( 'URL to image to be used as menu icon.', 'cpt-plugin' ),
								'wrap'          => false
							) );
							echo $ui->get_td_end() . $ui->get_tr_end();
							/*
							 * Menu Icon
							 */
							echo $ui->get_text_input( array(
								'namearray'     => 'cpt_custom_post_type',
								'name'          => 'menu_icon',
								'textvalue'     => ( isset( $current['menu_icon'] ) ) ? esc_attr( $current['menu_icon'] ) : '',
								'labeltext'     => __( 'Menu Icon', 'cpt-plugin' ),
								'aftertext'     => __( '(Full URL for icon)', 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'URL to image to be used as menu icon.', 'cpt-plugin' ),
							) );

							echo $ui->get_tr_start() . $ui->get_th_start() . __('Supports', 'cpt-plugin') . $ui->get_th_end() . $ui->get_td_start();
							/*
							 * Supports Title Checkbox
							 */
							echo $ui->get_check_input( array(
								'checkvalue'    => 'title',
								'checked'       => ( !empty( $current['supports'] ) && is_array( $current['supports'] ) ) ? in_array( 'title', $current['supports'] ) : false,
								'name'          => 'title',
								'namearray'     => 'cpt_supports',
								'textvalue'     => 'title',
								'labeltext'     => __( 'Title' , 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Adds the title meta box when creating content for this custom post type', 'cpt-plugin' ),
								'default'       => true,
								'wrap'          => false
							) );

							/*
							 * Supports Editor Checkbox
							 */
							echo $ui->get_check_input( array(
								'checkvalue'    => 'editor',
								'checked'       => ( !empty( $current['supports'] ) && is_array( $current['supports'] ) ) ? in_array( 'editor', $current['supports'] ) : false,
								'name'          => 'editor',
								'namearray'     => 'cpt_supports',
								'textvalue'     => 'editor',
								'labeltext'     => __( 'Editor' , 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Adds the content editor meta box when creating content for this custom post type', 'cpt-plugin' ),
								'default'       => true,
								'wrap'          => false
							) );

							/*
							 * Supports Excerpt Checkbox
							 */
							echo $ui->get_check_input( array(
								'checkvalue'    => 'excerpt',
								'checked'       => ( !empty( $current['supports'] ) && is_array( $current['supports'] ) ) ? in_array( 'excerpt', $current['supports'] ) : false,
								'name'          => 'excerpt',
								'namearray'     => 'cpt_supports',
								'textvalue'     => 'excerpt',
								'labeltext'     => __( 'Excerpt' , 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Adds the excerpt meta box when creating content for this custom post type', 'cpt-plugin' ),
								'default'       => true,
								'wrap'          => false
							) );

							/*
							 * Supports Trackbacks Checkbox
							 */
							echo $ui->get_check_input( array(
								'checkvalue'    => 'trackbacks',
								'checked'       => ( !empty( $current['supports'] ) && is_array( $current['supports'] ) ) ? in_array( 'trackbacks', $current['supports'] ) : false,
								'name'          => 'trackbacks',
								'namearray'     => 'cpt_supports',
								'textvalue'     => 'trackbacks',
								'labeltext'     => __( 'Trackbacks' , 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Adds the trackbacks meta box when creating content for this custom post type', 'cpt-plugin' ),
								'default'       => true,
								'wrap'          => false
							) );

							/*
							 * Supports Custom Fields Checkbox
							 */
							echo $ui->get_check_input( array(
								'checkvalue'    => 'custom-fields',
								'checked'       => ( !empty( $current['supports'] ) && is_array( $current['supports'] ) ) ? in_array( 'custom-fields', $current['supports'] ) : false,
								'name'          => 'custom-fields',
								'namearray'     => 'cpt_supports',
								'textvalue'     => 'custom-fields',
								'labeltext'     => __( 'Custom Fields' , 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Adds the custom fields meta box when creating content for this custom post type', 'cpt-plugin' ),
								'default'       => true,
								'wrap'          => false
							) );

							/*
							 * Supports Comments Checkbox
							 */
							echo $ui->get_check_input( array(
								'checkvalue'    => 'comments',
								'checked'       => ( !empty( $current['supports'] ) && is_array( $current['supports'] ) ) ? in_array( 'comments', $current['supports'] ) : false,
								'name'          => 'comments',
								'namearray'     => 'cpt_supports',
								'textvalue'     => 'comments',
								'labeltext'     => __( 'Comments' , 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Adds the comments meta box when creating content for this custom post type', 'cpt-plugin' ),
								'default'       => true,
								'wrap'          => false
							) );

							/*
							 * Supports Revisions Checkbox
							 */
							echo $ui->get_check_input( array(
								'checkvalue'    => 'revisions',
								'checked'       => ( !empty( $current['supports'] ) && is_array( $current['supports'] ) ) ? in_array( 'revisions', $current['supports'] ) : false,
								'name'          => 'revisions',
								'namearray'     => 'cpt_supports',
								'textvalue'     => 'revisions',
								'labeltext'     => __( 'Revisions' , 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Adds the revisions meta box when creating content for this custom post type', 'cpt-plugin' ),
								'default'       => true,
								'wrap'          => false
							) );

							/*
							 * Supports Post Thumbnail Checkbox
							 */
							echo $ui->get_check_input( array(
								'checkvalue'    => 'thumbnail',
								'checked'       => ( !empty( $current['supports'] ) && is_array( $current['supports'] ) ) ? in_array( 'thumbnail', $current['supports'] ) : false,
								'name'          => 'thumbnail',
								'namearray'     => 'cpt_supports',
								'textvalue'     => 'thumbnail',
								'labeltext'     => __( 'Featured Image' , 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Adds the featured image meta box when creating content for this custom post type', 'cpt-plugin' ),
								'default'       => true,
								'wrap'          => false
							) );

							/*
							 * Supports Author Checkbox
							 */
							echo $ui->get_check_input( array(
								'checkvalue'    => 'author',
								'checked'       => ( !empty( $current['supports'] ) && is_array( $current['supports'] ) ) ? in_array( 'author', $current['supports'] ) : false,
								'name'          => 'author',
								'namearray'     => 'cpt_supports',
								'textvalue'     => 'author',
								'labeltext'     => __( 'Author' , 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Adds the author meta box when creating content for this custom post type', 'cpt-plugin' ),
								'default'       => true,
								'wrap'          => false
							) );

							/*
							 * Supports Page Attributes Checkbox
							 */
							echo $ui->get_check_input( array(
								'checkvalue'    => 'page-attributes',
								'checked'       => ( !empty( $current['supports'] ) && is_array( $current['supports'] ) ) ? in_array( 'page-attributes', $current['supports'] ) : false,
								'name'          => 'page-attributes',
								'namearray'     => 'cpt_supports',
								'textvalue'     => 'page-attributes',
								'labeltext'     => __( 'Page Attributes' , 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Adds the page attribute meta box when creating content for this custom post type', 'cpt-plugin' ),
								'default'       => true,
								'wrap'          => false
							) );

							/*
							 * Supports Post Formats Checkbox
							 */
							echo $ui->get_check_input( array(
								'checkvalue'    => 'post-formats',
								'checked'       => ( !empty( $current['supports'] ) && is_array( $current['supports'] ) ) ? in_array( 'post-formats', $current['supports'] ) : false,
								'name'          => 'post-formats',
								'namearray'     => 'cpt_supports',
								'textvalue'     => 'post-formats',
								'labeltext'     => __( 'Post Formats' , 'cpt-plugin' ),
								'helptext'      => esc_attr__( 'Adds post format support', 'cpt-plugin' ),
								'default'       => true,
								'wrap'          => false
							) );

							echo $ui->get_td_end() . $ui->get_tr_end();

							echo $ui->get_tr_start() . $ui->get_th_start() . __('Built-in Taxonomies', 'cpt-plugin') . $ui->get_th_end() . $ui->get_td_start();

							//load built-in WP Taxonomies
							$args = apply_filters( 'cptui_attach_taxonomies_to_post_type', array( 'public' => true ) );
							$output = apply_filters( 'cptui_attach_taxonomies_to_post_type_output', 'objects' );

							//If they don't return an array, fall back to the original default. Don't need to check for empty, because empty array is default for $args param in get_post_types anyway.
							if ( !is_array( $args ) ) {
								$args = array( 'public' => true );
							}

							$add_taxes = get_taxonomies( $args, $output );
							unset( $add_taxes['nav_menu'] ); unset( $add_taxes['post_format'] );
							foreach ( $add_taxes as $add_tax ) {
								/*
								 * Supports Taxonomies Checkbox
								 */
								echo $ui->get_check_input( array(
									'checkvalue'        => $add_tax->name,
									'checked'           => ( !empty( $current['taxonomies'] ) && is_array( $current['taxonomies'] ) ) ? in_array( $add_tax->name, $current['taxonomies'] ) : null,
									'name'              => $add_tax->name,
									'namearray'         => 'cpt_addon_taxes',
									'textvalue'         => $add_tax->name,
									'labeltext'         => $add_tax->label,
									'helptext'          => sprintf( esc_attr__( 'Adds %s support', 'cpt-plugin' ), $add_tax->name ),
									'wrap'              => false
								) );
							}

							echo $ui->get_td_end() . $ui->get_tr_end(); ?>
							</table>
						</div>
						<?php if ( 'new' == $tab ) { ?>
						<h3>Starter Notes</h3>
							<div><ol>
								<?php
								echo '<li>' . sprintf( __( 'Post Type names should have %s max 20 characters %s, and only contain alphanumeric, lowercase characters and underscores in place of spaces. Reserved names: post, page, attachment, revision, nav_menu_item.', 'cpt-plugin' ), '<strong class="wp-ui-highlight">', '</strong>' );
								echo '<li>' . sprintf( __( 'If you are unfamiliar with the advanced post type settings, just fill in the %s Post Type Name %s and %s Label %s fields. RRemaining settings will use default values. Labels, if left blank, will be automatically created based on the post type name. Hover over the question mark for more details.', 'cpt-plugin' ), '<strong class="wp-ui-highlight">', '</strong>', '<strong class="wp-ui-highlight">', '</strong>' );
								echo '<li>' . sprintf( __( 'Deleting custom post types will %s NOT %s delete any content into the database or added to those post types. You can easily recreate your post types and the content will still exist.', 'cpt-plugin' ), '<strong class="wp-ui-highlight">', '</strong>' ); ?>
							</ol></div>
						<?php } ?>
				</td>
			</tr>
		</table>
	</form>
	</div><!-- End .wrap -->
<?php
	cpt_footer();
}

/**
 * Fetch error message based on $_GET parameter
 *
 * @since  0.9
 *
 * @return mixed  false on no error, mixed when there is one.
 */
function cptui_get_post_type_errors() {
	if ( isset( $_GET['cpt_error'] ) ) :
		$error = $_GET['cpt_error'];

		$msg = '<div class="error">';

		if ( 1 == $error ) {
			$msg .= '<p>' . __( 'Post type name is a required field.', 'cpt-plugin' ) . '</p>';
		}
		if ( 2 == $error ) {
			$msg .= '<p>' . __( 'Please do not use quotes in your post type slug or rewrite slug.', 'cpt-plugin' ) . '</p>';
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
function cptui_get_post_type_successes() {
	if ( isset( $_GET['cpt_msg'] ) ) :
		$success = $_GET['cpt_msg'];

		$msg = '<div id="message" class="updated">';
		//TODO: filters
		if ( 1 == $success ) {
			$msg .= '<p>' . __( 'Custom post type created successfully. You may need to refresh to view the new post type in the admin menu.', 'cpt-plugin' ) . '</p>';
		}
		//TODO: ADD SUCCESS FOR DELETING TYPES
		$msg .= '</div>';

		return $msg;

	endif;

	return false;
}
//TODO: refactor.
function cptui_get_post_type_code( $post_type ) {
	// Begin the display for the "Get code" feature
	//display register_post_type code
	$custom_post_type   = '';
	$cpt_support_array  = '';
	$cpt_tax_array      = '';

	$cpt_label = ( empty( $cpt_post_type["label"] ) ) ? esc_html($cpt_post_type["name"]) : esc_html($cpt_post_type["label"]);
	$cpt_singular = ( empty( $cpt_post_type["singular_label"] ) ) ? $cpt_label : esc_html($cpt_post_type["singular_label"]);
	$cpt_rewrite_slug = ( empty( $cpt_post_type["rewrite_slug"] ) ) ? esc_html($cpt_post_type["name"]) : esc_html($cpt_post_type["rewrite_slug"]);
	$cpt_menu_position = ( empty( $cpt_post_type["menu_position"] ) ) ? null : intval($cpt_post_type["menu_position"]);
	$cpt_menu_icon = ( !empty( $cpt_post_type["menu_icon"] ) ) ? esc_url($cpt_post_type["menu_icon"]) : null;

	if ( true == $cpt_post_type["show_ui"] ) {
		$cpt_show_in_menu = ( $cpt_post_type["show_in_menu"] == 1 ) ? 1 : 0;
		$cpt_show_in_menu = ( $cpt_post_type["show_in_menu_string"] ) ? '\''.$cpt_post_type["show_in_menu_string"].'\'' : $cpt_show_in_menu;
	} else {
		$cpt_show_in_menu = 0;
	}

	//set custom label values
	$cpt_labels['name'] = $cpt_label;
	$cpt_labels['singular_name'] = $cpt_post_type["singular_label"];
	$cpt_labels['menu_name'] = ( $cpt_post_type[2]["menu_name"] ) ? $cpt_post_type[2]["menu_name"] : $cpt_label;
	$cpt_labels['add_new'] = ( $cpt_post_type[2]["add_new"] ) ? $cpt_post_type[2]["add_new"] : 'Add ' .$cpt_singular;
	$cpt_labels['add_new_item'] = ( $cpt_post_type[2]["add_new_item"] ) ? $cpt_post_type[2]["add_new_item"] : 'Add New ' .$cpt_singular;
	$cpt_labels['edit'] = ( $cpt_post_type[2]["edit"] ) ? $cpt_post_type[2]["edit"] : 'Edit';
	$cpt_labels['edit_item'] = ( $cpt_post_type[2]["edit_item"] ) ? $cpt_post_type[2]["edit_item"] : 'Edit ' .$cpt_singular;
	$cpt_labels['new_item'] = ( $cpt_post_type[2]["new_item"] ) ? $cpt_post_type[2]["new_item"] : 'New ' .$cpt_singular;
	$cpt_labels['view'] = ( $cpt_post_type[2]["view"] ) ? $cpt_post_type[2]["view"] : 'View ' .$cpt_singular;
	$cpt_labels['view_item'] = ( $cpt_post_type[2]["view_item"] ) ? $cpt_post_type[2]["view_item"] : 'View ' .$cpt_singular;
	$cpt_labels['search_items'] = ( $cpt_post_type[2]["search_items"] ) ? $cpt_post_type[2]["search_items"] : 'Search ' .$cpt_label;
	$cpt_labels['not_found'] = ( $cpt_post_type[2]["not_found"] ) ? $cpt_post_type[2]["not_found"] : 'No ' .$cpt_label. ' Found';
	$cpt_labels['not_found_in_trash'] = ( $cpt_post_type[2]["not_found_in_trash"] ) ? $cpt_post_type[2]["not_found_in_trash"] : 'No ' .$cpt_label. ' Found in Trash';
	$cpt_labels['parent'] = ( $cpt_post_type[2]["parent"] ) ? $cpt_post_type[2]["parent"] : 'Parent ' .$cpt_singular;

	if( is_array( $cpt_post_type[0] ) ) {
		$counter = 1;
		$count = count( $cpt_post_type[0] );
		foreach ( $cpt_post_type[0] as $cpt_supports ) {
			//build supports variable
			$cpt_support_array .= '\'' . $cpt_supports . '\'';
			if ( $counter != $count ) {
				$cpt_support_array .= ',';
			}

			$counter++;
		}
	}

	if( is_array( $cpt_post_type[1] ) ) {
		$counter = 1;
		$count = count( $cpt_post_type[1] );
		foreach ( $cpt_post_type[1] as $cpt_taxes ) {
			//build taxonomies variable
			$cpt_tax_array .= '\''.$cpt_taxes.'\'';
			if ( $counter != $count ) {
				$cpt_tax_array .= ',';
			}
			$counter++;
		}
	}

	$custom_post_type = "add_action('init', 'cptui_register_my_cpt_" . $cpt_post_type["name"] . "');\n";
	$custom_post_type .= "function cptui_register_my_cpt_" . $cpt_post_type["name"] . "() {\n";
	$custom_post_type .= "register_post_type('" . $cpt_post_type["name"] . "', array(\n'label' => '" . $cpt_label . "',\n";
	$custom_post_type .= "'description' => '" . $cpt_post_type["description"] . "',\n";
	$custom_post_type .= "'public' => " . disp_boolean( $cpt_post_type["public"]) . ",\n";
	$custom_post_type .= "'show_ui' => " . disp_boolean( $cpt_post_type["show_ui"]) . ",\n";
	$custom_post_type .= "'show_in_menu' => " . disp_boolean( $cpt_show_in_menu) . ",\n";
	$custom_post_type .= "'capability_type' => '" . $cpt_post_type["capability_type"] . "',\n";
	$custom_post_type .= "'map_meta_cap' => " . disp_boolean( '1' ) . ",\n";
	$custom_post_type .= "'hierarchical' => " . disp_boolean( $cpt_post_type["hierarchical"] ) . ",\n";

	if ( !empty( $cpt_post_type["rewrite_slug"] ) ) {
		$custom_post_type .= "'rewrite' => array('slug' => '" . $cpt_post_type["rewrite_slug"] . "', 'with_front' => " . $cpt_post_type['rewrite_withfront'] . "),\n";
	} else {
		if( empty( $cpt_post_type['rewrite_withfront'] ) ) {
			$cpt_post_type['rewrite_withfront'] = 1;
		}
		$custom_post_type .= "'rewrite' => array('slug' => '" . $cpt_post_type["name"] . "', 'with_front' => " . disp_boolean( $cpt_post_type['rewrite_withfront'] ) . "),\n";
	}

	$custom_post_type .= "'query_var' => " . disp_boolean($cpt_post_type["query_var"]) . ",\n";

	if ( !empty( $cpt_post_type["has_archive"] ) ) {
		$custom_post_type .= "'has_archive' => " . disp_boolean( $cpt_post_type["has_archive"] ) . ",\n";
	}

	if ( !empty( $cpt_post_type["exclude_from_search"] ) ) {
		$custom_post_type .= "'exclude_from_search' => " . disp_boolean( $cpt_post_type["exclude_from_search"] ) . ",\n";
	}

	if ( !empty( $cpt_post_type["menu_position"] ) ) {
		$custom_post_type .= "'menu_position' => '" . $cpt_post_type["menu_position"] . "',\n";
	}

	if ( !empty( $cpt_post_type["menu_icon"] ) ) {
		$custom_post_type .= "'menu_icon' => '" . $cpt_post_type["menu_icon"] . "',\n";
	}

	$custom_post_type .= "'supports' => array(" . $cpt_support_array . "),\n";

	if ( !empty( $cpt_tax_array ) ) {
		$custom_post_type .= "'taxonomies' => array(" . $cpt_tax_array . "),\n";
	}

	if ( !empty( $cpt_labels ) ) {
		$custom_post_type .= "'labels' => " . var_export( $cpt_labels, true ) . "\n";
	}

	$custom_post_type .= ") ); }";

}

/**
 * Construct a dropdown of our post types so users can select which to edit.
 *
 * @since  0.9
 *
 * @param  array   $post_types array of post types that are registered
 *
 * @return mixed              html select dropdown.
 */
function cpt_post_types_dropdown( $post_types = array() ) {

	//instantiate our class
	$ui = new cptui_admin_ui();

	if ( !empty( $post_types ) ) {
		$select = array();
		$select['options'] = array();

		//Default empty.
		$select['options'][] = array( 'attr' => '', 'text' => '' );

		foreach( $post_types as $type ) {
			$select['options'][] = array( 'attr' => $type['name'], 'text' => $type['label'] );
		}

		//Grab our current selected post type
		$current = cpt_get_current_post_type();

		$select['selected'] = ( !empty( $current ) ) ? $current : '';
		echo $ui->get_select_input( array(
			'namearray'     => 'cptui_selected_post_type',
			'name'          => 'post_type',
			'selections'    => $select
		) );
	}
}

/**
 * Get the selected post type from the $_POST global.
 *
 * @since  0.9
 *
 * @return mixed  false on no result, sanitized post type if set.
 */
function cpt_get_current_post_type() {
	if ( !empty( $_POST ) && isset( $_POST['cptui_selected_post_type']['post_type'] ) ) {
		return sanitize_text_field( $_POST['cptui_selected_post_type']['post_type'] );
	}

	return false;
}
