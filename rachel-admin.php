<?php namespace pg_flashcards; ?>
<?php
include dirname(__FILE__) . "/template.php";

// This is a rachel-admin.php file. It is an optional file that RACHEL uses to
// display your module on the RACHEL admin page to allow access to admin-specific files or functions.
// You should place this file in your module's directory if needed.
// As it uses the template.php file, you should be able to set your specific info there.

echo "<div class='adminmodule' data-moduletype='{$templ['module_type']}' data-title='{$templ['title']}' data-img_uri='{$templ['img_uri']}' data-index_loc='{$templ['index_loc']}'>";

// Place your admin code here.
// PG - note that this is 2016 method without the admin display within the engine.php file, move this to cardupload.php as engine when time allows. 

    // Allows creation of new flashcards
    
        exec("df {$_SERVER['DOCUMENT_ROOT']}", $exec_out, $exec_err);
        $str = rtrim($exec_out[1]);
        $pieces = preg_split('/\s+/', $str);
        $avail = $pieces[sizeof($pieces) - 3]; // in KB

        exec("df -h {$_SERVER['DOCUMENT_ROOT']}", $exec_out2, $exec_err); // nice printable format with -h
        $str = rtrim($exec_out2[1]);
        $pieces = preg_split('/\s+/', $str);
        $availPrint = $pieces[sizeof($pieces) - 3];
        if ($avail > 50000) { // allow for card upload if there is a reasonable amount of space available (50MB)
            echo "<div style='margin: 50px 0 50px 0; padding: 10px; border: 1px solid blue; background: aliceblue;'>";
            echo "<h4>{$templ['title']}</h4>";
            echo "<p>{$templ['description']}</p>";
            echo "<p style='display:inline;'>{$templ['disk_space_avail']}: $availPrint</p>";
            echo "<button id='cardUpload' style='display:inline; margin-left:8px;'>{$templ['card_upload']}</button>";
        } else {
            echo "<div style='margin: 50px 0 50px 0; padding: 10px; border: 1px solid red; background: #fee;'>";
            echo "<h4>{$templ['title']}</h4>";
            echo "<p>{$templ['description']}</p>";
            echo "<p style='display:inline;'>{$templ['disk_space_avail']}: $availPrint</p>";
            echo "<p>{$templ['no_space_for_new']}</p>";
            echo "<button id='cardUpload' style='display:none; margin-left:8px;'>{$templ['card_upload']}</button>";
        }
	echo "</div>";
	echo "</div>";

include $templ["js_loc"];
?>
