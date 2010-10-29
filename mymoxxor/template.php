<?php 
/* This template is currently used on the http://www.moxxme.com/mymoxxor/ page */ 

/* Websites */
$goto = htmlspecialchars($_GET["goto"]);
$username = $entry->getDepartment();

if ($username == '') 
	$username = "moxxme";

$url = $goto . $username;
$outputStart = '<a href="' . $url . '" target="_blank" title="Distributor MyMoxxor.com Website">';
$outputEnd = '</a>';

/* Cats */
$catCountryBln = false;
$catCountryMore = '';
$catCountry = '';

$catLanguageBln = false;
$catLanguageMore = '';
$catLanguage = '';

if (is_array($entry->getCategory())) {
	foreach ($entry->getCategory() as $catRow){
		if ($catRow->parent == 2) {
			if (!$catCountryBln) {
				$catCountry = '<span class="cn_category_label"><strong>Country:</strong></span> ';
			}
			$catCountry .= $catCountryMore . $catRow->name;
			if (!$catCountryBln) {
				$catCountryMore = ', ';
				$catCountryBln = true;
			}
		}
	
		if ($catRow->parent == 3) {
			if (!$catLanguageBln) {
				$catLanguage = '<span class="cn_category_label"><strong>Language:</strong></span> ';
			}
			$catLanguage .= $catLanguageMore . $catRow->name;
			if (!$catLanguageBln) {
				$catLanguageMore = ', ';
				$catLanguageBln = true;
			}
		}
	} // END foreach
}
?>
<div class="cn-entry" style="-moz-border-radius:4px; background-color:#00000; border:1px solid #DCAB28; margin: 6px 8px; padding:6px; position: relative; width:46%; float: left; min-height: 122px;">
	<a name="<?php echo $entry->getId() ?>"></a>
	<div>
		<span style="float: left; padding: 5px;"><?php echo $outputStart; ?><?php echo $entry->getThumbnailImage() ?><?php echo $outputEnd; ?></span>

		<div>
			<span style="font-size:larger;font-variant: small-caps"><strong><?php echo $entry->getFullFirstLastNameBlock() ?></strong></span><a href="#<?php echo $entry->getId() ?>" class="anchor_me" title="Permalink for sharing - Click and copy the page url"><span class="displace">Permalink</span></a><br />
			<div style="margin-bottom: 1px;">
				<strong>Website:</strong> <?php echo $outputStart . 'www.mymoxxor.com/' . $username . $outputEnd; ?><br />
				<?php echo $catCountry . '<br />'; ?>
				<?php echo $catLanguage; ?>
			</div>
		</div>	
	</div>
	<div style="clear:both"></div>
</div>