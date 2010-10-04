<?php
add_filter('cn_list_atts', 'cnSearch');
add_filter('cn_list_results', 'cnLimitList');
add_filter('cn_list_before', 'cnListBefore', 10, 2);
add_filter('cn_list_before', 'cnListSearch', 9, 2);
add_filter('cn_list_index', 'cnListIndex', 10, 2);

function cnSearch($atts)
{
	if ( isset($_GET['cn-s']) )
	{
		$atts['last_name'] = esc_attr( $_GET['cn-s'] );
		return $atts;
	}
}

function cnLimitList($results)
{
	$limit = 20; // Page Limit
	//print_r(( $_GET['cn-page'] - 1 ) * $limit);
	
	( !isset($_GET['cn-page']) ) ? $offset = 0 : $offset = ( $_GET['cn-page'] - 1 ) * $limit;
	
	//print_r( count( array_slice($results, $offset, $limit, TRUE) ) );
	return array_slice($results, $offset, $limit, TRUE);
}

function cnListSearch($out, $results = NULL)
{
	$baseURL = get_permalink();
	
	$out .= '<form method="get" id="cn-search" action="' . esc_url($baseURL) . '">
				<input type="text" value="" name="cn-s" id="cn-search-input" />
				<input type="submit" id="cn-searchsubmit" value="Search" />
			</form>';
	return $out;
}

function cnListBefore($out, $results = NULL)
{
	$limit = 20; // Page Limit
	$pageCount = ceil( count($results) / $limit );
	$baseURL = get_permalink();
	//print_r(count($results));
	$out .= '<ul>';
		while ($i < $pageCount)
		{
			$i++;
			
			$out .= '<li><a href="' . esc_url( $baseURL . '?cn-page=' . $i ) . '">' . $i . '</a></li>';
		}
	$out .= '</ul';
	
	//$out .= '<p>BEFORE LIST FILTER: results = ' . $pageCount . '</p>';
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
	
	return '<div class="cn-alphaindex" style="text-align:right;font-size:larger;font-weight:bold">' . $setAnchor . '</div>';
}
?>