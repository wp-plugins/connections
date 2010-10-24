<?php
add_filter('cn_phone_number', 'cnTranslatePhone');

function cnTranslatePhone($phone)
{
	switch ( $phone->type )
	{
		case 'home':
			$phone->name = 'Telefon';
			break;
		case 'homephone':
			$phone->name = 'Telefoon';
			break;
		case 'cell':
			$phone->name = 'Mobil';
			break;
		case 'cellphone':
			$phone->name = 'Mobil';
			break;
		case 'work':
			$phone->name = 'Telefoon';
			break;
		case 'workphone':
			$phone->name = 'Telefoon';
			break;
		case 'workfax':
			$phone->name = 'Fax';
			break;
		case 'fax':
			$phone->name = 'Fax';
			break;
	}
	
	return $phone;
}
?>