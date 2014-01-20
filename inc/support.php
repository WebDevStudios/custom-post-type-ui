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

		do_action( 'cptui_main_page_before_faq' ); ?>

		<h1><?php _e( 'Custom Post Type UI Support', 'cpt-plugin' ); ?></h1>

		<p><?php _e( 'Click on a question below to see the answer. Click again if you wish to re-hide', 'cpt-plugin' ); ?></p>
		<p><?php _e( 'Please note that this plugin will NOT handle display of registered post types or taxonomies in your current theme. It will simply register them for you.', 'cpt-plugin' ); ?></p>

		<table class="form-table cptui-table">
			<tr>
				<td>
					<h2><?php _e( 'General', 'cpt-plugin' ); ?></h2>
					<ol id="questions">
						<li>
							<span class="question"><?php _e( 'I changed my custom post type name and now I can not get to my posts. How do I get them back?', 'cpt-plugin' ); ?></span>
							<div class="answer"><?php _e( 'You can either change the custom post type name back to the original name or try the Post Type Switcher plugin', 'cpt-plugin' ); ?> <a href="http://wordpress.org/plugins/post-type-switcher/" target="_blank">http://wordpress.org/extend/plugins/post-type-switcher/</a></div>
						</li>
						<li>
							<span class="question"><?php _e( 'I have added post thumbnail and/or post format support to my post type, but those do not appear when adding a post type post.', 'cpt-plugin' ); ?></span>
							<div class="answer">http://codex.wordpress.org/Function_Reference/add_theme_support</div>
						</li>
					</ol>
				</td>
				<td>
					<h2><?php _e( 'Frontend Display', 'cpt-plugin' ); ?></h2>
					<ol id="questions">
						<li>
							<span class="question"><?php _e( 'How do I add my custom post type to my frontpage?', 'cpt-plugin' ); ?></span>
							<div class="answer"></div>
						</li>
						<li>
							<span class="question"><?php _e( 'What template files should I edit to alter my post type display?', 'cpt-plugin' ); ?></span>
							<div class="answer">http://codex.wordpress.org/Template_Hierarchy</div>
						</li>
						<li>
							<span class="question"><?php _e( 'How do I display my custom post type on my site?', 'cpt-plugin' ); ?></span>
							<div class="answer">http://codex.wordpress.org/Class_Reference/WP_Query</div>
						</li>
						<li>
							<span class="question"><?php _e( 'I have added categories and tags to my custom post type, but they do not appear in the archives.', 'cpt-plugin' ); ?></span>
							<div class="answer">http://wpmu.org/add-custom-post-types-to-tags-and-categories-in-wordpress/</div>
						</li>
					</ol>
				</td>
			</tr>
			<tr>
				<td>
					<h2><?php _e( 'Advanced', 'cpt-plugin' ); ?></h2>
					<ol id="questions">
						<li>
							<span class="question"><?php _e( 'How do I add custom metaboxes to my post type?', 'cpt-plugin' ); ?></span>
							<div class="answer"><?php _e( 'Custom Metaboxes and Fields for WordPress class is a great alternative to a plugin for more advanced users.', 'cpt-plugin' ); ?> https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress</div>
						</li>
						<li>
							<span class="question"><?php _e( 'How do I add a newly registered taxonomy to a post type that already exists?', 'cpt-plugin' ); ?></span>
							<div class="answer">http://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type</div>
						</li>
						<li>
							<span class="question"><?php _e( 'Post relationships?', 'cpt-plugin' ); ?></span>
							<div class="answer">http://pippinsplugins.com/introduction-posts-2-posts-plugin/</div>
						</li>
						<li>
							<span class="question"><?php _e( 'Custom rewrite rules. What they all mean and what you can/cannot do', 'cpt-plugin' ); ?></span>
							<div class="answer"></div>
						</li>
						<li>
							<span class="question"><?php _e( 'How do I filter the "enter title here" text in the post editor screen.', 'cpt-plugin' ); ?></span>
							<div class="answer"><?php _e( 'Change text inside the post/page editor title field. Should be able to adapt as necessary.', 'cpt-plugin' ); ?></p>
<pre><code>function my_custom_title_text( $title ){
	global $post;
	if ( 'ENTER POST TYPE SLUG HERE' == $post->post_type )
		return 'NEW CUSTOM TEXT HERE';
	}
}
add_filter( 'enter_title_here', 'my_custom_title_text' );
</code></pre></div>
						</li>
					</ol>
				</td>
				<td>
					&nbsp;
				<!--Next group of questions here-->
				</td>
			</tr>
		</table>

		<?php do_action( 'cptui_main_page_after_faq' );

	echo '</div>';

	cptui_footer();
}
