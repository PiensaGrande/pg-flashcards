<?php namespace pg_flashcards; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<?php require_once("includes/functions.php"); ?>
<?php
$title = "Flashcards" ;
if(isset($_REQUEST['collection'])) { 
	$title_exploded =  explode("/", $_REQUEST['collection']);
	$title .= " - " . end($title_exploded);
}
?>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="images/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="images/manifest.json">
	<link rel="mask-icon" href="images/safari-pinned-tab.svg" color="#ff0000">
	<meta name="theme-color" content="#a6a6a6">
	<link rel="stylesheet" type="text/css" media="all" href="css/whiteboard.css" />
	<script src="js/1.9.1/jquery.min.js"></script>
<!-- We are gratefully using github.com/dabeng/OrgChart Copyright (c) 2016 dabeng to provide navigation of tree. -->
<!-- Both css and jquery plugin have been modified.  -->
	<link rel="stylesheet" type="text/css" media="all" href="css/jquery.orgchart.css" />
	<link rel="stylesheet" href="/css/magnific/magnific-popup.css"/>
	<script src="js/jquery.orgchart.js"></script>
<title><?php echo $title; ?></title>
</head>

<!-- TODO:      test mode could get sounds for correct and incorrect to be played as you answer.
		delay GET variable sets time to show answer, default is 1200, 5000 is alot but js should allow the user to change.
		alternately, a lock icon could show up to allow the user to study the answer (for math for example).
		add ability to upload zip to create a new subdirectory.	
		improve error handling throughout, especially where groups are creating multiple iterators.
		getcwd() and __DIR__ should go into the buildSoundUrl() function to deal with the extension moving in the future.
		one option is to include in this file an audioFunctions.php which returns the cwd of the audio files when asked.
-->

<body>
    <a href="/" id="pg-rachel"></a>
    <div class="menubar">
      <a href="whiteboard.php">Flashcards [HOME]</a>
    </div>

 <?php
        $buttonsonly = "no" ;
	$buttondiv = "";
	$group = get_group(); // check if we are consolidating groups of directories.
        $myPath = get_path(); // use passed dir from GET if passed
	$testmode = get_testmode();  // check if we are testing.
	$delay = get_delay();        // override for delay to show answer between cards.
	$dirname = pathinfo($myPath, PATHINFO_BASENAME);
        $directories = glob($myPath . '/*' , GLOB_ONLYDIR);
        if (empty($directories)) {
            $images = glob($myPath . '/*.{jpg,png,gif,svg,div}', GLOB_BRACE);
            $json = build_images($images,$myPath);
	    $buttonsonly = "no";
        } elseif($group == "yes") {
			 $dirList = new \RecursiveDirectoryIterator($myPath);
			 if (empty($dirList)) { }  // add proper error handling here. echo "No dirlist.\n"; } 
			 $iterator = new \RecursiveIteratorIterator($dirList);
			 if (empty($iterator)) { } // add proper error handling here. echo "No iterator.\n";}
			 $image_iterator = new \RegexIterator($iterator, '/^.+\.(?:jpg|gif|png|svg|div)$/i', \RecursiveRegexIterator::GET_MATCH); 
			 if (empty($image_iterator)) { } // add proper error handling here set $json, echo "No images.\n"; } else {
				else {
			 		foreach ($image_iterator as $key=>$val) {
					$images[] = $key;
					}
			 		$json = build_images($images,$myPath); 
				}
			 $buttonsonly = "no";
	       }
        else { 
		$buttondiv = "<div id=\"buttondiv\" style=\"display:none\">\n";
		$buttondiv .="<ul id=\"flash\"><li>{$dirname}\n"; // this sets a top level always because of restriction in orghcart.
		$buttondiv .= build_buttons_as_UL($directories,0); 
	        $buttondiv .= "</li></ul></div>\n";
                $buttonsonly = "yes" ;
        	}
    ?>

    
   <div id="main">
   <?php echo $buttondiv; ?> 
            <div id="container" style="display:none">
		<div id="board-container">
		<span id="collectionName" style="display:none"><?php echo $myPath ?></span>
		<span id="numright" style="display:none"></span>
		<span id="numwrong" style="display:none"></span>
                <img id="check-box" src="images/check-box.png" style="display:none"/>
                <img id="x-box" src="images/x-box.png" style="display:none"/>
                <img id="board" src="images/board.png" />
                <img id="item-image" src="#" class="board-center"/>
                <div id="itemDiv" class="board-center" style="display:none"/></div>
		<p class="big"><br/><br/></p>
                <p id="item-name" class="small"></p>
                <img id="next" src = "images/arrowRight-blue.png" />
		<div id="test-options">
			<ul style="list-style:none;">
				<li>A)<span id="65">option 1</span></li>
		    		<li>B)<span id="66">option 2</span></li>
		    		<li>C)<span id="67">option 3</span></li>
		    		<li>D)<span id="68">option 4</span></li>
			</ul>
		</div>
		<div id="percent-correct" style="display:none"></div>
		</div>
            </div>
          <img id="testMe" src="images/testMe.png" style="display:none"/>
          <img id="teacher" src="images/owlTeacher.png" />
    </div>
        
</body>
    
<script>
$(document).ready(function() {

<?php if($buttonsonly == "yes") { 
        $supp_js = build_button_js();
	echo $supp_js;
	echo "});</script>";
	} elseif($testmode == "no") { 
		$supp_js = build_flashcard_js($json,$delay);
		echo $supp_js;
	} else {                                   
		$supp_js = build_test_js($json); 
		echo $supp_js;
	  }                                        // expand here to support more test modes.
?>
        
    
</html>
