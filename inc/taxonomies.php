<?php
/**
 * Add our cptui.js file, with dependencies on jQuery and jQuery UI
 *
 * @since  1.9
 *
 * @return mixed  js scripts
 */
function cpt_taxonomies_enqueue_scripts() {
	wp_enqueue_script( 'cptui', plugins_url( 'js/cptui.js' , dirname(__FILE__) ) . '', array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion' ), '1.9', true );
}
add_action( 'admin_enqueue_scripts', 'cpt_taxonomies_enqueue_scripts' );

add_action( 'admin_menu', 'taxonomies_admin_menu' );
function taxonomies_admin_menu() {
	add_submenu_page( 'cpt_main_menu', __( 'Add/Edit Taxonomies', 'cpt-plugin' ), __( 'Add/Edit Taxonomies', 'cpt-plugin' ), 'manage_options', 'cptui_manage_taxonomies', 'cptui_manage_taxonomies' );
}

function cptui_manage_taxonomies() {
	cpt_settings_tab_menu( $page = 'taxonomies' );
}
