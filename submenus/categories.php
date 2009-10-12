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
		
		?>
			<div class="wrap nosubsub">
				<div class="icon32" id="icon-connections"><br/></div>
				<h2>Connections : Entry List</h2>
				
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
										<input type="hidden" value="6f87843e9c" name="_wpnonce" id="_wpnonce"/><input type="hidden" value="/wp-admin/categories.php" name="_wp_http_referer"/>
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
											<th class="manage-column column-posts num" id="posts" scope="col">Posts</th>
										</tr>
									</thead>
								
									<tfoot>
										<tr>
											<th class="manage-column column-cb check-column" scope="col"><input type="checkbox"/></th>
											<th class="manage-column column-name" scope="col">Name</th>
											<th class="manage-column column-description" scope="col">Description</th>
											<th class="manage-column column-slug" scope="col">Slug</th>
											<th class="manage-column column-posts num" scope="col">Posts</th>
										</tr>
									</tfoot>
								
									<tbody class="list:cat" id="the-list">
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
								
								<form class="add:the-list: validate" action="#" method="post" id="addcat" name="addcat">
								
								<div class="form-field form-required">
									<label for="cat_name">Category Name</label>
									<input type="text" aria-required="true" size="40" value="" id="cat_name" name="cat_name"/>
								</div>
								
								<div class="form-field">
									<label for="category_nicename">Category Slug</label>
									<input type="text" size="40" value="" id="category_nicename" name="category_nicename"/>
								</div>
								
								<div class="form-field">
									<label for="category_parent">Category Parent</label>
									<select class="postform" id="category_parent" name="category_parent">
										<option value="">None</option>
									</select>
								</div>
								
								<div class="form-field">
									<label for="category_description">Description</label>
									<textarea cols="40" rows="5" id="category_description" name="category_description"></textarea>
								</div>
								
								<p class="submit"><input type="submit" value="Add Category" name="submit" class="button"/></p>
								</form>
							</div>
						</div>
					</div><!-- left column -->
				
				</div><!-- Column container -->
			</div>
		<?
	}
}
?>