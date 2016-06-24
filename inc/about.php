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
			<?php _e( 'Thank you for choosing Custom Post Type UI! We hope that your experience with our plugin makes creating post types and taxonomies and organizing your content quick and easy.', 'custom-post-type-ui' ); ?>
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
					<p><?php _e( 'We have continued working on the accessibility of the plugin, building off what we accomplished in previous releases. If you have feedback on where it could be further improved, let us know.' ) ?></p>
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
