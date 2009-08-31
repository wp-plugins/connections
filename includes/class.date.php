<?php

class dateFunctions
{
	/**
	 * Returns an associative array containing days 1 thru 31
	 * @var array
	 */
	var $days = array(	null=>'Day',
						'01'=>'01st',
						'02'=>'02nd',
						'03'=>'03rd',
						'04'=>'04th',
						'05'=>'05th',
						'06'=>'06th',
						'07'=>'07th',
						'08'=>'08th',
						'09'=>'09th',
						'10'=>'10th',
						'11'=>'11th',
						'12'=>'12th',
						'13'=>'13th',
						'14'=>'14th',
						'15'=>'15th',
						'16'=>'16th',
						'17'=>'17th',
						'18'=>'18th',
						'19'=>'19th',
						'20'=>'20th',
						'21'=>'21st',
						'22'=>'22nd',
						'23'=>'23rd',
						'24'=>'24th',
						'25'=>'25th',
						'26'=>'26th',
						'27'=>'27th',
						'28'=>'28th',
						'29'=>'29th',
						'30'=>'30th',
						'31'=>'31st',);

	/**
	 * Returns an associative array of months Jan thru Dec
	 * @var array
	 */
	var $months = array( null=>'Month',
						'01'=>'January',
						'02'=>'February',
						'03'=>'March',
						'04'=>'April',
						'05'=>'May',
						'06'=>'June',
						'07'=>'July',
						'08'=>'August',
						'09'=>'September',
						'10'=>'October',
						'11'=>'November',
						'12'=>'December');
	
	public function getMonth($data)
	{
		if ($data != null)
		{
			$month = date("m", strtotime($data));
		}
		else
		{
			$month = null;
		}
		return $month;
	}
	
	public function getDay($data)
	{
		if ($data != null)
		{
			$day = date("d", strtotime($data));
		}
		else
		{
			$day = null;
		}
		return $day;
		
	}
}

?>