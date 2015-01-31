=== Custom Post Type UI ===
Contributors: williamsba1, tw2113, webdevstudios
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3084056
Tags: custom post types, CPT, CMS, post, types, post type, cck, taxonomy, tax, custom
Requires at least: 3.8
Tested up to: 4.1
Stable tag: 0.9.0
License: GPLv2

Admin UI for creating custom post types and custom taxonomies in WordPress

== Description ==

This plugin provides an easy to use interface to create and administer custom post types and taxonomies in WordPress.  This plugin is created for WordPress 3.x.

Please note that this plugin will NOT handle display of registered post types or taxonomies in your current theme. It will simply register them for you.

All official development on this plugin is on GitHub. Version bumps will still be published here on WordPress.org. You can find the repo at [https://github.com/WebDevStudios/custom-post-type-ui](https://github.com/WebDevStudios/custom-post-type-ui). Please file confirmed issues, bugs, and enhancement ideas there, when possible.

Import/Export functionality amended from original contribution by [Ben Allfree](http://wordpress.org/support/profile/benallfree).

Implied credit to:

* [exeQutor](https://github.com/exeQutor) For proper supports fix.

== Screenshots ==

1. Landing page screen.
2. Add new post type screen and tab.
3. Edit post type screen and tab.
4. Add new taxonomy screen and tab.
5. Edit taxonomy screen and tab.
6. Export post types screen and tab.
7. Export taxonomies screen and tab.
8. Get code screen and tab.
9. Help/support screen.

== Changelog ==

= 0.9.0 =
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

= 0.6.2 =
* Added support for assigning custom taxonomies to post types when creating/editing
* Fixed bug when setting hierarchy on a post type (props @JohnPBloch)
* Fixed an error when registering a post type with no supported meta boxes
* Fixed "Undefined index" error when determining return URLs
* Added Chinese translation

= 0.6.1 =
* Added translation functionality and .pot file
* Added Japanese translation
* Reworked entire path structure fixing "page not found" errors
* Fixed "First argument is expected to be a valid callback" error
* Random bug fixes

= 0.6 =
* Added support for custom labels with custom post types and taxonomies
* Added ability to assign built-in taxonomies to custom post types
* Added ability to assign custom taxonomies to multiple post types
* Fixed jQuery conflict with other plugins (props shadyvb)
* Managing post types now displays total published/draft per type
* Code optimization and cleanup

= 0.5.2 =
* Updated excerpts to excerpt in CPT Support field (props vlad27aug)

= 0.5.1 =
* Added flush_rewrite_rules() to reset rules when using custom rewrite slugs

= 0.5 =
* Updated post-thumbnails to thumbnail in CPT Support field
* Added singular_label option for custom post types
* Added support for custom Rewrite slugs for post types and taxonomies
* Reworked entire array structure for easier additions down the road
* Fixed Get Code bug in Custom Post Types and Custom Taxonomies
* View additional custom post types registered in WordPress

= 0.4.1 =
* Fixed bug with REWRITE and QUERY_VAR values not executing correctly
* Set REWRITE and QUERY_VAR values to True by default

= 0.4 =
* Default view now hides advanced options
* Get Code link to easily copy/paste code used to create custom post types and taxonomies
* Added support for 'author' and 'page-attributes' in CPT Supports field

= 0.3.1 =
* Fixed multiple warnings and errors

= 0.3 =
* added new menu/submenus for individual sections
* added support for 'title' and 'editor' in CPT Supports field
* added Singular Label for custom taxonomies (props sleary)

= 0.2.1 =
* Set default Query Var setting to False

= 0.2 =
* Added support for creating custom taxonomies
* Increased internationalization support
* Fixed siteurl bug

= 0.1.2 =
* Fixed a bug where default values were incorrect

= 0.1.1 =
* Fixed a bunch of warnings

= 0.1 =
* First beta release

== Upgrade Notice ==

= 0.9.0 =

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

= 0.6.2 =
* Added support for assigning custom taxonomies to post types when creating/editing
* Fixed bug when setting hierarchy on a post type (props @JohnPBloch)
* Fixed an error when registering a post type with no supported meta boxes
* Fixed "Undefined index" error when determining return URLs
* Added Chinese translation

= 0.6.1 =
* Added translation functionality and .pot file
* Added Japanese translation
* Reworked entire path structure fixing "page not found" errors
* Fixed "First argument is expected to be a valid callback" error
* Random bug fixes

= 0.6 =
* Added support for custom labels with custom post types and taxonomies
* Added ability to assign built-in taxonomies to custom post types
* Added ability to assign custom taxonomies to multiple post types
* Fixed jQuery conflict with other plugins (props shadyvb)
* Managing post types now displays total published/draft per type
* Code optimization and cleanup

= 0.5.2 =
* Updated excerpts to excerpt in CPT Support field (props vlad27aug)

= 0.5.1 =
* Added flush_rewrite_rules() to reset rules when using custom rewrite slugs

= 0.5 =
* Fixed multiple bugs
* If upgrading from pre 0.5 version you will need to recreate your custom post types

= 0.4.1 =
* Fixed bug with REWRITE and QUERY_VAR values not executing correctly

= 0.4 =
* Default view now hides advanced options
* Get Code link to easily copy/paste code used to create custom post types and taxonomies
* Added support for 'author' and 'page-attributes' in CPT Supports field

= 0.3.1 =
* Fixed multiple warnings and errors

= 0.3 =
* added new menu/submenus for individual sections
* added support for 'title' and 'editor' in CPT Supports field
* added Singular Label for custom taxonomies (props sleary)

= 0.2.1 =
* Set default Query Var setting to False

= 0.2 =
* Fixed the siteurl bug
* Added support for creating custom taxonomies

= 0.1.2 =
* Fixed a bug where default values were incorrect

= 0.1.1 =
* Fixed a bunch of warnings

= 0.1 =
* First beta release

== Installation ==

1. Upload the Custom Post Type UI folder to the plugins directory in your WordPress installation
2. Activate the plugin
3. Navigate to the Custom Post Type UI Menu

That's it! Now you can easily start creating custom post types and taxonomies in WordPress

== Frequently Asked Questions ==

Please see the Help/Support section for FAQs and start a new thread on the support forums for Custom Post Type UI if none of those answer your question.

== Other Notes ==

= Outside contributors that we wish to thank =
[brandondove](https://github.com/brandondove)
