<?php

/**
 * Create custom HTML forms.
 */
class formObjects
{
	private $formAction;
	private $formMethod;
	private $formEncType;
	
	//Function inspired from:
	//http://www.melbournechapter.net/wordpress/programming-languages/php/cman/2006/06/16/php-form-input-and-cross-site-attacks/
	/**
	 * Adds a random token and timestamp to the $_SESSION variable
	 * @return array
	 * @param string $formId The form ID
	 */
	public function token($formId)
	{
		
		$token = md5(uniqid(rand(), true));
		@session_start();
		$_SESSION['connections']['formTokens'][$formId]['token'] = $token;
		$_SESSION['connections']['formTokens'][$formId]['token_timestamp'] = time();
		
		return $token;
	}
	
	/**
	 * Builds a form select list
	 * @return HTML form select
	 * @param string $name
	 * @param array $value_options Associative array where the key is the name visible in the HTML output and the value is the option attribute value
	 * @param string $selected[optional]
	 */
	public function buildSelect($name, $value_options, $selected=null)
	{
		
		$select = "<select name='" . $name . "'> \n";
		foreach($value_options as $key=>$value)
		{
			$select .= "<option ";
			if ($value != null)
			{
				$select .= "value='" . $key . "'";
			}
			else
			{
				$select .= "value=''";
			}
			if ($selected == $key) $select .= " SELECTED";
			
			$select .= ">";
			$select .= $value;
			$select .= "</option> \n";
		}
		$select .= "</select> \n";
		
		return $select;
	}
	
	/**
	 * Builds radio groups. 
	 * @return HTML form radio group
	 * @param object $name
	 * @param object $id
	 * @param object $value_labels associative string array label name [key] and value [value]
	 * @param object $checked[optional] value to be selected by default
	 */
	public function buildRadio($name, $id, $value_labels, $checked=null)
	{
		$radio = null;
		$count = 0;
		
		foreach ($value_labels as $label => $value)
		{
			$idplus = $id . '_' . $count;
			
			if ($checked == $value) $selected = 'CHECKED';
			
			$radio .= '<label for="' . $idplus . '">';
			$radio .= '<input id="' . $idplus . '" type="radio" name="' . $name . '" value="' . $value . '" ' . $selected . ' />';
			$radio .= $label . '</label>';
			
			$selected = null;
			$idplus = null;
			$count = $count + 1;
		}
		
		return $radio;
	}
	
}

?>