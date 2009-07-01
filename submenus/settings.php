<?php
	
	/*
	 * Check whether user can edit roles
	 */
	if (!current_user_can('connections_change_settings'))
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

?>
	<div class="wrap">
		<div id="icon-connections" class="icon32">
	        <br>
	    </div>
		
		<h2>Connections : Settings</h2>
		
		<div id="message" class="updated fade below-h2">
			<p><strong>In the next version you will be able to set default for the images sizes.</strong></p>
		</div>
		
		
	</div>
	<div class="clear"></div>