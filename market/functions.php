<?php
add_filter('cn_list_results', 'cnSearch', 9);
add_filter('cn_list_results', 'cnLimitList', 10);
add_filter('cn_list_before', 'cnListPages', 10, 2);
add_filter('cn_list_before', 'cnListSearch', 9, 2);
add_filter('cn_list_index', 'cnListIndex', 10, 2);
add_filter('cn_phone_number', 'cnPhoneLables');
//add_filter('cn_website', 'cnWebsite');

function cnWebsite($data)
{
	
}

function cnPhoneLables($data)
{
	switch ($data->type)
	{
		case 'home':
			$data->name = "Phone";
			break;
		case 'homephone':
			$data->name = "Phone";
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
			$data->name = "Phone";
			break;
		case 'workphone':
			$data->name = "Phone";
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

function cnSearch($results)
{
	if ( isset($_GET['cn-s']) )
	{
		if ( empty($_GET['cn-s']) ) return $results;
		
		$found = array();
		
		// Get search terms
		$searchTerms = esc_attr( $_GET['cn-s'] );
		// Remove line breaks and trim white space.
		$searchTerms = preg_replace('/[\r\n\t ]+/', ' ', $searchTerms);
		// Create an array from the search terms.
		$searchTerms = explode(' ', $searchTerms);
		
		// Search for each of the terms.
		foreach ( (array) $searchTerms as $searchTerm)
		{
			foreach ($results as $key => $row)
			{
				foreach ( (array) $row as $data )
				{
					if ( mb_stristr($data, $searchTerm) !== FALSE )
					{
						$found[$key] = $row;
					}
				}
			}
		}
		
		if ( !empty($found) )
		{
			global $connections;
			
			$connections->resultCount = count($found);
			
			return $found;
		}
		else
		{
			return array();
		}
	}
	else
	{
		return $results;
	}
}

function cnLimitList($results)
{
	$limit = 20; // Page Limit
	
	( !isset($_GET['cn-pg']) ) ? $offset = 0 : $offset = ( $_GET['cn-pg'] - 1 ) * $limit;
	
	return array_slice($results, $offset, $limit, TRUE);
}

function cnListSearch($out, $results = NULL)
{
	$baseURL = get_permalink();
	$queryString = http_build_query($_GET);
	
	$out .= '<form method="get" id="cn-search" action="' . esc_url( $baseURL . '?' . $queryString ) . '">
				<input type="text" value="" name="cn-s" id="cn-search-input" />
				<input type="submit" id="cn-searchsubmit" value="Search" />
			</form>';
	return $out;
}

function cnListPages($out, $results = NULL)
{
	global $connections;
	$i = 1;
	
	$limit = 20; // Page Limit
	$pageCount = ceil( $connections->resultCount / $limit );
	$baseURL = get_permalink();
	
	$out .= '<ul id="cn-pages">';
		$out .= '<li>Pages: </li>';
		while ($i <= $pageCount)
		{
			$_GET['cn-pg'] = $i;
			$queryString = http_build_query($_GET);
			
			$out .= '<li><a href="' . esc_url( $baseURL . '?' . $queryString ) . '">' . $i . '</a></li>';
			$i++;
		}
	$out .= '</ul';
	
	return $out;
}

function cnListIndex($index, $results = NULL)
{
	/*
	 * Dynamically builds the alpha index based on the available entries.
	 */
	$previousLetter = NULL;
	$setAnchor = NULL;
	
	foreach ( (array) $results as $row)
	{
		$entry = new cnEntry($row);
		$currentLetter = strtoupper(mb_substr($entry->getFullLastFirstName(), 0, 1));
		if ($currentLetter != $previousLetter)
		{
			$setAnchor .= '<a href="#' . $currentLetter . '">' . $currentLetter . '</a> ';
			$previousLetter = $currentLetter;
		}
	}
	
	return '<div class="cn-alphaindex">' . $setAnchor . '</div>';
}
?>