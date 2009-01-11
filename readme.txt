=== Plugin Name ===
Contributors: Steven A. Zahm
Donate link: 
Tags: addresses, address book, addressbook, bio, bios, biographies, contact, contacts, connect, connections, directory, list, lists, listings, people, profile, profiles, plugin, user, users
Requires at least: 2.7
Tested up to: 2.7
Stable tag: 0.2.7

A address book plugin. Manage addresses in the admin and use the shortcodes to embed them in a post and/or page. An upcoming list of birthdays and anniversaries can also be embedded in a post and/or page.

== Description ==

This plugin was based off LBB, ["Little Black Book"](http://wordpress.org/extend/plugins/lbb-little-black-book/); which was based on [Addressbook](http://wordpress.org/extend/plugins/addressbook/), both of which can be found in the Plugin Directory. Though these are great, I needed something a bit more. Here's a overview list of changes:

* Added many more fields that include multiple addresses with selectable type; birthday and anniversary
* Each contact has selectable visibility. You can choose between, public, private and unlisted.
* Reduced the number of page refreshes when adding or managing the entry list.
* Reduced the amount of scrolling needing when managing a large number of entries.
* Matched the visual style to fit nicely in the new 2.7 admin interface.
* Added shortcodes that can be used for embedding contact list and upcoming birthdays/anniversaries in a page and/or post.
* Added preliminary image support for entries.

**Instructions:**

When adding a new entry in the entry list you have the option of choosing public, private or unlisted. This affects when a listing will be shown when embedded in a page/post. If an entry if public, that entry will show at all times. If an entry is private, that listing will only be shown when a registered user is logged into your site. By choosing unlisted, that entry will not be shown when embedded in a page/post and is only visible in the admin.


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
To show the upcoming birthdays use this shortcode.


This defaults to showing birthdays for the next 30 days using the this date format: January 15th; and does not show last names.

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


**Upcoming features:**

* Assign entries to groups and display those groups in a page/post
* Create "family" connections between entries
* Copy an entry
* Sidebar widget
* Dashboard widget
* An update request form
* A print link for easy printing of the list
* A print link for printing out a list of birthdays and anniversaries
* Integration with WP Users to permit users to maintain their own info, mostly likely this will be optional on a per entry instance
* Selectable entry type; Personal / Business to allow this to be used as a business directory as well
* Alternate views for the contact list; condenced and profile
* ...and any suggestion that I may receive...


**Known Issues:**

* **CURRENTLY THERE NO WAY TO REMOVE AN IMAGE LINKED TO AN ENTRY OTHER THAN DELETING THE ENTRY AND CREATING A NEW ENTRY WITHOUT THE IMAGE**
* The CSS style sheet is linked on every page in the admin
* The styles in the output html is hard coded
* When deleting an entry the entry list doesn't remember the filter setting


== Screenshots ==
[Samples and screenshots can be found here](http://www.shazahm.net/?page_id=111)


== Installation ==

1. Upload the `connections` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. You can mange addresses by going to the Connections item under the Tools menu.

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