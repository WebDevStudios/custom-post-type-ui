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
	return array_merge( [
		'<a href="' . admin_url( 'admin.php?page=cptui_main_menu' ) . '">' . __( 'About', 'custom-post-type-ui' ) . '</a>',
		'<a href="' . admin_url( 'admin.php?page=cptui_support' ) . '">' . __( 'Help', 'custom-post-type-ui' ) . '</a>',
	], $links );
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
		__( '%s version %s by %s', 'custom-post-type-ui' ),
		__( 'Custom Post Type UI', 'custom-post-type-ui' ),
		CPTUI_VERSION,
		'<a href="https://webdevstudios.com" target="_blank">WebDevStudios</a>'
	) . ' - ' .
	sprintf(
		'<a href="http://wordpress.org/support/plugin/custom-post-type-ui" target="_blank">%s</a>',
		__( 'Support forums', 'custom-post-type-ui' )
	) . ' - ' .
	sprintf(
		'<a href="https://wordpress.org/plugins/custom-post-type-ui/reviews/" target="_blank">%s</a>',
		sprintf(
			// translators: Placeholder will hold `<abbr>` tag for CPTUI.
			__( 'Review %s', 'custom-post-type-ui' ),
			sprintf(
				'<abbr title="%s">%s</abbr>',
				esc_attr__( 'Custom Post Type UI', 'custom-post-type-ui' ),
				'CPTUI'
			)
		)
	) . ' - ' .
	__( 'Follow on Twitter:', 'custom-post-type-ui' ) .
	sprintf(
		' %s',
		'<a href="https://twitter.com/webdevstudios" target="_blank">WebDevStudios</a>'
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
	if ( 'true' === ( $flush_it = get_transient( 'cptui_flush_rewrite_rules' ) ) ) {
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
	if ( ! empty( $_GET ) && isset( $_GET['action'] ) ) {
		$current_action .= esc_textarea( $_GET['action'] );
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

	cptui_newsletter_form();

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
		printf(
			'<p><a href="%s">%s</a></p>',
			'https://pluginize.com/plugins/custom-post-type-ui-extended/ref/pluginizeaff/?campaign=cptui-sidebar-remove',
			esc_html__( 'Remove these ads?', 'custom-post-type-ui' )
		);
	}
	echo '</div>';

}
add_action( 'cptui_below_post_type_tab_menu', 'cptui_products_sidebar' );
add_action( 'cptui_below_taxonomy_tab_menu', 'cptui_products_sidebar' );

/**
 * Outputs our newsletter signup form.
 *
 * @since 1.3.4
 * @internal
 */
function cptui_newsletter_form() {
	?>

<div class="email-octopus-form-wrapper">
    <p><strong>Get email updates from pluginize.com about Custom Post Type UI</strong></p>
    <p class="email-octopus-success-message"></p>
    <p class="email-octopus-error-message"></p>

    <form method="post"
      action="https://emailoctopus.com/lists/2039e001-4775-11ea-be00-06b4694bee2a/members/embedded/1.3/add"
      class="email-octopus-form"
      data-sitekey="6LdYsmsUAAAAAPXVTt-ovRsPIJ_IVhvYBBhGvRV6"
    >
        
        <div class="email-octopus-form-row">
            <label for="field_0">Email address</label>
            <input id="field_0" name="field_0" type="email" placeholder="email@domain.com" style="max-width:100%;">
        </div>

        <div class="email-octopus-form-row-hp" aria-hidden="true">
            <!-- Do not remove this field, otherwise you risk bot sign-ups -->
            <input type="text" name="hp2039e001-4775-11ea-be00-06b4694bee2a"
               tabindex="-1"
               autocomplete="nope">
        </div>

        <div class="email-octopus-form-row-subscribe">
            <input type="hidden"
               name="successRedirectUrl"
               value="">
            <button type="submit">Subscribe</button>
        </div>
    </form>

    
</div>

<?php
}

/**
 * Add our Email Octopus scripts and stylesheet.
 *
 * @author Scott Anderson <scott.anderson@webdevstudios.com>
 * @since  NEXT
 */
function enqueue_email_octopus_assets() {

	$current_screen = get_current_screen();

	if ( ! is_object( $current_screen ) ) {
		return;
	}

	$screens = [
		'toplevel_page_cptui_main_menu',
		'cpt-ui_page_cptui_manage_post_types',
		'cpt-ui_page_cptui_manage_taxonomies',
	];

	if ( ! in_array( $current_screen->base, $screens, true ) ) {
		return;
	}

	if ( ! has_action( 'cptui_below_post_type_tab_menu' ) || ! has_action( 'cptui_below_taxonomy_tab_menu' ) ) {
		return;
	}

	wp_enqueue_style( 'cptui-emailoctopus', 'https://emailoctopus.com/bundles/emailoctopuslist/css/formEmbed.css' );

	wp_enqueue_script( 'cptui-emailoctopus-js', 'https://emailoctopus.com/bundles/emailoctopuslist/js/1.4/formEmbed.js', array( 'jquery' ), '', true );

}
add_action( 'admin_enqueue_scripts', 'enqueue_email_octopus_assets' );

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
		'url'   => 'https://pluginize.com/plugins/custom-post-type-ui-extended/?utm_source=cptui-sidebar&utm_medium=text&utm_campaign=cptui',
		'image' => plugin_dir_url( __DIR__ ) . 'images/wds_ads/cptui-extended.png',
		'text'  => 'Custom Post Type UI Extended product ad',
	];

	$ads[] = [
		'url'   => 'https://pluginize.com/plugins/instago/?utm_source=cptui-sidebar&utm_medium=text&utm_campaign=instago',
		'image' => plugin_dir_url( __DIR__ ) . 'images/wds_ads/instago.png',
		'text'  => 'InstaGo product ad',
	];

	$ads[] = [
		'url'   => 'https://pluginize.com/plugins/buddypages/?utm_source=cptui-sidebar&utm_medium=text&utm_campaign=buddypages',
		'image' => plugin_dir_url( __DIR__ ) . 'images/wds_ads/buddypages.png',
		'text'  => 'BuddyPages product ad',
	];

	$ads[] = [
		'url'   => 'https://maintainn.com/?utm_source=Pluginize-v2&utm_medium=Plugin-Sidebar&utm_campaign=CPTUI',
		'image' => plugin_dir_url( __DIR__ ) . 'images/wds_ads/maintainn.png',
		'text'  => 'Maintainn product ad',
	];

	return $ads;
}
add_filter( 'cptui_ads', 'cptui_default_ads' );

/**
 * Randomize our array order.
 * Preserves CPTUI-Extended as the first index. Self promotion, yo.
 *
 * @since 1.3.0
 *
 * @param array $ads Array of ads to show.
 * @return array
 */
function cptui_randomize_ads( $ads = [] ) {
	$new_order = [];
	foreach ( $ads as $key => $ad ) {
		if ( false !== strpos( $ad['url'], 'custom-post-type-ui-extended' ) ) {
			$new_order[] = $ad;
			unset( $ads[ $key ] );
		}
	}
	shuffle( $ads );

	return array_merge( $new_order, $ads );
}
add_filter( 'cptui_ads', 'cptui_randomize_ads', 11 );

/**
 * Secondary admin notices function for use with admin_notices hook.
 *
 * Constructs admin notice HTML.
 *
 * @since 1.4.0
 *
 * @param string $message Message to use in admin notice. Optional. Default empty string.
 * @param bool   $success Whether or not a success. Optional. Default true.
 * @return mixed
 */
function cptui_admin_notices_helper( $message = '', $success = true ) {

	$class   = [];
	$class[] = $success ? 'updated' : 'error';
	$class[] = 'notice is-dismissible';

	$messagewrapstart = '<div id="message" class="' . implode( ' ', $class ) . '"><p>';

	$messagewrapend = '</p></div>';

	$action = '';

	/**
	 * Filters the custom admin notice for CPTUI.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value            Complete HTML output for notice.
	 * @param string $action           Action whose message is being generated.
	 * @param string $message          The message to be displayed.
	 * @param string $messagewrapstart Beginning wrap HTML.
	 * @param string $messagewrapend   Ending wrap HTML.
	 */
	return apply_filters( 'cptui_admin_notice', $messagewrapstart . $message . $messagewrapend, $action, $message, $messagewrapstart, $messagewrapend );
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
	if ( isset( $_POST['cpt_custom_post_type']['name'] ) ) {
		return sanitize_text_field( $_POST['cpt_custom_post_type']['name'] );
	}

	if ( isset( $_POST['cpt_custom_tax']['name'] ) ) {
		return sanitize_text_field( $_POST['cpt_custom_tax']['name'] );
	}

	return esc_html__( 'Object', 'custom-post-type-ui' );
}

/**
 * Successful add callback.
 *
 * @since 1.4.0
 */
function cptui_add_success_admin_notice() {
	echo cptui_admin_notices_helper(
		sprintf(
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
	echo cptui_admin_notices_helper(
		sprintf(
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
	echo cptui_admin_notices_helper(
		sprintf(
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
	echo cptui_admin_notices_helper(
		sprintf(
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
	echo cptui_admin_notices_helper(
		sprintf(
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
	echo cptui_admin_notices_helper(
		sprintf(
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
	echo cptui_admin_notices_helper(
		esc_html__( 'Successfully imported data.', 'custom-post-type-ui' )
	);
}

/**
 * Failure to import callback.
 *
 * @since 1.5.0
 */
function cptui_import_fail_admin_notice() {
	echo cptui_admin_notices_helper(
		esc_html__( 'Invalid data provided', 'custom-post-type-ui' ),
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
	return sprintf(
		esc_html__( 'Please choose a different post type name. %s matches an existing page slug, which can cause conflicts.', 'custom-post-type-ui' ),
		cptui_get_object_from_post_global()
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
	echo cptui_admin_notices_helper(
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
	$post_types = get_option( 'cptui_post_types' );

	if ( array_key_exists( $post_type, $post_types ) ) {
		return $post_types[ $post_type ];
	}
	return '';
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
	$taxonomies = get_option( 'cptui_taxonomies' );

	if ( array_key_exists( $taxonomy, $taxonomies ) ) {
		return $taxonomies[ $taxonomy ];
	}
	return '';
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
	foreach ( $post_types as $type ) {
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
	if ( ! in_array( $content_type, [ 'post_types', 'taxonomies' ] ) ) {
		return cptui_admin_url( 'admin.php?page=cptui_manage_post_types' );
	}

	return cptui_admin_url( 'admin.php?page=cptui_manage_' . $content_type );
}
