<?php
add_filter('cn_list_atts', 'cnSetCategory');
add_filter('cn_list_results', 'cnSearch', 9);
add_filter('cn_list_before', 'cnListSearch', 9, 2);
add_filter('cn_list_before', 'cnCategorySelect', 10, 2);
add_filter('cn_list_before', 'cnResultCount', 10, 2);

function cnSearch($results)
{
	if ( isset($_GET['cn-s']) )
	{
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

function cnListSearch($out, $results = NULL)
{
	$baseURL = get_permalink();
	$queryString = http_build_query($_GET);
	
	//$out .= '<span id="cn-search-label">Search the directory:</span>';
	$out .= '<form method="get" id="cn-search" action="' . esc_url( $baseURL . '?' . $queryString ) . '">
				<input type="text" value="" name="cn-s" id="cn-search-input" />
				<input type="submit" id="cn-searchsubmit" value="Search" />
			</form>';
	
	if ( isset($_GET['cn-s']) )
	{
		if ( !empty($_GET['cn-s']) ) $out .= '<div id="cn-clr-search"><a href="' . $baseURL .'">Clear Search Results</a></div>';
	}
	
	return $out;
}

function cnbuildOptionRowHTML($category, $level, $selected)
{
	$selectString = NULL;
	
	$pad = str_repeat('&nbsp;&nbsp;&nbsp;', max(0, $level));
	if ($selected == $category->term_id && !isset($selectString) ) $selectString = ' SELECTED ';
	
	$out .= '<option value="' . $category->term_id . '"' . $selectString . '>' . $pad . $category->name . '</option>';
	
	if ( !empty($category->children) )
	{
		foreach ( $category->children as $child )
		{
			++$level;
			$out .= cnbuildOptionRowHTML($child, $level, $selected);
			--$level;
		}
		
	}
	
	return $out;

}

function cnCategorySelect($out, $results = NULL)
{
	global $connections;
	$baseURL = get_permalink();
	$selected = esc_attr( $_GET['cn-cat'] );
	$level = 0;
	
	$categories = $connections->retrieve->categories();
	
	$out .= '<form id="cn-cat-select" action="' . esc_url($baseURL) . '" method="get">';
	$out .= '<select name="cn-cat" id="cnCategoryList" onchange="this.form.submit()">';
	
	$out .= '<option value="">Select Category</option>';
	$out .= '<option value="">All</option>';
	
	foreach ( $categories as $key => $category )
	{
		if ( isset($connections->limitCategoryTree) )
		{
			if ( $connections->limitCategoryTree === TRUE )
			{
				if ( !is_array($connections->categoryTreeIDs) )
				{
					// Trim the space characters if present.
					$connections->categoryTreeIDs = str_replace(' ', '', $connections->categoryTreeIDs);
					
					// Convert to array.
					$connections->categoryTreeIDs = explode(',', $connections->categoryTreeIDs);
				}
				
				if ( !in_array($category->term_id, $connections->categoryTreeIDs) ) continue;
			}
		}
		
		$out .= cnbuildOptionRowHTML($category, $level, $selected);
	}
	
	$out .= '</select>';
	$out .= '</form>';
	
	return $out;
}

function cnSetCategory($atts)
{
	global $connections;
	
	if ( isset($_GET['cn-cat']) ) $atts['category'] = $_GET['cn-cat'];
	
	return $atts;
}

function cnResultCount($out, $results = NULL)
{
	global $connections;
	
	$out .= '<div id="cn-result-count">Displaying ' . $connections->resultCount . ' of ' . $connections->recordCount . ' records.</div>';
	
	return $out;
}
?>