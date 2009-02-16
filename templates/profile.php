<div class="cnitem" id="cn <?php echo $entry->getId() ?>" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;">
	<div>
		<span style="float: left;"><?php echo $entry->getProfileImage() ?></span>

		<div>
			<span class="name" id="<?php echo $entry->getId() ?>" style="font-size:larger;font-variant: small-caps"><strong><?php echo $entry->getFullFirstLastName() ?></strong></span><br />
			<div style="margin-bottom: 20px;">
				<?php echo $entry->getTitleBlock() ?><br />
				
				<div class="org">
					<?php echo $entry->getOrganizationBlock() ?><br />
					<?php echo $entry->getDepartmentBlock() ?><br />
				</div>
			</div>
			<?php echo $entry->getBio() ?>
		</div>
			
	</div>
	<div style="clear:both"></div>
</div>