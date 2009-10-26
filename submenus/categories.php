<?php
function connectionsShowCategoriesPage()
{
	/*
	 * Check whether user can edit Settings
	 */
	if (!current_user_can('connections_edit_categories'))
	{
		wp_die('<p id="error-page" style="-moz-background-clip:border;
				-moz-border-radius:11px;
				background:#FFFFFF none repeat scroll 0 0;
				border:1px solid #DFDFDF;
				color:#333333;
				display:block;
				font-size:12px;
				line-height:18px;
				margin:25px auto 20px;
				padding:1em 2em;
				text-align:center;
				width:700px">You do not have sufficient permissions to access this page.</p>');
	}
	else
	{
		global $connections;
		$rowClass = '';
		
		//print_r($connections->retrieve->categories());
		//print_r($connections->retrieve->category('10'));
		
		function buildTableRow($parents, $level = 0)
		{
			foreach ($parents as $child)
			{
				$category = new cnCategory($child);
				
				$out .= buildTableRowHTML($child, $level);
				
				if (is_array($category->getChildren()))
				{
					++$level;
					$out .= buildTableRow($category->getChildren(), $level);
					--$level;
				}
				
			}
			
			$level = 0;
			return $out;
		}
		
		function buildTableRowHTML($term, $level)
		{
			global $rowClass;
			
			$category = new cnCategory($term);
			$pad = str_repeat('&#8212; ', max(0, $level));
			$rowClass = 'alternate' == $rowClass ? '' : 'alternate';
			
			$out = '<tr id="cat-' . $category->getId() . '" class="' . $rowClass . '">';
				$out .= '<th class="check-column"></th>';
				$out .= '<td class="name column-name"><a class="row-title" href="admin.php?page=connections_categories&action=edit&id=' . $category->getId() . '">' . $pad . $category->getName() . '</a><br />';
					$out .= '<div class="row-actions">';
						$out .= '<span class="edit"><a href="#">Edit</a> | </span>';
						$out .= '<span class="delete"><a href="#">Delete</a></span>';
					$out .= '</div>';
				$out .= '</td>';
				$out .= '<td class="description column-description">' . $category->getDescription() . '</td>';
				$out .= '<td class="slug column-slug">' . $category->getSlug() . '</td>';
				$out .= '<td class="posts column-posts num">' . $category->getCount() . '</td>';
			$out .= '</tr>';
			
			return $out;
		}
		
		function showForm($data = NULL)
		{
			$category = new cnCategory($data);
			
			$out = '<div class="form-field form-required connectionsform">';
				$out .= '<label for="cat_name">Category Name</label>';
				$out .= '<input type="text" aria-required="true" size="40" value="' . $category->getName() . '" id="category_name" name="category_name"/>';
				$out .= '<input type="hidden" value="' . $category->getID() . '" id="category_id" name="category_id"/>';
			$out .= '</div>';
			
			$out .= '<div class="form-field connectionsform">';
				$out .= '<label for="category_nicename">Category Slug</label>';
				$out .= '<input type="text" size="40" value="' . $category->getSlug() . '" id="category_slug" name="category_slug"/>';
			$out .= '</div>';
			
			$out .= '<div class="form-field connectionsform">';
				$out .= '<label for="category_parent">Category Parent</label>';
				$out .= '<select class="postform" id="category_parent" name="category_parent">';
					$out .= '<option value="">None</option>';
				$out .= '</select>';
			$out .= '</div>';
			
			$out .= '<div class="form-field connectionsform">';
				$out .= '<label for="category_description">Description</label>';
				$out .= '<textarea cols="40" rows="5" id="category_description" name="category_description">' . $category->getDescription() . '</textarea>';
			$out .= '</div>';
			
			echo $out;
		}
		
		if (isset($_GET['action']))
		{
			switch ($_GET['action'])
			{
				case 'edit':
					$result = $connections->retrieve->category(attribute_escape($_GET['id']));
					
					echo '<div class="wrap">';
						echo '<div class="form-wrap" style="width:600px; margin: 0 auto;">';
							echo '<h2><a name="new"></a>Edit Category</h2>';
					
							echo '<form action="admin.php?page=connections_categories" method="post" id="addcat" name="addcat">';
								showForm($result);
								echo '<p class="submit"><input class="button-primary" type="submit" value="Update Category" name="update" class="button"/></p>';
							echo '</form>';
					
						echo '</div>';
					echo '</div>';
				break;
			}
		}
		else
		{
			if (isset($_POST['add']))
			{
				$category = new cnCategory();
				
				$category->setName($_POST['category_name']);
				$category->setSlug($_POST['category_slug']);
				$category->setDescription($_POST['category_description']);
				
				echo $category->getName();
				echo $category->getSlug();
				echo $category->getDescription();
				$category->save();
				echo 'SAVE';
			}
			
			if (isset($_POST['update']))
			{
				$category = new cnCategory();
				
				$category->setID($_POST['category_id']);
				$category->setName($_POST['category_name']);
				$category->setSlug($_POST['category_slug']);
				$category->setDescription($_POST['category_description']);
				
				echo $category->getID();
				echo $category->getName();
				echo $category->getSlug();
				echo $category->getDescription();
				$category->update();
				echo 'UPDATE';
			}
			
			?>
				<div class="wrap nosubsub">
					<div class="icon32" id="icon-connections"><br/></div>
					<h2>Connections : Categories</h2>
					
					<div id="col-container">
					
						<div id="col-right">
							<div class="col-wrap">
								<form method="get" action="" id="posts-filter">
									<div class="tablenav">
										<div class="alignleft actions">
											<select name="action">
												<option selected="selected" value="">Bulk Actions</option>
												<option value="delete">Delete</option>
											</select>
											<input type="submit" class="button-secondary action" id="doaction" name="doaction" value="Apply"/>
										</div>
										
										<br class="clear"/>
									</div>
									
									<div class="clear"/></div>
								
									<table cellspacing="0" class="widefat fixed">
										<thead>
											<tr>
												<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
												<th class="manage-column column-name" id="name" scope="col">Name</th>
												<th class="manage-column column-description" id="description" scope="col">Description</th>
												<th class="manage-column column-slug" id="slug" scope="col">Slug</th>
												<th class="manage-column column-posts num" id="posts" scope="col">Entries</th>
											</tr>
										</thead>
									
										<tfoot>
											<tr>
												<th class="manage-column column-cb check-column" scope="col"><input type="checkbox"/></th>
												<th class="manage-column column-name" scope="col">Name</th>
												<th class="manage-column column-description" scope="col">Description</th>
												<th class="manage-column column-slug" scope="col">Slug</th>
												<th class="manage-column column-posts num" scope="col">Entries</th>
											</tr>
										</tfoot>
									
										<tbody class="list:cat" id="the-list">
											<?php
												echo buildTableRow($connections->retrieve->categories());
											?>
										</tbody>
									</table>
								</form>
								
								<div class="form-wrap">
								<p><strong>Note:</strong><br/>Deleting a category which has been assigned to an entry will reassign that entry as <strong>Uncategorized</strong>.</p>
								</div>
							
							</div>
						</div><!-- right column -->
						
						<div id="col-left">
							<div class="col-wrap">
								<div class="form-wrap">
									<h3>Add Category</h3>
										<form action="admin.php?page=connections_categories" method="post" id="addcat" name="addcat">
											<?php
												showForm();
											?>
										<p class="submit"><input type="submit" value="Save Category" name="add" class="button"/></p>
									</form>
								</div>
							</div>
						</div><!-- left column -->
					
					</div><!-- Column container -->
				</div>
			<?
		}
		
	}
}
?>