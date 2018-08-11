<?php
/**
 * Custom Post Type UI About Page.
 *
 * @package CPTUI
 * @subpackage About
 * @author WebDevStudios
 * @since 1.3.0
 * @license GPL-2.0+
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue our Custom Post Type UI assets.
 *
 * @since 1.6.0
 */
function cptui_about_assets() {
	$current_screen = get_current_screen();

	if ( ! is_object( $current_screen ) || 'toplevel_page_cptui_main_menu' !== $current_screen->base ) {
		return;
	}

	if ( wp_doing_ajax() ) {
		return;
	}

	wp_enqueue_style( 'cptui-css' );
}
add_action( 'admin_enqueue_scripts', 'cptui_about_assets' );

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
		<h1><?php esc_html_e( 'Custom Post Type UI', 'custom-post-type-ui' ); ?> <?php echo esc_html( CPTUI_VERSION ); ?></h1>

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
		<div class="cptui-badge"></div>

		<?php
		/**
		 * Fires before the About Page changelog.
		 *
		 * @since 1.4.0
		 */
		do_action( 'cptui_main_page_before_changelog' ); ?>

		<h2><?php printf( esc_html__( "What's new in version %s", 'custom-post-type-ui' ), CPTUI_VERSION ); ?></h2>
		<div class="changelog about-integrations">
			<div class="cptui-feature feature-section col three-col">
				<div class="col">
					<h2><?php esc_html_e( 'Renamed the Import/Export menu.', 'custom-post-type-ui' ); ?></h2>
					<p><?php esc_html_e( 'As Custom Post Type UI has evolved, we have found need to rename one of the menus. The Import/Export menu has now been renamed "Tools" to better reflect the utilities provided there.', 'custom-post-type-ui' ); ?></p>
				</div>
				<div class="col">
					<h2><?php esc_html_e( 'Eliminated page refresh need for importing.', 'custom-post-type-ui' ); ?></h2>
					<p><?php esc_html_e( 'Previously we eliminated page refresh need while creating new post types and taxonomies. We noticed this did not apply when importing settings. With this latest release, we have amended the issue.', 'custom-post-type-ui' ); ?></p>
				</div>
				<div class="col last-feature">
					<h2><?php esc_html_e( 'Multiple issue fixes.', 'custom-post-type-ui' ); ?></h2>
					<p><?php esc_html_e( 'We have fixed the following issues in this version. Added "action" as a reserved taxonomy name. Updated `get_terms()` handling for WordPress 4.5. Fixed PHP notices related to rewrite indexes, that were present since version 1.0.6. Prevented triggering a slug conversion when tabbing through the edit screen.', 'custom-post-type-ui' ) ?></p>
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

	if ( cptui_is_new_install() ) {
		return '';
	}

	?>
	<h3><?php esc_html_e( 'Stay informed', 'custom-post-type-ui' ); ?></h3>
	<?php
	cptui_newsletter_form();

	return '';
}
add_action( 'cptui_main_page_before_changelog', 'cptui_about_page_newsletter' );

/**
 * Marks site as not new at the end of the about/main page.
 *
 * Can't be done on activation or else cptui_is_new_install() will immediately start
 * returning false. So we'll do it at the end of the redirected landing page.
 *
 * @since 1.5.0
 */
function cptui_mark_not_new() {
	if ( cptui_is_new_install() ) {
		cptui_set_not_new_install();
	}
}
add_action( 'cptui_main_page_extra_notes', 'cptui_mark_not_new', 999 );
