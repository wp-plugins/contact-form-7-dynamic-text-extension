=== Contact Form 7 Dynamic Text Extension ===
Contributors: sevenspark
Donate link: http://bit.ly/bVogDN
Tags: Contact Form 7, Contact, Contact Form, dynamic, text, input, GET, 
Requires at least: 2.9
Tested up to: 3.0.1
Stable tag: 1.0.1

This plugin provides a new tag type for the Contact Form 7 Plugin. It allows the dynamic generation of content for a text input box via any shortcode. 

== Description ==

Contact Form 7 is an excellent WordPress plugin that provides all sorts of flexible features for creating Contact Forms. I've used it on a variety 
of sites with great success.

One thing it doesn't handle, however, is dynamic content. Any default values in Contact Form 7 are static. But what if I wanted a Contact Form 
with pre-populated fields based on some other value? Some examples might include:

* Auto-filling a URL
* Auto-filling a Post ID
* Pre-populating a Product Number
* Referencing other content on the site

There are many more case-specific examples. I searched for a solution, and there are some decent hacks out there. Many of them are explored in this 
forum topic: [Contact Form 7 Input Fields Values as PHP Get-Viarables](http://wordpress.org/support/topic/contact-form-7-input-fields-values-as-php-get-viarables). 
However, they all involved hacking the current Contact Form 7 code, which means next time the plugin is updated their edits will be overwritten. Oops.

This Dynamic Text Extension plugin provides a more elegant solution that leaves the Contact Form 7 Plugin intact.

= WHAT DOES IT DO? =

This plugin provides a new tag type for the Contact Form 7 Plugin. It allows the dynamic generation of content for a text input box via any shortcode. 
For example, it comes with two built-in shortcodes that will allow the Contact Form to be populated from any $_GET PHP variable or any info from the 
get_bloginfo() function.

= HOW TO USE IT =

After installing and activating the plugin, the Contact Form 7 tag generator will have a new tag type: Dynamic Text Field. Most of the options will be 
familiar to Contact Form 7 users. There are two important fields:

**Dynamic Value**

This field takes a shortcode, with two important provisions:

	1. The shortcode should NOT include the normal square brackets ([ and ]). So, instead of [CF7_GET key='value'] you would use CF7_GET key='value' .
	2. Any parameters in the shortcode must use single quotes. That is: CF7_GET key='value' and not CF7_GET key="value"
	
**Uneditable Option**

As these types of fields should often remain uneditable by the user, there is a checkbox to turn this option on.


= INCLUDED SHORTCODES =

The plugin includes 2 basic shortcodes for use with the Dynamic Text extension. You can write your own as well - any shortcode will work

**PHP GET Variables**

Want to use a variable from the PHP GET array? Just use the CF7_GET shortcode. For example, if you want to get the foo parameter from the url 
http://mysite.com?foo=bar

Enter the following into the "Dynamic Value" input

CF7_GET key='foo'
Your Content Form 7 Tag will look something like this:

[dynamictext dynamicname "CF7_GET key='foo'"]

Your form's dynamicname text input will then be pre-populated with the value of foo, in this case, bar

**Blog Info**

Want to grab some information from your blog like the URL or the sitename? Use the CF7_bloginfo shortcode. For example, to get the site's URL:

Enter the following into the "Dynamic Value" input

CF7_bloginfo show='url'

Your Content Form 7 Tag will look something like this:

[dynamictext dynamicname "CF7_bloginfo show='url'"]

Your form's dynamicname text input will then be pre-populated with your site's URL



== Installation ==

This section describes how to install the plugin and get it working.

1. Download and install the Contact Form 7 Plugin located at http://wordpress.org/extend/plugins/contact-form-7/
1. Upload the plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. You will now have a "Dynamic Text" tag option in the Contact Form 7 tag generator


== Frequently Asked Questions ==

None.  Yet.


== Screenshots ==

1. The new Dynamic Text Field options.


== Changelog ==

= 1.0.1 =
* Fixed dependency issue.


== Upgrade Notice ==

Nothing yet.
