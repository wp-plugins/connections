<?php
function connectionsShowHelpPage()
{
?>
	
	<div id="help" class="wrap">
	<div id="icon-connections" class="icon32">
		<br>
	</div>
	
	<h2>Connections : Help</h2>
	
	<div class="updated fade" id="message"><p><strong>IMPORTANT: </strong>If you copy code from this page, make sure you switch the editor
	to the HTML tab before pasting, otherwise extra code will be copied which will cause the directory to be incorrectly displayed</p></div>
	
	<div id="toc">
		<ol>
			<li><a href="#tocIdUsageInstructions">Usage Instructions</a>
				<ul>
					<li><a href="#tocIdDisplay">Display on a Page / Post</a></li>
					<li><a href="#tocIdConnectionGroups">Connection Groups</a></li>
				</ul>
			</li>
			
			<li><a href="#tocIdShortCodeInstructions">Shortcode Instructions</a>
	
				<ul>
					<li><a href="#tocIdEntryList">Entry list and Options</a></li>
					<li><a href="#tocIdCelebrateList">Celebrate list and Options</a></li>
				</ul>
			</li>
			
			<li><a href="#tocIdTemplateTags">Template Tags</a>
			
				<ul>
					<li><a href="#tocIdhCard">hCard Compatible Tags</a></li>
				</ul>
			</li>
			
			<li><a href="#tocIdThemeTags">Theme Template Tags</a></li>
			
			<li><a href="#tocIdFilters">Filters</a></li>
			
			<li><a href="#tocIdSupport">Support</a></li>
				
			<li><a href="#tocIdFAQ">FAQ</a></li>
				
			<li><a href="#tocIdDisclaimers">Disclaimers</a></li>
		
		</ol>
			
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="5070255">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
			
	</div>
		
		<h3><a name="tocIdUsageInstructions"></a>Usage Instructions:</h3>
		
		<h4><a name="tocIdDisplay"></a>Display</h4>
		<p>To display the directory in a page or post you must use a shortcode. 
		The shortcode is <code>[connections_list]</code>. You can also display a list of upcoming anniversaries and birthdays using 
		<code>[upcoming_list]</code>. Both shortcodes have many options to customize the display
		of both lists. See the <a href="#tocIdShortCodeInstructions">Shortcode Instructions</a> section for additonal instructions.</p>
		
		<h4><a name="tocIdConnectionGroups"></a>Families</h4>
		<p>Here is an example on how to use the Family entry type.</p>
		
		<p>Say we have a family, the Doe's.</p>
		
		<ol>
		<li>John</li>
		<li>Jane</li>
		<li>Girl</li>
		<li>Boy</li>
		</ol>

		<p>First, lets start with one of the parent's, John. Create a new Individual entry for John and put in all his personal info.
		Second, simply copy John and change the first name to Jane and add/change any info that relates only to her for example her birthday. 
		Third, do the same for Girl and Boy.</p>
		
		<p>Now create a new entry using the Family type. For the Family Name enter something like "Doe, John &amp; Jane" 
		and then click the "Add Relation" button for each member in the family, in this example, four times. 
		In the first column of drop downs select each family member and in the second column of 
		drop downs select their relationships.</p>
		
		<p>Finally, just add a the following attribute to the shortcode like such; [connections_list list_type='family']</p>
		
	<h3><a name="tocIdShortCodeInstructions"></a>Shortcode Instructions:</h3>
	
	<p><a name="tocIdEntryList"></a>To embed an entry list in a page/post just enter the
	shortcode text and any of the options outlined below in
	the page/post content area.</p>
	<pre>
	<code>[connections_list]</code>
	</pre>
	
	<p>This shortcode has many options to customize the content and display of the list.
	Here is the list of all the available attributes.</p>
	
	<ol>
		<li><a href="#cn_attr_id">id</a></li>
			
		<li><a href="#cn_attr_category">category</a></li>
		
		<li><a href="#cn_attr_wp_current_category">wp_current_category</a></li>
	
		<li><a href="#cn_attr_private_override">private_override</a></li>
			
		<li><a href="#cn_attr_public_override">public_override</a></li>
	
		<li><a href="#cn_attr_show_alphaindex">show_alphaindex</a></li>
			
		<li><a href="#cn_attr_repeat_alphaindex">repeat_alphaindex</a></li>
			
		<li><a href="#cn_attr_show_alphahead">show_alphahead</a></li>
	
		<li><a href="#cn_attr_list_type">list_type</a></li>
		
		<li><a href="#cn_attr_template_name">template</a></li>
		
		<li><a href="#cn_attr_order_by">order_by</a>
		
		<li><a href="#cn_attr_filters">filters</a>
		
	</ol>
	
	<a name="cn_attr_id"></a>
	<fieldset>
		<legend>id</legend>
		
		<p>The <em>id</em> option allows you to show the
		entry details for a single entry or multiple specific entries.
		To show multiple specific entries, list all the entries to be displayed
		by their <em>id</em> number separated by commas within single quotes. Default is to show all
		public and/or private entries in the list. The ID can
		be found in the admin by showing the details for an
		entry. It will be labeled <strong>Entry ID:</strong></p>
	
		<p><code>[connections_list id=2]</code></p>
		<p><code>[connections_list id='2,12,37']</code></p>
		
	</fieldset>
	
	<a name="cn_attr_category"></a>
	<fieldset>
		<legend>category</legend>
		
		<p>The <em>category</em> option allows you to show entries within a specific category
		and all children categories. The category ID can be found on the Category page in the admin. 
		the default is to show all available categories.</p>
		
		<p><code>[connections_list category=12]</code></p>
		
		<p>To show entries in multiple specific categories and their children, list the categories by id
		seperated by commas as show in the following example.</p>
		
		<p><code>[connections_list category='1,3,9']</code></p>
		
		<p><strong>NOTE:</strong> This attribute is an operational OR function. An entry will be shown if it is in any one of the specified categories
		or one of their children categories.</p>
	</fieldset>
	
	<a name="cn_attr_wp_current_category"></a>
	<fieldset>
		<legend>wp_current_category</legend>
		
		<p>If the shortcode is used on a post and <em>wp_current_category</em> is set to TRUE, the only 
		   entries that will be shown are those that have the same exact name as the categories that are assigned to the post.</p>
	 
		<p><code>[connections_list wp_current_category='true']</code></p>
		
		<p><strong>NOTE:</strong> In order for this attribute to function, the shortcode must be used on a post and the categories names of both the entry 
		and the post must be exactly identical.</p>
	</fieldset>
	
	<p><a name="cn_attr_private_override"></a>The <em>private_override</em> option allows you to
	show the a contact list including all private entries
	whether the user is logged into your site or not,
	but the admin can choose to remove private access and use the role options to choose
	a role the is permitted to view private entries.
	NOTE: this option can be disabled by the site admin.</p>

	<pre>
	<code>[connections_list private_override='true']</code>
	</pre>
	<pre>
	<code>[connections_list id=2 private_override='true']</code>
	</pre>
	
	<p><a name="cn_attr_public_override"></a>The <em>public_override</em> option allows you to
	show the a contact list including all public entries
	whether the user is logged into your site or not. By default all entries
	set to public would be displayed to site visitors whether they were logged in or not,
	but the admin can choose to remove public access and use the role options to choose
	a role the is permitted to view public entries. NOTE: this option can be disabled by the site admin.</p>

	<pre>
	<code>[connections_list public_override='true']</code>
	</pre>
	
	<p><a name="cn_attr_show_alphaindex"></a>The <em>show_alphaindex</em> option inserts an A
	thru Z anchor list at the head of the entry list. This
	is useful if you have many entries.</p>

	<pre>
	<code>[connections_list show_alphaindex='true']</code>
	</pre>
	
	<p><a name="cn_attr_repeat_alphaindex"></a>The <em>repeat_alphaindex</em> option inserts an A
	thru Z anchor list at the beginning of every character group.
	NOTE: the alpha index will only repeat if the <em>show_alphaindex</em>
	shortcode attribute is set to true</p>

	<pre>
	<code>[connections_list repeat_alphaindex='true']</code>
	</pre>
	
	<p><a name="cn_attr_show_alphahead"></a>The <em>show_alphahead</em> option inserts the current character
	at the beginning of each character group.</p>

	<pre>
	<code>[connections_list show_alphahead='true']</code>
	</pre>
	
	<p><a name="cn_attr_list_type"></a>The <em>list_type</em> option allows you to show all
	entries or you can choose to show only individuals, organizations and families. You can also show multiple entry types
	by listing the types you wish to display separated by commas. Setting the list_type option will also display the active
	template assigned to the entry type set in the template manager. If multiple entry types are specified, the template for
	the entry type listed first will be used to display the entry list.</p>
	
	<p>Use to show all entry types.</p>
	<pre>
	<code>[connections_list]</code>
	</pre>
	
	
	<p>Use to show only entries set as an individual.</p>
	<pre>
	<code>[connections_list list_type='individual']</code>
	</pre>
	
	
	<p>Use to show only entries set as an organization.</p>
	<pre>
	<code>[connections_list list_type='organization']</code>
	</pre>
	
	
	<p>Use to show only entries set as families.</p>
	<pre>
	<code>[connections_list list_type='family']</code>
	</pre>
	
	<p>Use to show only entries set as individual and family.</p>
	<pre>
	<code>[connections_list list_type='individuals,family']</code>
	</pre>
	

	<p><a name="cn_attr_template_name"></a>
	Two alternate template have been provieded. Use the <code>template_name</code>
	option and set it to one of the alternate templates. See the examples.</p>

	<p>This will ouput the list in the profile view.</p>
	<pre>
	<code>[connections_list template_name='profile']</code>
	</pre>
	
	
	<p>This will ouput entry id 2 using the card-single
	template.</p>
	<pre>
	<code>[connections_list id=2 template_name='card-single']</code>
	</pre>
	
	
	<p>If you create a custom template it must be saved in the
	<code>./wp-content/connections_templates</code> folder. It can  
	be call by using the <code>template_name</code> attribute. 
	The plug-in will automatically check in the custom template folder.
	For example, say you create a custom template named my-template.php. 
	The template name you would enter in the option would be
	"my-template", leaving off the ".php".</p>

	<pre>
	<code>[connections_list template_name='my-template']</code>
	</pre>
	
	<a name="cn_attr_order_by"></a>
	<fieldset>
		<legend>order_by</legend>
		
		<p>The <em>order_by</em> attribute can be used to apply a custom sort to the list.
		Here are the available sort fields and attributes.</p>
		<p><strong>Sort fields:</strong>
			<ul>
				<li>id</li>
				<li>date_added</li>
				<li>date_modified</li>
				<li>first_name</li>
				<li>last_name</li>
				<li>title</li>
				<li>organization</li>
				<li>department</li>
				<li>city</li>
				<li>state</li>
				<li>zipcode</li>
				<li>country</li>
				<li>birthday</li>
				<li>anniversary</li>
			</ul>
		</p>
		<p><strong>Order Flags:</strong>
			<ul>
				<li>SORT_ACS</li>
				<li>SORT_DESC</li>
				<li>SPECIFIED**</li>
				<li>RANDOM**</li>
			</ul>
		</p>
		<p><strong>Sort Types:</strong>
			<ul>
				<li>SORT_REGULAR</li>
				<li>SORT_NUMERIC</li>
				<li>SORT_STRING</li>
			</ul>
		</p>
		
		<p><strong>**NOTE:</strong> The SPECIFIED and RANDOM Order Flags can only 
		 be used with the id sort field. The SPECIFIED flag must be used in conjuction
		 with the id shortcode attribute which must be a comma delimited of entry IDs. 
		 If this is set, other sort fields/flags are ignored.</p>
		
		<p>You can put your fields in order to be sorted separated by commas. You can also add the flag to tell it to be sorted ascending or descending like so:</p>
		
		<pre>
		<code>order_by='state|SORT_ACS,zipcode|SORT_DESC'</code>
		</pre>
		
		<p>Notice that the flag is right after the field separated by a 'pipe' | . This character is usually placed above the backslash key.</p>
		<p>It will automatically try to figure the type of field that is being sorted. For example, last_name will be sorted as a string and zipcode as numeric. Lets say for some reason you want to force the zipcode to sort as a string:</p>
		
		<pre>
		<code>order_by='state|SORT_ACS|SORT_STRING,zipcode|SORT_DESC'</code>
		</pre>
		
		<p>You would add another pipe and add the sort type. This can be mixed and match for each field you wish to sort by.</p>
	</fieldset>
	
	<a name="cn_attr_filters"></a>
	<fieldset>
		<legend>filters</legend>
		
		<p>The filter attributes can be used one at a time per
		list or in combinations per list and are case
		sensitive. See the following examples.</p>
		
		<ul>
			<li>last_name</li>
			<li>group_name</li>
			<li>title</li>
			<li>organization</li>
			<li>department</li>
			<li>city</li>
			<li>state</li>
			<li>zip_code</li>
			<li>country</li>
		</ul>
		
		<pre>
		<code>[connections_list last_name='Zahm']</code>
		</pre>
		
		<p>This will only output a list where the last name is
		"Zahm". Remember, filters are case sensitive.</p>
	
		<pre>
		<code>[connections_list organization='ACME' department='Accounting']</code>
		</pre>
		
		<p>This will only output a list where the organization
		is "ACME" AND where the department is "Accounting".
		Remember, filters are case sensitive.</p>
					
		<p>The rest of the filters function identically.</p>
		
		<p><strong>NOTE:</strong> If you
		have multiple addresses assigned to an entry, the address filters will
		search all addresses and will display the entry if a match is found.</p>
		
		<p><strong>NOTE:</strong> If you
		want to filter names with with apostrophes quote the filter using the double quote.</p>
		</fieldset>
	
	<p><a name="tocIdCelebrateList"></a>There is a second shortcode that can be use for
	displaying a list of upcoming birthdays and/or
	anniversaries. Please note that this shortcode, at the
	moment does not support the use of custom templates.
	This support will be coming in a future release.</p>

	<pre>
	<code>[upcoming_list]</code>
	</pre>
	
	<p>To show the upcoming birthdays use this shortcode.
	This defaults to showing birthdays for the next 30 days
	using the this date format: January 15th; and does not
	show last names. ** NOTE: Custom template is not
	supported with this shortcode. This will be added to a
	future version. **</p>

	<p>This shortcode has several available options:</p>

	<ol>
		<li>list_type</li>

		<li>days</li>

		<li>private_override</li>

		<li>date_format</li>

		<li>show_lastname</li>

		<li>list_title</li>
	</ol>
	
	<p>The <em>list_type</em> option allows you to change
	the listed upcoming dates from birthdays to
	anniversaries.</p>

	<pre>
	<code>[upcoming_list list_type='anniversary']</code>
	</pre>
	
	<pre>
	<code>[upcoming_list list_type='birthday']</code>
	</pre>
	
	
	<p>The <em>days</em> option allows you to change the
	default 30 days to any numbers of days. This can be
	used with birthdays or anniversaries. If this attribute
	is not set, birthdays will show by default.</p>

	<pre>
	<code>[upcoming_list days=90]</code>
	</pre>
	
	<p>The list by default will only show public entries
	when a user is not logged into your site. By setting
	<em>private_override</em> to true this list will show
	all entries whether the user is logged in or not.</p>

	<pre>
	<code>[upcoming_list private_override='true']</code>
	</pre>
	
	
	<p>The <em>date_format</em> option allows you to
	customize the displayed date. The default is 'F jS'.
	Refer to the <a href="http://us2.php.net/date">PHP
	Manual</a> for the format characters.</p>

	<pre>
	<code>[upcoming_list date_format='F jS Y']</code>
	</pre>
	
	<p>By default only the first letter of the last name
	will be shown. The <em>show_lastname</em> option can be
	used to show the full last name.</p>

	<pre>
	<code>[upcoming_list show_lastname='true']</code>
	</pre>
	
	<p>The <em>list_title</em> option allows you to use
	custom text for the list title. The default, if the list
	is a birthday list for the next 7 days, the title will
	read "Upcoming Birthdays for the next 7 days".</p>

	<pre>
	<code>[upcoming_list list_title='Any Text']</code>
	</pre>
	
	<h3><a name="tocIdTemplateTags"></a>Template Tags</h3>

	<p>Connections has the ability to use custom 
	templates and many tags can be utilized
	for customizing the template. The
	template tags are used in nearly the same fashion as
	the template tags when developing WordPress themes. So
	if you know a little about HTML and have dabbled in
	WordPress theme developement, creating custom templates
	for Connections should be very easy. Every tag must be
	wrapped in a PHP statment and echoed <code>&lt;?php
	?&gt;</code>. See the example below. Custom templates
	must be saved in
	<code>./wp-content/connections_templates</code>
	directory/folder. To tell the Connections to use a
	custom template you must set the two template options
	when using the shortcode options mentioned above. If
	these are used you will have to ensure you class the
	items correctly in order to maintain hCard
	compatibility. Otherwise use the template tags that
	output preformatted HTML to maintain hCard
	compatibility.</p>

	<pre><code>&lt;?php echo $entry-&gt;getId(); ?&gt;</code></pre>
	<p>Example of a template tag that return the entry's ID.</p>

	<pre><code>$entry-&gt;getId()</code></pre>
	<p>Returns the ID.</p>

	<pre><code>$entry-&gt;getFormattedTimeStamp('FORMAT')</code></pre>
	<p>Returns the last updated time. The format is
	optional and conforms to the PHP standard, refer to the
	<a href="http://us2.php.net/date">PHP Manual</a> for
	the format characters.</p>

	<pre><code>$entry-&gt;getUnixTimeStamp()</code></pre>
	<p>Returns the last updated time in raw unix time format.</p>

	<pre><code>$entry-&gt;getHumanTimeDiff()</code></pre>
	<p>Returns the last updated time using human time difference.</p>

	<pre><code>$entry-&gt;getFirstName()</code></pre>
	<p>Returns the first name.</p>

	<pre><code>$entry-&gt;getLastName()</code></pre>
	<p>Returns the last name.</p>
	
	<pre><code>$entry-&gt;getFullFirstLastName()</code></pre>
	<p>Returns the full name with the first name first.
	NOTE: if the entry type in an Organization or Connection Group 
	this will return the Organization/Connection Group name instead.</p>

	<pre><code>$entry-&gt;getFullLastFirstName()</code></pre>
	<p>Retuns the full name with the last name first. NOTE:
	if the entry type in an Organization or Connection Group
	this will return the Organization/Connection Group name instead.</p>
	
	<pre><code>$entry-&gt;getFamilyName()</code></pre>
	<p>Returns the Connection Group name.</p>
	
	<pre><code>$entry-&gt;getConnectionGroup()</code></pre>
	<p>Returns an associative array containing the relation's entry id 
	as the key and the relation</p>
	
	<pre><code>$entry-&gt;getOrganization()</code></pre>
	<p>Returns the organization.</p>

	<pre><code>$entry-&gt;getTitle()</code></pre>
	<p>Returns the title.</p>

	<pre><code>$entry-&gt;getDepartment()</code></pre>
	<p>Returns the department.</p>

	<pre><code>$entry-&gt;getAddresses()</code></pre>
	<p>Returns an object containing all the addresses.</p>

	<pre><code>$entry-&gt;getPhoneNumbers()</code></pre>
	<p>Returns an object containing all the	phone numbers.</p>
	
	<pre><code>$entry-&gt;getEmailAddresses()</code></pre>
	<p>Returns an object containing all the email addresses.</p>

	<pre><code>$entry-&gt;getIm()</code></pre>
	<p>Returns an object containing all the IM ID's.</p>
	
	<pre><code>$entry-&gt;getSocialMedia()</code></pre>
	<p>Returns an object containing all the social network ID's.</p>
	
	<pre><code>$entry-&gt;getWebsites()</code></pre>
	<p>Returns an object containing all the websites.</p>

	<pre><code>$entry-&gt;getAnniversary('FORMAT')</code></pre>
	<p>Returns the anniversary date for the entry. The
	format is optional and conforms to the PHP standard,
	refer to the <a href="http://us2.php.net/date">PHP
	Manual</a> for the format characters.</p>

	<pre><code>$entry-&gt;getBirthday('FORMAT')</code></pre>
	<p>Returns the birthday date for the entry. The format
	is optional and conforms to the PHP standard, refer to
	the <a href="http://us2.php.net/date">PHP Manual</a>
	for the format characters.</p>

	<pre><code>$entry-&gt;getBio()</code></pre>
	<p>Returns the biography.</p>

	<pre><code>$entry-&gt;getNotes()</code></pre>
	<p>Returns the notes.</p>

	<h4><a name="tocIdhCard"></a>These tags return some
	preformatted HTML blocks and should be used to maintain
	hCard compatibility.</h4>

	<pre><code>$entry-&gt;getThumbnailImage()</code></pre>
	<p>Returns the thumbnail image.</p>

	<pre><code>$entry-&gt;getCardImage()</code></pre>
	<p>Returns the card image.</p>

	<pre><code>$entry-&gt;getProfileImage()</code></pre>
	<p>Returns the profile image.</p>

	<pre><code>$entry-&gt;getFullFirstLastNameBlock()</code></pre>
	<p>Returns the full name with the first name first.
	NOTE: if the entry type in an Organization or a Connection Group
	this will return the Organization/Connection Group name instead.</p>

	<pre><code>$entry-&gt;getFullLastFirstNameBlock()</code></pre>
	<p>Returns the full name with the last name first. NOTE:
	if the entry type in an Organization or a Connection Group
	this will return the Organization/Connection Group name instead.</p>
	
	<pre><code>$entry-&gt;getConnectionGroupBlock()</code></pre>
	<p>Returns the Connection Group items in a <code>&lt;span&gt;</code> tag followed by a <code>&lt;br&gt;</code>.</p>
	
	<pre><code>$entry-&gt;getTitleBlock()</code></pre>
	<p>Returns the title in a <code>&lt;span&gt;</code>tag.</p>

	<pre><code>$entry-&gt;getOrgUnitBlock()</code></pre>
	<p>Returns the organization ** AND ** the department in
	a <code>&lt;div&gt;</code> tag with each wrapped in a
	span. NOTE: this will only output the organization if
	the entry type is not an organization, but will still
	output the department if applicable. To get the
	organization name, use one of the full name template
	tags.</p>

	<pre><code>$entry-&gt;getOrganizationBlock()</code></pre>
	<p>Returns the organization in a
	<code>&lt;span&gt;</code>. If the entry type is an
	organization, this tag will not output any HTML. You
	should use one of the full name tags to get the
	organization name.</p>

	<pre><code>$entry-&gt;getDepartmentBlock()</code></pre>
	<p>Returns the department in a <code>&lt;span&gt;</code>.</p>

	<pre><code>$entry-&gt;getAddressBlock()</code></pre>
	<p>Returns all the addresses in a
	<code>&lt;div&gt;</code> and each address item in a
	<code>&lt;span&gt;</code>. NOTE: in order for proper
	hCard support the address must have a type assign;
	either work or home. If none is set, the entry type
	will be used to set the address type as either home or
	work.</p>

	<pre><code>$entry-&gt;getPhoneNumberBlock()</code></pre>
	<p>Returns all the phone numbers in a
	<code>&lt;div&gt;</code> and each phone number item in
	a <code>&lt;span&gt;</code>.</p>

	<pre><code>$entry-&gt;getEmailAddressBlock()</code></pre>
	<p>Returns all the email addresses in a
	<code>&lt;div&gt;</code> and each email address item in
	a <code>&lt;span&gt;</code>.</p>

	<pre><code>$entry-&gt;getImBlock()</code></pre>
	<p>Returns all the IM ID's in a
	<code>&lt;div&gt;</code> and each IM item in a
	<code>&lt;span&gt;</code>.</p>
	
	<pre><code>$entry-&gt;getSocialMediaBlock()</code></pre>
	<p>Returns all the social network ID's as a link to the profile page in a
	<code>&lt;div&gt;</code> and each network in a <code>&lt;span&gt;</code>.</p>

	<pre><code>$entry-&gt;getWebsiteBlock()</code></pre>
	<p>Returns all the wesites in a
	<code>&lt;div&gt;</code> and each website item in a
	<code>&lt;span&gt;</code>.</p>

	<pre><code>$entry-&gt;getBirthdayBlock('FORMAT')</code></pre>
	<p>Returns the birthday date in a
	<code>&lt;span&gt;</code>. The format is optional and
	conforms to the PHP standard, refer to the <a href=
	"http://us2.php.net/date">PHP Manual</a> for the format
	characters.</p>

	<pre><code>$entry-&gt;getAnniversaryBlock('FORMAT')</code></pre>
	<p>Returns the anniversary date in a
	<code>&lt;span&gt;</code>. The format is optional and
	conforms to the PHP standard, refer to the <a href=
	"http://us2.php.net/date">PHP Manual</a> for the format
	characters.</p>
	
	<pre><code>$entry-&gt;getNotesBlock()</code></pre>
	<p>Returns the notes in hCard compatible format wrapped in a <code>&lt;div&gt;</code>.</p>
	
	<pre><code>$entry-&gt;getBioBlock()</code></pre>
	<p>Returns the bio wrapped in a <code>&lt;div&gt;</code>.</p>
	
	<p>The <code>getCategoryBlock()</code> template tag will output the categories that an entry is assigned to. 
	By default as an unordered list. The tag accepts multiple attributes to customize 
	the display of the categories. See the following examples:</p>

	<pre><code>getCategoryBlock( array( 'list' =>  'ordered' ) )</code></pre>
	<p>This will output an ordered list.</p>
	
	<pre><code>getCategoryBlock( array( 'separator' => ', ', 'before' => '<span>', 'after' => '</span>' ) )</code></pre>
	<p>This will output the categories within a span element separated by a comma space.</p>
	
	<p><strong>Accepted Values</strong>
		<ul>
			<li><strong>list:</strong> Accepted values are <em>ordered</em> and <em>unordered</em></li>
			<li><strong>separator:</strong> The string that will be output between the category names.</li>
			<li><strong>before:</strong></li>
			<li><strong>after:</strong></li>
			<li><strong>label:</strong> The label. Default is: "Categories:"</li>
			<li><strong>parents:</strong> Not yet implemented.</li>
			<li><strong>return:</strong> Return the result rather then echo it. Default is FALSE.</li>
		</ul>
	</p>
	
	<br />
	<pre><code>$entry-&gt;getRevisionDateBlock()</code></pre>
	<p>Returns the last revision date in hCard compatible
	format wrapped in a <code>&lt;span&gt;</code>.</p>

	<pre><code>$entry-&gt;getLastUpdatedStyle()</code></pre>
	<p>Returns <code>color: VARIES BY AGE;</code> that can
	be used in then style HTML tag. Example usage:
	<code>&lt;span style="&lt;?php echo
	entry-&gt;getLastUpdatedStyle() ?&gt;"&gt;Updated
	&lt;?php echo entry-&gt;getHumanTimeDiff()
	?&gt;&lt;/span&gt;</code> This will change the color of
	Updated and the timestamp in human difference time
	based on age.</p>

	<pre><code>$entry-&gt;returnToTopAnchor()</code></pre>
	<p>Returns the HTML anchor to return to the top of the entry list using an up arrow graphic.</p>

	<pre><code>$vCard-&gt;download()</code></pre>
	<p>Returns the HTML anchor to allow downloading the
	entry in a vCard that can be imported into your
	preferred email program.</p>
	
	

	<h3><a name="tocIdThemeTags"></a>Theme Template Tags</h3>
	
	<p>If you want to incorporate Connections into your theme template, there are two theme template tags provided. 
	They are <em>connectionsEntryList()</em> which displays the entry list and <em>connectionsUpcomingList()</em> which shows the 
	upcoming list. Both tags support all of the shortcode attributes which are passed as an associative array with the key being 
	the shortcode attribute name and the value being the setting. See the following examples:</p>
 
	<p>This will display the entries where the post categories and entries category names match exactly.</p>
	
	<pre>
	<code>connectionsEntryList( array( 'wp_current_category' => TRUE ) )</code>
	</pre>
	
	 
	<p>This will display the entries where the organization is 'ACME' and department is 'Accounting'</p>
	
	<pre>
	<code>connectionsEntryList( array( 'organization' => 'ACME',  'department' => 'Accounting' ) )</code>
	</pre>
	
	 
	<p>This will show all the birthdays for the next 90 days.</p>
	
	<pre>
	<code>connectionsUpcomingList( array( 'days' => 90 ) )</code>
	</pre>
	
	 
	<p>This will show all the anniversaries for the next 30 days.</p>
	
	<pre>
	<code>connectionsUpcomingList( array( 'list_type' => 'anniversary', 'days' => 30 ) )</code>
	</pre>
	
	<a name="tocIdFilters"></a>
	<fieldset>
		<legend>Filters</legend>
		
		<p>Shortcode:</p>
		<dl>
			<dt>cn_list_atts</dt>
				<dd>Alter the shortcode attributes before use. Return associative array.</dd>
				
			<dt>cn_list_results</dt>
				<dd>Filter the returned results before being processed for display. Return indexed array of entry objects.</dd>
				
			<dt>cn_list_before</dt>
				<dd>Can be used to add content before the output of the list. The entry list
				results are passed. Return string.</dd>
			
			<dt>cn_list_index</dt>
				<dd>Can be used to modify the index before the output of the list. The entry list
				results are passed. Return string.</dd>
		</dl>
		
		
		<p>Data:</p>
		<dl>
			<dt>cn_email_address</dt>
				<dd>applied to each email object</dd>
				
			<dt>cn_email_addresses</dt>
				<dd>applied to indexed array of email objects</dd>
				
			<dt>cn_phone_number</dt>
				<dd>applied to each phone number object</dd>
				
			<dt>cn_phone_numbers</dt>
				<dd>applied to indexed array of phone number objects</dd>
			
			<dt>cn_website</dt>
				<dd>applied to each website object</dd>
				
			<dt>cn_websites</dt>
				<dd>applied to indexed array of website objects</dd>
			
			<dt>cn_excerpt_length</dt>
				<dd>Change the Excerpt length.</dd>
			
			<dt>cn_excerpt_more</dt>
				<dd>Change the default more string of the excerpt.</dd>
				
			<dt>cn_trim_excerpt</dt>
				<dd>Filter the final excerpt string before being returned.</dd>
		</dl>
		
		
		<p>Output:</p>
		<dl>
			<dt>cn_output_email_addresses</dt>
				<dd>applied to the email output string</dd>
		</dl>
		
	</fieldset>
	
	<a name="tocIdSupport"></a>
	<fieldset>
		<legend>Support</legend>
		
		<p>If support is needed go to <a href="http://connections-pro.com/help-desk/">connections-pro.com</a> and submit a help desk ticket.</p>
	</fieldset>
	
	<a name="tocIdFAQ"></a>
	<fieldset>
		<legend>FAQ</legend>
		
		<p>The frequently asked questions cab be found <a href="http://connections-pro.com/faq/">here</a>.</p>
	</fieldset>
	
	<a name="tocIdDisclaimers"></a>
	<fieldset>
		<legend>Disclaimers</legend>
		
		<p>This plugin is developed and tested in Firefox.
		If your using IE and something doesn't work, try it
		again in Firefox.</p>
		
		<p>This plugin is under active developement and 
			as such features and settings could change. You
		may also have to re-enter or edit your entries
		after an upgrade. An effort will be made to keep
		this to a minimum.</p>
		
		<p>It also should be mentioned that I am not a web
		designer nor am I a PHP programmer, this plugin is
		being developed out of a need and for the learning
		experience.</p>
		
	</fieldset>
	
	</div>

<?php
}
?>