<?php

class cnCounter
{
     private $step;
     private $count; 

     public function getcount() {
          return $this->count;
     }
 
     public function getstep() {
          return $this->step;
     }

     public function changestep($newval) {
          if(is_integer($newval))
          $this->step = $newval;
     }

     public function step() {
          $this->count += $this->step;
     }

     public function reset() {
          $this->count = 0;
          $this->step = 1;
     }
}

class cnConvert
{
	/**
	 * Converts the following strings: yes/no; true/false and 0/1 to boolean values.
	 * If the supplied string does not match one of those values the method will return NULL.
	 * 
	 * @param string $value
	 * @return boolean
	 */
	public function toBoolean($value)
	{
		switch ($value) 
		{
			case 'yes':
				$value = TRUE;
			break;
			
			case 'no':
				$value = FALSE;
			break;
			
			case 'true':
				$value = TRUE;
			break;
			
			case 'false':
				$value = FALSE;
			break;
			
			case '1':
				$value = TRUE;
			break;
			
			case '0':
				$value = FALSE;
			break;
			
			default:
				$value = NULL;
			break;
		}
		
		return $value;
	}
}
?>