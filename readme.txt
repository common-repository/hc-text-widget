=== HC Text Widget ===
Contributors: SomeWebMedia
Tags: widget, editor, WYSIWYG, tinymce, rich text editor, html editor, enhanced text widget, advanced text widget, tinymce widget
Requires at least: 3.7.1
Tested up to: 3.9.1
Stable tag: 1.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WYSIWYG editor inside a widget

== Description ==

This plugin creates new text widget with WordPress visual editor in it. It supports all default WordPress tinymce editor's features like in any Page/Post content editor.

== Installation ==

1. Upload `hc-text-widget.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use HC Text Widget in Appearance/Widgets

== Screenshots ==

1. Widget in available widgets
2. HC Text Widget window

== Changelog ==

= 1.2.2 =
* Widget value is now saved in JSON format instead of WP default serialized string (in case of database search and replace, serialized strings are broken, this way they are not)
* Optimized code

= 1.2.1 =
* Removed wpautop unction from output text
* Added filter "hc_widget_text" to output text

= 1.2 =
* Upgraded and more optimized code

= 1.1 =
* Plugin now supports WordPress 3.9
* Fixed reinit of editors on widget reorder

= 1.0 =
* Initial release