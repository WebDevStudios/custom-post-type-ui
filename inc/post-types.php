<?php
/**
 * Add our cptui.js file, with dependencies on jQuery and jQuery UI
 *
 * @since  1.9
 *
 * @return mixed  js scripts
 */
function cpt_post_type_enqueue_scripts() {
	wp_enqueue_script( 'cptui', plugins_url( 'js/cptui.js' , dirname(__FILE__) ) . '', array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion' ), '1.9', true );
}
add_action( 'admin_enqueue_scripts', 'cpt_post_type_enqueue_scripts' );

/**
 * Add our settings page to the menu.
 *
 * @since  1.9
 *
 * @return mixed  new menu
 */
function cpt_post_types_admin_menu() {
	add_submenu_page( 'cpt_main_menu', __( 'Add/Edit Post Types', 'cpt-plugin' ), __( 'Add/Edit Post Types', 'cpt-plugin' ), 'manage_options', 'cptui_manage_post_types', 'cptui_manage_post_types' );
}
add_action( 'admin_menu', 'cpt_post_types_admin_menu' );

function cptui_manage_post_types() {
	cpt_settings_tab_menu();
}
