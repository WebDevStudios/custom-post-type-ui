=== Custom Post Type UI ===
Contributors: webdevstudios, pluginize, tw2113, vegasgeek, modemlooper, williamsba1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3084056
Tags: custom post types, CPT, CMS, post, types, post type, taxonomy, tax, custom, content types, post types
Requires at least: 5.5
Tested up to: 5.6.0
Stable tag: 1.8.2
License: GPL-2.0+
Requires PHP: 5.6

Admin UI for creating custom post types and custom taxonomies for WordPress

== Description ==

Custom Post Type UI provides an easy to use interface for registering and managing custom post types and taxonomies for your website.

While CPTUI helps solve the problem of creating custom post types, displaying the data gleaned from them can be a whole new challenge. That’s why we created [Custom Post Type UI Extended](https://pluginize.com/product/custom-post-type-ui-extended/?utm_source=cptui-desription&utm_medium=text&utm_campaign=wporg). [View our Layouts page](https://pluginize.com/cpt-ui-extended-features/?utm_source=cptui-description-examples&utm_medium=text&utm_campaign=wporg) to see some examples that are available with Custom Post Type UI Extended.

Official development of Custom Post Type UI is on GitHub, with official stable releases published on WordPress.org. The GitHub repo can be found at [https://github.com/WebDevStudios/custom-post-type-ui](https://github.com/WebDevStudios/custom-post-type-ui). Please use the Support tab for potential bugs, issues, or enhancement ideas.

[Pluginize](https://pluginize.com/?utm_source=cptui&utm_medium=text&utm_campaign=wporg) was launched in 2016 by [WebDevStudios](https://webdevstudios.com/) to promote, support, and house all of their [WordPress products](https://pluginize.com/shop/?utm_source=cptui-&utm_medium=text&utm_campaign=wporg). Pluginize is not only [creating new products for WordPress all the time, like CPTUI Extended](https://pluginize.com/product/custom-post-type-ui-extended/?utm_source=cptui&utm_medium=text&utm_campaign=wporg), but also provides ongoing support and development for WordPress community favorites like [CMB2](https://wordpress.org/plugins/cmb2/) and more.

== Screenshots ==

1. Add new post type screen and tab.
2. Add new taxonomy screen and tab.
3. Registered post types and taxonomies from CPTUI
4. Import/Export Post Types screen.
5. Get Code screen.
6. Help/support screen.

== Changelog ==

= 1.8.2 - 2020-12-29 =
* Fixed: Addressed some missing available labels for taxonomies.
* Updated: Provide page slug and link to page if a post type slug is matching an existing page.
* Updated: Support link was pointed to legacy WordPress Codex but had been moved to Developer Portal. Props @robwent

= 1.8.1 - 2020-09-21 =
* Fixed: Issues with WP-CLI importing.
* Added: Menu icon preview that should have been in 1.8.0, but was missed. Props @glebkema

= 1.8.0 - 2020-08-14 =
* Added: support for default terms with a custom taxonomy.
* Updated: Removed the forcing of underscores for post type slugs. Taxonomies are still forced.
* Fixed: jQuery compatibility issue with WordPress 5.5.0

= 1.7.5 - 2020-08-11 =
* Updated: Addressed UI issues with WordPress 5.5.0
* Updated: Moved required minimum WordPress version to 5.5.0

= 1.7.4 - 2020-03-17 =
* Added: Nonce admin verification for import functionality.
* Added: Extra escaping of markup and output for "Get Code" areas.

= 1.7.3 - 2020-02-05 =
* Updated: styles and appearance to better match WordPress core.
* Updated: Change newsletter service integration. Hey, sign up for our newsletter! Props @Oceas

= 1.7.2 - 2020-01-08 =
* Fixed: Duplicate entries for "delete_with_user" in get code.
* Fixed: Delete button for post types and taxonomies at bottom of page did not trigger dialog prompt.

= 1.7.1 - 2019-11-06 =
* Fixed: Random-ish redirects to the "Add new" tab for post types or taxonomies
* Fixed: JavaScript error when trying to delete a taxonomy.

= 1.7.0 - 2019-11-06 =
* Actually added this time: Delete with user support for post types. Managed to miss the code with 1.6.0 which was a long time ago.
* Added: Ability to disable registration of post types or taxonomies, via code filter, without deleting them completely from settings.
* Added: New post type labels introduced in WordPress 5.0.0.
* Added: Link to Dashicon documentation for when editing menu icon. Props @juliekuehl
* Added: Ability to automatically fill in additional labels based on chosen plural and singular label text.
* Updated: Added post type templates documentation to help section.
* Updated: Redirect user to the "add" tab if deleting the last post type or taxonomy created.
* Updated: Touched up tab markup to match semantic improvements provided by WordPress 5.2.0.
* Fixed: potential duplicate output of "parent_item_colon" with "Get Code" output.
* Misc: Added code of conduct file to github repo. Props GaryJones.

== Upgrade Notice ==

= 1.8.2 - 2020-12-29 =
* Fixed: Addressed some missing available labels for taxonomies.
* Updated: Provide page slug and link to page if a post type slug is matching an existing page.
* Updated: Support link was pointed to legacy WordPress Codex but had been moved to Developer Portal. Props @robwent

= 1.8.1 - 2020-09-21 =
* Fixed: Issues with WP-CLI importing.
* Added: Menu icon preview that should have been in 1.8.0, but was missed. Props @glebkema

= 1.8.0 - 2020-08-14 =
* Added: support for default terms with a custom taxonomy.
* Updated: Removed the forcing of underscores for post type slugs. Taxonomies are still forced.
* Fixed: jQuery compatibility issue with WordPress 5.5.0

= 1.7.5 - 2020-08-11 =
* Updated: Addressed UI issues with WordPress 5.5.0
* Updated: Moved required minimum WordPress version to 5.5.0

= 1.7.4 - 2020-03-17 =
* Added: Nonce admin verification for import functionality.
* Added: Extra escaping of markup and output for "Get Code" areas.

= 1.7.3 - 2020-02-05 =
* Updated: styles and appearance to better match WordPress core.
* Updated: Change newsletter service integration. Hey, sign up for our newsletter! Props @Oceas

= 1.7.2 - 2020-01-08 =
* Fixed: Duplicate entries for "delete_with_user" in get code.
* Fixed: Delete button for post types and taxonomies at bottom of page did not trigger dialog prompt.

= 1.7.1 - 2019-11-06 =
* Fixed: Random-ish redirects to the "Add new" tab for post types or taxonomies
* Fixed: JavaScript error when trying to delete a taxonomy.

= 1.7.0 - 2019-11-06 =
* Actually added this time: Delete with user support for post types. Managed to miss the code with 1.6.0 which was a long time ago.
* Added: Ability to disable registration of post types or taxonomies, via code filter, without deleting them completely from settings.
* Added: New post type labels introduced in WordPress 5.0.0.
* Added: Link to Dashicon documentation for when editing menu icon. Props @juliekuehl
* Added: Ability to automatically fill in additional labels based on chosen plural and singular label text.
* Updated: Added post type templates documentation to help section.
* Updated: Redirect user to the "add" tab if deleting the last post type or taxonomy created.
* Updated: Touched up tab markup to match semantic improvements provided by WordPress 5.2.0.
* Fixed: potential duplicate output of "parent_item_colon" with "Get Code" output.
* Misc: Added code of conduct file to github repo. Props GaryJones.

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
