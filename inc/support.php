<?php

add_action( 'admin_menu', 'support_admin_menu' );
function support_admin_menu() {
	add_submenu_page( 'cpt_main_menu', __( 'Help/Support', 'cpt-plugin' ), __( 'Help/Support', 'cpt-plugin' ), 'manage_options', 'cptui_support', 'cptui_support' );
}

function cptui_support() {
	echo '<div class="wrap">';
	?>
		Template Hierarchy
			http://codex.wordpress.org/Template_Hierarchy
		WP_Query
			http://codex.wordpress.org/Class_Reference/WP_Query
		CPTs in Cat/Tag archives
			http://wpmu.org/add-custom-post-types-to-tags-and-categories-in-wordpress/
		add_theme_support()
			post-formats
			post-thumbnails
			http://codex.wordpress.org/Function_Reference/add_theme_support

	<?php
	echo '</div>';

	cpt_footer();
}
