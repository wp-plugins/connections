<?php
function connectionsShowHelpPage()
{
?>
	
	<div id="help" class="wrap">
	<div id="icon-connections" class="icon32">
		<br>
	</div>
	
	<h2>Connections : Help</h2>
	
	<div id="toc">
		<ol>
			<li><a href="#tocIdUsageInstructions">Usage Instructions</a></li>
	
			<ol>
				<li><a href="#tocIdConnectionGroups">Connection Groups</a></li>
			</ol>
				
			<li><a href="#tocIdShortCodeInstructions">Shortcode Instructions</a></li>
	
			<ol>
				<li><a href="#tocIdEntryList">Entry list and Options</a></li>
				<li><a href="#tocIdCelebrateList">Celebrate list and Options</a></li>
				<li><a href="#tocIdTemplateTags">Template Tags</a></li>
				<li><a href="#tocIdhCard">hCard Compatible Tags</a></li>
			</ol>
		
			<li><a href="#tocIdSupport">Support</a></li>
				
			<li><a href="#tocIdFAQ">FAQ</a>
	
				<ol>
					<li><a href="#tocIdFAQ001">Upgrading</a></li>
			
					<li><a href="#tocIdFAQ002">Why don't any entries show in my page/post?</a></li>
			
					<li><a href="#tocIdFAQ003">Why don't all individuals show using the list_type option in the shortcode?</a></li>
			
					<li><a href="#tocIdFAQ004">Where are the images I upload for entries stored?</a></li>
			
					<li><a href="#tocIdFAQ005">Where do my custom templates need uploaded to?</a></li>
			
					<li><a href="#tocIdFAQ006">Error upon activation?</a></li>
			
					<li><a href="#tocIdFAQ007">Why do dotted underlines show under the dates?</a></li>
					
				</ol>
			</li>
				
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
		
		<p><a name="tocIdConnectionGroups"></a>Here is an example on how to use the Connection Groups entry type.</p>
		
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
		
		<p>Now create a new entry using the Connection Group type. For the Connection Group name enter something like "Doe, John &amp; Jane" 
		and then click the "Add Connection" button for each member in the family, in this example, four times. 
		In the first column of drop downs select each family member and in the second column of 
		drop downs select their relationships.</p>
		
		<p>Finally, just add a the following attribute to the shortcode like such; [connections_list list_type='connection_group']</p>
		
	<h3><a name="tocIdShortCodeInstructions"></a>Shortcode Instructions:</h3>
	
	<p><a name="tocIdEntryList"></a>To embed an entry list in a page/post just enter the
	shortcode text and any of the options outlined below in
	the page/post content area.</p>
	<pre>
	<code>[connections_list]</code>
	</pre>
	
	<p>This shortcode has several many options:</p>
	
	<ol>
		<li><a href="#cn_attr_id">id</a></li>
			
		<li><a href="#cn_attr_category">category</a></li>
	
		<li><a href="#cn_attr_private_override">private_override</a></li>
			
		<li><a href="#cn_attr_public_override">public_override</a></li>
	
		<li><a href="#cn_attr_show_alphaindex">show_alphaindex</a></li>
			
		<li><a href="#cn_attr_repeat_alphaindex">repeat_alphaindex</a></li>
			
		<li><a href="#cn_attr_show_alphahead">show_alphahead</a></li>
	
		<li><a href="#cn_attr_list_type">list_type</a></li>
	
		<li><a href="#cn_attr_custom_template">custom_template</a></li>
	
		<li><a href="#cn_attr_template_name">template_name</a></li>
		
		<li><a href="#cn_attr_order_by">order_by</a> <strong><em>sort</em></strong></li>
	
		<li><a href="#cn_attr_filters">last_name</a> <strong><em>filter</em></strong></li>
	
		<li>group_name <strong><em>filter</em></strong></li>
		
		<li>title <strong><em>filter</em></strong></li>
	
		<li>organization <strong><em>filter</em></strong></li>
	
		<li>department <strong><em>filter</em></strong></li>
			
		<li>city <strong><em>filter</em></strong></li>
		
		<li>state <strong><em>filter</em></strong></li>
		
		<li>zip_code <strong><em>filter</em></strong></li>
		
		<li>country <strong><em>filter</em></strong></li>
	</ol>
	
	<p><a name="cn_attr_id"></a>The <em>id</em> option allows you to show the
	entry details for a single entry or multiple specific entries.
	To show multiple specific entries, list all the entries to be displayed
	by their <em>id</em> number separated by commas within single quotes. Default is to show all
	public and/or private entries in the list. The ID can
	be found in the admin by showing the details for an
	entry. It will be labeled <strong>Entry ID:</strong></p>

	<pre>
	<code>[connections_list id=2]</code>
	</pre>
	
	<pre>
	<code>[connections_list id='2,12,37']</code>
	</pre>
	
	<p><a name="cn_attr_category"></a>The <em>category</em> option allows you to show entries within a specific category
	and all children categories. The category ID can be found on the Category page in the admin. 
	the default is to show all available categories.</p>
	
	<pre>
	<code>[connections_list category=12]</code>
	</pre>
	
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
	entries or you can choose to show only individuals,
	organizations and connection groups.</p>
	
	<p>Use to show all entry types.</p>
	<pre>
	<code>[connections_list list_type='all']</code>
	</pre>
	
	
	<p>Use to show only entries set as an individual.</p>
	<pre>
	<code>[connections_list list_type='individual']</code>
	</pre>
	
	
	<p>Use to show only entries set as an organization.</p>
	<pre>
	<code>[connections_list list_type='organization']</code>
	</pre>
	
	
	<p>Use to show only entries set as a Connection Group.</p>
	<pre>
	<code>[connections_list list_type='connection_group']</code>
	</pre>
	
	

	<p>Two alternate template have been provieded. Use the <code>template_name</code>
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
	
	

	<p><a name="cn_attr_custom_template"></a><a name="cn_attr_template_name"></a>If you create a custom template you need to set two
	options <code>custom_template</code> and
	<code>template_name</code> as such. For example, say
	you create a custom template named my-template.php. The
	template name you would enter in the option would be
	"my-template", dropping off the ".php".</p>

	<pre>
	<code>[connections_list custom_template='true' template_name='the_template_name']</code>
	</pre>
	
	<p>Both of these must be set in order to use a custom
	template and the custom template must be saved in the
	<code>./wp-content/connections_templates</code>
	directory/folder.</p>
	
	<p><a name="cn_attr_order_by"></a>The <em>order_by</em> attribute can be used to apply a custom sort to the list.
	Here are the available sort fields and attributes.</p>
	<p><strong>Sort fields:</strong>
		<ul>
			<li>first_name</li>
			<li>last_name</li>
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
		</ul>
	</p>
	<p><strong>Sort Types:</strong>
		<ul>
			<li>SORT_REGULAR</li>
			<li>SORT_NUMERIC</li>
			<li>SORT_STRING</li>
		</ul>
	</p>
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
	
	<p><a name="cn_attr_filters"></a>The filter attributes can be used one at a time per
	list or in combinations per list and are case
	sensitive. See the examples below.</p>

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
	search all addresses and will disply the entry if a match is found.</p>
	
	<p><strong>NOTE:</strong> If you
	want to filter names with with apostrophes quote the filter using the double quote.</p>
	
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

	<pre>
	<code>&lt;?php echo $entry-&gt;getId(); ?&gt;</code>
	</pre>
	
	<p>Example of a template tag that return the entry's
	ID.</p>

	<pre>
	<code>$entry-&gt;getId()</code>
	</pre>
	
	<p>Returns the ID.</p>

	<pre>
	<code>$entry-&gt;getFormattedTimeStamp('FORMAT')</code>
	</pre>
	
	<p>Returns the last updated time. The format is
	optional and conforms to the PHP standard, refer to the
	<a href="http://us2.php.net/date">PHP Manual</a> for
	the format characters.</p>

	<pre>
	<code>$entry-&gt;getUnixTimeStamp()</code>
	</pre>
	
	<p>Returns the last updated time in raw unix time
	format.</p>

	<pre>
	<code>$entry-&gt;getHumanTimeDiff()</code>
	</pre>
	
	<p>Returns the last updated time using human time
	difference.</p>

	<pre>
	<code>$entry-&gt;getFirstName()</code>
	</pre>
	
	<p>Returns the first name.</p>

	<pre>
	<code>$entry-&gt;getLastName()</code>
	</pre>
	
	<p>Returns the last name.</p>
	<pre>

	<code>$entry-&gt;getFullFirstLastName()</code>
	</pre>
	
	<p>Retuns the full name with the first name first.
	NOTE: if the entry type in an Organization or Connection Group 
	this will return the Organization/Connection Group name instead.</p>

	<pre>
	<code>$entry-&gt;getFullLastFirstName()</code>
	</pre>
	
	<p>Retuns the full name with the last name first. NOTE:
	if the entry type in an Organization or Connection Group
	this will return the Organization/Connection Group name instead.</p>
	
	<pre>
	<code>$entry-&gt;getGroupName()</code>
	</pre>
	
	<p>Returns the Connection Group name.</p>
	
	<pre>
	<code>$entry-&gt;getConnectionGroup()</code>
	</pre>
	
	<p>Returns an associative array containing the relation's entry id 
	as the key and the relation</p>
	
	<pre>
	<code>$entry-&gt;getOrganization()</code>
	</pre>
	
	<p>Returns the organization.</p>

	<pre>
	<code>$entry-&gt;getTitle()</code>
	</pre>
	
	<p>Returns the title.</p>

	<pre>
	<code>$entry-&gt;getDepartment()</code>
	</pre>
	
	<p>Returns the department.</p>

	<pre>
	<code>$entry-&gt;getAddresses()</code>
	</pre>
	
	<p>Returns an associative array containing all the addresses.</p>

	<pre>
	<code>$entry-&gt;getPhoneNumbers()</code>
	</pre>
	
	<p>Returns an associative array containing all the
	phone numbers.</p>

	<pre>
	<code>$entry-&gt;getEmailAddresses()</code>
	</pre>
	
	<p>Returns an associative array containing all the
	email addresses.</p>

	<pre>
	<code>$entry-&gt;getIm()</code>
	</pre>
	
	<p>Returns an associative array containing all the IM ID's.</p>

	<pre>
	<code>$entry-&gt;getWebsites()</code>
	</pre>
	
	<p>Returns an associative array containing all the websites.</p>

	<pre>
	<code>$entry-&gt;getAnniversary('FORMAT')</code>
	</pre>
	
	<p>Returns the anniversary date for the entry. The
	format is optional and conforms to the PHP standard,
	refer to the <a href="http://us2.php.net/date">PHP
	Manual</a> for the format characters.</p>

	<pre>
	<code>$entry-&gt;getBirthday('FORMAT')</code>
	</pre>
	
	<p>Returns the birthday date for the entry. The format
	is optional and conforms to the PHP standard, refer to
	the <a href="http://us2.php.net/date">PHP Manual</a>
	for the format characters.</p>

	<pre>
	<code>$entry-&gt;getBio()</code>
	</pre>
	
	<p>Returns the biography.</p>

	<pre>
	<code>$entry-&gt;getNotes()</code>
	</pre>
	
	<p>Returns the notes.</p>

	<h4><a name="tocIdhCard"></a>These tags return some
	preformatted HTML blocks and should be used to maintain
	hCard compatibility.</h4>

	<pre>
	<code>$entry-&gt;getThumbnailImage()</code>
	</pre>
	
	<p>Returns the thumbnail image.</p>

	<pre>
	<code>$entry-&gt;getCardImage()</code>
	</pre>
	
	<p>Returns the card image.</p>

	<pre>
	<code>$entry-&gt;getProfileImage()</code>
	</pre>
	
	<p>Returns the profile image.</p>

	<pre>
	<code>$entry-&gt;getFullFirstLastNameBlock()</code>
	</pre>
	
	<p>Returns the full name with the first name first.
	NOTE: if the entry type in an Organization or a Connection Group
	this will return the Organization/Connection Group name instead.</p>

	<pre>
	<code>$entry-&gt;getFullLastFirstNameBlock()</code>
	</pre>
	
	<p>Returns the full name with the last name first. NOTE:
	if the entry type in an Organization or a Connection Group
	this will return the Organization/Connection Group name instead.</p>
	
	<pre>
	<code>$entry-&gt;getConnectionGroupBlock()</code>
	</pre>
	
	<p>Returns the Connection Group items in a <code>&lt;span&gt;</code> tag followed by a <code>&lt;br&gt;</code>.</p>
	
	<pre>
	<code>$entry-&gt;getTitleBlock()</code>
	</pre>
	
	<p>Returns the title in a <code>&lt;span&gt;</code>tag.</p>

	<pre>
	<code>$entry-&gt;getOrgUnitBlock()</code>
	</pre>
	
	<p>Returns the organization ** AND ** the department in
	a <code>&lt;div&gt;</code> tag with each wrapped in a
	span. NOTE: this will only output the organization if
	the entry type is not an organization, but will still
	output the department if applicable. To get the
	organization name, use one of the full name template
	tags.</p>

	<pre>
	<code>$entry-&gt;getOrganizationBlock()</code>
	</pre>
	
	<p>Returns the organization in a
	<code>&lt;span&gt;</code>. If the entry type is an
	organization, this tag will not output any HTML. You
	should use one of the full name tags to get the
	organization name.</p>

	<pre>
	<code>$entry-&gt;getDepartmentBlock()</code>
	</pre>
	
	<p>Returns the department in a <code>&lt;span&gt;</code>.</p>

	<pre>
	<code>$entry-&gt;getAddressBlock()</code>
	</pre>
	
	<p>Returns all the addresses in a
	<code>&lt;div&gt;</code> and each address item in a
	<code>&lt;span&gt;</code>. NOTE: in order for proper
	hCard support the address must have a type assign;
	either work or home. If none is set, the entry type
	will be used to set the address type as either home or
	work.</p>

	<pre>
	<code>$entry-&gt;getPhoneNumberBlock()</code>
	</pre>
	
	<p>Returns all the phone numbers in a
	<code>&lt;div&gt;</code> and each phone number item in
	a <code>&lt;span&gt;</code>.</p>

	<pre>
	<code>$entry-&gt;getEmailAddressBlock()</code>
	</pre>
	
	<p>Returns all the email addresses in a
	<code>&lt;div&gt;</code> and each email address item in
	a <code>&lt;span&gt;</code>.</p>

	<pre>
	<code>$entry-&gt;getImBlock()</code>
	</pre>
	
	<p>Returns all the IM ID's in a
	<code>&lt;div&gt;</code> and each IM item in a
	<code>&lt;span&gt;</code>.</p>

	<pre>
	<code>$entry-&gt;getWebsiteBlock()</code>
	</pre>
	
	<p>Returns all the wesites in a
	<code>&lt;div&gt;</code> and each website item in a
	<code>&lt;span&gt;</code>.</p>

	<pre>
	<code>$entry-&gt;getBirthdayBlock('FORMAT')</code>
	</pre>
	
	<p>Returns the birthday date in a
	<code>&lt;span&gt;</code>. The format is optional and
	conforms to the PHP standard, refer to the <a href=
	"http://us2.php.net/date">PHP Manual</a> for the format
	characters.</p>

	<pre>
	<code>$entry-&gt;getAnniversaryBlock('FORMAT')</code>
	</pre>
	
	<p>Returns the anniversary date in a
	<code>&lt;span&gt;</code>. The format is optional and
	conforms to the PHP standard, refer to the <a href=
	"http://us2.php.net/date">PHP Manual</a> for the format
	characters.</p>
	
	<pre>
	<code>$entry-&gt;getNotesBlock()</code>
	</pre>
	
	<p>Returns the notes in hCard compatible format wrapped in a <code>&lt;div&gt;</code>.</p>
	
	<pre>
	<code>$entry-&gt;getBioBlock()</code>
	</pre>
	
	<p>Returns the notes in	hCard compatible format wrapped in a <code>&lt;div&gt;</code>.</p>

	<pre>
	<code>$entry-&gt;getRevisionDateBlock()</code>
	</pre>
	
	<p>Returns the last revision date in hCard compatible
	format wrapped in a <code>&lt;span&gt;</code>.</p>

	<pre>
	<code>$entry-&gt;getLastUpdatedStyle()</code>
	</pre>
	
	<p>Returns <code>color: VARIES BY AGE;</code> that can
	be used in then style HTML tag. Example usage:
	<code>&lt;span style="&lt;?php echo
	entry-&gt;getLastUpdatedStyle() ?&gt;"&gt;Updated
	&lt;?php echo entry-&gt;getHumanTimeDiff()
	?&gt;&lt;/span&gt;</code> This will change the color of
	Updated and the timestamp in human difference time
	based on age.</p>

	<pre>
	<code>$entry-&gt;returnToTopAnchor()</code>
	</pre>
	
	<p>Returns the HTML anchor to return to the top of the entry list using an up arrow graphic.</p>

	<pre>
	<code>$vCard-&gt;download()</code>
	</pre>
	
	<p>Returns the HTML anchor to allow downloading the
	entry in a vCard that can be imported into your
	preferred email program.</p>
	
	
	<h3><a name="tocIdSupport"></a>Support</h3>
	
	<p>If support is needed go to <a href="http://www.connections-pro.com">connections-pro.com</a> and submit a help desk ticket.</p>
	
	
	<h3><a name="tocIdFAQ"></a>FAQ</h3>
	
	<h4><a name="tocIdFAQ001"></a>Upgrading</h4>
	
	<p>Use the automatic plug-in upgrade feature built into WordPress. Optionally, the plugin can be downloaded
	from the WordPress plug-in repository and manually uploaded via FTP. After the plug-in has been uploaded
	make sure to activate and deactive the plug-in.</p>
	
	<h4><a name="tocIdFAQ002"></a>Why don't any entries
	show in my page/post when I use the connections_list
	shortcode?</h4>

	<p>When adding a new entry in the entry list you have
	the option of choosing public, private or unlisted.
	This affects when a listing will be shown when embedded
	in a page/post. If an entry if public, that entry will
	show at all times. If an entry is private, that listing
	will only be shown when a registered user is logged
	into your site. By choosing unlisted, that entry will
	not be shown when embedded in a page/post and is only
	visible in the admin.</p>
	
	<h4><a name="tocIdFAQ003"></a>Why don't all
	individuals show when I use the list_type option in the
	shortcode?</h4>
	
	<p>Older versions of this plugin didn't set this
	property to an entry. To fix; edit all entries that
	should appear in the list by selecting the appropiate
	type and then save the entry.</p>
	
	<h4><a name="tocIdFAQ004"></a>Where are the images I
	upload for entries stored?</h4>
	
	<p>The images are unloaded to you wp-content folder.
	The path is ../wp-content/connection_images/</p>
	
	<h4><a name="tocIdFAQ005"></a>Where do my custom
	templates need uploaded to?</h4>
	
	<p>You need to create a folder in you wp-content folder
	named: connections_templates.</p>
	
	<h4><a name="tocIdFAQ006"></a>I get this error upon
	activation - "Parse error: syntax error, unexpected
	T_STRING, expecting T_OLD_FUNCTION or T_FUNCTION or
	T_VAR or '}' in *YOUR WP PATH*
	/wp-content/plugins/connections/includes/date.php on
	line 11", what is this?</h4>
	
	<p>This plugin requires PHP 5. Turn on or ask your web
	host to turn on PHP 5 support. <strong>OR</strong>
	because the .php extension defaults to PHP 4 on your
	web server. The easiest fix is to add a handler that
	maps .php to PHP 5. If you have cPanel, you can do this
	easily by clicking on "Apache Handlers" and adding a
	mapping for "php" to "application/x-httpd-php5" and
	that should fix the problem.</p>
	
	<h4><a name="tocIdFAQ007"></a>Why do dotted underlines
	show under the dates?</h4>
	
	<p>Some browsers put a dotted underline or border on
	each <code>&lt;abbr&gt;</code> tag. The
	<code>&lt;abbr&gt;</code> tag is needed for hCalendar
	event compatibility. To remove this from the styling,
	add <code>.vevent abbr{border:0}</code> to your theme's
	CSS.</p>
		
	<h3><a name="tocIdDisclaimers"></a>Disclaimers</h3>
	
	<ul>
	<li>This plugin is developed and tested in Firefox.
	If your using IE and something doesn't work, try it
	again in Firefox.</li>
	
	<li>This plugin is under active developement and 
		as such features and settings could change. You
	may also have to re-enter or edit your entries
	after an upgrade. An effort will be made to keep
	this to a minimum.</li>
	
	<li>It also should be mentioned that I am not a web
	designer nor am I a PHP programmer, this plugin is
	being developed out of a need and for the learning
	experience.</li>
	</ul>
	</div>

<?php
}
?>