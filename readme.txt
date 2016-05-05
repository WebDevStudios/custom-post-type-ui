=== Custom Post Type UI ===
Contributors: webdevstudios, tw2113, vegasgeek, modemlooper, williamsba1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3084056
Tags: custom post types, CPT, CMS, post, types, post type, cck, taxonomy, tax, custom
Requires at least: 4.2
Tested up to: 4.5
Stable tag: 1.3.4
License: GPLv2

Admin UI for creating custom post types and custom taxonomies in WordPress

== Description ==

This plugin provides an easy to use interface for creating and administrating custom post types and taxonomies in WordPress.  This plugin is created for WordPress 3.0 and higher.

Please note that Custom Post Type UI alone will not display post types or taxonomies data in customized places within your site; it simply registers them for you to use. Check out [Custom Post Type UI Extended](https://pluginize.com/product/custom-post-type-ui-extended/?utm_source=cptui-desription&utm_medium=text&utm_campaign=wporg) for an easy way to display post type content from any registered types on your site, including those created with Custom Post Type UI and more.

All official development on this plugin is on GitHub. New releases are still published here on WordPress.org. The version shown here should be considered the latest stable release. You can find the repo at [https://github.com/WebDevStudios/custom-post-type-ui](https://github.com/WebDevStudios/custom-post-type-ui). Please file confirmed issues, bugs, and enhancement ideas there, when possible.

== Screenshots ==

1. Add new post type screen and tab.
2. Edit post type screen and tab.
3. Add new taxonomy screen and tab.
4. Edit taxonomy screen and tab.
5. Registered post types and taxonomies from CPTUI
6. Import/Export Post Types screen.
7. Import/Export Taxonomies screen.
8. Get Code screen.
9. Debug Info screen.
10. Help/support screen.
11. About/Update Information/Donate screen.

== Changelog ==

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

= 1.1.3 - 2015-12-23 =
* Bumping WordPress compatibility version only. No other changes involved.

= 1.1.2 - 2015-08-11 =
* Change export value to plural label for taxonomies.
* Properly select a post type or taxonomy after deleting an existing value.
* Updated screenshots
* Added target="_blank" attribute to one of the inline help links for Menu position. Thanks @JulieKuehl
* Fixed potential XSS issue.

= 1.1.1 - 2015-06-15 =
* Re-add post type and taxonomy select buttons and only display for non-js users.

= 1.1.0 - 2015-06-12 =
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

= 1.0.8 - 2015-05-01 =
* Register taxonomies at a higher priority than post types.

= 1.0.7 - 2015-04-20 =
* Preventive measures for potential XSS security issues with add_query_arg()

= 1.0.6 - 2015-04-14 =
* Change priority of post type registration function that was causing rewrite issues for many.
* Fix issues with help text spots not showing up for some post type fields. Props pedro-mendonca.
* Fix logic issue with PHP's empty() function and CPTUI 0.9.x saved settings.

= 1.0.5 - 2015-03-20 =
* Explicitly set the post type and taxonomy rewrite slugs as the registered slug if no custom ones provided.
* Prevent cptui.js from loading where it is not needed.
* Fixed undefined index notice for post type rewrite_withfront.
* Repopulated labels when none provided and post type or taxonomy mentioned in default label.
* Fix for import/export get code tab and hierarchical taxonomies

= 1.0.4 - 2015-03-05 =
* Fixed incorrect boolean order for hierarchical taxonomies and default value.
* Fixed missing closing div tags.
* Default menu position to null instead of empty string.
* Undefined index notice cleanup.
* Remove unnecessary hook.

= 1.0.3 - Unknown =
* Fix logic error regarding string "0" evaluating to false when checked for not empty.
* Fix for taxonomy with_front boolean value not evaluating correctly.
* Fix for taxonomy hierarchical boolean value not evaluating correctly.
* Fix for post type has_archive.
* German translation updates. If you speak/read German, myself and the translator would LOVE to have feedback on this.
* Internationalization string changes after feedback from German translation work.
* Minor issue with link html being stripped from UI field explanation.
* Better apostrophe/single quote support in label fields.

= 1.0.2 - 2015-02-12 =
* Fix issue with checked checkboxes for post type associations for taxonomies.
* Fix "Get Code" spot related to post type associations for taxonomies.
* Update some text strings after localization feedback.
* Fix typos in textdomain for two spots.
* Updating progressing translation files.
* Fix value for with_front parameter.
* Fix error in boolean type for map_meta_cap.
* Fix missing use of query_var slug if present for taxonomies.

= 1.0.1 - 2015-02-11 =
* Fix issues with taxonomy transfer and registration.
* Fix issue with taxonomy "show admin column" evaluating to true regardless of setting.
* Prefix our "help" class to prevent conflict with other plugins that use just "help".
* Fix issue with menu position values not being obeyed.
* Fix hook names inside taxonomy update function.
* Fix potentially empty parameter with taxonomies and "Get Code" output.
* Added PHP "undefined index" notice prevention for some parameters.

= 1.0.0 - 2015-02-09 =
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

== Upgrade Notice ==

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

= 1.2.4 =
* Added: new CPTUI_VERSION constant to match naming of other current constants.
* Added: CPTUI_VERSION constant to cptui.css string for cache busting.

= 1.2.3 =
* Fixed: copy/paste error with admin css. Props hinaloe.

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

Please see the Help/Support section for FAQs and start a new thread on the support forums for Custom Post Type UI if none of those answer your question.

== Other Notes ==

Import/Export functionality amended from original contribution by [Ben Allfree](http://wordpress.org/support/profile/benallfree).

= Outside contributors that we wish to thank =
[brandondove](https://github.com/brandondove)
