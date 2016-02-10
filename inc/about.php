<?php
/**
 * Custom Post Type UI About Page.
 * @package    CPTUI
 * @subpackage About
 * @since 1.3.0
 */

/**
 * Display our primary menu page.
 *
 * @since 0.3.0
 *
 * @return string $value HTML markup for the page.
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
		<h1><?php _e( 'Custom Post Type UI', 'custom-post-type-ui' ); ?> <?php echo CPT_VERSION; ?></h1>

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
		<h2><?php printf( __( 'What\'s new in version %s', 'custom-post-type-ui' ), CPT_VERSION ); ?></h2>
		<div class="changelog about-integrations">
			<div class="cptui-feature feature-section col three-col">

				<div>
					<h2><?php _e( 'Updated internationalization', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'Our textdomain now matches the plugin slug from our WordPress.org repository to help aid in translating Custom Post Type UI', 'custom-post-type-ui' ); ?></p>
				</div>
				<div>
					<h2><?php _e( 'Debugging information', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'We have added a new "Debug Info" tab to the Import/Export area to aid in debugging issues with Custom Post Type UI.', 'custom-post-type-ui' ); ?></p>
				</div>
				<div>
					<h2><?php _e( 'Improved accessibility', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'A lot of work was done in the areas of accessibility to help aid users who need it. If you have feedback on where it could be further improved, let us know.', 'custom-post-type-ui' ); ?></p>
				</div>
				<div>
					<h2><?php _e( 'WP REST API support', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'We now have support for the required fields for the WP REST API. Now you can add your Custom Post Type UI post types and taxonomies to the available REST API lists.', 'custom-post-type-ui' ); ?></p>
				</div>
				<div>
					<h2><?php _e( 'More parameter support', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'We have added more parameters for greater customization of your post type and taxonomy settings.', 'custom-post-type-ui' ); ?></p>
				</div>
				<div>
					<h2><?php _e( 'New individual "Get Code" sections', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'The "Get Code" area now has support for copy/paste of individual post types and taxonomies.', 'custom-post-type-ui' ); ?></p>
				</div>
				<div class="last-feature">
					<h2><?php _e( 'Template hierarchy reference', 'custom-post-type-ui' ); ?></h2>
					<p><?php _e( 'To help aid your development with post types and taxonomies, we have added a quick reference list of common template files you can use in your theme. They will be listed on the "Registered Types/Taxes" screen.', 'custom-post-type-ui' ); ?></p>
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
					<img src="<?php echo plugins_url( '/images/professional-wordpress-plugin-development.png', dirname( __FILE__ ) ); ?>" width="200" alt="<?php esc_attr_e( 'Professional WordPress Pluing Development book cover.', 'custom-post-type-ui' ); ?>">
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
