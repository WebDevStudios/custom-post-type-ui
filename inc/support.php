<?php

add_action( 'admin_menu', 'support_admin_menu' );
function support_admin_menu() {
	add_submenu_page( 'cpt_main_menu', __( 'Help/Support', 'cpt-plugin' ), __( 'Help/Support', 'cpt-plugin' ), 'manage_options', 'cptui_support', 'cptui_support' );
}

function cptui_support() {
	echo '<div class="wrap">';
	?>
		<?php do_action( 'cptui_main_page_before_faq' ); ?>
		<h1>Frequently Asked Questions</h1>
			Please note that this plugin will NOT handle display of registered post types or taxonomies in your current theme. It will simply register them for you.

			How can I display content from a custom post type on my website?
			Justin Tadlock has written some great posts on the topic
				<a href="http://justintadlock.com/archives/2010/02/02/showing-custom-post-types-on-your-home-blog-page" target="_blank">Showing Custom Post Types on your Home Page</a>
				<a href="http://justintadlock.com/archives/2010/04/29/custom-post-types-in-wordpress" target="_blank">Custom Post Types in WordPress</a>

			How can I add custom meta boxes to my custom post types?
			The More Fields plugin does a great job at creating custom meta boxes and fully supports custom post types
				<a href="http://wordpress.org/extend/plugins/more-fields/" target="_blank">http://wordpress.org/extend/plugins/more-fields/</a>.  The Custom Metaboxes and Fields for WordPress class is a great alternative to a plugin for more advanced users.

			I changed my custom post type name and now I can't get to my posts
			You can either change the custom post type name back to the original name or try the Post Type Switcher plugin
				<a href="http://wordpress.org/extend/plugins/post-type-switcher/" target="_blank">http://wordpress.org/extend/plugins/post-type-switcher/</a>


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


		<?php do_action( 'cptui_main_page_after_faq' ); ?>
	<?php
	echo '</div>';

	cpt_footer();
}
