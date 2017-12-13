<?php namespace pg_flashcards; ?>
<?php

function build_questionCard($defaults_array,$form_id) {
	// By forcing $form_id to be passed, we have visibility to it in all calling programs which may need it for jquery.
	$output = '';
	$collection_options = get_Card_dirs();      // this needs to be written to return all directories with valid card files.
	$parent_options = get_Parent_dirs();        // this needs to be written to return all directories with no card files.
	$collection_input = build_selectbox($collection_options,$form_id, 'collection',$defaults_array["collection"]);
	$parent_input = build_selectbox($parent_options,$form_id,'parent',$defaults_array["parent"]);
	$output .= '
	<div id="questionCard" class="white-popup" style="display:none">
		<form id="' . $form_id . '" method="Post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
		<fieldset id="collection-info">
			<label class="form-left">Collection: ' . $collection_input . '</label>
			<label class="form-left">Parent: ' . $parent_input . '</label>
			<label class="form-left">Group: <input type="text" id="qform_group" name="group" maxlength="50" class="form-short" value="'. $defaults_array["group"] . '"></label>
			<label class="form-left">Collection: <input type="text" id="qform_ncollection" name="ncollection" maxlength="50" class="form-short" value="'. $defaults_array["ncollection"] . '"></label>
		</fieldset>
		
		<fieldset id="question-info">
		  <label class="form-left">Card Name: <input type="text" id="qform_filename" name="filename" maxlength="50" class="form-short" value="' . $defaults_array["filename"] . '"></label><br>
		  <label class="form-left">Question: <input type="text" id="qform_question" name="question" class="form-long" maxlength="150" value="' . $defaults_array["question"] . '"></label><br>
		  <label class="form-left">Answer: <input type="text" id="qform_answer" name="answer" class="form-long" maxlength="150" value="' . $defaults_array["answer"] . '"></label><br>
		  <label class="form-left">No-1: <input type="text" id="qform_no1" name="no1" class="form-long" maxlength="150" value="' . $defaults_array["no1"] . '"></label><br>
		  <label class="form-left">No-2: <input type="text" id="qform_no2" name="no2" class="form-long" maxlength="150" value="' . $defaults_array["no2"] . '"></label><br>
		  <label class="form-left">No-3: <input type="text" id="qform_no3" name="no3" class="form-long" maxlength="150" value="' . $defaults_array["no3"] . '"></label><br>
		</fieldset>

		<fieldset id="hidden-info">
			<input type="hidden" name="ext" id="ext" value="div">
			<input type="submit" name="QbtnSubmit" id="QbtnSubmit" value="Submit">
		</fieldset>
		</form>
	</div> ';
	return $output;
}

function build_imageCard($defaults_array,$form_id) {
	// By forcing $form_id to be passed, we have visibility to it in all calling programs which may need it for jquery.
        $output = '';
        $collection_options = get_Card_dirs();      // this needs to be written to return all directories with valid card files.
        $parent_options = get_Parent_dirs();        // this needs to be written to return all directories with no card files.
        $collection_input = build_selectbox($collection_options,$form_id, 'collection',$defaults_array["collection"]);
        $parent_input = build_selectbox($parent_options,$form_id,'parent',$defaults_array["parent"]);
        $output .= '
        <div id="imageCard" class="white-popup" style="display:none">
                <form id="' . $form_id . '" method="Post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" enctype="multipart/form-data">
                <fieldset id="i-collection-info">
                        <label class="form-left">Collection: ' . $collection_input . '</label>
                        <label class="form-left">Parent: ' . $parent_input . '</label>
                        <label class="form-left">Group: <input type="text" id="iform_group" name="group" maxlength="50" class="form-short" value="'. $defaults_array["group"] . '"></label>
                        <label class="form-left">Collection: <input type="text" id="iform_ncollection" name="ncollection" maxlength="50" class="form-short" value="'. $defaults_array["ncollection"] . '"></label>
                </fieldset>

                <fieldset id="i-file-info">
                  <label class="form-left">Card Name (Answer): <input type="text" id="cform_filename" name="filename" maxlength="50" class="form-short" value="' . $defaults_array["filename"] . '"></label><br>
                  <label class="form-left">Image File Name: <input type="file" name="imagename" id="iform_filename" value=""></label><br>
                  <label class="form-left">Audio File Name: <input type="file" name="audiofile" id="iform_audiofile" value=""></label><br>
                </fieldset>

                <fieldset id="hidden-info">
                        <input type="hidden" name="ext" id="ext" value="png">
                        <input type="submit" name="IbtnSubmit" id="IbtnSubmit" value="Submit">
                </fieldset>
                </form>
        </div> ';
        return $output;

}

function build_comboCard($defaults_array,$form_id) {
        // By forcing $form_id to be passed, we have visibility to it in all calling programs which may need it for jquery.
        $output = '';
        $collection_options = get_Card_dirs();      // this needs to be written to return all directories with valid card files.
        $parent_options = get_Parent_dirs();        // this needs to be written to return all directories with no card files.
        $collection_input = build_selectbox($collection_options,$form_id, 'collection',$defaults_array["collection"]);
        $parent_input = build_selectbox($parent_options,$form_id,'parent',$defaults_array["parent"]);
        $output .= '
        <div id="comboCard" class="white-popup" style="display:none">
                <form id="' . $form_id . '" method="Post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" enctype="multipart/form-data">
                <fieldset id="c-collection-info">
                        <label class="form-left">Collection: ' . $collection_input . '</label>
                        <label class="form-left">Parent: ' . $parent_input . '</label>
                        <label class="form-left">Group: <input type="text" id="cform_group" name="group" maxlength="50" class="form-short" value="'. $defaults_array["group"] . '"></label>
                        <label class="form-left">Collection: <input type="text" id="cform_ncollection" name="ncollection" maxlength="50" class="form-short" value="'. $defaults_array["ncollection"] . '"></label>
                </fieldset>

                <fieldset id="c-file-info">
                  <label class="form-left">Image File Name: <input type="file" name="imagename" id="cform_filename" value=""></label><br>
                  <label class="form-left">Audio File Name: <input type="file" name="audiofile" id="cform_audiofile" value=""></label><br>
                </fieldset>
	
		<fieldset id="c-question-info">
                  <label class="form-left">Card Name: <input type="text" id="qform_filename" name="filename" maxlength="50" class="form-short" value="' . $defaults_array["filename"] . '"></label><br>
                  <label class="form-left">Question: <input type="text" id="qform_question" name="question" class="form-long" maxlength="150" value="' . $defaults_array["question"] . '"></label><br>
                  <label class="form-left">Answer: <input type="text" id="qform_answer" name="answer" class="form-long" maxlength="150" value="' . $defaults_array["answer"] . '"></label><br>
                  <label class="form-left">No-1: <input type="text" id="qform_no1" name="no1" class="form-long" maxlength="150" value="' . $defaults_array["no1"] . '"></label><br>
                  <label class="form-left">No-2: <input type="text" id="qform_no2" name="no2" class="form-long" maxlength="150" value="' . $defaults_array["no2"] . '"></label><br>
                  <label class="form-left">No-3: <input type="text" id="qform_no3" name="no3" class="form-long" maxlength="150" value="' . $defaults_array["no3"] . '"></label><br>
                </fieldset>

                <fieldset id="hidden-info">
                        <input type="hidden" name="ext" id="ext" value="div">
                        <input type="submit" name="CbtnSubmit" id="CbtnSubmit" value="Submit">
                </fieldset>
                </form>
        </div> ';
        return $output;

}

function build_csvCard($defaults_array,$form_id) {
        // By forcing $form_id to be passed, we have visibility to it in all calling programs which may need it for jquery.
        $output = '';
        $collection_options = get_Card_dirs();      // this needs to be written to return all directories with valid card files.
        $parent_options = get_Parent_dirs();        // this needs to be written to return all directories with no card files.
        $collection_input = build_selectbox($collection_options,$form_id, 'collection',$defaults_array["collection"]);
        $parent_input = build_selectbox($parent_options,$form_id,'parent',$defaults_array["parent"]);
        $output .= '
        <div id="csvCard" class="white-popup" style="display:none">
                <form id="' . $form_id . '" method="Post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" enctype="multipart/form-data">
                <fieldset id="csv-collection-info">
                        <label class="form-left">Collection: ' . $collection_input . '</label>
                        <label class="form-left">Parent: ' . $parent_input . '</label>
                        <label class="form-left">Group: <input type="text" id="csvform_group" name="group" maxlength="50" class="form-short" value="'. $defaults_array["group"] . '"></label>
                        <label class="form-left">Collection: <input type="text" id="csvform_ncollection" name="ncollection" maxlength="50" class="form-short" value="'. $defaults_array["ncollection"] . '"></label>
                </fieldset>

                <fieldset id="csv-file-info">
                  <label class="form-left">CSV File Name: <input type="file" name="csvname" id="iform_filename" value=""></label><br>
                </fieldset>

                <fieldset id="hidden-info">
                        <input type="hidden" name="ext" id="ext" value="div">
                        <input type="hidden" name="delimiter" id="delimiter" value="~">
                        <input type="submit" name="CSVbtnSubmit" id="CSVbtnSubmit" value="Submit">
                </fieldset>
                </form>
        </div> ';
        return $output;

}

function build_selectbox($option_array,$form_id,$element_name,$default) {
	$output = '';
	$output .= '<select name="' . $element_name . '" id="qform_' . $element_name . '" class="form-short" form_id="' . $form_id . '">';
	foreach($option_array as $value) { 
		$dirname = html_collection($value);
    		if ($value == $default) { $output .= '<option value="' . $value . '" selected="seleceted">' . $dirname . '</option>' ; }
    		else { $output .= '<option value="' . $value . '">' . $dirname . '</option>' ; }
  	}
	$output .= '</select>';
	return $output;
}

function html_collection($collection) {

		$dirname = basename(dirname($collection));
		$collection = str_replace("./", "", $collection);
		$collection = str_replace($dirname."/.", strtoupper($dirname), $collection);
		$collection = str_replace("/", "->", $collection);
		return $collection;
}
// Scratchpad, erase at production.
// Insert this method in content_shortcuts		<form id="questionCardForm" method="Post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
//		<form id="questionCardForm" method="Post" action="cardUpload.php" onsubmit="javascript: return validate_questionCard();">
//			<label class="form-left">Collection: <input type="text" id="qform_collection" name="collection" maxlength="50" class="form-short" value="' . $defaults_array["collection"] . '"></label>
//			<label class="form-left">Parent: <input type="text" id="qform_parent" name="parent" maxlength="50" class="form-short" value="' . $defaults_array["parent"] . '"></label>

?>
