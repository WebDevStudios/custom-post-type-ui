<?php
/**
 * Custom Post Type UI About Page.
 *
 * @package CPTUI
 * @subpackage About
 * @author WebDevStudios
 * @since 1.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display our primary menu page.
 *
 * @since 0.3.0
 *
 * @internal
 */
function cptui_settings() {
	?>
	<div class="wrap about-wrap">
		<?php

		/**
		 * Fires inside and at the top of the wrapper for the main plugin landing page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'cptui_main_page_start' ); ?>
		<h1><?php esc_html_e( 'Custom Post Type UI', 'custom-post-type-ui' ); ?> <?php echo CPTUI_VERSION; ?></h1>

		<?php

		/**
		 * Fires after the main page `<h1>` heading tag.
		 *
		 * @since 1.3.0
		 */
		do_action( 'cptui_main_page_after_header' );
		?>

		<div class="about-text cptui-about-text">
			<?php esc_html_e( 'Thank you for choosing Custom Post Type UI! We hope that your experience with our plugin makes creating post types and taxonomies and organizing your content quick and easy.', 'custom-post-type-ui' ); ?>
		</div>

		<?php
		/**
		 * Fires before the About Page changelog.
		 *
		 * @since 1.4.0
		 */
		do_action( 'cptui_main_page_before_changelog' ); ?>

		<h2><?php printf( esc_html__( 'What\'s new in version %s', 'custom-post-type-ui' ), CPTUI_VERSION ); ?></h2>
		<div class="changelog about-integrations">
			<div class="cptui-feature feature-section col three-col">
				<div>
					<h2><?php esc_html_e( 'Evolved UI for Custom Post Type UI', 'custom-post-type-ui' ); ?></h2>
					<p><?php esc_html_e( 'Once again we have evolved the UI for post type and taxonomy parameter screens. We strive to adhere to familiar WordPress admin familiarity and have adapted styles and UX applied to WordPress metaboxes.' ) ?></p>
				</div>
				<div>
					<h2><?php esc_html_e( 'Eliminated page refresh need.', 'custom-post-type-ui' ); ?></h2>
					<p><?php esc_html_e( 'Previously, due to how settings were saved, there was need for an extra refresh for newly saved settings to be applied to a post type or taxonomy. Under the hood, we have amended the saving process in order to remove need to trigger a refresh to see applied changes.' ) ?></p>
				</div>
				<h2><?php esc_html_e( 'From our previous release:', 'custom-post-type-ui' ); ?></h2>
				<div>
					<h2><?php _e( 'Slug prevention measures', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'We added measures on the post type and taxonomy slug inputs to prevent using characters that should not be used in slugs. This is primarily for when adding new post types and taxonomies, but will also affect when editing existing options. Do not hesitate to contact support if you are experiencing issues.' ) ?></p>
				</div>
				<div>
					<h2><?php _e( 'Improved rewrite rules flushing', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'We improved what we do after registering a new post type or taxonomy to better prevent having to manually flush rewrite rules.' ) ?></p>
				</div>
				<div class="last-feature">
					<h2><?php _e( 'Continued accessibility improvements', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'We have continued working on the accessibility of the plugin, building off what we accomplished in previous releases. If you have feedback on where it could be further improved, let us know.' ) ?></p>
				</div>
			</div>
		</div>

		<div class="extranotes">
			<?php

			/**
			 * Fires inside a div for extra notes.
			 *
			 * @since 1.3.0
			 */
			do_action( 'cptui_main_page_extra_notes' ); ?>
		</div>
	</div>
	<?php
}

/**
 * Display Pluginize-based content.
 *
 * @since 1.4.0
 */
function cptui_pluginize_content() {
	echo '<h1>' . sprintf( esc_html__( 'More from %s', 'custom-post-type-ui' ), 'WebDevStudios' ) . '</h1>';
	echo '<div class="wdspromos-about">';
	$ads = cptui_get_ads();
	if ( ! empty( $ads ) ) {

		foreach ( $ads as $ad ) {

			$the_ad = sprintf(
				'<img src="%s" alt="%s">',
				esc_attr( $ad['image'] ),
				esc_attr( $ad['text'] )
			);

			// Escaping $the_ad breaks the html.
			printf(
				'<p><a href="%s">%s</a></p>',
				esc_url( $ad['url'] ),
				$the_ad
			);
		}
	}
	echo '</div>';
}
add_action( 'cptui_main_page_extra_notes', 'cptui_pluginize_content', 9 );

/**
 * Render our newsletter form for the about page.
 *
 * @since 1.4.0
 */
function cptui_about_page_newsletter() {
	?>
	<h3><?php esc_html_e( 'Stay informed', 'custom-post-type-ui' ); ?></h3>
	<?php
	cptui_about_page_newsletter_form();
}
add_action( 'cptui_main_page_before_changelog', 'cptui_about_page_newsletter' );

/**
 * Outputs our newsletter signup form.
 *
 * @since 1.4.0
 *
 * @internal
 */
function cptui_about_page_newsletter_form() {
	?>
	<!-- Begin MailChimp Signup Form -->
	<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
	<div id="mc_embed_signup">
		<form action="//webdevstudios.us1.list-manage.com/subscribe/post?u=67169b098c99de702c897d63e&amp;id=9cb1c7472e" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
			<div id="mc_embed_signup_scroll">

				<p>
					<strong><?php esc_html_e( 'Wanna make the most of WordPress? Sign up for the Pluginize newsletter and get access to discounts, plugin announcements, and more!', 'custom-post-type-ui' ); ?></strong>
				</p>
				<div class="mc-field-group">
					<label for="mce-EMAIL"><?php esc_html_e( 'Email Address', 'custom-post-type-ui' ); ?></label>
					<input tabindex="-1" type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
				</div>
				<div id="mce-responses" class="clear">
					<div class="response" id="mce-error-response" style="display:none"></div>
					<div class="response" id="mce-success-response" style="display:none"></div>
				</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
				<div style="position: absolute; left: -5000px;" aria-hidden="true">
					<input type="text" name="b_67169b098c99de702c897d63e_9cb1c7472e" tabindex="-1" value=""></div>
				<div class="clear">
					<input type="submit" value="<?php esc_attr_e( 'Subscribe', 'custom-post-type-ui' ); ?>" name="subscribe" id="mc-embedded-subscribe" class="button" tabindex="-1">
				</div>
			</div>
		</form>
	</div>
	<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
	<script type='text/javascript'>(function ($) {
			window.fnames = new Array();
			window.ftypes = new Array();
			fnames[0] = 'EMAIL';
			ftypes[0] = 'email';
		}(jQuery));
		var $mcj = jQuery.noConflict(true);</script>
	<!--End mc_embed_signup-->
	<?php
}
