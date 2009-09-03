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
		$entryForm = new entryForm();
		$form = new formObjects();
		
		if ($_POST['save'] && $_SESSION['cn_session']['formTokens']['entry_form']['token'] === $_POST['token'])
		{
			echo $entryForm->processEntry();
		}
	
	?>
		<div class="wrap">
			<div id="icon-connections" class="icon32">
		        <br>
		    </div>
			
			<h2>Connections : Add Entry</h2>
			
			<div class="form-wrap" style="width:600px; margin: 0 auto;">
				<h3><a name="new"></a>Add Entry</h3>
				
				<?php 
					$attr = array(
								 'action' => 'admin.php?page=connections_add&action=add',
								 'method' => 'post',
								 'enctype' => 'multipart/form-data',
								 );
					
					echo $form->open($attr);
					echo $entryForm->entryForm();
					echo '<input type="hidden" name="formId" value="entry_form" />';
					echo '<input type="hidden" name="token" value="' . $form->token("entry_form") . '" />';
				?>
					<p class="submit">
						<input class="button-primary" type="submit" name="save" value="Add Address" />
					</p>
				<?php echo $form->close(); ?>
			</div>
		</div>
		
	<?php }
}
?>