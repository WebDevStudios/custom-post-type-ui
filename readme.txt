=== Custom Post Type UI ===
Contributors: webdevstudios, pluginize, tw2113, williamsba1
Tags: custom post types, custom taxonomies, post type, taxonomy, content types
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.19.4
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Create custom post types and taxonomies from the WordPress admin, no code needed. Trusted by more than 1 million sites.

== Description ==

Custom Post Type UI (CPT UI) gives you a simple screen for creating and managing custom post types and taxonomies, without writing code. More than 1 million WordPress sites use it to build portfolios, event listings, product catalogs, staff directories, real estate listings and any other structured content.

= What you can do with CPT UI =

* Register post types and taxonomies with the options WordPress supports, from labels and menu icons to the REST API, archives and rewrite rules.
* Fill in every label automatically from a singular and plural name.
* Attach taxonomies to any post type, including Posts and Pages.
* Rename a post type and move its existing posts to the new name.
* Copy your setup to another site with import and export.
* Get the PHP code for any post type or taxonomy. Your content is never locked in.
* Manage settings with WP-CLI, and use your content types with WPGraphQL and WPML.

CPT UI registers your content types. How they look on your site is up to your theme, the block editor's Query Loop block, or CPT UI Pro.

= CPT UI Pro: make your content easy to run =

[CPT UI Pro](https://pluginize.com/plugins/custom-post-type-ui-pro/?utm_source=wporg-description&utm_medium=readme&utm_campaign=cptui) is the premium add-on from the WebDevStudios team that builds CPT UI. It works on any post type: ones made with CPT UI, registered in code, or added by other plugins. See it in screenshots 6 to 9.

**See every field without opening a post**

* Add any core field, taxonomy or custom field as a list column, and drag to reorder.
* ACF fields show the way people read them: images as thumbnails, users as names, dates in your format and choices as labels.
* Filter lists by text, numbers, dates, terms, author and status.
* Each user can show or hide columns for themselves, and taxonomy term screens get columns and filters too.

**Give clients admin access, keep the structure to yourself**

* Choose which administrators can manage CPT UI. Everyone else never sees the menu.
* Brand the CPT UI Pro menu with your own name and icon.

**Show any post type on your site without templates**

* A block and shortcode with 13 layouts, including grid, slider, post cards, lists, and WooCommerce and Easy Digital Downloads products.
* Copy any layout into your theme to change its markup.
* Add post types to blog, category and tag archives and RSS feeds with a checkbox.

**Multisite and developers**

* Register post types and taxonomies once in Network Admin, and every site in the network gets them.
* Documented hooks for columns, filters, fields and post type data.

Every Pro plan includes priority support from the CPT UI developers and a 30-day refund guarantee.

[See everything in CPT UI Pro](https://pluginize.com/plugins/custom-post-type-ui-pro/?utm_source=wporg-description-cta&utm_medium=readme&utm_campaign=cptui)

= Built by WebDevStudios =

WebDevStudios has built WordPress sites since 2008, from small businesses to brands like Microsoft and the NBA. The engineers who do that client work build and maintain CPT UI, and our other plugins are on [Pluginize](https://pluginize.com/?utm_source=wporg-description&utm_medium=readme&utm_campaign=cptui).

= Plugin development =

Development happens on [GitHub](https://github.com/WebDevStudios/custom-post-type-ui), with official releases published on WordPress.org. Please use the WordPress.org support forum for bugs, questions and ideas.

== Screenshots ==

1. Add a post type. Fill in a slug and two labels, and CPT UI completes the rest.
2. Add a taxonomy and attach it to any post type.
3. Every post type and taxonomy registered with CPT UI, with links to edit them or get their code.
4. Tools: import and export settings, get code, and debug info.
5. Searchable help, grouped by task.
6. CPT UI Pro (separate plugin): your custom fields as list columns, with photos, prices and filters.
7. CPT UI Pro (separate plugin): choose, reorder and hide list columns, including ACF fields.
8. CPT UI Pro (separate plugin): show any post type on your site with a block, using one of 13 layouts.
9. CPT UI Pro (separate plugin): choose which administrators can manage CPT UI.

== Installation ==

1. In your WordPress admin, go to Plugins and click Add New.
2. Search for "Custom Post Type UI", then click Install Now and Activate.
3. Open the CPT UI menu and add your first post type.

To install from a zip file, go to Plugins, click Add New, then Upload Plugin, and choose the zip.

== Frequently Asked Questions ==

= Where is the documentation? =
See the [CPT UI documentation](https://docs.pluginize.com/tutorials/custom-post-type-ui/). The plugin's Help screen also answers the most common questions.

= How do I show my post types on my site? =
Set "Has Archive" to True to get an archive page, or add a Query Loop block to any page and choose your post type. For ready-made layouts and no theme templates, see [CPT UI Pro](https://pluginize.com/plugins/custom-post-type-ui-pro/?utm_source=wporg-faq&utm_medium=readme&utm_campaign=cptui#display).

= My post type shows "Page not found". What do I do? =
Go to Settings, then Permalinks, and click Save Changes. WordPress rebuilds its URL rules and that fixes most 404s.

= Can I stop using CPT UI and keep my post types? =
Yes. CPT UI, then Tools, then Get Code gives you the PHP that registers each post type and taxonomy. Add it to your theme or a plugin and deactivate CPT UI. Your content is untouched.

= Does CPT UI Pro require this free plugin? =
No. Pro's columns, filters, access control and display tools work on any post type. You need the free plugin only to create post types and taxonomies in the admin.

= Does CPT UI Pro work with multisite? =
Yes. You can register post types and taxonomies once in Network Admin for every site, while each site can still add its own.

= Does CPT UI show ads? =
The plugin's own screens show information about CPT UI Pro and other WebDevStudios plugins. Notices can be dismissed, nothing is loaded from another site, and everything is hidden when CPT UI Pro is active. Developers can turn it off with the `cptui_show_pro_promos` filter.

= Will the free plugin stay free? =
Yes. Custom Post Type UI will always be free and actively maintained.

== External services ==

This plugin connects to one external service, and only when a site administrator chooses to use it.

= Pluginize newsletter (FluentCRM on pluginize.com) =
The optional "Get Custom Post Type UI updates" signup form, shown on the CPT UI screens, subscribes you to email updates about the plugin from Pluginize, the WebDevStudios plugin store.

* What is sent: only the email address typed into the form.
* When: only when an administrator clicks Subscribe. Nothing is sent when the form is displayed, and no other site or user data is included.
* Where: a FluentCRM webhook on pluginize.com.

You can unsubscribe from any email. Pluginize [terms](https://pluginize.com/terms/) and [privacy policy](https://pluginize.com/privacy/).

Links to WebDevStudios products in the plugin's admin screens are ordinary links with UTM tracking parameters. They send nothing unless you click one, and they never load content from another site.

== Changelog ==

= 1.19.4 - 2026-11-16 =
* Fixed: Users given access to CPT UI through the `cptui_required_capabilities` filter can now save, delete and import post types and taxonomies.
* Fixed: New post types are only blocked for matching a page slug when their archive URL would actually collide with that page. Archive and rewrite slugs that collide are now caught too.
* Fixed: Browsers now load updated CPT UI scripts and styles right after an update.
* Updated: Rebuilt the Help page with searchable answers grouped by task, corrected and new FAQs, and links to individual answers.
* Updated: Redesigned the About page with a compact header and recommendations based on what your site runs.
* Updated: Refreshed CPT UI Pro information, including a feature tour, a free vs Pro comparison page and notices that can be dismissed for 90 days. All of it is hidden when CPT UI Pro is active.
* Updated: The newsletter signup now uses Pluginize directly and no longer loads third-party scripts in the admin. See "External services" in this readme.
* Added: `cptui_show_pro_promos` filter to turn off Pro promotions.

= 1.19.3 - 2026-07-14 =
* Fixed: Debug info email failing to send due to nonce field name being passed to `wp_verify_nonce()` instead of the nonce field value. Props @thisismyurl.
* Fixed: Export textareas for post types and taxonomies outputting unescaped content; use `esc_textarea()` for correct HTML encoding. Props @thisismyurl.
* Updated: Revised how we're registering strings with WPML for better performance.

= 1.19.2 - 2026-05-07 =
* Fixed: JS error with changed icon picker on settings pages without an icon picker.
* Updated: CPTUI-Extended promotion has been changed to CPTUI-Pro new addon.

See [CHANGELOG.md](https://github.com/WebDevStudios/custom-post-type-ui/blob/master/CHANGELOG.md) for older releases.

== Upgrade Notice ==

= 1.19.4 =
Fixes saving for users given CPT UI access through a custom capability, and makes the page slug check smarter. Adds a rebuilt Help page and About page.
