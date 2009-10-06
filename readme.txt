=== Plugin Name ===
Contributors: Steven A. Zahm
Donate link: http://connections-pro.com/
Tags: addresses, address book, addressbook, bio, bios, biographies, contact, contacts, connect, connections, directory, hcalendar, hcard, ical, icalendar, image, images, list, lists, listings, microformat, microformats, page, pages, people, profile, profiles, post, posts, plugin, shortcode, user, users, vcard
Requires at least: 2.7
Tested up to: 2.8.4
Stable tag: 0.5.48
Connections is a simple to use addressbook or business directory that is highly customizable.

== Description ==

Connections is a simple to use address book or business directory that is highly customizable with templates and shortcode attributes. The entry list can be embedded in a post/page using a simple shortcode that has many available options. The entry list can embedded multiple times in the same post/page using different options and even different templates. Also included; a second shortcode that can be used to show upcoming anniversaries and birthdays.

= Admin Features =
* Built-in help page
* Selectable entry type; Individual / Organization / Connection Group 
* Each entry has selectable visibility. You can choose between, public, private and unlisted
* Image support
* Copy entries
* Bulk actions that include setting the visibility on deleting entries
* Filters that is persistent per user between sessions and browsers
* **0.5.47** Added extensive role support.
* **0.5.47** Added the ability to set custom image sizes.
* **0.5.47** Added the ability to determine how an image should be scaled and cropped.

= Frontend Features =
* xHTML Transition output
* Custom template support
* Shortcode filter attributes for the entry list
* Alternate output list templates; single entry, multiple entry (default) and profile view
* Entries output in [hCard](http://microformats.org/wiki/hcard) compatible format.
* Download the vCard of an individual entry that can be imported into you email application.
* **0.5.1** Added a shortcode attribute to repeat the alpha index and the beginning of each character change. [User requested.](http://wordpress.org/support/topic/266754)
* **0.5.1** Added a shortcode attribute to show the current character at the beginning of each character group. [User requested.](http://wordpress.org/support/topic/266754)
* **0.5.1** Added additional filters for addresses. [User requested.](http://wordpress.org/support/topic/248568)

= New features coming in the next version: =
* Make the Connection Group relations in the front end entry list clickable to bring up the entry's specific details.
* Add social media fields
* Add honorable pre/suffix
* Add middle name

= Upcoming features: =
* Add CRM features
* Assign entries to groups and have the option to display those groups in a page/post
* Sidebar widget
* Dashboard widget
* An update request form
* A link for easy printing of the entry list
* A link for printing out a list of birthdays and anniversaries
* Integration with WP users to permit registered users to maintain their own info, mostly likely this will be optional on a per entry instance and an import method
* Option to manage the address types
* Option to customize the input form
* Option to manage the relation types
* Multiple images per entry or video
* Gravatar support
* Custom fields
* Import/Export
* Backup
* ...and any suggestion that I may receive...

= Credits: =
* This plugin was based off LBB, ["Little Black Book"](http://wordpress.org/extend/plugins/lbb-little-black-book/); which was based on [Addressbook](http://wordpress.org/extend/plugins/addressbook/), both of which can be found in the Plugin Directory.
* vCard class is a modified version by [Troy Wolf](http://www.troywolf.com/articles/php/class_vcard/)
* Image uploading and processing done by the class.upload.php by [Colin Verot](http://www.verot.net/php_class_upload.htm)
* Counter class from O'Reilly's [Intro to PHP Objects](http://www.onlamp.com/pub/a/php/2002/07/18/php_foundations.html?page=2)

= Known Issues: =

* Currently when an image link is removed the files are not deleted from the server
* The vCard feature is not compatible with [WP Super Cache](http://wordpress.org/extend/plugins/wp-super-cache/). Make sure the page/post excluded in WP Super Cache.

== Screenshots ==
[Samples and screenshots can be found here](http://connections-pro.com/?page_id=52)

Here are a couple of sites using Connections as a business directory.

[Frisco Notary](http://www.frisconotary.com/directory/)

[Tipmasters of Merdian](http://www.tipmasters.biz/index.php/directory/)

[Deaf and Hard of Hearing Services Center, Inc.](http://www.deafhh.net/wp/interpreters-2/)

== Installation ==
1. Upload the `connections` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
4. If you wish to create and use custom template be sure to create the `./wp-content/connections_templates` directory/folder. This is where you will copy any custom templates you might create.


== Frequently Asked Questions ==

= Upgrading =
Make sure to de-activate and then re-activate Connections to upgrade.

= Why don't all individuals show when I use the list_type option in the shortcode? =
Older versions of this plugin didn't set this property to an entry. To fix; edit all entries that should appear in the list by selecting the appropriate type and then save the entry.


= I get this error upon activation — "Parse error: syntax error, unexpected T_STRING, expecting T_OLD_FUNCTION or T_FUNCTION or T_VAR or '}' in *YOUR WP PATH* /wp-content/plugins/connections/includes/date.php on line 11", what is this? =

This plugin requires PHP 5. Turn on or ask your web host to turn on PHP 5 support. **OR** because the .php extension defaults to PHP 4 on your web server. The easiest fix is to add a handler that maps .php to PHP 5. If you have cPanel, you can do this easily by clicking on “Apache Handlers” and adding a mapping for “php” to “application/x-httpd-php5″ and that should fix the problem.

* This plugin is developed and tested in Firefox. If your using IE and something doesn't work, try it again in Firefox.
* This plugin is also under active development and as such features and settings could change. You may also have to re-enter or edit your entries after an upgrade. An effort will be made to keep this to a minimum.
* It also should be mentioned that I am not a web designer nor am I a PHP programmer, this plugin is being developed out of a need and for the learning experience.
* If support is needed use the forum on the wordpress.org site. Title the post "[Plugin: Connections] Your Problem". Also be sure to tag the post with "connections".

= Why do dotted underlines show under the dates? =

Some browsers put a dotted underline or border on each `<abbr>` tag. The `<abbr>` tag is needed for hCalendar event compatibility. To remove this from the styling, add `.vevent abbr{border:0}` to your theme's CSS.

== Changelog ==

= 0.5.XX X/X/X =
* BUG: Fixed a bug when intializing the settings so they are not reset on activation of the plugin.
* BUG: Fixed how capabilites were created and managed for public entries.
* BUG: Fixed how the entry edit link was created which was causing a token mismatch.
* BUG: Fixed a permission error when using the shortcode override attributes.
* Updated class.upload.php to version .28
* Moved all queries to the SQL class.
* Options class now is used throughout the plug-in rather than creating new instances.
* Renamed all the classes to be more unique to help avoid conflict with other plug-ins.
* Re-worked the way method used to store the cached entry list filters to use the user_meta table.
* Capturing more meta datawhen added/ updating entries; date added, added by, last edited by.
* Added Contact First/Last name for the organization entry type. Support extended to hCard and vCard.
* Added honorable pre/suffix and middle name. Support extended to hCard and vCard.
* Added a setting to disable the private override shortcode attribute.
* Added a filter class that can easily be extended. Currently supports filtering by visibility and entry type.
* Added a permission filter. All queries are run thru this filter removing any entries the current user is not permitted to view.

= 0.5.48 9/14/2009 =
* Fixed a jQuery bug in IE8.
* The alpha index in the admin is now dynamic. It will only show letters for entries in the list.

= 0.5.47 9/3/2009 =
* Updated class.upload.php to version .25
* Added extensive role support.
* Added the ability to set custom image sizes.
* Added the ability to determine how an image should be scaled and cropped.
* Extensive backend code changes.
* Focus on making sure the plug-in is secure.

= 0.5.1 - 6/21/2009 =
* Added a shortcode attribute to repeat the alpha index and the beginning of each character change. [User requested.](http://wordpress.org/support/topic/266754)
* Added a shortcode attribute to show the current character at the beginning of each character group. [User requested.](http://wordpress.org/support/topic/266754)
* Added additional filters for addresses. [User requested.](http://wordpress.org/support/topic/248568)
* Run the SQL queries through the `$wpdb->prepare()` method for security
* Change the change log so it shows up as a top level tab on the WordPress plug-in page

= 0.5.0 =
* Adding/Editing/Copying now use the class
* Added bulk delete.
* Added an entry type of Connection Group. This allows to you create relational links between entries.
* Moved the plug-in to be a top level menu item.
* Added a help sub-page.
* Added a setting page. Settings will actually be available in the next version.
* Added a donate button - a user request.
* Added a spiffy icon for the menu and page headers.

= 0.4.0 =
* Added hCard compatible markup
* xHTML Transitional valid output
* Added hCalendar compatible markup for birthdays and anniversaries.
* Birthday/anniversary will now show the next anniversary/birthday date for the entry.
* Added support for vCard download of an entry.

= 0.3.3 =
* Added shortcode filter attributes

= 0.3.2 =
* Converted the rest of the code to use OO methods
* Started to add jQuery in the admin
* Fixed the CSS to load only in the Connections page
* All the fields that can be input are shown in the output
* Added the ability to use custom output templates and a slew of template tags
* Added a default profile template and a default single entry template

= 0.2.24 =
* Converted more code to use OO methods
* Code clean-up and documentation
* Fixed the alpha index bug not correctly working with organization entry types
* Added a shortcode to allow showing all entries; individual or organizations entries

= 0.2.23 =
* Converted more code to use OO methods
* Display some of the missing fields in the output

= 0.2.22 =
* Added Org/Individual options
* Added IM fields
* Added BIO field
* Added Org/Individual filter
* Started to convert the code to OO PHP

= 0.2.11 =
* Added a nice little up arrow to both the admin and page/post entry list to return to the top of the list

= 0.2.10 =
* Added the ability to copy an entry

= 0.2.9 =
* Some more code cleanup
* Started code documentation
* Added the ability to choose whether or not a linked image in an entry is displayed when embedded in a page/post
* Added the ability to remove the linked image from an entry

= 0.2.8 =
* Fix bug that was causing IE to not filter correctly
* Code cleanup

= 0.2.7 =
* Added preliminary image support for entries

= 0.2.4 =
* Add entry ID to the admin

= 0.2.3 =
* First version in the repository