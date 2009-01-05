=== Plugin Name ===
Contributors: Steven A. Zahm
Donate link:
Tags: addresses, address book, addressbook, contact, contacts, directory, lists, people
Requires at least: 2.7
Tested up to: 2.7
Stable tag: 0.2.3

A address book plugin. View and manage addresses in the admin back-end or embedded
in a post or page.

== Description ==

This plugin was based off LBB, "Little Black Book"; which was based on Addressbook, both of which can be found in the Plugin Directory. Though these are great, I needed something a bit more. Here's a overview list of changes:

* Added many more fields that include multiple addresses with selectable type; birthday and anniversary
* Each contact has selectable visibility. You can choose between, public, private and unlisted.
* Reduced the number of page refreshes when adding or managing the contact list.
* Reduced the amount of scrolling needing when managing a large number of addresses.
* Matched the visual style to fit nicely in the new 2.7 admin interface.
* Added shortcodes that can be used for embedding contact list and upcoming birthdays/anniversaries in a page and/or post.

**Instructions:**

When adding a new entry in the contact list you have the option of choosing public, private or unlisted. This affects when a listing will be shown when embedded in a page/post. If an entry if public, that entry will show at all times. If an entry is private, that listing will only be shown when a registered user is logged into your site. By choosing unlisted, that entry will not be shown when embedded in a page/post and is only visible in the admin.

To insert a contact list in a page/post use the shortcode:

`[connections_list]`

This shortcode has three available options:

# id
# private_override
# show_alphaindex


The *id* option allows you to show the contact info for a single entry. Default is all public and/or private entries.

Usage:

`[connections_list id=2]`

The *private_override* option allows you to show the a contact list including all private entires whether the user is logged into your site or not. This is useful when you want to sure a single private entry in a page/post to the public.

Usage:

`[connections_list private_override=true]` **or** `[connections_list id=2 private_override=true]`

The *show_alphaindex* option inserts an an A thru Z anchor list at the head of the contact list. This is useful if you have many entries.

Usage:

`[connections_list show_alphaindex=true]`



There is also a second shortcode that can be use for displaying a list of upcoming birthdays and/or anniversaries.

Usage:

`[upcoming_list]`

This defaults to showing birthdays for the next 30 days using the this date format: January 15th; and does not show last names.

This shortcode has six available options:

# list_type
# days
# private_override
# date_format
# show_lastname
# list_title


The *list_type* option allows you to change the listed upcoming dates from birthdays to anniversaries.

Usage:

`[upcoming_list list_type=anniversary]`

The *days* option allows you to change the default 30 days to any numbers of days. This can be used with birthdays or anniversaries.


Usage:

`[upcoming_list days=90]`

The list by default will only show public entries when a user is not logged into your site. By setting *private_override* to true this list will show all entries whether the user is logged in or not.

Usage:

`[upcoming_list private_override=true]`

The *date_format* option allows you to customize the displayed date. The default is 'F jS'. Refer to the [PHP Manual](http://us2.php.net/date) for the format characters.

Usage:

`[upcoming_list date_format="F jS Y"]` 

By default only the first letter of the last name will be shown. The *show_lastname* option can be used to show the full last name.

Usage:

`[upcoming_list show_lastname=true]`

The *list_title* option allows you to use custom text for the list title. Be default, if the list is a birthday list for the next 7 days, the title will read "Upcoming Birthdays for the next 7 days".

Usage:

`[upcoming_list list_title="Any Text"]`



Upcoming features:

* Picture support
* Assign entries to groups and display those groups in a page/post
* Create "family" connections between entries
* A print link for easy printing of the list
* ...and any suggestion that I may receive...



Known Issues:

* Currently the entry id has to be found in the output html
* The CSS style sheet is linked on every page in the admin
* The styles in the output html is hard coded




== Installation ==

1. Upload the `connections` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. You can mange addresses by going to the Connections item under the Tools menu.

== Upgrading ==

There is no need to de-activate and then re-activate Connections to upgrade.

== Version History ==
0.2.3 First version in the repository
