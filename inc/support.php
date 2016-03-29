<?php
/**
 * Custom Post Type UI Support Questions.
 *
 * @package CPTUI
 * @subpackage Support
 * @author WebDevStudios
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add our cptui.js file, with dependencies on jQuery.
 *
 * @since 1.0.0
 *
 * @internal
 */
function cptui_support_enqueue_scripts() {

	$current_screen = get_current_screen();

	if ( ! is_object( $current_screen ) || 'cpt-ui_page_cptui_support' !== $current_screen->base ) {
		return;
	}

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	wp_enqueue_script( 'cptui' );
}
add_action( 'admin_enqueue_scripts', 'cptui_support_enqueue_scripts' );

/**
 * Create our settings page output.
 *
 * @since 1.0.0
 *
 * @internal
 */
function cptui_support() {
	echo '<div class="wrap">';

		/**
		 * Fires at the top of the FAQ/Support page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'cptui_main_page_before_faq' ); ?>

		<h1><?php _e( 'Custom Post Type UI Support', 'custom-post-type-ui' ); ?></h1>

		<p><?php printf(
			__( 'Please note that this plugin will NOT handle display of registered post types or taxonomies in your current theme. It will simply register them for you. If all else fails, visit us on the %s', 'custom-post-type-ui' ),
			'<a href="https://www.wordpress.org/support/plugin/custom-post-type-ui/">' . __( 'Support Forums', 'custom-post-type-ui' ) . '</a>'
		); ?></p>

		<table id="support" class="form-table cptui-table">
			<tr>
				<td class="outter">
					<h2><?php _e( 'General', 'custom-post-type-ui' ); ?></h2>
					<ol id="questions_general">
						<li>
							<span tabindex="0" class="question" aria-controls="q1" aria-expanded="false"><?php _e( 'I have post types with spaces in their slug and can not successfully delete them. How can I fix that?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q1"><?php _e( 'Visit the Import/Export page and copy the export code into the import side on the left. Replace the space with an underscore and then click "Import". You should be able to delete the individual post types afterwards.', 'custom-post-type-ui' ); ?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q2" aria-expanded="false"><?php _e( 'I changed my custom post type name and now I can not get to my posts. How do I get them back?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q2"><?php _e( 'You can either change the custom post type name back to the original name or try the Post Type Switcher plugin', 'custom-post-type-ui' ); ?>
								<a href="https://wordpress.org/plugins/post-type-switcher/" target="_blank">https://wordpress.org/extend/plugins/post-type-switcher/</a>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q3" aria-expanded="false"><?php _e( 'I changed my custom post type or taxonomy slug and now I have duplicates shown. How do I remove the duplicate?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q3"><?php _e( 'Renaming the slug for a post type or taxonomy creates a new entry in our saved option which gets registered as its own post type or taxonomy. Since the settings will be mirrored from the previous slug, you will just need to delete the previous version\'s entry.', 'custom-post-type-ui' ); ?> <a href="https://wordpress.org/plugins/post-type-switcher/" target="_blank">https://wordpress.org/extend/plugins/post-type-switcher/</a></div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q4" aria-expanded="false"><?php _e( 'I have added post thumbnail and/or post format support to my post type, but those do not appear when adding a post type post.', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q4"><?php _e( 'Make sure your theme has post "post-thumbnails" theme support enabled.', 'custom-post-type-ui' ); ?> <a href="https://codex.wordpress.org/Function_Reference/add_theme_support" target="_blank">https://codex.wordpress.org/Function_Reference/add_theme_support</a></div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q5" aria-expanded="false"><?php _e( 'Do you have any recommendations for an alternative to Visual Composer?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q5"><?php _e( 'We recommend using VelocityPage.', 'custom-post-type-ui' ); ?>
								<a href="https://velocitypage.com" target="_blank">https://velocitypage.com</a>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q6" aria-expanded="false"><?php _e( 'Is there any way to get CPTUI-registered post types working with Visual Composer Media Grid?', 'custom-post-type-ui' ); ?></span>

							<div class="answer" id="q6"><?php _e( 'Please see the solution from the following forum support thread.', 'custom-post-type-ui' ); ?>
								<a href="https://wordpress.org/support/topic/custom-post-type-and-visual-composer-grid-block?replies=11#post-7111458" target="_blank">https://wordpress.org/support/topic/custom-post-type-and-visual-composer-grid-block?replies=11#post-7111458</a>
							</div>
						</li>
					</ol>
				</td>
				<td class="outter">
					<h2><?php _e( 'Front-end Display', 'custom-post-type-ui' ); ?></h2>
					<ol id="questions_front">
						<li>
							<span tabindex="0" class="question" aria-controls="q7" aria-expanded="false"><?php _e( 'What template files should I edit to alter my post type display?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q7"><?php printf( __( 'Please visit the %sTemplate Hierarchy%s page on the WordPress codex for details about available templates.', 'custom-post-type-ui' ),
							'<a href="https://codex.wordpress.org/Template_Hierarchy" target="_blank">',
							'</a>'
							); ?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q8" aria-expanded="false"><?php _e( 'How do I display my custom post type on my site?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q8"><?php printf( __( 'You will need to utilize the %sWP_Query%s class to handle display in custom locations. If you have set the post type to have archives, the archive url should be something like "http://www.mysite.com/post-type-slug"', 'custom-post-type-ui' ),
							'<a href="https://codex.wordpress.org/Class_Reference/WP_Query" target="_blank">',
							'</a>'
							); ?></div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q9" aria-expanded="false"><?php _e( 'I have added categories and tags to my custom post type, but they do not appear in the archives.', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q9"><?php printf( __( 'You will need to add your newly created post type to the types that the category and tag archives query for. You can see a tutorial on how to do that at %s', 'custom-post-type-ui' ),
								'<a href="https://wpmu.org/add-custom-post-types-to-tags-and-categories-in-wordpress/" target="_blank">https://wpmu.org/add-custom-post-types-to-tags-and-categories-in-wordpress/</a>'
							); ?> </div>
						</li>
					</ol>
				</td>
			</tr>
			<tr>
				<td class="outter">
					<h2><?php _e( 'Advanced', 'custom-post-type-ui' ); ?></h2>
					<ol id="questions_advanced">
						<li>
							<span tabindex="0" class="question" aria-controls="q10" aria-expanded="false"><?php _e( 'How do I add custom metaboxes to my post type?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q10"><?php printf(
									__( 'We recommend checking out %s, the latest iteration of "Custom Metaboxes and Fields for WordPress". Both are maintained by WebDevStudios.', 'custom-post-type-ui' ),
								'<a href="https://wordpress.org/plugins/cmb2/" target="_blank">CMB2</a>'
							); ?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q11" aria-expanded="false"><?php _e( 'How do I add a newly registered taxonomy to a post type that already exists?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q11"><?php printf(
								__( 'Check out the %s function for documentation and usage examples.', 'custom-post-type-ui' ),
								'<a href="https://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type" target="_blank">register_taxonomy_for_object_type()</a>'
								); ?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q12" aria-expanded="false"><?php _e( 'Post relationships?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q12"><?php printf( __( '%s has an excellent %spost%s introducing users to the %sPosts 2 Posts%s plugin that should be a good start.', 'custom-post-type-ui' ),
							'Pippin Williamson',
							'<a href="https://pippinsplugins.com/introduction-posts-2-posts-plugin/" target="_blank">',
							'</a>',
							'<a href="https://wordpress.org/plugins/posts-to-posts/" target="_blank">',
							'</a>'
							); ?></div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q13" aria-expanded="false"><?php _e( 'Is there any function reference list?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q13"><?php printf( __( '%s has compiled a nice list of functions used by our plugin. Note not all will be useful as they are attached to hooks.', 'custom-post-type-ui' ),
							'<a href="http://hookr.io/plugins/custom-post-type-ui/1.0.5/all/#index=c" target="_blank">Hookr.io</a>' ); ?></div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q14" aria-expanded="false"><?php _e( 'How do I filter the "enter title here" text in the post editor screen?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q14"><p><?php _e( 'Change text inside the post/page editor title field. Should be able to adapt as necessary.', 'custom-post-type-ui' ); ?></p>
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
				<td class="outter">
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
