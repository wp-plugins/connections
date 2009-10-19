<?php

class cnTerms
{
	/**
	 * Holds the array that shows the term parent relationship as array.
	 * key == the parent ID
	 * value == array of the child objects
	 * 
	 * @var array
	 */
	private $termChildren = array();
	
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
		
		/*
		 * Loop thru the results and build an array where key == parent ID and the value == the child objects
		 * 
		 * NOTE: Currently $taxonomies does not need to be sent, it's not being used in the method. It's 
		 * 		 being left in place for future use.
		 */
		foreach ($terms as $term)
		{
			$this->buildChildrenArray($term->term_id, $terms, $taxonomies);
		}
		
		/*
		 * Loop thru the results again adding the children objects from $this->termChildren to the parent object.
		 * 
		 * NOTE: Currently $taxonomies does not need to be sent, it's not being used in the method. It's 
		 * 		 being left in place for future use.
		 */
		foreach($terms as $key => $term)
		{
			$term->children = $this->getChildren($term->term_id, $terms, $taxonomies);
		}
		
		/*
		 * Loop thru the results once more and remove all child objects from the base array leaving only parent objects
		 */
		foreach($terms as $key => $term)
		{
			if ($this->isChild($term->term_id)) unset($terms[$key]);
		}
		
		//return $this->termChildren;
		return $terms;
	}
	
	private function getChildren($termID, $terms, $taxonomies)
	{
		foreach ($terms as $key => $term)
		{
			if ($termID == $term->parent)
			{
				$termList[] = $term;
			}
		}
		return $termList;
		//return $this->termChildren[$termID];
	}
	
	private function buildChildrenArray($termID, $terms, $taxonomies)
	{
		foreach ($terms as $term)
		{
			// Skip the term if it is itself
			if ($termID == $term->term_id) continue;
			
			if ($termID == $term->parent)
			{
				$this->termChildren[$termID][] = $term;
			}
		}
	}
	
	private function isChild($termID)
	{
		foreach ($this->termChildren as $parentID => $children)
		{
			foreach ($children as $child)
			{
				if ($termID == $child->term_id)
				{
					$isChild = TRUE;
				}
			}
		}
		
		if ($isChild)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

?>