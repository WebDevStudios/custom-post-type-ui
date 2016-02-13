=== Custom Post Type UI ===
Contributors: tw2113, williamsba1, webdevstudios
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3084056
Tags: custom post types, CPT, CMS, post, types, post type, cck, taxonomy, tax, custom
Requires at least: 4.2
Tested up to: 4.4.1
Stable tag: 1.2.4
License: GPLv2

Admin UI for creating custom post types and custom taxonomies in WordPress

== Description ==

This plugin provides an easy to use interface to create and administer custom post types and taxonomies in WordPress.  This plugin is created for WordPress 3.x.

Please note that this plugin will NOT handle display of registered post types or taxonomies in your current theme. It will simply register them for you.

All official development on this plugin is on GitHub. Version bumps will still be published here on WordPress.org. You can find the repo at [https://github.com/WebDevStudios/custom-post-type-ui](https://github.com/WebDevStudios/custom-post-type-ui). Please file confirmed issues, bugs, and enhancement ideas there, when possible.

== Screenshots ==

1. Add new post type screen and tab.
2. Edit post type screen and tab.
3. Add new taxonomy screen and tab.
4. Edit taxonomy screen and tab.
5. Registered post types and taxonomies from CPTUI
6. Import/Export screen.
7. Help/support screen.
8. Update Information/Donate screen.

== Changelog ==

= 1.2.4 =
* Added: new CPTUI_VERSION constant to match naming of other current constants.
* Added: CPTUI_VERSION constant to cptui.css string for cache busting.

= 1.2.3 =
* Fixed: copy/paste error with admin css.

= 1.2.2 =
* Fixed: Missing admin menu icon for some browsers.
* Fixed: Undefined index notices for post type screen.

= 1.2.1 =
* Fixed: Undefined index notices for custom taxonomies and new fields from 1.2.0

= 1.2.0 =
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

= 1.1.3 =
* Bumping WordPress compatibility version only. No other changes involved.

= 1.1.2 =
* Change export value to plural label for taxonomies.
* Properly select a post type or taxonomy after deleting an existing value.
* Updated screenshots
* Added target="_blank" attribute to one of the inline help links for Menu position. Thanks @JulieKuehl
* Fixed potential XSS issue.

= 1.1.1 =
* Re-add post type and taxonomy select buttons and only display for non-js users.

= 1.1.0 =
* Simplified UI with regards to clicking amount.
* Auto selecting of first available post type or taxonomy in Edit tab.
* Switch to post type or taxonomy upon selection within Edit tab.
* Return of a list of CPTUI-registered post types and taxonomies.
* Post type slug update capability.
* Added function reference 3rd party link to support area.
* New hooks in edit screen for custom content display on screen.
* String updates.
* UI field option for custom "Supports" parameters from other plugins. Example: Yet Another Related Posts Plugin.
* Updated help/support section with another reference tool.
* Trim extra spaces potentially left behind in text inputs.
* Rearranged menu listing slightly to remove duplicate naming.
* GitHub repo has GitHub Updater (https://github.com/afragen/github-updater) compatible copy of CPTUI version that is available on WordPress.org

= 1.0.8 =
* Register taxonomies at a higher priority than post types.

= 1.0.7 =
* Preventive measures for potential XSS security issues with add_query_arg()

= 1.0.6 =
* Change priority of post type registration function that was causing rewrite issues for many.
* Fix issues with help text spots not showing up for some post type fields. Props pedro-mendonca.
* Fix logic issue with PHP's empty() function and CPTUI 0.9.x saved settings.

= 1.0.5 =
* Explicitly set the post type and taxonomy rewrite slugs as the registered slug if no custom ones provided.
* Prevent cptui.js from loading where it is not needed.
* Fixed undefined index notice for post type rewrite_withfront.
* Repopulated labels when none provided and post type or taxonomy mentioned in default label.
* Fix for import/export get code tab and hierarchical taxonomies

= 1.0.4 =
* Fixed incorrect boolean order for hierarchical taxonomies and default value.
* Fixed missing closing div tags.
* Default menu position to null instead of empty string.
* Undefined index notice cleanup.
* Remove unnecessary hook.

= 1.0.3 =
* Fix logic error regarding string "0" evaluating to false when checked for not empty.
* Fix for taxonomy with_front boolean value not evaluating correctly.
* Fix for taxonomy hierarchical boolean value not evaluating correctly.
* Fix for post type has_archive.
* German translation updates. If you speak/read German, myself and the translator would LOVE to have feedback on this.
* Internationalization string changes after feedback from German translation work.
* Minor issue with link html being stripped from UI field explanation.
* Better apostrophe/single quote support in label fields.

= 1.0.2 =
* Fix issue with checked checkboxes for post type associations for taxonomies.
* Fix "Get Code" spot related to post type associations for taxonomies.
* Update some text strings after localization feedback.
* Fix typos in textdomain for two spots.
* Updating progressing translation files.
* Fix value for with_front parameter.
* Fix error in boolean type for map_meta_cap.
* Fix missing use of query_var slug if present for taxonomies.

= 1.0.1 =
* Fix issues with taxonomy transfer and registration.
* Fix issue with taxonomy "show admin column" evaluating to true regardless of setting.
* Prefix our "help" class to prevent conflict with other plugins that use just "help".
* Fix issue with menu position values not being obeyed.
* Fix hook names inside taxonomy update function.
* Fix potentially empty parameter with taxonomies and "Get Code" output.
* Added PHP "undefined index" notice prevention for some parameters.

= 1.0.0 =
* CPTUI has been given a new UI!
* Separated out post type and taxonomy creation to their own screens.
* Added import/export ability for post type and taxonomy settings.
* Added a Help/Support Screen.
* Added/Updated available parameters for post types and parameters.
* Updated i18n and translation files.
* Added Confirmation prompts before deleting post types and taxonomies.
* Added actions and filters for 3rd party customization.
* Added function that automatically converts 0.8.x and down settings to new setting arrangement.
* Changed how settings are stored so that post types and taxonomies are in named array indexes.
* Bug fixes not mentioned above.

= 0.9.5 =
* Revert 0.9.0 release until unfound bugs are fixed.

= 0.9.0 =
* See 1.0.0 list. This was reverted by 0.9.5 release.

= 0.8.5 =
* Fix issue with menu_postion being quoted in CPT get_code functionality.

= 0.8.4 =
* Fix issue with get code and post types/taxonomies that use a dash instead of underscore. Props Evan Mullins/circlecube.

= 0.8.3 =
* Dashicon support

= 0.8.2 =
* Fix with_front logic issue.

= 0.8.1 =
* Renamed menu entry to "CPT UI".
* Fixes for potential "undefined index" WP DEBUG log notices.
* Updated localization text files for text changes.
* Updated and fixed output for "get code" and custom post types.
* Updated and fixed output for "get code" and custom taxonomies.
* Fixes "get code" function callback name conflict.
* Added support for show_admin_column for WordPress 3.5+
* Added support for map_meta_cap field in custom post types.
* Prevent quotes in slug fields for Custom Post Types or Taxonomies.

= 0.8 =
* Added "with_front" support
* Added menu icon support. Upload and save full URL from Media Library
* Added General post formats support
* Every string is translation ready
* Better fallback options for new install that haven't created anything yet
* More notes to help users with options
* Code refactoring and cleanup
* Fix for possible empty rewrite value
* Fixed slashes issue with description field and taxonomy label fields
* Fixed issue with capabilities input having two value attributes
* Flush rewrite rules on deactivation
* UI touchups
* Updated screenshots.

= 0.7.2 =
* Added exclude_from_search support
* Fixed display bug with capability type
* Fixed JavaScript bug
* Strict CPT name check
* Code cleanup

= 0.7.1 =
* Fixed XSS security bug (props Duck)

= 0.7 =
* WordPress 3.1 support
* Added has_archive and show_in_menu support
* Added label for custom menu name
* Updated plugin UI to be consistent with WordPress styles
* Added "Get Code" feature to easily copy code used for post types and taxonomies (BETA)

== Upgrade Notice ==

= 1.2.4 =
* Added: new CPTUI_VERSION constant to match naming of other current constants.
* Added: CPTUI_VERSION constant to cptui.css string for cache busting.

= 1.2.3 =
* Fixed: copy/paste error with admin css.

= 1.2.2 =
* Fixed: Missing admin menu icon for some browsers.
* Fixed: Undefined index notices for post type screen.

= 1.2.1 =
* Fixed: Undefined index notices for custom taxonomies and new fields from 1.2.0

= 1.2.0 =
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

= 1.1.3 =
* Bumping WordPress compatibility version only. No other changes involved.

= 1.1.2 =
* Change export value to plural label for taxonomies.
* Properly select a post type or taxonomy after deleting an existing value.
* Updated screenshots
* Added target="_blank" attribute to one of the inline help links for Menu position. Thanks @JulieKuehl
* Fixed potential XSS issue.

= 1.1.1 =
* Re-add post type and taxonomy select buttons and only display for non-js users.

= 1.1.0 =
* Simplified UI with regards to clicking amount.
* Auto selecting of first available post type or taxonomy in Edit tab.
* Switch to post type or taxonomy upon selection within Edit tab.
* Return of a list of CPTUI-registered post types and taxonomies.
* Post type slug update capability.
* Added function reference 3rd party link to support area.
* New hooks in edit screen for custom content display on screen.
* String updates.
* UI field option for custom "Supports" parameters from other plugins. Example: Yet Another Related Posts Plugin.
* Updated help/support section with another reference tool.
* Trim extra spaces potentially left behind in text inputs.
* Rearranged menu listing slightly to remove duplicate naming.
* GitHub repo has GitHub Updater (https://github.com/afragen/github-updater) compatible copy of CPTUI version that is available on WordPress.org

= 1.0.8 =
* Register taxonomies at a higher priority than post types.

= 1.0.7 =
* Preventive measures for potential XSS security issues with add_query_arg()

= 1.0.6 =
* Change priority of post type registration function that was causing rewrite issues for many.
* Fix issues with help text spots not showing up for some post type fields. Props pedro-mendonca.
* Fix logic issue with PHP's empty() function and CPTUI 0.9.x saved settings.

= 1.0.5 =
* Explicitly set the post type and taxonomy rewrite slugs as the registered slug if no custom ones provided.
* Prevent cptui.js from loading where it is not needed.
* Fixed undefined index notice for post type rewrite_withfront.
* Repopulated labels when none provided and post type or taxonomy mentioned in default label.
* Fix for import/export get code tab and hierarchical taxonomies

= 1.0.4 =
* Fixed incorrect boolean order for hierarchical taxonomies and default value.
* Fixed missing closing div tags.
* Default menu position to null instead of empty string.
* Undefined index notice cleanup.
* Remove unnecessary hook.

= 1.0.3 =
* Fix logic error regarding string "0" evaluating to false when checked for not empty.
* Fix for taxonomy with_front boolean value not evaluating correctly.
* Fix for taxonomy hierarchical boolean value not evaluating correctly.
* Fix for post type has_archive.
* German translation updates. If you speak/read German, myself and the translator would LOVE to have feedback on this.
* Internationalization string changes after feedback from German translation work.
* Minor issue with link html being stripped from UI field explanation.
* Better apostrophe/single quote support in label fields.

= 1.0.2 =
* PLEASE TEST THIS UPDATE ON A DEV SITE IF YOU CAN, BEFORE UPDATING ON A LIVE SITE.
* Fix issue with checked checkboxes for post type associations for taxonomies.
* Fix "Get Code" spot related to post type associations for taxonomies.
* Update some text strings after localization feedback.
* Fix typos in textdomain for two spots.
* Updating progressing translation files.
* Fix value for with_front parameter.
* Fix error in boolean type for map_meta_cap.
* Fix missing use of query_var slug if present for taxonomies.

= 1.0.1 =
* Fix issues with taxonomy transfer and registration. May need to delete new option value and re-convert afterwards.
* Fix issue with taxonomy "show admin column" evaluating to true regardless of setting.
* Prefix our "help" class to prevent conflict with other plugins that use just "help".
* Fix issue with menu position values not being obeyed.
* Fix hook names inside taxonomy update function.
* Fix potentially empty parameter with taxonomies and "Get Code" output.
* Added PHP "undefined index" notice prevention for some parameters.

= 1.0.0 =

This is a major upgrade. This includes a new UI and a settings conversion to new stored arangement. 0.8.x settings will not be deleted if for some reason you must revert to the previous version.

Full list:
* CPTUI has been given a new UI!
* Separated out post type and taxonomy creation to their own screens.
* Added import/export ability for post type and taxonomy settings.
* Added a Help/Support Screen.
* Added/Updated available parameters for post types and parameters.
* Updated i18n and translation files.
* Added Confirmation prompts before deleting post types and taxonomies.
* Added actions and filters for 3rd party customization.
* Added function that automatically converts 0.8.x and down settings to new setting arrangement.
* Changed how settings are stored so that post types and taxonomies are in named array indexes.
* Bug fixes not mentioned above.

= 0.9.5 =
* Revert 0.9.0 release until unfound bugs are fixed.

= 0.9.0 =
* See 1.0.0 list. This was reverted by 0.9.5 release.

= 0.8.5 =
* Fix issue with menu_postion being quoted in CPT get_code functionality.

= 0.8.4 =
* Fix issue with get code and post types/taxonomies that use a dash instead of underscore. Props Evan Mullins/circlecube.

= 0.8.3 =
* Dashicon support. FINALLY. REJOICE!

= 0.8.2 =
* Fixes with_front logic issue that was defaulting to true.

= 0.8.1 =
* Many bug fixes and admittedly some features. Fixes the Get Code functionality primarily.

= 0.7.2 =
* Added exclude_from_search support
* Fixed display bug with capability type
* Fixed JavaScript bug
* Strict CPT name check
* Code cleanup

= 0.7.1 =
* XSS security bug patched

= 0.7 =
* WordPress 3.1 support
* Added has_archive and show_in_menu support
* Added label for custom menu name
* Updated plugin UI to be consistent with WordPress styles
* Added "Get Code" feature to easily copy code used for post types and taxonomies (BETA)

== Installation ==

=== Manual ===
1. Upload the Custom Post Type UI folder to the plugins directory in your WordPress installation
2. Activate the plugin.
3. Navigate to the "CPTUI" Menu.

=== Admin Installer ===
1. Visit the Add New plugin screen and search for "custom post type ui"
2. Click the "Install Now" button.
3. Activate the plugin.
4. Navigate to the "CPTUI" Menu.

That's it! Now you can easily start creating custom post types and taxonomies in WordPress

== Frequently Asked Questions ==

Please see the Help/Support section for FAQs and start a new thread on the support forums for Custom Post Type UI if none of those answer your question.

== Other Notes ==

Import/Export functionality amended from original contribution by [Ben Allfree](http://wordpress.org/support/profile/benallfree).

= Outside contributors that we wish to thank =
[brandondove](https://github.com/brandondove)
