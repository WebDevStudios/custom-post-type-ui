=== Custom Post Type UI ===
Contributors: webdevstudios, pluginize, tw2113, vegasgeek, modemlooper, williamsba1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3084056
Tags: custom post types, CPT, CMS, post, types, post type, taxonomy, tax, custom, content types, post types
Requires at least: 5.5
Tested up to: 5.8.1
Stable tag: 1.10.0
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

= 1.10.0 - 2021-10-04 =
* Added: Dashicon picker with popup. - Props arshidkv12
* Added: Tag Cloud widget support for custom taxonomies.
* Added: Filters that allow developers to override the data fetched from our options, if they choose to.
* Added: Ability to clear all filled in label values.
* Fixed: Hardened up various parts of our code to ensure security.
* Fixed: Incorrectly referenced variable from post types instead of taxonomies, for the rest_controller_class property.

= 1.9.2 - 2021-06-16 =
* Added: "date" as a reserved taxonomy slug.
* Fixed: duplicate "show_in_graphql" attribute output in "Get Code" output.
* Updated: image optimization for smaller file sizes.
* Updated: removed duplicate taxonomy labels.

= 1.9.1 - 2021-04-19 =
* Fixed: missed WPGraphQL settings in our Get Tools/Export functionality.
* Updated: note about needing a published item to set parent/child relationships in post types.

= 1.9.0 - 2021-03-30 =
* Added: WPGraphQL Support when WPGraphQL is installed and active.
* Fixed: Better handling of code in post_format based helper functions.
* Updated: Cleaned up unused CSS.
* Updated: Added `types` to disallowed taxonomy slugs.
* Updated: Amended some helper text on the listings page regarding templates. Props @tarecord

== Upgrade Notice ==

= 1.10.0 - 2021-10-04 =
* Added: Dashicon picker with popup. - Props arshidkv12
* Added: Tag Cloud widget support for custom taxonomies.
* Added: Filters that allow developers to override the data fetched from our options, if they choose to.
* Added: Ability to clear all filled in label values.
* Fixed: Hardened up various parts of our code to ensure security.
* Fixed: Incorrectly referenced variable from post types instead of taxonomies, for the rest_controller_class property.

= 1.9.2 - 2021-06-16 =
* Added: "date" as a reserved taxonomy slug.
* Fixed: duplicate "show_in_graphql" attribute output in "Get Code" output.
* Updated: image optimization for smaller file sizes.
* Updated: removed duplicate taxonomy labels.

= 1.9.1 - 2021-04-19 =
* Fixed: missed WPGraphQL settings in our Get Tools/Export functionality.
* Updated: note about needing a published item to set parent/child relationships in post types.

= 1.9.0 - 2021-03-30 =
* Added: WPGraphQL Support when WPGraphQL is installed and active.
* Fixed: Better handling of code in post_format based helper functions.
* Updated: Cleaned up unused CSS.
* Updated: Added `types` to disallowed taxonomy slugs.
* Updated: Amended some helper text on the listings page regarding templates. Props @tarecord

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
