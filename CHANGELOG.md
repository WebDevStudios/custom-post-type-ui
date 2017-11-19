# Change Log for Custom Post Type UI

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

- Not documented.

## [1.5.6] - 2017-11-09
### Added
- `custom_css`, `customize_changeset`, `author`, and `post_type` as reserved post_types.

### Fixed
- The "Invalid JSON" error message was receiving the wrong color indicator for for the admin notice.

## [1.5.5] - 2017-07-27
### Fixed
- Prevent possible conflicts with `.required` CSS selector by prefixing ours.
- Better accommodate possible labels with apostrophes, in relation to "Get code" functionality.

## [1.5.4] - 2017-06-22
### Fixed
- Resolved saving issue around post types that matched existing page slugs.
- Layout issues on about page.

## [1.5.3] - 2017-03-29
### Changed
- Updated existing and added new, fancier side graphics.

### Fixed
- Removed ability to rename post type and taxonomy slugs to reserved slugs after initial saving.

## [1.5.2] - 2017-02-01
### Added
- Clarification text regarding what the "Get code" section is useful for.

### Changed
- Help text for post type and taxonomy slugs around the use of dashes. See http://docs.pluginize.com/article/135-dashes-in-post-type-taxonomy-slugs-for-url-seo.

### Fixed
- Chrome conflicts around the JavaScript used to sanitize post type and taxonomy slugs and cursors moving to end of input.
- Further hardened undefined index notices and instance checks in our `cptui_not_new_install()` callback.

## [1.5.1] - 2017-01-17
### Changed
- Replaced hardcoded `manage_options` reference in our menu setup with variable holding filtered capability.

### Fixed
- Undefined index notice during update process for themes or plugins.
- Blacklisted the word `include` from allowed taxonomy slugs. Causes menus to not show in WP Admin.
- Blacklisted the word `fields` from allowed post type slugs. Causes pages to not show in WP Admin.

## [1.5.0] - 2017-01-10
### Added
- Helper functions to grab individual post types or taxonomies from CPTUI options, function to check for support for custom saved values.
- Helper functions to mark and check if a new CPTUI install.
- FAQ clarifying why post type/taxonomy slugs are forced to underscores. We mean well, I assure you.
- Conversion from Cyrillic characters to latin equivalents.

### Changed
- Further evolved Registered Post Types and Taxonomies screen to better match list table styles.
- Bumped minimum required WordPress version to 4.6.
- Clarified what checking a checkbox does in regards to "Supports" area of post type settings.
- Changed appropriate help/support links to docs.pluginize.com.
- Added filter to tab collection for the tools section. You can now add your own tabs.

### Fixed
- Parameter handling for `get_terms()` to match WordPress 4.5.
- Added `action` as a reserved taxonomy name.
- PHP Notices for rewrite array index, present since version 1.0.6.
- Prevent triggering post type/taxonomy slug convert when navigating screen via tab key.
- Provide empty quote indicator in Registered Post Types and Taxonomies screen for empty values.
- Post types and taxonomies no longer need extra page refresh to be registered after an import.

## [1.4.3] - 2016-10-17
### Fixed
- Issue with post types and taxonomies trying to be converted before registration. Prevented full success of process.
- Prevent trying to convert taxonomy terms if no terms exist. Taxonomy will still be deleted from CPTUI list.
- Prevent trying to redirect on activation if being network-activated.

## [1.4.2] - 2016-10-03
### Fixed
- Responsiveness of sections and "ad" space when creating post types or taxonomies on smaller screens. Props @thecxguy.

## [1.4.1] - 2016-08-25
### Fixed
- Issue with default values for new parameters regarding menu/nav menu display for taxonomies.
- Typo in support area.

## [1.4.0] - 2016-08-22
### Added
- "Export" tab on editor screens for quick access to post type or taxonomy export pages.
- CPTUI notices are now dismissable via a button on the right side.
- "Get code" link to registered post types and registered taxonomies listings.
- More amending of incorrect characters in post type and taxonomy slugs. Latin standard alphabet only. Sorry.
- New post type template stack reference from recent WordPress versions.
- Side warning notification if post type or taxonomy slug has been edited.
- Display About page upon activation of plugin.
- Link below ads regarding getting them removed via purchase of CPTUI Extended.
- No need to refresh page after initial save to see post types and taxonomies in menu.
- Taxonomy support for `show_in_menu` and `show_in_nav_menus`.

### Changed
- Switch to dedicated dashicon for color consistency between applied admin color schemes.
- Updated about page.
- Further UI refinements to better match WordPress admin. Adapted styles found from metaboxes, including collapse/expand toggles.

### Fixed
- Further improved labels for information text on inputs.
- Hide "choose icon" button for non-JS users.
- Issue with misused `parent` label key that should be `parent_item_colon`.
- Missed `show_in_menu_string` parameter for "get code" area.
- Make sure taxonomies have required post type associated.
- "Edit" links in listings area now account for network-admin when needed, with CPTUI Extended.

## [1.3.5] - 2016-06-03
### Fixed
- Removed undefined index error for `publicly_queryable` in "Get Code" area. That parameter is targeted for 1.4.0 release.

## [1.3.4] - 2016-05-04
### Added
- Newsletter subscription form to stay uptodate with Custom Post Type UI and Custom Post Type UI Extended news.
- Support page/FAQ info regarding Pluginize and recent sidebar developments.

### Changed
- Moved WDS-based services "ads" to within the plugin itself. Will not request remote resources.

### Fixed
- Better output formatting if WDS/Pluginize "ads" failed to load images.
- Undefined variable error in `cptui.js`.

## [1.3.3] - 2016-04-05
### Changed
- Revert changes for Ajax/heartbeat API requests before post type registration. 3rd party or other plugins were breaking because post types were not registered.

## [1.3.2] - 2016-04-05
### Added
- New contributors to readme file. Welcome John and Ryan.

### Changed
- New screenshot from 1.3.0 release. Moved to assets folder so users will no longer download as part of CPTUI.
- Better prevention of running our code during Ajax/heartbeat API requests.

### Fixed
- Logic issue with CPTUI JS files loading where they weren't meant to.
- Required markers missing on required post type fields.
- Removed excess labels that are not used by WordPress core.

## [1.3.1] - 2016-03-25
### Fixed
- Logic issue for default values of `public` parameter for taxonomies added in 1.3.0.

## [1.3.0] - 2016-03-24
### Added
- "Public" parameter for taxonomies.
- "View Post Types" and "View Taxonomies" tabs at top of add/edit screens.
- Better prevention of potential duplicate slugs in new post types and taxonomies.
- Current theme's textdomain as output in get code textareas.
- Fill in singular and plural label fields if none provided. WordPress does not auto-fill these.
- For developers: plenty of extra hooks all over for customization needs.
- Javascript-based prevention of spaces and special characters for post type and taxonomy slugs.
- Legend tag support to admin UI class.
- Minified copies of our JavaScript and CSS. Define SCRIPT_DEBUG to true to use non-minified versions.
- New post type and taxonomy labels provided by WordPress 4.3 and 4.4 releases. See: https://make.wordpress.org/core/2015/12/11/additional-labels-for-custom-post-types-and-custom-taxonomies/.
- Notes to post type and taxonomy edit screens about WordPress core's post types and taxonomies.
- Taxonomy slug update ability with preserved term association.
- Title, Editor, and Featured Image now checked by default for new post types.
- "Show in Quick Edit" taxonomy parameter available in WP 4.2.
- Promo spots on add/edit screens for other products from WebDevStudios.

### Changed
- Increased accessibility coverage.
- Revised how tabs are added to pages so 3rd party developers can add their own tabs.
- Improved string consistency in our UI helper notes. Props [Gary Jones].
- Tested on WordPress 4.5.
- Cleaned up admin footer area for social links.
- Moved all localization work to WordPress.org Translation packs.

### Deprecated
- `CPT_VERSION` constant.

### Fixed
- Need to visit permalinks page to flush rewrite rules after creating new post type or taxonomy.
- Missing REST API based parameters in "Get Code" output.

## [1.2.4] - 2016-02-13
### Added
- New `CPTUI_VERSION` constant to match naming of other current constants.
- `CPTUI_VERSION` constant to `cptui.css` string for cache busting.

## [1.2.3] - 2016-01-31
### Fixed
- Copy/paste error with admin CSS. Props hinaloe.

## [1.2.2] - 2016-01-30
### Fixed
- Missing admin menu icon for some browsers.
- Undefined index notices for post type screen.

## [1.2.1] - 2016-01-17
### Fixed
- Undefined index notices for custom taxonomies and new fields from 1.2.0.

## [1.2.0] - 2016-01-15
### Added
- Support for `show_in_nav_menus` parameter for post types.
- Support for taxonomy descriptions.
- Message on listings page if no post types or taxonomies are available.
- Note regarding `public` parameter not being true by default for WordPress but is for CPTUI.
- Individual post type and taxonomy output for "Get Code" area.
- Fallback values for post type/taxonomy select input and get code area if no plural label provided.
- Support for custom `query_var` strings for post types.
- Support for `show_in_rest` and `rest_base` for post types and taxonomies for the upcoming WordPress REST API.
- Template hierarchy files to listings tables for user reference.
- Form labels in more areas to help with accessibility and usability.
- Fieldset method to Admin UI class for easily adding fieldset output.
- Debugging tab for use with CPTUI support.

### Changed
- Minimum version required. Now WordPress 4.2.
- Heading tags match accessibility changes in WordPress 4.3.
- Moved page title for some pages above tabs.
- Textdomain now matches WordPress.org repo slug.
- Added Visual Composer questions/answers to support section.
- Added question/answer regarding spaces in post type slugs
- Changed help icons to a Dashicon help icon.

### Fixed
- Support for YARPP plugin.
- Prevent potential issues with Ajax requests and our scripts/styles.

## [1.1.3] - 2015-12-23
### Changed
- Bumping WordPress compatibility version only. No other changes involved.

## [1.1.2] - 2015-08-11
### Changed
- Added `target="_blank"` attribute to one of the inline help links for Menu position. Thanks @JulieKuehl.
- Change export value to plural label for taxonomies.
- Updated screenshots.

### Fixed
- Properly select a post type or taxonomy after deleting an existing value.
- Fixed potential XSS issue.

## [1.1.1] - 2015-06-15
### Fixed
- Re-add post type and taxonomy select buttons and only display for non-JS users.

## [1.1.0] - 2015-06-12
### Added
- Simplified UI with regards to clicking amount.
- Auto selecting of first available post type or taxonomy in Edit tab.
- Switch to post type or taxonomy upon selection within Edit tab.
- Return of a list of CPTUI-registered post types and taxonomies.
- Post type slug update capability.
- Added function reference third-party link to support area.
- New hooks in edit screen for custom content display on screen.
- UI field option for custom "Supports" parameters from other plugins. Example: Yet Another Related Posts Plugin.

### Changed
- String updates.
- Updated help/support section with another reference tool.
- Rearranged menu listing slightly to remove duplicate naming.
- GitHub repo has [GitHub Updater] compatible copy of CPTUI version that is available on WordPress.org.

### Fixed
- Trim extra spaces potentially left behind in text inputs.

## [1.0.8] - 2015-05-01
### Changed
- Register taxonomies at a higher priority than post types.

## [1.0.7] - 2015-04-20
### Fixed
- Preventive measures for potential XSS security issues with `add_query_arg()`.

## [1.0.6] - 2015-04-14
### Fixed
- Change priority of post type registration function that was causing rewrite issues for many.
- Issues with help text spots not showing up for some post type fields. Props pedro-mendonca.
- Logic issue with PHP's `empty()` function and CPTUI 0.9.x saved settings.

## [1.0.5] - 2015-03-20
### Added
- Explicitly set the post type and taxonomy rewrite slugs as the registered slug if no custom ones provided.
- Repopulated labels when none provided and post type or taxonomy mentioned in default label.

### Fixed
- Prevent cptui.js from loading where it is not needed.
- Undefined index notice for post type rewrite_withfront.
- Import/export get code tab and hierarchical taxonomies.

## [1.0.4] - 2015-03-05
### Fixed
- Incorrect boolean order for hierarchical taxonomies and default value.
- Missing closing div tags.
- Default menu position to null instead of empty string.
- Undefined index notice cleanup.

### Removed
- Remove unnecessary hook.

## [1.0.3] - Unknown
### Changed
- German translation updates. If you speak/read German, myself and the translator would LOVE to have feedback on this.
- Internationalization string changes after feedback from German translation work.

### Fixed
- Logic error regarding string "0" evaluating to false when checked for not empty.
- Taxonomy with_front boolean value not evaluating correctly.
- Taxonomy hierarchical boolean value not evaluating correctly.
- Post type `has_archive`.
- Minor issue with link HTML being stripped from UI field explanation.
- Better apostrophe/single quote support in label fields.

## [1.0.2] - 2015-02-12
### Changed
- Update some text strings after localization feedback.
- Updating progressing translation files.

### Fixed
- Issue with checked checkboxes for post type associations for taxonomies.
- "Get Code" spot related to post type associations for taxonomies.
- Typos in textdomain for two spots.
- Value for with_front parameter.
- Error in boolean type for `map_meta_cap`.
- Missing use of query_var slug if present for taxonomies.

## [1.0.1] - 2015-02-11
### Changed
- Prefix our "help" class to prevent conflict with other plugins that use just "help".

### Fixed
- Issues with taxonomy transfer and registration.
- Issue with taxonomy "show admin column" evaluating to true regardless of setting.
- Issue with menu position values not being obeyed.
- Hook names inside taxonomy update function.
- Potentially empty parameter with taxonomies and "Get Code" output.
- PHP "undefined index" notice for some parameters.

## 1.0.0 - 2015-02-09
### Added
- CPTUI has been given a new UI!
- Separated out post type and taxonomy creation to their own screens.
- Import/export ability for post type and taxonomy settings.
- Help/Support Screen.
- Confirmation prompts before deleting post types and taxonomies.
- Actions and filters for third-party customization.
- Function that automatically converts 0.8.x and down settings to new setting arrangement.

### Changed
- Updated available parameters for post types and parameters.
- Updated i18n and translation files.
- Changed how settings are stored so that post types and taxonomies are in named array indexes.

### Fixed
- Bug fixes not mentioned above.

[GitHub Updater]: https://github.com/afragen/github-updater

[Gary Jones]: https://github.com/GaryJones

[Unreleased]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.5.6...HEAD
[1.5.6]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.5.5...1.5.6
[1.5.5]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.5.4...1.5.5
[1.5.4]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.5.3...1.5.4
[1.5.3]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.5.2...1.5.3
[1.5.2]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.5.1...1.5.2
[1.5.1]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.5.0...1.5.1
[1.5.0]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.4.3...1.5.0
[1.4.3]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.4.2...1.4.3
[1.4.2]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.4.1...1.4.2
[1.4.1]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.4.0...1.4.1
[1.4.0]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.3.5...1.4.0
[1.3.5]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.3.4...1.3.5
[1.3.4]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.3.3...1.3.4
[1.3.3]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.3.2...1.3.3
[1.3.2]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.3.1...1.3.2
[1.3.1]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.2.4...1.3.0
[1.2.4]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.2.3...1.2.4
[1.2.3]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.2.2...1.2.3
[1.2.2]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.1.3...1.2.0
[1.1.3]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.1.2...1.1.3
[1.1.2]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.0.8...1.1.0
[1.0.8]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.0.7...1.0.8
[1.0.7]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.0.6...1.0.7
[1.0.6]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.0.5...1.0.6
[1.0.5]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.0.4...1.0.5
[1.0.4]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.0.3...1.0.4
[1.0.3]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/WebDevStudios/custom-post-type-ui/compare/1.0.0...1.0.1

