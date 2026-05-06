=== Custom Post Type UI ===
Contributors: webdevstudios, pluginize, tw2113, williamsba1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3084056
Tags: custom post types, post type, taxonomy, content types, types
Tested up to: 7.0
Stable tag: 1.19.1
License: GPL-2.0+

Admin UI for creating custom content types like post types and taxonomies

== Description ==

Custom Post Type UI provides an easy-to-use interface for registering and
managing custom post types and taxonomies for your WordPress website — no
coding required.

Used by more than 1,000,000 active WordPress sites, CPTUI is the trusted
foundation for building structured content in WordPress. Whether you are
creating a portfolio, events calendar, product catalog, staff directory, or
any other custom content type, CPTUI gives you the tools to define it quickly
and correctly.

= Ready to go further? Meet CPT UI Pro =

CPTUI handles registration. **CPT UI Pro** handles everything else.

[CPT UI Pro](https://pluginize.com/plugins/custom-post-type-ui-pro/?utm_source=cptui-description&utm_medium=text&utm_campaign=wporg)
is the official premium add-on built by the same WebDevStudios team that
created and maintains this plugin. It extends CPTUI into a complete content
operations stack — front-end display blocks, admin list table controls,
multisite management, and a full developer API.

**Front-end display tools**

* Ten built-in layout options: Default, List, Single Post Type, Taxonomy
  List, Post Slider, Post Cards, Featured Plus, Grid, and Grid With Overlay
* Taxonomy Filter Blocks so visitors can filter content by term
* Single Post Block Templates to control individual post output
* Shortcode Builder for reusable, configurable display output

**Admin list table management**

* Column Builder with drag-and-drop column control for any post type
* Advanced Filters by taxonomy, meta field, date, author, and status
* Screen Options Panel for per-user admin table visibility

**Multisite management**

* Network-level CPT registration from one central location
* Push config to subsites to keep site structures consistent
* Per-site inheritance control for flexible network governance

**Developer-focused workflow**

* `cptui_pro_post_types` filter to merge, modify, or replace registrations
* Auto-discovery hooks so themes and plugins can register CPTs without
  manual setup
* Programmatic registration API for JSON-driven or CI-driven workflows
* Extended REST API support on top of core CPTUI REST capabilities


[Get CPT UI Pro at Pluginize.com](https://pluginize.com/plugins/custom-post-type-ui-pro/?utm_source=cptui-description&utm_medium=text&utm_campaign=wporg)

= Plugin development =

Custom Post Type UI development is managed on GitHub, with official releases
published on WordPress.org. The GitHub repo can be found at
[https://github.com/WebDevStudios/custom-post-type-ui](https://github.com/WebDevStudios/custom-post-type-ui).
Please use the WordPress.org support tab for potential bugs, issues, or
enhancement ideas.

== Screenshots ==

1. Add new post type screen and tab.
2. Add new taxonomy screen and tab.
3. Registered post types and taxonomies from CPTUI.
4. Tools screen.
5. Help/support screen.

== Installation ==

= Admin installer via search =
1. Visit the Add New plugin screen and search for "custom post type ui".
2. Click the "Install Now" button.
3. Activate the plugin.
4. Navigate to the "CPTUI" menu.

= Admin installer via zip =
1. Visit the Add New plugin screen and click the "Upload Plugin" button.
2. Click the "Browse..." button and select the zip file from your computer.
3. Click "Install Now" button.
4. Once done uploading, activate Custom Post Type UI.

= Manual =
1. Upload the Custom Post Type UI folder to the plugins directory in your
   WordPress installation.
2. Activate the plugin.
3. Navigate to the "CPTUI" menu.

That's it! Now you can easily start creating custom post types and taxonomies
in WordPress.

== Frequently Asked Questions ==

= Where is the user documentation? =
Please see https://docs.pluginize.com/tutorials/custom-post-type-ui/

= I registered my post types — how do I display them on the front end? =
The free CPTUI plugin handles registration. To display your custom content
with block editor layouts, filters, and templates, check out
[CPT UI Pro](https://pluginize.com/plugins/custom-post-type-ui-pro/?utm_source=cptui-faq&utm_medium=text&utm_campaign=wporg).

= Does CPT UI Pro require this free plugin? =
Yes. CPT UI Pro is an add-on that requires Custom Post Type UI (free) to be
active. Your existing post type and taxonomy data carries over automatically
— no migration needed.

= Will the free plugin continue to be maintained? =
Absolutely. Custom Post Type UI will always be free and actively maintained.
CPT UI Pro is an optional add-on for users who want to go further.

= Does CPT UI Pro work with multisite? =
Yes. CPT UI Pro includes dedicated multisite support with network-level
registration, config push to subsites, and per-site inheritance controls.

== Changelog ==

= 1.19.1 - 2026-05-04 =
* Fixed: Escaped output on get code tab when no content types registered.
* Fixed: Force lowercase slugs on server side during save.
* Updated: Extra WPML support for labels.
* Updated: Premium product upsells.

= 1.19.0 - 2026-04-23 =
* Added: Support for "Filter by category" and "Filter by date" labels.
  Thanks aloMalbarez.
* Fixed: PHP notices from upsell notification display check.
* Updated: Various capitalization for CPTUI submenu labels.
* Updated: developer.wordpress.org URLs that had redirects.
* Updated: jQuery-less Dashicon picker.

= 1.18.3 - 2026-01-08 =
* Fixed: Remove double escaping in tools section for some output.
* Added: Dismissable upsell notifications for CPTUI Extended.

= 1.18.2 - 2025-12-05 =
* Fixed: Security issue around Get Code functionality.
* Fixed: Potential security issue around post type descriptions.
* Updated: Various internationalization details.

= 1.18.1 - 2025-11-20 =
* Fixed: Potential authorization access issues around content type
  modification.
* Fixed: JS issue regarding warning user when changing content type slugs.
* Updated: Revised "new tab" and rel="noopener" link behavior and attributes.
* Updated: Cleaned up CPTUI About screen.

= 1.18.0 - 2025-07-29 =
* Added: "template_name" label support.
* Fixed: Typo in "games" Dashicon classname.
* Updated: CPTUI admin ad graphics. Added ThemeSwitcher Pro.
* Updated: Touched up some label usage for post type registration screen.

= 1.17.3 - 2025-04-21 =
* Fixed: PHP notices around foreach loops in
  cptui_post_thumbnail_theme_support().
* Fixed: PHP notices around empty variable values with get code section.
* Fixed: PHP notices around false values with taxonomy listings with post
  type registration.
* Updated: Confirmed compatibility with WordPress 6.8.

= 1.17.2 - 2024-11-19 =
* Fixed: PHP warnings around empty description variables from tools page.
* Updated: Confirmed compatibility with WordPress 6.7.

= 1.17.1 - 2024-06-27 =
* Fixed: Missed re-showing of autolabel fill links for JS-enabled browsers.

= 1.17.0 - 2024-06-17 =
* Added: "sidebars" as a reserved slug for post types.
* Added: Blueprint for trying Custom Post Type UI on wordpress.org before
  installation.
* Updated: Reworked JavaScript files to be more modular with the build
  process.

= 1.16.0 - 2024-04-08 =
* Added: Added a wpml-config.xml file.
* Updated: Added "search_terms" to disallowed taxonomy list.
* Updated: Began converting JavaScript away from jQuery dependency.
* Updated: Tested up to WordPress 6.5.

= 1.15.1 - 2023-11-08 =
* Fixed: Right-to-Left language styling issues.
* Fixed: Forgot to update about page and some PHP constants for CPTUI
  version.

= 1.15.0 - 2023-11-06 =
* Added: Checkbox to indicate intent to migrate a post type into CPTUI in
  event of matching slugs. Props @ramsesdelr.
* Added: "item_trashed" post type label support from WordPress 6.3.
* Updated: Confirmed compatibility with WordPress 6.4.
* Updated: PHP 8 compatibility.
* Updated: Minimum WordPress version to 6.3, minimum PHP version to 7.4.

= 1.14.0 - 2023-08-07 =
* Added: "Scroll to top" links in CPTUI pages. Props @aslamatwebdevstudios.
* Added: Remembers toggled states for CPTUI settings panels. Props
  @aslamatwebdevstudios and @ramsesdelr.
* Updated: Notes about slugs for both post types and taxonomies.
* Updated: Support/FAQ section with more accurate links.
