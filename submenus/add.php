<?php
function connectionsShowAddPage()
{
	/*
	 * Check whether user can add entries
	 */
	if (!current_user_can('connections_add_entry'))
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
		
		$entryForm = new cnEntryForm();
		$form = new cnFormObjects();
		
		$connections->displayMessages();
	?>
		<div class="wrap">
			<div id="icon-connections" class="icon32">
		        <br>
		    </div>
			
			<h2>Connections : Add Entry</h2>
			
			<div class="form-wrap" style="width:880px; margin: 0 auto;">
				
				<div id="poststuff" class="metabox-holder has-right-sidebar">
					<?php 
						$attr = array(
									 'action' => 'admin.php?page=connections_add&action=add',
									 'method' => 'post',
									 'enctype' => 'multipart/form-data',
									 );
						
						$form->open($attr);
						$connections->tokenField('add_entry');
						$entryForm->displayForm();
						$form->close();
					?>
				</div>
			</div>
		</div>
		
	<?php }
}
?>