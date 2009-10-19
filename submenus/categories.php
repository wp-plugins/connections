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
			
		//print_r($connections->category->getCategories());
		
		function buildTableRow($parents, $level = 0)
		{
			foreach ($parents as $child)
			{
				$out .= buildTableRowHTML($child, $level);
				
				if (is_array($child->children))
				{
					++$level;
					$out .= buildTableRow($child->children, $level);
					--$level;
				}
				
			}
			
			$level = 0;
			return $out;
		}
		
		function buildTableRowHTML($term, $level)
		{
			global $rowClass;
			
			$pad = str_repeat('&#8212; ', max(0, $level));
			$rowClass = 'alternate' == $rowClass ? '' : 'alternate';
			
			$out = '<tr id="cat-' . $term->term_id . '" class="' . $rowClass . '">';
				$out .= '<th class="check-column"></th>';
				$out .= '<td class="name column-name"><a class="row-title" href"#>' . $pad . $term->name . '</a><br />';
					$out .= '<div class="row-actions">';
						$out .= '<span class="edit"><a href="#">Edit</a> | </span>';
						$out .= '<span class="delete"><a href="#">Delete</a></span>';
					$out .= '</div>';
				$out .= '</td>';
				$out .= '<td class="description column-description">' . $term->description . '</td>';
				$out .= '<td class="slug column-slug">' . $term->slug . '</td>';
				$out .= '<td class="posts column-posts num">' . $term->count . '</td>';
			$out .= '</tr>';
			
			return $out;
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
											/*foreach ($connections->category->getCategories() as $row)
											{
												echo '<tr id="cat-' . $row->term_id . '">';
													echo '<th class="check-column"></th>';
													echo '<td class="name column-name">' . $row->name . '</td>';
													echo '<td class="description column-description">' . $row->description . '</td>';
													echo '<td class="slug column-slug">' . $row->slug . '</td>';
													echo '<td class="posts column-posts num">' . $row->count . '</td>';
												echo '</tr>';
											}*/
											echo buildTableRow($connections->category->getCategories());
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
								
								<form class="add:the-list: validate" action="#" method="post" id="addcat" name="addcat">
								
								<div class="form-field form-required connectionsform">
									<label for="cat_name">Category Name</label>
									<input type="text" aria-required="true" size="40" value="" id="cat_name" name="cat_name"/>
								</div>
								
								<div class="form-field connectionsform">
									<label for="category_nicename">Category Slug</label>
									<input type="text" size="40" value="" id="category_nicename" name="category_nicename"/>
								</div>
								
								<div class="form-field connectionsform">
									<label for="category_parent">Category Parent</label>
									<select class="postform" id="category_parent" name="category_parent">
										<option value="">None</option>
									</select>
								</div>
								
								<div class="form-field connectionsform">
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