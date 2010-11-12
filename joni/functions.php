<?php
add_filter('cn_phone_number', 'cnPhoneLabels');
add_filter('cn_email_address', 'cnEmailLabels');

function cnPhoneLabels($data)
{
	switch ($data->type)
	{
		case 'home':
			$data->name = "Tel";
			break;
		case 'homephone':
			$data->name = "Tel";
			break;
		case 'homefax':
			$data->name = "Fax";
			break;
		case 'cell':
			$data->name = "Cell";
			break;
		case 'cellphone':
			$data->name = "Cell";
			break;
		case 'work':
			$data->name = "Tel";
			break;
		case 'workphone':
			$data->name = "Tel";
			break;
		case 'workfax':
			$data->name = "Fax";
			break;
		case 'fax':
			$data->name = "Fax";
			break;
	}
	
	return $data;
}

function cnEmailLabels($data)
{
	switch ($data->type)
	{
		case 'personal':
			$data->name = 'Email';
			break;
		case 'work':
			$data->name = 'Email';
			break;
		
		default:
			$data->name = 'Email';
		break;
	}
	
	return $data;
}
?>