<?php
/**
 * Custom Post Type UI Support Questions.
 *
 * @package CPTUI
 * @subpackage Support
 * @author WebDevStudios
 * @since 1.0.0
 * @license GPL-2.0+
 */

// phpcs:disable WebDevStudios.All.RequireAuthor

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

	if ( wp_doing_ajax() ) {
		return;
	}

	wp_enqueue_script( 'cptui' );
	wp_enqueue_style( 'cptui-css' );
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
	echo '<div class="wrap cptui-support">';

		/**
		 * Fires at the top of the FAQ/Support page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'cptui_main_page_before_faq' ); ?>

		<h1><?php esc_html_e( 'Custom Post Type UI Support', 'custom-post-type-ui' ); ?></h1>

		<p>
		<?php
		printf(
			/* translators: Placeholders are just for HTML markup that doesn't need translated */
			esc_html__( 'Please note that %1$s this plugin will not handle display %2$s of registered post types or taxonomies in your current theme. It simply registers them for you. To display your data, check out %3$s. %4$s to see some examples that are available with Custom Post Type UI Extended. If all else fails, visit us on the %5$s', 'custom-post-type-ui' ),
			'<strong>',
			'</strong>',
			'<a href="https://pluginize.com/plugins/custom-post-type-ui-extended/?utm_source=faq&utm_medium=text&utm_campaign=cptui">' . esc_html__( 'CPTUI Extended', 'custom-post-type-ui' ) . '</a>',
			'<a href="https://pluginize.com/cpt-ui-extended-features/?utm_source=faq-layouts&utm_medium=text&utm_campaign=cptui">' . esc_html__( 'View our Layouts page', 'custom-post-type-ui' ) . '</a>',
			'<a href="https://www.wordpress.org/support/plugin/custom-post-type-ui/">' . esc_html__( 'Support Forums', 'custom-post-type-ui' ) . '</a>'
		);
		?>
		</p>

		<table id="support" class="form-table cptui-table">
			<tr>
				<td class="outer">
					<h2><?php esc_html_e( 'Pluginize', 'custom-post-type-ui' ); ?></h2>
					<ol id="questions_pluginize">
						<li>
							<span tabindex="0" class="question" aria-controls="q1" aria-expanded="false"><?php esc_html_e( 'What is Pluginize?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q1"><?php esc_html_e( 'Pluginize is a marketplace of plugins by WebDevStudios.', 'custom-post-type-ui' ); ?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q2" aria-expanded="false"><?php esc_html_e( 'What does Pluginize offer?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q2"><?php esc_html_e( 'Pluginize offers both free and paid WordPress plugins.', 'custom-post-type-ui' ); ?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q3" aria-expanded="false"><?php esc_html_e( 'Will these ad spots ever show third-party data?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q3"><?php esc_html_e( 'No. These spots are intended for and will only be used for other available WebDevStudios products and services.', 'custom-post-type-ui' ); ?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q4" aria-expanded="false"><?php esc_html_e( 'How can I remove the ads that suddenly started showing up?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q4">
								<?php
								printf(
									/* translators: Placeholders are just for HTML markup that doesn't need translated */
									esc_html__( 'You can have them automatically removed from display via a purchased copy of %s.', 'custom-post-type-ui' ),
									sprintf(
										'<a href="%s">%s</a>',
										'https://pluginize.com/plugins/custom-post-type-ui-extended/?utm_source=faq-remove&utm_medium=text&utm_campaign=cptui',
										'Custom Post Type UI Extended'
									)
								);
								?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q5" aria-expanded="false"><?php esc_html_e( 'Are these ad spots tracking my personal information in any way?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q5"><?php esc_html_e( 'No, there is no data being sent out from your site with these. The only way anything is tracked is via UTM parameters for WebDevStudios\'s analytics so we can get an idea of where traffic is coming from. Those are only tracked if you actually click on an ad spot.', 'custom-post-type-ui' ); ?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q6" aria-expanded="false"><?php esc_html_e( 'Do I still need Custom Post Type UI if I purchase and install Custom Post Type UI Extended?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q6"><?php esc_html_e( 'Yes you will. The Custom Post Type UI Extended is not a replacement of the free version with added extra features. It acts based on the data made available through Custom Post Type UI', 'custom-post-type-ui' ); ?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q7" aria-expanded="false"><?php esc_html_e( 'Does Custom Post Type UI Extended require multisite?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q7"><?php esc_html_e( 'No it does not. The Shortcode builder is not multisite dependent and will work with either setup.', 'custom-post-type-ui' ); ?>
							</div>
						</li>
					</ol>
				</td>
				<td class="outer">
					<h2><?php esc_html_e( 'General', 'custom-post-type-ui' ); ?></h2>
					<ol id="questions_general">
						<li>
							<span tabindex="0" class="question" aria-controls="q8" aria-expanded="false"><?php esc_html_e( 'I have post types with spaces in their slug and can not successfully delete them. How can I fix that?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q8"><?php esc_html_e( 'Visit the Import/Export page and copy the export code into the import side on the left. Replace the space with an underscore and then click "Import". You should be able to delete the individual post types afterwards.', 'custom-post-type-ui' ); ?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q9" aria-expanded="false"><?php esc_html_e( 'I changed my custom post type name and now I can not get to my posts. How do I get them back?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q9"><?php esc_html_e( 'You can either change the custom post type name back to the original name or try the Post Type Switcher plugin', 'custom-post-type-ui' ); ?>
								<a href="https://wordpress.org/plugins/post-type-switcher/" target="_blank" rel="noopener">https://wordpress.org/extend/plugins/post-type-switcher/</a>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q10" aria-expanded="false"><?php esc_html_e( 'I changed my custom post type or taxonomy slug and now I have duplicates shown. How do I remove the duplicate?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q10"><?php esc_html_e( 'Renaming the slug for a post type or taxonomy creates a new entry in our saved option which gets registered as its own post type or taxonomy. Since the settings will be mirrored from the previous slug, you will just need to delete the previous version\'s entry.', 'custom-post-type-ui' ); ?> <a href="https://wordpress.org/plugins/post-type-switcher/" target="_blank" rel="noopener">https://wordpress.org/extend/plugins/post-type-switcher/</a></div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q11" aria-expanded="false"><?php esc_html_e( 'I have added post thumbnail and/or post format support to my post type, but those do not appear when adding a post type post.', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q11"><?php esc_html_e( 'Make sure your theme has post "post-thumbnails" theme support enabled.', 'custom-post-type-ui' ); ?> <a href="https://codex.wordpress.org/Function_Reference/add_theme_support" target="_blank" rel="noopener">https://codex.wordpress.org/Function_Reference/add_theme_support</a></div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q12" aria-expanded="false"><?php esc_html_e( 'Do you have any recommendations for an alternative to Visual Composer?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q12"><?php esc_html_e( 'We recommend using VelocityPage.', 'custom-post-type-ui' ); ?>
								<a href="https://velocitypage.com" target="_blank" rel="noopener">https://velocitypage.com</a>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q13" aria-expanded="false"><?php esc_html_e( 'Is there any way to get Custom Post Type UI-registered post types working with Visual Composer Media Grid?', 'custom-post-type-ui' ); ?></span>

							<div class="answer" id="q13"><?php esc_html_e( 'Please see the solution from the following forum support thread.', 'custom-post-type-ui' ); ?>
								<a href="https://wordpress.org/support/topic/custom-post-type-and-visual-composer-grid-block?replies=11#post-7111458" target="_blank" rel="noopener">https://wordpress.org/support/topic/custom-post-type-and-visual-composer-grid-block?replies=11#post-7111458</a>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q14" aria-expanded="false"><?php esc_html_e( 'Why can I not use dashes in post type or taxonomy slugs?', 'custom-post-type-ui' ); ?></span>

							<div class="answer" id="q14"><?php esc_html_e( 'Custom Post Type UI tries to make smart choices for our users, and forcing underscores is one of them. Please see the tutorial at the following URL for how to get dashes in your permalink urls while continuing to use underscores for the actual slug.', 'custom-post-type-ui' ); ?>
								<a href="http://docs.pluginize.com/article/135-dashes-in-post-type-taxonomy-slugs-for-url-seo" target="_blank" rel="noopener">http://docs.pluginize.com/article/135-dashes-in-post-type-taxonomy-slugs-for-url-seo</a>
							</div>
						</li>
					</ol>
				</td>
			</tr>
			<tr>
				<td class="outer">
					<h2><?php esc_html_e( 'Front-end Display', 'custom-post-type-ui' ); ?></h2>
					<ol id="questions_front">
						<li>
							<span tabindex="0" class="question" aria-controls="q15" aria-expanded="false"><?php esc_html_e( 'What template files should I edit to alter my post type display?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q15">
								<?php
								printf(
									/* translators: Placeholders are just for HTML markup that doesn't need translated */
									esc_html__( 'Please visit the %1$sTemplate Hierarchy%2$s page on the WordPress codex for details about available templates.', 'custom-post-type-ui' ),
									'<a href="https://codex.wordpress.org/Template_Hierarchy" target="_blank" rel="noopener">',
									'</a>'
								);
								?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q16" aria-expanded="false"><?php esc_html_e( 'How do I display my custom post type on my site?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q16">
								<?php
								printf(
									/* translators: Placeholders are just for HTML markup that doesn't need translated */
									esc_html__( 'We encourage checking out %1$s for easily displaying post type content wherever you can utilize a shortcode. If you prefer to handle on your own, you will need to utilize the %2$s class to handle display in custom locations. If you have set the post type to have archives, the archive url should be something like "http://www.mysite.com/post-type-slug"', 'custom-post-type-ui' ),
									sprintf(
										'<a href="%s">%s</a>',
										'https://pluginize.com/plugins/custom-post-type-ui-extended/?utm_source=faq&utm_medium=text&utm_campaign=cptui',
										'Custom Post Type UI Extended'
									),
									'<a href="https://codex.wordpress.org/Class_Reference/WP_Query" target="_blank" rel="noopener">WP_Query</a>'
								);
								?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q17" aria-expanded="false"><?php esc_html_e( 'I have added categories and tags to my custom post type, but they do not appear in the archives.', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q17">
								<?php
								printf(
									/* translators: Placeholders are just for HTML markup that doesn't need translated */
									esc_html__( 'You will need to add your newly created post type to the types that the category and tag archives query for. You can see a tutorial on how to do that at %s', 'custom-post-type-ui' ),
									'<a href="http://docs.pluginize.com/article/17-post-types-in-category-tag-archives" target="_blank" rel="noopener">http://docs.pluginize.com/article/17-post-types-in-category-tag-archives</a>'
								);
								?>
								</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q18" aria-expanded="false"><?php esc_html_e( 'How do I add custom post type support for custom templates selection like pages have?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q18">
								<?php
								printf(
									/* translators: Placeholders are just for HTML markup that doesn't need translated */
									esc_html__( 'Please visit the %1$sPost Type Templates in 4.7%2$s post on the Make WordPress Core blog for details about setting templates for multiple post types.', 'custom-post-type-ui' ),
									'<a href="https://make.wordpress.org/core/2016/11/03/post-type-templates-in-4-7/" target="_blank" rel="noopener">',
									'</a>'
								);
								?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q19" aria-expanded="false"><?php esc_html_e( 'Why are my post types not showing in taxonomy term archives?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q19">
								<?php
								esc_html_e( 'If you have set "exclude from search" to True for the pot type, this would be why. If you need the post types in the archives, but still want to exclude them from search, it is recommended to use the "pre_get_posts" hook to unset the post type from considered types.', 'custom-post-type-ui' );
								?>
							</div>
						</li>
					</ol>
				</td>
				<td class="outer">
					<h2><?php esc_html_e( 'Advanced', 'custom-post-type-ui' ); ?></h2>
					<ol id="questions_advanced">
						<li>
							<span tabindex="0" class="question" aria-controls="q20" aria-expanded="false"><?php esc_html_e( 'How do I add custom metaboxes to my post type?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q20">
								<?php
								printf(
									/* translators: Placeholders are just for HTML markup that doesn't need translated */
									esc_html__( 'We recommend checking out %s, the latest iteration of "CMB2". Both are supported by WebDevStudios.', 'custom-post-type-ui' ),
									'<a href="https://wordpress.org/plugins/cmb2/" target="_blank" rel="noopener">CMB2</a>'
								);
								?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q21" aria-expanded="false"><?php esc_html_e( 'How do I add a newly registered taxonomy to a post type that already exists?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q21">
								<?php
								printf(
									/* translators: Placeholders are just for HTML markup that doesn't need translated */
									esc_html__( 'Check out the %s function for documentation and usage examples.', 'custom-post-type-ui' ),
									'<a href="https://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type" target="_blank" rel="noopener">register_taxonomy_for_object_type()</a>'
								);
								?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q22" aria-expanded="false"><?php esc_html_e( 'Post relationships?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q22">
								<?php
								printf(
									/* translators: Placeholders are just for HTML markup that doesn't need translated */
									esc_html__( '%1$s has an excellent %2$spost%3$s introducing users to the %4$sPosts 2 Posts%5$s plugin that should be a good start.', 'custom-post-type-ui' ),
									'Pippin Williamson',
									'<a href="https://pippinsplugins.com/introduction-posts-2-posts-plugin/" target="_blank" rel="noopener">',
									'</a>',
									'<a href="https://wordpress.org/plugins/posts-to-posts/" target="_blank" rel="noopener">',
									'</a>'
								);
								?>
								</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q23" aria-expanded="false"><?php esc_html_e( 'Is there any function reference list?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q23">
							<?php
							printf(
								/* translators: Placeholders are just for HTML markup that doesn't need translated */
								esc_html__( '%s has compiled a nice list of functions used by our plugin. Note not all will be useful as they are attached to hooks.', 'custom-post-type-ui' ),
								'<a href="http://hookr.io/plugins/custom-post-type-ui/" target="_blank" rel="noopener">Hookr.io</a>'
							);
							?>
							</div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q24" aria-expanded="false"><?php esc_html_e( 'How do I filter the "enter title here" text in the post editor screen?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q24"><p><?php esc_html_e( 'Change text inside the post/page editor title field. Should be able to adapt as necessary.', 'custom-post-type-ui' ); ?></p>
<pre><code>function my_custom_title_text( $title ){
	global $post;
	if ( 'ENTER POST TYPE SLUG HERE' == $post->post_type )
		return 'NEW CUSTOM TEXT HERE';
	}
}
add_filter( 'enter_title_here', 'my_custom_title_text' );
</code></pre></div>
						</li>
						<li>
							<span tabindex="0" class="question" aria-controls="q25" aria-expanded="false"><?php esc_html_e( 'Any help with customizing capabilities?', 'custom-post-type-ui' ); ?></span>
							<div class="answer" id="q25">
								<p>
								<?php
								printf(
									/* translators: Placeholders are just for HTML markup that doesn't need translated */
									esc_html__( 'We recommend %s for some extended customization and addition of extra fields regarding roles and capabilities.', 'custom-post-type-ui' ),
									'<a href="https://github.com/tw2113/custom-post-type-ui-capabilities" target="_blank" rel="noopener">Custom Post Type UI Capabilities on GitHub</a>'
								);
								?>
									</p>
							</div>
						</li>
					</ol>
				</td>
				<td class="outer">
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
