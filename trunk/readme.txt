=== Plugin Name ===
Contributors: Steven A. Zahm
Donate link: http://www.shazahm.net/?page_id=111
Tags: addresses, address book, addressbook, bio, bios, biographies, contact, contacts, connect, connections, directory, list, lists, listings, people, profile, profiles, plugin, user, users
Requires at least: 2.7
Tested up to: 2.7
Stable tag: 0.2.22
An address book that is managed in the admin and embed them in a post/page. Upcoming birthdays and anniversaries can also be embedded in a post/page.

== Description ==
This plugin was based off LBB, ["Little Black Book"](http://wordpress.org/extend/plugins/lbb-little-black-book/); which was based on [Addressbook](http://wordpress.org/extend/plugins/addressbook/), both of which can be found in the Plugin Directory. Though these are great, I needed something a bit more. See the overview list of changes below. With many more to come. **See the instructions under installation, above.**
This plugin is under active developement, please make sure you have a recent backup of your WordPress database, just in case.

* Added many more fields that include multiple addresses with selectable type; birthday and anniversary
* Each contact has selectable visibility. You can choose between, public, private and unlisted.
* Selectable entry type; Individual / Organization to allow this to be used as a business directory as well
* Added image support for entries.
* Added support for copying an entry.
* Bulk actions
* Entry filters
* Persistent filter state between sessions
* Reduced the number of page refreshes when adding or managing the entry list.
* Reduced the amount of scrolling needing when managing a large number of entries.
* Matched the visual style to fit nicely in the new 2.7 admin interface.
* Added shortcodes that can be used for embedding contact list and upcoming birthdays/anniversaries in a page and/or post.


**Upcoming features:**

* Assign entries to groups and have the option to display those groups in a page/post
* Create relational "Connections" between entries
* Sidebar widget
* Dashboard widget
* An update request form
* A print link for easy printing of the list
* A print link for printing out a list of birthdays and anniversaries
* Integration with WP Users to permit users to maintain their own info, mostly likely this will be optional on a per entry instance and an import method
* Alternate views for the contact list; card, condenced and profile
* Promote the plug-in to a top level menu item and add an option page
* Option to set custom image sizes* Option to set minimum role permitted to manage the plug-in
* Option to manage the address types
* Option to customize the input form
* Option to manage the relation types
* Import/Export
* Backup
* Session view state per user
* ...and any suggestion that I may receive...

**Known Issues:**

* **Currently when an image link is removed the files are not deleted from the server**
* The CSS style sheet is linked on every page in the admin
* The styles in the output html is hard coded
* The alpha anchors do not generate if a letter is skipped in the last names that were entered
* Not all fields that are input appear when output
* Styling for organization entries needs work
* Individuals and Organization show in the frontend output list; needs a shortcode to filter

== Screenshots ==
[Samples and screenshots can be found here](http://www.shazahm.net/?page_id=111)

== Installation ==
1. Upload the `connections` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. You can mange addresses by going to the Connections item under the Tools menu.


**Instructions:**

When adding a new entry in the entry list you have the option of choosing public, private or unlisted. This affects when a listing will be shown when embedded in a page/post. If an entry if public, that entry will show at all times. If an entry is private, that listing will only be shown when a registered user is logged into your site. By choosing unlisted, that entry will not be shown when embedded in a page/post and is only visible in the admin.

To embed a list in a page/post just enter the shortcode text and any of the options outlined below in the page/post text area.



`[connections_list]`

To insert a contact list in a page/post use the shortcode.


This shortcode has three available options:

1. id
2. private_override
3. show_alphaindex


The *id* option allows you to show the contact info for a single entry. Default is to show all public and/or private entries in the list. The ID can be found in the admin by showing the details for an entry. It will be labelled **Entry ID:**


`[connections_list id=2]`

To show a single entry use the *id* option.


The *private_override* option allows you to show the a contact list including all private entires whether the user is logged into your site or not. This is useful when you want to show a single private entry in a page/post to the public.


`[connections_list private_override=true]`

`[connections_list id=2 private_override=true]`

The above examples show overide the visibilty setting in the entries.


The *show_alphaindex* option inserts an an A thru Z anchor list at the head of the contact list. This is useful if you have many entries.


`[connections_list show_alphaindex=true]`

If you have a long list of entries you can use this option to show the option index.


There is also a second shortcode that can be use for displaying a list of upcoming birthdays and/or anniversaries.


`[upcoming_list]`

To show the upcoming birthdays use this shortcode. This defaults to showing birthdays for the next 30 days using the this date format: January 15th; and does not show last names.


This shortcode has six available options:


1. list_type
2. days
3. private_override
4. date_format
5. show_lastname
6. list_title


The *list_type* option allows you to change the listed upcoming dates from birthdays to anniversaries.


`[upcoming_list list_type=anniversary]`

Change the list to show the upcoming anniversaries.


The *days* option allows you to change the default 30 days to any numbers of days. This can be used with birthdays or anniversaries.


`[upcoming_list days=90]`

Use this option to change the default number of days ahead to show.


The list by default will only show public entries when a user is not logged into your site. By setting *private_override* to true this list will show all entries whether the user is logged in or not.


`[upcoming_list private_override=true]`

Use this option to overide the visibilty of the entries.


The *date_format* option allows you to customize the displayed date. The default is 'F jS'. Refer to the [PHP Manual](http://us2.php.net/date) for the format characters.


`[upcoming_list date_format="F jS Y"]` 

IF you would like to change the default year format that is displayed in the list use this option.


By default only the first letter of the last name will be shown. The *show_lastname* option can be used to show the full last name.


`[upcoming_list show_lastname=true]`

Use this option if you would like to show the last name.


The *list_title* option allows you to use custom text for the list title. Be default, if the list is a birthday list for the next 7 days, the title will read "Upcoming Birthdays for the next 7 days".


`[upcoming_list list_title="Any Text"]`

Use this option to define the list title to any text you wish.

== Frequently Asked Questions ==

* This plugin is developed and tested in Firefox. If your using IE and something doesn't work, try it again in Firefox.
* This plugin is also under active developement and as such features and settings could change. You may also have to re-enter or edit your entries after an upgrade. An effort will be made to keep this to a minimum.
* It also should be mentioned that I am not a web designer nor am I a PHP programmer, this plugin is being developed out of a need and for the learning experience.
* If support is needed use the forum on the wordpress.org site. Title the post "[Plugin: Connections] Your Problem". Also be sure to tag the post with "connections".

== Upgrading ==

There is no need to de-activate and then re-activate Connections to upgrade.

== Change Log ==

**0.2.3**

* First version in the repository

**0.2.4**

* Add entry ID to the admin

**0.2.7**

* Added preliminary image support for entries

**0.2.8**

* Fix bug that was causing IE to not filter correctly
* Code cleanup

**0.2.9**

* Some more code cleanup
* Started code documentation
* Added the ability to choose whether or not a linked image in an entry is displayed when embedded in a page/post
* Added the ability to remove the linked image from an entry

**0.2.10**

* Added the ability to copy an entry

**0.2.11**

* Added a nice little up arrow to both the admin and page/post entry list to return to the top of the list