<?php
add_filter('cn_list_before', 'cnCategorySelect', 10, 2);
add_filter('cn_list_atts', 'cnSetCategory');

function cnbuildOptionRowHTML($category, $level, $selected)
{
	$selectString = NULL;
	
	$pad = str_repeat('&nbsp;&nbsp;&nbsp;', max(0, $level));
	if ($selected == $category->term_id) $selectString = ' SELECTED ';
	
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
	
	$out .= '<form id="cnCategorySelect" action="' . esc_url($baseURL) . '" method="get">';
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
	
	$connections->limitCategoryTree = TRUE;
	$connections->categoryTreeIDs = esc_attr( $atts['category'] );
	
	if ( isset($_GET['cn-cat']) ) $atts['category'] = $_GET['cn-cat'];
	
	return $atts;
}
?>