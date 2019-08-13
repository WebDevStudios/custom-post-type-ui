=== Custom Post Type UI ===
Contributors: webdevstudios, pluginize, tw2113, vegasgeek, modemlooper, williamsba1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3084056
Tags: custom post types, CPT, CMS, post, types, post type, taxonomy, tax, custom, content types, post types
Requires at least: 5.2
Tested up to: 5.2.2
Stable tag: 1.7.0
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

= 1.7.0 - TBD =
* Added: Ability to disable registration of post types or taxonomies without deleting them completely from settings.
* Added: New post type labels introduced in WordPress 5.0.0.
* Added: Link to Dashicon documentation for when editing menu icon. Props @juliekuehl
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

= 1.5.8 - 2018-04-16 =
* Fixed: Corrected issue with "Get Code" area regarding post types and "show in menu" field values.
* Fixed: Add post_format taxonomy support for CPTUI post types that declare post formats support. This primarily addresses issues with previewing changes for existing post type posts with post_format support.
* Fixed: Add "show_in_nav_menus" settings/output to tools section.
* Fixed: "Undefined index" notices for show_in_rest and rest_base settings.
* Updated: Change how we hide submit button when editing existing post types or taxes so switching is available if a javascript error occurs.
* Updated sidebar links for Pluginize products.

= 1.5.7 - 2018-03-07 =
* Added: "output" added to blacklisted taxonomy slug list.
* Fixed: Prevent potential fatal error with customized links in plugin list page.
* Updated: Text at top of help area and readme description to promote available layouts in CPTUI-Extended.
* Updated: Things have been tested on the latest WordPress. You're in good hands.

= 1.5.6 - 2017-11-09 =
* Added: Added "custom_css", "customize_changeset", "author", and "post_type" as reserved post_types.
* Fixed: The "Invalid JSON" error message was receiving the wrong color indicator for the admin notice.

= 1.5.5 - 2017-07-27 =
* Fixed: Prevent possible conflicts with .required css selector by prefixing ours.
* Fixed: Better accommodate possible labels with apostrophes, in relation to "Get code" functionality.

= 1.5.4 - 2017-06-22 =
* Fixed: Resolved saving issue around post types that matched existing page slugs.
* Fixed: Layout issues on about page.

= 1.5.3 - 2017-03-29 =
* Fixed: Removed ability to rename post type and taxonomy slugs to reserved slugs after initial saving.
* Updated: Updated existing and added new, fancier side graphics.

= 1.5.2 - 2017-2-1 =
* Fixed: Chrome conflicts around the js used to sanitize post type and taxonomy slugs and cursors moving to end of input.
* Fixed: Further hardened undefined index notices and instance checks in our cptui_not_new_install() callback.
* Updated: Help text for post type and taxonomy slugs around the use of dashes. See http://docs.pluginize.com/article/135-dashes-in-post-type-taxonomy-slugs-for-url-seo
* Added: Clarification text regarding what the "Get code" section is useful for.

= 1.5.1 - 2017-1-17 =
* Fixed: Undefined index notice during update process for themes or plugins.
* Fixed: Blacklisted the word "include" from allowed taxonomy slugs. Causes menus to not show in WP Admin.
* Fixed: Blacklisted the word "fields" from allowed post type slugs. Causes pages to not show in WP Admin.
* Updated: Replaced hardcoded "manage_options" reference in our menu setup with variable holding filtered capability.

= 1.5.0 - 2017-1-10 =
* Added: Helper functions to grab individual post types or taxonomies from CPTUI options, function to check for support for custom saved values.
* Added: Helper functions to mark and check if a new CPTUI install.
* Added: FAQ clarifying why post type/taxonomy slugs are forced to underscores. We mean well, I assure you.
* Added: Conversion from Cyrillic characters to latin equivalents.
* Fixed: Parameter handling for get_terms() to match WordPress 4.5.
* Fixed: Added "action" as a reserved taxonomy name.
* Fixed: PHP Notices for rewrite array index, present since version 1.0.6
* Fixed: Prevent triggering post type/taxonomy slug convert when navigating screen via tab key.
* Fixed: Provide empty quote indicator in Registered Post Types and Taxonomies screen for empty values.
* Fixed: Post types and taxonomies no longer need extra page refresh to be registered after an import.
* Updated: Further evolved Registered Post Types and Taxonomies screen to better match list table styles.
* Updated: Bumped minimum required WordPress version to 4.6.
* Updated: Clarified what checking a checkbox does in regards to "Supports" area of post type settings.
* Updated: Changed appropriate help/support links to docs.pluginize.com.
* Updated: Added filter to tab collection for the tools section. You can now add your own tabs.

== Upgrade Notice ==

= 1.7.0 - TBD =
* Added: Ability to disable registration of post types or taxonomies without deleting them completely from settings.
* Added: New post type labels introduced in WordPress 5.0.0.
* Added: Link to Dashicon documentation for when editing menu icon. Props @juliekuehl
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

= 1.5.8 - 2018-04-16 =
* Fixed: Corrected issue with "Get Code" area regarding post types and "show in menu" field values.
* Fixed: Add post_format taxonomy support for CPTUI post types that declare post formats support. This primarily addresses issues with previewing changes for existing post type posts with post_format support.
* Fixed: Add "show_in_nav_menus" settings/output to tools section.
* Fixed: "Undefined index" notices for show_in_rest and rest_base settings.
* Updated: Change how we hide submit button when editing existing post types or taxes so switching is available if a javascript error occurs.
* Updated sidebar links for Pluginize products.

= 1.5.7 - 2018-03-07 =
* Added: "output" added to blacklisted taxonomy slug list.
* Fixed: Prevent potential fatal error with customized links in plugin list page.
* Updated: Text at top of help area and readme description to promote available layouts in CPTUI-Extended.
* Updated: Things have been tested on the latest WordPress. You're in good hands.

= 1.5.6 - 2017-11-09 =
* Added: Added "custom_css", "customize_changeset", "author", and "post_type" as reserved post_types.
* Fixed: The "Invalid JSON" error message was receiving the wrong color indicator for the admin notice.

= 1.5.5 - 2017-07-27 =
* Fixed: Prevent possible conflicts with .required css selector by prefixing ours.
* Fixed: Better accommodate possible labels with apostrophes, in relation to "Get code" functionality.

= 1.5.4 - 2017-06-22 =
* Fixed: Resolved saving issue around post types tha matched existing page slugs.
* Fixed: Layout issues on about page.

= 1.5.3 - 2017-03-29 =
* Fixed: Removed ability to rename post type and taxonomy slugs to reserved slugs after initial saving.
* Updated: Updated existing and added new, fancier side graphics.

= 1.5.2 - 2017-2-1 =
* Fixed: Chrome conflicts around the js used to sanitize post type and taxonomy slugs and cursors moving to end of input.
* Fixed: Further hardened undefined index notices and instance checks in our cptui_not_new_install() callback.
* Updated: Help text for post type and taxonomy slugs around the use of dashes. See http://docs.pluginize.com/article/135-dashes-in-post-type-taxonomy-slugs-for-url-seo
* Added: Clarification text regarding what the "Get code" section is useful for.

= 1.5.1 - 2017-1-17 =
* Fixed: Undefined index notice during update process for themes or plugins.
* Fixed: Blacklisted the word "include" from allowed taxonomy slugs. Causes menus to not show in WP Admin.
* Fixed: Blacklisted the word "fields" from allowed post type slugs. Causes pages to not show in WP Admin.
* Updated: Replaced hardcoded "manage_options" reference in our menu setup with variable holding filtered capability.

= 1.5.0 - 2017-1-10 =
* Added: Helper functions to grab individual post types or taxonomies from CPTUI options, function to check for support for custom saved values.
* Added: Helper functions to mark and check if a new CPTUI install.
* Added: FAQ clarifying why post type/taxonomy slugs are forced to underscores. We mean well, I assure you.
* Fixed: Parameter handling for get_terms() to match WordPress 4.5.
* Fixed: Added "action" as a reserved taxonomy name.
* Fixed: PHP Notices for rewrite array index, present since version 1.0.6
* Fixed: Prevent triggering post type/taxonomy slug convert when navigating screen via tab key.
* Fixed: Provide empty quote indicator in Registered Post Types and Taxonomies screen for empty values.
* Fixed: Post types and taxonomies no longer need extra page refresh to be registered after an import.
* Updated: Further evolved Registered Post Types and Taxonomies screen to better match list table styles.
* Updated: Bumped minimum required WordPress version to 4.6.
* Updated: Clarified what checking a checkbox does in regards to "Supports" area of post type settings.
* Updated: Changed appropriate help/support links to docs.pluginize.com.
* Updated: Added filter to tab collection for the tools section. You can now add your own tabs.

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
