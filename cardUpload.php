<?php namespace pg_flashcards; ?>
<?php require_once(dirname(__FILE__) . "/includes/functions.php"); ?>
<?php require_once(dirname(__FILE__) . "/includes/session.php"); ?>
<?php require_once(dirname(__FILE__) . "/includes/validation_functions.php"); ?>
<?php require_once(dirname(__FILE__) . "/includes/forms.php"); ?>
<?php ob_start();                             // this allows us to redirect a bit later if we wish based on auth or validation fail, needs offsetting ob_end_flush() ?>
<?php include(dirname(__FILE__) . "/login.php"); ?>
<?php $_REQUEST = array_merge($_POST, $_GET); // this places GET with priority over post if both exist, then we can use getRequest(key,fallback) but drops cookie info  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="images/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="images/manifest.json">
	<link rel="mask-icon" href="images/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#ffffff">
	<link rel="stylesheet" type="text/css" media="all" href="css/whiteboard.css" />
	<script src="js/1.9.1/jquery.min.js"></script>
	<link rel="stylesheet" href="./js/magnific/magnific-popup.css" />
	<script src="./js/magnific/jquery.magnific-popup.min.js"></script>
</head>

<!-- TODO:      #'ered items first.
		add ability to upload zip to create a new subdirectory.	(unpack in tmp directory, check if image, move to destination.)
		add ability to upload csv of questions and answers.  (for each line, run the tests, create the cards.)
		improve error handling throughout.
		error trap file creation in create_cardP, creat_cardI, and create_cardC.
		question and answer can't be blank on cardP, or create_cardC.
	        create validation functions in jquery on client side. filename, group,collection no spcialchars, filename distinct in collection. Only show parent,group, collectionname when collection goes to new.
		add form code to use errors array to set class="errors" on form elements.  Simple jquery or no? yes, set id on errors div and then select each subsequent id.  for each this .id, toggle class form-this.id. 
		allow - in collection name.
		add file type check.
		audio file upload code needs to be checked and written.
		magnific the errors.
		11) Multilingual Error output display should reference images for entry boxes if possible to be multilingual.
		12) Add class array to distinguish when validation error occurs and send to buildform to insert and highlight where errors exist.
-->

<body>
    <a href="/" id="pg-rachel"></a>
    <div class="menubar">
      <a href="whiteboard.php">Flashcards [HOME]</a>
    </div>
    <div id="main">
            <div id="container" style="display:block">
		<button id="Icard_button" style="float:left" class="formButton">Add Image Card</button>
		<button id="Qcard_button" style="float:left" class="formButton">Add Question Card</button>
		<button id="Ccard_button" style="float:left" class="formButton">Add Combo Card</button>
		<button id="CSVcard_button" style="float:left" class="formButton">Add Cards from CSV</button>

 <?php
$merged_array = array();
$defaults_array = array("collection" => "./flashcards", "ncollection" => "", "filename" => "","imagename" => "", "parent" => "","group" => "","question" => "","answer" => "","no1" => "","no2" => "","no3" => "");
$output = "";
$permittedModes = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {                                     // only process form on POST, GET variables can set defaults to form.

       	$collection = getRequest('collection','./flashcards'); // use passed dir from GET if passed
	$ncollection = getRequest('ncollection','');
        $parent = getRequest('parent',''); // use passed dir from GET if passed
        $group = getRequest('group',''); // use passed dir from GET if passed
	$group = remove_spaces($group);
	$question = getRequest('question','');
	$answer = getRequest('answer','');
	$no1 = getRequest('no1','');
	$no2 = getRequest('no2','');
	$no3 = getRequest('no3','');
	if($collection === 'new') { $collection = $parent . '/' . $group . '/' . $ncollection . '/.';}
	$filename = getRequest('filename','');
	$filename = remove_spaces($filename);
	
	if(isset($_POST['QbtnSubmit'])) {                                        // each button form has its own validation logic.
		$ext = getRequest('ext','div');
		$filename .= '.' . $ext;

		// We should do all validation functions here before checking errors.
		uploadCheck_filename($collection,$filename);
		uploadCheck_spec_chars('collection',$collection, array('.','/'));
		uploadCheck_spec_chars('group',$group);
		uploadCheck_spec_chars('filename',$filename, array('.'));
		if(empty($errors)) { 
			$permittedModes = uploadCheck_path($collection,$parent,$group); 
		}
	
		if (empty($errors)) {
		// This is where we will process the form items
			if($permittedModes["card_creation"]==="yes") {
				$filename = '.' . str_replace('.','',$collection) . $filename;
				$output .= create_cardP($filename,$question,$answer,$no1,$no2,$no3);
			}
			$merged_array = array_merge($defaults_array,$_REQUEST);   // question here is will we have GET and POST needs, then $_REQUEST
		} else {							  // since we have errors, take post items as overriding defaults and present form.
			$output .= form_errors($errors);
			$merged_array = array_merge($defaults_array,$_REQUEST);   // question here is will we have GET and POST needs, then $_REQUEST
	  	}


	} elseif(isset($_POST['IbtnSubmit'])) {

		$imagename = $_FILES['imagename']['name'];
		$tmp_imagename = $_FILES['imagename']['tmp_name'];
		$audiofile = $_FILES['audiofile']['name'];
		$info = pathinfo($_FILES['imagename']['name']);
		if(isset($info['extension'])) { $ext = $info['extension']; }
			else { $ext = getRequest('ext','png'); }
		$filename .= '.' . $ext;

		uploadCheck_imagename($imagename,$filename,$tmp_imagename);
		uploadCheck_audiofile($audiofile,$filename);
		uploadCheck_filename($collection,$filename);
                uploadCheck_spec_chars('collection',$collection, array('.','/'));
                uploadCheck_spec_chars('group',$group);
                uploadCheck_spec_chars('filename',$filename, array('.'));
		if(empty($errors)) {
                        $permittedModes = uploadCheck_path($collection,$parent,$group);
                }

                if (empty($errors)) {
                // This is where we will process the form items
                        if($permittedModes["card_creation"]==="yes") {
                                $filename = '.' . str_replace('.','',$collection) . $filename;
                                $output .= create_cardI($filename,$tmp_imagename);
                        }
                        $merged_array = array_merge($defaults_array,$_REQUEST);   // question here is will we have GET and POST needs, then $_REQUEST
                } else {                                                          // since we have errors, take post items as overriding defaults and present form.
                        $output .= form_errors($errors);
                        $merged_array = array_merge($defaults_array,$_REQUEST);   // question here is will we have GET and POST needs, then $_REQUEST
                }
   	  } elseif(isset($_POST['CbtnSubmit'])) {

		$imagename = $_FILES['imagename']['name'];
                $tmp_imagename = $_FILES['imagename']['tmp_name'];
		$type_imagename = $_FILES['imagename']['type'];
                $audiofile = $_FILES['audiofile']['name'];
                $info = pathinfo($_FILES['imagename']['name']);
                $ext = getRequest('ext','div'); 
                $filename .= '.' . $ext;

                uploadCheck_imagename($imagename,$filename,$tmp_imagename);
                uploadCheck_audiofile($audiofile,$filename);
                uploadCheck_filename($collection,$filename);
                uploadCheck_spec_chars('collection',$collection, array('.','/'));
                uploadCheck_spec_chars('group',$group);
                uploadCheck_spec_chars('filename',$filename, array('.'));
                if(empty($errors)) {
                        $permittedModes = uploadCheck_path($collection,$parent,$group);
                }

                if (empty($errors)) {
                // This is where we will process the form items
                        if($permittedModes["card_creation"]==="yes") {
                                $filename = '.' . str_replace('.','',$collection) . $filename;
                                $output .= create_cardC($filename,$tmp_imagename,$type_imagename,$question,$answer,$no1,$no2,$no3);
                        }
                        $merged_array = array_merge($defaults_array,$_REQUEST);   // question here is will we have GET and POST needs, then $_REQUEST
                } else {                                                          // since we have errors, take post items as overriding defaults and present form.
                        $output .= form_errors($errors);
                        $merged_array = array_merge($defaults_array,$_REQUEST);   // question here is will we have GET and POST needs, then $_REQUEST
                }
	    } elseif(isset($_POST['CSVbtnSubmit'])) {                                        // each button form has its own validation logic.
                $ext = getRequest('ext','div');
                $tmp_csvname = $_FILES['csvname']['tmp_name'];

		if (($handle = fopen("{$tmp_csvname}", "r")) !== FALSE) {
			$fileadder = 0;
			$delimeter = getRequest('delimiter','~');
			$question = '';
			$answer = '';
			$no1 = '';
			$no2 = '';
			$no3 = '';
                	while (($data = fgetcsv($handle, 1000, "{$delimeter}")) !== FALSE) {
				$fileadder += 1;
				$question = $data[0];
				$answer = $data[1];
				$no1 = $data[2];
				$no2 = $data[3];
				$no3 = $data[4];
                	// We need a name for the file...this could be improved.
                	$filename = 'CSV' . $fileadder . '.' . $ext;
                	uploadCheck_filename($collection,$filename);
                	uploadCheck_spec_chars('collection',$collection, array('.','/'));
                	uploadCheck_spec_chars('group',$group);
                	uploadCheck_spec_chars('filename',$filename, array('.'));
                	if(empty($errors)) {
                        	$permittedModes = uploadCheck_path($collection,$parent,$group);
                	}

                	if (empty($errors)) {
                	// This is where we will process the form items
                        	if($permittedModes["card_creation"]==="yes") {
                                	$filename = '.' . str_replace('.','',$collection) . $filename;
                                	$output .= create_cardP($filename,$question,$answer,$no1,$no2,$no3);
					$question = '';
                        		$answer = '';
                        		$no1 = '';
                        		$no2 = '';
                        		$no3 = '';
                        	}
                        	$merged_array = array_merge($defaults_array,$_REQUEST);   // question here is will we have GET and POST needs, then $_REQUEST
                	} else {                                                          // since we have errors, take post items as overriding defaults and present form.
                        	$output .= form_errors($errors);
                        	$merged_array = array_merge($defaults_array,$_REQUEST);   // question here is will we have GET and POST needs, then $_REQUEST
                	}
	       		}
             	fclose($handle);
             	}
	    }
}else {
	// establish default form values from GET variables if present.

	$merged_array = array_merge($defaults_array,$_GET);

 }

$output .= build_questionCard($merged_array,'questionCardForm');
$output .= build_imageCard($merged_array,'imageCardForm');
$output .= build_comboCard($merged_array,'comboCardForm');
$output .= build_csvCard($merged_array,'csvCardForm');
echo $output ;
        
?>
</div>
          <img id="teacher" src="images/owlTeacher.png" />
    </div>
</body>
    
<script>
$(document).ready(function() {

$('#Qcard_button').click(function(){
	if ($('#questionCard').length) {
                $.magnificPopup.open({
                        items: {
                                src: '#questionCard' 
                        },
                        type: 'inline'
                  });
            $('#questionCard').show();
        }
});

$('#Icard_button').click(function(){
        if ($('#imageCard').length) {
                $.magnificPopup.open({
                        items: {
                                src: '#imageCard'
                        },
                        type: 'inline'
                  });
            $('#imageCard').show();
        }
});

$('#Ccard_button').click(function(){
        if ($('#comboCard').length) {
                $.magnificPopup.open({
                        items: {
                                src: '#comboCard'
                        },
                        type: 'inline'
                  });
            $('#comboCard').show();
        }
});

$('#CSVcard_button').click(function(){
        if ($('#csvCard').length) {
                $.magnificPopup.open({
                        items: {
                                src: '#csvCard'
                        },
                        type: 'inline'
                  });
            $('#csvCard').show();
        }
});

<?php { 
        // $supp_js = form_validation_js();
	// echo $supp_js;
	echo "});</script>";
	}  
?>
        
    
</html>
<?php ob_end_flush(); ?>

