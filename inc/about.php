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
	$post_type_count = count( cptui_get_post_type_slugs() );
	$taxonomy_count  = count( cptui_get_taxonomy_slugs() );
	?>
	<div class="wrap cptui-about">
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
		<header class="cptui-about__header">
			<img class="cptui-about__icon" src="<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/cptui-icon-173x173.png' ); ?>" width="48" height="48" alt="">
			<div class="cptui-about__heading">
				<h1><?php esc_html_e( 'Custom Post Type UI', 'custom-post-type-ui' ); ?> <span class="cptui-about__version"><?php echo esc_html( CPTUI_VERSION ); ?></span></h1>
				<p class="cptui-about__counts">
					<?php
					printf(
						/* translators: 1: Number of post types. 2: Number of taxonomies. */
						esc_html__( '%1$s and %2$s registered with CPT UI', 'custom-post-type-ui' ),
						sprintf(
							'<a href="%1$s">%2$s</a>',
							esc_url( cptui_admin_url( 'admin.php?page=cptui_listings' ) ),
							/* translators: %s: Number of post types. */
							esc_html( sprintf( _n( '%s post type', '%s post types', $post_type_count, 'custom-post-type-ui' ), number_format_i18n( $post_type_count ) ) )
						),
						sprintf(
							'<a href="%1$s">%2$s</a>',
							esc_url( cptui_admin_url( 'admin.php?page=cptui_listings#taxonomies' ) ),
							/* translators: %s: Number of taxonomies. */
							esc_html( sprintf( _n( '%s taxonomy', '%s taxonomies', $taxonomy_count, 'custom-post-type-ui' ), number_format_i18n( $taxonomy_count ) ) )
						)
					); // phpcs:ignore WordPress.Security.EscapeOutput
					?>
				</p>
			</div>
			<nav class="cptui-about__links" aria-label="<?php esc_attr_e( 'Custom Post Type UI', 'custom-post-type-ui' ); ?>">
				<a class="button" href="<?php echo esc_url( cptui_admin_url( 'admin.php?page=cptui_manage_post_types' ) ); ?>"><?php esc_html_e( 'Add post type', 'custom-post-type-ui' ); ?></a>
				<a class="button" href="<?php echo esc_url( cptui_admin_url( 'admin.php?page=cptui_manage_taxonomies' ) ); ?>"><?php esc_html_e( 'Add taxonomy', 'custom-post-type-ui' ); ?></a>
				<a class="button" href="https://docs.pluginize.com/" target="_blank" rel="noopener"><?php esc_html_e( 'Docs', 'custom-post-type-ui' ); ?></a>
				<a class="button" href="<?php echo esc_url( cptui_admin_url( 'admin.php?page=cptui_support' ) ); ?>"><?php esc_html_e( 'Help', 'custom-post-type-ui' ); ?></a>
			</nav>
		</header>
		<?php

		/**
		 * Fires after the main page header.
		 *
		 * @since 1.3.0
		 */
		do_action( 'cptui_main_page_after_header' );

		/**
		 * Fires before the main page content.
		 *
		 * Kept for backward compatibility; the page no longer has a changelog.
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
 * WebDevStudios products recommended on the About page, matched to this site.
 *
 * Each entry says why it fits. Detection only checks what is installed and
 * active here; nothing is sent anywhere.
 *
 * @since 1.19.4
 *
 * @return array Recommended products keyed by slug, best match first.
 */
function cptui_about_recommendations() {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$recommended = [];

	// WP Search with Algolia: a natural next step once a site has custom content.
	if ( ! defined( 'WPSWA_PRO_VERSION' ) ) {
		$post_types = count( cptui_get_post_type_slugs() );
		$installed  = array_key_exists( 'wp-search-with-algolia/algolia.php', get_plugins() );

		if ( class_exists( 'WooCommerce' ) ) {
			$reason = __( 'You run WooCommerce', 'custom-post-type-ui' );
		} elseif ( $post_types > 0 ) {
			/* translators: %s: Number of custom post types. */
			$reason = sprintf( _n( 'You have %s custom post type', 'You have %s custom post types', $post_types, 'custom-post-type-ui' ), number_format_i18n( $post_types ) );
		} else {
			$reason = __( 'Better search for your content', 'custom-post-type-ui' );
		}

		$pro_url = 'https://pluginize.com/plugins/wp-search-with-algolia-pro/?utm_source=cptui-about-recommended&utm_medium=plugin&utm_campaign=wp-search-with-algolia-pro';

		if ( defined( 'ALGOLIA_VERSION' ) ) {
			// Free plugin already running: pitch Pro's custom field search.
			$recommended['wp-search-with-algolia'] = [
				'name'    => 'WP Search with Algolia Pro',
				'reason'  => __( 'You use WP Search with Algolia', 'custom-post-type-ui' ),
				'pitch'   => __( 'Make the custom fields on your post types searchable and filterable, with WooCommerce SKU, price and stock support and AI ranking that learns from what visitors choose.', 'custom-post-type-ui' ),
				'image'   => 'algolia-search.webp',
				'primary' => [ __( 'See Algolia Pro', 'custom-post-type-ui' ), $pro_url, true ],
			];
		} else {
			$recommended['wp-search-with-algolia'] = [
				'name'      => 'WP Search with Algolia',
				'reason'    => $reason,
				'pitch'     => __( 'WordPress search skips custom fields and ranks results poorly. Algolia searches every post type as visitors type, with typo tolerance and relevance you can tune. Free on WordPress.org.', 'custom-post-type-ui' ),
				'image'     => 'algolia-search.webp',
				'primary'   => $installed
					? [ __( 'Activate free plugin', 'custom-post-type-ui' ), admin_url( 'plugins.php?plugin_status=inactive&s=algolia' ), false ]
					: [ __( 'Install free', 'custom-post-type-ui' ), admin_url( 'plugin-install.php?s=' . rawurlencode( 'WP Search with Algolia' ) . '&tab=search&type=term' ), false ],
				'secondary' => [ __( 'See Pro', 'custom-post-type-ui' ), $pro_url ],
			];
		}
	}

	// ThemeSwitcher Pro: for sites still on the Classic Editor or a classic theme.
	if ( ! defined( 'TSP_VERSION' ) ) {
		$reason = '';
		if ( class_exists( 'Classic_Editor' ) || is_plugin_active( 'disable-gutenberg/disable-gutenberg.php' ) || ( function_exists( 'use_block_editor_for_post_type' ) && ! use_block_editor_for_post_type( 'post' ) ) ) {
			$reason = __( 'You use the Classic Editor', 'custom-post-type-ui' );
		} elseif ( function_exists( 'wp_is_block_theme' ) && ! wp_is_block_theme() ) {
			$reason = __( 'Your theme is a classic theme', 'custom-post-type-ui' );
		}

		if ( '' !== $reason ) {
			$recommended['themeswitcher-pro'] = [
				'name'    => 'ThemeSwitcher Pro',
				'reason'  => $reason,
				'pitch'   => __( 'Move to the block editor and a new theme one page at a time. New pages use the new theme while the rest of your site stays exactly as it is.', 'custom-post-type-ui' ),
				'art'     => 'themeswitcher',
				'primary' => [ __( 'See ThemeSwitcher Pro', 'custom-post-type-ui' ), 'https://themeswitcher.com/?utm_source=cptui-about-recommended&utm_medium=plugin&utm_campaign=themeswitcher-pro', true ],
			];
		}
	}

	/**
	 * Filters the products recommended on the CPT UI About page.
	 *
	 * @since 1.19.4
	 *
	 * @param array $recommended Recommended products keyed by slug.
	 */
	return (array) apply_filters( 'cptui_about_recommendations', $recommended );
}

/**
 * Display the "Recommended for your site" row.
 *
 * Kept under its 1.4.0 name so code that unhooks it keeps working.
 *
 * @since 1.4.0
 * @since 1.19.4 Recommends products based on what the site runs.
 */
function cptui_pluginize_content() {
	$recommended = cptui_about_recommendations();
	if ( empty( $recommended ) ) {
		return;
	}
	?>
	<section class="cptui-recommended<?php echo 1 === count( $recommended ) ? ' cptui-recommended--single' : ''; ?>" aria-labelledby="cptui-recommended-heading">
		<h2 id="cptui-recommended-heading"><?php esc_html_e( 'Recommended for your site', 'custom-post-type-ui' ); ?></h2>
		<div class="cptui-recommended__grid">
			<?php foreach ( $recommended as $slug => $product ) : ?>
				<article class="cptui-recommended__card cptui-recommended__card--<?php echo esc_attr( $slug ); ?>">
					<div class="cptui-recommended__media">
						<?php if ( ! empty( $product['image'] ) ) : ?>
							<img src="<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'images/products/' . $product['image'] ); ?>" alt="" loading="lazy">
						<?php elseif ( isset( $product['art'] ) && 'themeswitcher' === $product['art'] ) : ?>
							<?php cptui_about_themeswitcher_art(); ?>
						<?php endif; ?>
					</div>
					<div class="cptui-recommended__body">
						<p class="cptui-recommended__reason"><?php echo esc_html( $product['reason'] ); ?></p>
						<h3><?php echo esc_html( $product['name'] ); ?></h3>
						<p><?php echo esc_html( $product['pitch'] ); ?></p>
						<p class="cptui-recommended__actions">
							<?php
							list( $label, $url, $external ) = $product['primary'];
							printf(
								'<a class="button button-primary" href="%1$s"%2$s>%3$s</a>',
								esc_url( $url ),
								$external ? ' target="_blank" rel="noopener"' : '',
								esc_html( $label )
							);
							if ( ! empty( $product['secondary'] ) ) {
								printf(
									'<a href="%1$s" target="_blank" rel="noopener">%2$s</a>',
									esc_url( $product['secondary'][1] ),
									esc_html( $product['secondary'][0] )
								);
							}
							?>
						</p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}
add_action( 'cptui_main_page_extra_notes', 'cptui_pluginize_content', 9 );

/**
 * A small drawing of two themes on one site, for the ThemeSwitcher Pro card.
 *
 * @since 1.19.4
 */
function cptui_about_themeswitcher_art() {
	?>
	<div class="cptui-ts-art" aria-hidden="true">
		<div class="cptui-ts-art__window cptui-ts-art__window--classic">
			<span class="cptui-ts-art__bar"><?php esc_html_e( 'Current theme', 'custom-post-type-ui' ); ?></span>
			<span class="cptui-ts-art__line"></span><span class="cptui-ts-art__line"></span><span class="cptui-ts-art__line cptui-ts-art__line--short"></span>
		</div>
		<span class="dashicons dashicons-randomize cptui-ts-art__switch"></span>
		<div class="cptui-ts-art__window cptui-ts-art__window--block">
			<span class="cptui-ts-art__bar"><?php esc_html_e( 'New block theme', 'custom-post-type-ui' ); ?></span>
			<span class="cptui-ts-art__hero"></span>
			<span class="cptui-ts-art__cols"><span></span><span></span></span>
		</div>
	</div>
	<?php
}

/**
 * Short introduction to WebDevStudios, the team behind CPT UI.
 *
 * @since 1.19.4
 */
function cptui_about_webdevstudios() {
	$url = 'https://webdevstudios.com/?utm_source=cptui-about&utm_medium=plugin&utm_campaign=cptui';
	?>
	<section class="cptui-about__wds" aria-labelledby="cptui-wds-heading">
		<div class="cptui-about__wds-copy">
			<h2 id="cptui-wds-heading"><?php esc_html_e( 'Built and maintained by WebDevStudios', 'custom-post-type-ui' ); ?></h2>
			<p><?php esc_html_e( 'WebDevStudios is a WordPress agency that has designed, built and supported WordPress sites since 2008, from small businesses to brands like Microsoft and the NBA. The same engineers who do that client work build and maintain Custom Post Type UI, so the plugin is shaped by real projects.', 'custom-post-type-ui' ); ?></p>
			<p><?php esc_html_e( 'If you need help beyond the plugin, our team takes on custom development, site builds and redesigns, migrations, performance and accessibility work, and ongoing support.', 'custom-post-type-ui' ); ?></p>
			<p class="cptui-about__wds-actions">
				<a class="button" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Meet WebDevStudios', 'custom-post-type-ui' ); ?></a>
			</p>
		</div>
		<ul class="cptui-about__wds-stats">
			<li><strong><?php esc_html_e( '2008', 'custom-post-type-ui' ); ?></strong> <span><?php esc_html_e( 'Building WordPress since', 'custom-post-type-ui' ); ?></span></li>
			<li><strong><?php esc_html_e( '1M+', 'custom-post-type-ui' ); ?></strong> <span><?php esc_html_e( 'Sites run Custom Post Type UI', 'custom-post-type-ui' ); ?></span></li>
			<li><strong><?php esc_html_e( 'Free', 'custom-post-type-ui' ); ?></strong> <span><?php esc_html_e( 'Actively maintained, always', 'custom-post-type-ui' ); ?></span></li>
		</ul>
	</section>
	<?php
}
add_action( 'cptui_main_page_extra_notes', 'cptui_about_webdevstudios', 10 );

/**
 * Footer with the rest of WebDevStudios' products and the newsletter signup.
 *
 * @since 1.19.4
 */
function cptui_about_footer() {
	$recommended = array_keys( cptui_about_recommendations() );
	$more        = array_filter(
		cptui_get_ads(),
		static function ( $ad ) use ( $recommended ) {
			if ( ! empty( $ad['format'] ) && 'compact' !== $ad['format'] ) {
				return false;
			}
			return empty( $ad['slug'] ) || ! in_array( $ad['slug'], $recommended, true );
		}
	);
	?>
	<div class="cptui-about__footer">
		<?php if ( ! empty( $more ) ) : ?>
			<section class="cptui-about__more" aria-labelledby="cptui-more-heading">
				<?php // translators: Placeholder will hold the name of the author of the plugin. ?>
				<h2 id="cptui-more-heading"><?php echo esc_html( sprintf( __( 'More from %s', 'custom-post-type-ui' ), 'WebDevStudios' ) ); ?></h2>
				<ul>
					<?php foreach ( $more as $ad ) : ?>
						<?php
						if ( empty( $ad['name'] ) || empty( $ad['url'] ) ) {
							continue;
						}
						?>
						<li>
							<a href="<?php echo esc_url( $ad['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $ad['name'] ); ?></a>
							<?php if ( ! empty( $ad['tagline'] ) ) : ?>
								<span><?php echo esc_html( wp_trim_words( $ad['tagline'], 14 ) ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>
		<?php cptui_about_page_newsletter(); ?>
	</div>
	<?php
}
add_action( 'cptui_main_page_extra_notes', 'cptui_about_footer', 11 );

/**
 * Render our newsletter form for the about page.
 *
 * @since 1.4.0
 * @since 1.19.4 Rendered in the page footer by cptui_about_footer().
 */
function cptui_about_page_newsletter() {

	if ( cptui_is_new_install() ) {
		return '';
	}

	?>
	<div class="cptui-about-newsletter">
		<?php
		cptui_newsletter_form();
		?>
	</div>

	<?php

	return '';
}

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
