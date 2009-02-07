=== Plugin Name ===
Contributors: Steven A. Zahm
Donate link: http://www.shazahm.net/?page_id=111
Tags: addresses, address book, addressbook, bio, bios, biographies, contact, contacts, connect, connections, directory, list, lists, listings, people, profile, profiles, plugin, user, users
Requires at least: 2.7
Tested up to: 2.7
Stable tag: 0.3.2
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
* Persistent filter state per user between sessions
* Reduced the number of page refreshes when adding or managing the entry list.
* Reduced the amount of scrolling needing when managing a large number of entries.
* Matched the visual style to fit nicely in the new 2.7 admin interface.
* Added shortcodes that can be used for embedding entry list and upcoming birthdays/anniversaries in a page and/or post with many options.
* **NEW** Added the ability to use custom templates and provided many template tags. See the instructions under Installation.
* **NEW** Added a alternate list format / single entry format -- profile view.
* **NEW** Added a alternate single entry format -- single-card view.

**Upcoming features:**

* Assign entries to groups and have the option to display those groups in a page/post
* Create relational "Connections" between entries
* Sidebar widget
* Dashboard widget
* An update request form
* A print link for easy printing of the list
* A print link for printing out a list of birthdays and anniversaries
* Integration with WP Users to permit users to maintain their own info, mostly likely this will be optional on a per entry instance and an import method
* Promote the plug-in to a top level menu item and add an option page
* Option to set custom image sizes
* Option to set minimum role permitted to manage the plug-in
* Option to manage the address types
* Option to customize the input form
* Option to manage the relation types
* Import/Export
* Backup
* ...and any suggestion that I may receive...

**Known Issues:**

* **Currently when an image link is removed the files are not deleted from the server**
* When edited an entry the form doesn't proper hide the fields based on the entry type
* The styles in the output html is hard coded
* Not all fields that are input appear when output
* Styling for organization entries needs work

== Screenshots ==
[Samples and screenshots can be found here](http://www.shazahm.net/?page_id=111)

== Installation ==
1. Upload the `connections` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. You can mange addresses by going to the Connections item under the Tools menu.
4. If you wish to create and use custom template besure to create the `./wp-content/connections_templates` directory/folder. This is where you will copy any custom templates you might create.


**Instructions:**

When adding a new entry in the entry list you have the option of choosing public, private or unlisted. This affects when a listing will be shown when embedded in a page/post. If an entry if public, that entry will show at all times. If an entry is private, that listing will only be shown when a registered user is logged into your site. By choosing unlisted, that entry will not be shown when embedded in a page/post and is only visible in the admin.

To embed a list in a page/post just enter the shortcode text and any of the options outlined below in the page/post content area.

`[connections_list]`
To insert a contact list in a page/post use the shortcode.


This shortcode has several available options:

1. id
2. private_override
3. show_alphaindex
4. list_type
5. custom_template
6. template_name


The *id* option allows you to show the contact info for a single entry. Default is to show all public and/or private entries in the list. The ID can be found in the admin by showing the details for an entry. It will be labelled **Entry ID:**

`[connections_list id=2]`
To show a single entry use the *id* option.


The *private_override* option allows you to show the a contact list including all private entires whether the user is logged into your site or not. This is useful when you want to show a single private entry in a page/post to the public.

`[connections_list private_override='true']`
`[connections_list id=2 private_override='true']`
The above examples show overide the visibilty setting in the entries in a list and a single entry.


The *show_alphaindex* option inserts an A thru Z anchor list at the head of the entry list. This is useful if you have many entries.

`[connections_list show_alphaindex='true']`
If you have a long list of entries you can use this option to show the option index.


The *list_type* option allows you to show all entries or you can choose to show only individuals or organizations.

`[connections_list list_type='all']`
Use to show all entry types.

`[connections_list list_type='individual']`
Use to show only entries set as an individual.

`[connections_list list_type='organization']`
Use to show only entries set as an organization.

An alternate list view has been provided -- profile view. This view can be used for a single entry or the list. An alternate card view has also been provided -- card-single. This template can be used when you wish to show a single entry. 
Use the `template_name` option and set to one of the provide alternate templates. See the examples below.

`[connections_list template_name='profile']`
This will ouput the list in the profile view.

`[connections_list id=2 template_name='card-single']`
This will ouput entry id 2 using the card-single template.

If you create a custom template you need to set two options `custom_template` and `template_name` as such. For example, say you create a custom template named my-template.php. The template name you would enter in the option would be "my-template", dropping off the ".php".

`[connections_list custom_template='true' template_name='the_template_name']`
Both of these must be set in order to use a custom template and the custom template must be saved in the `./wp-content/connections_templates` directory/folder.

There is a second shortcode that can be use for displaying a list of upcoming birthdays and/or anniversaries. Please note that this shortcode, at the moment does not support the use of custom templates. This support will be coming in a future release.

`[upcoming_list]`
To show the upcoming birthdays use this shortcode. This defaults to showing birthdays for the next 30 days using the this date format: January 15th; and does not show last names. ** NOTE: Custom template is not supported with this shortcode. This will be added to a future version. **


This shortcode has several available options:

1. list_type
2. days
3. private_override
4. date_format
5. show_lastname
6. list_title


The *list_type* option allows you to change the listed upcoming dates from birthdays to anniversaries.

`[upcoming_list list_type='anniversary']`
Change the list to show the upcoming anniversaries.


The *days* option allows you to change the default 30 days to any numbers of days. This can be used with birthdays or anniversaries.

`[upcoming_list days=90]`
Use this option to change the default number of days ahead to show.


The list by default will only show public entries when a user is not logged into your site. By setting *private_override* to true this list will show all entries whether the user is logged in or not.

`[upcoming_list private_override='true']`
Use this option to overide the visibilty of the entries.


The *date_format* option allows you to customize the displayed date. The default is 'F jS'. Refer to the [PHP Manual](http://us2.php.net/date) for the format characters.

`[upcoming_list date_format='F jS Y']` 
If you would like to change the default year format that is displayed in the list use this option.


By default only the first letter of the last name will be shown. The *show_lastname* option can be used to show the full last name.

`[upcoming_list show_lastname='true']`
Use this option if you would like to show the last name.


The *list_title* option allows you to use custom text for the list title. Be default, if the list is a birthday list for the next 7 days, the title will read "Upcoming Birthdays for the next 7 days".

`[upcoming_list list_title='Any Text']`
Use this option to define the list title to any text you wish.



** Template Tags **

In the version 0.3 series of Connections the ability to use custom output templates with many tags were added that can be used for customizing the template. The template tags are used in nearly the same fashion as the template tags when developing WordPress themes. So if you know a little about HTML and have dabbled in WordPress them developement, creating custom templates for Connections should be very easy. Every tag must be wrapped in a PHP statment and echoed `<?php ?>`. See the example below. Custom templates must be saved in `./wp-content/connections_templates` directory/folder. To tell the Connections to use a custom template you must set the two template options when using the shortcode options mentioned above.

`<?php echo entry->getId(); ?>`
Example of a template tag that return the entry's ID.

`entry->getId()`
Returns the ID.

`entry->getFormattedTimeStamp('FORMAT')`
Returns the last updated time. The format is optional and conforms to the PHP standard, refer to the [PHP Manual](http://us2.php.net/date) for the format characters.

`entry->getUnixTimeStamp()`
Returns the last updated time in raw unix time format.

`entry->getHumanTimeDiff()`
Returns the last updated time using human time difference.

`entry->getFirstName()`
Returns the first name.

`entry->getLastName()`
Returns the last name.

`entry->getFullFirstLastName()`
Retuns the full name with the first name first. NOTE: if the entry type in an organization this will return the organizations name instead.

`entry->getFullLastFirstName()`
Retuns the full name with the last name first. NOTE: if the entry type in an organization this will return the organizations name instead.

`entry->getOrganization()`
Returns the organization.

`entry->getTitle()`
Returns the title.

`entry->getDepartment()`
Returns the department.

`entry->getAddresses()`
Returns an associative array containing all the addresses.

`entry->getPhoneNumbers()`
Returns an associative array containing all the phone numbers.

`entry->getEmailAddresses()`
Returns an associative array containing all the email addresses.

`entry->getIm()`
Returns an associative array containing all the IM ID's.

`entry->getWebsites()`
Returns an associative array containing all the websites.

`entry->getAnniversary('FORMAT')`
Returns the anniversary date for the entry. The format is optional and conforms to the PHP standard, refer to the [PHP Manual](http://us2.php.net/date) for the format characters.

`entry->getBirthday('FORMAT')`
Returns the birthday date for the entry. The format is optional and conforms to the PHP standard, refer to the [PHP Manual](http://us2.php.net/date) for the format characters.

`entry->getBio()`
Returns the biography.

`entry->getNotes()`
Returns the notes.

= These tags return some preformatted HTML blocks =

`entry->getThumbnailImage()`
Returns the thumbnail image.

`entry->getCardImage()`
Returns the card image.

`entry->getProfileImage()`
Returns the profile image.

`entry->getTitleBlock()`
Returns the title in a `<span>` tag.

`entry->getOrganizationBlock()`
Returns the organization in a `<span>` tag. NOTE: this will only ouput if the entry type is not and organization. To get the organization name, use one of the full name template tags.

`entry->getDepartmentBlock()`
Returns the department in a `<span>`.

`entry->getAddressBlock()`
Returns all the addresses in a `<div>` and each address item in a `<span>`.

`entry->getPhoneNumberBlock()`
Returns all the phone numbers in a `<div>` and each phone number item in a `<span>`.

`entry->getEmailAddressBlock()`
Returns all the email addresses in a `<div>` and each email address item in a `<span>`.

`entry->getImBlock()`
Returns all the IM ID's in a `<div>` and each IM item in a `<span>`.

`entry->getWebsiteBlock()`
Returns all the wesites in a `<div>` and each website item in a `<span>`.

`entry->getBirthdayBlock('FORMAT')`
Returns the birthday date in a `<span>`. The format is optional and conforms to the PHP standard, refer to the [PHP Manual](http://us2.php.net/date) for the format characters.

`entry->getAnniversaryBlock('FORMAT')`
Returns the anniversary date in a `<span>`. The format is optional and conforms to the PHP standard, refer to the [PHP Manual](http://us2.php.net/date) for the format characters.

`entry->getLastUpdatedStyle()`
Returns `color: VARIES BY AGE; ` that can be used in then style HTML tag.
Example usage: `<span style="<?php echo entry->getLastUpdatedStyle() ?>">Updated <?php echo entry->getHumanTimeDiff() ?></span>`
This will change the color of Updated and the timestamp in human difference time based on age.

`$entry->returnToTopAnchor()`
Returns the HTML anchor to return to the top of the entry list using an up arrow graphic.

== Frequently Asked Questions ==

= Upgrading =
There is no need to de-activate and then re-activate Connections to upgrade.

= Why don't all individuals show when I use the list_type option in the shortcode? =
Older versions of this plugin didn't set this property to an entry. To fix; edit all entries that should appear in the list by selecting the appropiate type and then save the entry.


= I get this error upon activation — "Parse error: syntax error, unexpected T_STRING, expecting T_OLD_FUNCTION or T_FUNCTION or T_VAR or '}' in *YOUR WP PATH* /wp-content/plugins/connections/includes/date.php on line 11", what is this? =

This plugin requires PHP 5. Turn on or ask your web host to turn on PHP 5 support. **OR** because the .php extension defaults to PHP 4 on their web server. The easiest fix is to add a handler that maps .php to PHP 5. If you have cPanel, you can do this easily by clicking on “Apache Handlers” and adding a mapping for “php” to “application/x-httpd-php5″ and that should fix the problem.

* This plugin is developed and tested in Firefox. If your using IE and something doesn't work, try it again in Firefox.
* This plugin is also under active developement and as such features and settings could change. You may also have to re-enter or edit your entries after an upgrade. An effort will be made to keep this to a minimum.
* It also should be mentioned that I am not a web designer nor am I a PHP programmer, this plugin is being developed out of a need and for the learning experience.
* If support is needed use the forum on the wordpress.org site. Title the post "[Plugin: Connections] Your Problem". Also be sure to tag the post with "connections".


== Change Log ==

=0.2.3=

* First version in the repository

=0.2.4=

* Add entry ID to the admin

=0.2.7=

* Added preliminary image support for entries

=0.2.8=

* Fix bug that was causing IE to not filter correctly
* Code cleanup

=0.2.9=

* Some more code cleanup
* Started code documentation
* Added the ability to choose whether or not a linked image in an entry is displayed when embedded in a page/post
* Added the ability to remove the linked image from an entry

=0.2.10=

* Added the ability to copy an entry

=0.2.11=

* Added a nice little up arrow to both the admin and page/post entry list to return to the top of the list

=0.2.22=

* Added Org/Individual options
* Added IM fields
* Added BIO field
* Added Org/Individual filter
* Started to convert the code to OO PHP

=0.2.23=

* Converted more code to use  OO methods
* Display some of the missing fields in the output

=0.2.24=

* Converted more code to use  OO methods
* Code clean-up and documentation
* Fixed the alpha index bug not correctly working with organization entry types
* Added a shortcode to allow showing all entries; individual or organizations entries

=0.3.2=

* Converted the rest of the code to use OO methods
* Started to add jQuery in the admin
* Fixed the CSS to load only in the Connections page
* All the fields that can be input are shown in the output
* Added the ability to use custom output templates and a slew of template tags
* Added a default profile template and a default single entry template