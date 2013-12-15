<?php
add_submenu_page( 'cpt_main_menu', __( 'Add/Edit Taxonomies', 'cpt-plugin' ), __( 'Add/Edit Taxonomies', 'cpt-plugin' ), 'manage_options', 'cptui_manage_taxonomies', 'cptui_manage_taxonomies' );

function cptui_manage_taxonomies() {
	cpt_settings_tab_menu( $page = 'taxonomies' );
}
