=== Custom Post Type UI ===
Contributors: webdevstudios, pluginize, tw2113, vegasgeek, modemlooper, williamsba1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3084056
Tags: custom post types, CPT, CMS, post, types, post type, taxonomy, tax, custom, content types, post types
Requires at least: 5.2
Tested up to: 5.4.0
Stable tag: 1.7.4
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

= 1.6.2 - 2019-05-20 =
* Added: "themes" is now a reserved post type slug due to conflicts with WordPress internally.
* Fixed: Updated wording around "Supports" section of post type settings screen.

= 1.6.1 - 2018-12-03 =
* Fixed: Missed quote for "publicly_queryable" in taxonomy "get code" output.
* Fixed: Adjusted handling of capitalization on the word "false" when being used to disable a taxonomy metabox via the "metabox callback" setting. The word "false" needs to be all lowercase to disable.
* Updated: Added note about ability to pass "false" for the "metabox callback" setting to disable the metabox.
* Updated: Fall back to "custom-post-type-ui" textdomain in "get code" output if theme does not have their own.
* Updated: Make "Show In Rest" setting default to true taxonomies for sake of easier Gutenberg integration in the future. 1.6.0 had just post types doing this.

= 1.6.0 - 2018-10-22 =
* Added: Support for `meta_box_cb` parameter for `register_taxonomy` and taxonomies.
* Added: Dynamically warn about existing slugs.
* Added: "delete_with_user" support for post types.
* Added: Filters for post type and taxonomy data right before saving of final data.
* Added: `cptui_get_taxonomy_exists` function to check if a provided taxonomy is registered.
* Added: Required PHP version to readme.txt
* Added: Filter on taxonomies to be listed as available for association with a post type.
* Added: Warning indicators to our "Slug changed" and "Slug exists" warnings for post types and taxonomies.
* Added: Support for "publicly_queryable" parameter for taxonomies.
* Added: Support for "rest_controller_class" parameters for both post types and taxonomies.
* Added: Some initial WP-CLI support for importing and exporting CPTUI settings.
* Added: `name_admin_bar` support in post type labels.
* Added: Handling of array versions for "capability_type" field.
* Updated: Bump minimum WordPress version to 4.7.
* Updated: Pass the object_type array to `cptui_pre_register_taxonomy` filter as extra parameter.
* Updated: Adjusted preserved label handling to aid with plugin performance. Props alex-solovyev
* Updated: Utilize `wp_doing_ajax()` function for our AJAX checks.
* Updated: Clarify what is exported with the Post Types and Taxonomies spots for the Tools section.
* Updated: Clarify that the listed post types to associate to a CPTUI taxonomy are public post types by default.
* Updated: Make "Show In Rest" setting default to true for sake of easier Gutenberg integration in the future.
* Fixed: Only register archive slug if has_archive is set to true.
* Fixed: Error occurred when attempting to unset post types while editing a taxonomy.
* Fixed: Prevent errors from non-existant callback functions during post type or taxonomy processing.
* Fixed: Incorrect use of reserved taxonomy slugs function in a check for reserved post types.
* Fixed: Make sure "No post type selected" alert trigger from both buttons on the taxonomy settings edit screen.
* Fixed: Add our stylesheet only on our CPTUI pages. Fixes responsive bug on post editor screen.
* Fixed: Removed duplicate "label" in taxonomy "get code" section.


== Upgrade Notice ==

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

= 1.6.2 - 2019-05-20 =
* Added: "themes" is now a reserved post type slug due to conflicts with WordPress internally.
* Fixed: Updated wording around "Supports" section of post type settings screen.

= 1.6.1 - 2018-12-03 =
* Fixed: Missed quote for "publicly_queryable" in taxonomy "get code" output.
* Fixed: Adjusted handling of capitalization on the word "false" when being used to disable a taxonomy metabox via the "metabox callback" setting. The word "false" needs to be all lowercase to disable.
* Updated: Added note about ability to pass "false" for the "metabox callback" setting to disable the metabox.
* Updated: Fall back to "custom-post-type-ui" textdomain in "get code" output if theme does not have their own.
* Updated: Make "Show In Rest" setting default to true taxonomies for sake of easier Gutenberg integration in the future. 1.6.0 had just post types doing this.

= 1.6.0 - 2018-10-22 =
* Added: Support for `meta_box_cb` parameter for `register_taxonomy` and taxonomies.
* Added: Dynamically warn about existing slugs.
* Added: "delete_with_user" support for post types.
* Added: Filters for post type and taxonomy data right before saving of final data.
* Added: `cptui_get_taxonomy_exists` function to check if a provided taxonomy is registered.
* Added: Required PHP version to readme.txt
* Added: Filter on taxonomies to be listed as available for association with a post type.
* Added: Warning indicators to our "Slug changed" and "Slug exists" warnings for post types and taxonomies.
* Added: Support for "publicly_queryable" parameter for taxonomies.
* Added: Support for "rest_controller_class" parameters for both post types and taxonomies.
* Added: Some initial WP-CLI support for importing and exporting CPTUI settings.
* Added: `name_admin_bar` support in post type labels.
* Added: Handling of array versions for "capability_type" field.
* Updated: Bump minimum WordPress version to 4.7.
* Updated: Pass the object_type array to `cptui_pre_register_taxonomy` filter as extra parameter.
* Updated: Adjusted preserved label handling to aid with plugin performance. Props alex-solovyev
* Updated: Utilize `wp_doing_ajax()` function for our AJAX checks.
* Updated: Clarify what is exported with the Post Types and Taxonomies spots for the Tools section.
* Updated: Clarify that the listed post types to associate to a CPTUI taxonomy are public post types by default.
* Updated: Make "Show In Rest" setting default to true for sake of easier Gutenberg integration in the future.
* Fixed: Only register archive slug if has_archive is set to true.
* Fixed: Error occurred when attempting to unset post types while editing a taxonomy.
* Fixed: Prevent errors from non-existant callback functions during post type or taxonomy processing.
* Fixed: Incorrect use of reserved taxonomy slugs function in a check for reserved post types.
* Fixed: Make sure "No post type selected" alert trigger from both buttons on the taxonomy settings edit screen.
* Fixed: Add our stylesheet only on our CPTUI pages. Fixes responsive bug on post editor screen.
* Fixed: Removed duplicate "label" in taxonomy "get code" section.

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
