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
		// Ensure all tags are closed. Uses WordPress method balanceTags().
		$balancedText = balanceTags($string, TRUE);
		
		// Strip all tags except the permitted.
		if (!$allowHTML)
		{
			$strippedText = strip_tags($balancedText);
		}
		else
		{
			$strippedText = strip_tags($balancedText, '<p><a><b><strong><i><em><br>');
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
			$escapedText = str_replace('&lt;em&gt;', '<em>', $escapedText);
			$escapedText = str_replace('&lt;/em&gt;', '</em>', $escapedText);
			
			// Allow <br> and <br/> and <br />
			$escapedText = str_replace('&lt;br&gt;', '<br />', $escapedText);
			$escapedText = str_replace('&lt;br/&gt;', '<br />', $escapedText);
			$escapedText = str_replace('&lt;br /&gt;', '<br />', $escapedText);
			
			// Allow <a>. Uses WordPress method make_clickable().
			$escapedText = make_clickable($escapedText);
			$escapedText = preg_replace('/\&lt\;a.*\s[^>]*href\s*=\s*(\&quot\;??)(.*)\\1[^>]*\&gt\;(.*)\&lt\;\/a\&gt\;/siU', '<a href="\2">\3</a>' ,$escapedText);
			
			// Reference here for the regexp http://www.the-art-of-web.com/php/parse-links/

		}
		
		// Remove line breaks.
		$escapedText = preg_replace('/[\r\n\t ]+/', ' ', $escapedText);
		
		return trim($escapedText);
	}
	
	/**
	 * Uses WordPress function to sanitize the input string.
	 * 
	 * Limits the output to alphanumeric characters, underscore (_) and dash (-).
	 * Whitespace becomes a dash.
	 * 
	 * @param string $string
	 * @return string
	 */
	public function sanitizeStringStrong($string)
	{
		$string = sanitize_title_with_dashes($string);
		return $string;
	}
	
	/**
	 * Strips all numeric characters from the supplied string and returns the string.
	 * 
	 * @param string $string
	 * @return string
	 */
	public function stripNonNumeric($string)
	{
		return preg_replace('/[^0-9]/', '', $string);
	}
	
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

class cnValidate
{
	public function attributesArray($defaults, $untrusted)
	{
		$intersect = array_intersect_key($untrusted, $defaults); // Get data for which is in the valid fields.
		$difference = array_diff_key($defaults, $untrusted); // Get default data which is not supplied.
		return array_merge($intersect, $difference); // Merge the results. Contains only valid fields of all defaults.
	}
	
	public function url()
	{
		
	}
	
	public function email()
	{
		
	}
}
?>