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
function cptui_settings() { ?>
	<div class="wrap about-wrap">
		<?php

		/**
		 * Fires inside and at the top of the wrapper for the main plugin landing page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'cptui_main_page_start' ); ?>
		<h1><?php _e( 'Custom Post Type UI', 'custom-post-type-ui' ); ?> <?php echo CPTUI_VERSION; ?></h1>

		<?php

		/**
		 * Fires after the main page `<h1>` heading tag.
		 *
		 * @since 1.3.0
		 */
		do_action( 'cptui_main_page_after_header' );
		?>

		<div class="about-text cptui-about-text">
			<?php _e( 'Thank you for choosing Custom Post Type UI. We hope that your experience with our plugin provides efficiency and speed in creating post types and taxonomies, to better organize your content, without having to touch code.', 'custom-post-type-ui' ); ?>
		</div>
		<h2><?php printf( __( 'What\'s new in version %s', 'custom-post-type-ui' ), CPTUI_VERSION ); ?></h2>
		<div class="changelog about-integrations">
			<div class="cptui-feature feature-section col three-col">
				<div>
					<h2><?php _e( 'Evolved UI for Custom Post Type UI', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'We have further revised our UI for post type and taxonomy parameter screens. Better separation of required and optional settings was a primary goal as well as user flow through the screen.' ) ?></p>
				</div>
				<div>
					<h2><?php _e( 'Slug prevention measures', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'We added measures on the post type and taxonomy slug inputs to prevent using characters that should not be used in slugs. This is primarily for when adding new post types and taxonomies, but will also affect when editing existing options. Do not hesitate to contact support if you are experiencing issues.' ) ?></p>
				</div>
				<div>
					<h2><?php _e( 'Improved rewrite rules flushing', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'We improved what we do after registering a new post type or taxonomy to better prevent having to manually flush rewrite rules.' ) ?></p>
				</div>
				<div>
					<h2><?php _e( 'Continued accessibility improvements', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'We have continued working on the accessibility of the plugin, building off what we accomplished in previous releases. If you have feedback on where it could be further improved, let us know.') ?></p>
				</div>
				<div>
					<h2><?php _e( 'More parameter and label support', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'We have added more parameters as well as recently added labels for greater customization of your post type and taxonomy settings.', 'custom-post-type-ui' ); ?></p>
				</div>
				<div class="last-feature">
					<h2><?php _e( 'Default "supports" for post types', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'The "Title", "Editor", and "Featured Image" checkboxes are now checked by default when adding a new post type.', 'custom-post-type-ui' ); ?></p>
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
 * Outputs the Donation content on the about page.
 *
 * @since 1.3.0
 *
 * @internal
 */
function cptui_donation_content() { ?>
	<h1><?php _e( 'Help Support This Plugin!', 'custom-post-type-ui' ); ?></h1>
	<table border="0">
		<tr>
			<td class="one-third valign">
				<h2><?php _e( 'Professional WordPress<br />Third Edition', 'custom-post-type-ui' ); ?></h2>
				<a href="http://bit.ly/prowp3" target="_blank">
					<img src="<?php echo plugins_url( '/images/professional-wordpress-thirdedition.jpg', dirname( __FILE__ ) ); ?>" width="200" alt="<?php esc_attr_e( 'Professional WordPress Design and Development book cover.', 'custom-post-type-ui' ); ?>">
				</a>
				<br />
				<p><?php _e( 'The leading book on WordPress design and development! Brand new third edition!', 'custom-post-type-ui' ); ?></p>
			</td>
			<td class="one-third valign">
				<h2><?php _e( 'Professional WordPress<br />Plugin Development', 'custom-post-type-ui' ); ?></h2>
				<a href="http://amzn.to/plugindevbook" target="_blank">
					<img src="<?php echo plugins_url( '/images/professional-wordpress-plugin-development.png', dirname( __FILE__ ) ); ?>" width="200" alt="<?php esc_attr_e( 'Professional WordPress Plugin Development book cover.', 'custom-post-type-ui' ); ?>">
				</a>
				<br />
				<p><?php _e( 'Highest rated WordPress development book on Amazon!', 'custom-post-type-ui' ); ?></p>
			</td>
			<td class="one-third valign">
				<h2><?php _e( 'PayPal Donation', 'custom-post-type-ui' ); ?></h2>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="YJEDXPHE49Q3U">
					<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" name="submit" alt="<?php esc_attr_e( 'PayPal - The safer, easier way to pay online!', 'custom-post-type-ui' ); ?>">
					<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
				<p><?php _e( 'Please donate to the development of Custom Post Type UI:', 'custom-post-type-ui' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'cptui_main_page_extra_notes', 'cptui_donation_content', 10 );
