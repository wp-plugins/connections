<?php



class date
{
    /**
     * Returns $days.
     * @see date::$days
     */
    public function getDays()
    {
        return $this->days;
    }
    
    /**
     * Sets $days.
     * @param object $days
     * @see date::$days
     */
    public function setDays($days)
    {
        $this->days = $days;
    }

	/**
	 * Returns an associative array containing days 1 thru 31
	 * @var array
	 */
	var $days = array(	'Day'=>null,
						'1st'=>'01',
						'2nd'=>'02',
						'3rd'=>'03',
						'4th'=>'04',
						'5th'=>'05',
						'6th'=>'06',
						'7th'=>'07',
						'8th'=>'08',
						'9th'=>'09',
						'10th'=>'10',
						'11th'=>'11',
						'12th'=>'12',
						'13th'=>'13',
						'14th'=>'14',
						'15th'=>'15',
						'16th'=>'16',
						'17th'=>'17',
						'18th'=>'18',
						'19th'=>'19',
						'20th'=>'20',
						'21st'=>'21',
						'22nd'=>'22',
						'23rd'=>'23',
						'24th'=>'24',
						'25th'=>'25',
						'26th'=>'26',
						'27th'=>'27',
						'28th'=>'28',
						'29th'=>'29',
						'30th'=>'30',
						'31st'=>'31',);

    /**
     * Returns $months.
     * @see month::$months
     */
    public function getMonths()
    {
        return $this->months;
    }
    
    /**
     * Sets $months.
     * @param object $months
     * @see month::$months
     */
    public function setMonths($months)
    {
        $this->months = $months;
    }

	/**
	 * Returns an associative array of months Jan thru Dec
	 * @var
	 */
	var $months = array('Month'=>null,
						'January'=>'01',
						'February'=>'02',
						'March'=>'03',
						'April'=>'04',
						'May'=>'05',
						'June'=>'06',
						'July'=>'07',
						'August'=>'08',
						'September'=>'09',
						'October'=>'10',
						'November'=>'11',
						'December'=>'12');
	
}

?>