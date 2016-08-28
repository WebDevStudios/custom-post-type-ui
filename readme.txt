=== Custom Post Type UI ===
Contributors: webdevstudios, tw2113, vegasgeek, modemlooper, williamsba1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3084056
Tags: custom post types, CPT, CMS, post, types, post type, cck, taxonomy, tax, custom, content types, post types
Requires at least: 4.2
Tested up to: 4.6
Stable tag: 1.4.1
License: GPLv2

Admin UI for creating custom post types and custom taxonomies in WordPress

== Description ==

This plugin provides an easy to use interface for creating and administrating custom post types and taxonomies in WordPress.  This plugin is created for WordPress 3.0 and higher.

Please note that Custom Post Type UI alone will not display post types or taxonomies data in customized places within your site; it simply registers them for you to use. Check out [Custom Post Type UI Extended](https://pluginize.com/product/custom-post-type-ui-extended/?utm_source=cptui-desription&utm_medium=text&utm_campaign=wporg) for an easy way to display post type content from any registered types on your site, including those created with Custom Post Type UI and more.

All official development on this plugin is on GitHub. New releases are still published here on WordPress.org. The version shown here should be considered the latest stable release. You can find the repo at [https://github.com/WebDevStudios/custom-post-type-ui](https://github.com/WebDevStudios/custom-post-type-ui). Please file confirmed issues, bugs, and enhancement ideas there, when possible.

[Pluginize](https://pluginize.com/?utm_source=cptui&utm_medium=text&utm_campaign=wporg) was launched in 2016 by [WebDevStudios](https://webdevstudios.com/) to promote, support, and house all of their [WordPress products](https://pluginize.com/shop/?utm_source=cptui-&utm_medium=text&utm_campaign=wporg). Pluginize is not only [creating new products for WordPress all the time, like CPTUI Extended](https://pluginize.com/product/custom-post-type-ui-extended/?utm_source=cptui&utm_medium=text&utm_campaign=wporg), but also provides ongoing support and development for WordPress community favorites like [CMB2](https://wordpress.org/plugins/cmb2/) and more.

== Screenshots ==

1. Add new post type screen and tab.
2. Add new taxonomy screen and tab.
3. Registered post types and taxonomies from CPTUI
4. Import/Export Post Types screen.
5. Get Code screen.
6. Help/support screen.

== Changelog ==

= 1.4.1 - 2016-8-25 =
* Fixed: issue with default values for new parameters regarding menu/nav menu display for taxonomies.
* Fixed: typo in support area.

= 1.4.0 - 2016-8-22 =
* Added: "Export" tab on editor screens for quick access to post type or taxonomy export pages.
* Added: CPTUI notices are now dismissable via a button on the right side.
* Added: "Get code" link to registered post types and registered taxonomies listings.
* Added: More amending of incorrect characters in post type and taxonomy slugs. Latin standard alphabet only. Sorry.
* Added: New post type template stack reference from recent WordPress versions.
* Added: Side warning notification if post type or taxonomy slug has been edited.
* Added: Display About page upon activation of plugin.
* Added: Link below ads regarding getting them removed via purchase of CPTUI Extended.
* Added: No need to refresh page after initial save to see post types and taxonomies in menu.
* Added: Taxonomy support for show_in_menu and show_in_nav_menus.
* Fixed: Further improved labels for information text on inputs.
* Fixed: Hide "choose icon" button for non-js users.
* Fixed: Issue with misused "parent" label key that should be parent_item_colon.
* Fixed: Missed show_in_menu_string parameter for "get code" area.
* Fixed: Make sure taxonomies have required post type associated.
* Fixed: "Edit" links in listings area now account for network-admin when needed, with CPTUI Extended.
* Updated: Switch to dedicated dashicon for color consistency between applied admin color schemes.
* Updated: Updated about page.
* Updated: Further UI refinements to better match WordPress admin. Adapted styles found from metaboxes, including collapse/expand toggles.

= 1.3.5 - 2016-6-3 =
* Removed undefined index error for publicly_queryable in "Get Code" area. That parameter is targeted for 1.4.0 release.

= 1.3.4 - 2016-5-4 =
* Fixed: moved WDS-based services "ads" to within the plugin itself. Will not request remote resources.
* Fixed: Better output formatting if WDS/Pluginize "ads" failed to load images.
* Fixed: undefined variable error in cptui.js
* Added: Newsletter subscription form to stay uptodate with Custom Post Type UI &amp; Custom Post Type UI Extended news.
* Added: Support page/FAQ info regarding Pluginize and recent sidebar developments.

= 1.3.3 - 2016-4-5 =
* Revert Changes for ajax/heartbeat API requests before post type registration. 3rd party or other plugins were breaking because post types were not registered.

= 1.3.2 - 2016-4-5 =
* Fixed: Logic issue with cptui js files loading where they weren't meant to.
* Fixed: Required markers missing on required post type fields.
* Fixed: Removed excess labels that are not used by WordPress core.
* Added: New contributors to readme file. Welcome John and Ryan.
* Updated: New screenshot from 1.3.0 release. Moved to assets folder so users will no longer download as part of CPTUI.
* Updated: Better prevention of running our code during ajax/heartbeat api requests.

= 1.3.1 - 2016-3-25 =
* Fixed: Logic issue for default values of `public` parameter for taxonomies added in 1.3.0.

= 1.3.0 - 2016-3-24 =
* Added: "CPTUI_VERSION" constant and deprecated "CPT_VERSION".
* Added: "Public" parameter for taxonomies
* Added: "View Post Types" and "View Taxonomies" tabs at top of add/edit screens.
* Added: Better prevention of potential duplicate slugs in new post types and taxonomies.
* Added: Current theme's textdomain as output in get code textareas.
* Added: Fill in singular and plural label fields if none provided. WordPress does not auto-fill these.
* Added: For developers: plenty of extra hooks all over for customization needs.
* Added: Javascript-based prevention of spaces and special characters for post type and taxonomy slugs.
* Added: Legend tag support to admin UI class.
* Added: Minified copies of our JavaScript and CSS. Define SCRIPT_DEBUG to true to use non-minified versions.
* Added: New post type and taxonomy labels provided by WordPress 4.3 and 4.4 releases.
	* See: https://make.wordpress.org/core/2015/12/11/additional-labels-for-custom-post-types-and-custom-taxonomies/
* Added: Notes to post type and taxonomy edit screens about WordPress core's post types and taxonomies.
* Added: Taxonomy slug update ability with preserved term association.
* Added: Title, Editor, and Featured Image now checked by default for new post types.
* Added: "Show in Quick Edit" taxonomy parameter available in WP 4.2
* Added: Promo spots on add/edit screens for other products from WebDevStudios.
* Fixed: Need to visit permalinks page to flush rewrite rules after creating new post type or taxonomy.
* Fixed: Missing REST API based parameters in "Get Code" output.
* Updated: Increased accessibility coverage.
* Updated: Revised how tabs are added to pages so 3rd party developers can add their own tabs.
* Updated: Improved string consistency in our UI helper notes. Props @GaryJ
* Updated: Tested on WordPress 4.5
* Updated: Cleaned up admin footer area for social links.
* Updated: Moved all localization work to WordPress.org Translation packs

= 1.2.4 =
* Added: new CPTUI_VERSION constant to match naming of other current constants.
* Added: CPTUI_VERSION constant to cptui.css string for cache busting.

= 1.2.3 - 2016-01-31 =
* Fixed: copy/paste error with admin css. Props hinaloe.

= 1.2.2 - 2016-01-30 =
* Fixed: Missing admin menu icon for some browsers.
* Fixed: Undefined index notices for post type screen.

= 1.2.1 - 2016-01-17 =
* Fixed: Undefined index notices for custom taxonomies and new fields from 1.2.0

= 1.2.0 - 2016-01-15 =
* Added: Support for show_in_nav_menus parameter for post types.
* Added: Support for taxonomy descriptions.
* Added: Message on listings page if no post types or taxonomies are available.
* Added: Note regarding 'public' parameter not being true by default for WordPress but is for CPTUI.
* Added: Individual post type and taxonomy output for "Get Code" area.
* Added: Fallback values for post type/taxonomy select input and get code area if no plural label provided.
* Added: Support for custom query_var strings for post types.
* Added: Support for show_in_rest and rest_base for post types and taxonomies for the upcoming WordPress REST API.
* Added: Template hierarchy files to listings tables for user reference.
* Added: Form labels in more areas to help with accessibility and usability.
* Added: Fieldset method to Admin UI class for easily adding fieldset output.
* Added: Debugging tab for use with CPTUI support.
* Updated: Minimum version required. Now WordPress 4.2.
* Updated: Heading tags match accessibility changes in WordPress 4.3.
* Updated: Moved page title for some pages above tabs.
* Updated: Textdomain now matches WordPress.org repo slug.
* Updated: Added Visual Composer questions/answers to support section.
* Updated: Added question/answer regarding spaces in post type slugs
* Updated: Changed help icons to a Dashicon help icon.
* Fixed: Support for YARPP plugin.
* Fixed: Prevent potential issues with AJAX requests and our scripts/styles

== Upgrade Notice ==

= 1.4.1 - 2016-8-25 =
* Fixed: issue with default values for new parameters regarding menu/nav menu display for taxonomies.
* Fixed: typo in support area.

= 1.4.0 - 2016-8-22 =
* Added: "Export" tab on editor screens for quick access to post type or taxonomy export pages.
* Added: CPTUI notices are now dismissable via a button on the right side.
* Added: "Get code" link to registered post types and registered taxonomies listings.
* Added: More amending of incorrect characters in post type and taxonomy slugs. Latin standard alphabet only. Sorry.
* Added: New post type template stack reference from recent WordPress versions.
* Added: Side warning notification if post type or taxonomy slug has been edited.
* Added: Display About page upon activation of plugin.
* Added: Link below ads regarding getting them removed via purchase of CPTUI Extended.
* Added: No need to refresh page after initial save to see post types and taxonomies in menu.
* Added: Taxonomy support for show_in_menu and show_in_nav_menus.
* Fixed: Further improved labels for information text on inputs.
* Fixed: Hide "choose icon" button for non-js users.
* Fixed: Issue with misused "parent" label key that should be parent_item_colon.
* Fixed: Missed show_in_menu_string parameter for "get code" area.
* Fixed: Make sure taxonomies have required post type associated.
* Fixed: "Edit" links in listings area now account for network-admin when needed, with CPTUI Extended.
* Updated: Switch to dedicated dashicon for color consistency between applied admin color schemes.
* Updated: Updated about page.
* Updated: Further UI refinements to better match WordPress admin. Adapted styles found from metaboxes, including collapse/expand toggles.

= 1.3.5 - 2016-6-3 =
* Removed undefined index error for publicly_queryable in "Get Code" area. That parameter is targeted for 1.4.0 release.

= 1.3.4 - 2016-5-4 =
* Fixed: moved WDS-based services "ads" to within the plugin itself. Will not request remote resources.
* Fixed: Better output formatting if WDS/Pluginize "ads" failed to load images.
* Fixed: undefined variable error in cptui.js
* Added: Newsletter subscription form to stay uptodate with Custom Post Type UI &amp; Custom Post Type UI Extended news.
* Added: Support page/FAQ info regarding Pluginize and recent sidebar developments.

= 1.3.3 - 2016-4-5 =
* Revert Changes for ajax/heartbeat API requests before post type registration. 3rd party or other plugins were breaking because post types were not registered.

= 1.3.2 - 2016-4-5 =
* Fixed: Logic issue with cptui js files loading where they weren't meant to.
* Fixed: Required markers missing on required post type fields.
* Fixed: Removed excess labels that are not used by WordPress core.
* Added: New contributors to readme file. Welcome John and Ryan.
* Updated: New screenshot from 1.3.0 release. Moved to assets folder so users will no longer download as part of CPTUI.
* Updated: Better prevention of running our code during ajax/heartbeat api requests.

= 1.3.1 - 2016-3-25 =
* Fixed: Logic issue for default values of `public` parameter for taxonomies added in 1.3.0.

= 1.3.0 =
* Added: "CPTUI_VERSION" constant and deprecated "CPT_VERSION".
* Added: "Public" parameter for taxonomies
* Added: "View Post Types" and "View Taxonomies" tabs at top of add/edit screens.
* Added: Better prevention of potential duplicate slugs in new post types and taxonomies.
* Added: Current theme's textdomain as output in get code textareas.
* Added: Fill in singular and plural label fields if none provided. WordPress does not auto-fill these.
* Added: For developers: plenty of extra hooks all over for customization needs.
* Added: Javascript-based prevention of spaces and special characters for post type and taxonomy slugs.
* Added: Legend tag support to admin UI class.
* Added: Minified copies of our JavaScript and CSS. Define SCRIPT_DEBUG to true to use non-minified versions.
* Added: New post type and taxonomy labels provided by WordPress 4.3 and 4.4 releases.
	* See: https://make.wordpress.org/core/2015/12/11/additional-labels-for-custom-post-types-and-custom-taxonomies/
* Added: Notes to post type and taxonomy edit screens about WordPress core's post types and taxonomies.
* Added: Taxonomy slug update ability with preserved term association.
* Added: Title, Editor, and Featured Image now checked by default for new post types.
* Added: "Show in Quick Edit" taxonomy parameter available in WP 4.2
* Added: Promo spots on add/edit screens for other products from WebDevStudios.
* Fixed: Need to visit permalinks page to flush rewrite rules after creating new post type or taxonomy.
* Fixed: Missing REST API based parameters in "Get Code" output.
* Updated: Increased accessibility coverage.
* Updated: Revised how tabs are added to pages so 3rd party developers can add their own tabs.
* Updated: Improved string consistency in our UI helper notes. Props @GaryJ
* Updated: Tested on WordPress 4.5
* Updated: Cleaned up admin footer area for social links.
* Updated: Moved all localization work to WordPress.org Translation packs

== Installation ==

= Manual =
1. Upload the Custom Post Type UI folder to the plugins directory in your WordPress installation
2. Activate the plugin.
3. Navigate to the "CPTUI" Menu.

= Admin Installer =
1. Visit the Add New plugin screen and search for "custom post type ui"
2. Click the "Install Now" button.
3. Activate the plugin.
4. Navigate to the "CPTUI" Menu.

That's it! Now you can easily start creating custom post types and taxonomies in WordPress

== Frequently Asked Questions ==

User documentation: http://docs.pluginize.com/collection/1-custom-post-type-ui
Code/API documentation: http://codex.pluginize.com/cptui/

== Other Notes ==

Import/Export functionality amended from original contribution by [Ben Allfree](http://wordpress.org/support/profile/benallfree).
