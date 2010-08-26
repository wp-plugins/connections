/**
 * @author Steven A. Zahm
 */

// Use jQuery() instead of $()for WordPress compatibility with the included prototype js library which uses $()
// http://ipaulpro.com/blog/tutorials/2008/08/jquery-and-wordpress-getting-started/
// See http://chrismeller.com/using-jquery-in-wordpress
jQuery(document).ready(function(){
	//jQuery('.connections tr:even').addClass('alternate');

	jQuery(function()
	{
		jQuery('a.detailsbutton')
			.css("cursor","pointer")
			.attr("title","Click to show details.")
			.click(function()
			{
				jQuery('.child-'+this.id).each(function(i, elem)
				{
					jQuery(elem).toggle(jQuery(elem).css('display') == 'none');
				});

				return false;		
			})
			.toggle
			(
				function() 
				{
					jQuery(this).html('Hide Details');
					jQuery(this).attr("title","Click to hide details.")
				},
				
				function() 
				{
					jQuery(this).html('Show Details');
					jQuery(this).attr("title","Click to show details.")
				}
			);
		//jQuery('tr[@class^=child-]').hide().children('td');
		return false;
	});
	
	
	jQuery(function() {
		jQuery('input#entry_type_0')
			.click(function(){
				jQuery('#connection_group').slideUp();
				jQuery('.namefield').slideDown();
				jQuery('#contact_name').slideUp();
				//jQuery('.home').slideDown();
				//jQuery('.homephone').slideDown();
				//jQuery('.homefax').slideDown();
				//jQuery('.personal').slideDown();
				//jQuery('.cell').slideDown();
				//jQuery('.cellphone').slideDown();
				//jQuery('.im').slideDown();
				jQuery('.celebrate').slideDown();
			});
	});
	
	jQuery(function() {
		jQuery('input#entry_type_1')
			.click(function(){
				jQuery('#connection_group').slideUp();
				jQuery('.namefield').slideUp();
				jQuery('#contact_name').slideDown();
				//jQuery('.home').slideUp();
				//jQuery('.homephone').slideUp();
				//jQuery('.homefax').slideUp();
				//jQuery('.personal').slideUp();
				//jQuery('.cell').slideUp();
				//jQuery('.cellphone').slideUp();
				//jQuery('.im').slideUp();
				jQuery('.celebrate').slideUp();
			});
	});
	
	jQuery(function() {
		jQuery('input#entry_type_2')
			.click(function(){
				jQuery('#connection_group').slideDown();
				jQuery('.namefield').slideUp();
				//jQuery('.home').slideDown();
				//jQuery('.homephone').slideDown();
				//jQuery('.homefax').slideDown();
				//jQuery('.personal').slideDown();
				//jQuery('.cell').slideDown();
				//jQuery('.cellphone').slideDown();
				//jQuery('.im').slideDown();
				jQuery('.celebrate').slideUp();
			});
	});
	
	
	jQuery(function() {
		var $entryType = (jQuery('input[@name=entry_type]:checked').val());
		
		switch ($entryType)
		{
			case 'individual':
				jQuery('#connection_group').slideUp();
				jQuery('#contact_name').slideUp();
				break;
			
			case 'organization':
				jQuery('#connection_group').slideUp();
				jQuery('.namefield').slideUp();
				//jQuery('.home').slideUp();
				//jQuery('.homephone').slideUp();
				//jQuery('.homefax').slideUp();
				//jQuery('.personal').slideUp();
				//jQuery('.cell').slideUp();
				//jQuery('.cellphone').slideUp();
				//jQuery('.im').slideUp();
				jQuery('.celebrate').slideUp();
				break;
			
			case 'connection_group':
				jQuery('.namefield').slideUp();
				jQuery('.celebrate').slideUp();
				break;
		}
	
	});
	
	jQuery(function() {
		var intCount = 0;
		//var jRelations = (jQuery('#relation_row_base').html());
		
		jQuery('#add_relation')
			.click(function() {
				var jRelations = (jQuery('#relation_row_base').text());
				
				jRelations = jRelations.replace(
					new RegExp('::FIELD::', 'gi'),
					intCount
					);
				
				jQuery('#relations').append( '<div id="relation_row_' + intCount + '" class="relation_row">' + jRelations + '<a href="#" id="remove_button_' + intCount + '" ' + 'class="button button-warning" onClick="removeEntryRow(\'#relation_row_' + intCount + '\'); return false;">Remove</a>' + '</div>' );
				
				intCount++;
			});
	});
	
	jQuery(function() {
		var intCount = 0;
		//var jRelations = (jQuery('#social_media_row_base').html());
		
		jQuery('#add_social_media')
			.click(function() {
				var jRelations = (jQuery('#social_media_row_base').text());
				
				jRelations = jRelations.replace(
					new RegExp('::FIELD::', 'gi'),
					intCount
					);
				
				//jQuery('#social_media').append( '<div id="social_media_row_' + intCount + '" class="social_media_row">' + jRelations + '<a href="#" id="remove_button_' + intCount + '" ' + 'class="button button-warning" onClick="removeEntryRow(\'#social_media_row_' + intCount + '\'); return false;">Remove</a>' + '</div>' );
				jQuery('#social_media').append( '<div id="social_media_row_' + intCount + '" class="social_media_row">' + jRelations + '</div>' );
				
				intCount++;
			});
	});
	
	jQuery(function() {
		var intCount = 0;
		//var jRelations = (jQuery('#address_row_base').html());
		
		jQuery('#add_address')
			.click(function() {
				var jRelations = (jQuery('#address_row_base').text());
				
				jRelations = jRelations.replace(
					new RegExp('::FIELD::', 'gi'),
					intCount
					);
				
				//jQuery('#addresses').append( '<div id="address_row_' + intCount + '" class="address_row">' + jRelations + '<br /><a href="#" id="remove_button_' + intCount + '" ' + 'class="button button-warning" onClick="removeEntryRow(\'#address_row_' + intCount + '\'); return false;">Remove</a>' + '</div>' );
				jQuery('#addresses').append( '<div id="address_row_' + intCount + '" class="address_row">' + jRelations + '</div>' );
				
				intCount++;
			});
	});
	
	jQuery(function() {
		var intCount = 0;
		//var jRelations = (jQuery('#phone_number_row_base').html());
		
		jQuery('#add_phone_number')
			.click(function() {
				var jRelations = (jQuery('#phone_number_row_base').text());
				
				jRelations = jRelations.replace(
					new RegExp('::FIELD::', 'gi'),
					intCount
					);
				
				//jQuery('#phone_numbers').append( '<div id="phone_number_row_' + intCount + '" class="phone_number_row">' + jRelations + '<a href="#" id="remove_button_' + intCount + '" ' + 'class="button button-warning" onClick="removeEntryRow(\'#phone_number_row_' + intCount + '\'); return false;">Remove</a>' + '</div>' );
				jQuery('#phone_numbers').append( '<div id="phone_number_row_' + intCount + '" class="phone_number_row">' + jRelations  + '</div>' );
				
				intCount++;
			});
	});
	
	jQuery(function() {
		var intCount = 0;
		//var jRelations = (jQuery('#email_address_row_base').html());
		
		jQuery('#add_email_address')
			.click(function() {
				var jRelations = (jQuery('#email_address_row_base').text());
				
				jRelations = jRelations.replace(
					new RegExp('::FIELD::', 'gi'),
					intCount
					);
				
				//jQuery('#email_addresses').append( '<div id="email_address_row_' + intCount + '" class="email_address_row">' + jRelations + '<a href="#" id="remove_button_' + intCount + '" ' + 'class="button button-warning" onClick="removeEntryRow(\'#email_address_row_' + intCount + '\'); return false;">Remove</a>' + '</div>' );
				jQuery('#email_addresses').append( '<div id="email_address_row_' + intCount + '" class="email_address_row">' + jRelations + '</div>' );
				
				intCount++;
			});
	});
	
	jQuery(function() {
		var intCount = 0;
		//var jRelations = (jQuery('#website_address_row_base').html());
		
		jQuery('#add_website_address')
			.click(function() {
				var jRelations = (jQuery('#website_address_row_base').text());
				
				jRelations = jRelations.replace(
					new RegExp('::FIELD::', 'gi'),
					intCount
					);
				
				//jQuery('#website_addresses').append( '<div id="website_address_row_' + intCount + '" class="website_address_row">' + jRelations + '<a href="#" id="remove_button_' + intCount + '" ' + 'class="button button-warning" onClick="removeEntryRow(\'#website_address_row_' + intCount + '\'); return false;">Remove</a>' + '</div>' );
				jQuery('#website_addresses').append( '<div id="website_address_row_' + intCount + '" class="website_address_row">' + jRelations + '</div>' );
				
				intCount++;
			});
	});
	
	jQuery(function() {
		var intCount = 0;
		//var jRelations = (jQuery('#im_row_base').html());
		
		jQuery('#add_im_id')
			.click(function() {
				var jRelations = (jQuery('#im_row_base').text());
				
				jRelations = jRelations.replace(
					new RegExp('::FIELD::', 'gi'),
					intCount
					);
				
				//jQuery('#im_ids').append( '<div id="im_row_' + intCount + '" class="im_row">' + jRelations + '<a href="#" id="remove_button_' + intCount + '" ' + 'class="button button-warning" onClick="removeEntryRow(\'#im_row_' + intCount + '\'); return false;">Remove</a>' + '</div>' );
				jQuery('#im_ids').append( '<div id="im_row_' + intCount + '" class="im_row">' + jRelations + '</div>' );
				
				intCount++;
			});
	});

	/*jQuery(function()
	{
		jQuery('#bio_wysiwyg').wysiwyg(
		{
			controls : {
				strikeThrough : { visible : false },
				underline     : { visible : false },
				
				separator00 : { visible : false },
				
				justifyLeft   : { visible : false },
				justifyCenter : { visible : false },
				justifyRight  : { visible : false },
				justifyFull   : { visible : false },
				
				separator01 : { visible : false },
				
				indent  : { visible : false },
				outdent : { visible : false },
				
				separator02 : { visible : false },
				
				subscript   : { visible : false },
				superscript : { visible : false },
				
				separator03 : { visible : false },
				
				undo : { visible : false },
				redo : { visible : false },
				
				separator04 : { visible : false },
				
				insertOrderedList    : { visible : false },
				insertUnorderedList  : { visible : false },
				insertHorizontalRule : { visible : false },
				
				insertImage : { visible : false },
				insertTable : { visible : false },
				
				separator06 : { visible : false },
				
				h1mozilla : { visible : false && $.browser.mozilla, className : 'h1', command : 'heading', arguments : ['h1'], tags : ['h1'], tooltip : "Header 1" },
				h2mozilla : { visible : false && $.browser.mozilla, className : 'h2', command : 'heading', arguments : ['h2'], tags : ['h2'], tooltip : "Header 2" },
				h3mozilla : { visible : false && $.browser.mozilla, className : 'h3', command : 'heading', arguments : ['h3'], tags : ['h3'], tooltip : "Header 3" },
				h4mozilla : { visible : false && $.browser.mozilla, className : 'h4', command : 'heading', arguments : ['h4'], tags : ['h4'], tooltip : "Header 4" },
				h5mozilla : { visible : false && $.browser.mozilla, className : 'h5', command : 'heading', arguments : ['h5'], tags : ['h5'], tooltip : "Header 5" },
				h6mozilla : { visible : false && $.browser.mozilla, className : 'h6', command : 'heading', arguments : ['h6'], tags : ['h6'], tooltip : "Header 6" },
				
				h1 : { visible : false && !( $.browser.mozilla ), className : 'h1', command : 'formatBlock', arguments : ['<H1>'], tags : ['h1'], tooltip : "Header 1" },
				h2 : { visible : false && !( $.browser.mozilla ), className : 'h2', command : 'formatBlock', arguments : ['<H2>'], tags : ['h2'], tooltip : "Header 2" },
				h3 : { visible : false && !( $.browser.mozilla ), className : 'h3', command : 'formatBlock', arguments : ['<H3>'], tags : ['h3'], tooltip : "Header 3" },
				h4 : { visible : false && !( $.browser.mozilla ), className : 'h4', command : 'formatBlock', arguments : ['<H4>'], tags : ['h4'], tooltip : "Header 4" },
				h5 : { visible : false && !( $.browser.mozilla ), className : 'h5', command : 'formatBlock', arguments : ['<H5>'], tags : ['h5'], tooltip : "Header 5" },
				h6 : { visible : false && !( $.browser.mozilla ), className : 'h6', command : 'formatBlock', arguments : ['<H6>'], tags : ['h6'], tooltip : "Header 6" },
				
				separator07 : { visible : false },
				
				cut   : { visible : false },
				copy  : { visible : false },
				paste : { visible : false }
			}
		});
	});
	
	jQuery(function()
	{
		jQuery('#note_wysiwyg').wysiwyg(
		{
			controls : {
				strikeThrough : { visible : false },
				underline     : { visible : false },
				
				separator00 : { visible : false },
				
				justifyLeft   : { visible : false },
				justifyCenter : { visible : false },
				justifyRight  : { visible : false },
				justifyFull   : { visible : false },
				
				separator01 : { visible : false },
				
				indent  : { visible : false },
				outdent : { visible : false },
				
				separator02 : { visible : false },
				
				subscript   : { visible : false },
				superscript : { visible : false },
				
				separator03 : { visible : false },
				
				undo : { visible : false },
				redo : { visible : false },
				
				separator04 : { visible : false },
				
				insertOrderedList    : { visible : false },
				insertUnorderedList  : { visible : false },
				insertHorizontalRule : { visible : false },
				
				insertImage : { visible : false },
				insertTable : { visible : false },
				
				separator06 : { visible : false },
				
				h1mozilla : { visible : false && $.browser.mozilla, className : 'h1', command : 'heading', arguments : ['h1'], tags : ['h1'], tooltip : "Header 1" },
				h2mozilla : { visible : false && $.browser.mozilla, className : 'h2', command : 'heading', arguments : ['h2'], tags : ['h2'], tooltip : "Header 2" },
				h3mozilla : { visible : false && $.browser.mozilla, className : 'h3', command : 'heading', arguments : ['h3'], tags : ['h3'], tooltip : "Header 3" },
				h4mozilla : { visible : false && $.browser.mozilla, className : 'h4', command : 'heading', arguments : ['h4'], tags : ['h4'], tooltip : "Header 4" },
				h5mozilla : { visible : false && $.browser.mozilla, className : 'h5', command : 'heading', arguments : ['h5'], tags : ['h5'], tooltip : "Header 5" },
				h6mozilla : { visible : false && $.browser.mozilla, className : 'h6', command : 'heading', arguments : ['h6'], tags : ['h6'], tooltip : "Header 6" },
				
				h1 : { visible : false && !( $.browser.mozilla ), className : 'h1', command : 'formatBlock', arguments : ['<H1>'], tags : ['h1'], tooltip : "Header 1" },
				h2 : { visible : false && !( $.browser.mozilla ), className : 'h2', command : 'formatBlock', arguments : ['<H2>'], tags : ['h2'], tooltip : "Header 2" },
				h3 : { visible : false && !( $.browser.mozilla ), className : 'h3', command : 'formatBlock', arguments : ['<H3>'], tags : ['h3'], tooltip : "Header 3" },
				h4 : { visible : false && !( $.browser.mozilla ), className : 'h4', command : 'formatBlock', arguments : ['<H4>'], tags : ['h4'], tooltip : "Header 4" },
				h5 : { visible : false && !( $.browser.mozilla ), className : 'h5', command : 'formatBlock', arguments : ['<H5>'], tags : ['h5'], tooltip : "Header 5" },
				h6 : { visible : false && !( $.browser.mozilla ), className : 'h6', command : 'formatBlock', arguments : ['<H6>'], tags : ['h6'], tooltip : "Header 6" },
				
				separator07 : { visible : false },
				
				cut   : { visible : false },
				copy  : { visible : false },
				paste : { visible : false }
			}
		});
	});*/

});

function removeEntryRow(id)
	{
		jQuery(id).remove();
		//jQuery(id).slideUp('slow', function() {jQuery(id).remove});
	}