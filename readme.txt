=== Custom Post Type UI ===
Contributors: webdevstudios, pluginize, tw2113, williamsba1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3084056
Tags: custom post types, post type, taxonomy, content types, CPT, CMS, post, types, custom
Requires at least: 5.9
Tested up to: 6.2.2
Stable tag: 1.13.7
License: GPL-2.0+
Requires PHP: 5.6

Admin UI for creating custom content types like post types and taxonomies

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

= 1.13.7 - 2023-07-11 =
* Fixed: "themes" marked as reserved taxonomy slug. Causes issues with featured image metabox.
* Fixed: PHP notice around `sort` parameter.

= 1.13.6 - 2023-05-30 =
* Fixed: Prevent PHP errors for dynamic WordPress hooks.
* Fixed: Prevent PHP errors from array_key_exist() checks on non arrays.
* Updated: Removed Maintainn graphic and added WP Search with Algolia Pro graphic.
* Updated: Fixed a lot of text escaping for translation-ready content.

= 1.13.5 - 2023-03-27 =
* Fixed: Security issue in CPTUI Debug Info screen.
* Fixed: Added `empty()` check for `can_export` parameters.
* Updated: Changed textdomain loading from `plugins_loaded` to `init`.

= 1.13.4 - 2022-12-16 =
* Fixed: Character encoding issue on CPTUI setting save in conjunction with PHP8 compatibility.

= 1.13.3 - 2022-12-15 =
* Fixed: Multiple PHP8 compatibility notices and warnings.
* Fixed: "Invalid argument for foreach" based notices around labels.
* Updated: Added taxonomy PHP global sanitization for 3rd party parameters.

= 1.13.2 - 2022-11-29 =
* Fixed: Removed forcing of underscores in place of dashes for taxonomy slugs. Yay!
* Updated: tested up to WP 6.1.1
* Updated: Documentation links in wordpress.org FAQ section.

= 1.13.1 - 2022-09-08 =
* Fixed: Various issues caused by a misplaced output for `ob_get_clean()` outside of function.

= 1.13.0 - 2022-09-07 =
* Added: Notes regarding featured image and post format support also needing `add_theme_support` to work.
* Fixed: Issues around double quotes and JSON export with the post type description field
* Fixed: Issues around HTML markup being removed from post type description field stemming from 1.10.x release
* Fixed: Pluralization issue with our UI for some field labels
* Updated: Code separation and quality cleanup.
* Updated: Plugin branding.

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

== Upgrade Notice ==

= 1.13.7 - 2023-07-11 =
* Fixed: "themes" marked as reserved taxonomy slug. Causes issues with featured image metabox.
* Fixed: PHP notice around `sort` parameter.

= 1.13.6 - 2023-05-30 =
* Fixed: Prevent PHP errors for dynamic WordPress hooks.
* Fixed: Prevent PHP errors from array_key_exist() checks on non arrays.
* Updated: Removed Maintainn graphic and added WP Search with Algolia Pro graphic.
* Updated: Fixed a lot of text escaping for translation-ready content.

= 1.13.5 - 2023-03-27 =
* Fixed: Security issue in CPTUI Debug Info screen.
* Fixed: Added `empty()` check for `can_export` parameters.
* Updated: Changed textdomain loading from `plugins_loaded` to `init`.

= 1.13.4 - 2022-12-16 =
* Fixed: Character encoding issue on CPTUI setting save in conjunction with PHP8 compatibility.

= 1.13.3 - 2022-12-15 =
* Fixed: Multiple PHP8 compatibility notices and warnings.
* Fixed: "Invalid argument for foreach" based notices around labels.
* Updated: Added taxonomy PHP global sanitization for 3rd party parameters.

= 1.13.2 - 2022-11-29 =
* Fixed: Removed forcing of underscores in place of dashes for taxonomy slugs. Yay!
* Updated: tested up to WP 6.1.1
* Updated: Documentation links in wordpress.org FAQ section.

= 1.13.1 - 2022-09-08 =
* Fixed: Various issues caused by a misplaced output for `ob_get_clean()` outside of function.

= 1.13.0 - 2022-09-07 =
* Added: Notes regarding featured image and post format support also needing `add_theme_support` to work.
* Fixed: Issues around double quotes and JSON export with the post type description field
* Fixed: Issues around HTML markup being removed from post type description field stemming from 1.10.x release
* Fixed: Pluralization issue with our UI for some field labels
* Updated: Code separation and quality cleanup.
* Updated: Plugin branding.

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
Please see https://docs.pluginize.com/tutorials/custom-post-type-ui/
