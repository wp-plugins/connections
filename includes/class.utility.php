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

class cnFormatting
{
	/**
	 * Sanitize the input string. HTML tags can be permitted.
	 * The permitted tags can be suppled in an array.
	 * 
	 * @TODO: Finish the code needed to support the $permittedTags array.
	 * 
	 * @param string $string
	 * @param bool $allowHTML [optional]
	 * @param array $permittedTags [optional]
	 * @return string
	 */
	public function sanitizeString($string, $allowHTML = FALSE, $permittedTags = NULL)
	{
		// Ensure all tags are closed.
		$balancedText = balanceTags($string, TRUE);
		
		// Strip all tags except the permitted.
		if (!$allowHTML)
		{
			$strippedText = strip_tags($balancedText);
		}
		else
		{
			$strippedText = strip_tags($balancedText, '<p><b><strong><i><em><br>');
		}
		
		// Strip all script and style tags.
		$strippedText = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $strippedText );
		
		// Escape text using the WordPress method and then strip slashes.
		$escapedText = stripslashes(esc_attr($strippedText));
		
		if ($allowHTML)
		{
			// Allow <p> and </p>
			$escapedText = str_replace('&lt;p&gt;', '<p>', $escapedText);
			$escapedText = str_replace('&lt;/p&gt;', '</p>', $escapedText);
			
			// Allow <b> and </b>
			$escapedText = str_replace('&lt;b&gt;', '<b>', $escapedText);
			$escapedText = str_replace('&lt;/b&gt;', '</b>', $escapedText);
			
			// Allow <strong> and </strong>
			$escapedText = str_replace('&lt;strong&gt;', '<strong>', $escapedText);
			$escapedText = str_replace('&lt;/strong&gt;', '</strong>', $escapedText);
			
			// Allow <i> and </i>
			$escapedText = str_replace('&lt;i&gt;', '<i>', $escapedText);
			$escapedText = str_replace('&lt;/i&gt;', '</i>', $escapedText);
			
			// Allow <em> and </em>
			$escapedText = str_replace('&lt;strong&gt;', '<em>', $escapedText);
			$escapedText = str_replace('&lt;/strong&gt;', '</em>', $escapedText);
			
			// Allow <br> and <br/> and <br />
			$escapedText = str_replace('&lt;br&gt;', '<br />', $escapedText);
			$escapedText = str_replace('&lt;br/&gt;', '<br />', $escapedText);
			$escapedText = str_replace('&lt;br /&gt;', '<br />', $escapedText);
		}
		
		// Remove line breaks.
		$escapedText = preg_replace('/[\r\n\t ]+/', ' ', $escapedText);
		
		return trim($escapedText);
	}
}
?>