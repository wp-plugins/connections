<div class="wrap">
	<h2>Connections : Help</h2>
	<p>To embed a list in a page/post just enter the shortcode text and any of the options outlined below in the page/post content area.</p>

	<code>[connections_list]</code><br />
	<span class="setting-description">To insert a contact list in a page/post use the shortcode.</span>
	
	<p>This shortcode has several available options:</p>
	
	<ol>
		<li>id</li>
		<li>private_override</li>
		<li>show_alphaindex</li>
		<li>list_type</li>
		<li>custom_template</li>
		<li>template_name</li>
		<li>last_name <strong>filter</strong></li>
		<li>title <strong>filter</strong></li>
		<li>organization <strong>filter</strong></li>
		<li>department <strong>filter</strong></li>
	</ol>
	
	<p>The <strong>id</strong> option allows you to show the info for a single entry. Default is to show all public and/or private entries in the list. 
	The ID can be found in the admin by showing the details for an entry. It will be labelled <strong><em>Entry ID:</em></strong></p>
	
	<code>[connections_list id=2]</code><br />
	<span class="setting-description">To show a single entry use the id option.</span>
	
	<p>The <strong>private_override</strong> option allows you to show the entry list including all private entires whether the user is logged into your site or not. 
	This is useful when you want to show a single private entry in a page/post to the public.</p>
	
	<code>[connections_list private_override='true']</code><br />
	<code>[connections_list id=2 private_override='true']</code><br />
	<span class="setting-description">The above examples show overide the visibilty setting in the entries in a list and a single entry.</span>
	
	<p>The <strong>show_alphaindex</strong> option inserts an A thru Z anchor list at the head of the entry list. This is useful if you have many entries.</p>
	
	<code>[connections_list show_alphaindex='true']</code><br />
	<span class="setting-description">If you have a long list of entries you can use this option to show the option index.</span>
	
	<p>The list_type option allows you to show all entries or you can choose to show only individuals or organizations.</p>
	
	[connections_list list_type='all']
	Use to show all entry types.
	
	[connections_list list_type='individual']
	Use to show only entries set as an individual.
	
	[connections_list list_type='organization']
	Use to show only entries set as an organization.
	
	An alternate list view has been provided -- profile view. This view can be used for a single entry or the list. An alternate card view has also been provided -- card-single. This template can be used when you wish to show a single entry. Use the template_name option and set to one of the provide alternate templates. See the examples below.
	
	[connections_list template_name='profile']
	This will ouput the list in the profile view.
	
	[connections_list id=2 template_name='card-single']
	This will ouput entry id 2 using the card-single template.
	
	If you create a custom template you need to set two options custom_template and template_name as such. For example, say you create a custom template named my-template.php. The template name you would enter in the option would be "my-template", dropping off the ".php".
	
	[connections_list custom_template='true' template_name='the_template_name']
	Both of these must be set in order to use a custom template and the custom template must be saved in the ./wp-content/connections_templates directory/folder.
	
	The filter attributes can be used one at a time per list or in combinations per list and are case sensitive. See the examples below.
	
	[connections_list last_name='Zahm']
	This will only output a list where the last name is "Zahm". Remember, filters are case sensitive.
	
	[connections_list organization='ACME' department='Accounting']
	This will only output a list where the organization is "ACME" AND where the department is "Accounting". Remember, filters are case sensitive.
	
	There is a second shortcode that can be use for displaying a list of upcoming birthdays and/or anniversaries. Please note that this shortcode, at the moment does not support the use of custom templates. This support will be coming in a future release.
	
	[upcoming_list]
	To show the upcoming birthdays use this shortcode. This defaults to showing birthdays for the next 30 days using the this date format: January 15th; and does not show last names. ** NOTE: Custom template is not supported with this shortcode. This will be added to a future version. **
	
	This shortcode has several available options:
	
	1.list_type
	2.days
	3.private_override
	4.date_format
	5.show_lastname
	6.list_title
	The list_type option allows you to change the listed upcoming dates from birthdays to anniversaries.
	
	[upcoming_list list_type='anniversary']
	Change the list to show the upcoming anniversaries.
	
	The days option allows you to change the default 30 days to any numbers of days. This can be used with birthdays or anniversaries.
	
	[upcoming_list days=90]
	Use this option to change the default number of days ahead to show.
	
	The list by default will only show public entries when a user is not logged into your site. By setting private_override to true this list will show all entries whether the user is logged in or not.
	
	[upcoming_list private_override='true']
	Use this option to overide the visibilty of the entries.
	
	The date_format option allows you to customize the displayed date. The default is 'F jS'. Refer to the PHP Manual for the format characters.
	
	[upcoming_list date_format='F jS Y'] 
	If you would like to change the default year format that is displayed in the list use this option.
	
	By default only the first letter of the last name will be shown. The show_lastname option can be used to show the full last name.
	
	[upcoming_list show_lastname='true']
	Use this option if you would like to show the last name.
	
	The list_title option allows you to use custom text for the list title. Be default, if the list is a birthday list for the next 7 days, the title will read "Upcoming Birthdays for the next 7 days".
	
	[upcoming_list list_title='Any Text']
	Use this option to define the list title to any text you wish

</div>
<div class="clear"></div>