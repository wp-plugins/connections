/**
 * @author Steven A. Zahm
 */

// Use jQuery() instead of $()for WordPress compatibility with the included prototype js library which uses $()
// http://ipaulpro.com/blog/tutorials/2008/08/jquery-and-wordpress-getting-started/
// See http://chrismeller.com/using-jquery-in-wordpress
jQuery(document).ready(function(){
	jQuery('.connections tr:even').addClass('alternate');

	jQuery(function()
	{
		jQuery('a.detailsbutton')
			.css("cursor","pointer")
			.attr("title","Click to show details.")
			.click(function()
			{
				jQuery('.child-'+this.id).toggle();			
			})
			.toggle
			(
				function() 
				{
					jQuery(this).html('Hide Details');
				},
				
				function() 
				{
					jQuery(this).html('Show Details');
				}
			);
		//jQuery('tr[@class^=child-]').hide().children('td');
		return false;
	});
	
	
	jQuery(function() {
		jQuery('input#entry_type0')
			.click(function(){
				jQuery('#connection_group').slideUp();
				jQuery('.namefield').slideDown();
				jQuery('.home').slideDown();
				jQuery('.homephone').slideDown();
				jQuery('.homefax').slideDown();
				jQuery('.personal').slideDown();
				jQuery('.cell').slideDown();
				jQuery('.cellphone').slideDown();
				jQuery('.im').slideDown();
				jQuery('.celebrate').slideDown();
			});
	});
	
	jQuery(function() {
		jQuery('input#entry_type1')
			.click(function(){
				jQuery('#connection_group').slideUp();
				jQuery('.namefield').slideUp();
				jQuery('.home').slideUp();
				jQuery('.homephone').slideUp();
				jQuery('.homefax').slideUp();
				jQuery('.personal').slideUp();
				jQuery('.cell').slideUp();
				jQuery('.cellphone').slideUp();
				jQuery('.im').slideUp();
				jQuery('.celebrate').slideUp();
			});
	});
	
	jQuery(function() {
		jQuery('input#entry_type2')
			.click(function(){
				jQuery('#connection_group').slideDown();
				jQuery('.namefield').slideUp();
				jQuery('.home').slideDown();
				jQuery('.homephone').slideDown();
				jQuery('.homefax').slideDown();
				jQuery('.personal').slideDown();
				jQuery('.cell').slideDown();
				jQuery('.cellphone').slideDown();
				jQuery('.im').slideDown();
				jQuery('.celebrate').slideUp();
			});
	});
	
	
	jQuery(function() {
		var $entryType = (jQuery('input[@name=entry_type]:checked').val());
		
		switch ($entryType)
		{
			case 'individual':
				jQuery('#connection_group').slideUp();
				break;
			
			case 'organization':
				jQuery('#connection_group').slideUp();
				jQuery('.namefield').slideUp();
				jQuery('.home').slideUp();
				jQuery('.homephone').slideUp();
				jQuery('.homefax').slideUp();
				jQuery('.personal').slideUp();
				jQuery('.cell').slideUp();
				jQuery('.cellphone').slideUp();
				jQuery('.im').slideUp();
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
		var jRelations = (jQuery('#relation_row_base').html());
		
		jQuery('#add_button')
			.click(function() {
				intCount++;
				
				jQuery('#relations').append( '<div id="relation_row_' + intCount + '">' + jRelations + '<a href="#" id="remove_button_' + intCount + '" ' + 'class="button" onClick="removeRelationRow(\'#relation_row_' + intCount + '\'); return false;">Remove</a>' + '</div>' );
			});
	});
	
	

});

function removeRelationRow(id)
	{
		jQuery(id).remove();
	}