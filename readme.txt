=== lorem shortcode ===
Contributors: Sjeiti
Tags: lorem ipsum, dummy text, dummy html, dummy article, dummy image, shortcode
Requires at least: 2.8.6
Tested up to: 3.9
Stable tag: 1.0.0

A seeded dummy article generator shortcode. This plugin does not just generate 'lorem ipsum' but full articles (with links, images, and whatnot).


== Description ==

This plugin not just generates dummy text but tries to mimic what a Wordpress page or post might look like irl. This includes paragraphs, hyperlinks, images, more-tags, lists all easily adjustable through the shortcode attributes.
The generated code is also seeded, meaning that the dummy html on page-x will always look the same (unless you specify your own seed).




= Usage =
Add the `[lorem]` shortcode to a post or page.

= Parameters, all are optional =

**[lorem]**

* p="3" Number of paragraphs. Default is 5
* p="3" Number of paragraphs. Default is 5
* l="7", Number of lines per paragraph. Default is 3
* align="right" This tells how you'd like to allign a nested shortcode. There are two alternatives, left or right. Default is right

= Example =

`
[lorem p="1" l="20"]
`


== Installation ==

= Requirement =
* PHP: 5.2.x or newer

= Manual Installation =
* Upload the files to wp-content/plugins/lorem-shortcode/
* Activate the plugin

= Automatic Installation =
* On your WordPress blog, open the Dashboard
* Go to Plugins->Install New
* Search for "lorem shortcode"
* Click on install to install the lorem shortcode


== Frequently Asked Questions ==

= What are shortcodes? =

Shortcode, a "shortcut to code", makes it easy to add funtionality to a page or post. When a page with a shortcode is saved, WordPress execute the linked code and embedds the output in the page.

= Writing your own shortcode plugin =

* [Smashing Magazine](http://www.smashingmagazine.com/) has a nice (as allways) article about [Mastering WordPress shortcodes](http://www.smashingmagazine.com/2009/02/02/mastering-wordpress-shortcodes/). The article has several examples you can use as a starting point for writing your own.
* At codplex, you'll find the [Shortcode API documented](http://codex.wordpress.org/Shortcode_API)
* Also, feel free to use this plugin as a template for you own shortcode plugin


== Changelog ==
= 1.0.0 =
* initial release