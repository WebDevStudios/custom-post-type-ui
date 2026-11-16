<?php
/**
 * Custom Post Type UI newsletter signup.
 *
 * Subscribers are sent to FluentCRM on pluginize.com through an incoming
 * webhook. The request is made server side, so no third-party scripts load in
 * the admin and the webhook works without CORS. Only the email address the
 * user types is sent, and only when they press Subscribe.
 *
 * @package CPTUI
 * @subpackage Newsletter
 * @author WebDevStudios
 * @since 1.19.4
 * @license GPL-2.0+
 */

// phpcs:disable WebDevStudios.All.RequireAuthor

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * FluentCRM incoming webhook URL that receives new subscribers.
 *
 * Lists, tags and double opt-in are configured on the webhook in FluentCRM.
 * Define CPTUI_NEWSLETTER_ENDPOINT in wp-config.php to point at a test
 * webhook while developing.
 *
 * @since 1.19.4
 *
 * @return string Empty string disables the form.
 */
function cptui_newsletter_endpoint() {
	$endpoint = defined( 'CPTUI_NEWSLETTER_ENDPOINT' )
		? (string) CPTUI_NEWSLETTER_ENDPOINT
		: 'https://pluginize.com/?fluentcrm=1&route=contact&hash=5f1f8bde-330f-4d54-b951-9d53b7a382d6';

	/**
	 * Filters the FluentCRM webhook URL used for newsletter signups.
	 *
	 * Return an empty string to remove the signup form.
	 *
	 * @since 1.19.4
	 *
	 * @param string $endpoint Webhook URL.
	 */
	return (string) apply_filters( 'cptui_newsletter_endpoint', $endpoint );
}

/**
 * Whether the current user already subscribed from this site.
 *
 * @since 1.19.4
 *
 * @return bool
 */
function cptui_newsletter_user_subscribed() {
	return (bool) get_user_meta( get_current_user_id(), 'cptui_newsletter_subscribed', true );
}

/**
 * Outputs the newsletter signup form.
 *
 * @since 1.3.4
 * @since 1.19.4 Sends subscribers to FluentCRM on pluginize.com.
 *
 * @internal
 */
function cptui_newsletter_form() {
	if ( '' === cptui_newsletter_endpoint() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$status = isset( $_GET['cptui-newsletter'] ) ? sanitize_key( wp_unslash( $_GET['cptui-newsletter'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( cptui_newsletter_user_subscribed() && 'subscribed' !== $status ) {
		return;
	}

	wp_enqueue_script(
		'cptui-newsletter',
		plugin_dir_url( __DIR__ ) . 'build/cptui-newsletter.js',
		[],
		cptui_asset_version( 'build/cptui-newsletter.js' ),
		true
	);

	static $instance = 0;
	++$instance;
	$field_id = 'cptui-newsletter-email-' . $instance;
	$messages = cptui_newsletter_messages();
	$notice   = isset( $messages[ $status ] ) ? $messages[ $status ] : '';
	?>
	<div class="cptui-newsletter">
		<p class="cptui-newsletter__title"><strong><?php esc_html_e( 'Get Custom Post Type UI updates', 'custom-post-type-ui' ); ?></strong></p>
		<p class="cptui-newsletter__desc"><?php esc_html_e( 'New releases, tips and the occasional Pluginize offer. Unsubscribe anytime.', 'custom-post-type-ui' ); ?></p>

		<?php if ( 'subscribed' !== $status ) : ?>
			<form class="cptui-newsletter__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-sending="<?php esc_attr_e( 'Subscribing…', 'custom-post-type-ui' ); ?>" data-error="<?php echo esc_attr( $messages['error'] ); ?>">
				<input type="hidden" name="action" value="cptui_newsletter_subscribe">
				<?php wp_nonce_field( 'cptui_newsletter_subscribe', 'cptui_newsletter_nonce' ); ?>
				<label for="<?php echo esc_attr( $field_id ); ?>"><?php esc_html_e( 'Email address', 'custom-post-type-ui' ); ?></label>
				<div class="cptui-newsletter__row">
					<input id="<?php echo esc_attr( $field_id ); ?>" name="email" type="email" required autocomplete="email" placeholder="you@example.com">
					<button type="submit" class="button button-secondary"><?php esc_html_e( 'Subscribe', 'custom-post-type-ui' ); ?></button>
				</div>
				<div class="cptui-newsletter__hp" aria-hidden="true">
					<label><?php esc_html_e( 'Leave this field empty', 'custom-post-type-ui' ); ?> <input type="text" name="cptui_website" tabindex="-1" autocomplete="off"></label>
				</div>
			</form>
		<?php endif; ?>

		<p class="cptui-newsletter__status<?php echo 'subscribed' === $status ? ' is-success' : ( $notice ? ' is-error' : '' ); ?>" role="status" aria-live="polite"><?php echo esc_html( $notice ); ?></p>
	</div>
	<?php
}

/**
 * Result messages shown after a signup attempt.
 *
 * @since 1.19.4
 *
 * @return array
 */
function cptui_newsletter_messages() {
	return [
		'subscribed' => __( 'Thanks for subscribing! Check your inbox.', 'custom-post-type-ui' ),
		'invalid'    => __( 'Please enter a valid email address.', 'custom-post-type-ui' ),
		'wait'       => __( 'Please wait a minute before trying again.', 'custom-post-type-ui' ),
		'error'      => __( 'Something went wrong. Please try again later.', 'custom-post-type-ui' ),
	];
}

/**
 * Send an email address to the FluentCRM webhook.
 *
 * @since 1.19.4
 *
 * @param string $email Email address.
 * @return string Status key: subscribed, invalid, wait or error.
 */
function cptui_newsletter_subscribe( $email ) {
	$endpoint = cptui_newsletter_endpoint();
	if ( '' === $endpoint ) {
		return 'error';
	}

	$email = sanitize_email( $email );
	if ( ! is_email( $email ) ) {
		return 'invalid';
	}

	$throttle_key = 'cptui_newsletter_' . get_current_user_id();
	if ( get_transient( $throttle_key ) ) {
		return 'wait';
	}
	set_transient( $throttle_key, 1, MINUTE_IN_SECONDS );

	$body = [ 'email' => $email ];

	/**
	 * Filters the data sent to the FluentCRM webhook.
	 *
	 * @since 1.19.4
	 *
	 * @param array $body Webhook fields. Default only the email address.
	 */
	$body = (array) apply_filters( 'cptui_newsletter_webhook_body', $body );

	$response = wp_remote_post(
		$endpoint,
		[
			'timeout' => 10,
			'body'    => $body,
		]
	);

	if ( is_wp_error( $response ) ) {
		return 'error';
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	if ( $code < 200 || $code >= 300 ) {
		return 'error';
	}

	// FluentCRM answers {"success":true,...}. Treat an explicit false as a failure.
	$result = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( is_array( $result ) && isset( $result['success'] ) && false === $result['success'] ) {
		return 'error';
	}

	update_user_meta( get_current_user_id(), 'cptui_newsletter_subscribed', time() );

	return 'subscribed';
}

/**
 * Validate a signup request and subscribe.
 *
 * @since 1.19.4
 *
 * @return string Status key.
 */
function cptui_newsletter_process_request() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return 'error';
	}

	if ( ! isset( $_POST['cptui_newsletter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cptui_newsletter_nonce'] ) ), 'cptui_newsletter_subscribe' ) ) {
		return 'error';
	}

	// Honeypot: real users never see this field.
	if ( ! empty( $_POST['cptui_website'] ) ) {
		return 'subscribed';
	}

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	return cptui_newsletter_subscribe( $email );
}

/**
 * AJAX signup handler.
 *
 * @since 1.19.4
 */
function cptui_newsletter_ajax() {
	$status   = cptui_newsletter_process_request();
	$messages = cptui_newsletter_messages();
	$data     = [
		'status'  => $status,
		'message' => $messages[ $status ],
	];

	if ( 'subscribed' === $status ) {
		wp_send_json_success( $data );
	}
	wp_send_json_error( $data );
}
add_action( 'wp_ajax_cptui_newsletter_subscribe', 'cptui_newsletter_ajax' );

/**
 * Non-JavaScript signup handler. Redirects back with the result.
 *
 * @since 1.19.4
 */
function cptui_newsletter_post() {
	$status   = cptui_newsletter_process_request();
	$referer  = wp_get_referer();
	$redirect = $referer ? $referer : admin_url( 'admin.php?page=cptui_main_menu' );

	wp_safe_redirect( add_query_arg( 'cptui-newsletter', $status, remove_query_arg( 'cptui-newsletter', $redirect ) ) );
	exit;
}
add_action( 'admin_post_cptui_newsletter_subscribe', 'cptui_newsletter_post' );

/**
 * Suggest privacy policy text for the newsletter signup.
 *
 * Shown to site owners in Settings → Privacy → Policy Guide.
 *
 * @since 1.19.4
 */
function cptui_newsletter_privacy_policy_content() {
	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}

	$content = '<p class="privacy-policy-tutorial">' . esc_html__( 'Custom Post Type UI only sends data when a site administrator uses its optional newsletter signup. Site visitors are never affected.', 'custom-post-type-ui' ) . '</p>'
		. '<strong class="privacy-policy-tutorial">' . esc_html__( 'Suggested text:', 'custom-post-type-ui' ) . ' </strong>'
		. '<p>' . sprintf(
			/* translators: 1: Link to the Pluginize privacy policy. 2: Link to the Pluginize terms. */
			esc_html__( 'If an administrator of this site subscribes to Custom Post Type UI updates, the email address they enter is sent to Pluginize (WebDevStudios) at pluginize.com to manage that subscription. No other data from this site or its visitors is sent. See the Pluginize %1$s and %2$s.', 'custom-post-type-ui' ),
			'<a href="https://pluginize.com/privacy/">' . esc_html__( 'privacy policy', 'custom-post-type-ui' ) . '</a>',
			'<a href="https://pluginize.com/terms/">' . esc_html__( 'terms', 'custom-post-type-ui' ) . '</a>'
		) . '</p>';

	wp_add_privacy_policy_content( 'Custom Post Type UI', wp_kses_post( $content ) );
}
add_action( 'admin_init', 'cptui_newsletter_privacy_policy_content' );
