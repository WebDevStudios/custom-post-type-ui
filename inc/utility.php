<?php
/**
 * Custom Post Type UI Utility Code.
 *
 * @package CPTUI
 * @subpackage Utility
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
 * Edit links that appear on installed plugins list page, for our plugin.
 *
 * @since 1.0.0
 *
 * @internal
 *
 * @param array $links Array of links to display below our plugin listing.
 * @return array Amended array of links.
 */
function cptui_edit_plugin_list_links( $links ) {

	if ( is_array( $links ) && isset( $links['edit'] ) ) {
		// We shouldn't encourage editing our plugin directly.
		unset( $links['edit'] );
	}

	// Add our custom links to the returned array value.
	return array_merge(
		array(
			'<a href="' . admin_url( 'admin.php?page=cptui_main_menu' ) . '">' . esc_html__( 'About', 'custom-post-type-ui' ) . '</a>',
			'<a href="' . admin_url( 'admin.php?page=cptui_support' ) . '">' . esc_html__( 'Help', 'custom-post-type-ui' ) . '</a>',
		),
		$links
	);
}
add_filter( 'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) ) . '/custom-post-type-ui.php', 'cptui_edit_plugin_list_links' );

/**
 * Returns SVG icon for custom menu icon
 *
 * @since 1.2.0
 *
 * @return string
 */
function cptui_menu_icon() {
	return 'dashicons-forms';
}

/**
 * Return boolean status depending on passed in value.
 *
 * @since 0.5.0
 *
 * @param mixed $bool_text text to compare to typical boolean values.
 * @return bool Which bool value the passed in value was.
 */
function get_disp_boolean( $bool_text ) {
	$bool_text = (string) $bool_text;
	if ( empty( $bool_text ) || '0' === $bool_text || 'false' === $bool_text ) {
		return false;
	}

	return true;
}

/**
 * Return string versions of boolean values.
 *
 * @since 0.1.0
 *
 * @param string $bool_text String boolean value.
 * @return string standardized boolean text.
 */
function disp_boolean( $bool_text ) {
	$bool_text = (string) $bool_text;
	if ( empty( $bool_text ) || '0' === $bool_text || 'false' === $bool_text ) {
		return 'false';
	}

	return 'true';
}

/**
 * Display footer links and plugin credits.
 *
 * @since 0.3.0
 *
 * @internal
 *
 * @param string $original Original footer content. Optional. Default empty string.
 * @return string $value HTML for footer.
 */
function cptui_footer( $original = '' ) {

	$screen = get_current_screen();

	if ( ! is_object( $screen ) || 'cptui_main_menu' !== $screen->parent_base ) {
		return $original;
	}

	return sprintf(
		// translators: Placeholder will hold the name of the plugin, version of the plugin and a link to WebdevStudios.
		esc_attr__( '%1$s %2$s by %3$s', 'custom-post-type-ui' ),
		esc_attr__( 'Custom Post Type UI', 'custom-post-type-ui' ),
		CPTUI_VERSION,
		'<a href="https://webdevstudios.com">WebDevStudios</a>'
	) . ' - ' .
	sprintf(
		// translators: Placeholders are just for HTML markup that doesn't need translated.
		'<a href="https://wordpress.org/support/plugin/custom-post-type-ui">%s</a>',
		esc_attr__( 'Support forums', 'custom-post-type-ui' )
	) . ' - ' .
	sprintf(
		// translators: Placeholders are just for HTML markup that doesn't need translated.
		'<a href="https://wordpress.org/plugins/custom-post-type-ui/reviews/">%s</a>',
		sprintf(
			// translators: Placeholder will hold `<abbr>` tag for CPTUI.
			esc_attr__( 'Review %s', 'custom-post-type-ui' ),
			sprintf(
				// translators: Placeholders are just for HTML markup that doesn't need translated.
				'<abbr title="%s">%s</abbr>',
				esc_attr__( 'Custom Post Type UI', 'custom-post-type-ui' ),
				'CPTUI'
			)
		)
	) . ' - ' .
	esc_attr__( 'Follow on X:', 'custom-post-type-ui' ) .
	sprintf(
		// translators: Placeholders are just for HTML markup that doesn't need translated.
		' %s',
		'<a href="https://x.com/webdevstudios">WebDevStudios</a>'
	);
}
add_filter( 'admin_footer_text', 'cptui_footer' );

/**
 * Conditionally flushes rewrite rules if we have reason to.
 *
 * @since 1.3.0
 */
function cptui_flush_rewrite_rules() {

	if ( wp_doing_ajax() ) {
		return;
	}

	/*
	 * Wise men say that you should not do flush_rewrite_rules on init or admin_init. Due to the nature of our plugin
	 * and how new post types or taxonomies can suddenly be introduced, we need to...potentially. For this,
	 * we rely on a short lived transient. Only 5 minutes life span. If it exists, we do a soft flush before
	 * deleting the transient to prevent subsequent flushes. The only times the transient gets created, is if
	 * post types or taxonomies are created, updated, deleted, or imported. Any other time and this condition
	 * should not be met.
	 */
	$flush_it = get_transient( 'cptui_flush_rewrite_rules' );
	if ( 'true' === $flush_it ) {
		flush_rewrite_rules( false );
		// So we only run this once.
		delete_transient( 'cptui_flush_rewrite_rules' );
	}
}
add_action( 'admin_init', 'cptui_flush_rewrite_rules' );

/**
 * Return the current action being done within CPTUI context.
 *
 * @since 1.3.0
 *
 * @return string Current action being done by CPTUI
 */
function cptui_get_current_action() {
	$current_action = '';
	if ( ! empty( $_GET ) && isset( $_GET['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$current_action .= esc_textarea( wp_unslash( $_GET['action'] ) ); // phpcs:ignore
	}

	return $current_action;
}

/**
 * Return an array of all post type slugs from Custom Post Type UI.
 *
 * @since 1.3.0
 *
 * @return array CPTUI post type slugs.
 */
function cptui_get_post_type_slugs() {
	$post_types = get_option( 'cptui_post_types' );
	if ( ! empty( $post_types ) ) {
		return array_keys( $post_types );
	}
	return [];
}

/**
 * Return an array of all taxonomy slugs from Custom Post Type UI.
 *
 * @since 1.3.0
 *
 * @return array CPTUI taxonomy slugs.
 */
function cptui_get_taxonomy_slugs() {
	$taxonomies = get_option( 'cptui_taxonomies' );
	if ( ! empty( $taxonomies ) ) {
		return array_keys( $taxonomies );
	}
	return [];
}

/**
 * Return the appropriate admin URL depending on our context.
 *
 * @since 1.3.0
 *
 * @param string $path URL path.
 * @return string
 */
function cptui_admin_url( $path ) {
	if ( is_multisite() && is_network_admin() ) {
		return network_admin_url( $path );
	}

	return admin_url( $path );
}

/**
 * Construct action tag for `<form>` tag.
 *
 * @since 1.3.0
 *
 * @param object|string $ui CPTUI Admin UI instance. Optional. Default empty string.
 * @return string
 */
function cptui_get_post_form_action( $ui = '' ) {
	/**
	 * Filters the string to be used in an `action=""` attribute.
	 *
	 * @since 1.3.0
	 */
	return apply_filters( 'cptui_post_form_action', '', $ui );
}

/**
 * Display action tag for `<form>` tag.
 *
 * @since 1.3.0
 *
 * @param object $ui CPTUI Admin UI instance.
 */
function cptui_post_form_action( $ui ) {
	echo esc_attr( cptui_get_post_form_action( $ui ) );
}

/**
 * Fetch our CPTUI post types option.
 *
 * @since 1.3.0
 *
 * @return mixed
 */
function cptui_get_post_type_data() {
	return apply_filters( 'cptui_get_post_type_data', get_option( 'cptui_post_types', [] ), get_current_blog_id() );
}

/**
 * Fetch our CPTUI taxonomies option.
 *
 * @since 1.3.0
 *
 * @return mixed
 */
function cptui_get_taxonomy_data() {
	return apply_filters( 'cptui_get_taxonomy_data', get_option( 'cptui_taxonomies', [] ), get_current_blog_id() );
}

/**
 * Checks if a post type is already registered.
 *
 * @since 1.3.0
 *
 * @param string       $slug Post type slug to check. Optional. Default empty string.
 * @param array|string $data Post type data being utilized. Optional.
 * @return mixed
 */
function cptui_get_post_type_exists( $slug = '', $data = [] ) {

	/**
	 * Filters the boolean value for if a post type exists for 3rd parties.
	 *
	 * @since 1.3.0
	 *
	 * @param string       $slug Post type slug to check.
	 * @param array|string $data Post type data being utilized.
	 */
	return apply_filters( 'cptui_get_post_type_exists', post_type_exists( $slug ), $data );
}

/**
 * Checks if a taxonomy is already registered.
 *
 * @since 1.6.0
 *
 * @param string       $slug Taxonomy slug to check. Optional. Default empty string.
 * @param array|string $data Taxonomy data being utilized. Optional.
 *
 * @return mixed
 */
function cptui_get_taxonomy_exists( $slug = '', $data = [] ) {

	/**
	 * Filters the boolean value for if a taxonomy exists for 3rd parties.
	 *
	 * @since 1.6.0
	 *
	 * @param string       $slug Taxonomy slug to check.
	 * @param array|string $data Taxonomy data being utilized.
	 */
	return apply_filters( 'cptui_get_taxonomy_exists', taxonomy_exists( $slug ), $data );
}

/**
 * Displays WebDevStudios products in a sidebar on the add/edit screens for post types and taxonomies.
 *
 * We hope you don't mind.
 *
 * @since 1.3.0
 *
 * @internal
 */
function cptui_products_sidebar() {

	echo '<div class="wdspromos">';

	if ( function_exists( 'cptui_show_pro_promos' ) && cptui_show_pro_promos() ) {
		$context = doing_action( 'cptui_below_taxonomy_tab_menu' ) ? 'taxonomy' : 'post_type';
		cptui_render_pro_sidebar_card( $context );
	}

	// Third-party ads added through the cptui_ads filter still render here.
	foreach ( cptui_get_ads() as $ad ) {
		if ( ! empty( $ad['format'] ) && 'hero' === $ad['format'] ) {
			cptui_render_ad_hero( $ad, 'sidebar' );
		} elseif ( empty( $ad['name'] ) && ! empty( $ad['image'] ) ) {
			cptui_render_ad_compact( $ad, 'sidebar' );
		}
	}

	cptui_newsletter_form();

	if ( function_exists( 'cptui_show_pro_promos' ) && cptui_show_pro_promos() ) {
		printf(
			'<p class="cptui-promo-remove-ads"><a href="%1$s" target="_blank" rel="noopener">%2$s</a></p>',
			esc_url( cptui_pro_url( 'cptui-remove-ads' ) ),
			esc_html__( 'Pro also removes this sidebar', 'custom-post-type-ui' )
		);
	}

	echo '</div>';
}

/**
 * Render the CPT UI Pro sidebar card, matched to the screen and the site.
 *
 * @since 1.19.4
 *
 * @param string $context "post_type" or "taxonomy".
 */
function cptui_render_pro_sidebar_card( $context = 'post_type' ) {
	$pillars = cptui_pro_pillars();
	$key     = cptui_pro_pick_pillar( 'taxonomy' === $context ? 'taxonomy' : '' );
	if ( empty( $pillars[ $key ] ) ) {
		return;
	}
	$pillar = $pillars[ $key ];
	$thumbs = [
		'lists'     => 'lists-after.webp',
		'display'   => 'display-grid.webp',
		'handoff'   => 'access-control.webp',
		'multisite' => '',
	];
	$thumb  = isset( $thumbs[ $key ] ) ? $thumbs[ $key ] : '';
	?>
	<div class="cptui-promo-hero cptui-promo-hero--pro">
		<span class="cptui-promo-hero__badge"><?php esc_html_e( 'CPT UI Pro', 'custom-post-type-ui' ); ?></span>
		<h3 class="cptui-promo-hero__title"><?php echo esc_html( $pillar['title'] ); ?></h3>
		<?php if ( $thumb ) : ?>
			<button type="button" class="cptui-promo-hero__shot" data-cptui-pro-tour="<?php echo esc_attr( $key ); ?>">
				<img src="<?php echo esc_url( cptui_pro_asset_url( $thumb ) ); ?>" alt="<?php echo esc_attr( $pillar['alt'] ); ?>" loading="lazy">
				<span class="cptui-promo-hero__play"><?php esc_html_e( 'See it in action', 'custom-post-type-ui' ); ?></span>
			</button>
		<?php endif; ?>
		<p class="cptui-promo-hero__tagline"><?php echo esc_html( $pillar['pro'] ); ?></p>
		<p class="cptui-promo-hero__also-heading"><?php esc_html_e( 'Also in Pro', 'custom-post-type-ui' ); ?></p>
		<ul class="cptui-promo-hero__also">
			<?php foreach ( $pillars as $other_key => $other ) : ?>
				<?php
				if ( $other_key === $key ) {
					continue;
				}
				?>
				<li>
					<button type="button" class="cptui-promo-hero__also-item" data-cptui-pro-tour="<?php echo esc_attr( $other_key ); ?>">
						<span class="dashicons <?php echo esc_attr( $other['icon'] ); ?>" aria-hidden="true"></span>
						<span class="cptui-promo-hero__also-text">
							<span class="cptui-promo-hero__also-label"><?php echo esc_html( $other['label'] ); ?></span>
							<span class="cptui-promo-hero__also-short"><?php echo esc_html( $other['short'] ); ?></span>
						</span>
						<span class="dashicons dashicons-arrow-right-alt2 cptui-promo-hero__also-chevron" aria-hidden="true"></span>
					</button>
				</li>
			<?php endforeach; ?>
		</ul>
		<a class="cptui-promo-hero__cta" href="<?php echo esc_url( cptui_pro_url( 'cptui-sidebar', $pillar['section'] ) ); ?>" target="_blank" rel="noopener">
			<?php esc_html_e( 'Get CPT UI Pro', 'custom-post-type-ui' ); ?>
		</a>
		<p class="cptui-promo-hero__guarantee"><?php esc_html_e( '30-day money-back guarantee', 'custom-post-type-ui' ); ?></p>
	</div>
	<?php
}
add_action( 'cptui_below_post_type_tab_menu', 'cptui_products_sidebar' );
add_action( 'cptui_below_taxonomy_tab_menu', 'cptui_products_sidebar' );

/**
 * Fetch all set ads to be displayed.
 *
 * @since 1.3.4
 *
 * @return array
 */
function cptui_get_ads() {

	/**
	 * Filters the array of ads to iterate over.
	 *
	 * Each index in the ads array should have a url index with the url to link to,
	 * an image index specifying an image location to load from, and a text index used
	 * for alt attribute text.
	 *
	 * @since 1.3.4
	 *
	 * @param array $value Array of ads to iterate over. Default empty.
	 */
	return (array) apply_filters( 'cptui_ads', [] );
}

/**
 * Add our default ads to the ads filter.
 *
 * @since 1.3.4
 *
 * @internal
 *
 * @param array $ads Array of ads set so far. Optional.
 * @return array $ads Array of newly constructed ads.
 */
function cptui_default_ads( $ads = [] ) {
	$ads[] = [
		'format'    => 'compact',
		'slug'      => 'themeswitcher-pro',
		'name'      => 'ThemeSwitcher Pro',
		'tagline'   => esc_html__( 'Run multiple themes on one WordPress site. Start creating new content with the Gutenberg block editor on your legacy WordPress site without replacing your active theme.', 'custom-post-type-ui' ),
		'url'       => 'https://themeswitcher.com/?utm_source=cptui-about&utm_medium=plugin&utm_campaign=themeswitcher-pro',
		'cta_label' => esc_html__( 'Learn more', 'custom-post-type-ui' ),
	];

	$ads[] = [
		'format'    => 'compact',
		'slug'      => 'wp-search-with-algolia',
		'name'      => 'WP Search with Algolia Pro',
		'tagline'   => esc_html__( 'AI-powered, lightning-fast search for WordPress. Full WooCommerce support — indexes product SKU and pricing data for both simple and variable products.', 'custom-post-type-ui' ),
		'url'       => 'https://pluginize.com/plugins/wp-search-with-algolia-pro/?utm_source=cptui-about&utm_medium=plugin&utm_campaign=wp-search-with-algolia-pro',
		'cta_label' => esc_html__( 'Learn more', 'custom-post-type-ui' ),
	];

	$ads[] = [
		'format'    => 'compact',
		'slug'      => 'youtube-video-sync',
		'name'      => 'WDS YouTube Video Sync for WP',
		'tagline'   => esc_html__( 'Connect your YouTube channel and import videos as native WordPress posts — titles, descriptions, featured images, and embed-ready, with daily auto-sync or manual triggers.', 'custom-post-type-ui' ),
		'url'       => 'https://pluginize.com/plugins/youtube-video-sync-for-wp/?utm_source=cptui-about&utm_medium=plugin&utm_campaign=youtube-video-sync-for-wp',
		'cta_label' => esc_html__( 'Learn more', 'custom-post-type-ui' ),
	];

	return $ads;
}
add_filter( 'cptui_ads', 'cptui_default_ads' );

/**
 * Adjust an ad URL's utm_source for the placement it's being rendered in.
 *
 * @since 1.19.1
 *
 * @param string $url       Original ad URL.
 * @param string $placement Placement key. Either 'sidebar' or 'about'.
 * @return string
 */
function cptui_promo_url_for_placement( $url, $placement ) {
	if ( 'about' !== $placement ) {
		return $url;
	}
	return add_query_arg( 'utm_source', 'cptui-about', remove_query_arg( 'utm_source', $url ) );
}

/**
 * Render the large CPT UI Pro hero promo card.
 *
 * @since 1.19.1
 *
 * @param array  $ad        Ad data.
 * @param string $placement Placement context. Either 'sidebar' or 'about'.
 */
function cptui_render_ad_hero( $ad, $placement = 'sidebar' ) {
	if ( empty( $ad['name'] ) || empty( $ad['url'] ) ) {
		return;
	}

	$url       = cptui_promo_url_for_placement( $ad['url'], $placement );
	$cta_label = isset( $ad['cta_label'] ) ? $ad['cta_label'] : __( 'Learn more', 'custom-post-type-ui' );
	?>
	<div class="cptui-promo-hero">
		<span class="cptui-promo-hero__badge"><?php esc_html_e( 'Pro', 'custom-post-type-ui' ); ?></span>
		<h3 class="cptui-promo-hero__title"><?php echo esc_html( $ad['name'] ); ?></h3>
		<?php if ( ! empty( $ad['tagline'] ) ) : ?>
			<p class="cptui-promo-hero__tagline"><?php echo esc_html( $ad['tagline'] ); ?></p>
		<?php endif; ?>
		<?php if ( ! empty( $ad['features'] ) && is_array( $ad['features'] ) ) : ?>
			<ul class="cptui-promo-hero__features">
				<?php foreach ( $ad['features'] as $feature ) : ?>
					<?php if ( is_array( $feature ) ) : ?>
						<li>
							<span class="cptui-promo-hero__feature-title"><?php echo esc_html( $feature['title'] ); ?></span>
							<?php if ( ! empty( $feature['detail'] ) ) : ?>
								<span class="cptui-promo-hero__feature-detail"><?php echo esc_html( $feature['detail'] ); ?></span>
							<?php endif; ?>
						</li>
					<?php else : ?>
						<li><span class="cptui-promo-hero__feature-title"><?php echo esc_html( $feature ); ?></span></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ( ! empty( $ad['guarantee'] ) ) : ?>
			<p class="cptui-promo-hero__guarantee"><?php echo esc_html( $ad['guarantee'] ); ?></p>
		<?php endif; ?>
		<a class="cptui-promo-hero__cta" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
			<?php echo esc_html( $cta_label ); ?>
		</a>
	</div>
	<?php
}

/**
 * Render a compact secondary product promo card.
 *
 * Falls back to legacy image rendering for any third-party-filtered ads
 * that still use the old [ url, image, text ] shape.
 *
 * @since 1.19.1
 *
 * @param array  $ad        Ad data.
 * @param string $placement Placement context. Either 'sidebar' or 'about'.
 */
function cptui_render_ad_compact( $ad, $placement = 'sidebar' ) {
	// Legacy image fallback for third-party filter additions to cptui_ads.
	if ( empty( $ad['name'] ) && ! empty( $ad['image'] ) ) {
		printf(
			'<p><a href="%1$s" target="_blank" rel="noopener"><img src="%2$s" alt="%3$s"></a></p>',
			esc_url( isset( $ad['url'] ) ? $ad['url'] : '' ),
			esc_attr( $ad['image'] ),
			esc_attr( isset( $ad['text'] ) ? $ad['text'] : '' )
		);
		return;
	}

	if ( empty( $ad['name'] ) || empty( $ad['url'] ) ) {
		return;
	}

	$url       = cptui_promo_url_for_placement( $ad['url'], $placement );
	$cta_label = isset( $ad['cta_label'] ) ? $ad['cta_label'] : __( 'Learn more', 'custom-post-type-ui' );
	?>
	<div class="cptui-promo-card">
		<h4 class="cptui-promo-card__title"><?php echo esc_html( $ad['name'] ); ?></h4>
		<?php if ( ! empty( $ad['tagline'] ) ) : ?>
			<p class="cptui-promo-card__tagline"><?php echo esc_html( $ad['tagline'] ); ?></p>
		<?php endif; ?>
		<a class="cptui-promo-card__cta" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
			<?php echo esc_html( $cta_label ); ?> <span aria-hidden="true">&rarr;</span>
		</a>
	</div>
	<?php
}

/**
 * Secondary admin notices function for use with admin_notices hook.
 *
 * Constructs admin notice HTML.
 *
 * @since 1.4.0
 *
 * @param string $message Message to use in admin notice. Optional. Default empty string.
 * @param bool   $success Whether or not a success. Optional. Default true.
 */
function cptui_admin_notices_helper( $message = '', $success = true, $success_override_type = false ) {

	$class   = [];
	$class[] = $success ? 'updated' : 'error';

	$messagewrapstart = '<div id="message" class="' . implode( ' ', $class ) . '"><p>';
	$messagewrapend = '</p></div>';

	/**
	 * Filters the custom admin notice for CPTUI.
	 *
	 * @deprecated
	 *
	 * @param string $value            Complete HTML output for notice.
	 * @param string $action           Action whose message is being generated.
	 * @param string $message          The message to be displayed.
	 * @param string $messagewrapstart Beginning wrap HTML.
	 * @param string $messagewrapend   Ending wrap HTML.
	 *
	 * @since 1.0.0
	 */
	$notice = apply_filters_deprecated(
		'cptui_admin_notice',
		[ $messagewrapstart . $message . $messagewrapend, '', $message, $messagewrapstart, $messagewrapend ],
		'1.18.3',
		'',
		esc_html__( 'No filter replacement. Deprecated', 'custom-post-type-ui' )
	);

	$type = $success ? 'success' : 'error';
	if ( ! empty( $success_override_type ) ) {
		$type = $success_override_type;
	}

	wp_admin_notice(
		$message,
		[
			'id'          => 'cptui-message',
			'type'        => $type,
			'dismissible' => true,
		]
	);
}

/**
 * Grab post type or taxonomy slug from $_POST global, if available.
 *
 * @since 1.4.0
 *
 * @internal
 *
 * @return string
 */
function cptui_get_object_from_post_global() {
	if ( isset( $_POST['cpt_custom_post_type']['name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$type_item = filter_input( INPUT_POST, 'cpt_custom_post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );
		if ( $type_item ) {
			return sanitize_text_field( $type_item['name'] );
		}
	}

	if ( isset( $_POST['cpt_custom_tax']['name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$tax_item = filter_input( INPUT_POST, 'cpt_custom_tax', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );
		if ( $tax_item ) {
			return sanitize_text_field( $tax_item['name'] );
		}
	}

	return esc_html__( 'Object', 'custom-post-type-ui' );
}

/**
 * Successful add callback.
 *
 * @since 1.4.0
 */
function cptui_add_success_admin_notice() {
	cptui_admin_notices_helper( // phpcs:ignore WordPress.Security.EscapeOutput
		sprintf(
			/* translators: Placeholders are just for HTML markup that doesn't need translated */
			esc_html__( '%s has been successfully added', 'custom-post-type-ui' ),
			cptui_get_object_from_post_global()
		)
	);
}

/**
 * Fail to add callback.
 *
 * @since 1.4.0
 */
function cptui_add_fail_admin_notice() {
	cptui_admin_notices_helper( // phpcs:ignore WordPress.Security.EscapeOutput
		sprintf(
			/* translators: Placeholders are just for HTML markup that doesn't need translated */
			esc_html__( '%s has failed to be added', 'custom-post-type-ui' ),
			cptui_get_object_from_post_global()
		),
		false
	);
}

/**
 * Successful update callback.
 *
 * @since 1.4.0
 */
function cptui_update_success_admin_notice() {
	cptui_admin_notices_helper( // phpcs:ignore WordPress.Security.EscapeOutput
		sprintf(
			/* translators: Placeholders are just for HTML markup that doesn't need translated */
			esc_html__( '%s has been successfully updated', 'custom-post-type-ui' ),
			cptui_get_object_from_post_global()
		)
	);
}

/**
 * Fail to update callback.
 *
 * @since 1.4.0
 */
function cptui_update_fail_admin_notice() {
	cptui_admin_notices_helper( // phpcs:ignore WordPress.Security.EscapeOutput
		sprintf(
			/* translators: Placeholders are just for HTML markup that doesn't need translated */
			esc_html__( '%s has failed to be updated', 'custom-post-type-ui' ),
			cptui_get_object_from_post_global()
		),
		false
	);
}

/**
 * Successful delete callback.
 *
 * @since 1.4.0
 */
function cptui_delete_success_admin_notice() {
	cptui_admin_notices_helper( // phpcs:ignore WordPress.Security.EscapeOutput
		sprintf(
			/* translators: Placeholders are just for HTML markup that doesn't need translated */
			esc_html__( '%s has been successfully deleted', 'custom-post-type-ui' ),
			cptui_get_object_from_post_global()
		)
	);
}

/**
 * Fail to delete callback.
 *
 * @since 1.4.0
 */
function cptui_delete_fail_admin_notice() {
	cptui_admin_notices_helper( // phpcs:ignore WordPress.Security.EscapeOutput
		sprintf(
			/* translators: Placeholders are just for HTML markup that doesn't need translated */
			esc_html__( '%s has failed to be deleted', 'custom-post-type-ui' ),
			cptui_get_object_from_post_global()
		),
		false
	);
}

/**
 * Success to import callback.
 *
 * @since 1.5.0
 */
function cptui_import_success_admin_notice() {
	cptui_admin_notices_helper( // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html__( 'Successfully imported data.', 'custom-post-type-ui' )
	);
}

/**
 * Failure to import callback.
 *
 * @since 1.5.0
 */
function cptui_import_fail_admin_notice() {
	cptui_admin_notices_helper( // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html__( 'Invalid data provided', 'custom-post-type-ui' ),
		false
	);
}

/**
 * Failure to verify nonce, callback
 *
 * @since 1.7.4
 */
function cptui_nonce_fail_admin_notice() {
	cptui_admin_notices_helper( // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html__( 'Nonce failed verification', 'custom-post-type-ui' ),
		false
	);
}

/**
 * Returns error message for if trying to register existing post type.
 *
 * @since 1.4.0
 *
 * @return string
 */
function cptui_slug_matches_post_type() {
	return sprintf(
		/* translators: Placeholders are just for HTML markup that doesn't need translated */
		esc_html__( 'Please choose a different post type name. %s is already registered.', 'custom-post-type-ui' ),
		cptui_get_object_from_post_global()
	);
}

/**
 * Returns error message for if trying to register existing taxonomy.
 *
 * @since 1.4.0
 *
 * @return string
 */
function cptui_slug_matches_taxonomy() {
	return sprintf(
		/* translators: Placeholders are just for HTML markup that doesn't need translated */
		esc_html__( 'Please choose a different taxonomy name. %s is already registered.', 'custom-post-type-ui' ),
		cptui_get_object_from_post_global()
	);
}

/**
 * Returns error message for if not providing a post type to associate taxonomy to.
 *
 * @since 1.6.0
 *
 * @return string
 */
function cptui_empty_cpt_on_taxonomy() {
	return esc_html__( 'Please provide a post type to attach to.', 'custom-post-type-ui' );
}

/**
 * Returns error message for if trying to register post type with matching page slug.
 *
 * @since 1.4.0
 *
 * @return string
 */
function cptui_slug_matches_page() {
	$slug         = cptui_get_object_from_post_global();
	$matched_slug = get_page_by_path(
		cptui_get_object_from_post_global()
	);
	if ( $matched_slug instanceof WP_Post ) {
		$slug = sprintf(
			/* translators: Placeholders are just for HTML markup that doesn't need translated */
			'<a href="%s">%s</a>',
			get_edit_post_link( $matched_slug->ID ),
			cptui_get_object_from_post_global()
		);
	}

	return sprintf(
		/* translators: Placeholders are just for HTML markup that doesn't need translated */
		esc_html__( 'Please choose a different post type name. %s matches an existing page slug, which can cause conflicts.', 'custom-post-type-ui' ),
		$slug
	);
}

/**
 * Returns error message for if trying to use quotes in slugs or rewrite slugs.
 *
 * @since 1.4.0
 *
 * @return string
 */
function cptui_slug_has_quotes() {
	return sprintf(
		esc_html__( 'Please do not use quotes in post type/taxonomy names or rewrite slugs', 'custom-post-type-ui' ),
		cptui_get_object_from_post_global()
	);
}

/**
 * Error admin notice.
 *
 * @since 1.4.0
 */
function cptui_error_admin_notice() {
	cptui_admin_notices_helper( // phpcs:ignore WordPress.Security.EscapeOutput
		apply_filters( 'cptui_custom_error_message', '' ),
		false
	);
}

/**
 * Mark site as not a new CPTUI install upon update to 1.5.0
 *
 * @since 1.5.0
 *
 * @param object $wp_upgrader WP_Upgrader instance.
 * @param array  $extras      Extra information about performed upgrade.
 */
function cptui_not_new_install( $wp_upgrader, $extras ) {

	if ( $wp_upgrader instanceof \Plugin_Upgrader ) {
		return;
	}

	if ( ! array_key_exists( 'plugins', $extras ) || ! is_array( $extras['plugins'] ) ) {
		return;
	}

	// Was CPTUI updated?
	if ( ! in_array( 'custom-post-type-ui/custom-post-type-ui.php', $extras['plugins'], true ) ) {
		return;
	}

	// If we are already known as not new, return.
	if ( cptui_is_new_install() ) {
		return;
	}

	// We need to mark ourselves as not new.
	cptui_set_not_new_install();
}
add_action( 'upgrader_process_complete', 'cptui_not_new_install', 10, 2 );

/**
 * Check whether or not we're on a new install.
 *
 * @since 1.5.0
 *
 * @return bool
 */
function cptui_is_new_install() {
	$new_or_not = true;
	$saved      = get_option( 'cptui_new_install', '' );

	if ( 'false' === $saved ) {
		$new_or_not = false;
	}

	/**
	 * Filters the new install status.
	 *
	 * Offers third parties the ability to override if they choose to.
	 *
	 * @since 1.5.0
	 *
	 * @param bool $new_or_not Whether or not site is a new install.
	 */
	return (bool) apply_filters( 'cptui_is_new_install', $new_or_not );
}

/**
 * Set our activation status to not new.
 *
 * @since 1.5.0
 */
function cptui_set_not_new_install() {
	update_option( 'cptui_new_install', 'false' );
}

/**
 * Returns saved values for single post type from CPTUI settings.
 *
 * @since 1.5.0
 *
 * @param string $post_type Post type to retrieve CPTUI object for.
 * @return string
 */
function cptui_get_cptui_post_type_object( $post_type = '' ) {
	$post_types = get_option( 'cptui_post_types', [] );

	if ( is_array( $post_types ) && array_key_exists( $post_type, $post_types ) ) {
		return $post_types[ $post_type ];
	}
	return [];
}

/**
 * Returns saved values for single taxonomy from CPTUI settings.
 *
 * @since 1.5.0
 *
 * @param string $taxonomy Taxonomy to retrieve CPTUI object for.
 * @return string
 */
function cptui_get_cptui_taxonomy_object( $taxonomy = '' ) {
	$taxonomies = get_option( 'cptui_taxonomies', [] );

	if ( is_array( $taxonomies ) && array_key_exists( $taxonomy, $taxonomies ) ) {
		return $taxonomies[ $taxonomy ];
	}
	return [];
}

/**
 * Checks if a requested post type has a custom CPTUI feature supported.
 *
 * @since 1.5.0
 *
 * @param string $post_type Post type slug.
 * @param string $feature   Feature to check for.
 * @return bool
 */
function cptui_post_type_supports( $post_type, $feature ) {

	$object = cptui_get_cptui_post_type_object( $post_type );

	if ( ! empty( $object ) ) {
		if ( array_key_exists( $feature, $object ) && ! empty( $object[ $feature ] ) ) {
			return true;
		}

		return false;
	}

	return false;
}

/**
 * Add missing post_format taxonomy support for CPTUI post types.
 *
 * Addresses bug wih previewing changes for published posts with post types that
 * have post-formats support.
 *
 * @since 1.5.8
 *
 * @param array $post_types Array of CPTUI post types.
 */
function cptui_published_post_format_fix( $post_types ) {
	if ( empty( $post_types ) || ! is_array( $post_types ) ) {
		return;
	}

	foreach ( $post_types as $type ) {
		if ( ! is_array( $type['supports'] ) ) {
			continue;
		}

		if ( in_array( 'post-formats', $type['supports'], true ) ) {
			add_post_type_support( $type['name'], 'post-formats' );
			register_taxonomy_for_object_type( 'post_format', $type['name'] );
		}
	}
}
add_action( 'cptui_post_register_post_types', 'cptui_published_post_format_fix' );

/**
 * Return a ready-to-use admin url for adding a new content type.
 *
 * @since 1.7.0
 *
 * @param string $content_type Content type to link to.
 * @return string
 */
function cptui_get_add_new_link( $content_type = '' ) {
	if ( ! in_array( $content_type, [ 'post_types', 'taxonomies' ], true ) ) {
		return cptui_admin_url( 'admin.php?page=cptui_manage_post_types' );
	}

	return cptui_admin_url( 'admin.php?page=cptui_manage_' . $content_type );
}

/**
 * Register theme support for CPTUI based content types, for extra assurance.
 *
 * @since 1.14.0
 */
function cptui_post_thumbnail_theme_support() {
	$post_types = cptui_get_post_type_data();

	if ( empty( $post_types ) || ! is_array( $post_types ) ) {
		return;
	}

	$supported = [];
	foreach ( $post_types as $post_type ) {
		if ( empty( $post_type['supports'] ) ) {
			continue;
		}
		if (
			// Some way, somehow, null can end up saved in the supports spot.
			// Lets check for an array first.
			is_array( $post_type['supports'] ) &&
			in_array( 'thumbnail', $post_type['supports'] )
		) {
			$supported[] = $post_type['name'];
		}
	}
	if ( ! empty( $supported ) ) {
		add_theme_support( 'post-thumbnails', $supported );
	}
}
add_action( 'after_setup_theme', 'cptui_post_thumbnail_theme_support' );

function cptui_add_dialog_missing_post_type_confirm() {
?>
	<dialog id="cptui-select-post-type-confirm">
		<p><?php esc_html_e( 'Please select a post type to associate with.', 'custom-post-type-ui' ); ?></p>
		<button id="cptui-select-post-type-confirm-close" class="confirm button-secondary" type="button"><?php esc_html_e( 'OK', 'custom-post-type-ui' ); ?></button>
	</dialog>
<?php
}
add_action( 'cptui_taxonomy_after_fieldsets', 'cptui_add_dialog_missing_post_type_confirm' );

function cptui_add_dialog_delete_content_type_confirm() {
	?>
	<dialog id="cptui-content-type-delete" class="wp-core-ui">
		<p><?php esc_html_e( 'Are you sure you want to delete this? Deleting will NOT remove created content.', 'custom-post-type-ui' ); ?></p>
		<div class="cptui-confirm-deny-delete">
			<button id="cptui-content-type-confirm-delete" class="confirm button-secondary" type="button"><?php esc_html_e( 'OK', 'custom-post-type-ui' ); ?></button>
			<button id="cptui-content-type-deny-delete" class="confirm button-secondary" type="button"><?php esc_html_e( 'Cancel', 'custom-post-type-ui' ); ?></button>
		</div>
	</dialog>
	<?php
}
#add_action( 'cptui_post_type_after_fieldsets', 'cptui_add_dialog_delete_content_type_confirm' );
#add_action( 'cptui_taxonomy_after_fieldsets', 'cptui_add_dialog_delete_content_type_confirm' );
