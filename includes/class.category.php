<?php

class cnCategory
{
	/**
	 * Returns all the category terms
	 * @return object
	 */
	public function getCategories()
	{
		global $connections;
		
		return $connections->term->getTerms('category');
	}
}

?>