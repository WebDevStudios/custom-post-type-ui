<?php

add_action( 'admin_menu', 'support_admin_menu' );
function support_admin_menu() {
	add_submenu_page( 'cpt_main_menu', __( 'Help/Support', 'cpt-plugin' ), __( 'Help/Support', 'cpt-plugin' ), 'manage_options', 'cptui_support', 'cptui_support' );
}

function cptui_support() {
	echo '<div class="wrap">';
		echo '<h1>Testing</h1>';
	echo '</div>';

	cpt_footer();
}
