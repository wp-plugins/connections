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
		$form = new cnFormObjects();
		$categoryObjects = new cnCategoryObjects();
		$showPage = TRUE;
		
		switch ($_GET['action'])
		{
			case 'edit':
				$id = attribute_escape($_GET['id']);
				
				if ($form->tokenCheck('category_edit_' . $id, $_GET['token']))
				{
					$result = $connections->retrieve->category($id);
					
					echo '<div class="wrap">';
						echo '<div class="form-wrap" style="width:600px; margin: 0 auto;">';
							echo '<h2><a name="new"></a>Edit Category</h2>';
					
							echo '<form action="admin.php?page=connections_categories&action=update" method="post" id="addcat" name="updatecategory">';
								$categoryObjects->showForm($result);
								echo '<p class="submit"><input class="button-primary" type="submit" value="Update Category" name="update" class="button"/></p>';
							echo '</form>';
					
						echo '</div>';
					echo '</div>';
					
					unset($id);
					$showPage = FALSE;
				}
			break;
			
			case 'addcategory':
				if (isset($_POST['add']) && $form->tokenCheck($_POST['form_id'], $_POST['token']))
				{
					$category = new cnCategory();
					
					$category->setName($_POST['category_name']);
					$category->setSlug($_POST['category_slug']);
					$category->setParent($_POST['category_parent']);
					$category->setDescription($_POST['category_description']);
					
					$category->save();
				}
				$showPage = TRUE;
			break;
			
			case 'update':
				if (isset($_POST['update']) && $form->tokenCheck($_POST['form_id'], $_POST['token']))
				{
					$category = new cnCategory();
					
					$category->setID($_POST['category_id']);
					$category->setName($_POST['category_name']);
					$category->setParent($_POST['category_parent']);
					$category->setSlug($_POST['category_slug']);
					$category->setDescription($_POST['category_description']);
					
					$category->update();
				}
				$showPage = TRUE;
			break;
			
			case 'delete':
				$id = attribute_escape($_GET['id']);
				
				if ($form->tokenCheck('category_delete_' . $id, $_GET['token']))
				{
					$result = $connections->retrieve->category($id);
					$category = new cnCategory($result);
					$category->delete();
					
					unset($id);
				}
				$showPage = TRUE;
			break;
			
			case 'bulk_delete':
				if (isset($_POST['doaction']) && $form->tokenCheck($_POST['form_id'], $_POST['token']))
				{
					foreach ( (array) $_POST['category'] as $cat_ID )
					{
						$result = $connections->retrieve->category(attribute_escape($cat_ID));
						$category = new cnCategory($result);
						$category->delete();
					}
				}
				$showPage = TRUE;
			break;
			
		}
		
		if ($showPage === TRUE)
		{
			?>
				<div class="wrap nosubsub">
					<div class="icon32" id="icon-connections"><br/></div>
					<h2>Connections : Categories</h2>
					<?php echo $connections->displayMessages(); ?>
					<div id="col-container">
					
						<div id="col-right">
							<div class="col-wrap">
								<form method="post" action="admin.php?page=connections_categories&action=bulk_delete" id="posts-filter">
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
									
									<input type="hidden" name="form_id" value="category_action_form" />
									<input type="hidden" name="token" value="<?php echo $form->token('category_action_form') ?>" />
									
									<div class="clear"/></div>
								
									<table cellspacing="0" class="widefat fixed">
										<thead>
											<tr>
												<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
												<th class="manage-column column-name" id="name" scope="col">Name</th>
												<th class="manage-column column-description" id="description" scope="col">Description</th>
												<th class="manage-column column-slug" id="slug" scope="col">Slug</th>
												<th class="manage-column column-posts" id="posts" scope="col">Info</th>
											</tr>
										</thead>
									
										<tfoot>
											<tr>
												<th class="manage-column column-cb check-column" scope="col"><input type="checkbox"/></th>
												<th class="manage-column column-name" scope="col">Name</th>
												<th class="manage-column column-description" scope="col">Description</th>
												<th class="manage-column column-slug" scope="col">Slug</th>
												<th class="manage-column column-posts" scope="col">Info</th>
											</tr>
										</tfoot>
									
										<tbody class="list:cat" id="the-list">
											<?php
												echo $categoryObjects->buildCategoryRow('table', $connections->retrieve->categories());
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
										<form action="admin.php?page=connections_categories&action=addcategory" method="post" id="addcat" name="addcat">
											<?php
												$categoryObjects->showForm();
											?>
										<p class="submit"><input type="submit" value="Save Category" name="add" class="button"/></p>
									</form>
								</div>
							</div>
						</div><!-- left column -->
					
					</div><!-- Column container -->
				</div>
			<?php
		}
		
	}
}
?>