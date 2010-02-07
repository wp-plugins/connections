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
		
		//if ($_POST['save'] && $form->tokenCheck('entry_form', $_POST['token']))
		/*if ($_POST['save'])
		{
			check_admin_referer($form->getNonce('add_entry'), '_cn_wpnonce');
			echo $entryForm->processEntry();
			print_r($_SERVER['REQUEST_URI']);
			$_SERVER['REQUEST_URI'] = remove_query_arg(array('action'), $_SERVER['REQUEST_URI']);
			print_r($_SERVER['REQUEST_URI']);
			wp_redirect( wp_get_referer() . '?added=true' );
		}*/
	
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
						echo $entryForm->displayForm();
						echo '<input type="hidden" name="formId" value="entry_form" />';
						echo '<input type="hidden" name="token" value="' . $form->token("entry_form") . '" />';
						$form->close();
					?>
				</div>
			</div>
		</div>
		
	<?php }
}
?>