=== Gospel Resource Hub ===
Contributors: dkjensen
Tested up to: 4.7.4
Stable tag: 1.0.4
License: GPLv2 or later

Integrates your WordPress website with Gospel Resource Hub API v1.

== Description ==
Display resources easily that are pulled directly from the Gospel Resource Hub API. Comes with an easy to use widget for filtering, as well as an extendable codebase for developers.

This plugin integrates with the third party Gospel Resource Hub API endpoint `//grh.devecl.io/api/v1/`. Your information is not stored or shared with this service, the connection is only required to retrieve resources from the API.

Powered by [Indigitous](https://indigitous.org)

== Installation ==

1. Upload `gospel-resource-hub` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I display the resources? =
Resources can be displayed using the shortcode `[gospel-resource-hub]`

Attributes supported:

* `posts_per_page` Specify the number of resources to display

= How can I override the display of the resources? =
The display of the resources can be overridden by creating a directory in your theme folder called `gospel-resource-hub`. The files in the `templates` directory of this plugin can be copied to this new folder inside your theme and then modified. 

You may also override the shortcode function entirely by creating a new function `gospelrh_shortcode( $atts, $content = '' ) {}` inside your themes functions.php file.

== Changelog ==

= 1.0.4 =

* Moved language filters to settings
* Added setting for "Powered by Indigitous" under filters
* Fixed posts_results filter only being applied to GRH_Query

= 1.0.3 =

* Fix `posts_per_page` attribute on shortcode
* Add new template files

= 1.0.2 =

* Initial plugin release