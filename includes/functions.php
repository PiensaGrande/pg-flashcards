<?php namespace pg_flashcards; ?>
<?php

function getPost($key, $default){
        if (isset($_POST[$key])) {
            return $_POST[$key];
        } 
        return $default;
    }

function getRequest($key, $default){
        if (isset($_REQUEST[$key])) {
            return $_REQUEST[$key];
        }
        return $default;
    }


function get_path() {
	// Set directory to collection passed, need a check here for validity.    
        $collection = "./flashcards";
	if ((isset($_GET["collection"])) && file_exists($_GET["collection"])) {
		$collection = $_GET["collection"];	
            } elseif(isset($_POST["collection"])) { $collection = $_POST["collection"]; }
    return $collection;
}

function get_testmode() {

        if (isset($_GET["testmode"])) {
                $testmode = $_GET["testmode"];      // set testmode if passed, no otherwise.
            } else $testmode = "no";
    return $testmode;
}

function get_group() {

        if (isset($_GET["group"])) {
                $group = $_GET["group"];      // set group if passed, no otherwise.
            } else $group = "no";
    return $group;
}

function get_delay() {

        if (isset($_GET["delay"])) {
                $delay = (int) $_GET["delay"];      // set default delay if passed, 1500 otherwise.
            } else $delay = 1500;
    return $delay;
}

function get_Card_dirs()  {
    // this needs to be written to return all directories where card files are permitted.
	$dirs = array();
	$dirList = new \RecursiveDirectoryIterator("./flashcards");
        if (empty($dirList)) { }  // add proper error handling here. echo "No dirlist.\n"; }
        $iterator = new \RecursiveIteratorIterator($dirList);
        if (empty($iterator)) { } // add proper error handling here. echo "No iterator.\n";}
	foreach($iterator as $val){
		if($val->isDir()) {
			$subdir = glob($val . "/*", GLOB_ONLYDIR);
			if((empty($subdir)) && (is_writable($val))) { array_push($dirs,$val); }
		}
	}
	array_push($dirs,'new');
	return $dirs;
}

function create_cardP($filename,$question,$answer,$no1,$no2,$no3) {
	$output = "" ;
	$card = "";
	$card .= '<p> ' . $question . '</p> <script>setOptions(\' ' . $answer . '\',\' ' . $no1 . '\',\' '  . $no2 . '\',\' '  . $no3 . '\');</script>';
	if(file_put_contents($filename,$card)) {
		$output .= '<div>Card ' . $filename . ' successfully created.</div>';
		$_REQUEST['question']='';
		$_REQUEST['answer']='';
		$_REQUEST['no1']='';
		$_REQUEST['no2']='';
//		$_REQUEST['no3']='';
//		$REQUEST['filename']='';
	} else { $output .= '<div>Card ' . $filename . ' could not be created.</div>'; }

	return $output;
}

function create_cardI($target,$tmp_imagename) {
	$output = "";
	if(move_uploaded_file($tmp_imagename, $target)) {
		$output .= '<div>Card ' . $target . ' successfully created.</div>';
	} else { $output .= '<div>Card ' . $target . ' could not be created.</div>'; }

	return $output;
}

function create_cardC($filename,$tmp_imagename,$type,$question,$answer,$no1,$no2,$no3) {
        $output = "" ;
        $card = "";
	$file = file_get_contents($tmp_imagename);
	$file = base64_encode($file);
//	$card .= '<img src="data:image/png;base64,' . $file ;
	$card .= '<img src="data:' . $type .';base64,' . $file ;
        $card .= '">
<p> ' . $question . '</p> <script>setOptions(\' ' . $answer . '\',\' ' . $no1 . '\',\' '  . $no2 . '\',\' '  . $no3 . '\');</script>';
        if(file_put_contents($filename,$card)) {
                $output .= '<div>Card ' . $filename . ' successfully created.</div>';
                $_REQUEST['question']='';
                $_REQUEST['answer']='';
                $_REQUEST['no1']='';
                $_REQUEST['no2']='';
//              $_REQUEST['no3']='';
//              $REQUEST['filename']='';
        } else { $output .= '<div>Card ' . $filename . ' could not be created.</div>'; }

        return $output;

}

function get_Parent_dirs() {
    // this needs to be written to return all directories with no card files.
	$dirs = array();
	$dirs[] = '';
	$dirList = new \RecursiveDirectoryIterator("./flashcards");
        if (empty($dirList)) { }  // add proper error handling here. echo "No dirlist.\n"; }
        $iterator = new \RecursiveIteratorIterator($dirList);
        if (empty($iterator)) { } // add proper error handling here. echo "No iterator.\n";}
        foreach($iterator as $val){
                if(($val->isDir())&& (basename($val)!='.')) {
			$images = glob($val . '/*.{jpg,png,gif,svg,div}', GLOB_BRACE);
                        if((empty($images)) && (is_writable($val))) { array_push($dirs,dirname($val)); }
                }       
        }
	
        return $dirs;
}

function build_images($list,$path) {
    $urlpath = "" ;
    // echo "in build_images \n" ;
    $jsonobj = "{\"objName\": \"cosa\"," ;
    $jsonobj .= "\"costumes\": [" ;
    $first = 1;
    foreach($list as $image) {
	// we made this if we get strange characters in filenames (adjust for locale)
	// setlocale(LC_ALL,'en_US.UTF-8'); 
        $costumeName = pathinfo($image, PATHINFO_FILENAME);
        $costumePath = pathinfo($image, PATHINFO_DIRNAME);
	$imageExt = pathinfo($image, PATHINFO_EXTENSION);
        $printableName = str_replace("_", " ", $costumeName);
        $imageUrl = $urlpath . $image;
        $soundUrl = buildSoundUrl($costumeName,$costumePath); 
        // echo $image . " " . $costumeName . " " . $imageUrl . " " . $soundUrl . "\n";
        if ($soundUrl == "missing") { //do nothing right now
        } else {
            if ($first){ $jsonobj .= "{"; $first = 0; } else { $jsonobj .= "},{"; }
            $jsonobj .= "\"costumeName\": \"" . $printableName . "\"," ;
            $jsonobj .= "\"imageUrl\": \"" . $imageUrl . "\"," ;
            $jsonobj .= "\"imageExt\": \"" . $imageExt . "\"," ;
            $jsonobj .= "\"soundUrl\": \"" . $soundUrl . "\"" ;
           }
    }
    $jsonobj .= "}" ;
    $jsonobj .= "]}" ; 
    return $jsonobj ;
}

function build_buttons_as_UL($list,$count) {
    $output =  "<ul id=\"org{$count}\" class=\"anchorbuttons\">" ;
    foreach($list as $dir) {

// if there are no more subdirectories, make a button, else check for subs
	$subdir = glob($dir . "/*", GLOB_ONLYDIR);
	if (empty($subdir)) {
		$dirname = pathinfo($dir, PATHINFO_BASENAME);
		$output .=  "<li id=\"---->\"><a href=\"whiteboard.php?collection={$dir}\" class=\"anchorasabutton\">{$dirname}~whiteboard.php?collection={$dir}</a></li>\n";
	}
	else {
		$dirname = pathinfo($dir, PATHINFO_BASENAME);
       		$output .= "<li class=\"Subdir{$count}\">{$dirname}~whiteboard.php?group=yes&collection={$dir}\n"; 
		$output .= build_buttons_as_UL($subdir, $count+1);  // recursion, increase count to allow proper css settings
		$output .= "</li>\n";
	}
    }
    $output .= "</ul>\n";
    return $output;
}

// We are gratefully using gitbub.com/dabeng/OrgChart Copyright (c) 2016 dabeng to provide navigation of tree.

function build_button_js() {
	$output = "";
	$output .= "
	var c = 0;
          \$(\"#main\").orgchart({
             'data' : \$('#flash'),
             'direction' : 'l2r',
             'nodeContent' : 'id',
             'createNode': function(\$node, data) {
                              var newname = data.name.split('~');
                        if (newname.length > 1) { var url = newname[1];
                              \$node.on('click', function() { location.href = url; });
                                }
                        }
          }); 
	\$('.orgchart').addClass('noncollapsable');

        \$.each(\$('div.title'), function () {
           var newname = \$(this).text().split('~');
           \$(this).text(newname[0]);
              });
        \$.each(\$('ul#org0 li a'), function () {
           var newname = \$(this).text().split('~');
           \$(this).text(newname[0]);
              });
	\$('#teacher').css('max-height','120px');
	\$('#teacher').css('float','left');
	\$('#teacher').css('cursor','inherit');
	\$('#teacher').css('position','relative');
	\$('#teacher').css('top','0%');
	\$('#teacher').css('left','0%');
	\$('.orgchart').css('float','left');
	
	";
        return($output);
}

function build_flashcard_js($json,$delay) {
        $output = "";
        $output .= "
        \$(\"#container\").toggle(1000);
        var obj = jQuery.parseJSON('$json') ;
        var i = \"\";
        var j = \"\";
        var name = \"\";
        var snd;
	var suspend = false;
   
        contentType = newItem();
        \$(\"#testMe\").toggle(400);
        \$(\"#item-name\").toggle(400);
        \$(\"#test-options\").toggle();

        \$(\"#testMe\").on('click', function() { location.href = $(location).attr(\"href\") + \"&testmode=abc\"; });

	
        \$(\"#next\").on( \"click\", function() {
					  suspend = true;
					  \$(\"#next\").toggle();
                                          \$(\"#item-name\").toggle(200);
                                          if (snd.readyState != 0) { snd.play();}
         	                          setTimeout( function() { \$(\"#item-name\").toggle(); newItem(); \$(\"#next\").toggle(); suspend = false;}, {$delay});
                          });
   
        \$(\"#teacher\").on( \"click\", function() {
					  suspend = true;
                                          \$(\"#item-name\").toggle(200);
                                          if (snd.readyState != 0) { snd.play();}
                                          setTimeout( function() { \$(\"#item-name\").toggle(200); suspend = false;},1000);
                          }); 


	\$(\"body\").on(\"keydown\",function (event){
				      if(suspend == true) { return; }
					else {
                                        if(event.which != 39) { return; }
                                          else {
                                                \$(\"#next\").click();
                                          }
					}
                                        });


    function newItem(){
        while (i === j) { 
            j = Math.floor( Math.random() * obj.costumes.length );
        }
        i = j;
        snd = new Audio(obj.costumes[i].soundUrl); 
        \$(\"#item-name\").text(obj.costumes[i].costumeName);
	if(obj.costumes[i].imageExt == \"div\") {
        	\$(\"#itemDiv\").load(obj.costumes[i].imageUrl);
		\$(\"#itemDiv\").show(); 
		\$(\"#item-image\").hide();
	} else {
        	\$(\"#item-image\").attr(\"src\", obj.costumes[i].imageUrl);
		\$(\"#itemDiv\").hide(); 
		\$(\"#item-image\").show();
	}
	return obj.costumes[i].imageExt;
    } 

    function printInfo(i, jsonobj) {
        console.log (jsonobj.costumes[i].costumeName);
        console.log (jsonobj.costumes[i].imageUrl);
        console.log (jsonobj.costumes[i].soundUrl);
    }

});
</script>

<script>
    function setOptions(flashcardsYes,flashcardsNo1,flashcardsNo2,flashcardsNo3) {
        \$(\"#item-name\").text(flashcardsYes);
    }

</script>

" ;  // end output creation. Note that we end the document ready function early in this mode to place 1 script available to <script> tags within .div docs.

   return $output;
}

function build_test_js($json) {
    $output = "";
    $output .= "
        \$(\"#container\").toggle(1000);
        var obj = jQuery.parseJSON('$json') ;
        var i = 0;
        var j = 0;
        var name = \"\";
        var snd;
        var correct_score = 0;
        var incorrect_score = 0;
	var clength = obj.costumes.length ;
	var item_arr = [];
	var suspend = false;

        for (var x=0;x<clength;x++){
               item_arr[x]=x;
        }
	item_arr = shuffleArray(item_arr);
  
	contentType = newItem();
        \$(\"#item-image\").toggleClass(\"board-center board-left\");
        \$(\"#itemDiv\").toggleClass(\"board-center board-left\");
        \$(\"#item-name\").toggle(400);
        \$(\"#next\").toggle();
  
        \$(\"#next\").on( \"click\", function() {
                                          \$(\"#next\").toggle();
                                          \$(\"#item-name\").toggle(200);
                                          if (snd.readyState != 0) { snd.play();}
                                          setTimeout( function() { \$(\"#item-name\").toggle(); newItem(); \$(\"#next\").toggle();}, 1200);
                          });
  

        \$(\"#test-options li span\").on(\"click\",function (){
					if (suspend == true) { return; }
						else {
               			                        if ($(this).hasClass(\"correct\")) { 
                               		                 displayAnswer(1,0);
                                       		 } else { 
                                                	displayAnswer(0,1);
						   }
						}
                                        });

	\$(\"#teacher\").on( \"dblclick\", function() {
                                          \$(\"#teacher\").toggle(200);
                          });

        \$(\"body\").on(\"keydown\",function (event){
					if(suspend == true) { return; }
					  else {
						\$(\"#\" + event.which).click();
					  }
                                        });

    function displayAnswer(r,w) {
	suspend = true;
        if (snd.readyState != 0) { snd.play();}
	\$(\"#test-options li span.incorrect\").toggle();
        setTimeout(function() { 
	\$(\"#test-options li span.incorrect\").toggle(); 
	suspend = false;
	updateScore(r,w);},1200);
    }

    function newItem(){
	var arr = [] ;
	i = item_arr[j] ;
	for (var x=0;x<i;x++){
                arr[x]=x;
        }

	for (var x=i+1;x<clength;x++){
		arr[x-1]=x;
	}
	var mno = shuffleArray(arr);
        snd = new Audio(obj.costumes[i].soundUrl);
        \$(\"#item-name\").text(obj.costumes[i].costumeName);
	if(obj.costumes[i].imageExt == \"div\") {
                \$(\"#itemDiv\").load(obj.costumes[i].imageUrl); 
                \$(\"#itemDiv\").show();
                \$(\"#item-image\").hide();
        } else {
                \$(\"#item-image\").attr(\"src\", obj.costumes[i].imageUrl);
                \$(\"#itemDiv\").hide();
                \$(\"#item-image\").show();
        }
	setOptions(obj.costumes[i].costumeName,obj.costumes[mno[0]].costumeName,obj.costumes[mno[1]].costumeName,obj.costumes[mno[2]].costumeName);
	j++;
        return obj.costumes[i].imageExt;
    }


    function updateScore(amtup,amtdown) {
      correct_score += amtup;
      incorrect_score += amtdown;
      \$(\"#numright\").text(correct_score);
      \$(\"#numwrong\").text(incorrect_score);
      if (correct_score + incorrect_score == clength) { finalScore(); }
		else { newItem(); }
    }

    function finalScore() {
	suspend = true;
	\$(\"#numright\").text(correct_score);
	\$(\"#numwrong\").text(incorrect_score);
	percent_correct = parseFloat(100*(correct_score/(correct_score+incorrect_score)));
	\$(\"#percent-correct\").text(percent_correct.toFixed(2) + \" %\");
	\$(\"#check-box, #x-box, #numright, #numwrong, #test-options, #percent-correct\").toggle(); 
    }

    function printInfo(i, jsonobj) {
        console.log (jsonobj.costumes[i].costumeName);
        console.log (jsonobj.costumes[i].imageUrl);
        console.log (jsonobj.costumes[i].soundUrl);
    }
}); 
</script>

<script>
    function setOptions(flashcardsYes,flashcardsNo1,flashcardsNo2,flashcardsNo3) {
        var options = shuffleArray([1,2,3,4]);
        \$(\"#test-options li:nth-of-type(\" + options[0] + \") span\").text(flashcardsYes).removeClass(\"correct incorrect\").addClass(\"correct\");
        \$(\"#test-options li:nth-of-type(\" + options[1] + \") span\").text(flashcardsNo1).removeClass(\"correct incorrect\").addClass(\"incorrect\");
        \$(\"#test-options li:nth-of-type(\" + options[2] + \") span\").text(flashcardsNo2).removeClass(\"correct incorrect\").addClass(\"incorrect\");
        \$(\"#test-options li:nth-of-type(\" + options[3] + \") span\").text(flashcardsNo3).removeClass(\"correct incorrect\").addClass(\"incorrect\");
    }
    function shuffleArray(array) {
      for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
       }
    return array;
    }
</script>

" ;  // end output creation. Note that we end the document ready function early in this mode to place 2 scripts available to <script> tags within .div docs.

   return $output;
}


                                
function buildSoundUrl ($name,$path) {
    $soundUrl = "missing";
    $systemAudio = dirname(__FILE__) . "/../audio/";
    $webpath_me = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__));
    $webpath_me = str_replace('/includes', '', $webpath_me);
    $systemAudio2 = $webpath_me . "/audio/";
    if (file_exists($path . "/" . $name . ".mp3")) { $soundUrl = $path . "/" . $name . ".mp3";}
        elseif (file_exists($path . "/" . $name . ".wav")) {$soundUrl = $path . "/" . $name . ".wav";}
                elseif (file_exists($path . "/" . $name . ".ogg")) {$soundUrl = $path . "/" . $name . ".ogg";}
                    elseif (file_exists($systemAudio . $name . ".mp3")) { $soundUrl = $systemAudio2 . $name . ".mp3";}
        		elseif (file_exists($systemAudio . $name . ".wav")) {$soundUrl = $systemAudio2 . $name . ".wav";}
                		elseif (file_exists($systemAudio . $name . ".ogg")) {$soundUrl = $systemAudio2 . $name . ".ogg";}
                   			 else { $soundUrl = "#";}  //we need an empty sound file here.
    return ($soundUrl);
}

function form_validation_js() {
	$output = "";
	return $output;
}
