jQuery(document).ready(function ($) {
	
	/*$('.cn-vcard').qtip({
			content: {
				text: $('#vcard'), // Add .clone() if you don't want the matched elements to be removed, but simply copied
				title: {
					text: 'vCard',
					button: true
				},
			},
			position: {
				my: 'bottom center',
				at: 'top center'
			},
			hide: false,
			hide: 'unfocus',
			style: { classes: 'ui-tooltip-shadow ui-tooltip-jtools' }
		});*/
	
	$('.cn-vcard').each(function(){
		$(this).qtip({
			/*content: $(this).find('span.qtip-vcard'),
			hide: {
				fixed: true,
				delay: 180
			}*/
			
			content: {
				text: $(this).find('span.qtip-vcard'), // Add .clone() if you don't want the matched elements to be removed, but simply copied
				title: {
					text: 'vCard',
					button: true
				},
			},
			position: {
				my: 'bottom center',
				at: 'top center'
			},
			hide: false,
			hide: 'unfocus',
			style: { classes: 'ui-tooltip-shadow ui-tooltip-jtools' }
			
		});
	});
	
});