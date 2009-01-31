/**
 * @author Steven A. Zahm
 */

// Use jQuery() instead of $()for WordPress compatibility with the included prototype js library which uses $()
// http://ipaulpro.com/blog/tutorials/2008/08/jquery-and-wordpress-getting-started/
// See http://chrismeller.com/using-jquery-in-wordpress
jQuery(document).ready(function(){
	jQuery('.connections tr:even').addClass('alternate');
});

jQuery(function() {
	jQuery('a.detailsbutton')
		.css("cursor","pointer")
		.attr("title","Click to show details.")
		.click(function(){
			jQuery('.child-'+this.id).toggle();
		});
	//jQuery('tr[@class^=child-]').hide().children('td');
	return false;
});

jQuery(function() {
	jQuery('input#entry_type1')
		.click(function(){
			jQuery('.namefield').slideUp();
		});
});

jQuery(function() {
	jQuery('input#entry_type0')
		.click(function(){
			jQuery('.namefield').slideDown();
		});
});