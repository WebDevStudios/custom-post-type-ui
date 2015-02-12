<?php
/* This file controls everything about our support section. */

# Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add our cptui.js file, with dependencies on jQuery.
 *
 * @since 1.0.0
 */
function cptui_support_enqueue_scripts() {
	wp_enqueue_script( 'cptui', plugins_url( 'js/cptui.js' , dirname(__FILE__) ) . '', array( 'jquery' ), '0.9', true );
}
add_action( 'admin_enqueue_scripts', 'cptui_support_enqueue_scripts' );

/**
 * Add our settings page to the menu.
 *
 * @since 1.0.0
 */
function cptui_support_admin_menu() {
	add_submenu_page( 'cptui_main_menu', __( 'Help/Support', 'cpt-plugin' ), __( 'Help/Support', 'cpt-plugin' ), 'manage_options', 'cptui_support', 'cptui_support' );
}
add_action( 'admin_menu', 'cptui_support_admin_menu' );

/**
 * Create our settings page output.
 *
 * @since 1.0.0
 *
 * @return string HTML output for the page.
 */
function cptui_support() {
	echo '<div class="wrap">';

		/**
		 * Fires at the top of the FAQ/Support page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'cptui_main_page_before_faq' ); ?>

		<h1><?php _e( 'Custom Post Type UI Support', 'cpt-plugin' ); ?></h1>

		<p><?php printf( __( 'Please note that this plugin will NOT handle display of registered post types or taxonomies in your current theme. It will simply register them for you. If all else fails, visit us on the %s', 'cpt-plugin' ),
				'<a href="http://www.wordpress.org/support/plugin/custom-post-type-ui/">' . __( 'Support Forums', 'cpt-plugin' ) . '</a>'
			); ?></p>

		<table id="support" class="form-table cptui-table">
			<tr>
				<td>
					<h2><?php _e( 'General', 'cpt-plugin' ); ?></h2>
					<ol id="questions_general">
						<li>
							<span class="question"><?php _e( 'I changed my custom post type name and now I can not get to my posts. How do I get them back?', 'cpt-plugin' ); ?></span>
							<div class="answer"><?php _e( 'You can either change the custom post type name back to the original name or try the Post Type Switcher plugin', 'cpt-plugin' ); ?>
								<a href="http://wordpress.org/plugins/post-type-switcher/" target="_blank">http://wordpress.org/extend/plugins/post-type-switcher/</a>
							</div>
						</li>
						<li>
							<span class="question"><?php _e( 'I changed my custom post type or taxonomy slug and now I have duplicates shown. How do I remove the duplicate?', 'cpt-plugin' ); ?></span>
							<div class="answer"><?php _e( 'Renaming the slug for a post type or taxonomy creates a new entry in our saved option which gets registered as its own post type or taxonomy. Since the settings will be mirrored from the previous slug, you will just need to delete the previous version\'s entry.', 'cpt-plugin' ); ?> <a href="http://wordpress.org/plugins/post-type-switcher/" target="_blank">http://wordpress.org/extend/plugins/post-type-switcher/</a></div>
						</li>
						<li>
							<span class="question"><?php _e( 'I have added post thumbnail and/or post format support to my post type, but those do not appear when adding a post type post.', 'cpt-plugin' ); ?></span>
							<div class="answer"><?php _e( 'Make sure your theme has post "post-thumbnails" theme support enabled.', 'cpt-plugin' ); ?> <a href="http://codex.wordpress.org/Function_Reference/add_theme_support" target="_blank">http://codex.wordpress.org/Function_Reference/add_theme_support</a></div>
						</li>
					</ol>
				</td>
				<td>
					<h2><?php _e( 'Front-end Display', 'cpt-plugin' ); ?></h2>
					<ol id="questions_front">
						<li>
							<span class="question"><?php _e( 'What template files should I edit to alter my post type display?', 'cpt-plugin' ); ?></span>
							<div class="answer"><?php printf( __( 'Please visit the %sTemplate Hierarchy%s page on the WordPress codex for details about available templates.', 'cpt-plugin' ),
							'<a href="http://codex.wordpress.org/Template_Hierarchy" target="_blank">',
							'</a>'
							); ?>
							</div>
						</li>
						<li>
							<span class="question"><?php _e( 'How do I display my custom post type on my site?', 'cpt-plugin' ); ?></span>
							<div class="answer"><?php printf( __( 'You will need to utilize the %sWP_Query%s class to handle display in custom locations. If you have set the post type to have archives, the archive url should be something like "http://www.mysite.com/post-type-slug"', 'cpt-plugin' ),
							'<a href="http://codex.wordpress.org/Class_Reference/WP_Query" target="_blank">',
							'</a>'
							); ?></div>
						</li>
						<li>
							<span class="question"><?php _e( 'I have added categories and tags to my custom post type, but they do not appear in the archives.', 'cpt-plugin' ); ?></span>
							<div class="answer"><?php printf( __( 'You will need to add your newly created post type to the types that the category and tag archives query for. You can see a tutorial on how to do that at %s', 'cpt-plugin' ),
								'<a href="http://wpmu.org/add-custom-post-types-to-tags-and-categories-in-wordpress/" target="_blank">http://wpmu.org/add-custom-post-types-to-tags-and-categories-in-wordpress/</a>'
							); ?> </div>
						</li>
					</ol>
				</td>
			</tr>
			<tr>
				<td>
					<h2><?php _e( 'Advanced', 'cpt-plugin' ); ?></h2>
					<ol id="questions_advanced">
						<li>
							<span class="question"><?php _e( 'How do I add custom metaboxes to my post type?', 'cpt-plugin' ); ?></span>
							<div class="answer"><?php printf(
									__( 'We recommend checking out %s, the latest iteration of "Custom Metaboxes and Fields for WordPress". Both are maintained by WebDevStudios.', 'cpt-plugin' ),
								'<a href="https://wordpress.org/plugins/cmb2/" target="_blank">CMB2</a>'
							); ?>
							</div>
						</li>
						<li>
							<span class="question"><?php _e( 'How do I add a newly registered taxonomy to a post type that already exists?', 'cpt-plugin' ); ?></span>
							<div class="answer"><?php printf(
								__( 'Check out the %s function for documentation and usage examples.', 'cpt-plugin' ),
								'<a href="http://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type" target="_blank">register_taxonomy_for_object_type()</a>'
								); ?>
							</div>
						</li>
						<li>
							<span class="question"><?php _e( 'Post relationships?', 'cpt-plugin' ); ?></span>
							<div class="answer"><?php printf( __( '%s has an excellent %spost%s introducing users to the %sPosts 2 Posts%s plugin that should be a good start.', 'cpt-plugin' ),
							'Pippin Williamson',
							'<a href="http://pippinsplugins.com/introduction-posts-2-posts-plugin/" target="_blank">',
							'</a>',
							'<a href="https://wordpress.org/plugins/posts-to-posts/" target="_blank">',
							'</a>'
							); ?></div>
						</li>
						<li>
							<span class="question"><?php _e( 'How do I filter the "enter title here" text in the post editor screen?', 'cpt-plugin' ); ?></span>
							<div class="answer"><p><?php _e( 'Change text inside the post/page editor title field. Should be able to adapt as necessary.', 'cpt-plugin' ); ?></p>
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
					<?php

					/**
					 * Fires in the last table cell of the FAQ list.
					 *
					 * @since 1.0.0
					 */
					do_action( 'cptui_main_page_custom_questions' );
					?>
				</td>
			</tr>
		</table>

		<?php

		/**
		 * Fires at the bottom of the FAQ/Support page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'cptui_main_page_after_faq' );

	echo '</div>';
}
