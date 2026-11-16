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
 * @since 1.19.1
 */
function cptui_pro_callout_content() {

	if ( ! function_exists( 'cptui_show_pro_promos' ) || ! cptui_show_pro_promos() ) {
		return;
	}

	$pillars = cptui_pro_pillars();
	$thumbs  = [
		'lists'     => 'lists-after.webp',
		'display'   => 'display-grid.webp',
		'handoff'   => 'access-control.webp',
		'multisite' => '',
	];
	?>
	<section class="cptui-pro-callout" aria-labelledby="cptui-pro-callout-heading">
		<header class="cptui-pro-callout__header">
			<h2 id="cptui-pro-callout-heading" class="cptui-pro-callout__title">
				<?php esc_html_e( 'You built the content model. Now make it easy to run.', 'custom-post-type-ui' ); ?>
			</h2>
			<p class="cptui-pro-callout__tagline">
				<?php esc_html_e( 'CPT UI Pro puts your fields in the admin lists, keeps clients from breaking the structure, and shows your content on the site without templates.', 'custom-post-type-ui' ); ?>
			</p>
		</header>

		<ul class="cptui-pro-callout__features">
			<?php foreach ( $pillars as $key => $pillar ) : ?>
				<li class="cptui-pro-callout__feature">
					<button type="button" class="cptui-pro-callout__card" data-cptui-pro-tour="<?php echo esc_attr( $key ); ?>">
						<?php if ( ! empty( $thumbs[ $key ] ) ) : ?>
							<img src="<?php echo esc_url( cptui_pro_asset_url( $thumbs[ $key ] ) ); ?>" alt="" loading="lazy">
						<?php else : ?>
							<span class="cptui-pro-callout__icon dashicons dashicons-networking" aria-hidden="true"></span>
						<?php endif; ?>
						<span class="cptui-pro-callout__feature-label"><?php echo esc_html( $pillar['label'] ); ?></span>
						<span class="cptui-pro-callout__feature-title"><?php echo esc_html( $pillar['title'] ); ?></span>
					</button>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="cptui-pro-callout__footer">
			<?php cptui_pro_cta_row( 'cptui-about', 'pricing' ); ?>
			<a class="cptui-pro-callout__compare" href="<?php echo esc_url( cptui_pro_page_url( 'compare' ) ); ?>"><?php esc_html_e( 'Compare free and Pro', 'custom-post-type-ui' ); ?> <span aria-hidden="true">&rarr;</span></a>
		</div>
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
