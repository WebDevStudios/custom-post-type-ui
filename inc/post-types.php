<?php
add_submenu_page( 'cpt_main_menu', __( 'Add/Edit Post Types', 'cpt-plugin' ), __( 'Add/Edit Post Types', 'cpt-plugin' ), 'manage_options', 'cptui_manage_post_types', 'cptui_manage_post_types' );

function cptui_manage_post_types() {
	cpt_settings_tab_menu();
}
