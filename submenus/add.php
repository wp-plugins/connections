<?php
    $entryForm = new entryForm();
?>

<div class="wrap">
	<div id="icon-connections" class="icon32">
        <br>
    </div>
	
	<h2>Connections : Add Entry</h2>
	
	<div class="form-wrap" style="width:600px; margin: 0 auto;">
		<h3><a name="new"></a>Add Entry</h3>
		
		<form action="<?php echo WP_PLUGIN_URL . '/connections/includes/process.php' ?>" method="post" enctype="multipart/form-data">
			<?php echo $entryForm->entryForm(); ?>
			<p class="submit">
				<input class="button-primary" type="submit" name="save" value="Add Address" />
			</p>
		</form>
	</div>
</div>