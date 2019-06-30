=== Unclickable Featured Image ===
Contributors: zggz
Tags: Featured Image, Thumbnail, Thumbnails, Links, Images, Themes
Requires at least: 2.9.0
Tested up to: 5.2.2
Stable tag: 2.1
Requires PHP: 5.2.2
License: MIT License
License URI: https://opensource.org/licenses/MIT

A WordPress plugin that forces all featured images to NOT be links

== Description ==

If your theme makes thumbnail pictures links (which casuses a useless refresh on click), 
use this plugin to disable that. That's it. Once enabled, it will make every featured image 
(on a post/page) unclickable.

== Installation ==

Installation:
1. Download this repository
2. Upload the 'unclickable-featured-image' folder to your webserver (it's the whole repo)
3. Place the 'unclickable-featured-image' folder in the '/wp-content/plugins' folder
4. Activate the plugin from the WordPress admin console
5. Test your site. If it does not work, try the settings page under Settings > Unclickable Featured Image Settings

OR:
1. Install the plugin through the WordPress plugins screen directly.
2. Activate the plugin from the WordPress admin console
3. Test your site. If it does not work, try the settings page under Settings > Unclickable Featured Image Settings


== Frequently Asked Questions ==

= Why would I use this? =

A select few themes make their thumbnails clickable (like the title of the page) and cause
a redirect to the current page. This plugin removes that without modifying the theme itself.

= How do I know if the plugin is working? =

The easiest way is to hover over a thumbnail and look at where the link points (usually 
shown in the corner of your browser). If the plugin is active, the link will point 
to 'javascript:void(0);'

== Screenshots ==

1. The settings page

== Changelog ==

= 2.1 =
* Switched to plugins_url() to find JavaScript files

= 2.0 =
* Added options page
* Added optional client side unlinking code
* Added screenshot

= 1.1 =
* Added style='cursor:default' to thumbnail overlay

= 1.0 =
* Original Release

== Upgrade Notice ==

= 2.1 =
* Better support for non-standard folder layouts and caching

= 2.0 =
* More ways to make featured images unclickable. Larger theme compatibility.

= 1.1 =
The cursor (when over the thumbnail) now looks normal (not like a it's a link)

= 1.0 =
It exists

== Contributing ==
If you have any requests, bugs, or improvements, leave them at the GitHub for this plugin
[GitHub repo link](https://github.com/zggz/unclickable-featured-image "GitHub Repository")