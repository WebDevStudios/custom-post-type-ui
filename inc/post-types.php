<?php
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
	add_submenu_page( 'cpt_main_menu', __( 'Add/Edit Post Types', 'cpt-plugin' ), __( 'Add/Edit Post Types', 'cpt-plugin' ), 'manage_options', 'cptui_manage_post_types', 'cptui_manage_post_types' );
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

	if ( $success = cptui_get_post_type_successes() ) {
		echo $success;
	}
	if ( $errors = cptui_get_post_type_errors() ) {
		echo $errors;
	}

	cpt_settings_tab_menu();
	$ui = new cptui_admin_ui();

	$ui->get_p( __('If you are unfamiliar with the options below only fill out the <strong>Post Type Name</strong> and <strong>Label</strong> fields and check which meta boxes to support.  The other settings are set to the most common defaults for custom post types. Hover over the question mark for more details.', 'cpt-plugin') ); ?>

	<form method="post" <?php echo $RETURN_URL; ?>>
		<?php
			if ( function_exists( 'wp_nonce_field' ) )
				wp_nonce_field( 'cpt_add_custom_post_type' );
			?>
			<?php if ( isset( $_GET['edittype'] ) ) { ?>
				<input type="hidden" name="cpt_edit" value="<?php echo esc_attr( $editType ); ?>" />
			<?php } ?>

			<table>
				<?php
				/*
				 * Post Slug
				 */
				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_custom_post_type',
					'name'          => 'name',
					'textvalue'     => ( isset( $cpt_post_type_name ) ) ? esc_attr( $cpt_post_type_name ) : '',
					'maxlength'     => '20',
					'onblur'        => 'this.value=this.value.toLowerCase()',
					'labeltext'     => __( 'Post Type Name', 'cpt-plugin' ),
					'aftertext'     => __( '(e.g. movie)', 'cpt-plugin' ),
					'helptext'      => esc_attr__( 'The post type name.  Used to retrieve custom post type content.  Should be short and sweet', 'cpt-plugin'),
					'required'      => true
					) );

				// TODO: add in the special note regarding max length etc.
				echo $ui->get_p( __( 'Max 20 characters, can not contain capital letters or spaces. Reserved post types: post, page, attachment, revision, nav_menu_item.', 'cpt-plugin' ) );

				/*
				 * Post Label
				 */
				echo $ui->get_text_input( array(
					'namearray'     => 'cpt_custom_post_type',
					'name'          => 'label',
					'textvalue'     => ( isset( $cpt_label ) ) ? esc_attr( $cpt_label ) : '',
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
					'textvalue'     => ( isset( $cpt_singular_label ) ) ? esc_attr( $cpt_singular_label ) : '',
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
					'textvalue' => ( isset( $cpt_description ) ) ?  esc_textarea( $cpt_description ) : '',
					'labeltext' => __('Description', 'cpt-plugin'),
					'helptext' => esc_attr__( 'Custom Post Type Description. Describe what your custom post type is used for.', 'cpt-plugin' )
					) );
				?>
			</table>

			<p class="submit">
			<input type="submit" class="button-primary" name="cpt_submit" value="<?php echo $cpt_submit_name; ?>" />
			</p>

		</form>
	<?php echo $ui->get_p( __('Below are the advanced label options for custom post types.  If you are unfamiliar with these labels, leave them blank and the plugin will automatically create labels based off of your custom post type name', 'cpt-plugin') ); ?>
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
					'textvalue'     => ( isset( $cpt_labels["menu_name"] ) ) ? esc_attr( $cpt_labels["menu_name"] ) : '',
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
					'textvalue'     => ( isset( $cpt_labels["add_new"] ) ) ? esc_attr( $cpt_labels["add_new"] ) : '',
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
					'textvalue'     => ( isset( $cpt_labels["add_new_item"] ) ) ? esc_attr( $cpt_labels["add_new_item"] ) : '',
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
					'textvalue'     => ( isset( $cpt_labels["edit"] ) ) ? esc_attr( $cpt_labels["edit"] ) : '',
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
					'textvalue'     => ( isset( $cpt_labels["edit_item"] ) ) ? esc_attr( $cpt_labels["edit_item"] ) : '',
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
					'textvalue'     => ( isset( $cpt_labels["new_item"] ) ) ? esc_attr( $cpt_labels["new_item"] ) : '',
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
					'textvalue'     => ( isset( $cpt_labels["view"] ) ) ? esc_attr( $cpt_labels["view"] ) : '',
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
					'textvalue'     => ( isset( $cpt_labels["view_item"] ) ) ? esc_attr( $cpt_labels["view_item"] ) : '',
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
					'textvalue'     => ( isset( $cpt_labels["search_items"] ) ) ? esc_attr( $cpt_labels["search_items"] ) : '',
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
					'textvalue'     => ( isset( $cpt_labels["not_found"] ) ) ? esc_attr( $cpt_labels["not_found"] ) : '',
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
					'textvalue'     => ( isset( $cpt_labels["not_found_in_trash"] ) ) ? esc_attr( $cpt_labels["not_found_in_trash"] ) : '',
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
					'textvalue'     => ( isset( $cpt_labels["parent"] ) ) ? esc_attr( $cpt_labels["parent"] ) : '',
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
					$select['selected'] = ( isset( $cpt_public ) ) ? $cpt_public : '';
					echo $ui->get_select_bool_input( array(
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
					$select['selected'] = ( isset( $cpt_showui ) ) ? $cpt_showui : '';
					echo $ui->get_select_bool_input( array(
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
					$select['selected'] = ( isset( $cpt_showui ) ) ? $cpt_showui : '';
					echo $ui->get_select_bool_input( array(
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
					$select['selected'] = ( isset( $cpt_showui ) ) ? $cpt_showui : '';
					echo $ui->get_select_bool_input( array(
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
						'textvalue'     => ( isset( $cpt_capability ) ) ? esc_attr( $cpt_capability ) : 'post',
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
					$select['selected'] = ( isset( $cpt_showui ) ) ? $cpt_showui : '';
					echo $ui->get_select_bool_input( array(
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
					$select['selected'] = ( isset( $cpt_showui ) ) ? $cpt_showui : '';
					echo $ui->get_select_bool_input( array(
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
						'textvalue'     => ( isset( $cpt_rewrite_slug ) ) ? esc_attr( $cpt_rewrite_slug ) : '',
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
					$select['selected'] = ( isset( $cpt_showui ) ) ? $cpt_showui : '';
					echo $ui->get_select_bool_input( array(
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
					$select['selected'] = ( isset( $cpt_showui ) ) ? $cpt_showui : '';
					echo $ui->get_select_bool_input( array(
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
						'textvalue'     => ( isset( $cpt_menu_position ) ) ? esc_attr( $cpt_menu_position ) : '',
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
					$select['selected'] = ( isset( $cpt_showui ) ) ? $cpt_showui : '';
					echo $ui->get_select_bool_input( array(
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
						'textvalue'     => ( isset( $cpt_show_in_menu_string ) ) ? esc_attr( $cpt_show_in_menu_string ) : '',
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
						'textvalue'     => ( isset( $cpt_menu_icon ) ) ? esc_attr( $cpt_menu_icon ) : '',
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
						'checked'       => ( !empty( $cpt_supports ) && is_array( $cpt_supports ) ) ? in_array( 'title', $cpt_supports ) : false,
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
						'checked'       => ( !empty( $cpt_supports ) && is_array( $cpt_supports ) ) ? in_array( 'editor', $cpt_supports ) : false,
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
						'checked'       => ( !empty( $cpt_supports ) && is_array( $cpt_supports ) ) ? in_array( 'excerpt', $cpt_supports ) : false,
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
						'checked'       => ( !empty( $cpt_supports ) && is_array( $cpt_supports ) ) ? in_array( 'trackbacks', $cpt_supports ) : false,
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
						'checked'       => ( !empty( $cpt_supports ) && is_array( $cpt_supports ) ) ? in_array( 'custom-fields', $cpt_supports ) : false,
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
						'checked'       => ( !empty( $cpt_supports ) && is_array( $cpt_supports ) ) ? in_array( 'comments', $cpt_supports ) : false,
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
						'checked'       => ( !empty( $cpt_supports ) && is_array( $cpt_supports ) ) ? in_array( 'revisions', $cpt_supports ) : false,
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
						'checked'       => ( !empty( $cpt_supports ) && is_array( $cpt_supports ) ) ? in_array( 'thumbnail', $cpt_supports ) : false,
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
						'checked'       => ( !empty( $cpt_supports ) && is_array( $cpt_supports ) ) ? in_array( 'author', $cpt_supports ) : false,
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
						'checked'       => ( !empty( $cpt_supports ) && is_array( $cpt_supports ) ) ? in_array( 'page-attributes', $cpt_supports ) : false,
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
						'checked'       => ( !empty( $cpt_supports ) && is_array( $cpt_supports ) ) ? in_array( 'post-formats', $cpt_supports ) : false,
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
						'checked'           => ( !empty( $cpt_taxes ) && is_array( $cpt_taxes ) ) ? in_array( $add_tax->name, $cpt_taxes ) : null,
						'name'              => $add_tax->name,
						'namearray'         => 'cpt_addon_taxes',
						'textvalue'         => $add_tax->name,
						'labeltext'         => $add_tax->label,
						'helptext'          => sprintf( esc_attr__( 'Adds %s support', 'cpt-plugin' ), $add_tax->name ),
						'wrap'              => false
					) );
				}

				echo $ui->get_td_end() . $ui->get_tr_end();
				?>

			</table>
		</div>
	</div>
<?php
	cpt_footer();
}
