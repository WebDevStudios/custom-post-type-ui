<?php
/* This file controls everything about our support section */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add our cptui.js file, with dependencies on jQuery
 *
 * @since  0.9
 *
 * @return mixed  js scripts
 */
function cptui_support_enqueue_scripts() {
	wp_enqueue_script( 'cptui', plugins_url( 'js/cptui.js' , dirname(__FILE__) ) . '', array( 'jquery' ), '0.9', true );
}
add_action( 'admin_enqueue_scripts', 'cptui_support_enqueue_scripts' );

/**
 * Add our settings page to the menu.
 *
 * @since  0.9
 *
 * @return mixed  new menu
 */
function cptui_support_admin_menu() {
	add_submenu_page( 'cptui_main_menu', __( 'Help/Support', 'cpt-plugin' ), __( 'Help/Support', 'cpt-plugin' ), 'manage_options', 'cptui_support', 'cptui_support' );
}
add_action( 'admin_menu', 'cptui_support_admin_menu' );

function cptui_support() {
	echo '<div class="wrap">';
	?>
		<?php
		/* Ideas:
			Searching
			admin sorting
			admin filtering.
		 */
		?>
		<?php do_action( 'cptui_main_page_before_faq' ); ?>
		<h1>Custom Post Type UI Support</h1>
				Please note that this plugin will NOT handle display of registered post types or taxonomies in your current theme. It will simply register them for you.

			<div class="questions">
				<h2>General</h2>
				<ol>
					<li>I changed my custom post type name and now I can't get to my posts. How do I get them back?
					<li>I have added post thumbnail and/or post format support to my post type, but those don't appear when adding a post type post. http://codex.wordpress.org/Function_Reference/add_theme_support
				</ol>
				<h2>Frontend Display</h2>
				<ol>
					<li>How do I add my custom post type to my frontpage?
					<li>What template files should I edit to alter my post type display? http://codex.wordpress.org/Template_Hierarchy
					<li>How do I display my custom post type on my site? http://codex.wordpress.org/Class_Reference/WP_Query
					<li>I have added categories and tags to my custom post type, but they don't appear in the archives. http://wpmu.org/add-custom-post-types-to-tags-and-categories-in-wordpress/
				</ol>
				<h2>Advanced</h2>
				<ol>
					<li>How do I add custom metaboxes to my post type? Custom Metaboxes and Fields for WordPress class is a great alternative to a plugin for more advanced users. https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress
					<li>How do I add a newly registered taxonomy to a post type that already exists? http://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type
					<li>Post relationships http://pippinsplugins.com/introduction-posts-2-posts-plugin/
					<li>Custom rewrite rules. What they all mean and what you can/can't do
					<li>Filtering "enter title here" text
							//Change text inside the post/page editor title field. Should be able to adapt as necessary.
							function title_text_input( $title ){
								global $post;
								return (($post->post_type == 'page') ? 'Foo' : 'Bar');
							}
							add_filter( 'enter_title_here', 'title_text_input' );
				</ol>
			</div>
			<div class="answers">
				<ol>
					<li>You can either change the custom post type name back to the original name or try the Post Type Switcher plugin <a href="http://wordpress.org/extend/plugins/post-type-switcher/" target="_blank">http://wordpress.org/extend/plugins/post-type-switcher/</a>

				</ol>
			</div>
		<?php do_action( 'cptui_main_page_after_faq' ); ?>
	<?php
	echo '</div>';

	cptui_footer();
}
