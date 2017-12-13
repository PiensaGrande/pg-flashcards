<?php namespace pg_flashcards; ?>
<?php

// Mostly form validation functions.  Each function that may dedect errors has to declare errors as global.
// Then write to the error array with .= in case a field has more than one error.
// example - $errors[$field] .= fieldname_as_text($field) . " can't be blank";

$errors = array();

function add_error($key,$error_string) {
	// TODO: figure out if multilingual error reporting should be handled here.  If so, consider extending to include error_code.
	global $errors;
	if(isset($errors[$key])) { $errors[$key] .= $error_string; }
		else { $errors[$key] = $error_string; }
}

function form_errors($errors=array()) {
                $output = "";
                if (!empty($errors)) {
                  $output .= "<div class=\"errors\">";
                  $output .= "Please fix the following errors:";
                  $output .= "<ul>";
                  foreach ($errors as $key => $error) {
                    if ($error !== '') {
				$output .= "<li>";
                                $output .= htmlentities($key) . " - " . htmlentities($error);
                                $output .= "</li>";
		    }
                  }
                  $output .= "</ul>";
                  $output .= "</div>";
                }
                return $output;
        }

function fieldname_as_text($fieldname) {
  $fieldname = str_replace("_", " ", $fieldname);
  $fieldname = ucfirst($fieldname);
  return $fieldname;
}

function remove_spaces($fieldname) {
  $fieldname = str_replace(" ", "_", $fieldname);
  return $fieldname;
}

// * presence
// use trim() so empty spaces don't count
// use === to avoid false positives
// empty() would consider "0" to be empty
function has_presence($value) {
	return isset($value) && $value !== "";
}

function validate_presences($required_fields) {
  global $errors;
  foreach($required_fields as $field) {
    $value = trim($_POST[$field]);
  	if (!has_presence($value)) {
		$error_string = fieldname_as_text($field) . " can't be blank" ;
  		add_error($field,$error_string);
  	}
  }
}

// * string length
// max length
function has_max_length($value, $max) {
	return strlen($value) <= $max;
}

function validate_max_lengths($fields_with_max_lengths) {
	global $errors;
	// Expects an assoc. array
	foreach($fields_with_max_lengths as $field => $max) {
		$value = trim($_POST[$field]);
	  if (!has_max_length($value, $max)) {
	    $error_string = fieldname_as_text($field) . " is too long";
	    add_error($field,$error_string);
	  }
	}
}

// * inclusion in a set
function has_inclusion_in($value, $set) {
	return in_array($value, $set);
}

function has_spec_chars($x,$excludes=array()){
    if (is_array($excludes)&&!empty($excludes)) {
        foreach ($excludes as $exclude) {
            $x=str_replace($exclude,'',$x);        
        }    
    }    
    if (preg_match('/[^a-z0-9_ ]+/i',$x)) {
        return true;        
    }
    return false;
}

function uploadCheck_spec_chars($field,$x,$excludes=array()) {
	global $errors;
	if (has_spec_chars($x, $excludes)) { 
		$error_string = $x . " No special chars, please.";
		add_error("{$field}",$error_string) ; 
	}
}

function uploadCheck_path($collection,$parent,$group) {
        // We don't want to mix cards and directories so we restrict new collection when cards are present, new groups if parent doesn't exist, and set errors.
        // If collection doesn't exist but the parent does and is without cards, create collection and permit everything.
        // Form UI should make selection of new collection choose parent from a drop down list of directories with subdirectories.
        // Possibly presenting Parent, Group Name, Collection Name.  If groupname exists, it is always a new creation.
        // then see if subdirectory creation is also permitted (directory exists, no cards)
	global $errors;

        $permitted_modes = array();
        if($parent !== '') { $cards_parent = glob($parent . '/*.{jpg,png,gif,svg,div}', GLOB_BRACE); } else { $cards_parent = '';}

        if ($group === "") {                                             // no group request, but possible new collection request
                if (!(file_exists($collection)) && (is_writable($parent))) {
                        if (empty($cards_parent)) {
                        	if (!(file_exists($collection))) { mkdir($collection,0755,true); }
                                $permitted_modes["card_creation"] = "yes" ;
				$_REQUEST["collection"] = $collection ;                          // make sure next form load defaults to created directory
				$_REQUEST["parent"] = '';
				$_REQUEST["ncollection"] = '';
                        }
		}
        } else {                                                         // we have a group request.
                if (empty($cards_parent) && (is_writable($parent))){
			if (!(file_exists($parent . '/' . $group))) { mkdir($parent . '/' . $group,0755,true); }
                        if (!(file_exists($collection))) { mkdir($collection,0755,true); }
                        $permitted_modes["card_creation"] = "yes" ;
			$_REQUEST["collection"] = $collection ;
			$_REQUEST["ncollection"] = '' ;
			$_REQUEST["group"] = '' ;
			$_REQUEST["parent"] = '' ;
                } else {
                        // report errors for this situation which is group create request but can't.
                        if (!empty($cards_parent)) {
                                add_error("parent","can't create group where cards exist.  Move existing cards into subgroup first.");
                        } else { if($parent !== '') { add_error("parent","Location to place group " . $parent . "doesn't exist for writing."); }}
                }
        }

        $collectionDirs = glob($collection . '/*' , GLOB_ONLYDIR);
	if((is_writable($collection)) && (empty($collectionDirs))) {
                $permitted_modes["card_creation"] = "yes" ;
        } else { if(!empty($collectionDirs)) {
                        add_error("collection","can't add cards to a group directory" . $collection) ;
                 } else {
                        add_error("collection","collection contains directories which don't exist." . $collection . "  Use parent and group to create new directories.") ;
                   }
          }

        return $permitted_modes;
}

function uploadCheck_filename($collection,$filename){
	// check for distinct filename
	$full_name = $collection . "/" . $filename;
	if(file_exists($full_name)) {
		add_error("filename","Filename exists, please choose a new one.");
	}
}

function uploadCheck_imagename($imagename,$filename,$tmpimage){
        // check imagefile validity
	if($imagename==='') { 
		add_error("imagename","You must choose an image file.");
		return;
	} elseif(is_image_or_svg($tmpimage)) {
		return true;
	} else {
        	add_error("imagename","image is not an image..." . $imagename);
	}
}

function uploadCheck_audiofile($audiofile,$filename){
        // check audiofile validity
        // add_error("audiofile","audiofile name is..." . $audiofile);
}

function is_image($path)
{
    $a = getimagesize($path);
    $image_type = $a[2];

    if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
    {
        return true;
    }
    return false;
}

function is_image_or_svg($path)
{
    $a = getimagesize($path);
    $image_type = $a[2];

    $handle = finfo_open(FILEINFO_MIME);
    $mime_type = strtolower(finfo_file($handle,$path));
    $pos = strpos($mime_type,'svg');
    if($pos === false) {
    	if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP))) {
                return true;
            }
    }
    else {
	  return true;
    } 
    return false;
}
?>
