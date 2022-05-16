=== Custom Post Type UI ===
Contributors: webdevstudios, pluginize, tw2113, vegasgeek, modemlooper, williamsba1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3084056
Tags: custom post types, CPT, CMS, post, types, post type, taxonomy, tax, custom, content types, post types
Requires at least: 5.9
Tested up to: 6.0
Stable tag: 1.12.1
License: GPL-2.0+
Requires PHP: 5.6

Admin UI for creating custom post types and custom taxonomies for WordPress

== Description ==

Custom Post Type UI provides an easy to use interface for registering and managing custom post types and taxonomies for your website.

= Custom Post Type UI Extended =

CPTUI helps create custom content types, but displaying that content can be a whole new challenge. We created [Custom Post Type UI Extended](https://pluginize.com/product/custom-post-type-ui-extended/?utm_source=cptui-desription&utm_medium=text&utm_campaign=wporg) to help with displaying your crafted content. [View our Layouts page](https://pluginize.com/cpt-ui-extended-features/?utm_source=cptui-description-examples&utm_medium=text&utm_campaign=wporg) to see available layout examples with Custom Post Type UI Extended.

Beginning with version 1.7.0, Custom Post Type UI Extended has properly moved in to the Block editor experience and is working to get all the layouts available in the new "Custom Post Type UI Block". It's now even easier to start showing your content with the existing and future layouts available with Custom Post Type UI Extended.

[Pluginize](https://pluginize.com/?utm_source=cptui&utm_medium=text&utm_campaign=wporg) was launched in 2016 by [WebDevStudios](https://webdevstudios.com/) to promote, support, and house all of their [WordPress products](https://pluginize.com/shop/?utm_source=cptui-&utm_medium=text&utm_campaign=wporg). Pluginize is not only [creating new products for WordPress, like CPTUI Extended](https://pluginize.com/product/custom-post-type-ui-extended/?utm_source=cptui&utm_medium=text&utm_campaign=wporg), but also provides ongoing support and development for WordPress community favorites like [CMB2](https://wordpress.org/plugins/cmb2/) and more.

= Plugin development =

Custom Post Type UI development is managed on GitHub, with official releases published on WordPress.org. The GitHub repo can be found at [https://github.com/WebDevStudios/custom-post-type-ui](https://github.com/WebDevStudios/custom-post-type-ui). Please use the WordPress.org support tab for potential bugs, issues, or enhancement ideas.

== Screenshots ==

1. Add new post type screen and tab.
2. Add new taxonomy screen and tab.
3. Registered post types and taxonomies from CPTUI
4. Tools screen.
5. Help/support screen.

== Changelog ==

= 1.12.1 - 2022-05-16 =
* Fixed: JSON decoding issues around WP-CLI import.

= 1.12.0 - 2022-05-09 =
* Added: Tested on WordPress 6.0.
* Added: Auto-check, but not force, "page-attributes" when setting a post type as hierarchical.
* Added: "rest_namespace" parameters for both post types and taxonomies.
* Added: "register_meta_box_cb" parameter for post types.
* Fixed: undefined index notices for "can_export".
* Updated: list of reserved taxonomy names with more that are considered reserved.
* Updated: readme descriptions and screenshots.

= 1.11.2 - 2022-03-21 =
* Fixed: Unintended reuse of `$delete_with_user` variable and `can_export` option. Props @bogutskyy
* Fixed: PHP notices around `sort` array index.

= 1.11.1 - 2022-03-18 =
* Fixed: Errors and warnings around array_key_exists() and bool values

= 1.11.0 - 2022-03-17 =
* Added: "sort" argument for taxonomies.
* Added: "can export" argument for post types
* Added: New taxonomy labels from WordPress 5.9
* Added: Custom option to set "Enter title here" value for post types.
* Added: Notes around "exclude from search" argument for post types and taxonomy term archives.
* Added: Notes around taxonomy "hierarchical" option regarding category vs tag behavior.
* Updated: Reserved post type slugs from recent WordPress releases.
* Fixed: PHP warnings around foreach loops in cptui_published_post_format_fix()

= 1.10.2 - 2022-01-28 =
* Updated: Confirmed compatibility with WordPress 5.9

= 1.10.1 - 2021-12-07 =
* Added: Filter to our PHP Global sanitization function for Extended usage.

= 1.10.0 - 2021-10-04 =
* Added: Dashicon picker with popup. - Props arshidkv12
* Added: Tag Cloud widget support for custom taxonomies.
* Added: Filters that allow developers to override the data fetched from our options, if they choose to.
* Added: Ability to clear all filled in label values.
* Fixed: Hardened up various parts of our code to ensure security.
* Fixed: Incorrectly referenced variable from post types instead of taxonomies, for the rest_controller_class property.

== Upgrade Notice ==

= 1.12.1 - 2022-05-16 =
* Fixed: JSON decoding issues around WP-CLI import.

= 1.12.0 - 2022-05-09 =
* Added: Tested on WordPress 6.0.
* Added: Auto-check, but not force, "page-attributes" when setting a post type as hierarchical.
* Added: "rest_namespace" parameters for both post types and taxonomies.
* Added: "register_meta_box_cb" parameter for post types.
* Fixed: undefined index notices for "can_export".
* Updated: list of reserved taxonomy names with more that are considered reserved.
* Updated: readme descriptions and screenshots.

= 1.11.2 - 2022-03-21 =
* Fixed: Unintended reuse of `$delete_with_user` variable and `can_export` option. Props @bogutskyy
* Fixed: PHP notices around `sort` array index.

= 1.11.1 - 2022-03-18 =
* Fixed: Errors and warnings around array_key_exists() and bool values

= 1.11.0 - 2022-03-17 =
* Added: "sort" argument for taxonomies.
* Added: "can export" argument for post types
* Added: New taxonomy labels from WordPress 5.9
* Added: Custom option to set "Enter title here" value for post types.
* Added: Notes around "exclude from search" argument for post types and taxonomy term archives.
* Added: Notes around taxonomy "hierarchical" option regarding category vs tag behavior.
* Updated: Reserved post type slugs from recent WordPress releases.
* Fixed: PHP warnings around foreach loops in cptui_published_post_format_fix()

= 1.10.2 - 2022-01-28 =
* Updated: Confirmed compatibility with WordPress 5.9

= 1.10.1 - 2021-12-07 =
* Added: Filter to our PHP Global sanitization function for Extended usage.

= 1.10.0 - 2021-10-04 =
* Added: Dashicon picker with popup. - Props arshidkv12
* Added: Tag Cloud widget support for custom taxonomies.
* Added: Filters that allow developers to override the data fetched from our options, if they choose to.
* Added: Ability to clear all filled in label values.
* Fixed: Hardened up various parts of our code to ensure security.
* Fixed: Incorrectly referenced variable from post types instead of taxonomies, for the rest_controller_class property.

== Installation ==

= Admin Installer via search =
1. Visit the Add New plugin screen and search for "custom post type ui".
2. Click the "Install Now" button.
3. Activate the plugin.
4. Navigate to the "CPTUI" Menu.

= Admin Installer via zip =
1. Visit the Add New plugin screen and click the "Upload Plugin" button.
2. Click the "Browse..." button and select zip file from your computer.
3. Click "Install Now" button.
4. Once done uploading, activate Custom Post Type UI.

= Manual =
1. Upload the Custom Post Type UI folder to the plugins directory in your WordPress installation.
2. Activate the plugin.
3. Navigate to the "CPTUI" Menu.

That's it! Now you can easily start creating custom post types and taxonomies in WordPress.

== Frequently Asked Questions ==

#### User documentation
Please see http://docs.pluginize.com/category/126-custom-post-type-ui

#### Code/API documentation
Please see http://codex.pluginize.com/cptui/
