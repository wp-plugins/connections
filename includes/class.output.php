<?php

class output extends entry
{
	
	function getAddressBlock()
	{
		if ($this->getAddresses())
		{
			$addressObject = new addresses;
			foreach ($this->getAddresses() as $addressRow)
			{
				$out .= "<div class='address' style='margin-bottom: 10px;'>";
				if ($addressObject->getName($addressRow) != null || $addressObject->getType($addressRow)) $out .= "<strong>" . $addressObject->getName($addressRow) . "</strong><br />"; //The OR is for compatiblity for 0.2.24 and under
				if ($addressObject->getLineOne($addressRow) != null) $out .= $addressObject->getLineOne($addressRow) . "<br />";
				if ($addressObject->getLineTwo($addressRow) != null) $out .= $addressObject->getLineTwo($addressRow) . "<br />";
				if ($addressObject->getCity($addressRow) != null) $out .= $addressObject->getCity($addressRow) . "&nbsp;";
				if ($addressObject->getState($addressRow) != null) $out .= $addressObject->getState($addressRow) . "&nbsp;";
				if ($addressObject->getZipCode($addressRow) != null) $out .= $addressObject->getZipCode($addressRow) . "<br />";
				if ($addressObject->getCountry($addressRow) != null) $out .= $addressObject->getCountry($addressRow);
				$out .= "</div>";
															
			}
		}
		return $out;
	}
	
}

?>