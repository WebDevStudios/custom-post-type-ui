<?php
/**
 * Custom Post Type UI Pro promotion.
 *
 * Everything the free plugin does to introduce CPT UI Pro lives here: the
 * shared URL and gating helpers, the "Get Pro" menu page, the feature tour
 * dialog, contextual notices and the block editor panel.
 *
 * Nothing here locks or disables free functionality. Every promo is hidden
 * when CPT UI Pro (or the legacy CPTUI Extended) is active, and can be turned
 * off entirely with the `cptui_show_pro_promos` filter.
 *
 * @package CPTUI
 * @subpackage Pro
 * @author WebDevStudios
 * @since 1.19.4
 * @license GPL-2.0+
 */

// phpcs:disable WebDevStudios.All.RequireAuthor
// phpcs:disable WordPress.Security.NonceVerification.Recommended

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * -------------------------------------------------------------------------
 * Helpers
 * -------------------------------------------------------------------------
 */

/**
 * Whether CPT UI Pro, or the legacy CPTUI Extended, is active.
 *
 * @since 1.19.4
 *
 * @return bool
 */
function cptui_is_pro_active() {
	$active = class_exists( 'CPTUI_Pro' ) || class_exists( 'CPTUI_Extended' );

	/**
	 * Filters whether CPT UI Pro is considered active.
	 *
	 * @since 1.19.4
	 *
	 * @param bool $active Whether CPT UI Pro or CPTUI Extended is active.
	 */
	return (bool) apply_filters( 'cptui_is_pro_active', $active );
}

/**
 * Whether CPT UI Pro promotions should be shown to the current user.
 *
 * @since 1.19.4
 *
 * @return bool
 */
function cptui_show_pro_promos() {
	if ( cptui_is_pro_active() ) {
		return false;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	/**
	 * Filters whether CPT UI Pro promotions are shown at all.
	 *
	 * Return false to remove every Pro promotion from the admin.
	 *
	 * @since 1.19.4
	 *
	 * @param bool $show Whether to show Pro promotions. Default true.
	 */
	return (bool) apply_filters( 'cptui_show_pro_promos', true );
}

/**
 * Build a tracked link to the CPT UI Pro page on pluginize.com.
 *
 * Every Pro link in the plugin goes through this so tracking stays
 * consistent: utm_source names the placement, utm_medium is always
 * "plugin" and utm_campaign is always "cptui".
 *
 * @since 1.19.4
 *
 * @param string $placement Where the link appears, for example "cptui-sidebar".
 * @param string $section   Optional section anchor on the Pro page: admin, handoff, display, multisite, compare or pricing.
 * @return string
 */
function cptui_pro_url( $placement = 'cptui', $section = '' ) {
	$url = add_query_arg(
		[
			'utm_source'   => sanitize_key( $placement ),
			'utm_medium'   => 'plugin',
			'utm_campaign' => 'cptui',
		],
		'https://pluginize.com/plugins/custom-post-type-ui-pro/'
	);

	if ( ! empty( $section ) ) {
		$url .= '#' . sanitize_key( $section );
	}

	/**
	 * Filters a CPT UI Pro link.
	 *
	 * @since 1.19.4
	 *
	 * @param string $url       Full URL.
	 * @param string $placement Placement key.
	 * @param string $section   Section anchor.
	 */
	return apply_filters( 'cptui_pro_url', $url, $placement, $section );
}

/**
 * URL of the in-plugin "Get Pro" comparison page.
 *
 * @since 1.19.4
 *
 * @param string $section Optional anchor on the page.
 * @return string
 */
function cptui_pro_page_url( $section = '' ) {
	$url = cptui_admin_url( 'admin.php?page=cptui_upgrade' );
	if ( ! empty( $section ) ) {
		$url .= '#' . sanitize_key( $section );
	}
	return $url;
}

/**
 * URL of a bundled Pro marketing image or video in images/pro/.
 *
 * @since 1.19.4
 *
 * @param string $file File name.
 * @return string
 */
function cptui_pro_asset_url( $file ) {
	return plugin_dir_url( __DIR__ ) . 'images/pro/' . $file;
}

/**
 * Feature groups ("pillars") used by the tour dialog, the Pro page and the About page.
 *
 * @since 1.19.4
 *
 * @return array
 */
function cptui_pro_pillars() {
	$pillars = [
		'lists'     => [
			'label'    => __( 'Admin lists', 'custom-post-type-ui' ),
			'short'    => __( 'Your fields as list columns', 'custom-post-type-ui' ),
			'icon'     => 'dashicons-columns',
			'title'    => __( 'See every field without opening a post', 'custom-post-type-ui' ),
			'today'    => __( 'Every post type gets the same Title / Author / Date list, so editors open entries one at a time to check a price, a date or an image.', 'custom-post-type-ui' ),
			'pro'      => __( 'Pick the columns and filters for each post type. ACF fields show as thumbnails, names, dates and labels, not IDs.', 'custom-post-type-ui' ),
			'bullets'  => [
				__( 'Column builder for any core field, taxonomy or custom field', 'custom-post-type-ui' ),
				__( 'Filter by text, numbers, dates, terms, author and status', 'custom-post-type-ui' ),
				__( 'Columns and filters on taxonomy term screens too', 'custom-post-type-ui' ),
			],
			'video'    => 'columns-loop.mp4',
			'poster'   => 'columns-loop-poster.webp',
			'alt'      => __( 'Adding custom fields as list columns with the CPT UI Pro column builder.', 'custom-post-type-ui' ),
			'section'  => 'admin',
		],
		'display'   => [
			'label'    => __( 'Display', 'custom-post-type-ui' ),
			'short'    => __( 'Show posts on any page', 'custom-post-type-ui' ),
			'icon'     => 'dashicons-grid-view',
			'title'    => __( 'Show any post type on your site without templates', 'custom-post-type-ui' ),
			'today'    => __( 'Free CPT UI registers your post types but does not display them. Showing them means writing a theme template or custom loop for each one.', 'custom-post-type-ui' ),
			'pro'      => __( 'Add a block, pick a layout, done. Override any layout in your theme when you need custom markup.', 'custom-post-type-ui' ),
			'bullets'  => [
				__( 'Block and shortcode with 13 layouts: grid, slider, cards, lists and more', 'custom-post-type-ui' ),
				__( 'Add post types to blog, category and tag archives and RSS', 'custom-post-type-ui' ),
				__( 'WooCommerce and Easy Digital Downloads layouts', 'custom-post-type-ui' ),
			],
			'image'    => 'display-grid.webp',
			'alt'      => __( 'Listings shown on the front end with the CPT UI Pro grid layout.', 'custom-post-type-ui' ),
			'section'  => 'display',
		],
		'handoff'   => [
			'label'    => __( 'Client handoff', 'custom-post-type-ui' ),
			'short'    => __( 'Hide CPT UI from clients', 'custom-post-type-ui' ),
			'icon'     => 'dashicons-lock',
			'title'    => __( 'Give clients admin access. Keep the structure to yourself.', 'custom-post-type-ui' ),
			'today'    => __( 'Every administrator can open CPT UI and rename or delete a post type. One wrong click and the content disappears from the site.', 'custom-post-type-ui' ),
			'pro'      => __( 'Choose which admins can manage CPT UI. Everyone else never sees the menu, and the Pro menu can carry your name and icon.', 'custom-post-type-ui' ),
			'bullets'  => [
				__( 'Access control for CPT UI and CPT UI Pro', 'custom-post-type-ui' ),
				__( 'You cannot lock yourself out', 'custom-post-type-ui' ),
				__( 'Menu branding with your own name and icon', 'custom-post-type-ui' ),
			],
			'image'    => 'access-control.webp',
			'alt'      => __( 'The CPT UI Pro Access Control screen, limiting CPT UI to two named administrators.', 'custom-post-type-ui' ),
			'section'  => 'handoff',
		],
		'multisite' => [
			'label'    => __( 'Multisite', 'custom-post-type-ui' ),
			'short'    => __( 'One setup for every site', 'custom-post-type-ui' ),
			'icon'     => 'dashicons-networking',
			'title'    => __( 'Define post types once for the whole network', 'custom-post-type-ui' ),
			'today'    => __( 'You recreate the same post types on every subsite by hand, and over time they drift apart.', 'custom-post-type-ui' ),
			'pro'      => __( 'Register post types and taxonomies in Network Admin. Every site gets them automatically and nobody can change them site by site.', 'custom-post-type-ui' ),
			'bullets'  => [
				__( 'Network-wide post types and taxonomies', 'custom-post-type-ui' ),
				__( 'New and existing sites pick them up with no setup', 'custom-post-type-ui' ),
				__( 'Sites can still add their own types alongside', 'custom-post-type-ui' ),
			],
			'section'  => 'multisite',
		],
	];

	/**
	 * Filters the CPT UI Pro feature groups shown in the plugin.
	 *
	 * @since 1.19.4
	 *
	 * @param array $pillars Feature groups keyed by slug.
	 */
	return (array) apply_filters( 'cptui_pro_pillars', $pillars );
}

/**
 * Pick the Pro feature group most likely to matter on this site.
 *
 * Multisite installs hear about network registration, sites using ACF or with
 * a busy post type hear about admin columns, everyone else hears about display.
 *
 * @since 1.19.4
 *
 * @param string $context Optional. "taxonomy" to prefer the admin lists pitch.
 * @return string Pillar key.
 */
function cptui_pro_pick_pillar( $context = '' ) {
	if ( is_multisite() ) {
		$pick = 'multisite';
	} elseif ( 'taxonomy' === $context || class_exists( 'ACF' ) || cptui_pro_has_busy_post_type() ) {
		$pick = 'lists';
	} else {
		$pick = 'display';
	}

	/**
	 * Filters which Pro feature group is pitched first.
	 *
	 * @since 1.19.4
	 *
	 * @param string $pick    Pillar key.
	 * @param string $context Context passed by the caller.
	 */
	return (string) apply_filters( 'cptui_pro_pick_pillar', $pick, $context );
}

/**
 * Whether any CPT UI post type has enough entries that list columns pay off.
 *
 * @since 1.19.4
 *
 * @return bool
 */
function cptui_pro_has_busy_post_type() {
	/**
	 * Filters the number of published entries that makes a post type "busy".
	 *
	 * @since 1.19.4
	 *
	 * @param int $threshold Default 50.
	 */
	$threshold = (int) apply_filters( 'cptui_pro_busy_post_type_threshold', 50 );

	foreach ( cptui_get_post_type_slugs() as $slug ) {
		if ( ! post_type_exists( $slug ) ) {
			continue;
		}
		$counts = wp_count_posts( $slug );
		if ( isset( $counts->publish ) && (int) $counts->publish >= $threshold ) {
			return true;
		}
	}

	return false;
}

/**
 * Whether the current user has snoozed Pro notices.
 *
 * Kept under its 1.18.3 name for backward compatibility.
 *
 * @since 1.18.3
 * @since 1.19.4 Dismissals are a per-user snooze instead of a permanent flag reset on every update.
 *
 * @return bool
 */
function cptui_user_dismissed_pro_upsell() {
	$until = (int) get_user_meta( get_current_user_id(), 'cptui_pro_promo_snoozed_until', true );
	return $until > time();
}

/**
 * Snooze Pro notices for the current user.
 *
 * @since 1.19.4
 */
function cptui_snooze_pro_promos() {
	/**
	 * Filters how many days a dismissed Pro notice stays hidden.
	 *
	 * @since 1.19.4
	 *
	 * @param int $days Default 90.
	 */
	$days = (int) apply_filters( 'cptui_pro_promo_snooze_days', 90 );
	update_user_meta( get_current_user_id(), 'cptui_pro_promo_snoozed_until', time() + ( $days * DAY_IN_SECONDS ) );
}

/**
 * URL that snoozes Pro notices and returns to the current screen.
 *
 * @since 1.19.4
 *
 * @return string
 */
function cptui_pro_snooze_url() {
	return add_query_arg(
		[
			'cptui-action'        => 'cptui-dismiss',
			'cptui-dismiss-nonce' => wp_create_nonce( 'cptui-dismiss-nonce' ),
		]
	);
}

/**
 * Handle the "Dismiss" link on Pro notices.
 *
 * @since 1.18.3
 */
function cptui_handle_upsell_dismissal() {
	if ( empty( $_GET['cptui-dismiss-nonce'] ) || ! current_user_can( 'manage_options' ) || wp_doing_ajax() ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['cptui-dismiss-nonce'] ) ), 'cptui-dismiss-nonce' ) ) {
		return;
	}

	cptui_snooze_pro_promos();

	wp_safe_redirect( remove_query_arg( [ 'cptui-action', 'cptui-dismiss-nonce' ] ) );
	exit;
}
add_action( 'admin_init', 'cptui_handle_upsell_dismissal' );

/**
 * Whether the current admin screen is a list or editor screen for a CPT UI post type.
 *
 * @since 1.19.4
 *
 * @param string $base Screen base to match, "edit" or "post".
 * @return string Post type slug, or empty string.
 */
function cptui_pro_current_cptui_post_type( $base = 'edit' ) {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! is_object( $screen ) || $base !== $screen->base || empty( $screen->post_type ) ) {
		return '';
	}
	return in_array( $screen->post_type, cptui_get_post_type_slugs(), true ) ? $screen->post_type : '';
}

/**
 * Whether the current screen is one of CPT UI's own admin pages.
 *
 * @since 1.19.4
 *
 * @param string $hook Admin page hook suffix.
 * @return bool
 */
function cptui_pro_is_cptui_screen( $hook = '' ) {
	if ( empty( $hook ) ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		$hook   = is_object( $screen ) ? $screen->id : '';
	}
	return false !== strpos( $hook, 'cptui_' );
}

/*
 * -------------------------------------------------------------------------
 * Assets
 * -------------------------------------------------------------------------
 */

/**
 * Register and enqueue promo assets where promos appear.
 *
 * @since 1.19.4
 *
 * @param string $hook Admin page hook suffix.
 */
function cptui_pro_enqueue_assets( $hook ) {
	$css = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'cptui-pro.css' : 'cptui-pro.min.css';
	wp_register_style( 'cptui-pro-promo', plugin_dir_url( __DIR__ ) . 'build/' . $css, [], cptui_asset_version( 'build/' . $css ) );
	wp_register_script( 'cptui-pro-promo', plugin_dir_url( __DIR__ ) . 'build/cptui-pro.js', [], cptui_asset_version( 'build/cptui-pro.js' ), true );

	if ( ! cptui_show_pro_promos() ) {
		return;
	}

	$on_list = '' !== cptui_pro_current_cptui_post_type( 'edit' ) && ! cptui_user_dismissed_pro_upsell();

	if ( cptui_pro_is_cptui_screen( $hook ) || $on_list ) {
		wp_enqueue_style( 'cptui-pro-promo' );
		wp_enqueue_script( 'cptui-pro-promo' );
		add_action( 'admin_footer', 'cptui_pro_tour_dialog' );
	}
}
add_action( 'admin_enqueue_scripts', 'cptui_pro_enqueue_assets' );

/**
 * Small inline style for the highlighted "Get Pro" menu item, shown on every admin screen.
 *
 * @since 1.19.4
 */
function cptui_pro_menu_style() {
	if ( ! cptui_show_pro_promos() ) {
		return;
	}
	?>
	<style>
		#adminmenu .cptui-menu-get-pro { background: #534ab7; border-radius: 3px; color: #fff; display: inline-block; font-weight: 600; line-height: 1.6; padding: 0 7px; }
		#adminmenu a:hover .cptui-menu-get-pro, #adminmenu a:focus .cptui-menu-get-pro, #adminmenu .current .cptui-menu-get-pro { background: #3f36a0; }
	</style>
	<?php
}
add_action( 'admin_head', 'cptui_pro_menu_style' );

/*
 * -------------------------------------------------------------------------
 * Menu item, Plugins screen link and the "Get Pro" page
 * -------------------------------------------------------------------------
 */

/**
 * Add the highlighted "Get Pro" item to the end of the CPT UI menu.
 *
 * @since 1.19.4
 */
function cptui_pro_add_menu_item() {
	if ( ! cptui_show_pro_promos() ) {
		return;
	}

	/** This filter is documented in custom-post-type-ui.php */
	$capability = apply_filters( 'cptui_required_capabilities', 'manage_options' );

	add_submenu_page(
		'cptui_main_menu',
		esc_html__( 'Get CPT UI Pro', 'custom-post-type-ui' ),
		'<span class="cptui-menu-get-pro">' . esc_html__( 'Get Pro', 'custom-post-type-ui' ) . '</span>',
		$capability,
		'cptui_upgrade',
		'cptui_pro_page'
	);
}
add_action( 'admin_menu', 'cptui_pro_add_menu_item', 20 );

/**
 * Add a "Get Pro" link to the plugin's row on the Plugins screen.
 *
 * @since 1.19.4
 *
 * @param array $links Plugin action links.
 * @return array
 */
function cptui_pro_plugin_action_link( $links ) {
	if ( ! cptui_show_pro_promos() || ! is_array( $links ) ) {
		return $links;
	}

	$links['cptui_get_pro'] = sprintf(
		'<a href="%1$s" style="color:#534ab7;font-weight:600;">%2$s</a>',
		esc_url( cptui_pro_page_url() ),
		esc_html__( 'Get Pro', 'custom-post-type-ui' )
	);

	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) ) . '/custom-post-type-ui.php', 'cptui_pro_plugin_action_link', 20 );

/**
 * Render the "Get CPT UI Pro" admin page.
 *
 * @since 1.19.4
 */
function cptui_pro_page() {
	$pillars = cptui_pro_pillars();
	?>
	<div class="wrap cptui-pro-page">
		<?php
		/** This action is documented in inc/about.php */
		do_action( 'cptui_inside_wrap' );
		?>
		<h1 class="screen-reader-text"><?php esc_html_e( 'Get CPT UI Pro', 'custom-post-type-ui' ); ?></h1>

		<section class="cptui-pro-page__hero">
			<p class="cptui-pro-page__eyebrow"><?php esc_html_e( 'Custom Post Type UI Pro', 'custom-post-type-ui' ); ?></p>
			<h2 class="cptui-pro-page__title"><?php esc_html_e( 'You built the content model. Now make it easy to run.', 'custom-post-type-ui' ); ?></h2>
			<p class="cptui-pro-page__lead"><?php esc_html_e( 'Free CPT UI registers your post types and taxonomies. Pro puts your fields in the admin lists, keeps clients from breaking the structure, and shows your content on the site without templates. It works on any post type, not just the ones CPT UI made.', 'custom-post-type-ui' ); ?></p>
			<?php cptui_pro_cta_row( 'cptui-upgrade-hero', 'pricing' ); ?>
		</section>

		<section class="cptui-pro-page__compare-shots" aria-labelledby="cptui-pro-before-after">
			<h2 id="cptui-pro-before-after" class="screen-reader-text"><?php esc_html_e( 'Before and after', 'custom-post-type-ui' ); ?></h2>
			<figure>
				<img src="<?php echo esc_url( cptui_pro_asset_url( 'lists-before.webp' ) ); ?>" width="900" height="491" loading="lazy" alt="<?php esc_attr_e( 'A default WordPress list screen showing only title, author and date.', 'custom-post-type-ui' ); ?>">
				<figcaption><?php esc_html_e( 'Free: Title, Author, Date', 'custom-post-type-ui' ); ?></figcaption>
			</figure>
			<figure>
				<img src="<?php echo esc_url( cptui_pro_asset_url( 'lists-after.webp' ) ); ?>" width="900" height="491" loading="lazy" alt="<?php esc_attr_e( 'The same list with photo, price, status, agent and open house columns plus filters.', 'custom-post-type-ui' ); ?>">
				<figcaption><?php esc_html_e( 'Pro: your fields as columns, plus filters', 'custom-post-type-ui' ); ?></figcaption>
			</figure>
		</section>

		<?php foreach ( $pillars as $key => $pillar ) : ?>
			<section class="cptui-pro-page__pillar" id="<?php echo esc_attr( $pillar['section'] ); ?>">
				<div class="cptui-pro-page__pillar-copy">
					<p class="cptui-pro-page__eyebrow"><?php echo esc_html( $pillar['label'] ); ?></p>
					<h2><?php echo esc_html( $pillar['title'] ); ?></h2>
					<p class="cptui-pro-page__today"><strong><?php esc_html_e( 'Today:', 'custom-post-type-ui' ); ?></strong> <?php echo esc_html( $pillar['today'] ); ?></p>
					<p><strong><?php esc_html_e( 'With Pro:', 'custom-post-type-ui' ); ?></strong> <?php echo esc_html( $pillar['pro'] ); ?></p>
					<ul class="cptui-pro-checklist">
						<?php foreach ( $pillar['bullets'] as $bullet ) : ?>
							<li><?php echo esc_html( $bullet ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="cptui-pro-page__pillar-media">
					<?php cptui_pro_pillar_media( $pillar, 'page' ); ?>
				</div>
			</section>
		<?php endforeach; ?>

		<section class="cptui-pro-page__table" id="compare" aria-labelledby="cptui-pro-compare-heading">
			<h2 id="cptui-pro-compare-heading"><?php esc_html_e( 'Free and Pro, side by side', 'custom-post-type-ui' ); ?></h2>
			<p><?php esc_html_e( 'Pro runs alongside free CPT UI, adds features and leaves your existing setup alone.', 'custom-post-type-ui' ); ?></p>
			<table class="widefat striped">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Feature', 'custom-post-type-ui' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Free', 'custom-post-type-ui' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Pro', 'custom-post-type-ui' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$rows = [
						[ __( 'Register post types and taxonomies', 'custom-post-type-ui' ), true ],
						[ __( 'Import and export settings', 'custom-post-type-ui' ), true ],
						[ __( 'Custom columns and filters on any post type', 'custom-post-type-ui' ), false ],
						[ __( 'ACF fields shown as thumbnails, names, dates and labels', 'custom-post-type-ui' ), false ],
						[ __( 'Per-user column views', 'custom-post-type-ui' ), false ],
						[ __( 'Columns and filters on taxonomy screens', 'custom-post-type-ui' ), false ],
						[ __( 'Hide CPT UI from other admins and brand the menu', 'custom-post-type-ui' ), false ],
						[ __( 'Display block and shortcode (13 layouts)', 'custom-post-type-ui' ), false ],
						[ __( 'Theme-overridable display templates', 'custom-post-type-ui' ), false ],
						[ __( 'Add post types to blog, category and tag archives and RSS', 'custom-post-type-ui' ), false ],
						[ __( 'Multisite network-wide post types', 'custom-post-type-ui' ), false ],
						[ __( 'Developer hooks for columns, filters and fields', 'custom-post-type-ui' ), false ],
					];
					foreach ( $rows as $row ) :
						?>
						<tr>
							<td><?php echo esc_html( $row[0] ); ?></td>
							<td><?php echo $row[1] ? '<span class="cptui-pro-yes" aria-hidden="true">&#10003;</span><span class="screen-reader-text">' . esc_html__( 'Included', 'custom-post-type-ui' ) . '</span>' : '<span class="cptui-pro-no" aria-hidden="true">&mdash;</span><span class="screen-reader-text">' . esc_html__( 'Not included', 'custom-post-type-ui' ) . '</span>'; ?></td>
							<td><span class="cptui-pro-yes" aria-hidden="true">&#10003;</span><span class="screen-reader-text"><?php esc_html_e( 'Included', 'custom-post-type-ui' ); ?></span></td>
						</tr>
					<?php endforeach; ?>
					<tr>
						<td><?php esc_html_e( 'Support', 'custom-post-type-ui' ); ?></td>
						<td><?php esc_html_e( 'Forums', 'custom-post-type-ui' ); ?></td>
						<td><?php esc_html_e( 'Priority, from the CPT UI developers', 'custom-post-type-ui' ); ?></td>
					</tr>
				</tbody>
			</table>
		</section>

		<section class="cptui-pro-page__close">
			<ul class="cptui-pro-page__trust">
				<li><strong><?php esc_html_e( '30-day refund', 'custom-post-type-ui' ); ?></strong> <?php esc_html_e( 'Not the right fit? Full refund, no questions asked.', 'custom-post-type-ui' ); ?></li>
				<li><strong><?php esc_html_e( 'No lock-in', 'custom-post-type-ui' ); ?></strong> <?php esc_html_e( 'If you do not renew, your site keeps working.', 'custom-post-type-ui' ); ?></li>
				<li><strong><?php esc_html_e( 'Real support', 'custom-post-type-ui' ); ?></strong> <?php esc_html_e( 'Answered by the developers who build CPT UI.', 'custom-post-type-ui' ); ?></li>
			</ul>
			<?php cptui_pro_cta_row( 'cptui-upgrade-footer', 'pricing' ); ?>
		</section>
	</div>
	<?php
}

/**
 * Output the primary "Get CPT UI Pro" button with the refund guarantee.
 *
 * @since 1.19.4
 *
 * @param string $placement Tracking placement.
 * @param string $section   Pro page anchor.
 */
function cptui_pro_cta_row( $placement, $section = '' ) {
	?>
	<p class="cptui-pro-cta-row">
		<a class="button button-primary button-hero cptui-pro-button" href="<?php echo esc_url( cptui_pro_url( $placement, $section ) ); ?>" target="_blank" rel="noopener">
			<?php esc_html_e( 'Get CPT UI Pro', 'custom-post-type-ui' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'custom-post-type-ui' ); ?></span>
		</a>
		<span class="cptui-pro-cta-row__meta"><?php esc_html_e( '30-day money-back guarantee', 'custom-post-type-ui' ); ?></span>
	</p>
	<?php
}

/**
 * Output the image or video for a feature group.
 *
 * @since 1.19.4
 *
 * @param array  $pillar  Feature group.
 * @param string $context "page" or "tour". Tour videos start when the dialog opens.
 */
function cptui_pro_pillar_media( $pillar, $context = 'page' ) {
	if ( ! empty( $pillar['video'] ) ) {
		printf(
			'<video class="cptui-pro-media" src="%1$s" poster="%2$s" muted loop playsinline preload="none" %3$s aria-label="%4$s"></video>',
			esc_url( cptui_pro_asset_url( $pillar['video'] ) ),
			esc_url( cptui_pro_asset_url( $pillar['poster'] ) ),
			'page' === $context ? 'controls' : 'data-cptui-autoplay',
			esc_attr( $pillar['alt'] )
		);
		return;
	}

	if ( ! empty( $pillar['image'] ) ) {
		printf(
			'<img class="cptui-pro-media" src="%1$s" alt="%2$s" loading="lazy">',
			esc_url( cptui_pro_asset_url( $pillar['image'] ) ),
			esc_attr( $pillar['alt'] )
		);
		return;
	}

	// No screenshot: draw a small network diagram instead.
	$sites = [ 'news.example.com', 'shop.example.com', 'uk.example.com' ];
	?>
	<div class="cptui-pro-media cptui-pro-network" aria-hidden="true">
		<div class="cptui-pro-network__source">
			<span class="dashicons dashicons-networking"></span>
			<?php esc_html_e( 'Network Admin: event, venue, region', 'custom-post-type-ui' ); ?>
		</div>
		<ul>
			<?php foreach ( $sites as $site ) : ?>
				<li><span><?php echo esc_html( $site ); ?></span> <em>&#10003; <?php esc_html_e( 'network types', 'custom-post-type-ui' ); ?></em></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}

/*
 * -------------------------------------------------------------------------
 * Feature tour dialog
 * -------------------------------------------------------------------------
 */

/**
 * Print the feature tour dialog. Any element with data-cptui-pro-tour="<pillar>" opens it.
 *
 * @since 1.19.4
 */
function cptui_pro_tour_dialog() {
	static $printed = false;
	if ( $printed ) {
		return;
	}
	$printed = true;

	$pillars = cptui_pro_pillars();
	?>
	<dialog id="cptui-pro-tour" class="cptui-pro-tour" aria-labelledby="cptui-pro-tour-title">
		<div class="cptui-pro-tour__header">
			<h2 id="cptui-pro-tour-title"><?php esc_html_e( 'What CPT UI Pro adds', 'custom-post-type-ui' ); ?></h2>
			<button type="button" class="cptui-pro-tour__close" data-cptui-pro-close>
				<span class="dashicons dashicons-no-alt" aria-hidden="true"></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Close', 'custom-post-type-ui' ); ?></span>
			</button>
		</div>
		<div class="cptui-pro-tour__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Pro features', 'custom-post-type-ui' ); ?>">
			<?php foreach ( $pillars as $key => $pillar ) : ?>
				<button type="button" role="tab" id="cptui-pro-tab-<?php echo esc_attr( $key ); ?>" aria-controls="cptui-pro-panel-<?php echo esc_attr( $key ); ?>" aria-selected="false" tabindex="-1" data-cptui-pro-tab="<?php echo esc_attr( $key ); ?>">
					<?php echo esc_html( $pillar['label'] ); ?>
				</button>
			<?php endforeach; ?>
		</div>
		<?php foreach ( $pillars as $key => $pillar ) : ?>
			<div class="cptui-pro-tour__panel" role="tabpanel" id="cptui-pro-panel-<?php echo esc_attr( $key ); ?>" aria-labelledby="cptui-pro-tab-<?php echo esc_attr( $key ); ?>" hidden>
				<div class="cptui-pro-tour__media">
					<?php cptui_pro_pillar_media( $pillar, 'tour' ); ?>
				</div>
				<div class="cptui-pro-tour__copy">
					<h3><?php echo esc_html( $pillar['title'] ); ?></h3>
					<p><?php echo esc_html( $pillar['pro'] ); ?></p>
					<ul class="cptui-pro-checklist">
						<?php foreach ( $pillar['bullets'] as $bullet ) : ?>
							<li><?php echo esc_html( $bullet ); ?></li>
						<?php endforeach; ?>
					</ul>
					<p class="cptui-pro-tour__actions">
						<a class="button button-primary cptui-pro-button" href="<?php echo esc_url( cptui_pro_url( 'cptui-tour-' . $key, $pillar['section'] ) ); ?>" target="_blank" rel="noopener">
							<?php esc_html_e( 'Get CPT UI Pro', 'custom-post-type-ui' ); ?>
							<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'custom-post-type-ui' ); ?></span>
						</a>
						<a href="<?php echo esc_url( cptui_pro_page_url( 'compare' ) ); ?>"><?php esc_html_e( 'Compare free and Pro', 'custom-post-type-ui' ); ?></a>
					</p>
					<p class="cptui-pro-tour__meta"><?php esc_html_e( '30-day money-back guarantee', 'custom-post-type-ui' ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</dialog>
	<?php
}

/**
 * A small inline "Pro" badge.
 *
 * @since 1.19.4
 *
 * @return string
 */
function cptui_pro_badge() {
	return '<span class="cptui-pro-badge">' . esc_html__( 'Pro', 'custom-post-type-ui' ) . '</span>';
}

/*
 * -------------------------------------------------------------------------
 * Contextual placements
 * -------------------------------------------------------------------------
 */

/**
 * After a new post type or taxonomy is added, show what to do next with it.
 *
 * Runs after cptui_add_success_admin_notice() so it sits under the success message.
 *
 * @since 1.19.4
 */
function cptui_pro_next_step_notice() {
	if ( ! cptui_show_pro_promos() || ! has_action( 'admin_notices', 'cptui_add_success_admin_notice' ) ) {
		return;
	}

	$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
	$slug = cptui_get_object_from_post_global();

	if ( 'cptui_manage_post_types' === $page ) {
		$object = get_post_type_object( $slug );
		$name   = $object ? $object->labels->name : $slug;
		$pillar = 'display';
		/* translators: %s: Post type plural label. */
		$heading = sprintf( __( 'Next: get %s onto your site', 'custom-post-type-ui' ), $name );
		$body    = __( 'CPT UI registers the post type, but your theme still needs a template to show it. CPT UI Pro adds a block with 13 layouts, so you can place it on any page without code.', 'custom-post-type-ui' );
		$button  = __( 'See the layouts', 'custom-post-type-ui' );
	} elseif ( 'cptui_manage_taxonomies' === $page ) {
		$object = get_taxonomy( $slug );
		$name   = $object ? $object->labels->name : $slug;
		$pillar = 'lists';
		/* translators: %s: Taxonomy plural label. */
		$heading = sprintf( __( 'Next: put %s to work in the admin', 'custom-post-type-ui' ), $name );
		$body    = __( 'CPT UI Pro adds filters and columns for this taxonomy to your list screens, so editors can find entries by term in one click.', 'custom-post-type-ui' );
		$button  = __( 'See how it works', 'custom-post-type-ui' );
	} else {
		return;
	}

	$message = sprintf(
		'<div class="cptui-pro-next"><div class="cptui-pro-next__copy"><p class="cptui-pro-next__heading">%1$s %2$s</p><p>%3$s</p></div><p class="cptui-pro-next__actions"><button type="button" class="button button-primary cptui-pro-button" data-cptui-pro-tour="%4$s">%5$s</button></p></div>',
		esc_html( $heading ),
		cptui_pro_badge(),
		esc_html( $body ),
		esc_attr( $pillar ),
		esc_html( $button )
	);

	wp_admin_notice(
		$message,
		[
			'id'                 => 'cptui-pro-next',
			'type'               => 'info',
			'dismissible'        => true,
			'paragraph_wrap'     => false,
			'additional_classes' => [ 'cptui-pro-notice' ],
		]
	);
}
add_action( 'admin_notices', 'cptui_pro_next_step_notice', 12 );

/**
 * Pro notice on the "Add new" tabs, only once the user has built something.
 *
 * Kept under its 1.19.1 name so other plugins can unhook it.
 *
 * @since 1.19.1
 */
function cptui_pro_upsell_notification() {
	if ( ! cptui_show_pro_promos() || wp_doing_ajax() || cptui_user_dismissed_pro_upsell() ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! is_object( $screen ) || ! empty( $_GET['action'] ) || ! empty( $_POST ) ) {
		return;
	}

	if ( 'cpt-ui_page_cptui_manage_post_types' === $screen->base ) {
		$context = 'post_type';
	} elseif ( 'cpt-ui_page_cptui_manage_taxonomies' === $screen->base ) {
		$context = 'taxonomy';
	} else {
		return;
	}

	// New users are still learning the free plugin. Only pitch once they have a content model.
	$count = count( cptui_get_post_type_slugs() );
	if ( $count < 1 ) {
		return;
	}

	$pillar_key = cptui_pro_pick_pillar( $context );
	$pillars    = cptui_pro_pillars();
	if ( empty( $pillars[ $pillar_key ] ) ) {
		return;
	}
	$pillar = $pillars[ $pillar_key ];

	$message = sprintf(
		'<div class="cptui-pro-next"><div class="cptui-pro-next__copy"><p class="cptui-pro-next__heading">%1$s %2$s</p><p>%3$s</p></div><p class="cptui-pro-next__actions"><button type="button" class="button cptui-pro-button-secondary" data-cptui-pro-tour="%4$s">%5$s</button> <a href="%6$s" class="cptui-pro-next__dismiss">%7$s</a></p></div>',
		esc_html( $pillar['title'] ),
		cptui_pro_badge(),
		esc_html( $pillar['pro'] ),
		esc_attr( $pillar_key ),
		esc_html__( 'Take a quick look', 'custom-post-type-ui' ),
		esc_url( cptui_pro_snooze_url() ),
		esc_html__( 'Not now', 'custom-post-type-ui' )
	);

	wp_admin_notice(
		$message,
		[
			'id'                 => 'cptui-pro-upsell',
			'type'               => 'info',
			'paragraph_wrap'     => false,
			'additional_classes' => [ 'cptui-pro-notice' ],
		]
	);
}
add_action( 'admin_notices', 'cptui_pro_upsell_notification', 11 );

/**
 * "Columns & filters" button in the list table toolbar for CPT UI post types.
 *
 * Replaces the 1.19.1 banner notice with a quiet button next to the
 * Filter button that opens the tour at the admin lists feature.
 *
 * @since 1.19.4
 *
 * @param string $which Top or bottom tablenav.
 */
function cptui_pro_list_table_button( $which ) {
	if ( 'top' !== $which || ! cptui_show_pro_promos() || cptui_user_dismissed_pro_upsell() ) {
		return;
	}

	if ( '' === cptui_pro_current_cptui_post_type( 'edit' ) ) {
		return;
	}

	printf(
		'<div class="alignleft actions cptui-pro-tablenav"><button type="button" class="button cptui-pro-columns-button" data-cptui-pro-tour="lists"><span class="dashicons dashicons-columns" aria-hidden="true"></span> %1$s %2$s</button></div>',
		esc_html__( 'Columns & filters', 'custom-post-type-ui' ),
		cptui_pro_badge() // phpcs:ignore WordPress.Security.EscapeOutput
	);
}
add_action( 'manage_posts_extra_tablenav', 'cptui_pro_list_table_button' );

/**
 * "Display on your site" link for each post type on the Registered types screen.
 *
 * @since 1.19.4
 */
function cptui_pro_listing_row_link() {
	if ( ! cptui_show_pro_promos() ) {
		return;
	}

	printf(
		'<br/><button type="button" class="button-link cptui-pro-inline-link" data-cptui-pro-tour="display">%1$s</button> %2$s',
		esc_html__( 'Display on your site', 'custom-post-type-ui' ),
		cptui_pro_badge() // phpcs:ignore WordPress.Security.EscapeOutput
	);
}
add_action( 'cptui_listings_post_type_row_actions', 'cptui_pro_listing_row_link' );

/*
 * -------------------------------------------------------------------------
 * Block editor panel
 * -------------------------------------------------------------------------
 */

/**
 * Enqueue the CPT UI Pro panel in the block editor's document sidebar.
 *
 * @since 1.19.1
 */
function cptui_enqueue_pro_panel_assets() {
	if ( ! cptui_show_pro_promos() || cptui_user_dismissed_pro_upsell() ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! is_object( $screen ) || empty( $screen->post_type ) ) {
		return;
	}

	$cptui_post_types = cptui_get_post_type_slugs();
	if ( ! in_array( $screen->post_type, $cptui_post_types, true ) ) {
		return;
	}

	$asset_file = plugin_dir_path( __DIR__ ) . 'build/cptui-editor.asset.php';
	if ( ! file_exists( $asset_file ) ) {
		return;
	}

	$asset = include $asset_file;

	wp_enqueue_script(
		'cptui-pro-panel',
		plugin_dir_url( __DIR__ ) . 'build/cptui-editor.js',
		$asset['dependencies'],
		$asset['version'],
		true
	);

	wp_localize_script(
		'cptui-pro-panel',
		'cptuiProPanel',
		[
			'postTypes' => $cptui_post_types,
			'proUrl'    => cptui_pro_url( 'cptui-editor-panel', 'display' ),
			'image'     => cptui_pro_asset_url( 'layout-grid.webp' ),
		]
	);
}
add_action( 'enqueue_block_editor_assets', 'cptui_enqueue_pro_panel_assets' );

/**
 * Register the REST route the block editor panel uses to dismiss itself.
 *
 * @since 1.19.1
 */
function cptui_register_pro_panel_rest_route() {
	register_rest_route(
		'cptui/v1',
		'/dismiss-pro-upsell',
		[
			'methods'             => 'POST',
			'callback'            => 'cptui_rest_dismiss_pro_upsell',
			'permission_callback' => static function () {
				return current_user_can( 'manage_options' );
			},
		]
	);
}
add_action( 'rest_api_init', 'cptui_register_pro_panel_rest_route' );

/**
 * REST callback that snoozes Pro notices for the current user.
 *
 * @since 1.19.1
 *
 * @return WP_REST_Response
 */
function cptui_rest_dismiss_pro_upsell() {
	cptui_snooze_pro_promos();
	return new WP_REST_Response( [ 'dismissed' => true ], 200 );
}

/**
 * Clean up the pre-1.20 dismissal options.
 *
 * @since 1.19.4
 */
function cptui_pro_cleanup_legacy_dismissals() {
	if ( get_option( 'cptui-user-dismissed-pro-upsell', false ) !== false ) {
		delete_option( 'cptui-user-dismissed-pro-upsell' );
	}
	if ( get_option( 'cptui-user-dismissed-extended-upsell', false ) !== false ) {
		delete_option( 'cptui-user-dismissed-extended-upsell' );
	}
}
add_action( 'admin_init', 'cptui_pro_cleanup_legacy_dismissals' );
