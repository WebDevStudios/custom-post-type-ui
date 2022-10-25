<?php
/**
 * Custom Post Type UI Display Post Types Page.
 *
 * @package CPTUI
 * @subpackage Display Post TYpes
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
function cptui_display_assets() {
	$current_screen = get_current_screen();

	if ( ! is_object( $current_screen ) || 'toplevel_page_cptui_main_menu' !== $current_screen->base ) {
		return;
	}

	if ( wp_doing_ajax() ) {
		return;
	}

	wp_enqueue_style( 'cptui-css' );
}
add_action( 'admin_enqueue_scripts', 'cptui_display_assets' );

/**
 * Display our primary menu page.
 *
 * @since 0.3.0
 *
 * @internal
 */
function cptui_display() {
	?>
	<div class="wrap display-wrap">
		<?php

		/**
		 * Fires inside and at the top of the wrapper for the main plugin landing page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'cptui_main_page_start' );
		?>
		<h1><?php esc_html_e( 'Display Custom Post Type Content', 'custom-post-type-ui' ); ?></h1>

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
				<?php esc_html_e( 'We’ve made it simple to display content from your custom post types directly inside your pages and posts.', 'custom-post-type-ui' ); ?>

			</p>
			<div class="cptui-badge"></div>


		</div>
		<h3>Custom Post Type UI Extended</h3>
		<p>The Custom Post Type UI Extended plugin features an easy to use Gutenberg Block to easily display your custom post type content anywhere on your website!</p>
		
		<ul>
			<li>
				<h3>Default Layout</h3>
				<img src="<?php echo plugin_dir_url( __DIR__ ) . 'images/layout_examples/default-layout.png'; ?>" />
			</li>
			<li>
				<h3>List Layout</h3>
				<img src="<?php echo plugin_dir_url( __DIR__ ) . 'images/layout_examples/list-layout.png'; ?>" />
			</li>
			<li>
				<h3>Grid with Overlay Layout</h3>
				<img src="<?php echo plugin_dir_url( __DIR__ ) . 'images/layout_examples/grid-with-overlay-layout.png'; ?>" />
			</li>
			<li>Single Post</li>
			<li>Taxonomly List</li>
			<li>Post Slider</li>
			<li>Post Cards</li>
			<li>Featured Plus</li>
			<li>Grid</li>
		</ul>

		<?php
		/**
		 * Fires before the About Page changelog.
		 *
		 * @since 1.4.0
		 */
		//do_action( 'cptui_main_page_before_changelog' );
		?>

		<div class="extranotes">
			<?php

			/**
			 * Fires inside a div for extra notes.
			 *
			 * @since 1.3.0
			 */
			//do_action( 'cptui_main_page_extra_notes' );
			?>
		</div>
	</div>
	<?php
}



