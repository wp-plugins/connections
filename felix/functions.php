<?php
add_filter('cn_phone_number', 'cnTranslatePhone');

function cnTranslatePhone($phone)
{
	switch ( $phone->type )
	{
		case 'home':
			$phone->name = 'Telefoon';
			break;
		case 'homephone':
			$phone->name = 'Telefoon';
			break;
		case 'cell':
			$phone->name = 'Mobiel';
			break;
		case 'cellphone':
			$phone->name = 'Mobiel';
			break;
	}
	
	return $phone;
}
?>