<?php

class cnCategory
{
	private $id;
	private $name;
	private $slug;
	private $termGroup;
	private $taxonomy;
	private $description;
	private $parent;
	private $count;
	private $children;
	
	function __construct($data = NULL)
	{
		$this->id = $data->term_id;
		$this->name = $data->name;
		$this->slug =$data->slug;
		$this->termGroup = $data->term_group;
		$this->taxonomy = $data->taxonomy;
		$this->description = $data->description;
		$this->parent = $data->parent;
		$this->count = $data->count;
		$this->children = $data->children;
	}
    
    /**
     * Returns $children.
     *
     * @see cnCategory::$children
     */
    public function getChildren() {
        return $this->children;
    }
    
    /**
     * Sets $children.
     *
     * @param object $children
     * @see cnCategory::$children
     */
    public function setChildren($children) {
        $this->children = $children;
    }
    
    /**
     * Returns $count.
     *
     * @see cnCategory::$count
     */
    public function getCount() {
        return $this->count;
    }
    
    /**
     * Sets $count.
     *
     * @param object $count
     * @see cnCategory::$count
     */
    public function setCount($count) {
        $this->count = $count;
    }
    
    /**
     * Returns $description.
     *
     * @see cnCategory::$description
     */
    public function getDescription() {
        return $this->description;
    }
    
    /**
     * Sets $description.
     *
     * @param object $description
     * @see cnCategory::$description
     */
    public function setDescription($description) {
        $this->description = $description;
    }
    
    /**
     * Returns $id.
     *
     * @see cnCategory::$id
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Sets $id.
     *
     * @param object $id
     * @see cnCategory::$id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns $name.
     *
     * @see cnCategory::$name
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Sets $name.
     *
     * @param object $name
     * @see cnCategory::$name
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * Returns $parent.
     *
     * @see cnCategory::$parent
     */
    public function getParent() {
        return $this->parent;
    }
    
    /**
     * Sets $parent.
     *
     * @param object $parent
     * @see cnCategory::$parent
     */
    public function setParent($parent) {
        $this->parent = $parent;
    }
    
    /**
     * Returns $slug.
     *
     * @see cnCategory::$slug
     */
    public function getSlug() {
        return $this->slug;
    }
    
    /**
     * Sets $slug.
     *
     * @param object $slug
     * @see cnCategory::$slug
     */
    public function setSlug($slug) {
        $this->slug = $slug;
    }
    
    /**
     * Returns $taxonomy.
     *
     * @see cnCategory::$taxonomy
     */
    public function getTaxonomy() {
        return $this->taxonomy;
    }
    
    /**
     * Sets $taxonomy.
     *
     * @param object $taxonomy
     * @see cnCategory::$taxonomy
     */
    public function setTaxonomy($taxonomy) {
        $this->taxonomy = $taxonomy;
    }
    
    /**
     * Returns $termGroup.
     *
     * @see cnCategory::$termGroup
     */
    public function getTermGroup() {
        return $this->termGroup;
    }
    
    /**
     * Sets $termGroup.
     *
     * @param object $termGroup
     * @see cnCategory::$termGroup
     */
    public function setTermGroup($termGroup) {
        $this->termGroup = $termGroup;
    }
	
	public function save()
	{
		global $connections;
		
		$attributes['slug'] = $this->slug;
		$attributes['description'] = $this->description;
		$attributes['parent'] = $this->parent;
		
		$connections->term->addTerm($this->name, 'category', $attributes);
	}
    
	public function update()
	{
		global $connections;
		
		$attributes['name'] = $this->name;
		$attributes['slug'] = $this->slug;
		$attributes['description'] = $this->description;
		$attributes['parent'] = $this->parent;
		
		$connections->term->updateTerm($this->id, 'category', $attributes);
	}
}

?>