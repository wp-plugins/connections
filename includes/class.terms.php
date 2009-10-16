<?php

class cnTerms
{
	/**
	 * Returns all the terms under a taxonomy type.
	 * 
	 * $taxonomies currently this will only accept a string of the specified taxonomy
	 * @TODO: Add the code necessary to accept arrays for requesting multiple taxonomy types
	 * $TODO: Add default arguments see /wp-includes/taxonomy.php ->  line 515 to get terms specific to a type
	 * 
	 * @param array $taxonomies
	 * @param array $arguments [optional]
	 * @return 
	 */
	public function getTerms($taxonomies, $arguments = NULL)
	{
		global $wpdb;
		
		$query = "SELECT t.*, tt.* from wp_connections_terms AS t INNER JOIN wp_connections_term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('$taxonomies') ORDER BY 'name'";
		
		$terms = $wpdb->get_results($query);
		
		return $terms;
	}
	
	private function sortHierarchical($terms)
	{
		foreach ($terms as $term)
		{
			if ($term['parent'] != 0)
			{
				//$terms[$term['parent']]
			}
		}
	}
}

?>