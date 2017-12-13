<?php namespace pg_flashcards; ?>
<?php
// Place module specific hints for RACHEL in template.php
// For a simple module, that will be all that is necessary.
include dirname(__FILE__) . "/template.php";

// Permit template.php to define whether we show anything on index.
// Remember that hiding in admin will cause rachel-admin.php to be hidden as well.
if (strtoupper($templ["hide_index"]) == "YES") { return; }

// Here we build core module structure with logo, title
// Note the availability of this data to jquery using data-
echo "
<!-- version={$templ['version']} -->
<div class='indexmodule' data-moduletype='{$templ['module_type']}' data-title='{$templ['title']}' data-img_uri='{$templ['img_uri']}' data-index_loc='{$templ['index_loc']}'>
<a href='{$templ['index_loc']}'>
<img src='{$templ['img_uri']}' alt='Logo de Piensa Grande'>
</a>
<h2><a href='{$templ['index_loc']}'>{$templ['title']}</a></h2>
";

// If you have any links or additional info to provide do it here, extend $templ in messages.php for multi-lingual.
// Comment out the description if not used.
// Piensa Grande - We display 3 rows with up to 4 items per row, based on which collections are installed.	
echo "<p>{$templ["description"]}</p>";

	$list = glob(dirname(__FILE__) . "/flashcards/*", GLOB_ONLYDIR);
	echo "<ul class=\"triple\">";
	$count = 0;
	foreach($list as $dir) {
		$count++;
		$dirname = pathinfo($dir, PATHINFO_BASENAME);
		if($count<=12) { echo "<li><a href=\"{$templ['index_loc']}?collection=flashcards/{$dirname}\">{$dirname}</li>\n"; }
			else { break; }
	}
	echo "</ul>\n";

echo "</div>";
?>
