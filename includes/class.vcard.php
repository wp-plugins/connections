<?php

class vCard extends entry
{
	private $data;		//array of this vCard data
	private $class;		//PUBLIC, PRIVATE, CONFIDENTIAL
	private $revisionDate;
	private $card;
  
	public function setvCardData($class = null)
	{
		$this->class = $class;
		$this->revisionDate = date('Y-m-d H:i:s', strtotime($this->getUnixTimeStamp()));
		
		$this->data = array(
							'display_name'=>$this->getFullFirstLastName(),
							'first_name'=>$this->getFirstName(),
							'last_name'=>$this->getLastName(),
							'additional_name'=>null,
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
							'office_tel'=>null,
							'home_tel'=>null,
							'cell_tel'=>null,
							'fax_tel'=>null,
							'pager_tel'=>null,
							'email1'=>null,
							'email2'=>null,
							'url'=>null,
							'photo'=>CN_IMAGE_BASE_URL . $this->getImageNameCard(),
							'birthday'=>$this->getBirthday('Y-m-d'),
							'timezone'=>null,
							'sort_string'=>null,
							'note'=>$this->getNotes()
							);
		$this->setvCardAddresses();
		$this->buildvCard();
	}

	private function buildvCard()
	{
		if (!$this->class) { $this->class = "PUBLIC"; }
		if (!$this->data['display_name'])
		{
			$this->data['display_name'] = trim($this->data['first_name']." ".$this->data['last_name']);
		}
		
		if (!$this->data['sort_string']) { $this->data['sort_string'] = $this->data['last_name']; }
		if (!$this->data['sort_string']) { $this->data['sort_string'] = $this->data['company']; }
		if (!$this->data['timezone']) { $this->data['timezone'] = date("O"); }
		if (!$this->revision_date) { $this->revision_date = date('Y-m-d H:i:s'); }
		
		$this->card = "BEGIN:VCARD\r\n";
		$this->card .= "VERSION:3.0\r\n";
		$this->card .= "CLASS:".$this->class."\r\n";
		$this->card .= "PRODID:-//class_vcard from TroyWolf.com//NONSGML Version 1//EN\r\n";
		$this->card .= "REV:".$this->revisionDate."\r\n";
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
		
		if ($this->data['email1']) { $this->card .= "EMAIL;TYPE=internet,pref:".$this->data['email1']."\r\n"; }
		if ($this->data['email2']) { $this->card .= "EMAIL;TYPE=internet:".$this->data['email2']."\r\n"; }
		if ($this->data['office_tel']) { $this->card .= "TEL;TYPE=work,voice:".$this->data['office_tel']."\r\n"; }
		if ($this->data['home_tel']) { $this->card .= "TEL;TYPE=home,voice:".$this->data['home_tel']."\r\n"; }
		if ($this->data['cell_tel']) { $this->card .= "TEL;TYPE=cell,voice:".$this->data['cell_tel']."\r\n"; }
		if ($this->data['fax_tel']) { $this->card .= "TEL;TYPE=work,fax:".$this->data['fax_tel']."\r\n"; }
		if ($this->data['pager_tel']) { $this->card .= "TEL;TYPE=work,pager:".$this->data['pager_tel']."\r\n"; }
		if ($this->data['url']) { $this->card .= "URL;TYPE=work:".$this->data['url']."\r\n"; }
		if ($this->data['birthday']) { $this->card .= "BDAY:".$this->data['birthday']."\r\n"; }
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
			$addressObject = new addresses;
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
						if ($this->getEntryType() == 'individual')
						{
							$this->data['home_address'] = $addressObject->getLineOne($addressRow);
							$this->data['home_extended_address'] = $addressObject->getLineTwo($addressRow);
							$this->data['home_city'] = $addressObject->getCity($addressRow);
							$this->data['home_state'] = $addressObject->getState($addressRow);
							$this->data['home_postal_code'] = $addressObject->getZipCode($addressRow);
							$this->data['home_country'] = $addressObject->getCountry($addressRow);
						}
						elseif ($this->getEntryType() == 'organization')
						{
							$this->data['work_address'] = $addressObject->getLineOne($addressRow);
							$this->data['work_extended_address'] = $addressObject->getLineTwo($addressRow);
							$this->data['work_city'] = $addressObject->getCity($addressRow);
							$this->data['work_state'] = $addressObject->getState($addressRow);
							$this->data['work_postal_code'] = $addressObject->getZipCode($addressRow);
							$this->data['work_country'] = $addressObject->getCountry($addressRow);
						}
					break;
				}
			}
		}
	}
  
	public function download()
	{
		session_start();
		
		$filename = trim($this->data['display_name']); 
		$filename = str_replace(" ", "_", $filename);
		
		$token = md5(uniqid(rand(), true));
		
		$_SESSION['vcard'][$token]['filename'] = $filename;
		$_SESSION['vcard'][$token]['data'] = $this->card;
		
		return '<a href="http://www.sandbox.nh-online.com/wp-content/plugins/connections/includes/download.vCard.php?uid=' . $token . '">Add to Address Book</a>';
	}
}
?>