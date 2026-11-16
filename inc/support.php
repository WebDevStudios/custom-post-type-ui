<?php
/**
 * Custom Post Type UI Help and Support page.
 *
 * @package CPTUI
 * @subpackage Support
 * @author WebDevStudios
 * @since 1.0.0
 * @license GPL-2.0+
 */

// phpcs:disable WebDevStudios.All.RequireAuthor

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add our cptui.js file, with dependencies on jQuery.
 *
 * @since 1.0.0
 *
 * @internal
 */
function cptui_support_enqueue_scripts( $hook ) {

	if ( 'cpt-ui_page_cptui_support' !== $hook ) {
		return;
	}

	if ( wp_doing_ajax() ) {
		return;
	}

	wp_enqueue_script( 'cptui' );
	wp_enqueue_style( 'cptui-css' );
}
add_action( 'admin_enqueue_scripts', 'cptui_support_enqueue_scripts' );

/**
 * Build a link for use inside an FAQ answer.
 *
 * @since 1.19.4
 *
 * @param string $url  URL.
 * @param string $text Link text.
 * @return string
 */
function cptui_support_link( $url, $text ) {
	$external = 0 !== strpos( $url, admin_url() ) && 0 !== strpos( $url, network_admin_url() );
	return sprintf(
		'<a href="%1$s"%2$s>%3$s</a>',
		esc_url( $url ),
		$external ? ' target="_blank" rel="noopener"' : '',
		esc_html( $text )
	);
}

/**
 * Frequently asked questions, grouped by task.
 *
 * Each answer is trusted HTML built here; every dynamic part is escaped.
 *
 * @since 1.19.4
 *
 * @return array Sections keyed by slug, each with a title and questions keyed by anchor.
 */
function cptui_support_faqs() {
	$tools       = cptui_admin_url( 'admin.php?page=cptui_tools' );
	$get_code    = cptui_admin_url( 'admin.php?page=cptui_tools&action=get_code' );
	$permalinks  = admin_url( 'options-permalink.php' );
	$add_type    = cptui_admin_url( 'admin.php?page=cptui_manage_post_types' );
	$add_tax     = cptui_admin_url( 'admin.php?page=cptui_manage_taxonomies' );
	$pro_display = function_exists( 'cptui_pro_url' ) ? cptui_pro_url( 'faq', 'display' ) : 'https://pluginize.com/plugins/custom-post-type-ui-pro/';
	$pro_page    = function_exists( 'cptui_pro_page_url' ) ? cptui_pro_page_url() : $pro_display;
	$pro_url     = function_exists( 'cptui_pro_url' ) ? cptui_pro_url( 'faq-about' ) : $pro_display;

	$sections = [
		'troubleshooting' => [
			'title'     => __( 'Getting started and troubleshooting', 'custom-post-type-ui' ),
			'questions' => [
				'faq-404' => [
					__( 'My post type or taxonomy shows "Page not found"', 'custom-post-type-ui' ),
					'<p>' . sprintf(
						/* translators: %s: Link to the Permalinks settings screen. */
						esc_html__( 'WordPress needs to rebuild its URL rules after a new post type or taxonomy is added or its slug changes. Open %s and click Save Changes without changing anything. That fixes most 404s.', 'custom-post-type-ui' ),
						cptui_support_link( $permalinks, __( 'Settings → Permalinks', 'custom-post-type-ui' ) )
					) . '</p><p>' . esc_html__( 'Still seeing a 404? Check that the post type is set to Public and Publicly Queryable, and that no page or other post type uses the same slug.', 'custom-post-type-ui' ) . '</p>',
				],
				'faq-block-editor' => [
					__( 'My post type uses the classic editor instead of the block editor', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'The block editor only works for post types that are available in the REST API. Edit the post type and set "Show in REST API" to True. If it still opens the classic editor, a plugin such as Classic Editor may be turning the block editor off for that post type.', 'custom-post-type-ui' ) . '</p>',
				],
				'faq-featured-image' => [
					__( 'Featured images or post formats don\'t appear when editing my posts', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'Make sure "Featured Image" or "Post Formats" is checked under Supports on the post type. CPT UI adds the theme support featured images need for you.', 'custom-post-type-ui' ) . '</p><p>' . esc_html__( 'If the box is checked and the panel is still missing, open the editor\'s Preferences (or Screen Options in the classic editor) and make sure the panel isn\'t hidden.', 'custom-post-type-ui' ) . '</p>',
				],
				'faq-menus' => [
					__( 'My post type doesn\'t appear in Appearance → Menus', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'Set "Show in Nav Menus" to True on the post type or taxonomy. On the Menus screen, also check Screen Options at the top right, since new post types start hidden there.', 'custom-post-type-ui' ) . '</p>',
				],
				'faq-taxonomy-archives' => [
					__( 'My posts don\'t show up in taxonomy term archives', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'If "Exclude From Search" is set to True on the post type, WordPress also leaves it out of taxonomy archives. To keep it out of search but in archives, set it back to False and remove it from search results with the pre_get_posts hook instead.', 'custom-post-type-ui' ) . '</p>',
				],
			],
		],
		'display'         => [
			'title'     => __( 'Showing content on your site', 'custom-post-type-ui' ),
			'questions' => [
				'faq-display' => [
					__( 'How do I display my post type on my site?', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'CPT UI registers post types but doesn\'t control how they look. A few ways to show them:', 'custom-post-type-ui' ) . '</p><ul>'
						. '<li>' . esc_html__( 'Archive page: set "Has Archive" to True and your posts are listed at yoursite.com/your-post-type-slug/.', 'custom-post-type-ui' ) . '</li>'
						. '<li>' . esc_html__( 'Block editor: add a Query Loop block to any page and choose your post type in its settings.', 'custom-post-type-ui' ) . '</li>'
						. '<li>' . sprintf(
							/* translators: %s: Link to CPT UI Pro. */
							esc_html__( 'Ready-made layouts: %s adds a block and shortcode with 13 layouts, including grid, slider and cards, plus theme-overridable templates.', 'custom-post-type-ui' ),
							cptui_support_link( $pro_display, 'CPT UI Pro' )
						) . '</li>'
						. '<li>' . sprintf(
							/* translators: %s: Link to WP_Query documentation. */
							esc_html__( 'Custom code: query your posts with %s in a theme template.', 'custom-post-type-ui' ),
							cptui_support_link( 'https://developer.wordpress.org/reference/classes/wp_query/', 'WP_Query' )
						) . '</li></ul>',
				],
				'faq-templates' => [
					__( 'Which template files control how my post type looks?', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'Block themes: open Appearance → Editor → Templates and add a "Single item: your post type" or "Archive: your post type" template.', 'custom-post-type-ui' ) . '</p><p>' . sprintf(
						/* translators: %s: Link to the template hierarchy documentation. */
						esc_html__( 'Classic themes: create single-{post_type}.php and archive-{post_type}.php in your theme. See the %s for the full lookup order.', 'custom-post-type-ui' ),
						cptui_support_link( 'https://developer.wordpress.org/themes/basics/template-hierarchy/', __( 'template hierarchy', 'custom-post-type-ui' ) )
					) . '</p>',
				],
				'faq-category-archives' => [
					__( 'I added categories and tags to my post type, but the posts don\'t appear in those archives', 'custom-post-type-ui' ),
					'<p>' . sprintf(
						/* translators: 1: Link to the tutorial. 2: Link to CPT UI Pro. */
						esc_html__( 'WordPress category and tag archives only query regular posts by default, so your post type has to be added to that query. Follow %1$s, or turn it on with a checkbox in %2$s.', 'custom-post-type-ui' ),
						cptui_support_link( 'https://docs.pluginize.com/article/post-types-in-category-tag-archives/', __( 'this tutorial', 'custom-post-type-ui' ) ),
						cptui_support_link( function_exists( 'cptui_pro_url' ) ? cptui_pro_url( 'faq-archives', 'display' ) : $pro_display, 'CPT UI Pro' )
					) . '</p>',
				],
				'faq-page-templates' => [
					__( 'Can my post type use page templates, like Pages do?', 'custom-post-type-ui' ),
					'<p>' . sprintf(
						/* translators: %s: Link to the post type templates announcement. */
						esc_html__( 'Yes. In a classic theme, add a "Template Post Type:" line to the template file\'s header listing your post type. See %s for an example. In a block theme, any template you create in the Site Editor can be picked from the post\'s Template panel.', 'custom-post-type-ui' ),
						cptui_support_link( 'https://make.wordpress.org/core/2016/11/03/post-type-templates-in-4-7/', __( 'Post type templates', 'custom-post-type-ui' ) )
					) . '</p>',
				],
			],
		],
		'changes'         => [
			'title'     => __( 'Changing or moving post types', 'custom-post-type-ui' ),
			'questions' => [
				'faq-rename' => [
					__( 'I renamed my post type and my posts disappeared', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'Your posts are still in the database, saved under the old slug. Edit the post type, change the slug back, and save to see them again.', 'custom-post-type-ui' ) . '</p><p>' . esc_html__( 'To rename and keep your posts, check "Migrate posts to newly renamed post type?" under the slug field when you change it. CPT UI moves the posts to the new slug as it saves.', 'custom-post-type-ui' ) . '</p>',
				],
				'faq-duplicates' => [
					__( 'I changed a slug and now I see the post type or taxonomy twice', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'Changing a slug saves the settings under the new slug, and the old entry can stay behind. Once your content is on the new slug, delete the old entry from its edit screen.', 'custom-post-type-ui' ) . '</p>',
				],
				'faq-move' => [
					__( 'How do I copy my post types and taxonomies to another site?', 'custom-post-type-ui' ),
					'<p>' . sprintf(
						/* translators: %s: Link to the CPT UI Tools screen. */
						esc_html__( 'Go to %s, copy the export code from this site, and paste it into the import box on the other site.', 'custom-post-type-ui' ),
						cptui_support_link( $tools, __( 'CPT UI → Tools', 'custom-post-type-ui' ) )
					) . '</p><p><strong>' . esc_html__( 'Importing replaces all of that site\'s CPT UI post types (or taxonomies) with the ones you paste.', 'custom-post-type-ui' ) . '</strong> ' . esc_html__( 'Export the other site\'s settings first if you want to keep them, and combine the two before importing. This only moves settings, not posts.', 'custom-post-type-ui' ) . '</p>',
				],
				'faq-code' => [
					__( 'Can I stop using CPT UI and keep my post types?', 'custom-post-type-ui' ),
					'<p>' . sprintf(
						/* translators: %s: Link to the Get Code tool. */
						esc_html__( 'Yes. %s gives you the PHP that registers each post type and taxonomy exactly as CPT UI does. Add it to your theme or a plugin, then deactivate CPT UI. Your content is untouched either way.', 'custom-post-type-ui' ),
						cptui_support_link( $get_code, __( 'CPT UI → Tools → Get Code', 'custom-post-type-ui' ) )
					) . '</p>',
				],
				'faq-delete-broken' => [
					__( 'I can\'t delete an old post type that has a space or capital letters in its slug', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'Older versions allowed slugs that WordPress doesn\'t accept. Edit the post type, give it a lowercase slug with underscores or dashes and check "Migrate posts to newly renamed post type?", save, and then delete the old entry. Back up your database first.', 'custom-post-type-ui' ) . '</p>',
				],
			],
		],
		'fields'          => [
			'title'     => __( 'Fields and relationships', 'custom-post-type-ui' ),
			'questions' => [
				'faq-fields' => [
					__( 'How do I add custom fields to my post type?', 'custom-post-type-ui' ),
					'<p>' . sprintf(
						/* translators: 1: Link to ACF. 2: Link to Meta Box. 3: Link to CMB2. */
						esc_html__( 'CPT UI creates the post type; a field plugin adds the fields. Popular choices are %1$s, %2$s and %3$s, which WebDevStudios maintains. Assign the field group to your post type and the fields appear on its edit screen.', 'custom-post-type-ui' ),
						cptui_support_link( 'https://wordpress.org/plugins/advanced-custom-fields/', 'Advanced Custom Fields' ),
						cptui_support_link( 'https://wordpress.org/plugins/meta-box/', 'Meta Box' ),
						cptui_support_link( 'https://wordpress.org/plugins/cmb2/', 'CMB2' )
					) . '</p><p>' . sprintf(
						/* translators: %s: Link to CPT UI Pro. */
						esc_html__( 'To see those fields as columns and filters on your admin lists, take a look at %s.', 'custom-post-type-ui' ),
						cptui_support_link( $pro_page, 'CPT UI Pro' )
					) . '</p>',
				],
				'faq-attach-taxonomy' => [
					__( 'How do I add a taxonomy to a post type that already exists?', 'custom-post-type-ui' ),
					'<p>' . sprintf(
						/* translators: %s: Link to the taxonomies screen. */
						esc_html__( 'Edit the taxonomy in %s and check the post type under "Attach to Post Type". This works for built-in types like Posts and Pages too.', 'custom-post-type-ui' ),
						cptui_support_link( $add_tax, __( 'CPT UI → Add/edit taxonomies', 'custom-post-type-ui' ) )
					) . '</p>',
				],
				'faq-relationships' => [
					__( 'How do I relate posts to each other?', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'Use a relationship field from a field plugin, such as the Relationship or Post Object field in Advanced Custom Fields, or Meta Box\'s relationships. Taxonomies also work well when many posts share the same label.', 'custom-post-type-ui' ) . '</p>',
				],
				'faq-title-placeholder' => [
					__( 'How do I change the "Add title" placeholder in the editor?', 'custom-post-type-ui' ),
					'<p>' . sprintf(
						/* translators: %s: Link to the post types screen. */
						esc_html__( 'Edit the post type in %s, open Additional labels, and fill in "Add Title". No code needed.', 'custom-post-type-ui' ),
						cptui_support_link( $add_type, __( 'CPT UI → Add/edit post types', 'custom-post-type-ui' ) )
					) . '</p>',
				],
			],
		],
		'developers'      => [
			'title'     => __( 'For developers', 'custom-post-type-ui' ),
			'questions' => [
				'faq-capabilities' => [
					__( 'How do I customize capabilities for my post type?', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'The "Capability Type" setting controls which capabilities a post type uses. Set it to your own value, such as "book", and WordPress expects capabilities like edit_books, which you can grant with a role editor plugin.', 'custom-post-type-ui' ) . '</p><p>' . sprintf(
						/* translators: %s: Link to the GitHub repository. */
						esc_html__( 'For more control from the CPT UI screen, see %s.', 'custom-post-type-ui' ),
						cptui_support_link( 'https://github.com/tw2113/custom-post-type-ui-capabilities', __( 'Custom Post Type UI Capabilities on GitHub', 'custom-post-type-ui' ) )
					) . '</p>',
				],
				'faq-delegate' => [
					__( 'Can people other than administrators manage CPT UI?', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'Yes. Filter cptui_required_capabilities to return the capability that should have access:', 'custom-post-type-ui' ) . '</p><pre><code>add_filter( \'cptui_required_capabilities\', function () {
	return \'edit_pages\';
} );</code></pre>',
				],
			],
		],
		'about'           => [
			'title'     => __( 'About CPT UI and Pro', 'custom-post-type-ui' ),
			'questions' => [
				'faq-who' => [
					__( 'Who makes Custom Post Type UI?', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'WebDevStudios builds and maintains CPT UI. Our other plugins, free and paid, are on Pluginize.com.', 'custom-post-type-ui' ) . '</p>',
				],
				'faq-pro-needs-free' => [
					__( 'Do I still need CPT UI if I buy CPT UI Pro?', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'To create post types and taxonomies in the admin, yes. Pro runs alongside it and adds admin list columns and filters, access control, display layouts and multisite tools. Those Pro features work on any post type, including ones registered in code or by other plugins.', 'custom-post-type-ui' ) . '</p>',
				],
				'faq-pro-multisite' => [
					__( 'Does CPT UI Pro require multisite?', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'No. Pro works on single sites. Network-wide post types are an extra for people who run multisite.', 'custom-post-type-ui' ) . '</p>',
				],
				'faq-ads' => [
					__( 'Why does CPT UI show ads, and can I remove them?', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'They promote WebDevStudios products only, never third parties, and they help fund development of the free plugin.', 'custom-post-type-ui' ) . '</p><p>' . sprintf(
						/* translators: %s: Link to CPT UI Pro. */
						esc_html__( 'Notices can be dismissed for 90 days with "Not now". %s removes them all.', 'custom-post-type-ui' ),
						cptui_support_link( $pro_url, 'CPT UI Pro' )
					) . '</p>',
				],
				'faq-privacy' => [
					__( 'Do the ads or the newsletter send any data from my site?', 'custom-post-type-ui' ),
					'<p>' . esc_html__( 'The ads send nothing. Their links include UTM parameters so we can see which ones get clicked, and only when you click one.', 'custom-post-type-ui' ) . '</p><p>' . esc_html__( 'The newsletter form sends only the email address you type, and only when you click Subscribe. You can unsubscribe from any email.', 'custom-post-type-ui' ) . '</p>',
				],
			],
		],
	];

	/**
	 * Filters the questions on the CPT UI Help page.
	 *
	 * @since 1.19.4
	 *
	 * @param array $sections Sections keyed by slug. Each has a title and questions keyed by anchor ID,
	 *                        where each question is [ question text, answer HTML ].
	 */
	return (array) apply_filters( 'cptui_support_faqs', $sections );
}

/**
 * Create our settings page output.
 *
 * @since 1.0.0
 * @since 1.19.4 Rebuilt with grouped, searchable questions.
 *
 * @internal
 */
function cptui_support() {
	$sections = cptui_support_faqs();
	$forum    = 'https://wordpress.org/support/plugin/custom-post-type-ui/';
	$docs     = 'https://docs.pluginize.com/';
	$debug    = cptui_admin_url( 'admin.php?page=cptui_tools&action=debuginfo' );
	$show_pro = function_exists( 'cptui_show_pro_promos' ) && cptui_show_pro_promos();
	?>
	<div class="wrap cptui-support">
		<?php
		/**
		 * Fires immediately after wrap div started on all of the cptui admin pages.
		 *
		 * @since 1.14.0
		 */
		do_action( 'cptui_inside_wrap' );

		/**
		 * Fires at the top of the FAQ/Support page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'cptui_main_page_before_faq' );
		?>

		<header class="cptui-support__header">
			<h1><?php esc_html_e( 'Help and support', 'custom-post-type-ui' ); ?></h1>
			<p><?php esc_html_e( 'Answers to common questions about Custom Post Type UI. CPT UI registers your post types and taxonomies; your theme or a display tool decides how they look on the site.', 'custom-post-type-ui' ); ?></p>
			<div class="cptui-support__search">
				<label for="cptui-faq-search" class="screen-reader-text"><?php esc_html_e( 'Search questions', 'custom-post-type-ui' ); ?></label>
				<span class="dashicons dashicons-search" aria-hidden="true"></span>
				<input type="search" id="cptui-faq-search" placeholder="<?php esc_attr_e( 'Search questions, for example "404" or "archive"', 'custom-post-type-ui' ); ?>" autocomplete="off">
			</div>
		</header>

		<ul class="cptui-support__cards">
			<li>
				<span class="dashicons dashicons-book" aria-hidden="true"></span>
				<strong><?php esc_html_e( 'Documentation', 'custom-post-type-ui' ); ?></strong>
				<span><?php esc_html_e( 'Guides for every setting and tool.', 'custom-post-type-ui' ); ?></span>
				<a href="<?php echo esc_url( $docs ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Read the docs', 'custom-post-type-ui' ); ?></a>
			</li>
			<li>
				<span class="dashicons dashicons-format-chat" aria-hidden="true"></span>
				<strong><?php esc_html_e( 'Support forum', 'custom-post-type-ui' ); ?></strong>
				<span><?php esc_html_e( 'Free help from the CPT UI team on WordPress.org.', 'custom-post-type-ui' ); ?></span>
				<a href="<?php echo esc_url( $forum ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Ask a question', 'custom-post-type-ui' ); ?></a>
			</li>
			<?php if ( $show_pro ) : ?>
				<li>
					<span class="dashicons dashicons-star-filled" aria-hidden="true"></span>
					<strong><?php esc_html_e( 'Priority support', 'custom-post-type-ui' ); ?></strong>
					<span><?php esc_html_e( 'Included with CPT UI Pro, answered by the developers.', 'custom-post-type-ui' ); ?></span>
					<a href="<?php echo esc_url( cptui_pro_url( 'cptui-support', 'pricing' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'See CPT UI Pro', 'custom-post-type-ui' ); ?></a>
				</li>
			<?php endif; ?>
		</ul>

		<nav class="cptui-support__toc" aria-label="<?php esc_attr_e( 'Question topics', 'custom-post-type-ui' ); ?>">
			<?php foreach ( $sections as $slug => $section ) : ?>
				<a href="#cptui-faq-<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $section['title'] ); ?></a>
			<?php endforeach; ?>
		</nav>

		<div class="cptui-support__faqs" id="support">
			<?php foreach ( $sections as $slug => $section ) : ?>
				<section class="cptui-support__section" id="cptui-faq-<?php echo esc_attr( $slug ); ?>">
					<h2><?php echo esc_html( $section['title'] ); ?></h2>
					<?php foreach ( $section['questions'] as $anchor => $question ) : ?>
						<details class="cptui-faq" id="<?php echo esc_attr( $anchor ); ?>">
							<summary>
								<span class="cptui-faq__question"><?php echo esc_html( $question[0] ); ?></span>
								<a class="cptui-faq__anchor" href="#<?php echo esc_attr( $anchor ); ?>" aria-label="<?php esc_attr_e( 'Link to this question', 'custom-post-type-ui' ); ?>"><span class="dashicons dashicons-admin-links" aria-hidden="true"></span></a>
							</summary>
							<div class="cptui-faq__answer">
								<?php echo wp_kses_post( $question[1] ); ?>
							</div>
						</details>
					<?php endforeach; ?>
				</section>
			<?php endforeach; ?>

			<?php
			ob_start();
			/**
			 * Fires after the built-in questions, for adding custom questions.
			 *
			 * @since 1.0.0
			 */
			do_action( 'cptui_main_page_custom_questions' );
			$custom = trim( ob_get_clean() );
			if ( '' !== $custom ) {
				echo '<section class="cptui-support__section cptui-support__section--custom">' . $custom . '</section>'; // phpcs:ignore WordPress.Security.EscapeOutput
			}
			?>

			<p class="cptui-support__no-results" hidden><?php esc_html_e( 'No questions match your search. Try another word, or ask in the support forum.', 'custom-post-type-ui' ); ?></p>
		</div>

		<section class="cptui-support__stuck">
			<h2><?php esc_html_e( 'Still stuck?', 'custom-post-type-ui' ); ?></h2>
			<p>
				<?php
				printf(
					/* translators: 1: Link to the support forum. 2: Link to the Debug Info tool. */
					esc_html__( 'Post in the %1$s. Tell us what you expected, what happened instead, and which theme and plugins you use. Copying your settings from %2$s helps us answer faster.', 'custom-post-type-ui' ),
					cptui_support_link( $forum, __( 'support forum', 'custom-post-type-ui' ) ), // phpcs:ignore WordPress.Security.EscapeOutput
					cptui_support_link( $debug, __( 'Tools → Debug Info', 'custom-post-type-ui' ) ) // phpcs:ignore WordPress.Security.EscapeOutput
				);
				?>
			</p>
		</section>

		<?php
		/**
		 * Fires at the bottom of the FAQ/Support page.
		 *
		 * @since 1.0.0
		 */
		do_action( 'cptui_main_page_after_faq' );
		?>
	</div>
	<?php
}
