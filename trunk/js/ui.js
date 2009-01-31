/**
 * @author Steven A. Zahm
 */
function click_contact(row, id) {
									
	if (
		document.getElementById('contact-'+id+'-detail').style.display != 'none' ) {
		document.getElementById('contact-'+id+'-detail').style.display = 'none';
		document.getElementById('contact-'+id+'-detail-notes').style.display = 'none';
		document.getElementById('detailbutton'+id).innerHTML='Show Details';
	} else {
		document.getElementById('contact-'+id+'-detail').style.display = '';
		document.getElementById('contact-'+id+'-detail-notes').style.display = '';
		document.getElementById('detailbutton'+id).innerHTML='Hide Details';
	}
	
}
