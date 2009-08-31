=== Plugin Name ===
Contributors: Steven A. Zahm
Donate link: http://www.shazahm.net/?page_id=111
Tags: addresses, address book, addressbook, bio, bios, biographies, contact, contacts, connect, connections, directory, hcalendar, hcard, ical, icalendar, image, images, list, lists, listings, microformat, microformats, page, pages, people, profile, profiles, post, posts, plugin, shortcode, user, users, vcard
Requires at least: 2.7
Tested up to: 2.8.4
Stable tag: 0.5.1
Connections is a simple to use addressbook that is highly customizable.

== Description ==
**ALERT:** Due to extensive changes please only update to this version if you wish to test and report bugs.

An address book / addressbook that is managed in the admin and embedded in a post/page. Upcoming birthdays and anniversaries can also be embedded in a post/page. **See the instructions under installation, above.**
This plugin is under active developement, please make sure you have a recent backup of your WordPress database, just in case.

* Many fields that include multiple addresses with selectable type; birthday and anniversary
* Each entry has selectable visibility. You can choose between, public, private and unlisted
* Selectable entry type; Individual / Connection Group / Organization to allow this to be used as a business directory as well
* Image support for entries
* Copy entries
* Admin Bulk actions
* Admin Entry filters
* Persistent filter state per user between sessions
* Shortcodes that can be used for embedding entry list and upcoming birthdays/anniversaries in a page and/or post with many options.
* Use custom templates and provided many template tags. See the instructions under Installation.
* Alternate list formats using the supllied templates — single entry view; profile view.
* Filter shortcode attributes for filtering the output list. See the instructions under Installation.
* xHTML Transition output.
* Entries output in [hCard](http://microformats.org/wiki/hcard) compatible format.
* Download vCard of entries for importing into you email application.
* Connection Groups. This allows to you create relational links between entries.
* **0.5.1** Added a shortcode attribute to repeat the alpha index and the beginning of each character change. [User requested.](http://wordpress.org/support/topic/266754)
* **0.5.1** Added a shortcode attribute to show the current character at the beginning of each character group. [User requested.](http://wordpress.org/support/topic/266754)
* **0.5.1** Added additional filters for addresses. [User requested.](http://wordpress.org/support/topic/248568)
* **NEW** Added extensive role support.
* **NEW** Added the ability to set custom image sizes.
* **NEW** Added the ability to determine how an image should be scaled and cropped.

**New features coming in the next version:**

* This feature [here](http://wordpress.org/support/topic/266754)
* Make the Connection Group relations in the output list clickable to bring up the entries specific details.

**Upcoming features:**

* Add CRM features
* Assign entries to groups and have the option to display those groups in a page/post
* Sidebar widget
* Dashboard widget
* An update request form
* Additional filters for the output.
* A print link for easy printing of the list
* A print link for printing out a list of birthdays and anniversaries
* Integration with WP Users to permit users to maintain their own info, mostly likely this will be optional on a per entry instance and an import method
* Promote the plug-in to a top level menu item and add an option page
* Option to manage the address types
* Option to customize the input form
* Option to manage the relation types
* Import/Export
* Backup
* ...and any suggestion that I may receive...

**Credits**

* This plugin was based off LBB, ["Little Black Book"](http://wordpress.org/extend/plugins/lbb-little-black-book/); which was based on [Addressbook](http://wordpress.org/extend/plugins/addressbook/), both of which can be found in the Plugin Directory.
* vCard class is a modified version by [Troy Wolf](http://www.troywolf.com/articles/php/class_vcard/)
* Image uploading and processing done by the class.upload.php by [Colin Verot](http://www.verot.net/php_class_upload.htm)
* Counter class from O'Reilly's [Intro to PHP Objects](http://www.onlamp.com/pub/a/php/2002/07/18/php_foundations.html?page=2)

**Known Issues:**

* Currently when an image link is removed the files are not deleted from the server
* The vCard feature is not compatible with [WP Super Cache](http://wordpress.org/extend/plugins/wp-super-cache/). Make sure the page/post excluded in WP Super Cache.


== Screenshots ==
[Samples and screenshots can be found here](http://www.shazahm.net/?page_id=111)

== Installation ==
1. Upload the `connections` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
4. If you wish to create and use custom template besure to create the `./wp-content/connections_templates` directory/folder. This is where you will copy any custom templates you might create.


== Frequently Asked Questions ==

= Upgrading =
There is no need to de-activate and then re-activate Connections to upgrade.

= Why don't all individuals show when I use the list_type option in the shortcode? =
Older versions of this plugin didn't set this property to an entry. To fix; edit all entries that should appear in the list by selecting the appropiate type and then save the entry.


= I get this error upon activation — "Parse error: syntax error, unexpected T_STRING, expecting T_OLD_FUNCTION or T_FUNCTION or T_VAR or '}' in *YOUR WP PATH* /wp-content/plugins/connections/includes/date.php on line 11", what is this? =

This plugin requires PHP 5. Turn on or ask your web host to turn on PHP 5 support. **OR** because the .php extension defaults to PHP 4 on your web server. The easiest fix is to add a handler that maps .php to PHP 5. If you have cPanel, you can do this easily by clicking on “Apache Handlers” and adding a mapping for “php” to “application/x-httpd-php5″ and that should fix the problem.

* This plugin is developed and tested in Firefox. If your using IE and something doesn't work, try it again in Firefox.
* This plugin is also under active developement and as such features and settings could change. You may also have to re-enter or edit your entries after an upgrade. An effort will be made to keep this to a minimum.
* It also should be mentioned that I am not a web designer nor am I a PHP programmer, this plugin is being developed out of a need and for the learning experience.
* If support is needed use the forum on the wordpress.org site. Title the post "[Plugin: Connections] Your Problem". Also be sure to tag the post with "connections".

= Why do dotted underlines show under the dates? =

Some browsers put a dotted underline or border on each `<abbr>` tag. The `<abbr>` tag is needed for hCalendar event compatibility. To remove this from the styling, add `.vevent abbr{border:0}` to your theme's CSS.

== Changelog ==

= 0.2.3 =
* First version in the repository

= 0.2.4 =
* Add entry ID to the admin

= 0.2.7 =
* Added preliminary image support for entries

= 0.2.8 =
* Fix bug that was causing IE to not filter correctly
* Code cleanup

= 0.2.9 =
* Some more code cleanup
* Started code documentation
* Added the ability to choose whether or not a linked image in an entry is displayed when embedded in a page/post
* Added the ability to remove the linked image from an entry

= 0.2.10 =
* Added the ability to copy an entry

= 0.2.11 =
* Added a nice little up arrow to both the admin and page/post entry list to return to the top of the list

= 0.2.22 =
* Added Org/Individual options
* Added IM fields
* Added BIO field
* Added Org/Individual filter
* Started to convert the code to OO PHP

= 0.2.23 =
* Converted more code to use OO methods
* Display some of the missing fields in the output

= 0.2.24 =
* Converted more code to use OO methods
* Code clean-up and documentation
* Fixed the alpha index bug not correctly working with organization entry types
* Added a shortcode to allow showing all entries; individual or organizations entries

= 0.3.2 =
* Converted the rest of the code to use OO methods
* Started to add jQuery in the admin
* Fixed the CSS to load only in the Connections page
* All the fields that can be input are shown in the output
* Added the ability to use custom output templates and a slew of template tags
* Added a default profile template and a default single entry template

= 0.3.3 =
* Added shortcode filter attributes

= 0.4.0 =
* Added hCard compatible markup
* xHTML Transitional valid output
* Added hCalendar compatible markup for birthdays and anniversaries.
* Birthday/anniversary will now show the next anniversary/birthday date for the entry.
* Added support for vCard download of an entry.

= 0.5.0 =
* Adding/Editing/Copying now use the class
* Added bulk delete.
* Added an entry type of Connection Group. This all ows to you create relational links between entries.
* Moved the plug-in to be a top level menu item.
* Added a help sub-page.
* Added a setting page. Settings will actually be available in the next version.
* Added a donate button - a user request.
* Added a spiffy icon for the menu and page headers.

= 0.5.1 6/21/2009 =
* Added a shortcode attribute to repeat the alpha index and the beginning of each character change. [User requested.](http://wordpress.org/support/topic/266754)
* Added a shortcode attribute to show the current character at the beginning of each character group. [User requested.](http://wordpress.org/support/topic/266754)
* Added additional filters for addresses. [User requested.](http://wordpress.org/support/topic/248568)
* Run the SQL queries through the `$wpdb->prepare()` method for security
* Change the change log so it shows up as a top level tab on the WordPress plug-in page

= 0.5.44 DATE =
* Updated class.upload.php to version .25
* Added extensive role support.
* Added the ability to set custom image sizes.
* Added the ability to determine how an image should be scaled and cropped.
* Extensive backend code changes.
* Focus on making sure the plg-in is secure.