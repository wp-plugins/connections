<?php

class cnvCard extends cnEntry
{
	private $data;
	private $card;
  
	private function setvCardData()
	{
		$this->data = array(
							'class'=>null,
							'display_name'=>$this->getFullFirstLastName(),
							'first_name'=>$this->getFirstName(),
							'last_name'=>$this->getLastName(),
							'additional_name'=>$this->getMiddleName(),
							'name_prefix'=>null,
							'name_suffix'=>null,
							'nickname'=>null,
							'title'=>$this->getTitle(),
							'role'=>null,
							'department'=>$this->getDepartment(),
							'company'=>$this->getOrganization(),
							'work_po_box'=>null,
							'work_extended_address'=>null,
							'work_address'=>null,
							'work_city'=>null,
							'work_state'=>null,
							'work_postal_code'=>null,
							'work_country'=>null,
							'home_po_box'=>null,
							'home_extended_address'=>null,
							'home_address'=>null,
							'home_city'=>null,
							'home_state'=>null,
							'home_postal_code'=>null,
							'home_country'=>null,
							'other_po_box'=>null,
							'Other_extended_address'=>null,
							'other_address'=>null,
							'other_city'=>null,
							'other_state'=>null,
							'other_postal_code'=>null,
							'other_country'=>null,
							'work_tel'=>null,
							'home_tel'=>null,
							'home_fax'=>null,
							'cell_tel'=>null,
							'work_fax'=>null,
							'pager_tel'=>null,
							'email1'=>null,
							'email2'=>null,
							'url'=>null,
							'aim'=>null,
							'messenger'=>null,
							'yim'=>null,
							'jabber'=>null,
							'photo'=>CN_IMAGE_BASE_URL . $this->getImageNameCard(),
							'birthday'=>$this->getBirthday('Y-m-d'),
							'anniversary'=>$this->getAnniversary('Y-m-d'),
							'spouce'=>null,
							'timezone'=>null,
							'revision_date'=>date('Y-m-d H:i:s', strtotime($this->getUnixTimeStamp())),
							'sort_string'=>null,
							'note'=>$this->getNotes()
							);
		
		$this->setvCardAddresses();
		$this->setvCardPhoneNumbers();
		$this->setvCardEmailAddresses();
		$this->setvCardWebAddresses();
		$this->setvCardIMIDs();
		$this->buildvCard();
	}

	private function buildvCard()
	{
		if (!$this->data['class']) { $this->data['class'] = "PUBLIC"; }
		if (!$this->data['display_name'])
		{
			$this->data['display_name'] = trim($this->data['first_name']." ".$this->data['last_name']);
		}
		
		if (!$this->data['sort_string']) { $this->data['sort_string'] = $this->data['last_name']; }
		if (!$this->data['sort_string']) { $this->data['sort_string'] = $this->data['company']; }
		if (!$this->data['timezone']) { $this->data['timezone'] = date("O"); }
		if (!$this->data['revision_date']) { $this->data['revision_date'] = date('Y-m-d H:i:s'); }
		
		$this->card = "BEGIN:VCARD\r\n";
		$this->card .= "VERSION:3.0\r\n";
		$this->card .= "CLASS:".$this->data['class']."\r\n";
		$this->card .= "PRODID:-//Connections - WordPress Plug-in//Version 1.0//EN\r\n";
		$this->card .= "REV:".$this->data['revision_date']."\r\n";
		$this->card .= "FN:".$this->data['display_name']."\r\n";
		$this->card .= "N:"
			. $this->data['last_name'].";"
			. $this->data['first_name'].";"
			. $this->data['additional_name'].";"
			. $this->data['name_prefix'].";"
			. $this->data['name_suffix']."\r\n";
		
		if ($this->data['nickname']) { $this->card .= "NICKNAME:".$this->data['nickname']."\r\n"; }
		if ($this->data['title']) { $this->card .= "TITLE:".$this->data['title']."\r\n"; }
		if ($this->data['company']) { $this->card .= "ORG:".$this->data['company']; }
		if ($this->data['department']) { $this->card .= ";".$this->data['department']; }
		$this->card .= "\r\n";
			
		if ($this->data['work_po_box']
			|| $this->data['work_extended_address']
			|| $this->data['work_address']
			|| $this->data['work_city']
			|| $this->data['work_state']
			|| $this->data['work_postal_code']
			|| $this->data['work_country'])
		{
			$this->card .= "ADR;TYPE=work:"
		    . $this->data['work_po_box'].";"
		    . $this->data['work_extended_address'].";"
		    . $this->data['work_address'].";"
		    . $this->data['work_city'].";"
		    . $this->data['work_state'].";"
		    . $this->data['work_postal_code'].";"
		    . $this->data['work_country']."\r\n";
		}
		
		if ($this->data['home_po_box']
			|| $this->data['home_extended_address']
			|| $this->data['home_address']
			|| $this->data['home_city']
			|| $this->data['home_state']
			|| $this->data['home_postal_code']
			|| $this->data['home_country'])
		{
			$this->card .= "ADR;TYPE=home:"
		    . $this->data['home_po_box'].";"
		    . $this->data['home_extended_address'].";"
		    . $this->data['home_address'].";"
		    . $this->data['home_city'].";"
		    . $this->data['home_state'].";"
		    . $this->data['home_postal_code'].";"
		    . $this->data['home_country']."\r\n";
		}
		
		if ($this->data['other_po_box']
			|| $this->data['other_extended_address']
			|| $this->data['other_address']
			|| $this->data['other_city']
			|| $this->data['other_state']
			|| $this->data['other_postal_code']
			|| $this->data['other_country'])
		{
			$this->card .= "ADR;TYPE=other:"
		    . $this->data['other_po_box'].";"
		    . $this->data['other_extended_address'].";"
		    . $this->data['other_address'].";"
		    . $this->data['other_city'].";"
		    . $this->data['other_state'].";"
		    . $this->data['other_postal_code'].";"
		    . $this->data['other_country']."\r\n";
		}
		
		if ($this->data['email1']) { $this->card .= "EMAIL;TYPE=internet:".$this->data['email1']."\r\n"; }
		if ($this->data['email2']) { $this->card .= "EMAIL;TYPE=internet:".$this->data['email2']."\r\n"; }
		if ($this->data['work_tel']) { $this->card .= "TEL;TYPE=work,voice:".$this->data['work_tel']."\r\n"; }
		if ($this->data['home_tel']) { $this->card .= "TEL;TYPE=home,voice:".$this->data['home_tel']."\r\n"; }
		if ($this->data['cell_tel']) { $this->card .= "TEL;TYPE=cell,voice:".$this->data['cell_tel']."\r\n"; }
		if ($this->data['work_fax']) { $this->card .= "TEL;TYPE=work,fax:".$this->data['work_fax']."\r\n"; }
		if ($this->data['home_fax']) { $this->card .= "TEL;TYPE=home,fax:".$this->data['home_fax']."\r\n"; }
		if ($this->data['pager_tel']) { $this->card .= "TEL;TYPE=work,pager:".$this->data['pager_tel']."\r\n"; }
		if ($this->data['url']) { $this->card .= "URL:".$this->data['url']."\r\n"; }
		if ($this->data['aim']) { $this->card .= "IMPP;TYPE=personal:aim:".$this->data['aim']."\r\n"; }
		if ($this->data['aim']) { $this->card .= "X-AIM:".$this->data['aim']."\r\n"; }
		if ($this->data['messenger']) { $this->card .= "IMPP;TYPE=personal:msn:".$this->data['messenger']."\r\n"; }
		if ($this->data['messenger']) { $this->card .= "X-MSN:".$this->data['messenger']."\r\n"; }
		if ($this->data['yim']) { $this->card .= "IMPP;TYPE=personal:ymsgr:".$this->data['yim']."\r\n"; }
		if ($this->data['yim']) { $this->card .= "X-YAHOO:".$this->data['yim']."\r\n"; }
		if ($this->data['jabber']) { $this->card .= "IMPP;TYPE=personal:xmpp:".$this->data['jabber']."\r\n"; }
		if ($this->data['jabber']) { $this->card .= "X-JABBER:".$this->data['jabber']."\r\n"; }
		if ($this->data['birthday']) { $this->card .= "BDAY:".$this->data['birthday']."\r\n"; }
		if ($this->data['anniversary']) { $this->card .= "X-ANNIVERSARY:".$this->data['anniversary']."\r\n"; }
		if ($this->data['spouse']) { $this->card .= "X-SPOUSE:".$this->data['spouse']."\r\n"; }
		if ($this->data['role']) { $this->card .= "ROLE:".$this->data['role']."\r\n"; }
		if ($this->data['note']) { $this->card .= "NOTE:".$this->data['note']."\r\n"; }
		if ($this->data['photo']) { $this->card .= "PHOTO;VALUE=uri:".$this->data['photo']."\r\n"; }
		$this->card .= "TZ:".$this->data['timezone']."\r\n";
		$this->card .= "END:VCARD\r\n";
	}
	
	private function setvCardAddresses()
	{
		if ($this->getAddresses())
		{
			$addressObject = new cnAddresses;
			foreach ($this->getAddresses() as $addressRow)
			{
				switch ($addressObject->getType($addressRow))
				{
					case 'home':
						$this->data['home_address'] = $addressObject->getLineOne($addressRow);
						$this->data['home_extended_address'] = $addressObject->getLineTwo($addressRow);
						$this->data['home_city'] = $addressObject->getCity($addressRow);
						$this->data['home_state'] = $addressObject->getState($addressRow);
						$this->data['home_postal_code'] = $addressObject->getZipCode($addressRow);
						$this->data['home_country'] = $addressObject->getCountry($addressRow);
					break;
					
					case 'work':
						$this->data['work_address'] = $addressObject->getLineOne($addressRow);
						$this->data['work_extended_address'] = $addressObject->getLineTwo($addressRow);
						$this->data['work_city'] = $addressObject->getCity($addressRow);
						$this->data['work_state'] = $addressObject->getState($addressRow);
						$this->data['work_postal_code'] = $addressObject->getZipCode($addressRow);
						$this->data['work_country'] = $addressObject->getCountry($addressRow);
					break;
					
					case 'school':
						$this->data['other_address'] = $addressObject->getLineOne($addressRow);
						$this->data['other_extended_address'] = $addressObject->getLineTwo($addressRow);
						$this->data['other_city'] = $addressObject->getCity($addressRow);
						$this->data['other_state'] = $addressObject->getState($addressRow);
						$this->data['other_postal_code'] = $addressObject->getZipCode($addressRow);
						$this->data['other_country'] = $addressObject->getCountry($addressRow);
					break;
					
					case 'other':
						$this->data['other_address'] = $addressObject->getLineOne($addressRow);
						$this->data['other_extended_address'] = $addressObject->getLineTwo($addressRow);
						$this->data['other_city'] = $addressObject->getCity($addressRow);
						$this->data['other_state'] = $addressObject->getState($addressRow);
						$this->data['other_postal_code'] = $addressObject->getZipCode($addressRow);
						$this->data['other_country'] = $addressObject->getCountry($addressRow);
					break;
					
					default:
						switch ($this->getEntryType())
						{
							case 'individual':
								if ($addressObject->getLineOne($addressRow) != null) $this->data['home_address'] = $addressObject->getLineOne($addressRow);
								if ($addressObject->getLineTwo($addressRow) != null) $this->data['home_extended_address'] = $addressObject->getLineTwo($addressRow);
								if ($addressObject->getCity($addressRow) != null) $this->data['home_city'] = $addressObject->getCity($addressRow);
								if ($addressObject->getState($addressRow) != null) $this->data['home_state'] = $addressObject->getState($addressRow);
								if ($addressObject->getZipCode($addressRow) != null) $this->data['home_postal_code'] = $addressObject->getZipCode($addressRow);
								if ($addressObject->getCountry($addressRow) != null) $this->data['home_country'] = $addressObject->getCountry($addressRow);
							break;
						
							case 'organization':
								if ($addressObject->getLineOne($addressRow) != null) $this->data['work_address'] = $addressObject->getLineOne($addressRow);
								if ($addressObject->getLineTwo($addressRow) != null) $this->data['work_extended_address'] = $addressObject->getLineTwo($addressRow);
								if ($addressObject->getCity($addressRow) != null) $this->data['work_city'] = $addressObject->getCity($addressRow);
								if ($addressObject->getState($addressRow) != null) $this->data['work_state'] = $addressObject->getState($addressRow);
								if ($addressObject->getZipCode($addressRow) != null) $this->data['work_postal_code'] = $addressObject->getZipCode($addressRow);
								if ($addressObject->getCountry($addressRow) != null) $this->data['work_country'] = $addressObject->getCountry($addressRow);
							break;
							
							default:
								if ($addressObject->getLineOne($addressRow) != null) $this->data['home_address'] = $addressObject->getLineOne($addressRow);
								if ($addressObject->getLineTwo($addressRow) != null) $this->data['home_extended_address'] = $addressObject->getLineTwo($addressRow);
								if ($addressObject->getCity($addressRow) != null) $this->data['home_city'] = $addressObject->getCity($addressRow);
								if ($addressObject->getState($addressRow) != null) $this->data['home_state'] = $addressObject->getState($addressRow);
								if ($addressObject->getZipCode($addressRow) != null) $this->data['home_postal_code'] = $addressObject->getZipCode($addressRow);
								if ($addressObject->getCountry($addressRow) != null) $this->data['home_country'] = $addressObject->getCountry($addressRow);
							break;
						}
					break;
				}
			}
		}
	}
	
	private function setvCardPhoneNumbers()
    {

		if ($this->getPhoneNumbers())
		{
			$phoneNumberObject = new cnPhoneNumber();
			foreach ($this->getPhoneNumbers() as $phoneNumberRow) 
			{
				switch ($phoneNumberObject->getType($phoneNumberRow))
				{
					case 'home':
						$this->data['home_tel'] = $phoneNumberObject->getNumber($phoneNumberRow);
					break;
						
					case 'homephone':
						$this->data['home_tel'] = $phoneNumberObject->getNumber($phoneNumberRow);
					break;
						
					case 'homefax':
						$this->data['home_fax'] = $phoneNumberObject->getNumber($phoneNumberRow);
					break;
						
					case 'cell':
						$this->data['cell_tel'] = $phoneNumberObject->getNumber($phoneNumberRow);
					break;
						
					case 'cellphone':
						$this->data['cell_tel'] = $phoneNumberObject->getNumber($phoneNumberRow);
					break;
						
					case 'work':
						$this->data['work_tel'] = $phoneNumberObject->getNumber($phoneNumberRow);
					break;
						
					case 'workphone':
						$this->data['work_tel'] = $phoneNumberObject->getNumber($phoneNumberRow);
					break;
						
					case 'workfax':
						$this->data['work_fax'] = $phoneNumberObject->getNumber($phoneNumberRow);
					break;
						
					case 'fax':
						$this->data['work_fax'] = $phoneNumberObject->getNumber($phoneNumberRow);
					break;
				}	
			}
		}
    }

	private function setvCardEmailAddresses()
	{
		if ($this->getEmailAddresses())
		{
			$emailAddressObject = new cnEmail();
			
			foreach ($this->getEmailAddresses() as $emailRow)
			{
				switch ($emailAddressObject->getType($emailRow))
				{
					case 'personal':
						$this->data['email1'] = $emailAddressObject->getAddress($emailRow);
					break;
					
					case 'work':
						$this->data['email2'] = $emailAddressObject->getAddress($emailRow);
					break;
					
					default:
						$this->data['email1'] = $emailAddressObject->getAddress($emailRow);
					break;
				}
			}
			
		}
	}
	
	private function setvCardWebAddresses()
	{
		if ($this->getWebsites())
		{
			$websiteObject = new cnWebsite;
			
			foreach ($this->getWebsites() as $websiteRow)
			{
				switch ($websiteObject->getType($websiteRow))
				{
					case 'personal':
						$this->data['url'] = $websiteObject->getAddress($websiteRow);
					break;
					
					default:
						$this->data['url'] = $websiteObject->getAddress($websiteRow);
					break;
				}
			}
			
		}
	}
	
	private function setvCardIMIDs()
	{
		if ($this->getIm())
		{
			$imObject = new cnIM();
			
			foreach ($this->getIm() as $imRow)
			{
				switch ($imObject->getType($imRow))
				{
					case 'aim':
						$this->data['aim'] = $imObject->getId($imRow);
					break;
					
					case 'yahoo':
						$this->data['yim'] = $imObject->getId($imRow);
					break;
					
					case 'messenger':
						$this->data['messenger'] = $imObject->getId($imRow);
					break;
					
					case 'jabber':
						$this->data['jabber'] = $imObject->getId($imRow);
					break;
					
					default:
						switch ($imObject->getName($imRow))
						{
							case 'AIM':
								$this->data['aim'] = $imObject->getId($imRow);
							break;
							
							case 'Yahoo IM':
								$this->data['yim'] = $imObject->getId($imRow);
							break;
							
							case 'Messenger':
								$this->data['messenger'] = $imObject->getId($imRow);
							break;
							
							case 'Jabber / Google Talk':
								$this->data['jabber'] = $imObject->getId($imRow);
							break;
						}
					break;
				}
			}
			
		}
	}
	
	public function getvCard()
	{
		$this->setvCardData();
		return $this->card;
	}
	
	public function download()
	{
		$token = wp_create_nonce('download_vcard_' . $this->getId());
		
		$filenameEncoded = rawurlencode($filename);
		
		echo '<a href="' . get_option('home') . '/download.vCard.php?token=' . $token . '&entry=' . $this->getId() . '">Add to Address Book</a>';
	}
}
?>