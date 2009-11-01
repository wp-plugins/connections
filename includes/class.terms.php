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
		
		$query = "SELECT t.*, tt.* from " . CN_TERMS_TABLE . " AS t INNER JOIN " . CN_TERM_TAXONOMY_TABLE . " AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('$taxonomies') ORDER BY `name`";
		
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
	
	public function getTerm($id, $taxonomy)
	{
		global $wpdb;
		
		$query = "SELECT t.*, tt.* from " . CN_TERMS_TABLE . " AS t INNER JOIN " . CN_TERM_TAXONOMY_TABLE . " AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy='$taxonomy' AND t.term_id='$id'";
		
		return $wpdb->get_row($query);
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
	
	public function addTerm($term, $taxonomy, $attributes)
	{
		global $wpdb, $connections;
		
		$slug = $attributes['slug'];
		$description = $attributes['description'];
		$parent = $attributes['parent'];
		
		$this->getUniqueSlug(&$slug, $term);
		
		/**
		 * @TODO: Make sure the term doesn't exist before adding it.
		 * If term does exist, only the taxonomy table needs to be updated.
		 */
		$sql = "INSERT INTO " . CN_TERMS_TABLE . " SET
			name    	= '" . $wpdb->escape($term) . "',
			slug    	= '" . $wpdb->escape($slug) . "',
			term_group	= '0'";
		
		/**
		 * @TODO: Error check the insert and return error
		 */
		$wpdb->query($wpdb->prepare($sql));
		unset($sql);
		
		// Not quite sure how the wpdb class sets this variable???
		$term_id = (int) $wpdb->insert_id;
		
		
		$sql = "INSERT INTO " . CN_TERM_TAXONOMY_TABLE . " SET
			term_id    	= '" . $wpdb->escape($term_id) . "',
			taxonomy   	= '" . $wpdb->escape($taxonomy) . "',
			description	= '" . $wpdb->escape($description) . "',
			count		= '0',
			parent		= '" . $wpdb->escape($parent) . "'";
		
		/**
		 * @TODO: Error check the insert and return error
		 */
		$wpdb->query($wpdb->prepare($sql));
		unset($sql);
	}
	
	public function updateTerm($termID, $taxonomy, $attributes)
	{
		global $wpdb, $connections;
		
		$name = $attributes['name'];
		$slug = $attributes['slug'];
		$parent = $attributes['parent'];
		$description = $attributes['description'];
		
		$this->getUniqueSlug(&$slug, $name);
		
		$sql = "UPDATE " . CN_TERMS_TABLE . " SET
			name		= '" . $wpdb->escape($name) . "',
			slug		= '" . $wpdb->escape($slug) . "',
			term_group	= '0'
			WHERE term_id = '" . $wpdb->escape($termID) . "'";
		
		/**
		 * @TODO: Error check the insert and return error
		 */
		$wpdb->query($wpdb->prepare($sql));
		unset($sql);	
		
		$ttID = $wpdb->get_var( $wpdb->prepare( "SELECT tt.term_taxonomy_id FROM " . CN_TERM_TAXONOMY_TABLE . " AS tt INNER JOIN " . CN_TERMS_TABLE . " AS t ON tt.term_id = t.term_id WHERE tt.taxonomy = %s AND t.term_id = %d", $taxonomy, $termID) );
		
		$sql = "UPDATE " . CN_TERM_TAXONOMY_TABLE . " SET
			term_id		= '" . $wpdb->escape($termID) . "',
			taxonomy	= '" . $wpdb->escape($taxonomy) . "',
			description	= '" . $wpdb->escape($description) . "',
			parent		= '" . $wpdb->escape($parent) . "'
			WHERE term_taxonomy_id 	= '" . $wpdb->escape($ttID) . "'";
		
		/**
		 * @TODO: Error check the insert and return error
		 */
		$wpdb->query($wpdb->prepare($sql));
		unset($sql);
	
	}
	
	/**
	 * Remove a term from the database.
	 * 
	 * If the term contains children terms, the children terms will be updated
	 * to the deleted term parent.
	 * 
	 * @param int $id Term ID
	 * @param int $id Term Parent ID
	 * @param string $taxonomy Taxonomy Name
	 */
	public function deleteTerm($id, $parent, $taxonomy)
	{
		global $wpdb;
		
		$childrenTerms = $wpdb->get_col( "SELECT `term_taxonomy_id` FROM " . CN_TERM_TAXONOMY_TABLE . " WHERE `parent` = " . (int)$id );
		
		// Move the children terms to the parent term.
		foreach ($childrenTerms as $childID)
		{
				$sql = "UPDATE " . CN_TERM_TAXONOMY_TABLE . " SET
				parent			= '" . $wpdb->escape($parent) . "'
				WHERE parent	= '" . $wpdb->escape($id) . "'";
			
			/**
			 * @TODO: Error check the insert and return error
			 */
			$wpdb->query($wpdb->prepare($sql));
			unset($sql);
		}
		
		// Delete the term taxonomy.
		$wpdb->query($wpdb->prepare("DELETE FROM " . CN_TERM_TAXONOMY_TABLE . " WHERE term_taxonomy_id = %d", $id ));
		
		// Delete the term if no taxonomies use it.
		if ( !$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM " . CN_TERM_TAXONOMY_TABLE . " WHERE term_id = %d", $id) ) )
		{
			$wpdb->query( $wpdb->prepare( "DELETE FROM " . CN_TERMS_TABLE . " WHERE term_id = %d", $id) );
		}
		
	}
	
	private function getUniqueSlug($slug, $term)
	{
		global $wpdb;
  		
		if (empty($slug))
		{
			//If the slug is empty assign the $slug the $term name
			$slug = $term;
		}
		
		// WP function -- formatting class
		sanitize_title(&$slug);
		
		$query = $wpdb->prepare( "SELECT slug FROM " . CN_TERMS_TABLE . " WHERE slug = %s", $slug );
		
		if ( $wpdb->get_var( $query ) )
		{
			$num = 2;
			do
			{
				$alt_slug = $slug . "-$num";
				$num++;
				$slug_check = $wpdb->get_var( $wpdb->prepare( "SELECT slug FROM " . CN_TERMS_TABLE . " WHERE slug = %s", $alt_slug ) );
			}
			while ( $slug_check );
			$slug = $alt_slug;
		}
		
		return $slug;
		
	}
}

?>