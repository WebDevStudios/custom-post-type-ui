=== Custom Post Type UI ===
Contributors: webdevstudios, pluginize, tw2113, williamsba1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3084056
Tags: custom post types, post type, taxonomy, content types, types
Tested up to: 7.0
Stable tag: 1.19.2
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
No. CPT UI Pro does not require Custom Post Type UI (free), but pairs great. Your existing post type and taxonomy content integrates automatically
— no migration needed.

= Will the free plugin continue to be maintained? =
Absolutely. Custom Post Type UI will always be free and actively maintained.
CPT UI Pro is an optional add-on for users who want to go further.

= Does CPT UI Pro work with multisite? =
Yes. CPT UI Pro includes dedicated multisite support with network-level
registration, config push to subsites, and per-site inheritance controls.

== Changelog ==

= 1.19.2 - 2026-05-07 =
* Fixed: JS error with changed icon picker on settings pages without an icon picker.
* Updated: CPTUI-Extended promotion has been changed to CPTUI-Pro new addon.

= 1.19.1 - 2026-05-04 =
* Fixed: Escaped output on get code tab when no content types registered.
* Fixed: Force lowercase slugs on server side during save.
* Updated: Extra WPML support for labels.
* Updated: Pro product upsells.

= 1.19.0 - 2026-04-23 =
* Added: Support for "Filter by category" and "Filter by date" labels. Thanks aloMalbarez.
* Fixed: PHP notices from upsell notification display check.
* Updated: Various capitalization for CPTUI submenu labels.
* Updated: developer.wordpress.org URLs that had redirects.
* Updated: jQuery-less Dashicon picker.

= 1.18.3 - 2026-01-08 =
* Fixed: Remove double escaping in tools section for some output.
* Added: Dismissable upsell notifications for CPTUI Pro.

= 1.18.2 - 2025-12-05 =
* Fixed: Security issue around Get Code functionality.
* Fixed: Potential security issue around post type descriptions.
* Updated: Various internationalization details.

= 1.18.1 - 2025-11-20 =
* Fixed: Potential authorization access issues around content type modification.
* Fixed: JS issue regarding warning user when changing content type slugs.
* Updated: Revised "new tab" and rel="noopener" link behavior and attributes.
* Updated: Cleaned up CPTUI About screen.

= 1.18.0 - 2025-07-29 =
* Added: "template_name" label support.
* Fixed: Typo in "games" Dashicon classname.
* Updated: CPTUI admin ad graphics. Added ThemeSwitcher Pro.
* Updated: Touched up some label usage for post type registration screen.
