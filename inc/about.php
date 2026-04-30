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

// phpcs:disable WebDevStudios.All.RequireAuthor

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue our Custom Post Type UI assets.
 *
 * @since 1.6.0
 */
function cptui_about_assets( $hook ) {

	if ( 'toplevel_page_cptui_main_menu' !== $hook ) {
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
		 * Fires immediately after wrap div started on all of the cptui admin pages.
		 *
		 * @since 1.14.0
		 */
		do_action( 'cptui_inside_wrap' );

		/**
		 * Fires inside and at the top of the wrapper for the main plugin landing page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'cptui_main_page_start' );
		?>
		<h1><?php esc_html_e( 'Custom Post Type UI', 'custom-post-type-ui' ); ?> <?php echo esc_html( CPTUI_VERSION ); ?></h1>

		<?php

		/**
		 * Fires after the main page `<h1>` heading tag.
		 *
		 * @since 1.3.0
		 */
		do_action( 'cptui_main_page_after_header' );
		?>
		<div class="cptui-intro-devblock">
			<p class="about-text cptui-about-text">
				<?php esc_html_e( 'Thank you for choosing Custom Post Type UI! We hope that your experience with our plugin makes creating post types and taxonomies and organizing your content quick and easy.', 'custom-post-type-ui' ); ?>
			</p>
			<div class="cptui-badge"></div>
		</div>
		<?php
		/**
		 * Fires before the About Page changelog.
		 *
		 * @since 1.4.0
		 */
		do_action( 'cptui_main_page_before_changelog' );
		?>

		<div class="extranotes">
			<?php

			/**
			 * Fires inside a div for extra notes.
			 *
			 * @since 1.3.0
			 */
			do_action( 'cptui_main_page_extra_notes' );
			?>
		</div>
	</div>
	<?php
}

/**
 * Display the prominent CPT UI Pro upgrade callout.
 *
 * Hooked at priority 5 so it renders above the smaller "More from WebDevStudios"
 * ad row from cptui_pluginize_content() (priority 9).
 *
 * @since 1.20.0
 */
function cptui_pro_callout_content() {

	if ( class_exists( 'CPTUI_Pro' ) ) {
		return;
	}

	$pro_url = 'https://pluginize.com/plugins/custom-post-type-ui-pro/?utm_source=cptui-about&utm_medium=plugin&utm_campaign=cptui';

	$features = [
		[
			'title' => esc_html__( 'Column Builder', 'custom-post-type-ui' ),
			'desc'  => esc_html__( 'Add, remove, and reorder columns on any post type list screen — core fields, custom meta, ACF fields, and taxonomy terms. Includes per-user visibility controls.', 'custom-post-type-ui' ),
		],
		[
			'title' => esc_html__( 'Advanced Filters', 'custom-post-type-ui' ),
			'desc'  => esc_html__( 'Add dropdown filters to any post type list screen — filter by taxonomy, meta field, or custom data without writing code.', 'custom-post-type-ui' ),
		],
		[
			'title' => esc_html__( 'Taxonomy List Table Controls', 'custom-post-type-ui' ),
			'desc'  => esc_html__( 'Extend the column builder and filter system to taxonomy term list screens, not just post types.', 'custom-post-type-ui' ),
		],
		[
			'title' => esc_html__( 'Shortcode Builder', 'custom-post-type-ui' ),
			'desc'  => esc_html__( 'Display custom post type content anywhere with a visual shortcode builder. Layouts include list, grid, grid with overlay, slider, post cards, featured plus, single post, single page, single post type, and taxonomy list.', 'custom-post-type-ui' ),
		],
		[
			'title' => esc_html__( 'Gutenberg Display Block', 'custom-post-type-ui' ),
			'desc'  => esc_html__( 'A dedicated block for pulling and displaying custom post type content inside the block editor.', 'custom-post-type-ui' ),
		],
		[
			'title' => esc_html__( 'Multisite / Network Support', 'custom-post-type-ui' ),
			'desc'  => esc_html__( 'Full network admin UI for managing CPT UI settings across a multisite installation.', 'custom-post-type-ui' ),
		],
		[
			'title' => esc_html__( 'Third-Party Integrations', 'custom-post-type-ui' ),
			'desc'  => esc_html__( 'ACF fields native in the column builder, dedicated WooCommerce product and Easy Digital Downloads layouts, and Customizer-based styling for shortcode output.', 'custom-post-type-ui' ),
		],
		[
			'title' => esc_html__( 'Theme Template Overrides', 'custom-post-type-ui' ),
			'desc'  => esc_html__( 'Copy shortcode templates into your theme to fully customize the HTML output.', 'custom-post-type-ui' ),
		],
		[
			'title' => esc_html__( 'Automatic Updates & Priority Support', 'custom-post-type-ui' ),
			'desc'  => esc_html__( 'License-based updates delivered directly from Pluginize, plus access to priority support.', 'custom-post-type-ui' ),
		],
	];

	?>
	<section class="cptui-pro-callout" aria-labelledby="cptui-pro-callout-heading">
		<header class="cptui-pro-callout__header">
			<h2 id="cptui-pro-callout-heading" class="cptui-pro-callout__title">
				<?php esc_html_e( 'Upgrade to Custom Post Type UI Pro', 'custom-post-type-ui' ); ?>
			</h2>
			<p class="cptui-pro-callout__tagline">
				<?php esc_html_e( 'Unlock the full potential of your custom post types and taxonomies — display content anywhere, control list table columns and filters, and integrate with the tools you already use.', 'custom-post-type-ui' ); ?>
			</p>
			<p class="cptui-pro-callout__cta">
				<a class="button button-primary button-hero" href="<?php echo esc_url( $pro_url ); ?>" target="_blank" rel="noopener">
					<?php esc_html_e( 'Get CPT UI Pro', 'custom-post-type-ui' ); ?>
				</a>
			</p>
		</header>

		<ul class="cptui-pro-callout__features">
			<?php foreach ( $features as $feature ) : ?>
				<li class="cptui-pro-callout__feature">
					<h3 class="cptui-pro-callout__feature-title"><?php echo esc_html( $feature['title'] ); ?></h3>
					<p class="cptui-pro-callout__feature-desc"><?php echo esc_html( $feature['desc'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php
}
add_action( 'cptui_main_page_extra_notes', 'cptui_pro_callout_content', 5 );

/**
 * Display Pluginize-based content.
 *
 * @since 1.4.0
 */
function cptui_pluginize_content() {
	$ads = array_filter(
		cptui_get_ads(),
		static function ( $ad ) {
			// Skip the hero (CPT UI Pro) — the dedicated callout above already promotes it.
			return empty( $ad['format'] ) || 'compact' === $ad['format'];
		}
	);

	if ( empty( $ads ) ) {
		return;
	}

	// translators: Placeholder will hold the name of the author of the plugin.
	echo '<h2>' . sprintf( esc_html__( 'More from %s', 'custom-post-type-ui' ), 'WebDevStudios' ) . '</h2>';
	echo '<div class="cptui-promo-secondary cptui-promo-secondary--about">';
	echo '<div class="cptui-promo-secondary__list">';
	foreach ( $ads as $ad ) {
		cptui_render_ad_compact( $ad, 'about' );
	}
	echo '</div>';
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
	<div class='wdsoctosignup'>
		<?php
		cptui_newsletter_form();
		?>
	</div>

	<?php

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
