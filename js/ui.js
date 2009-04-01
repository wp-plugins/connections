/**
 * @author Steven A. Zahm
 */

// Use jQuery() instead of $()for WordPress compatibility with the included prototype js library which uses $()
// http://ipaulpro.com/blog/tutorials/2008/08/jquery-and-wordpress-getting-started/
// See http://chrismeller.com/using-jquery-in-wordpress
jQuery(document).ready(function(){
	jQuery('.connections tr:even').addClass('alternate');
	
	/*var $entryType = (jQuery('input[@name=entry_type]:checked').val());
	
	if ($entryType == 'organization')
	{
		jQuery('.namefield').slideUp();
		jQuery('.homephone').slideUp();
		jQuery('.homefax').slideUp();
		jQuery('.personal').slideUp();
		jQuery('.cellphone').slideUp();
		jQuery('.im').slideUp();
		jQuery('.celebrate').slideUp();
	}*/

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
		jQuery('input#entry_type1')
			.click(function(){
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
		jQuery('input#entry_type0')
			.click(function(){
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
		var $entryType = (jQuery('input[@name=entry_type]:checked').val());
		
		if ($entryType == 'organization')
		{
			jQuery('.namefield').slideUp();
			jQuery('.home').slideUp();
			jQuery('.homephone').slideUp();
			jQuery('.homefax').slideUp();
			jQuery('.personal').slideUp();
			jQuery('.cell').slideUp();
			jQuery('.cellphone').slideUp();
			jQuery('.im').slideUp();
			jQuery('.celebrate').slideUp();
		}
	
	});

});